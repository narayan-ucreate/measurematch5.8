<?php

namespace App\Http\Controllers;
use App\Components\Common;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Carbon\Carbon;
use Validator;
use App\Model\{User,UsersCommunication,Communication,PostJob,BuyerProfile,JobsSkill,Draft,Deliverable,ShareProject,ReferralExpert,TypeOfOrganization,Contract};
use App\Components\{Email, PostProjectComponent, SegmentComponent, WebflowComponent};

class PostController extends Controller {

    /**
     * Construct Method
     */
    public function __construct() {
        $this->middleware('auth', ['except' => ['createFromHomePage', 'saveProjectFromHome', 'postProjectFromHome']]);
        $ssl = getenv('APP_SSL');
    }

    public function postJob(Request $request) {
        return Redirect::To('project/create');
    }
   
    public function saveProject(Request $request) {
        $post_form_data = $request->all();
        $validator = $this->validations($post_form_data);
        if ($validator->fails()) {
            if (isset($post_form_data['rebook_project'])) {
                return response($validator->messages(), 422);
            }
            return Redirect::back()->withErrors($validator)
                ->withInput();
        } else {
            $is_edit=(isset($request->project_id))?True:false;
            $is_edit_by_admin=isset($request->is_admin_edit)?True:false;
            (isset($request->is_admin_edit)) ? $buyer_id=$request->buyer_id: $buyer_id=Auth::user()->id;
            $post_form_data = stripScriptingTags($post_form_data, ['description']);
            $post_job = $this->prepareData($post_form_data, $is_edit,$buyer_id);
            $post_job = $this->removeUnwantedKeysFromProjectObject($post_job);
            $redirect_url = $request->get('redirect_url') ?? '';
            if ($post_job->save()) {
                if (array_key_exists('type_of_organization', $post_form_data) && !empty(trim($post_form_data['type_of_organization']))) {
                    BuyerProfile::updateBuyerInformation($buyer_id, ['type_of_organization_id' => $post_form_data['type_of_organization']]);
                }
                PostProjectComponent::saveDeliverables($post_job->id, $post_form_data['deliverables'],'project',$is_edit);
                PostProjectComponent::saveSkills($post_form_data, $post_job->id,$is_edit);
                if (!$is_edit && !$is_edit_by_admin)
                    (new SegmentComponent)->projectTracking($buyer_id, $post_job->id, $post_job->job_title, "Project Created");
                if (isset($post_form_data['rebook_project'])) {
                    $communication = new Communication;
                    $communication->user_id = $post_form_data['expert_id'];
                    $communication->buyer_id = Auth::user()->id;
                    $communication->job_post_id = $post_job->id;
                    $communication->status = 1;
                    $communication->type = config('constants.PROJECT');
                    if ($communication->save()) {
                        $common_component = new Common();
                        $common_component->initateConversationBuyerForReBooking(Auth::user()->id, $post_form_data['expert_id'], $communication->id, $post_job->job_title);
                        $common_component->initateConversationExpertForReBooking(Auth::user()->id, $post_form_data['expert_id'], $communication->id, $post_job->job_title);
                        $common_component->sendRebookedProjectMessage(Auth::user()->id, $post_form_data['expert_id'], $communication->id);
                        Email::projectRebookEmailNotificationToExpert(
                            ['expert_id' => $post_form_data['expert_id'],
                            'buyer_first_name' => ucfirst(explode(' ', Auth::user()->name)[0]),
                            'buyer_id' => Auth::user()->id,
                            'message_url' => route('expertMessage').'?communication_id='.$communication->id
                            ]
                        );
                    }
                    return $post_job->id;
                }
                if ($this->sendEmails($post_job, $is_edit))
                    return $this->redirectAfterSaveProject($post_job, $is_edit, $is_edit_by_admin, $redirect_url);

            }
        }
    }
    private function validateSkillsAndTools($post_job){
       $post_job['skills']= isset($post_job['skills']) ? explode(',',$post_job['skills']) : [];
       $post_job['tools']= isset($post_job['tools']) ? explode(',',$post_job['tools']) : [];
       return $post_job;
    }

