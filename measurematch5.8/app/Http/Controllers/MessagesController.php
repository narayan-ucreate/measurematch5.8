<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use Session;
use Validator;
use Carbon\Carbon;
use App\Model\User;
use App\Model\BuyerProfile;
use App\Model\Message;
use App\Model\Communication;
use App\Model\Contract;
use App\Model\PostJob;
use App\Model\UsersCommunication;
use App\Model\ReferralExpert;
use App\Model\ReferralCouponCode;
use App\Model\CouponAppliedByExpert;
use App\Model\PromotionalCoupon;
use App\Model\PromotionalCouponUsageDetail;
use App\Model\UserProfile;
use App\Model\Invoice;
use App\Model\OutboundEmailLog;
use App\Model\Deliverable;
use App\Model\PostmarkInbound;
use \App\Components\Email;
use App\Components\Common;
use \App\Components\ServicePackageComponent;
use \App\Components\SegmentComponent;
use \PDF;
use App\Model\JobsSkill;
use App\Model\TemporaryProposal;
use App\Model\CountryVatDetails;
use App\Model\ContractTerm;
use App\Model\BusinessInformation;
use App\Model\ServicePackage;

class MessagesController extends Controller {
    const INVOICE = 'invoice';
    
    public function __construct() {
        $this->middleware('auth', ['except' => ['postmarkInbound', 'emailSeenByUser']]);
    }
    public function buyerMessageView(Request $request) {
        try {
            if (!in_array(Auth::user()->user_type_id, [config('constants.BUYER'), config('constants.VENDOR')]) || ($request->type!= config('constants.PROJECT') &&  $request->type!= config('constants.SERVICE_PACKAGE'))){
                return view('errors.404');
            }
            $project_approved_status="";
            $rebook_project = false;
            if($request->type==config('constants.PROJECT')){
                $project_approved_status = (new PostJob)->checkProjectPublishStatus(Auth::user()->id ,$request->id);
                if(!$project_approved_status || empty($project_approved_status)) 
                  return view('errors.404');
                $rebook_project = $project_approved_status->rebook_project;
                $project_approved_status =  (_count($project_approved_status))? $project_approved_status->publish : null;

               $accepted_contract_id =  (new PostJob())->getJobInfo(Auth::user()->id, $request->id)->accepted_contract_id;
            }
            $user_type = Auth::user()->user_type_id;
            $id = $request->id;
            $type = $request->type;
            $user_list = Communication::expertList(Auth::user()->id, $request->type, $request->id);
               if(!_count($user_list)) 
                return view('message.buyer_empty_messages', compact('project_approved_status','id','type','accepted_contract_id','user_type', 'user_list'));
            $countries = (new CountryVatDetails)->getAllCountryVatDetails();
            return view('message.messages', compact('project_approved_status','id','type','accepted_contract_id','user_type', 'user_list', 'countries', 'rebook_project'));
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }
    
    public function viewProjectDetail(Request $request)
    {       
        try
        {
            $form_data = $request->all();
            $buyer_id = $form_data['buyer_id'] ?? Auth::user()->id;
            $job_id = $form_data['post_job_id'];
            $post_job = new PostJob();
            $job_info = $post_job->getJobInfo($buyer_id, $job_id);
            $deliverables = (new Deliverable)->getProjectDeliverables($job_id);
            $all_deliverables = null;
            foreach($deliverables as $key => $deliverable) {
              $all_deliverables .= $deliverable->deliverable.'|';
            }
            $buyer_information = (new BuyerProfile)->getCompanyNameByBuyerId($buyer_id);
            if($job_info->rebook_project == config('constants.TRUE'))
            {
                $data = view('message.popups.rebooked_project', compact(
                    'job_info',
                    'deliverables',
                    'all_deliverables',
                    'buyer_information',
                    'buyer_id')
                    )->render();
                return ['success' => 1, 'content' => $data];            
            }
            
            $skills = (new JobsSkill)->getProjectSkillsAndToolsByProjectId($job_id);
            $buyer_information = (new BuyerProfile)->getCompanyNameByBuyerId($buyer_id);
            $office_location = (!empty($job_info->office_location)) ? $job_info->office_location : $buyer_information->office_location;
            $data = view('message.popups.project_detail', compact('job_info', 'deliverables','all_deliverables', 'skills', 'buyer_information','office_location','job_id'))->render();
            return ['success' => 1, 'content' => $data];
        }
        catch (\Exception $exc)
        {
            return ['success' => 0, 'data' => "Error:Oops! something went wrong."];
        }
    }

    public function expertMessageView() {
        try {
            if (Auth::user()->user_type_id != config('constants.EXPERT')) {
                return view('errors.404');
            }
            $user_type = Auth::user()->user_type_id;
            $request = \Request::all();
            $proposal_sent = $request['success'] ?? '';
            $user_list = Communication::BuyerList(Auth::user()->id);
            return view('message.messages', compact('user_type', 'user_list', 'proposal_sent'));
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    public function messageDetails(Request $request) {
        try {
            if ($request->communication_id) {
                $communication = Communication::find($request->communication_id);
                if (!$communication->status && $communication->type == config('constants.PROJECT') && \Auth::user()->user_type_id === config('constants.USER_TYPE.BUYER')) {
                    return ['preview_profile' => true];
                }
                $options = [
                    'type' => $request->type ?? '',
                    'offset' => $request->offset ?? ''
                ];
                $view_data = [];
                if($communication->type == config('constants.PROJECT'))
                {
                    $post_job = (new PostJob)->getSelectedFields (['job_title', 'description'], ['id' => $communication->job_post_id]);
                    $view_data['project_details'] = $post_job;
                }
                $data = Message::messageData($request->communication_id, $options);
                $latest_contract = $this->latestContractInChatPanel($request->communication_id);
                if (!empty($data)) {
                    if ($options['type'] !== '' && is_array($data->toArray())) {
                        $list = Common::filterMessageDate($data);
                        return array_merge($view_data, ['success' => 1, 'data' => $list, 'latest_contract' => $latest_contract]);
                    }
                    $view_data = ['success' => 1, 'data' => $data, 'latest_contract' => $latest_contract];
                    return array_merge($view_data, ['success' => 1, 'data' => $data, 'latest_contract' => $latest_contract]);
                }
            } else {
                return ['success' => 0, 'message' => 'Incomplete parameters'];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }
    private function latestContractInChatPanel($communication_id) {
        $latest_contract = (new Contract)->getLatestContractDetails($communication_id);
        if (_count($latest_contract)) {
            $job_end_date = !empty($latest_contract->job_end_date) ? date('j M, Y', strtotime($latest_contract->job_end_date)) : '';
            if (isset($latest_contract->subscription_type) && $latest_contract->subscription_type == 'monthly_retainer') {
                $job_end_date = 'Monthly Retainer (cancel anytime)';
            }
            $latest_contract->job_start_date = date('j M, Y', strtotime($latest_contract->job_start_date));
            $latest_contract->job_end_date = $job_end_date;
            $latest_contract->rate = convertToCurrencySymbol($latest_contract->rate_variable) . number_format($latest_contract->rate);
            $latest_contract->status = (!empty($latest_contract->parent_contract_id) ? config('constants.ACCEPTED') : $latest_contract->status );
            return $latest_contract;
        }
    }

    public function userProfileDetails(Request $request)
    {
        if (empty(Auth::user()))
            return redirect('/login');
        try
        {
            $user_id = $request->user_id;
            $user_type = $request->user_type;
            $communication_id = $request->communication_id;
            $communication = (new Communication())->getCommunicationInfo($request->communication_id);
            if ($user_type == config('constants.BUYER'))
            {
                $user_profile = BuyerProfile::getBuyerDetail($user_id);
                $viewed_by = config('constants.EXPERT');
            }
            else
            {
                if ($communication->status == 0 && $communication->job_post_id > 0)
                {
                    $expert_profile = (new UserProfile)->userProfileWithAssociatedData($user_id);
                    $user_name = $expert_profile->expertBasicInfo->name;
                    $image_url = $expert_profile->profile_picture;
                    $last_message = (new Communication())->getLastMessageOfSpecificCommunication($request->communication_id);
                    $business_detail = (new BusinessInformation())->getUserBusinessInformation(Auth::user()->id);
                    $vat_status = (!isset($business_detail->businessDetails->vat_status) || is_null($business_detail->businessDetails->vat_status)) ? 0 : 1;
                    $user_setting = json_decode(Auth::user()->settings, 1);
                    $vat_country_confirmation_pop_up = isset($user_setting['vat_country_confirmation_pop_up']) ? config('constants.TRUE') : config('constants.NOT');
                    $countries = (new CountryVatDetails)->getAllCountryVatDetails();
                    return [
                        'profile' => view('include.expert_profile',
                            compact('expert_profile',
                                'last_message',
                                'user_id'))->render(),
                        'start_conversation' => view('include.start_conversation_inside_message',
                            compact('user_name',
                                'image_url',
                                'countries',
                                'vat_country_confirmation_pop_up',
                                'last_message'))->render(),
                        'view_profile' => true
                    ];
                }
                $user_profile = UserProfile::Profile($user_id);
                $viewed_by = config('constants.BUYER');
            }
            $project_detail = $this->getProjectDetails($communication, $communication->type);
            $contract_detail = $communication->contractDetails;
            $latest_contract = Contract::findByCondition(['communications_id' => $request->communication_id],
                    [],
                    ['order_by' => ['created_at', 'desc']],
                    'first');
            return view('message.chatuserprofile',
                    ['user_profile' => $user_profile,
                        'user_type' => $viewed_by, 
                        'project_detail' => $project_detail, 
                        'contract_detail' => $contract_detail, 
                        'latest_contract' => $latest_contract, 
                        'communication_id' => $communication_id])->render();
        } catch (\Exception $exc)
        {
            return $exc->getMessage();
            throw new \Exception($exc);
        }

    }

    public function getProjectDetails($communication, $type) {
        if ($type == config('constants.PROJECT')) {
            $project_detail = $communication->projectDetail;
        } else if ($communication->type == config('constants.SERVICE_PACKAGE')) {
            $project_detail = $communication->servicePackageDetail;
            $project_detail->subscription_type = config('constants.ONE_TIME_PACKAGE');
        }
        $project_detail->type = $type;
        return $project_detail;
    }

    public function userContractDetails(Request $request) {
        if (empty(Auth::user())) {
            return redirect('/login');
        }
        try {
            $form_data = $request->all();
            if (isset($form_data['communication_id'])) {
                $contract_current_actions = $this->messageContractStatus($form_data['communication_id']);
                $contract_detail = Contract::getFirstContractWithCommunication($form_data['communication_id']);
                $subscription_type = $contract_detail['subscription_type'];
                if($subscription_type == 'monthly_retainer'){
                    $contract_current_actions['packaged_ended'] = trim($contract_detail['finished_on']) ?? '';
                    $contract_current_actions['payment_processed'] = $contract_detail['complete_status'] ?? '';
                    unset($contract_current_actions['contract_has_been_marked_complete_by_expert']);
                }
                if (User::getUserType($form_data['user_id']) == config('constants.BUYER')) {
                    $user_type = config('constants.BUYER');
                } else {
                    $user_type = config('constants.EXPERT');
                }
                return ['success' => 1, 'data' => $contract_current_actions];
            } else {
                return ['success' => 0];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    public function messageContractStatus($commmunication_id) {
        return contractStatus($commmunication_id);
    }

    public function saveMessage(Request $request) {
        if (empty(Auth::user())) {
            return redirect('/login');
        }
        try {
            $id = Auth::user()->id;
            $is_initial_message = 0;
            $form_data = $request->all();
            $segment_message_category = config('constants.STANDARD_MESSAGE');
            $file = $request->file('upload_file');
            if(empty($file))
                $file = $request->file('upload_file_proposal');
            $expert_availiblity_message = Common::expertAvailbalityMessageToBuyer($form_data['available_status_value'],$form_data['message'],$form_data['communication_id']);
            $message_data = [
                'sender_id' => trim($id),
                'receiver_id' => trim($form_data['receiver_id']),
                'communications_id' => $form_data['communication_id'],
                'msg' => trim($expert_availiblity_message['message']),
                'read' => 0,
                'automated_message' => 0,
                'attachment' => ((isset($file) && !empty($file)) ? uploadFile($file) : ''),
                'message_type' => $expert_availiblity_message['message_type']
            ];
            $message_data=(!empty($form_data['available_status_value'])) ? $message_data : stripScriptingTags($message_data);
            $message = new Message($message_data);
            $communication_information = Communication::getCommunicationInformation($form_data['communication_id']);
            if ($message->save()) {
                if(array_key_exists('initial_message', $form_data) && $form_data['initial_message'] == 1)
                {
                    $segment_message_category = config('constants.NEGOTIATION_STARTED_MESSAGE');
                    $is_initial_message = 1;
                    Message::where('message_type', 'expert_expression_of_interest')->where('communications_id', $form_data['communication_id'])->delete();
                    Common::initateConversationBuyerMessage($message->sender_id,
                                $message->receiver_id,
                                $message->communications_id,
                                getJob($communication_information[0]->job_post_id, 0)
                            );
                    Common::initateConversationExpertMessage($message->sender_id,
                                $message->receiver_id,
                                $message->communications_id,
                                getJob($communication_information[0]->job_post_id, 0)
                            );
                }
                $segment_field_values = $this->segmentFieldValues($message);
                (new SegmentComponent)->messagesTracking(
                    $id,
                    $message->id,
                    $form_data['receiver_id'],
                    $segment_field_values['message_text'],
                    $segment_field_values['attachment_link'],
                    $segment_message_category,
                    config('constants.MESSAGE_SENT')
                );
                if ($communication_information[0]->type == 'service_package' && $form_data['available_status_value'] != "")
                {
                    if (hasSubscribed(trim($id)))
                    {
                        Email::servicePackageAvailableOnServicePackageMessageToBuyerEmail(['communication_id' => $message->communications_id]);
                    }
                }
                else
                {
                    Email::adminChatNotification(['message_id' => $message->id]);
                    Email::sendMissedMessageEmail(['message_id' => $message->id]);
                }
                return ['success' => 1,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'communication_id' => $message->communications_id,
                    'text' => $message->msg,
                    'filepath' => $message->attachment,
                    'is_initial_message' => $is_initial_message,
                    'message_date' => addTimeZone('j M, Y',
                        $message->created_at), 'message_time' => addTimeZone('h:i a',
                        $message->created_at)
                    ];
            } else {
                return ['success' => 0];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    private function segmentFieldValues($message){
        $trimmed_text = trim($message->msg);
        if(!empty($trimmed_text) && empty($message->attachment))
            return [
                'message_text' => $trimmed_text,
                'attachment_link' => NULL
                ];
        elseif(empty($trimmed_text) && !empty($message->attachment))
            return [
                'message_text' => NULL,
                'attachment_link' => $message->attachment
                ];
        elseif(!empty($trimmed_text) && !empty($message->attachment))
            return [
                'message_text' => $trimmed_text,
                'attachment_link' => $message->attachment
                ];
    }

    public function buyerInitiateConversation(Request $request) {
        if (empty(Auth::user()->user_type != config('constants.BUYER'))) {
            return redirect('/login');
        }
        try {
            $cover_letter_message = (!empty(trim($request->message_text))) ? trim($request->message_text): NULL;
            $communication = Communication::find($request->communication_id);
            $project_title = getJob($communication->job_post_id, 0);
            if($communication->status != config('constants.ACCEPTED'))
            {
                $communication->status = config('constants.ACCEPTED');
                if ($communication->save()) {
                    (new SegmentComponent)->negotiationsTracking(
                        $request->buyer_id,
                        $communication->job_post_id,
                        $communication->id,
                        $project_title,
                        $cover_letter_message,
                        config('constants.NEGOTIATION_STARTED'),
                        $request->expert_id,
                        config('constants.ACCEPTED_EOI')
                    );
                    if (UsersCommunication::getEmailSubscriptionStatus($request->buyer_id) == 1) {
                        if(hasSubscribed($request->expert_id)){
                        Email::emailToExpertExpressionOfInterestAccepted(['expert_id' => $request->expert_id, 'buyer_id' => $request->buyer_id, 'communication_id' => $communication->id]);
                        }
                        if(hasSubscribed($request->buyer_id)){
                        Email::emailToBuyerExpressionOfInterestAccepted(['expert_id' => $request->expert_id, 'buyer_id' => $request->buyer_id, 'project_id' => $communication->job_post_id]);
                        }
                    }
                    return ['success' => 1];
                } else {
                    return ['success' => 0];
                }
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    public function makeOfferBuyerStatus(Request $request) {
        if (empty(Auth::user())) {
            return redirect('/login');
        }
        try {
            $communication = Communication::find($request->communication_id);
            $project_detail = $this->getProjectDetails($communication, $communication->type);
            $all_posted_jobs = PostJob::getPostedProjectsWithNoContractAccepted(auth::user()->id);
            $buyer_company = ucfirst((new BuyerProfile)->getCompanyNameByBuyerId($communication->buyer_id)->company_name);
            $popup['name'] = 'make_offer_popup';
            if ($communication->type == 'project') {
                $popup['content'] = view('message.popups.make_offer', compact('project_detail', 'all_posted_jobs', 'buyer_company'))->render();
            } else if ($communication->type == 'service_package') {
                $project_detail->deliverables = Deliverable::findByCondtion(['service_package_id' => $project_detail['id'], 'type' => 'service_package']);
                $popup['content'] = view('message.popups.make_service_package_offer', compact('project_detail', 'buyer_company'))->render();
            }

            return $popup;
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }
    
    public function sendContract(Request $request, $buyer_id, $expert_id)
    {
        $data_to_fetch = ['buyer_id' => $buyer_id, 'user_id' => $expert_id];
        if ($request->type == config('constants.PROJECT')) $data_to_fetch['job_post_id'] = $request->job_post;
        if ($request->type == config('constants.SERVICE_PACKAGE')) $data_to_fetch['service_package_id'] = $request->job_post;
        $communication_data = Communication::fetchCommunications($data_to_fetch, 'first');
        $form_data = (new TemporaryProposal)->getDetails($communication_data['id']);
        if (_count($form_data))
            $form_data = json_decode($form_data->details, 1);
        $form_data['receiver_id'] = $buyer_id;
        $form_data['sender_id'] = $expert_id;
        $form_data['job_post'] = $request->job_post;
        $form_data['type'] = $request->type;
        $form_data['communication_id'] = $communication_data['id'];
        $form_data['currency'] = $request->currency;
        if($request->type == config('constants.SERVICE_PACKAGE'))
            $form_data['subscription_type'] = config('constants.ONE_TIME_PACKAGE');
        if (array_key_exists('contract_id', $request->all()))
            $form_data['contract_id'] = $request->contract_id;
        $validator = $this->sendContractValidations($form_data);
        if ($validator->fails())
        {
            return \Response::json($validator->errors(), 422);
        }
        if($this->totalContractAmount($form_data['deliverables'])['total_amount'] < 1000)
        {
            $validator->errors()->add('deliverables',
                    trans('custom_validation_messages.deliverables.amount',
                    [ 'amount' => convertToCurrencySymbol($request->currency).config('constants.THOUSAND')])
                );
            return \Response::json($validator->errors(), 422);
        }
        $contract = $this->prepareContractData($form_data);
        if (array_key_exists('contract_id', $form_data))
            return $this->editContractOffer($form_data, $contract);
        $data_to_fetch['alias_name'] = config('constants.PARENT_CONTRACT');
        $if_already_exists = Contract::fetchContracts($data_to_fetch, 'count');
        if ($if_already_exists)
            return ['success' => 0];
        $type = $request->type;
        if ($contract->save())
        {
            (new SegmentComponent)->proposalSubmittedTracking($expert_id, $buyer_id, 
                                $request->job_post, $request->type, $contract->id, 
                                config('constants.PROPOSAL_SUBMITTED'));
            
            if ($type == config('constants.PROJECT'))
            {
                return $this->afterSaveProjectContract($contract,
                    $form_data);
            }
            if ($type == config('constants.SERVICE_PACKAGE'))
            {
                $this->sendServicePackageEmails($contract,
                    $form_data['communication_id']);
                return $this->afterSaveServicePackageContract($contract,
                    $form_data);
            }
        }
        return ['success' => 0];
    }

    private function sendContractValidations($form_data)
    {
        $messages = [
            'job_start_date.required' => 'Please enter job start date',
            'term.*' => 'Please enter lesser than 500 words',
        ];
        return $validator = Validator::make($form_data, [
                'job_start_date' => 'required',
                'introduction' => 'required|max:10000',
                'summary' => 'required|max:10000',
                'term.*' => 'nullable|max:5000',
                ], $messages);
    }
    
    private function prepareContractData($form_data)
    {
        $contract_amount_details = $this->totalContractAmount($form_data['deliverables']);
        $contract_value = $contract_amount_details['total_amount'];
        $sub_total = $contract_amount_details['sub_total'];
        $expert_information = User::where('id', Auth::user()->id)->with('businessInformation.businessDetails')->first();
        $buyer_information =  (new User())->userInfoWithBusinessDetails($form_data['receiver_id']);
        $buyer_vat_status = (empty($buyer_information->businessInformation->businessDetails->vat_country)) ? FALSE : TRUE;
        $vat_calculations = calculateContractVATValues($sub_total,
           $expert_information->businessInformation->businessDetails->vat_status ?? '',
           $expert_information->businessInformation->businessDetails->vat_country ?? '',
           $buyer_vat_status ?? '',
           $buyer_information->businessInformation->businessDetails->vat_country ?? '');
        if (isset($form_data['contract_id']) && $form_data['contract_id'] > 0)
            $contract = Contract::find($form_data['contract_id']);
        else
            $contract = new Contract;
        $contract->buyer_id = $form_data['receiver_id'];
        $contract->user_id = Auth::user()->id ;
        $contract->communications_id = $form_data['communication_id'];
        $contract->job_post_id = ($form_data['type'] == 'project') ? $form_data['job_post'] : null;
        $contract->service_package_id = ($form_data['type'] == 'service_package') ? $form_data['job_post'] : null;
        $contract->type = $form_data['type'];
        $contract->status = 0;
        $contract->job_start_date = isset($form_data['job_start_date']) ? date('Y-m-d', strtotime($form_data['job_start_date'])) : null;
        $contract->job_end_date = isset($form_data['job_end_date']) ? date('Y-m-d', strtotime($form_data['job_end_date'])) : null;
        $contract->rate_variable = trim($form_data['currency']);
        $contract->rate = $contract_value;
        $contract->payment_mode = self::INVOICE;
        $contract->subscription_type = isset($form_data['subscription_type']) ? trim($form_data['subscription_type']) : null;
        $contract->monthly_days_commitment = isset($form_data['monthly_days_commitment']) ? trim($form_data['monthly_days_commitment']) : null;
        $contract->introduction = $form_data['introduction'];
        $contract->summary = $form_data['summary'];
        $contract->vat = $vat_calculations['vat'];
        $contract->vat_value = $vat_calculations['vat_value'] * 100;
        $contract->sub_total = $vat_calculations['subtotal'] * 100;
        $contract->mm_fee = $vat_calculations['mm_fee'] * 100;
        $contract->mm_fee_vat = $vat_calculations['mm_fee_vat'] * 100;
        $contract->expert_amount = $vat_calculations['total_expert_will_receive'] * 100;
        if($vat_calculations['reverse_charge_invoice'])
            $contract->reverse_charge_invoice = true;
        if($vat_calculations['reverse_charge_mm_fee'])
            $contract->reverse_charge_mm_fee = true;
        return $contract;
    }
    
    private function totalContractAmount($deliverables)
    {
        $total_amount = $sub_total = 0;
        foreach($deliverables as $deliverable)
        {
            $total_amount += $deliverable['total_buyer_will_pay'];
            $sub_total += $deliverable['subtotal'];
        }
        return ['total_amount' => $total_amount, 'sub_total' => $sub_total];
    }
    
    private function sendServicePackageEmails($contract, $communication_id)
    {
        if ($contract->subscription_type == 'monthly_retainer') {
            Email::servicePackageShowContractMonthlyOfferEmailToAdmin(['service_package_id' => $contract->service_package_id,
                'communication_id' => $communication_id, 'amount' => $contract->rate, 'amount_type' => $contract->rate_variable,
                'start_date' => $contract->job_start_date, 'contract_id' => $contract->id,
                'monthly_days_commitment' => $contract->monthly_days_commitment, 'payment_mode' => $contract->payment_mode]);
        }
        if ($contract->subscription_type == 'one_time_package') {
            Email::servicePackageShowContractOneTimeOfferEmailToAdmin(['service_package_id' => $contract->service_package_id,
                'communication_id' => $communication_id, 'amount' => $contract->rate, 'amount_type' => $contract->rate_variable,
                'start_date' => $contract->job_start_date, 'job_end_date' => $contract->job_end_date, 'payment_mode' => $contract->payment_mode]);
        }
        Email::newContractOfferedEmailToBuyer($contract->id);
        Email::newContractCreatedByExpertToHimself($contract->id);
    }

    private function saveInvoice($contract) {
        $invoice_data = [
            'post_job_id' => $contract['job_post_id'],
            'contract_id' => $contract['id'],
            'amount' => round($contract['rate'] * config('constants.HUNDRED')),
            'application_fee' => round((config('constants.MM_SHARE')) * $contract['rate'])
        ];
        $invoice = new Invoice($invoice_data);
        $invoice->save();
    }

    public function savePromotionalCouponUsageDetails($promotional_coupon_id, $user_id, $contract_id) {
        $contract = Contract::getFirstContract($contract_id);
        if (_count($contract)) {
            $save_promotion_coupon_usage = new PromotionalCouponUsageDetail;
            $save_promotion_coupon_usage->promotional_coupon_id = $promotional_coupon_id;
            $save_promotion_coupon_usage->contract_id = $contract_id;
            $save_promotion_coupon_usage->job_post_id = $contract->job_post_id;
            $save_promotion_coupon_usage->user_id = $user_id;
            $save_promotion_coupon_usage->user_type_id = config('constants.BUYER');
            if ($save_promotion_coupon_usage->save()) {
                Contract::updatePromotionalCouponcodeApplied($contract_id, TRUE);
                return true;
            }
        }
        return false;
    }

    public function confirmMakeOfferTerms(Request $request) {
        $stay_safe_confirm = Communication::updateStaySafeStatus($request->communication_id);
        if ($stay_safe_confirm) {
            return ['success' => 1];
        } else {
            return ['success' => 0];
        }
    }

    public function getFeedbackByBuyerPopup(Request $request) {
        $communication = Communication::find($request->communication_id);
        $project_detail = $this->getProjectDetails($communication, $communication->type);
        $type = $communication->type;
        $contract_detail = $communication->contractDetails;
        if (!empty($project_detail)) {
            return ['success' => 1, 'content' => view('message.popups.buyer_give_feedback', compact('project_detail', 'contract_detail', 'type'))->render()];
        }
        return ['success' => 0];
    }

    function buyerFeedbackToExpert(Request $request) {
        $ssl = getenv('APP_SSL');
        $latest_contract = Contract::findByCondition(['communications_id' => $request->communication_id ], [], ['order_by' => ['created_at', 'desc']], 'first');
        $contract = Contract::updateContract($latest_contract->id, 1, $request->rating, $request->feedback_comment, Carbon::now());
        Communication::updateCommunication($latest_contract->communications_id, ['contract_action_date' => Carbon::now()]);
        $message = new Message;
        $message->sender_id = $request->sender_id;
        $message->receiver_id = $request->receiver_id;
        $message->msg = "I have given you some feedback, it will appear now on your profile";
        $buyer_link = url('buyer/expert-profile/' . $request->receiver_id, [], $ssl);
        $expert_link = url('expert/profile-summary', [], $ssl);
        $message->buyer_link = "<a href='" . $buyer_link . "'  title='View full profile' class='view-full-btn'><span> here</span></a> ";
        $message->expert_link = "<a href='" . $expert_link . "' title='View full profile' class='view-full-btn'><span> here</span></a> ";
        $message->communications_id = $latest_contract->communications_id;
        $message->read = 0;
        $message->automated_message = 1;
        $message->attachment = '';

        if ($message->save()) {
            (new SegmentComponent)->feedbackTracking(
                $request->sender_id,
                $request->project_id,
                $request->receiver_id,
                $request->feedback_comment,
                config('constants.FEEDBACK_SUBMITTED')
            );
            return ['success' => 1, 'data' => $message];
        } else {
            return ['success' => 0, 'message' => ''];
        }
    }

    public function editContractOffer($contract_data, $contract)
    {
        if ($contract->save())
        {
            Communication::updateCommunication($contract_data['communication_id'],
                ['contract_action_date' => Carbon::now()]);
            if ($contract_data['type'] == "project")
            {
                $communication = Communication::updateCommunicationJobId($contract_data['communication_id'],
                        ['job_post_id' => $contract_data['job_post']]);
                ServicePackageComponent::updateContractDeliverables('', 
                    $contract_data['contract_id'], 
                    $contract_data['deliverables'], 
                    $contract_data['job_post']
                    );
            }
            else
            {
                ServicePackageComponent::updateContractDeliverables($contract_data['job_post'], 
                    $contract_data['contract_id'], 
                    $contract_data['deliverables'], 
                    ''
                    );
            }
            if(array_key_exists('terms', $contract_data) && _count($contract_data['terms']))
            {
                (new ContractTerm)->deleteTerms($contract_data['contract_id']);
                $this->saveContractTerms($contract_data['terms'], $contract_data['contract_id']);
            }
            $message = Common::editContractAutoMessage($contract_data['sender_id'],
                    $contract_data['receiver_id'],
                    $contract_data['communication_id'],
                    $contract_data['contract_id']);
            (new SegmentComponent)->proposalSubmittedTracking($contract_data['sender_id'], $contract_data['receiver_id'], 
                                $contract_data['job_post'], $contract_data['type'], $contract_data['contract_id'], 
                                config('constants.PROPOSAL_EDITED'));
            if ($message)
            {
                return ['success' => 1, 'data' => $message, 'project_id' => $contract_data['job_post']];
            }
            return ['success' => 0, 'data' => ''];
        }
    }

    public function viewContractByExpertPopup(Request $request) {
        $source = $request['source'];
        $buyer_profile = '';
        $communication = Communication::find($request->communication_id);
        $buyer_information = (new Communication())->getCommunication($request->communication_id);
        $project_detail = $this->getProjectDetails($communication, $communication->type);
        $contract_detail = (new Contract)->contractWithDeliverablesAndTerms(['communications_id' => $communication->id]);
        $project_type = $contract_detail['type'];
        $project_info = (new PostJob())->fetchWithSelectedFields(['id' => $contract_detail['job_post_id']], ['currency', 'user_id'], 'first');
        $buyer_name = userName($contract_detail['buyer_id'], 0, 1);
        if(!_count($contract_detail['deliverables']))
            $buyer_profile = BuyerProfile::getBuyerDetail($request->buyer_id);
        $contract_accepted = ($contract_detail['status'] == config('constants.ACCEPTED')) ? true: false;
        $deliverables=[];
        if($contract_detail['type']=='service_package'){
            $deliverables = Deliverable::findByCondtion(['contract_id' => $contract_detail['id'],'type' => 'contract']);
        }
        if (!empty($contract_detail)) {
            if (!$contract_detail['is_extended']) {
                return ['success' => 1,
                    'content' => view('message.popups.expert-contract-preview',
                    compact('project_detail',
                        'contract_detail',
                        'buyer_profile',
                        'deliverables',
                        'contract_accepted',
                        'buyer_information',
                        'project_type',
                        'project_info')
                    )->render()];
            }
            if ($contract_detail['type'] == 'service_package') {
                $all_contracts = Contract::findByCondition(
                    ['communications_id' => $contract_detail['communications_id']],
                    ['contractDeliverables'],
                    ['order_by' => ['created_at', 'asc']]
                );
            } else {
                $all_contracts = Contract::findByCondition(
                    ['communications_id' => $contract_detail['communications_id']],
                    [],
                    ['order_by' => ['created_at', 'asc']]
                );
            }              
            $expert_name = userName($contract_detail['user_id'], 0, 1);                
            return ['success' => 1,
                'content' => view('message.popups.expert_view_extended_contract_offer',
                compact('contract_detail', 'deliverables', 'all_contracts', 'expert_name'))->render()
            ];
        }
        return ['success' => 0];
    }
    
    public function expertContractCompleteConfirmationPopUp($id) {
        $contract_detail = Contract::getFirstContract($id);
        if(_count($contract_detail)){
            $contract_detail = $contract_detail->toArray();
        }
        $communication = Communication::find($contract_detail['communications_id']);
        $project_detail = $this->getProjectDetails($communication, $communication->type);
        $deliverables=[];
        if($contract_detail['type']=='service_package'){
            $deliverables = Deliverable::findByCondtion(['contract_id' => $id,'type' => 'contract']);
        }
        if (!empty($contract_detail)) {
            return ['success' => 1, 'content' => view('message.popups.expert_mark_contract_complete_confirmation', compact('project_detail', 'contract_detail', 'deliverables'))->render()];
        } else {
            return ['success' => 0];
        }
    }

    public function applyRefferalCouponByExpert(Request $request) {
        $form_data = $request->all();

        $coupon_code = $form_data['coupon'];
        $contract_id = $form_data['contract_id'];
        $user_id = Auth::user()->id;

        $coupon_information = ReferralCouponCode::isRefferalCouponCodeAppliedByExpert($user_id, $coupon_code);
        $response['status'] = 0;
        $response['message'] = "No such coupon found.";

        foreach ($coupon_information as $coupon) {
            $expiry_date = date('Y-m-d 12:00:00', strtotime($coupon['created_at'] . " +90 days"));
            $current_date = date('Y-m-d H:i:s');

            if ($current_date > $expiry_date) {
                $response['status'] = 0;
                $response['message'] = "Coupon code has been expired!";
            } elseif (isset($coupon) && !empty($coupon) && $coupon['coupon_code_used_status'] == 1) {
                $response['status'] = 0;
                $response['message'] = "Coupon code is already used!";
            } else {

                $referral_coupon_code = ReferralCouponCode::find($coupon['id']);
                $referral_coupon_code->coupon_code_used_status = 1;
                $referral_coupon_code->save();

                $coupon_applied = new CouponAppliedByExpert;
                $coupon_applied->referral_coupon_code_id = $referral_coupon_code['id'];
                $coupon_applied->contract_id = $contract_id;
                $coupon_applied->expert_id = $user_id;

                if ($coupon_applied->save()) {

                    $contract_information = Contract::find($contract_id);
                    $total_price = (double) $contract_information['rate'];
                    $rate_variable = $contract_information['rate_variable'];
                    $app_paid = $total_price - ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price);
                    $mm_fee = $rate_variable . round($app_paid, 2);
                    $total_you_will_receive = $total_price - (((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $total_price)) + config('constants.TWENTY');
                    $what_you_will_get = $rate_variable . round($total_you_will_receive, 2);


                    $response['mm_fee'] = $mm_fee;
                    $response['what_you_will_get'] = $what_you_will_get;
                    $response['status'] = 1;
                    $response['discount_applied'] = '$20';
                    $response['message'] = 'Coupon has been applied!';
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Try Again!';
                }
            }
        }

        return $response;
    }

    public function acceptContractByBuyer(Request $request) { 
       try {
            if (!isset($request->contract_id) && !isset($request->project_id)) {
                return ['success' => 0];
            } else {
                $message_to_both = $acceptance_message_to_buyer = $acceptance_message_to_expert = $message_to_buyer = $message_to_expert = '';
                $contract_starts_in_future = 0;
                $contract_information = Contract::getContractInformation($request->contract_id);
                if(strtotime($contract_information[0]['job_start_date']) > strtotime(date('Y-m-d'))){
                    $contract_starts_in_future = 1;
                }
                if($contract_information[0]['type']=='project'){
                $update_contract = $this->acceptContract($request->contract_id, $request->rate, $contract_information[0]['created_at']);
                PostJob::updatePostData(['accepted_contract_id' => $request->contract_id], $request->project_id);
                if ($update_contract) {
                    $buyer_profile = BuyerProfile::getBuyerInformation(trim($request->buyer_id));
                    $company_name = $buyer_profile[0]['company_name'];
                    $job_title = $contract_information[0]['job_post_id'] > 0
                        ? getJob($contract_information[0]['job_post_id'], 0)
                        : getServicePackageName($contract_information[0]['service_package_id'], 0);
                    Email::sendContractEmailToExpert(['expert_id' => $request->expert_id, 'buyer_id' => $request->buyer_id,
                        'job_title' => $job_title, 'company_name' => $company_name]);
                    Email::sendContractEmailToBuyer(['expert_id' => $request->expert_id, 'buyer_id' => $request->buyer_id,
                         'job_title' => $job_title]);
                    Email::sendEmailsToOtherExpertsThatExpressedInterest(['project_id' => $request->project_id, 
                                                'communication_id_of_accepted_contract' => $request->comm_id]);

                    
                    $is_promo_coupon_applied = PromotionalCouponUsageDetail::isPromotionalCouponApplied($request->contract_id);
                    $contract_detail = CouponAppliedByExpert::isRefferalCouponAppliedByExpert($request->expert_id, $request->contract_id);
                    if (_count($is_promo_coupon_applied) > 0) { 
                        $promo_code = config('constants.APPLIED_PROMO_CODE_VALUE');
                    } elseif (!empty($contract_detail)) {  
                        $promo_code = config('constants.REFFERAL_PROMO_CODE_VALUE');
                    } else {
                        $promo_code = config('constants.NO_PROMO_CODE');
                    }
                    $this->saveInvoice($contract_information[0]);
                    Email::sendContractEmailToAdmin([
                        'expert_id' => $request->expert_id,
                        'buyer_id' => $request->buyer_id,
                        'job_title' => $job_title,
                        'contract_id' => $request->contract_id,
                        'promo_code' => $promo_code]
                        );
                    }
                }else{
                    if($contract_information[0]['subscription_type'] == 'monthly_retainer'){
                    $update_contract = Contract::updateContractData(['status' => 1,'monthly_billing_date'=>date('Y-m-d G:i:s', strtotime(nextBillingDateForMonthlyRetainer($request->contract_id))), 'unique_id'=> 'MM'.strtotime($contract_information[0]['created_at']), 'accepted_by_expert_on' => Carbon::now()], $request->contract_id);    
                    }else{
                    $update_contract = $this->acceptContract($request->contract_id, $request->rate, $contract_information[0]['created_at']);
                    }
                    Email::servicePackageContractOfferAcceptEmailToBuyer(['communication_id'=>$request->comm_id]);
                    Email::servicePackageContractOfferAcceptEmailToExpert(['communication_id'=>$request->comm_id,'contract_id'=>$request->contract_id]);
                    Email::servicePackageContractOfferAcceptEmailToAdmin(['communication_id'=>$request->comm_id,'contract_id'=>$request->contract_id]);
                }                
                $acceptance_message_to_buyer = Common::saveAcceptOfferMessageToBuyer($request->expert_id, $request->buyer_id, $request->comm_id);
                $acceptance_message_to_expert = Common::saveProposalAcceptanceOfferMessageToExpert($request->expert_id, $request->buyer_id, $request->comm_id);
                Communication::updateCommunication($contract_information[0]['communications_id'], ['contract_action_date' => Carbon::now()]);
                if ($acceptance_message_to_buyer == '' || $acceptance_message_to_buyer) {                    
                    $message_to_buyer = Common::saveAcceptOfferMessageByAdminToBuyer($request->expert_id, $request->buyer_id, $request->comm_id, $contract_information[0]['type']);
                    $message_to_expert = Common::saveAcceptOfferMessageByAdminToExpert($request->expert_id, $request->buyer_id, $request->comm_id);
                    $proposals_accepted_count = (new Contract)->getContractsAcceptedCount($request->comm_id);
                    $contract_unique_id = (new Contract)->getContractUniqueId($request->contract_id);
                    $project_id = ($contract_information[0]['type'] == config('constants.PROJECT')) ? $contract_information[0]['job_post_id'] :
                                $contract_information[0]['service_package_id'];
                            (new SegmentComponent)->proposalAcceptedTracking($request->expert_id, $request->buyer_id, 
                                $project_id, $contract_information[0]['type'], $contract_unique_id, $proposals_accepted_count,
                                config('constants.PROPOSAL_ACCEPTED'));
                            return ['success' => 1,
                                    'data' => [
                                        'acceptance_message_to_buyer' => $acceptance_message_to_buyer,
                                        'acceptance_message_to_expert' => $acceptance_message_to_expert,
                                        'message_to_buyer' => $message_to_buyer,
                                        'message_to_expert' => $message_to_expert,
                                        'message_to_both' => $message_to_both,
                                        'contract_starts_in_future' => $contract_starts_in_future,
                                        'latest_contract' => $this->latestContractInChatPanel($request->comm_id)
                                        ]
                                    ];
                }
                return ['success' => 0];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    public function acceptServicePackageByExpert(Request $request) {
        try {
            if (!isset($request->contract_id) && !isset($request->project_id)) {
                return ['success' => 0];
            } else {
                $update_contract = $this->acceptContract($request->contract_id, $contract_information[0]['rate'], $contract_information[0]['created_at']);
                if ($update_contract) {
                    $contract_information = Contract::getContractInformation($request->contract_id);
                    $contract_rate = $contract_information[0]['rate_variable'] . '' . $contract_information[0]['rate'];
                    $job_start_date = date('d/m/Y', strtotime($contract_information[0]['job_start_date']));
                    $job_end_date = date('d/m/Y', strtotime($contract_information[0]['job_end_date']));
                    $buyer_profile = BuyerProfile::getBuyerInformation(trim($request->buyer_id));
                    $company_name = $buyer_profile[0]['company_name'];
                    $sender = getUserDetails($request->expert_id);
                    $receiver = getUserDetails($request->buyer_id);
                    $job_title = $request->contract_job_title;
                 

                }
                return ['success' => 1];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }
    
    private function acceptContract($contract_id, $rate, $created_at){
        $data_to_update = [];
        $data_to_update['unique_id'] = 'MM'.strtotime($created_at);
        $data_to_update['status'] = config('constants.ACCEPTED');
        $data_to_update['accepted_by_expert_on'] = Carbon::now();
        return Contract::updateContractData($data_to_update, $contract_id);
    }

    public function markContractAsComplete(Request $request) {
        try {
            $form_data = $request->all();
            if(isContractCompletionAllowed($form_data['communications_id'])['mark_complete_allowed'] == 'no'){
                return ['success' => 0];
            }
            $all_contracts = Contract::findByCondition(['communications_id' => $form_data['communications_id']], [], ['order_by' => ['created_at', 'asc']]);
            $contract_id_to_action = $form_data['contract_id'];
            if(_count($all_contracts)>1){
                if($all_contracts[count($all_contracts)-1]->status == config('constants.NOTACCEPTED')){
                    Contract::updateContractInformation($all_contracts[count($all_contracts)-1]->id, ['deleted_at' => Carbon::now()]);
                    Contract::updateContractInformation($all_contracts[count($all_contracts)-2]->id, ['is_extended' => 'FALSE']);
                    $contract_id_to_action = $all_contracts[count($all_contracts)-2]->id;
                }else{
                    $contract_id_to_action = $all_contracts[count($all_contracts)-1]->id;
                }
            }
            $id = Auth::user()->id;
            $contract_information = getContract($form_data['contract_id']);
            $contract_id = $form_data['contract_id'];
            $buyer_message = Common::saveBuyerMarkProjectAsComplete($id, $form_data['receiver_id'], $form_data['communications_id'],($form_data['contract_type']=='service_package')?'package':'project');
            if($form_data['contract_type']=="service_package"){
             return $this->markServicePackageContractComplete($contract_id_to_action,$contract_information, $contract_id, $form_data,$buyer_message, $id);
            }
            if($buyer_message) {
                    $payment = Invoice::fetchInvoice($contract_id_to_action);
                    if(_count($payment)){
                        $application_fee_shown_to_buyer = (double) $payment->application_fee / config('constants.HUNDRED');
                        $payment_status = true;
                    }
                $coupon_contract_information = CouponAppliedByExpert::isRefferalCouponAppliedByExpert($form_data['receiver_id'], $contract_id);
                if($payment_status==true && !empty($coupon_contract_information)) {
                 $rate=(double) $payment[0]['amount']/config('constants.HUNDRED');
                 $application_fee_shown_to_buyer= round($rate - (((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $rate)), 2);
                }
                Contract::updateContractData(['complete_status' => 1, 'expert_complete_status' => 1,'finished_on'=>Carbon::now()], $contract_id_to_action);
                if (hasSubscribed($form_data['receiver_id'])) {
                    Email::sendExpertProjectCompletionNotification(['contract_id' => $form_data['contract_id'], 'expert_id' => $form_data['receiver_id']]);
                }
                if (hasSubscribed($id)) {
                    Email::sendBuyerProjectCompletionNotification(['contract_id' => $form_data['contract_id'], 'buyer_id' => $id,
                        'application_fee' => $application_fee_shown_to_buyer, 'communication_id' => $form_data['communications_id']]);
                }
                Email::projectCompletionNotificationToAdmin($form_data['contract_id']);
                PostJob::updatePostData(['accepted_contract_complete_status' => TRUE, 'accepted_contract_id' => $form_data['contract_id']], $contract_information[0]->job_post_id);
                if ($payment_status === true) {
                   $message_feedback = Common::saveBuyerFeedbackMessageAferProjectCompletion($form_data['receiver_id'], $id, $form_data['communications_id'], $contract_id,($form_data['contract_type']=='service_package')?'package':'project');
                    return ['success' => 1, 'data' => ['message_to_expert' => $buyer_message, 'message_to_buyer' => $message_feedback]];
                } else {
                    return ['success' => 0];
                }
            } else {
                return ['success' => 0];
            }
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }
    private function markServicePackageContractComplete($contract_id_to_action, $contract_information, $contract_id, $form_data, $buyer_message, $auth_id) {
        Contract::updateContractData(['complete_status' => 1, 'expert_complete_status' => 1,'finished_on'=>Carbon::now()], $contract_id_to_action);
        Communication::updateCommunication($form_data['communications_id'], ['contract_action_date' => Carbon::now()]);
        $message_feedback = Common::saveBuyerFeedbackMessageAferProjectCompletion($form_data['receiver_id'], Auth::user()->id , $form_data['communications_id'], $contract_id, 'service_package');
        if ($contract_information[0]->subscription_type == 'one_time_package') {
            if (hasSubscribed($form_data['receiver_id'])) {
                Email::servicePackageOneTimeContractCompletionByBuyerToExpert($form_data['contract_id']);
            }
            if (hasSubscribed(Auth::user()->id)) {
                Email::servicePackageOneTimeContractCompletionByBuyerToBuyer($form_data['contract_id']);
            }
            Email::servicePackageOneTimeContractCompletionByBuyerToAdmin($form_data['contract_id']);
        }
        return ['success' => 1, 'data' => ['message_to_expert' => $buyer_message, 'message_to_buyer' => $message_feedback]];
    }
    private function contractExtensionEmail($contract_id_to_action, $expert_id, $buyer_id){
        if (hasSubscribed($buyer_id)) {
            Email::extensionContractAndMainContractCompleteEmailToBuyer($contract_id_to_action);
        }
        if (hasSubscribed($expert_id)) {
            Email::extensionContractAndMainContractCompleteEmailToExpert($contract_id_to_action);
        }
        Email::extensionContractAndMainContractCompleteEmailToAdmin($contract_id_to_action);
    }
    
    public function expertMarkContractComplete(Request $request) {
        $form_data = $request->all();
        $sender_id = Common::getAuthorizeUser()->id;
        $contract_detail = Contract::getContractInformation($form_data['contract_id']);
        $contract = Contract::updateExpertContractCompleteStatus($form_data['contract_id']);
        $message = Common::saveExpertMarkProjectComplete($sender_id, $form_data['receiver_id'], $form_data['communications_id'], ($contract_detail[0]['type']=='service_package')?'package':'project');
        if ($message->save()) {
            if($contract_detail[0]['type']=== "project"){
              if (hasSubscribed($sender_id)) {
                Email::projectContractCompletedByExpertEmailToExpert($contract_detail[0]['id']);
              }
              if (hasSubscribed($form_data['receiver_id'])) {
                Email::projectContractCompletedByExpertEmailToBuyer($contract_detail[0]['id']);
              }
            }
            if(($contract_detail[0]['type']=== "service_package") && ($contract_detail[0]['subscription_type']=="one_time_package")){
                if (hasSubscribed($form_data['receiver_id'])) {
                    Email::servicePackageOneTimeContractCompletedByExpertEmailToExpert([
                        'expert_id' => $sender_id,
                        'buyer_id' => $form_data['receiver_id'],
                        'communication_id' => $form_data['communications_id']
                    ]);
                }
                if (hasSubscribed($sender_id)) {
                    Email::servicePackageOneTimeContractCompletedByExpertEmailToBuyer([
                        'expert_id' => $sender_id,
                        'buyer_id' => $form_data['receiver_id'],
                        'communication_id' => $form_data['communications_id']
                    ]);
                }
            }
            (new Email)->projectContractCompletedByExpertEmailToAdmin($contract_detail[0]['id']);
            return ['success' => 1, 'data' => $message];
        } else {
            return ['success' => 0];
        }
    }

    public function updatePostjobsAccpetedContractStatus($offest = 0, $limit = 5) {
        $all_projects = PostJob::select('id')->has('acceptedContractInfo')->with('acceptedContractInfo')->offset($offest)->limit($limit)->orderBy('id')->get()->toArray();
        $updated = [];
        if (_count($all_projects)) {
            foreach ($all_projects as $key => $projects) {
                $updated['project_id-' . $projects['id']] = $projects['id'];
                $project_updated = PostJob::where('id', $projects['id'])->update(['accepted_contract_id' => $projects['accepted_contract_info']['id'], 'accepted_contract_complete_status' => ($projects['accepted_contract_info']['complete_status'] == 1) ? TRUE : FALSE]);
            }
        }
        return $updated;
    }

    public function chatNotificationCount(Request $request) {
        $options = ['type' => 'created_at', 'value' => 'desc'];
        if (trim($request->communication_id) == '' || !isset($request->communication_id)) {
            return ['success' => 0];
        } else {
            $all_messages = $this->countUnreadMessages(Auth::user()->id);
            if(!isset($request->communication_id) || empty($request->communication_id)){
                return ['success' => 0];
            }
            $single_user_messages_count = Message::getCountUnreadMsgCount($request->communication_id, Auth::user()->id);
            $latest_message = Message::getCountUnreadMsgCount($request->communication_id, Auth::user()->id, $options);
            if (_count($latest_message) && array_key_exists('created_at', $latest_message) && !empty($latest_message['created_at'])) {
                return ['success' => 1, 'all_unread_messages' => $all_messages['all_unread_messages'], 'new_message_notification' => $single_user_messages_count, 'latest_message_date' => addTimeZone('h:i a', $latest_message['created_at'])];
            } else {
                return ['success' => 0];
            }
        }
    }

    public function markAllMessagesRead(Request $request) {
        if ($request->communication_id) {
            $update_communication = Communication::updateCommunicationReadStatus($request->communication_id);
            $update_messages = Message::updateMessageReadStatus($request->communication_id, Auth::user()->id);
            if ($update_communication && $update_messages) {
                $all_messages = $this->countUnreadMessages(Auth::user()->id);
                return ['success' => 1, 'all_unread_messages' => $all_messages['all_unread_messages']];
            } else {
                return ['success' => 0];
            }
        }
    }

    public function countUnreadMessages($receiver_id, $inputs = []) {
        $all_messages = Message::getUnreadMessageCount($receiver_id);
        $project_unread_message_count = isset($inputs['id']) ? (new Communication)->getUnreadMessageOfSpecificProject($inputs) : 0;
        return ['success' => 1, 'all_unread_messages' => $all_messages, 'project_unread_message_count' => $project_unread_message_count];
    }

    public function getNewMessageNotificationOnOtherPages(Request $request) {
        $inputs = $request->all();
        $all_messages = $this->countUnreadMessages(Auth::user()->id, $inputs);
        if (_count($all_messages['all_unread_messages'])) {
            $project_id = $inputs['id'] ?? '';
            $project_type = $inputs['project_type'] ?? '';
            return ['success' => 1, 'all_unread_messages' => $all_messages['all_unread_messages'], 'project_unread_message_count' => $all_messages['project_unread_message_count'], 'id' => $project_id, 'project_type' => $project_type];
        } else {
            return ['success' => 0];
        }
    }

    public function inviteExpertForConversation(Request $request) {
        $communication = new Communication;
        $communication->user_id = $request->user_id;
        $communication->buyer_id = $request->buyer_id;
        $communication->job_post_id = ((isset($request->jobTitle) && !empty($request->jobTitle)) ? $request->jobTitle : 0);
        $communication->status = 1;
        if ($communication->save()) {
            $post_information = PostJob::getPostJobInformation($communication->job_post_id)->first();
            (new SegmentComponent)->negotiationsTracking(
                $request->buyer_id, 
                $communication->job_post_id, 
                $communication->id, 
                $post_information->job_title, 
                $request->sendMessage, 
                config('constants.NEGOTIATION_STARTED'),
                $request->user_id,
                config('constants.MANUAL_INVITE')
            );
            $common_component = new Common;
            $common_component->saveWelcomeMessageToExpertShowInterestInProjectByAdmin($request->buyer_id,
                $request->user_id,
                $communication,
                $post_information->job_title,
                $communication->job_post_id);
            $message = $common_component->saveBuyerInviteNewExpertForConversation($request->buyer_id, $request->user_id, stripScriptingTagsInline($request->sendMessage), $communication);
            (new SegmentComponent)->messagesTracking(
                $request->buyer_id, 
                $message->id, 
                $request->user_id, 
                $request->sendMessage, 
                '', 
                config('constants.MANUAL_INVITE_MESSAGE'), 
                config('constants.MESSAGE_SENT')
            );
            if ($message) {
                $project = PostJob::getPostJobInformation($communication->job_post_id);
                if ($project->exists() && hasSubscribed($request->user_id)) {
                    Email::emailToExpertOnInvite(['expert_id' => $request->user_id, 'buyer_id' => $request->buyer_id, 'post_job_id' => $communication->job_post_id]);
                }
            }
            return ['success' => 1, 'data' => $message];
        } else {
            return ['success' => 0];
        }
    }

    public function insertPromotionalCouponCode($coupon, $date) {
        $is_admin = Auth::user()->user_type_id;
        if ($is_admin == config('constants.ADMIN')) {
            $coupon = ($coupon) ?? getenv('COUPON_CODE');
            $date = ($date) ?? getenv('COUPON_LAST_DATE');
            $if_exists = PromotionalCoupon::where('coupon_code', $coupon)->count();
            if ($if_exists < 1) {
                $promotional_coupon = new PromotionalCoupon;
                $promotional_coupon->coupon_code = $coupon;
                $promotional_coupon->number_of_times_redeemed = '0';
                $promotional_coupon->is_active = True;
                $promotional_coupon->expiry_date = date('Y-m-d 23:59:00', strtotime($date));
                $promotional_coupon->amount = config('constants.HUNDRED');
                $coupons = $promotional_coupon->save();
                if ($coupons) {
                    echo"Coupon Inserted successfully";
                }
            } else {
                echo"Coupon already exists";
            }
        } else {
            echo"You are not authorized to insert coupon code";
        }
    }

    function buyerfeedback(Request $request) {
        $ssl = getenv('APP_SSL');
        $form_data = $request->all();
        $id = Auth::user()->id;
        $feedback_time = Carbon::now();
        $contract = Contract::updateContract($form_data['contract_id'], 1, $form_data['rating'], $form_data['feedback_comment'], $feedback_time);

        $message = new Message;
        $message->sender_id = $id;
        $message->receiver_id = $form_data['receiver_id'];
        $message->msg = "I have given you some feedback, it will appear now on your profile";
        $buyerlink = url('buyer/expert-profile/' . $form_data['receiver_id'], [], $ssl);
        $expertlink = url('expert/profile-summary', [], $ssl);
        $message->buyer_link = "<a href='" . $buyerlink . "'  title='View full profile' class='view-full-btn'><span> here</span></a> ";
        $message->expert_link = "<a href='" . $expertlink . "' title='View full profile' class='view-full-btn'><span> here</span></a> ";

        $message->communications_id = $form_data['communications_id'];
        $message->read = 0;
        $message->attachment = ((isset($fullurl) && !empty($fullurl)) ? $fullurl : '');

        if ($message->save()) {
            $response = 1;
        } else {
            $response = 0;
        }
        return $response;
    }

    function editBuyerfeedback(Request $request) {
        $ssl = getenv('APP_SSL');
        $form_data = $request->all();
        $id = Auth::user()->id;
        $feedback_time = Carbon::now();
        $contract = Contract::updateContract($form_data['contract_id'], 1, $form_data['rating'], $form_data['feedback_comment'], $feedback_time);
        $response = 0;
        if ($contract == true) {
            $response = 1;
        }
        return $response;
    }

    /* ajax request when clients apply promo code on  make offer */

    public function previewBuyerPromoCode(Request $request) {
        $form_data = $request->all();
        $coupon_code = $form_data['coupon'];
        $contract_id = $form_data['contract_id'];
        $rate = $form_data['rate'];
        $buyer_id = Auth::user()->id;

        $check_promo_validity = $this->checkIfPromoCouponIsValidForBuyer($buyer_id, $coupon_code, $rate);
        if ($check_promo_validity['status'] == 1) {
            if ($contract_id == 'preview') {
                $response['status'] = 1;
                $response['message'] = 'Coupon has been applied!';
                $response['amount'] = $check_promo_validity['amount'];
            } else {
                /* Insert coupon code details in db */
                $promotional_coupon = PromotionalCoupon::getFirstPromotionalCoupon($coupon_code);
                $result = $this->savePromotionalCouponUsageDetails($promotional_coupon->id, $buyer_id, $contract_id);
                if ($result == 1) {


                    $contractInfo = Contract::find($contract_id);
                    $total_price = (double) $contractInfo['rate'];
                    $rate_variable = $contractInfo['rate_variable'];

                    $app_paid = $total_price - ((config('constants.EXPERT_SHARE') / config('constants.HUNDRED')) * $total_price);
                    $mm_fee = $rate_variable . round($app_paid, 2);
                    $total_you_will_receive = $total_price - $promotional_coupon->amount;
                    $what_you_will_get = $rate_variable . round($total_you_will_receive, 2);

                    $response['mm_fee'] = $mm_fee;
                    $response['what_you_will_get'] = $what_you_will_get;
                    $response['status'] = 2;
                    $response['discount_applied'] = '$100';
                    $response['message'] = 'Coupon has been applied!';
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Coupon code could not be saved!';
                }
            }
        } else {
            $response = $check_promo_validity;
        }
        return $response;
    }

    public function removeBuyerPromoCode(Request $request) {
        $form_data = $request->all();
        $user_id = Auth::user()->id;
        $contract_id = $form_data['contract_id'];
        if (isset($contract_id) && !empty($contract_id)) {
            $contract_updated = Contract::updatePromotionalCouponcodeApplied($contract_id, FALSE);
            $promotional_coupon_details_deleted = PromotionalCouponUsageDetail::deletePromotionCouponUsageCode($contract_id);
            if ($contract_updated && $promotional_coupon_details_deleted) {
                $result = 1;
            } else {
                $result = 0;
            }
        }
        return $result;
    }

    public function referExpert(Request $request) {
        $form_data = $request->all();

        $created = Carbon::now();
        $expert_id = Auth::user()->id;
        $email_count = $form_data['email_count'];
        if (isset($form_data) && !empty($form_data['email']) && array_key_exists('email', $form_data)) {
            $referral_expert = [];
            $response = 1;
            foreach ($form_data['email'] as $key => $email) {

                if (isset($email) && !empty($email)) {
                    $referalInfo = checkReferralStatus($email, $expert_id);
                    if ($referalInfo == 0) {
                        $referral_expert[$key]['expert_id'] = $expert_id;
                        $referral_expert[$key]['referral_expert_email'] = $email;
                        $referral_expert[$key]['referral_status'] = 0;
                        $referral_expert[$key]['created_at'] = $created;
                        $referral_expert[$key]['updated_at'] = $created;
                    }
                }
            }
            $response = ReferralExpert::insert($referral_expert);
            if ($response == config('constants.ACCEPTED')) {
                foreach ($referral_expert as $email) {
                    $expert_information = [
                        'expert_id' => $email['expert_id'],
                        'referral_email' => $email['referral_expert_email'],
                        'referral_name' => ''
                    ];
                    if (hasSubscribed($email['expert_id']))
                        Email::sendExpertReferalEmail($expert_information);
                    }
                    if (hasSubscribed($expert_id)) {
                        Email::sendBuyerReferalEmail(['expert_id' => $expert_id]);
                    }
                Session::flash('expertRefeeralMessage', 'You have just sent ' . $email_count . ' email invites to join MeasureMatch. Thanks!');
                return 1;
            }
        }
    }

    public function referSingleExpert(Request $request) {
        $form_data = $request->all();
        $created = Carbon::now();
        $expert_id = Auth::user()->id;

        $referral_expert['expert_id'] = $expert_id;
        $referral_expert['referral_expert_name'] = $form_data['referral_name'];
        $referral_expert['referral_expert_email'] = $form_data['referral_email'];
        $referral_expert['referral_status'] = 0;
        $referral_expert['created_at'] = $created;
        $referral_expert['updated_at'] = $created;

        $response = ReferralExpert::insert($referral_expert);
        if ($response == config('constants.ACCEPTED')) {
            $expert_information = [
                'expert_id' => $expert_id,
                'referral_email' => $form_data['referral_email'],
                'referral_name' => $form_data['referral_name']
            ];

            Email::sendExpertReferalEmail($expert_information);
            Email::sendBuyerReferalEmail(['expert_id' => $expert_id]);
            return 1;
        }
    }

    public function checkIfCouponAlreadyApplied(Request $request) {
        $form_data = $request->all();
        $promotional_coupon = isset($form_data['coupon']) ? PromotionalCoupon::getFirstPromotionalCoupon($form_data['coupon']) : 0;
        $buyer_id = Auth::user()->id;
        $accepted_contract_exists = Contract::getBuyercontractsCount($buyer_id);
        $coupon_already_applied_on_contract = Contract::getFirstPromotionalCouponApplied($buyer_id);
        if ($accepted_contract_exists > 0 || _count($promotional_coupon) == 0) {
            $response['status'] = 0;
            $response['message'] = "This code is no longer valid.";
        } else if (_count($coupon_already_applied_on_contract) > 0) {
            $response['status'] = 3;
            $response['message'] = "This code has been applied to a previous project, would you like to use it on this project instead?";
            $response['already_applied_contract_id'] = $coupon_already_applied_on_contract->id;
        } else {
            $response['status'] = 0;
            $response['message'] = "This code is no longer valid.";
        }
        return $response;
    }

    public function checkIfPromoCouponIsValidForBuyer($buyer_id, $coupon_code, $rate) {
        $accepted_contract_exists = Contract::getBuyercontractsCount($buyer_id);
        $promotional_coupon = PromotionalCoupon::getFirstPromotionalCoupon($coupon_code);
        if (_count($promotional_coupon) < 1) {
            $response['status'] = 0;
            $response['message'] = "No such coupon found.";
        } elseif ($accepted_contract_exists > 0) {
            $response['status'] = 0;
            $response['message'] = "This code is no longer valid.";
        } else if ($rate < 1000) {
            $response['status'] = 0;
            $response['message'] = "This code isn't valid for this project. The value of the project must be at least $1,000 ";
        } else if (!empty($promotional_coupon)) {

            $expiry_date = $promotional_coupon->expiry_date;
            $current_date = date('Y-m-d H:i:s');
            $promotional_coupon_usage_details = PromotionalCouponUsageDetail::getPromotionCouponCodeCount($promotional_coupon->id);
            if ($promotional_coupon_usage_details >= config('constants.HUNDRED') || $current_date > $expiry_date || $promotional_coupon->is_active === FALSE) {
                $response['status'] = 0;
                $response['message'] = "Coupon code has been expired!";
            } else {
                $response['status'] = 1;
                $response['message'] = "Coupon code is valid";
                $response['amount'] = $promotional_coupon->amount;
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Copoun code is not valid";
        }
        return $response;
    }

    public function postmarkInbound(Request $request) {
        $inbound_email_data = $request->all();
        if (!empty($inbound_email_data)) {
            $inbound_data_string = str_replace('\'', '\\\'', $inbound_email_data);
            $inbound_email_encoded_data = json_encode($inbound_data_string);
            $inbound_email_decoded_data = json_decode($inbound_email_encoded_data, true);
            $message_content = $inbound_email_decoded_data['StrippedTextReply'];
            $message_details = $inbound_email_decoded_data['MailboxHash'];
            if ($message_details) {
                $message_details = explode('+', $message_details);
                $message_data = [
                    'sender_id' => trim($message_details[1]),
                    'receiver_id' => trim($message_details[0]),
                    'communications_id' => trim($message_details[2]),
                    'msg' => ((isset($message_content) && !empty($message_content)) ? str_replace('\\\'', '\'', $message_content) : ''),
                    'read' => 0,
                    'automated_message' => 0,
                    'attachment' => '',
                ];
                PostmarkInbound::insertData(['inbound' => json_encode($message_data)]);
                $message = new Message($message_data);
                if ($message->save()) {
                    Email::adminChatNotification(['message_id' => $message->id]);
                    Email::sendMissedMessageEmail(['message_id' => $message->id]);
                }
            }
        } else {
            return "No json data found";
        }
    }
    public function emailSeenByUser(Request $request) {
        $email_data = $request->all();
        if(is_array($email_data)){
            if(_count($email_data) && array_key_exists('MessageID', $email_data) && array_key_exists('FirstOpen', $email_data)){
                if($email_data['FirstOpen']){
                    OutboundEmailLog::updateData(['email_client_message_id' => $email_data['MessageID']], ['is_seen' => TRUE]);
                }
            }
        }
    }
    public function afterSaveProjectContract($contract, $form_data) {
        $id = Auth::user()->id;
        if (isset($form_data['contract_id']) && $form_data['contract_id'] > 0) {
            (new Deliverable())->deleteDeliverablesByContractId($form_data['contract_id']);
        }
        ServicePackageComponent::saveDeliverables('', $form_data['deliverables'], config('constants.CONTRACT'), $contract->id, $contract->job_post_id);
        if(isset($form_data['terms']) && _count($form_data['terms']))
            $this->saveContractTerms($form_data['terms'], $contract->id);
        if (isset($form_data['coupon_code_applied']) && !empty($form_data['coupon_code_applied'])) {
            $is_coupon_valid = Common::checkIfPromoCouponIsValidForBuyer($id, $form_data['coupon_code_applied'], $form_data['rate']);
            if ($is_coupon_valid['status'] == config('constants.APPROVED')) {
                $promotional_coupon = PromotionalCoupon::getFirstPromotionalCoupon($form_data['coupon_code_applied']);
                $this->savePromotionalCouponUsageDetails($promotional_coupon->id, $id, $contract->id);
            }
        }
        Communication::updateCommunicationJobId($form_data['communication_id'], ['job_post_id' => $contract->job_post_id]);
        $make_offer_message = Common::sendContractOfferMessageByBuyer($form_data['sender_id'], $form_data['receiver_id'],
            $form_data['communication_id'], $contract->id);
        if (!empty($make_offer_message)) {
            Email::emailToAdminSendContractDetail(['receiver_id' => $form_data['receiver_id'], 'sender_id' => $form_data['sender_id'],
                'contract_id' => $contract->id]);
            Email::newContractOfferedEmailToBuyer($contract->id);
            Email::newContractCreatedByExpertToHimself($contract->id);
            $project_description = PostJob::getPostJobDetails($contract->job_post_id);
            return ['success' => 1,
                'data' => $make_offer_message,
                'contract_id' => $contract->id,
                'posted_job_id' => $contract->job_post_id,
                'posted_job_name' => $project_description->job_title,
                'contract_start_date' => date('j M, Y', strtotime($contract->job_start_date)),
                'contract_end_date' => date('j M, Y', strtotime($contract->job_end_date)),
                'contract_rate' => convertToCurrencySymbol($contract->rate_variable) . number_format($contract->rate),
                'accepted_status' => $contract->status,
                'project_type' => config('constants.PROJECT'),
                'communication_id' => $form_data['communication_id']
            ];
        }
    }
    private function saveContractTerms($terms, $contract_id)
    {
        $data_to_save = [];
        foreach($terms as $term)
        {
            $data_to_save[] = ['term' => $term['term'], 'contract_id' => $contract_id];
        }
        if(_count($data_to_save))
            ContractTerm::insert($data_to_save);
    }
    
    public function afterSaveServicePackageContract($contract, $form_data) {
        $make_offer_message = Common::sendContractOfferMessageByBuyer($form_data['sender_id'], $form_data['receiver_id'], $form_data['communication_id'], $contract->id);
        Communication::updateCommunication($form_data['communication_id'], ['contract_action_date' => Carbon::now()]);
        $deliverable = ServicePackageComponent::saveDeliverables($contract->service_package_id, $form_data['deliverables'], 'contract',$contract->id);
        if(isset($form_data['terms']) && _count($form_data['terms']))
            $this->saveContractTerms($form_data['terms'], $contract->id);
        if ($contract->subscription_type == config('constants.MONTHLY_RETAINER')) {
            $job_end_date = 'Monthly Retainer (cancel anytime)';
        } else {
            $job_end_date = isset($contract->job_end_date) ? date('j M, Y', strtotime($contract->job_end_date)) : '';
        }
        return ['success' => 1, 'data' => $make_offer_message, 'contract_id' => $contract->id, 
            'posted_job_id' => $contract->service_package_id, 'posted_job_name' =>getServicePackageName($contract->service_package_id, 0),
            'contract_start_date' => date('j M, Y', strtotime($contract->job_start_date)),
            'contract_end_date' => $job_end_date,
            'contract_rate' => convertToCurrencySymbol($contract->rate_variable).number_format($contract->rate),
            'accepted_status' => $contract->status, 'project_type' => config('constants.SERVICE_PACKAGE'),
            'communication_id' => $form_data['communication_id']];
    }
    
    public function servicePackageEditContractPoup($id){
        if(!ctype_digit($id)){
            return 0;
        }
        $contract_detail = Contract::getContractDetail($id)->first();
        $deliverables = Deliverable::findByCondtion(['contract_id' => $id,'type' => 'contract']);
        return view('message.popups.edit_service_package_offer', compact('deliverables','contract_detail'))->render();
   } 
    
    public function showPopupFinishMonthlyServicePackageContract(Request $request){
       if(!ctype_digit($request->contract_id)){
            return 0;
        }
        $contract_detail = Contract::getContractDetail($request->contract_id)->first();
        $expert_name = ucfirst(userInfo($contract_detail['user_id'])[0]['name']);
        if(Auth::user()->user_type_id == config('constants.EXPERT')){
            return view('message.popups.expert_finish_monthly_retainer_contract', compact(['contract_detail','expert_name']))->render();
        }
        return view('message.popups.buyer_finish_monthly_retainer_contract', compact(['contract_detail','expert_name']))->render();
   } 
    public function showInterestInServicePackage(Request $request) {
        if(Auth::user()->admin_approval_status != config('constants.APPROVED')) return ['success' => false];
        $communication = new Communication;
        $communication->user_id = $request->expert_id;
        $communication->buyer_id = $request->buyer_id;
        $communication->service_package_id = $request->service_package_id;
        $communication->status = 0;
        $communication->type ="service_package";
        $communication->contract_action_date = Carbon::now();
        if ($communication->save()) {
            $auto_message=Common::autoMessageShowInterestInServicePackageToExpert($request->buyer_id, $request->expert_id,$communication->service_package_id, $communication->id);
            if ($auto_message) {
                $message = '';
                if(!empty(trim($request->cover_letter_message))){
                    $message = Common::SaveMessageShowInterestInServicePackageToExpert($request->buyer_id, $request->expert_id, stripScriptingTagsInline($request->cover_letter_message), $communication->id);
                }
                if (hasSubscribed($request->expert_id)) {
                    Email::servicePackageShowInterestEmailToExpert([
                        'communication_id' => $communication->id,
                        'service_package_id' => $request->service_package_id
                    ]);
                }
                if (hasSubscribed($request->buyer_id)) {
                    Email::servicePackageShowInterestEmailToBuyer([
                        'communication_id' => $communication->id,
                        'service_package_id' => $request->service_package_id
                    ]);
                }
                return ['success' => true, 'message' => $message,'auto_message'=>$auto_message];
            }
            return ['success' => true, 'auto_message'=>$auto_message];
        } else {
            return ['success' => false];
        }
    }
    public function finishMonthlyRetainerServicePackageContract(Request $request,$contract_id) {
        if(!$contract_id){return ['success' => 0];}
        $sender_id = Common::getAuthorizeUser()->id;
        $contract_detail = Contract::getContractInformation($contract_id);
        $message = Common::saveBuyerFinishPackageContractMessage($contract_id, $sender_id, $contract_detail[0]['user_id'], $contract_detail[0]['communications_id'], $contract_detail[0]['monthly_days_commitment']);
        $message_to_buyer = Common::saveBuyerFeedbackMessageAferProjectCompletion($contract_detail[0]['user_id'], $sender_id, $contract_detail[0]['communications_id'], $contract_id, 'service_package');
        $contract = Contract::updateContractInformation($contract_id, ['finished_by' => config('constants.BUYER'), 'finished_on' => Carbon::now(), 'job_end_date' => date('Y-m-d', strtotime(nextBillingDateForMonthlyRetainer($contract_id)))]);
        if ($contract) {
           if (strtotime($contract_detail[0]['monthly_billing_date']) == strtotime(date('Y-m-d'))) {
               if (hasSubscribed($contract_detail[0]['user_id'])) {
                   Email::servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToExpert($contract_id);
               }
               if (hasSubscribed($contract_detail[0]['buyer_id'])) {
                   Email::servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToBuyer($contract_id);
               }
                Email::servicePackageMonthlyContractFinalDayPaymentToAdmin($contract_id);
            } else {
               if (hasSubscribed($contract_detail[0]['user_id'])) {
                   Email::servicePackageMonthlyContractFinishedByBuyerToExpert($contract_id);
               }
               if (hasSubscribed($contract_detail[0]['buyer_id'])) {
                   Email::servicePackageMonthlyContractFinishedByBuyerToBuyer($contract_id);
               }
                Email::servicePackageMonthlyContractFinishedEmailToAdmin(['contract_id' => $contract_id, 'finished_by' => config('constants.BUYER')]);
            }
            return ['success' => 1, 'data' => $message, 'message_to_buyer' => $message_to_buyer];
        } else {
            return ['success' => 0];
        }
    }
    
    public function expertFinishServicePackageContract(Request $request) {
        $form_data = $request->all();
        $sender_id = Common::getAuthorizeUser()->id;
        $contract_detail = Contract::getContractInformation($form_data['contract_id']);
        $message = Common::saveExpertFinishPackageContractMessage($form_data['contract_id'], $sender_id, $contract_detail[0]['buyer_id'], $contract_detail[0]['communications_id'], $contract_detail[0]['monthly_days_commitment']);
        $contract = Contract::updateContractInformation($form_data['contract_id'], ['finished_by' => config('constants.EXPERT'), 'finished_on' => Carbon::now(), 'job_end_date' => date('Y-m-d', strtotime(nextBillingDateForMonthlyRetainer($form_data['contract_id'])))]);
        if ($message->save()) {
            if(strtotime($contract_detail[0]['monthly_billing_date']) == strtotime(date('Y-m-d'))){
                if (hasSubscribed($contract_detail[0]['user_id'])) {
                    Email::servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToExpert($form_data['contract_id']);
                }
                if (hasSubscribed($contract_detail[0]['buyer_id'])) {
                    Email::servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToBuyer($form_data['contract_id']);
                }

                Email::servicePackageMonthlyContractFinalDayPaymentToAdmin($form_data['contract_id']);
            }else{
                if (hasSubscribed($contract_detail[0]['user_id'])) {
                    Email::servicePackageMonthlyContractFinishedByExpertToExpert($form_data['contract_id']);
                }
                if (hasSubscribed($contract_detail[0]['buyer_id'])) {
                    Email::servicePackageMonthlyContractFinishedByExpertToBuyer($form_data['contract_id']);
                }
                Email::servicePackageMonthlyContractFinishedEmailToAdmin(['contract_id'=>$form_data['contract_id'],'finished_by' => config('constants.EXPERT')]);
            }
            
            return ['success' => 1, 'data' => $message];
        } else {
            return ['success' => 0];
        }
    }
    
    public function contractViewPopUp($id)
    {
        if (!ctype_digit($id))
            return 0;
        $contract_detail = (new Contract)->contractWithDeliverablesAndTerms(['id' => $id], true);
        if (!empty($contract_detail))
        {
            $buyer_information = (new Communication())->getCommunication($contract_detail['communications_id']);
            $contract_accepted = ($contract_detail['status'] == config('constants.ACCEPTED')) ? true : false;
            $project_type = $contract_detail['type'];
            $project_info = (new PostJob())->fetchWithSelectedFields(['id' => $contract_detail['job_post_id']],
                ['currency', 'user_id'], 'first');
            $deliverables = [];
            if ($contract_detail['type'] == 'service_package')
            {
                $deliverables = Deliverable::findByCondtion(['contract_id' => $id, 'type' => 'contract']);
                $contract_accepted = $contract_detail['status'];
            }
            if (!$contract_detail['is_extended'])
            {
                $user_setting = json_decode(Auth::user()->settings, 1);
                $business_address_pop_up_status = isset($user_setting['business_address_pop_up']) ? TRUE : FALSE;
                $business_information = (new BusinessInformation)->getUserBusinessInformation(Auth::user()->id);
                if (!$business_address_pop_up_status && !isAdmin())
                {
                    if (_count($business_information))
                    {
                        $business_address = $business_information->businessAddress;
                    }
                    $communication_id = $contract_detail['communications_id'];
                    $countries = (new CountryVatDetails)->getAllCountryVatDetails();
                    $pop_up['name'] = config('constants.BUSINESS_ADDRESS_POP_UP');
                    $pop_up['content'] = view('message.popups.buyer_business_address',
                        compact('contract_detail',
                            'countries',
                            'communication_id',
                            'business_address'))->render();
                    return $pop_up;
                }
                if (Communication::CommunicationStaySafeFieldStatus($contract_detail['communications_id']) == 0 && !isAdmin())
                {
                    $pop_up['name'] = config('constants.STAY_SAFE_POP_UP');
                    $pop_up['content'] = view('message.popups.terms_services')->render();
                    return $pop_up;
                }
                $view_pop_up_file = 'view_proposal_pop_up';
                if (!_count($contract_detail['deliverables']) || 
                    (_count($contract_detail['deliverables']) && 
                    empty($contract_detail['deliverables'][0]['title'])))
                        $view_pop_up_file = 'view_contract_pop_up';
                return view("message.popups.$view_pop_up_file",
                        compact([
                        'contract_accepted',
                        'buyer_information',
                        'contract_detail',
                        'deliverables',
                        'project_type',
                        'project_info'
                        ])
                    )->render();
            }
            $contract_deliverable = ($contract_detail['type'] == 'service_package') ? ['contractDeliverables'] : [];
            $all_contracts = Contract::findByCondition(['communications_id' => $contract_detail['communications_id']],
                    $contract_deliverable,
                    ['order_by' => ['created_at', 'asc']]);
            $expert_name = userName($contract_detail['user_id'], 0, 1);
            return view('message.popups.view_extended_contract_offer',
                    compact('project_detail',
                        'all_contracts',
                        'expert_name',
                        'deliverables'))->render();
        }
        return 0;
    }

    public function contractEditPopUp($id){
        if(!ctype_digit($id)){
            return 0;
        }
        $contract = Contract::getContractDetail($id);
        if($contract->exists()){
           $contract_detail = $contract->with('expert.user_profile', 'buyer')->with('post_jobs')->first()->toArray();
            return view('message.popups.edit_contract_pop_up', compact('contract_detail'))->render();
        }else{
           return 0;
        }
    }
   
   public function markAsCompleteConfirmPopUp($id){
       if(!ctype_digit($id)){
            return 0;
        }
       $contract = Contract::getContractDetail($id);
       if($contract->exists()){
           $contract_detail = $contract->with('expert.user_profile')->with('post_jobs')->first()->toArray();
           return view('message.popups.mark_project_complete_pop_up', compact('contract_detail'))->render();
       }else{
           return 0;
       }
   }
   
   public function applyCouponPopUp($id){
       if(!ctype_digit($id)){
            return 0;
        }
       $contract = Contract::getContractDetail($id);
       if($contract->exists()){
           $contract_detail = $contract->with('expert.user_profile')->with('post_jobs')->first()->toArray();
           return view('message.popups.apply_coupon_pop_up', compact('contract_detail'))->render();
       }else{
           return 0;
       }
   }
   
   public function addLocalTimeZoneToSession(Request $request){
       $input_data = $request->all();
       Session::forget('timezone');
       if(_count($input_data) && !empty($input_data['timezone'])){
           $time_zone = trim($input_data['timezone']);
       }else{
           $time_zone = 'UTC';
       }
       session(['timezone' => $time_zone]);
   }
   
   public function downloadPdf(Request $request, $id){
        $contract = Contract::findByCondition(['id' => $id], ['contractDeliverables', 'buyer'], [], 'first')->toArray();
        $payment_calculation = contractPaymentCalculationWithoutCoupon($contract['rate']);
        $contract_rate = convertToCurrencySymbol($contract['rate_variable']) . number_format($contract['rate'], 2);
        $amount_to_be_paid_to_expert = convertToCurrencySymbol($contract['rate_variable']) .
            number_format($payment_calculation['amount_to_be_paid_to_expert'], 2);
        $mm_fee = convertToCurrencySymbol($contract['rate_variable']) . number_format($payment_calculation['mm_fee'], 2);
        $vat_amount = 0;
        $total = convertToCurrencySymbol($contract['rate_variable']) . number_format($contract['rate'], 2);

        $project_label = ($contract['parent_contract_id'])? (($contract['type'] == 'project')?'Project': 'Package').' Extension':(($contract['type'] == 'project')?'Project': 'Package');
        $options = ['contract' => $contract,
            'contract_rate' => $contract_rate,
            'company_name' => $contract['buyer']['company_name'],
            'project_name' => ($contract['service_package_id']) ? getServicePackageName($contract['service_package_id'], 0) : getJob($contract['job_post_id'], 0),
            'payment_calculation' => $payment_calculation,
            'buyer_name' => getUserDetails($contract['buyer_id'])['name'],
            'buyer_email' => getUserDetails($contract['buyer_id'])['email'],
            'expert_name' => getUserDetails($contract['user_id'])['name'],
            'expert_email' => getUserDetails($contract['user_id'])['email'],
            'amount_to_be_paid_to_expert' => $amount_to_be_paid_to_expert,
            'mm_fee' => $mm_fee,
            'vat_amount' => $vat_amount,
            'total' => $total,
            'project_label' => $project_label,
        ];
        $pdf = PDF::loadView('message.pdfs.contractpdf', $options);
        return $pdf->download('contract.pdf');
   }
   
   public function scriptToUpdateUniqueIdInContracts(){
        $contracts = Contract::all()->toArray();
        $count = 0;
        foreach($contracts as $contract){
            if(Contract::updateContractInformation($contract['id'], ['unique_id' => 'MM'.strtotime($contract['created_at'])])){
                $count++;
            }
        }
        return $count.' records updated!';
   }
   
   public function redirectFromCustomUrl($type, $id){
       if($type=='project'){
           return redirect("buyer/messages/project/$id");
       }
       if($type=='expert-profile'){
           return redirect("buyer/expert-profile/$id");
       }
       if($type=='project-view'){
           return redirect("projects_view?sellerid=$id");
       }
       if($type=='service-package'){
           return redirect("servicepackage/$id");
       }
       if($type=='service-package-detail'){
           return redirect("servicepackage/detail/$id");
       }
   }
   
    public function redirectBuyerToProjectBasedMessaging(Request $request)
    {
        $input_data = $request->all();
        if (!_count($input_data))
            return redirect("myprojects");
        if(array_key_exists('communication_id', $input_data))
        {
            $communication_detail = Communication::fetchCommunications(['id' => $input_data['communication_id']], 'first');
            if(empty($communication_detail))
                return redirect("myprojects");
            $id = ($communication_detail['type']==config('constants.PROJECT'))?$communication_detail['job_post_id']:$communication_detail['service_package_id'];
            return redirect(getBuyerMessageLink() . "/" . $communication_detail['type'] . "/".$id."?communication_id=" . $communication_detail['id']);
        }
    }

}
