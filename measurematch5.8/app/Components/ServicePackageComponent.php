<?php

namespace App\Components;

use Auth;
use Exception;
use App\Model\{ServicePackage,ServicePackageTag,ServicePackageCategory,SavedServicePackage,ServicePackageViewer,Contract,Communication,Deliverable,Tag};
Class ServicePackageComponent {

    public static function saveNewServicePackage($service_package_data, $user_id) {
        if (!empty($service_package_data)) {
            $data = getServicePackageInformation($service_package_data, $user_id);

            $service_package = new ServicePackage(stripScriptingTags($data, ['description']));
            if ($service_package->save()) {
                $deliverables = self::saveDeliverables($service_package->id, $service_package_data['deliverables']);
                $tags = self::saveTags($service_package->id, $service_package_data['tags']);
                $category = self::saveServicePackageCategory($service_package->id, $service_package_data['service_package_category']);
                return $service_package;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function updateExisitngServicePackage($service_package_id, $service_package_data, $user_id) {
        if (!empty($service_package_data)) {
            $data = getServicePackageInformation($service_package_data, $user_id);
            $update_service_package = ServicePackage::updateServicePackage($service_package_id, stripScriptingTags($data, ['description']));
            if ($update_service_package) {
                $deliverables = self::updateDeliverables($service_package_id, stripScriptingTags($service_package_data['deliverables']));
                $tags = self::saveTags($service_package_id, $service_package_data['tags']);
                $category = self::saveServicePackageCategory($service_package_id, $service_package_data['service_package_category']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function saveDeliverables($service_package_id, $deliverables, $type="service_package", $contract_id = null, $project_id = null) {
        if (!empty(array_filter($deliverables))) {
            foreach ($deliverables as $deliverable) {
                if ($deliverable) {
                    $deliverable_data = [
                        'type' => $type, 
                        'deliverable' => $type != 'contract' ? $deliverable : stripScriptingTagsInline($deliverable['description']),
                       ];
                    if ($type == 'contract') {
                        $deliverable_data['contract_id'] = $contract_id;
                        $deliverable_data['rate'] = $deliverable['price']*100;
                        $deliverable_data['rate_unit'] = $deliverable['rate_type'];
                        $deliverable_data['quantity'] = $deliverable['quantity'];
                        $deliverable_data['title'] = stripScriptingTagsInline($deliverable['title']);

                    }
                    if(!empty($service_package_id))
                        $deliverable_data['service_package_id'] = $service_package_id;
                    if(!empty($project_id))
                        $deliverable_data['post_job_id'] = $project_id;
                    
                    $save_deliverable = new Deliverable($deliverable_data);
                    $save_deliverable->save();
                }
            }
        }
    }

    public static function saveTags($service_package_id, $tags) {
        $delete_service_package_tag = ServicePackageTag::deleteServicePackageTag($service_package_id);
        if (!empty($tags)) {
            $all_tags = explode(',', $tags);            
            foreach ($all_tags as $_tag) {
                if (!empty($_tag)) {
                    $existing_tag = Tag::getSimilarTags($_tag);
                    if (_count($existing_tag)) {
                        self::saveServicePackageTag($service_package_id, $existing_tag->id);
                    } else {
                        $tag = new Tag(['name' => trim($_tag), 'tag_type' => 'expert']);
                        if ($tag->save()) {
                            self::saveServicePackageTag($service_package_id, $tag->id);
                        }
                    }
                }
            }
        }
    }

    public static function saveServicePackageTag($service_package_id, $tag_id) {

        $save_service_package_tag = new ServicePackageTag(['service_package_id' => $service_package_id, 'tag_id' => $tag_id]);
        $save_service_package_tag->save();
    }

    public static function saveServicePackageCategory($service_package_id, $category_id) {
        $save_service_package_category = new ServicePackageCategory(['service_package_id' => $service_package_id, 'category_id' => $category_id]);
        $save_service_package_category->save();
    }

    public static function updateDeliverables($service_package_id, $deliverables) {
        Deliverable::deleteDeliverables($service_package_id);
        self::saveDeliverables($service_package_id, $deliverables);
    }

    public static function servicePackageListing($options) {
        $listing = ServicePackage::fetchServicePackages($options);
        return $listing;
    }

    public static function servicePackageDetails($service_package_id) {
        return ServicePackage::getServicePackageById($service_package_id);
    }

    public static function hideServicePackage($service_package_id) {
        return ServicePackage::updateServicePackage($service_package_id, ['is_hidden' => 'TRUE']);
    }

    public static function unHideServicePackage($service_package_id) {
        return ServicePackage::updateServicePackage($service_package_id, ['is_hidden' => 'FALSE']);
    }

    public static function approveServicePackage($service_package_id) {
        return ServicePackage::updateServicePackage($service_package_id, ['publish' => 'TRUE', 'is_approved' => 'TRUE']);
    }

    public static function disApproveServicePackage($service_package_id) {
        return ServicePackage::updateServicePackage($service_package_id, ['publish' => 'TRUE', 'is_rejected' => 'TRUE']);
    }

    public static function getDraftedServicePackages($user_id) {
        return ServicePackage::fetchServicePackages(['user_id' => $user_id, 'publish' => 'FALSE']);
    }

    public static function viewersCount($service_package_id) {
        return ServicePackage::getVisitorsCount($service_package_id);
        
    }

    public static function servicePackageEoi($service_package_id) {
        $result['new_expression_of_interests'] = Communication::fetchCommunications(['type' => 'service_package', 'service_package_id' => $service_package_id, 'status' => 0]);
        $result['actioned_expression_of_interests'] = Communication::fetchCommunications(['type' => 'service_package', 'service_package_id' => $service_package_id, 'status' => 1]);
        $result['archived_expression_of_interests'] = Communication::fetchCommunications(['type' => 'service_package', 'service_package_id' => $service_package_id, 'status' => 2]);
        return $result;
    }

    public static function servicePackageContracts($service_package_id) {
        return Contract::fetchContracts(['type' => 'service_package', 'service_package_id' => $service_package_id]);
    }
    
    public static function servicePackageFeedbacks($service_package_id) {
        return Contract::fetchContracts(['buyer_feedback_status' => config('constants.UPDATED'), 'type' => 'service_package', 'service_package_id' => $service_package_id]);
    }

    public static function getServicePackageTags($service_package_id) {
        $tags = ServicePackageTag::getServicePackageTags($service_package_id);
        $taglist = array();
        foreach ($tags as $tag) {
            $taglist[] = Tag::getTagsById($tag['tag_id']);
        }
        return $taglist;
    }

    public static function archiveEoi($service_package_id) {
        return Communication::updateCommunication($service_package_id, ['status' => config('constants.REJECTED')]);
    }

    public static function unArchiveEoi($service_package_id) {
        return Communication::updateCommunication($service_package_id, ['status' => config('constants.APPROVED')]);
    }

    public static function saveServicePackage($service_package_information) {
        $save_service_package = new SavedServicePackage($service_package_information);
        if ($save_service_package->save()) {
            return $save_service_package['id'];
        } else {
            return 0;
        }
    }

    public static function deleteSavedServicePackage($id) {
        return SavedServicePackage::deleteSavedServicePackage($id);
    }

    public static function incrementViewerCount($service_package_id) {
       if(Auth::check() && Auth::user()->user_type_id == config('constants.EXPERT')){
           return true;
       }
        return ServicePackage::updateVisitorsCount($service_package_id);
     }

    public static function getContractBroughtStatus($service_package_id, $status) {
        return Contract::getContractBroughtStatus($service_package_id, $status);
    }
    
    public static function updateContractDeliverables($service_package_id, $contract_id, $deliverables, $project_id = null) {
        Deliverable::deleteContractDeliverables($contract_id);
        self::saveDeliverables($service_package_id, $deliverables, config('constants.CONTRACT'), $contract_id, $project_id = null);
    }
    
    public static function isInterestShownByBuyer($buyer_id, $service_package_id) {
        $interest_shown = Communication::fetchCommunications(['buyer_id'=>$buyer_id,'service_package_id'=>$service_package_id],'count');
        if ($interest_shown) {
            return True;
        } else {
            return false;
        }
    }
}