    private function removeUnwantedKeysFromProjectObject($post_job){
        unset($post_job->skills);
        unset($post_job->tools);
        unset($post_job->type_of_organization);
        unset($post_job->deliverables);
       return $post_job;
    }
    private function redirectAfterSaveProject($post_job, $is_edit, $is_edit_by_admin, $redirect_url='') {
        if ($is_edit_by_admin) {
            return $this->redirectAfterAdminEditsProject($post_job->publish, $redirect_url);
        } else {
            if ($post_job->publish == config('constants.APPROVED') && $is_edit == false)
               return redirect('buyer/experts/search')->with('status', config('constants.VIEW_JUST_POSTED_PROJECT'))->with('post_id', $post_job->id);
            if ($post_job->publish == config('constants.PROJECT_PENDING') && $is_edit == false)
                return redirect('myprojects')->with('project-posted', config('constants.VIEW_JUST_POSTED_PROJECT'));
            return redirect('myprojects');
        }
    }

    private function redirectAfterAdminEditsProject($publish_status, $redirect_url = '')
    {
        if ($redirect_url != '')
            return Redirect::To('admin/'.$redirect_url)->with('success', config('constants.PROJECT_UPDATED'));
        if ($publish_status == config('constants.PUBLISHED'))
              return Redirect::To('admin/liveProjects')->with('success', config('constants.PROJECT_UPDATED'));
        if ($publish_status == config('constants.PROJECT_PENDING'))
             return Redirect::To('admin/pendingProjects')->with('success', config('constants.PROJECT_UPDATED'));

    }

    public function saveProjectFromHome(request $request){
        $post_form_data = $request->all();
        $validator = $this->validations($post_form_data);
        if ($validator->fails()) { 
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            try {
                $post_job = $this->prepareData($post_form_data, FALSE);
                $json = json_encode($post_job);
                setcookie('project_from_home', $json, time() + (12000),'/');
                return Redirect::To('/postproject/finalstep');
            } catch (\Exception $e) {
                return Redirect::back()->withErrors(['Something went wrong!'])->withInput();
            }
        }
    }
    
    private function validations($post_form_data){
        
        $post_form_data=$this->validateSkillsAndTools($post_form_data);
        $messages = [
            'title.required' => 'Please enter your job title',
            'description.required' => 'Please enter description',
            'deliverables.required' => 'Please enter the deliverables',
            'work_location.required' => 'Please enter location preference',
            'office_location.required' => 'Please enter office location',
            'end_date.required' => 'Please enter the project start date',
            'project_duration.required' => 'Please enter the project duration',
            'rate_variable.required' => 'Please choose project budget type',
            'budget_approval_status.required' => 'Please choose project budget approved status',
            'skills.*' => 'Special characters not allowed in skill/tools',
            'tools.*' => 'Special characters not allowed in skill/tools',
        ];

        if (isset($post_form_data['rebook_project'])) {
            $validations = [
                'title' => 'required',
                'description' => 'required',
                'deliverables.*' => 'required',
                'currency' => 'required'
            ];
        } else {
            $validations = [
                'title' => 'required',
                'description' => 'required',
                'deliverables' => 'required',
                'work_location' => 'required',
                'office_location' => 'required',
                'end_date' => 'required',
                'project_duration' => 'required',
                'rate_variable' => 'required',
                'budget_approval_status' => 'required',
                'skills.*'=> 'regex:/^[a-zA-Z0-9\.\,\-\/\s]+$/',
                'tools.*'=> 'regex:/^[a-zA-Z0-9\.\,\-\/\s]+$/',

            ];
        }
        return Validator::make($post_form_data, $validations, $messages);
    }
    
