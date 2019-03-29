<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Authenticatable,
    CanResetPassword;
use Auth;
use Redirect;
use Validator;
use Carbon\Carbon;
use App\Components\{Common, ServicePackageComponent, Email, WebflowComponent};
use App\Model\{ServicePackage,SavedServicePackage,ServicePackageType,Communication,UserProfile,Deliverable,Category};

class ServicePackageController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['publicServicePackage']]);
    }

    public function createServicePackage(Request $request) {
        if (!expertAuth()) {
            return view('errors.404');
        }
        $first_service_package_users = Common::getPopUpStatus();
        $service_package_count = ServicePackage::fetchServicePackages(['user_id' => auth::user()->id], 'count');
        if ($service_package_count > 0) {
            $welcome_service_package_value = TRUE;
        } else {
            $welcome_service_package_value = ($first_service_package_users['service_package_welcome_email']) ?? '';
        }
        $service_package_welcome_popup_count = $first_service_package_users['service_package_welcome_popup_count'];
        if (array_key_exists('service_package_welcome_popup_count', $first_service_package_users) && !empty(trim($first_service_package_users['service_package_welcome_popup_count'])) && ($first_service_package_users['service_package_welcome_popup_count'] > config('constants.MAX_WELCOME_POPUP_COUNT'))) {
            $welcome_service_package_value = isset($first_service_package_users['service_package_welcome_email']) ? TRUE : FALSE;
        } else {
            updateUserSetting(['service_package_welcome_popup_count' => ($first_service_package_users['service_package_welcome_popup_count'] + 1 )]);
        }
        $featured_listing = ServicePackageType::listNameId(['is_featured' => TRUE]);
        $categories = Category::getAllCategories();
        $drafts = ServicePackageComponent::getDraftedServicePackages(Auth::user()->id);
        return view('service_package.create_service_package', compact('welcome_service_package_value', 
                'service_package_welcome_popup_count', 'categories', 'drafts', 'featured_listing'));
    }

    public function saveServicePackage(Request $request) {
        if (!expertAuth()) {
            return view('errors.404');
        }
        $service_package_data = $request->all();
        $rules = array(
            'name' => 'required|max:500',
            'description' => 'required|max:10000',
            'subscription_type' => 'required',
            'service_package_type' => 'required',
            'service_package_category' => 'required',
            'buyer_remarks' => 'required',
            'deliverables' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'publish' => 'required',
            'tags' => 'required'
        );

        $validator = Validator::make($service_package_data, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->input());
        } else {
            if($service_package_data['service_package_type'] == 'Other'){
                if(empty($service_package_data['service_package_type_other'])){
                    return back()->withInput($request->input());
                } else {
                    $service_package_data['service_package_type'] = $service_package_data['service_package_type_other'];
                }
            }
            
            $save_service_package = ServicePackageComponent::saveNewServicePackage($service_package_data, Auth::user()->id);
            if ($save_service_package) {
                if($service_package_data['publish'] =="TRUE"){
                    if (hasSubscribed(Auth::user()->id)) {
                        Email::servicePackageCreatedEmailToExpert(['service_package_id' => $save_service_package->id, 'expert_id' => Auth::user()->id]);
                    }
                    Email::servicePackageCreatedEmailToAdmin(['service_package_id' => $save_service_package->id,'expert_id'=>Auth::user()->id]);
                 }else{
                    Email::servicePackageDraftedEmailToAdmin(['service_package_id' => $save_service_package->id,'expert_id'=>Auth::user()->id]);
                 }
                return Redirect::To('servicepackages')->with('package_created', True)->with('publish_status', $service_package_data['publish']);
            } else {
                $welcome_service_package_value = '';
                return Redirect::To('servicepackage/create')->with('message', 'service package could not be saved.Please try again!');
            }
        }
    }

    public function updateServicePackage(Request $request, $id) {
        if (!ctype_digit($id) || (!expertAuth() && !adminAuth())) {
            return view('errors.404');
        }
        $service_package_data = $request->all();
        $user_id = Auth::user()->id;
        if (adminAuth() && array_key_exists('expert_id', $service_package_data)) {
            $user_id = $service_package_data['expert_id'];
        }
        $rules = array(
            'name' => 'required|max:500',
            'description' => 'required|max:10000',
            'subscription_type' => 'required',
            'service_package_type' => 'required',
            'service_package_category' => 'required',
            'buyer_remarks' => 'required',
            'deliverables' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'publish' => 'required',
            'tags' => 'required'
        );

        $validator = Validator::make($service_package_data, $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput($request->input());
        } else {
            if($service_package_data['service_package_type'] == 'Other'){
                if(empty($service_package_data['service_package_type_other'])){
                    return back()->withInput($request->input());
                } else {
                    $service_package_data['service_package_type'] = $service_package_data['service_package_type_other'];
                }
            }
           
            $is_drafted=(ServicePackage::getServicePackage($id)->publish=="")?True:False;
            $save_service_package = ServicePackageComponent::updateExisitngServicePackage($id, $service_package_data, $user_id);
            if ($save_service_package) {
                if (adminAuth()) {
                    if (array_key_exists('approved', $service_package_data) || array_key_exists('draft', $service_package_data) || array_key_exists('all_draft', $service_package_data)) {
                        if (isset($service_package_data['approved']) && $service_package_data['approved'] == 'true') {
                            return Redirect::To('admin/servicepackages')->with('success', config('constants.SP_UPDATED'));
                        } elseif (isset($service_package_data['draft']) && $service_package_data['draft'] == 'true') {
                            return Redirect::To('admin/expert/draftedservicepackages/' . $service_package_data['expert_id'])->with('success', config('constants.SP_UPDATED'));
                        } elseif (isset($service_package_data['all_draft']) && $service_package_data['all_draft'] == 'true') {
                            return Redirect::To('admin/alldraftedservicepackages')->with('success', config('constants.SP_UPDATED'));
                        } else {
                            return Redirect::To('admin/pendingservicepackages')->with('success', config('constants.SP_UPDATED'));
                        }
                    } else {
                      if($is_drafted && $service_package_data['publish']=="True"){
                         return Redirect::To('admin/expert/servicepackages/' . $service_package_data['expert_id'])->with('success', config('constants.SP_PUBLISHED_FROM_DRAFTS'));
                        }else{
                        return Redirect::To('admin/expert/servicepackages/' . $service_package_data['expert_id'])->with('success', config('constants.SP_UPDATED'));
                        }
                    }
                }else if(expertAuth()){
                      if ($is_drafted && $service_package_data['publish'] == "True") {
                        Email::servicePackageCreatedEmailToExpert(['service_package_id' =>$id,'expert_id'=>$user_id]);
                        Email::servicePackageCreatedEmailToAdmin(['service_package_id' => $id,'expert_id'=>$user_id]); 
                        return Redirect::To('servicepackages')->with('package_updated', True)->with('success', config('constants.SP_PUBLISHED_FROM_DRAFTS'));
                        }
                    }
                return Redirect::To('servicepackages')->with('package_updated', True)->with('success', config('constants.SP_UPDATED'));
               
            } else {
                return back()->with('success', config('constants.SP_NOT_UPDATED'));
            }
        }
    }

    public function editServicePackage($id) {
        if(!ctype_digit($id)){
            return view('errors.404');
        }
        if(!ServicePackage::checkExistance(['id' => $id])){
            return view('errors.404');
        }
        $service_package_details = ServicePackage::with('servicePackageType')->findOrFail($id);
        if($service_package_details->user_id != Auth::user()->id) return Redirect::To('/redirectlogin');
        $deliverable = Deliverable::findByCondtion(['service_package_id' => $service_package_details->id,'type' => 'service_package']);
        $service_package_details->deliverables = $deliverable;
        $categories = Category::getAllCategories();
        $drafts = ServicePackageComponent::getDraftedServicePackages(Auth::user()->id);
        $tags = ServicePackageComponent::getServicePackageTags($service_package_details->id);
        $featured_listing = ServicePackageType::listNameId(['is_featured' => TRUE]);
        return view('service_package.edit_service_package', compact('service_package_details', 'drafts', 'categories', 'tags', 'featured_listing'));
    }

    public function expertServicePackageListing() {
        if(!expertAuth()){
            return redirect('/');
        }
        $first_service_package_users = Common::getPopUpStatus();
        $service_package_count = ServicePackage::fetchServicePackages(['user_id' => auth::user()->id], 'count');
        if ($service_package_count > 0) {
            $service_package_listing_show_popup = TRUE;
        } else {
            $service_package_listing_show_popup = ($first_service_package_users['service_package_listing_show_popup']) ?? '';
        }
        $service_package_listing_welcome_popup_count = $first_service_package_users['service_package_listing_welcome_popup_count'];
        if (array_key_exists('service_package_listing_welcome_popup_count', $first_service_package_users) && !empty(trim($first_service_package_users['service_package_listing_welcome_popup_count'])) && ($first_service_package_users['service_package_listing_welcome_popup_count'] > 3)) {
            if (array_key_exists('service_package_listing_show_popup', $first_service_package_users)) {
                $service_package_listing_show_popup = true;
            }
        } else {
            updateUserSetting(['service_package_listing_welcome_popup_count' => ($first_service_package_users['service_package_listing_welcome_popup_count'] + 1 )]);
        }
        $my_service_package_listing = ServicePackage::fetchServicePackages(['user_id' => Auth::user()->id, 'publish' => True], '', ['contract', 'communication']);
        $drafts = ServicePackageComponent::getDraftedServicePackages(Auth::user()->id);
        return view('service_package.my_service_package_listing', compact('my_service_package_listing', 'drafts', 'service_package_listing_show_popup', 'service_package_listing_welcome_popup_count'));
    }
    
    public function servicePackageDetails(Request $request) {
        $service_package_details = ServicePackageComponent::servicePackageDetails($request->id);
        if(!_count($service_package_details)||(Auth::user()->user_type_id != 1)){
            return view('errors.404');
        }
        if(Auth::user()->id != $service_package_details[0]['user_id']){
            return view('errors.404');
        }
        $deliverables = Deliverable::getDeliverablesByServicePackage($request->id);
        $service_package_eoi = ServicePackageComponent::servicePackageEoi($request->id);
        $service_package_contracts = ServicePackageComponent::servicePackageContracts($request->id);
        $service_package_feedbacks = ServicePackageComponent::servicePackageFeedbacks($request->id);
        return view('service_package.my_service_package_detail', compact('service_package_details', 'deliverables', 'service_package_eoi', 'service_package_contracts', 'service_package_feedbacks'));
    }
    public function hideServicePackage(Request $request) {
        return ServicePackageComponent::hideServicePackage($request->service_package_id);
    }

    public function unHideServicePackage(Request $request) {
        return ServicePackageComponent::unHideServicePackage($request->service_package_id);
    }

    public function approveServicePackage(Request $request) {
        $webflow_component = new WebflowComponent;
        $new_service_package = ServicePackage::find($request->service_package_id);
        $service_package_object = $webflow_component->createServicePackageObject($new_service_package);
        $new_item = $webflow_component->createSingleItem(getenv('WEBFLOW_SERVICE_PACKAGES_COLLECTION_ID'), $service_package_object);
        if (isset($new_item['_id'])){
            $webflow_component->saveServicePackage($new_item, $new_service_package->id);
        }
        $service_package_approved=ServicePackageComponent::approveServicePackage($request->service_package_id);
        if($service_package_approved){
         Email::servicePackageApprovedEmailToExpert(['service_package_id' => $request->service_package_id]);
         return['success'=>true];
        }else{
        return['success'=>false];
        }
       
    }

    public function approveWebflow(Request $request) {
        $webflow_component = new WebflowComponent;
        $new_service_package = ServicePackage::find($request->service_package_id);
        $service_package_object = $webflow_component->createServicePackageObject($new_service_package);
        $new_item = $webflow_component->createSingleItem(getenv('WEBFLOW_SERVICE_PACKAGES_COLLECTION_ID'), $service_package_object);
        if (isset($new_item['_id'])){
            $webflow_component->saveServicePackage($new_item, $new_service_package->id);
            return['success'=>true];
        }
        return['success'=>false];
    }

    public function disApproveServicePackage(Request $request) {
        return ServicePackageComponent::disApproveServicePackage($request->service_package_id);
    }

    public function getTags(Request $request) {
        return \App\Model\Tag::serachTagsByName($request['term']);
    }

    public function archieveEOI(Request $request) {
        return ServicePackageComponent::archiveEoi($request->service_package_communication_id);
    }

    public function unArchieveEOI(Request $request) {
        return ServicePackageComponent::unArchiveEoi($request->service_package_communication_id);
    }

    public function getTypes(Request $request) {
        $input_data = $request->all();
        if(isset($input_data) && array_key_exists('exclude_featured', $input_data)){
            $condition = ['added_by' => NULL, 'is_featured' => FALSE];
        }else{
            $condition = ['added_by' => NULL];
        }
        return ServicePackageType::listTypes($condition);
    }

    public function types() {
        if (!Auth::user()->admin_approval_status) {
           return redirect('/project/create');
        }
        $welcome_pop_up_checked = FALSE;
        $first_service_package_users = Common::getPopUpStatus();
        if (isset($first_service_package_users['buyer_search_service_package_welcome_pop_up']) && $first_service_package_users['buyer_search_service_package_welcome_pop_up']) {
            $welcome_pop_up_checked = TRUE;
        }
        $keys = array_keys(ServicePackageType::listNameId(['is_featured' => TRUE]));
        $other_records_count = ServicePackage::getOther('', $keys, [], ['count' => 'count']);
        $service_package_types = ServicePackageType::fetchWithServicePackageCount(['is_featured' => 'TRUE']);
        return view('service_package.searchpackage', compact('service_package_types', 'welcome_pop_up_checked', 'other_records_count'));
    }
    
    public function servicePackageTypeWiseList(Request $request, $id){
        if(!(isVendor()) || (!ctype_digit($id) && ($id != config('constants.OTHER') && $id != config('constants.SEARCH')))){
            return view('errors.404');
        }
        if ($request->session()->has('last_page')) {
           if(session('last_page') == 'buyer_service_package_listing'){
               $request->session()->forget('last_page');
           }
        }
        $saved_packages_list = SavedServicePackage::savedPackageList(Auth::user()->id);
        $limit = config('constants.SERVICE_PACKAGE_SEARCH_PER_PAGE');
        $show_load_more_button = TRUE;
        $featured_list[''] = config('constants.ALL_SERVICE_PACKAGE_TITLE');
        $featured_listing = ServicePackageType::listNameId(['is_featured' => TRUE], ['related_model' => TRUE]);
        foreach($featured_listing as $key=>$value){
            $featured_list[$key]=$value;
        }
        $offset = 0;
        if(isset($request->number_of_listed_packages)){
            $offset = $request->number_of_listed_packages;
        }
        $title = config('constants.ALL_SERVICE_PACKAGE_TITLE');
        if(isset($request->selected_featured_package) && !empty($request->selected_featured_package) && isset($featured_list[$request->selected_featured_package])){
            $title = $featured_list[$request->selected_featured_package].' Service Packages';
        }
        $result = $this->getServicePackages($id, ['limit' => $limit, 'offset' => $offset], $featured_list, $request);
        $service_packages = [];
        $total_records = 0;
        if(_count($result)){
            $service_packages = $result['service_packages'];
            if(_count($service_packages)){
                $service_packages = $service_packages->toArray();
                $total_records = $service_packages[0]['total'];
            }
            
            $title = $result['title'];
        }
        if($offset+$limit>=$total_records || _count($service_packages)<$limit){
            $show_load_more_button = FALSE;
        }
        $other_records_count = ServicePackage::getOther('', array_keys(ServicePackageType::listNameId(['is_featured' => TRUE])), [], ['count' => 'count']);
        if($other_records_count){
            $featured_list['other'] = config('constants.OTHER_AWESOME');
        }
        
        $is_searched = FALSE;
        if($id == 'search'){
            $is_searched = TRUE;
        }
        if(\Request::ajax()){
            return $result = [ 'view' => view('service_package.typewiseajaxlist', compact('service_packages', 
                                                        'saved_packages_list'))->render(),
                                                        'show_load_more_button' => $show_load_more_button];
        } 
        return view('service_package.typewiselist', compact('service_packages', 'featured_list', 'id', 'title', 
                                                'total_records', 'show_load_more_button', 'saved_packages_list', 'is_searched'));
    }
    
    private function getServicePackages($id, $query_options, $featured_list, $request){
        $result = [];
        if(is_numeric($id)){
            if(array_key_exists($id, $featured_list)){
                $service_packages = ServicePackage::getServicePackages(['service_package_type_id' => $id, 'is_approved' => 'TRUE', 'is_hidden' => 'FALSE'], ['userDetails.user_profile'], $query_options);
                $title = $featured_list[$id].' Service Packages';
                $result = ['service_packages' => $service_packages, 'title' => $title];
            }
        }elseif($id=='other'){
            unset($featured_list['']);
            $keys = array_keys($featured_list);
            $service_packages = ServicePackage::getOther('', $keys, [], $query_options);
            $title = config('constants.OTHER_SERVICE_PACKAGE_TITLE');
            $result = ['service_packages' => $service_packages, 'title' => $title];
        }elseif($id=='search'){
            $search = $request->search;
            $expert_ids = $this->getExpertIds($request->location, $request->selectremoteoption);
            if((!empty($request->location) || $request->selectremoteoption) && !_count($expert_ids)){
                $result = [];
            }else{
                $searched_result = $this->searchServicePackages($search, $expert_ids, $request->selected_featured_package, $featured_list, $query_options);
                if(_count($searched_result)){
                    $result = ['service_packages' => $searched_result['service_packages'], 'title' => $searched_result['title']];
                }
            }
        }
        return $result;
    }
    
    private function getExpertIds($location, $remote_option){
        $result = $user_id = [];
        if(!empty($location)){
            $location_array = explode(',', $location);
            $user_exists = UserProfile::searchLocationRemoteOption($location, $remote_option, 'exists');
            if(!$user_exists){
                if(_count($location_array)>1){
                    $user_id = UserProfile::searchLocationRemoteOption($location_array[0], $remote_option, 'lists');
                }
            }else{
                $user_id = UserProfile::searchLocationRemoteOption($location, $remote_option, 'lists');
            }
        }elseif($remote_option){
            $user_id = UserProfile::searchLocationRemoteOption('', $remote_option, 'lists');
        }
        if(_count($user_id)){
            if(!is_array($user_id))
                $user_id = $user_id->toArray();
        }
        return $user_id;
    }
    
    private function searchServicePackages($search, $expert_ids, $selected_featured_package, $featured_list, $query_options){
        $title = config('constants.ALL_SERVICE_PACKAGE_TITLE');
        if(!empty($selected_featured_package)){
            if($selected_featured_package == 'other'){
                unset($featured_list['']);
                $keys = array_keys($featured_list);
                $other_service_packages_exist = ServicePackage::getOther($search, $keys, $expert_ids, ['exists' => 'exists']);
                if($other_service_packages_exist){
                    $service_packages = ServicePackage::getOther($search, $keys, $expert_ids, $query_options);
                    $title = config('constants.OTHER_SERVICE_PACKAGE_TITLE');
                    return $result = ['service_packages' => $service_packages, 'title' => $title]; 
                }
            }else{
                $result_with_selected_package_exists = ServicePackage::searchNameDescription($search, $selected_featured_package, 'exists', $expert_ids);
                if($result_with_selected_package_exists){
                    $service_packages = ServicePackage::searchNameDescription($search, $selected_featured_package, 'get', $expert_ids, ['userDetails.user_profile'], $query_options);
                    if(array_key_exists($selected_featured_package, $featured_list)){
                        $title = $featured_list[$selected_featured_package].' Service Packages';
                    }
                    return $result = ['service_packages' => $service_packages, 'title' => $title];
                }
            }
        }
        $result_without_selected_package_exists = ServicePackage::searchNameDescription($search, '', 'exists', $expert_ids);
        if($result_without_selected_package_exists){
            $service_packages = ServicePackage::searchNameDescription($search, '', 'get', $expert_ids, ['userDetails.user_profile'], $query_options);
            return $result = ['service_packages' => $service_packages, 'title' => $title];
        }
        return [];
    }

    public function updateFeaturedTypes() {
        $featured_types = ['Commerce Analytics', 'Data Collection & Storage', 'Data Management Platforms', 'Data Science',
            'Data Visualization/Dashboards', 'Marketing Automation & CRM', 'Growth Marketing Execution', 'Mobile App Analytics',
            'Website Analytics', 'Social Analytics', 'A/B Testing & Personalization'];
        $query = ServicePackageType::updateWhereIn('name', $featured_types, ['is_featured' => 'TRUE']);
        if ($query) {
            return 'udpated';
        }
        return 'not updated';
    }

    public function delete(Request $request) {
        if (!expertAuth()) {
            return view('errors.404');
        }
        $delete_id = $request->all();
        $service_package = ServicePackage::fetchServicePackages(['id' => $delete_id['service_package_id'], 'publish' => True], '', ['contract', 'communication']);
        if(_count($service_package[0]['contract']) || _count($service_package[0]['communication'])){
            return 0;
        }
        $result = ServicePackage::updateServicePackage($delete_id['service_package_id'], ['deleted_at' => Carbon::now()]);
        return $result;
    }
    public function saveServicePackageByBuyer(Request $request){
        $save_service_package = $request->all();
        return ServicePackageComponent::saveServicePackage(['buyer_id'=>$save_service_package['buyer_id'],'service_package_id'=>$save_service_package['service_package_id']]);
        
    }
    public function detailsViewedByBuyer(Request $request, $service_package_id){
        if(!ctype_digit($service_package_id)){
            return view('errors.404');
        }
       if(!ServicePackage::fetchServicePackages(['id' => $service_package_id, 'is_approved' => 'TRUE', 'is_hidden' => 'FALSE', 'publish' => 'TRUE'], 'count')){
           return view('errors.404');
       }
       $last_page_buyer_service_package_listing = FALSE;
       if ($request->session()->has('last_page')) {
           if(session('last_page') == 'buyer_service_package_listing'){
               $last_page_buyer_service_package_listing = TRUE;
           }
       }
       $ssl=getenv('APP_SSL');
       $service_package_details = ServicePackage::fetchServicePackages(['id' => $service_package_id], '', ['contractFeedbacks.buyer']);
       $total_rating = 0;
       $average_rating = 0;
       if(_count($service_package_details[0]['contract_feedbacks'])){
           foreach ($service_package_details[0]['contract_feedbacks'] as $feedbacks){
               $total_rating+= $feedbacks['expert_rating'];
           }
           $average_rating = averageRating($total_rating, _count($service_package_details[0]['contract_feedbacks']));
       }
       $featured_listing = ServicePackageType::listNameId(['is_featured' => TRUE]);
       $bread_crumb_category = config('constants.OTHERS');
       $service_package_type_id = $service_package_details[0]['service_package_type_id'];
       $category_page_url = url('servicepackages/type/other', [], $ssl);
       if(array_key_exists($service_package_type_id, $featured_listing)){
           $bread_crumb_category = $featured_listing[$service_package_type_id];
           $category_page_url = url('servicepackages/type/'.$service_package_type_id, [], $ssl);
       }
       $deliverables = Deliverable::getDeliverablesByServicePackage($service_package_id);
       $tags = ServicePackageComponent::getServicePackageTags($service_package_id);  
       $user_information = expertInformation($service_package_details[0]['user_id'])[0];
       ServicePackageComponent::incrementViewerCount($service_package_id);
       $looked_at_service_package = ServicePackageComponent::viewersCount($service_package_id);
       $service_package_brought = ServicePackageComponent::getContractBroughtStatus($service_package_id, config('constants.APPROVED'));
       $is_interest_shown = ServicePackageComponent::isInterestShownByBuyer(Auth::user()->id, $service_package_id);
       return view('service_package.service_package_buyer_detail',compact('service_package_details','deliverables','tags',
               'user_information','looked_at_service_package','service_package_brought','is_interest_shown', 
               'bread_crumb_category', 'category_page_url', 'last_page_buyer_service_package_listing', 'average_rating'));
    }
    public function deleteSavedServicePackage(Request $request){
        $saved_service_package_id = $request->all();
        return ServicePackageComponent::deleteSavedServicePackage($saved_service_package_id['saved_service_package_id']);
    }

    public function savedExperts(Request $request){
        if (!buyerAuth()) {
            return view('errors.404');
        }
        $buyer_id = Auth::user()->id;
        $saved_packages_list = SavedServicePackage::savedPackageList($buyer_id);
        $limit = config('constants.SERVICE_PACKAGE_SEARCH_PER_PAGE');
        $show_load_more_button = TRUE;
        $offset = 0;
        if(isset($request->number_of_listed_packages)){
            $offset = $request->number_of_listed_packages;
        }
        $query_options = ['limit' => $limit, 'offset' => $offset];        
        $total_records = ServicePackage::savedPackages($buyer_id, ['is_approved' => 'TRUE', 'is_hidden' => 'FALSE'], [], ['count' => 'count']);
        $service_packages = ServicePackage::savedPackages($buyer_id, ['is_approved' => 'TRUE', 'is_hidden' => 'FALSE'], ['userDetails.user_profile'], $query_options);
        if($offset+$limit>=$total_records || _count($service_packages)<$limit){
            $show_load_more_button = FALSE;
        }
        
        return $result = [ 'view' => view('service_package.savedpackagesajaxlist', compact('service_packages', 
                                                        'saved_packages_list'))->render(),
                                                        'show_load_more_button' => $show_load_more_button,
                                                        'saved_package_count' => $total_records];
    }
    
    public function addToSession(Request $request){
        if(isset($request->service_package_id)){
            $request->session()->put('last_page', 'buyer_service_package_listing');
            return 1;
        }
    }
    public function publicServicePackage(Request $request, $name, $service_package_id){
        if(!ctype_digit($service_package_id)){
            return view('errors.404');
        }
        if (expertAuth() && ServicePackage::fetchServicePackages(['id' => $service_package_id, 'user_id' => auth::user()->id, 'is_approved' => 'TRUE', 'is_hidden' => 'TRUE', 'publish' => 'TRUE'], 'count')) {
            return Redirect::To('servicepackage/detail/' . $service_package_id);
        }
        if(!ServicePackage::fetchServicePackages(['id' => $service_package_id, 'is_approved' => 'TRUE', 'is_hidden' => 'FALSE', 'publish' => 'TRUE'], 'count')){
           return view('errors.404');
        }
       if(buyerAuth()){
           return Redirect::To('servicepackage/'.$service_package_id);
       }
       if(adminAuth()){
           return Redirect::To('admin/approvedservicepackage/'.$service_package_id);
       }
       $service_package_details = ServicePackage::fetchServicePackages(['id' => $service_package_id], '', ['contractFeedbacks.buyer']);
       if(expertAuth() && $service_package_details[0]['user_id']==auth::user()->id){
           return Redirect::To('servicepackage/detail/'.$service_package_id);
       }
       $total_rating = 0;
       $average_rating = 0;
       if(_count($service_package_details[0]['contract_feedbacks'])){
           foreach ($service_package_details[0]['contract_feedbacks'] as $feedbacks){
               $total_rating+= $feedbacks['expert_rating'];
           }
           $average_rating = averageRating($total_rating, _count($service_package_details[0]['contract_feedbacks']));
       }
       $user_information = expertInformation($service_package_details[0]['user_id'])[0];
       ServicePackageComponent::incrementViewerCount($service_package_id);
       $looked_at_service_package = ServicePackageComponent::viewersCount($service_package_id);
       return view('landingpages.public_service_package',compact('service_package_details',
               'user_information','looked_at_service_package','average_rating'));
    }
    public function scriptToUpdateVisitorsCount(){
        $all_service_packages= ServicePackage::get();
        foreach ($all_service_packages as $service_package => $value) {
            $package_views= \App\Model\ServicePackageViewer::getCount($value->id);
             if(!empty($package_views)){
                 ServicePackage::updateServicePackage($value->id,['visitors_count'=>$package_views]);
                 echo"updated</br>";
            }
          }
        
       
    }    
}
