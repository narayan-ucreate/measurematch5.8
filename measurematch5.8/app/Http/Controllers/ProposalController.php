<?php

namespace App\Http\Controllers;

use App\Model\Communication;
use App\Model\Contract;
use App\Model\PostJob;
use Illuminate\Http\Request;
use App\Model\TemporaryProposal;
use App\Http\Requests\Proposal\
{
    CreateDeliverable,
    StoreTerm
};
use Validator;
use App\Model\{
    CountryVatDetails,
    BusinessInformation,
    BusinessDetails,
    User,
    BusinessAddress
};

class ProposalController extends Controller
{
    private $user_id;
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user_id = \Auth::user()->id;
            return $next($request);
        });
    }

    public function index($communication_id, $step=1 ) {
        $buyer_information = (new Communication())->getCommunication($communication_id);
        $project_type= $buyer_information->type;
        $project_id = ( $buyer_information->type == config('constants.PROJECT')) ? $buyer_information->job_post_id : $buyer_information->service_package_id;
        $business_information = (new BusinessInformation)->getUserBusinessInformation($this->user_id);
        if (!_count($business_information)) {
            return redirect(route('vat-details-page', [$communication_id]));
        }
        $temporary_proposal_object = (new TemporaryProposal());
        $project_info = $buyer_name = '';
        if ($project_type == config('constants.PROJECT')) {
            $project_info = (new PostJob())->whereId($project_id)->first(['currency', 'user_id']);
        }
        $deliverables = $temporary_proposal_object->getDetails($communication_id);
        $completed_steps = isset($deliverables['details']) ? json_decode($deliverables['details'], 1) : false;
        $first_step = $completed_steps['step-1-status'] ?? false;
        $is_completed_previous_step = $completed_steps['step-'.($step-1).'-status'] ?? false;
        $is_completed_previous_step = $step == 1 ? true : $is_completed_previous_step;
        if (!$first_step && $step != 1 || !$is_completed_previous_step) {
            $redirect_to_step = $step == 1 ? $step : $step - 1;
            return redirect(route('send-proposal', [$communication_id, $redirect_to_step]));
        }
        $user_data = User::getUserById($this->user_id);
        if (_count($business_information)) {
            $business_type = $business_information->type;
            $business_details = $business_information->businessDetails;
            $business_address = $business_information->businessAddress;
        }
        $company_name = $company_website = $birth_day = $birth_month = $birth_year = '';
        if (isset($business_details->company_name))
        {
            $company_name = $business_details->company_name;
            $company_website = $business_details->company_website;
        }
        if (!isset($business_details->company_name)
            && isset($user_data[0]['buyer_profile']['company_name'])
            && array_key_exists(0, $user_data)
            && array_key_exists('buyer_profile', $user_data[0])
            )
        {
            $company_name = $user_data[0]['buyer_profile']['company_name'];
            $company_website = $user_data[0]['buyer_profile']['company_url'];
        }
        $related_contract = Communication::fetchCommunications(['id' => $communication_id], 'first', [], ['relatedContract']);
        $contract_info = '';
        if(!empty($related_contract['related_contract']))
            $contract_info = (new Contract())->getJobOrPackageContract($project_id, $project_type);
        $countries = (new CountryVatDetails)->getAllCountryVatDetails();
        $expert_id = $this->user_id;
        if (!empty($user_data->date_of_birth))
        {
            $birth_day = date('d', strtotime($user_data->date_of_birth));
            $birth_month = date('m', strtotime($user_data->date_of_birth));
            $birth_year = date('Y', strtotime($user_data->date_of_birth));
        }
        return view('proposal.basic',
            compact(
                'deliverables',
                'communication_id',
                'project_info',
                'project_type',
                'user_data',
                'project_id',
                'step',
                'buyer_name',
                'business_type',
                'business_details',
                'business_address',
                'countries',
                'expert_id',
                'buyer_information',
                'company_name',
                'company_website',
                'contract_info',
                'birth_day',
                'birth_month',
                'birth_year'
            ));
    }

    public function storeDeliverable($communication_id, $buyer_information, $inputs, $project_id, $project_type) {
        $inputs['quantity'] = $inputs['quantity'] > 0 ? $inputs['quantity'] : 1;
        $inputs['price'] = preg_replace('/\D/', '', $inputs['price']);
        $total_values = $inputs['quantity'] * $inputs['price'];
        $project_info = (new PostJob())->whereId($project_id)->first();
        $buyer_user_id = $buyer_information->buyer_id;
        $expert_inforamation = (new BusinessInformation())->getUserBusinessInformation($this->user_id);
        $buyer_inforamation = (new BusinessInformation())->getUserBusinessInformation($buyer_user_id);
        $buyer_vat_status = (empty($buyer_inforamation->businessDetails->vat_country)) ? FALSE : TRUE;
        $vat_details = calculateContractVATValues($total_values,
            $expert_inforamation->businessDetails->vat_status ?? '',
            $expert_inforamation->businessDetails->vat_country ?? '',
            $buyer_vat_status ?? '',
            $buyer_inforamation->businessDetails->vat_country ?? '');
        $data_to_be_update = [
            'vat' => $vat_details['vat'],
            'subtotal' => $vat_details['subtotal'],
            'total_buyer_will_pay' => $vat_details['total_buyer_will_pay'],
            'mm_fee' => $vat_details['mm_fee'],
            'mm_fee_vat' => $vat_details['mm_fee_vat'],
            'total_expert_will_receive' => $vat_details['total_expert_will_receive'],
            'reverse_charge_invoice' => $vat_details['reverse_charge_invoice'],
            'reverse_charge_mm_fee' => $vat_details['reverse_charge_mm_fee'],
            'title' => $inputs['title'],
            'rate_type' => $inputs['rate_type'],
            'description' => $inputs['description'],
            'price' => $inputs['price'],
            'quantity' => $inputs['quantity'],
        ];
        $update_index = $inputs['update_index'] >= 0 ? $inputs['update_index'] : '';
        $data_to_be_update['project_type'] = $project_type;
        $this->addToTemporaryProposal($communication_id, 'deliverables', $data_to_be_update, $update_index);
    }
    
    private function addToTemporaryProposal($communication_id, $key_to_update, $data_to_be_update, $update_index)
    {   
        $temporary_proposal_object = (new TemporaryProposal());
        $deliverables = $temporary_proposal_object->getDetails($communication_id);
        $previous_details = json_decode($deliverables, true);
        $previous_details = $previous_details['details'] ?? '{}';
        $previous_details = json_decode($previous_details, 1);
                 
        $previous_deliverables = $previous_details[$key_to_update] ?? [];
        if (ctype_digit($update_index) && $update_index >= 0) {
            $updated_index = $update_index;
            $previous_deliverables[$updated_index] = $data_to_be_update;
        } else {
            array_push($previous_deliverables, $data_to_be_update);
        }
        $previous_details[$key_to_update] = $previous_deliverables;
        $data_to_be_update['details'] = json_encode($previous_details, 1);
        $data_to_be_update['communication_id'] = $communication_id;
        if ($deliverables) {
            $temporary_proposal_object
                ->whereCommunicationId($communication_id)
                ->update(['details' => $data_to_be_update['details']]);
        } else {
            $temporary_proposal_object->create($data_to_be_update);
        }
    }

    private function arrayPushAssoc(&$array, $key, $value){
        $array[$key] = $value;
        return $array;
    }
    
    private function arrayPushAssocMultipleValues(&$array, $array_to_inject){
        foreach ($array_to_inject as $key => $value)
        {
            $array[$key] = $value;
        }
        return $array;
    }

    public function manageDeliverable(CreateDeliverable $request, $communication_id) {
        $inputs = $request->all();
        $buyer_information = (new Communication())->whereId($communication_id)->with('buyer')->first();
        $project_type = $buyer_information->type;
        $project_id = $project_id = $buyer_information->type == config('constants.PROJECT') ? $buyer_information->job_post_id : $buyer_information->service_package_id;
        if (isset($inputs['action']) && $inputs['action'] == 'delete_deliverable') {
            $this->deleteFromTemporaryProposal($communication_id, 'deliverables', $inputs['index']);
        } else  if (isset($inputs['action']) && ($inputs['action'] == 'auto-save' || $inputs['action'] == 'update-step-1')) {
            if(array_key_exists('name', $inputs))
                unset($inputs[$inputs['name']]);
            $deliverables = $this->getOnlyDeliverables($communication_id);
            $temporary_proposal_object = (new TemporaryProposal());
            if (_count($deliverables)) {
                if ($inputs['action'] == 'update-step-1')
                {
                    unset($inputs['action']);
                    $deliverables = $this->arrayPushAssocMultipleValues($deliverables, $inputs);
                    unset($inputs['step-1-status'],
                        $inputs['introduction'],
                        $inputs['summary'],
                        $inputs['job_start_date'],
                        $inputs['job_end_date'],
                        $inputs['code_of_conduct'],
                        $inputs['stay_safe_confirm']);
                }
                else
                {
                    $deliverables = $this->arrayPushAssoc($deliverables, $inputs['name'], $inputs['value'] );
                    unset($inputs['action'], $inputs['name'], $inputs['value']);
                }
                $inputs['details'] = json_encode($deliverables, 1);
                $temporary_proposal_object
                    ->whereCommunicationId($communication_id)
                    ->update($inputs);
            } else {
                $inputs['details'] = json_encode([$inputs['name'] => $inputs['value']], 1);
                $inputs['communication_id'] = $communication_id;
                $temporary_proposal_object->create($inputs);
            }
            return 1;
        } else {
            $this->storeDeliverable($communication_id, $buyer_information, $inputs, $project_id, $project_type);
        }
        return $this->getDraftedDeliverables($communication_id, $buyer_information, $project_id, $project_type, $steps = 1);
    }
    
    private function deleteFromTemporaryProposal($communication_id, $key_to_action, $index)
    {
        $temporary_proposal_object = (new TemporaryProposal());
        $deliverables = $this->getOnlyDeliverables($communication_id);
        unset($deliverables[$key_to_action][$index]);
        $encoded_deliverables = json_encode($deliverables, 1);
        $temporary_proposal_object
            ->whereCommunicationId($communication_id)
            ->update(['details' => $encoded_deliverables]);
    }

    private function getOnlyDeliverables($communication_id) {
        $temporary_proposal_object = (new TemporaryProposal());
        $deliverables = $temporary_proposal_object->getDetails($communication_id);
        return json_decode($deliverables['details'], 1);
    }

    private function getDraftedDeliverables($communication_id, $buyer_information, $project_id, $project_type, $step) {
        $deliverables = $this->getOnlyDeliverables($communication_id);
        $project_info = (new PostJob())->whereId($project_id)->first(['currency']);
        $expert_id = $this->user_id;
        return view('proposal.list_deliverable', compact('deliverables', 'project_id', 'communication_id', 'project_info', 'project_type', 'step', 'expert_id', 'buyer_information'));
    }

    public function vatPreview(Request $request, $communication_id) {
        $buyer_information = (new Communication())->whereId($communication_id)->with('buyer')->first();
        $business_information = (new BusinessInformation)->getUserBusinessInformation($this->user_id);
        if (!_count($business_information)) {
            $company_name=$buyer_information->buyer->company_name;
            $buyer_name = $buyer_information->buyer->first_name;
            $countries = (new CountryVatDetails)->getAllCountryVatDetails();
            return view('proposal.vat_preview', compact('countries', 'business_information', 'buyer_name','company_name','communication_id'));
        }
        return redirect(route('send-proposal', [$communication_id, 1]));
    }
    public function storeTerms(StoreTerm $request, $communication_id)
    {
        $form_data = $request->all();
        if (isset($form_data['action']) && $form_data['action'] == 'delete_term')
        {
            $this->deleteFromTemporaryProposal($communication_id, 'terms', $form_data['index']);
        }
        else
        {
            $update_index = $form_data['update_index'] >= 0 ? $form_data['update_index'] : '';
            $data_to_be_update = ['term' => $form_data['term']];
            $this->addToTemporaryProposal($communication_id, 'terms', $data_to_be_update, $update_index);
        }
        return $this->getTermsList($communication_id);
    }
    
    private function getOnlyTerms($communication_id) {
        $temporary_proposal_object = (new TemporaryProposal());
        $terms = $temporary_proposal_object->getDetails($communication_id);
        return json_decode($terms['details'], 1);
    }

    private function getTermsList($communication_id) {
        $deliverables = $this->getOnlyTerms($communication_id);
        return view('proposal.list_terms', compact('deliverables', 'communication_id'));
    }
}