    private function prepareData($post_form_data, $is_edit,$buyer_id=NULL){
        $post_job = $this->getProjectObject($post_form_data);    
        if(Auth::check()) $post_job->user_id = $buyer_id;
        $post_job = $this->addNewProjectDefaultFields($post_job,$post_form_data,$is_edit);
        $post_job->job_title = $post_form_data['title'];
        $post_job->description = $post_form_data['description'];
        $post_job->remote_id = $post_form_data['work_location'] ?? null;
        $post_job->rebook_project = isset($post_form_data['rebook_project']) ? true : false;
        $post_job->project_duration = $post_form_data['project_duration'] ?? null;
        if(!empty($post_form_data['end_date'])) $post_job->job_end_date = date('Y-m-d', strtotime($post_form_data['end_date']));
        if(!empty($post_form_data['visibility_date'])) $post_job->visibility_date = date('Y-m-d', strtotime($post_form_data['visibility_date']));
        $post_job->rate_variable = $post_form_data['rate_variable'] ?? null;
        $post_job->currency = isset($post_form_data['currency']) ? $post_form_data['currency'] :$post_job->currency;
        $post_job->office_location = $post_form_data['office_location'] ?? null;
        $post_job->skills = $post_form_data['skills'] ?? [];
        $post_job->tools = $post_form_data['tools'] ?? [];
        $post_job->type_of_organization = $post_form_data['type_of_organization'] ?? null;
        $post_job->deliverables = $post_form_data['deliverables'];
        $post_job->budget_approval_status = $post_form_data['budget_approval_status'] ?? null;
        $post_job=  $this->getProjectRate($post_job,$post_form_data);
        $post_job=  $this->getProjectAttachements($post_job,$post_form_data,$is_edit);
        (array_key_exists('hide_company_name', $post_form_data) && $post_form_data['hide_company_name']==1)?$post_job->hide_company_name = TRUE:$post_job->hide_company_name = FALSE;
        return $post_job;
    }
    private function getProjectObject($post_form_data){
         if(isset($post_form_data['project_id'])){
            $post_job = PostJob::find($post_form_data['project_id']);
            $post_job->id = $post_form_data['project_id'];
        }else{
            $post_job = new PostJob;    
        }
        return $post_job;
    } 
    private function addNewProjectDefaultFields($post_job, $post_form_data, $is_edit){
        if(!$is_edit){
            $post_job->post_is_negotiable = 0;
            $job_number = checkUniqueJobNums();
            $post_job->job_num = $job_number;
            $post_job->job_start_date = date('Y-m-d');
            $post_job->publish = isset($post_form_data['rebook_project']) ? config('constants.PUBLISHED') : config('constants.PROJECT_PENDING');
            $post_job->publish_date = date('Y-m-d H:i:s');
        } else {
            $post_job->publish = $post_form_data['publish'];
        }
        return $post_job;
    } 
    private function getProjectRate($post_job,$post_form_data){
        if (isset($post_form_data['rate_variable']) && $post_form_data['rate_variable'] == 'fixed') {
            $post_job->rate = str_replace(",", "", filter_var($post_form_data['project_budget'], FILTER_SANITIZE_NUMBER_INT));
        } elseif (isset($post_form_data['rate_variable']) && $post_form_data['rate_variable'] == 'daily_rate') {
            $post_job->rate = str_replace(",", "", filter_var($post_form_data['daily_project_budget'], FILTER_SANITIZE_NUMBER_INT));
        }  else {
            $post_job->rate = 0;
        }
        return $post_job;
    } 
    private function getProjectAttachements($post_job,$post_form_data,$is_edit){
         $file_path = [];
        if(array_key_exists('attachments', $post_form_data) && _count($post_form_data['attachments'])
                && !empty($post_form_data['attachments'][0])){
            foreach($post_form_data['attachments'] as $attachment){
                $file_path[] = uploadFile($attachment);
            }
        }
        if(_count($file_path)){
            $post_job->upload_document = json_encode($file_path);
        }else{
            if($is_edit && $post_form_data['attachments_from_db']==""){
            $post_job->upload_document = NULL;
            }
        }
        return $post_job;
    } 

    private function sendEmails($post_job, $is_edit){
        $email_componenet = new Email();
        if($is_edit){
            return TRUE;
        }
        $all_projects_count = isFirstProject();
        $post_id = $post_job->id;
        $id = Auth::user()->id;
        $user_details = User::find($id);
        $post_detail = PostJob::find(trim($post_id));
        if (($post_detail->publish != config('constants.NOT')) && ($post_detail->publish != config('constants.DRAFTED'))) {
            $email_componenet->waitingProjectApprovalEmail(['buyer_id' =>$user_details->id , 'project_id' => $post_id]);
            if($all_projects_count == 0){
                $email_componenet->sendFirstPostProjectMail(['buyer_id' =>$id , 'project_id' => $post_job->id]);
            }else{
                $email_componenet->sendPostProjectMail(['buyer_id' =>$id , 'project_id' => $post_job->id]);
            }
        }
        if ($post_job->publish == config('constants.APPROVED')) {
            if (is_numeric($post_job->rate)) {
                $post_job->rate = number_format($post_job->rate, 2);
            }
        }
        return TRUE;
    }

    /**
     * Update Draft Method
     *
     * @param Request $request
     *
     */
    public function updatedraft(Request $request) {
        $user_id = Auth::user()->id;
        $id = $request['id'];
        $draft = new Draft;
        $draft->job_post_id = $id;
        $draft->user_id = $user_id;
        if ($draft->save())
            $post_detail = PostJob::find(trim($id));
        $post_detail->publish = "2";
        if ($post_detail->save()) {
            echo 1;
            die;
        }
    }

    public function shareProject(Request $request) {
        $project_form_data = $request->all();
        $created = Carbon::now();
        $share_expert['expert_id'] = Auth::user()->id;
        $share_expert['shared_project_id'] = $project_form_data['projectId'];
        $share_expert['referred_expert_email'] = $project_form_data['email'];
        $share_expert['created_at'] = $created;
        $share_expert['updated_at'] = $created;
        ShareProject::insert($share_expert);
        $email_status = checkEmailStatus($project_form_data['email']);
        if ($email_status == 0) {
            $referral_expert['expert_id'] = Auth::user()->id;
            $referral_expert['referral_expert_name'] = $project_form_data['expert_name'];
            $referral_expert['referral_expert_email'] = $project_form_data['email'];
            $referral_expert['referral_status'] = 0;
            $referral_expert['created_at'] = $created;
            $referral_expert['updated_at'] = $created;
            $response = ReferralExpert::insert($referral_expert);
        }
        $this->expertEmailForShare($project_form_data);
        $this->referredExpertEmail($project_form_data);
        return 1;
    }

    public function expertEmailForShare($project_details) {
        $email_status = checkEmailStatus($project_details['email']);
        try {            
            if ($email_status == 1) {
                $response = \App\Components\Email::shareEmailWithRegisteredUser(['expert_name'=>Auth::user()->name,'referral_expert_name'=>$project_details['expert_name'],'post_job_id'=>$project_details['projectId'],'expert_email'=>Auth::user()->email]);
            } else {
                $response = \App\Components\Email::shareEmailWithUnregisteredUser(['expert_name'=>Auth::user()->name,'referral_expert_name'=>$project_details['expert_name'],'post_job_id'=>$project_details['projectId'],'expert_email'=>Auth::user()->email]);
            }
            if ($response) {
                return 1;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function referredExpertEmail($project_details) {
        $email_status = checkEmailStatus($project_details['email']);
         try {
            if ($email_status == 1) {
                $response = \App\Components\Email::referredRegisteredExpertEmail(['expert_name'=>Auth::user()->name,'expert_id'=>Auth::user()->id,'referral_expert_name'=>$project_details['expert_name'],'post_job_id'=>$project_details['projectId'],'expert_email'=>$project_details['email']]);
            } else {
                $response = \App\Components\Email::referredUnregisteredExpertEmail(['expert_name'=>Auth::user()->name,'expert_id'=>Auth::user()->id,'referral_expert_name'=>$project_details['expert_name'],'post_job_id'=>$project_details['projectId'],'expert_email'=>$project_details['email']]);
            }
            if ($response) {
                return 1;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function checkShareProject(Request $request) {
        $project_details = $request->all();
        $response = 0;
        $share_data = ShareProject::where([['referred_expert_email', $project_details['email']], ['shared_project_id', $project_details['projectId']], ['expert_id', Auth::user()->id]])->count();
        if ($share_data > 0) {
            $response = 1;
        }
        return $response;
    }

    public function scriptToUpdatePublishDate() {
        die('not allowed');
        $projects = PostJob::createdAtDateOfProjects()->get()->toArray();
        $count = 0;
        foreach ($projects as $key => $project) {
            if (PostJob::updatePublishedDate($project['id'], $project['created_at'])) {
                $count++;
            }
        }
        echo $count . " records updated.";
        die();
    }
    
    public function createProject()
    {
        $page_already_visited = FALSE;
        if(session()->has('post_project_page_visited_' . Auth::user()->id)){
            $page_already_visited = TRUE;
        }else{
            session()->put('post_project_page_visited_'.Auth::user()->id, 1);
        }
        //to check if buyer has expressed interest in service package
        $expression_of_interest_count = Communication::fetchCommunications(['buyer_id' => Auth::user()->id], 'count');
        $type_of_org_list = TypeOfOrganization::listAll();
        $buyer_detail = BuyerProfile::getBuyerInformation(Auth::user()->id);
        return view('buyer.postjobs.new_post_project', compact('type_of_org_list', 'buyer_detail', 'expression_of_interest_count', 'page_already_visited'));
    }
    
    public function editProject(Request $request) {
        if (!isBuyerAndVendor(Auth::user()->user_type_id)) {
            return view('errors.404');
        }
        if (!empty(Auth::user() && Auth::user()->id) && isBuyerAndVendor(Auth::user()->user_type_id)) {
            $type_of_org_list = TypeOfOrganization::listAll();
            $project = PostJob::find(trim($request->id));
            if(_count($project) && (Auth::user()->id != $project->user_id)) return Redirect::To('/redirectlogin');
            $buyer_detail = BuyerProfile::getBuyerInformation(Auth::user()->id);
            $office_location = (!empty($project->office_location))?$project->office_location:$buyer_detail[0]['office_location'];
            $deliverables = Deliverable::findByCondtion(['post_job_id'=>$request->id,'type'=>'project']);
            $tools= JobsSkill::getProjectToolsByProjectId($request->id);
            $skills= JobsSkill::getProjectSkillsByProjectId($request->id);
            $is_currency_editable= (_count(Contract::getContractStatus($request->id)))?'currency-edit':'';
            return view('buyer.postjobs.editproject', compact('type_of_org_list', 'buyer_detail','project',
                       'deliverables','office_location','tools','skills','is_currency_editable'));
        } else {
            return Redirect::To('/redirectlogin');
        }
    }
    public function createFromHomePage() {
        if(Auth::check()){
            return Redirect::To('/redirectlogin');
        }
        $type_of_org_list = TypeOfOrganization::listAll();
        return view('buyer.postjobs.post_project_from_home', compact('type_of_org_list'));
    }
    public function postProjectFromHome(){
        return Redirect::To('homepage/postproject'); 
    }
    public function scriptToUpdateVisibilityDate()
    {
        $projects = PostJob::getAllProjectsPublishedDate()->toArray();
        $count = 0;
        foreach ($projects as $key => $project)
        {
            if (!empty($project['publish_date']))
            {
                $visibility_date = date("Y-m-d H:i:s", strtotime("+3 day", strtotime($project['publish_date'])));
                if (PostJob::updatePostData(['visibility_date' => $visibility_date], $project['id']))
                    $count++;
            }
        }
        return $count . " records updated.";
    }

    public function approveWebflow(Request $request){
        $form_data = $request->all();
        $id = $form_data['project_id'];
        $project['title'] = $form_data['project_title'];
        $project['description'] = $form_data['project_description'];
        $webflow_component = new WebflowComponent;

        $project_object = $webflow_component->createProjectObject($project);
        $new_item = $webflow_component->createSingleItem(getenv('WEBFLOW_PROJECTS_COLLECTION_ID'), $project_object);
        if (isset($new_item['_id'])){
            $webflow_component->saveProject($new_item, $id);
            return['success'=>true];
        }
        return ['success'=>false, 'response' => $new_item];
    }
}
