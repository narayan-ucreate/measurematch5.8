<?php

namespace App\Components;

use App\Model\ServicePackage;
use App\Model\User;
use App\Model\WebflowPage;

class WebflowComponent {

    private $api_key = null;
    private $base_url = 'https://api.webflow.com/';

    private function getApiKey(){
        if (!$this->api_key) {
            $this->api_key = getenv('WEBFLOW_API_KEY');
        }
        return $this->api_key;
    }

    public function getSites() {
        $request_site = 'sites/';
        return $this->request($request_site, 'GET');
    }

    public function getCollectionsList($site_id){
        $request_collections = 'sites/' . $site_id . '/collections';
        return $this->request($request_collections, 'GET');
    }

    public function getSingleCollection($collection_id) {
        $request_single_collection = 'collections/' . $collection_id . '/items';
        return $this->request($request_single_collection, 'GET');
    }

    public function getSingleItem($collection_id, $item_id){
        $request_single_item = 'collections/' . $collection_id . '/items/' . $item_id;
        return $this->request($request_single_item, 'GET');
    }

    public function createSingleItem($collection_id, $new_item){
        $post_single_item = 'collections/' . $collection_id . '/items?live=true';
        return $this->postRequest($post_single_item, $new_item);
    }

    public function editSingleItem($collection_id, $item_id, $item_slug, $fields_to_edit){
        $put_single_item = '/collections/' . $collection_id . '/items/' . $item_id;
        return $this->putRequest($put_single_item, $collection_id, $item_id, $item_slug, $fields_to_edit);
    }

    public function deleteSingleItem($collection_id, $item_id){
        $delete_single_item = '/collections/' . $collection_id . '/items/' . $item_id;
        return $this->request($delete_single_item, 'DELETE');
    }

    public function putRequest($request_url, $collection_id, $item_id, $item_slug, $fields){
        $defaults = [
            "_archived" => false,
            "_draft" => false,
            "slug" => $item_slug
        ];
        $data = [
            'collection_id' => $collection_id,
            'item_id' => $item_id,
            'fields' => $defaults + $fields
        ];
        return $this->request($request_url, 'PUT', $data);
    }

    public function postRequest($request_url, $fields){
        $defaults = [
            "_archived" => false,
            "_draft" => false,
        ];
        $data = ['fields' => $defaults + $fields];

        return $this->request($request_url, 'POST', $data);
    }

    public function request($request_url, $method, $data = [])
    {
        $ch = curl_init();
        $options = [
            CURLOPT_URL => $this->base_url . $request_url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->getApiKey(),
                "accept-version: 1.0.0",
                "Accept: application/json",
                "Content-Type: application/json",
            ],
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
        ];

        if (!empty($data)) {
            $json = json_encode($data);
            $options[CURLOPT_POSTFIELDS] = $json;
            $options[CURLOPT_HTTPHEADER][] = "Content-Length: " . strlen($json);
        }
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);
        $json_string = json_decode($body, true);
        return $json_string;
    }

    public function createProfileObject($user_data){
        $profile_object = [
            'name' => ucfirst($user_data['name']) . ' ' . ucfirst($user_data['last_name'][0]),
            'profile-picture' => $user_data['user_profile']['profile_picture'],
            'profile-title' => $user_data['user_profile']['describe'],
            'consultant-type' => 'Independent Consultant',
            'location' => trim($user_data['user_profile']['current_city']) . ', ' . trim($user_data['user_profile']['country']),
            'bio' => $user_data['user_profile']['summary'],
            ];
        if ($user_data['user_profile']['expert_type'] != config('constants.EXPERT_TYPE_INDEPENDENT')) {
            $profile_object['consultant-type'] = config('constants.CONSULTANT_TYPES');
        }
        $skills_index = 1;
        $tools_index = 1;
        foreach ($user_data['user_skills'] as $skill){
            if ($skill['skill']['is_tool'] && $tools_index < 6){
                $string = 'tech-skill-'.$tools_index++;
                $profile_object[$string] = $skill['skill']['name'];
            }
            if (!$skill['skill']['is_tool'] && $skills_index < 6) {
                $string = 'advisory-skill-' . $skills_index++;
                $profile_object[$string] = $skill['skill']['name'];
            }
        }
        return $profile_object;
    }

    public function saveProfile($profile, $id){

        $new_page = WebflowPage::create([
            'webflow_collection_id' => $profile['_cid'],
            'webflow_item_id' => $profile['_id'],
            'user_id' => $id,
            'type' => 'Expert',
            'webflow_url' => getenv('WEBFLOW_BASE_URL') . $profile['slug'],
        ]);

        if ($new_page){
            return $new_page;
        }
        return null;
    }

    public function exportAllApprovedExperts(){
        $all_experts = User::where([
            ['user_type_id', 1],
            ['admin_approval_status', 1]
        ])->get();

        foreach ($all_experts as $expert){
            $expert_details = User::getSellerDetails($expert->id);
            if ($expert_details) {
                $profile = $this->createProfileObject($expert_details[0]);
                $new_item = $this->createSingleItem(getenv('WEBFLOW_PROFILE_COLLECTION_ID'), $profile);
                $new_profile = $this->saveProfile($new_item, $expert->id);
                if (!$new_profile)
                    return false;
            }
        }
        return true;
    }

    public function createServicePackageObject($service_package){
        $user = $service_package->userDetails()->get()->first();
        $deliverables = $service_package->deliverables()->get()->toArray();

        $service_package_object = [
            'expert-name' => ucfirst($user['name']) . ' ' . ucfirst($user['last_name'][0]),
            'expert-profile-picture' => $user['user_profile']['profile_picture'],
            'expert-title' => $user['user_profile']['describe'],
            'name' => $service_package->name,
            'rate' => '$' . number_format($service_package->price),
            'length' => $service_package->duration . ' days',
            'description' => $service_package->description,
        ];
        $deliverables_index = 1;
        foreach ($deliverables as $deliverable) {
            if ($deliverables_index < 6) {
                $string = 'deliverable-' . $deliverables_index++;
                $service_package_object[$string] = $deliverable['deliverable'];
            }
        }
        return $service_package_object;
    }

    public function saveServicePackage($service_package, $id){
        $new_page = WebflowPage::create([
            'webflow_collection_id' => $service_package['_cid'],
            'webflow_item_id' => $service_package['_id'],
            'internal_id' => $id,
            'type' => config('constants.SERVICE_PACKAGE'),
            'webflow_url' => getenv('WEBFLOW_SP_BASE_URL') . $service_package['slug'],
        ]);
        if ($new_page){
            return $new_page;
        }
        return null;
    }

    public function exportAllApprovedServicePackages(){
        $all_packages = ServicePackage::where([
            ['is_approved', 1]
        ])->get();

        foreach ($all_packages as $package){
            $sp_details = ServicePackage::find($package->id);
            if ($sp_details) {
                $service_package_object = $this->createServicePackageObject($package);
                $new_item = $this->createSingleItem(getenv('WEBFLOW_SERVICE_PACKAGES_COLLECTION_ID'), $service_package_object);
                $new_sp = $this->saveServicePackage($new_item, $package->id);
                if (!$new_sp)
                    return false;
            }
        }
        return true;
    }

    public function createProjectObject($project){

        $project_object = [
            'name' => ucfirst($project['title']),
            'project-description' => $project['description'],
        ];
        return $project_object;
    }

    public function saveProject($project, $id){
        $new_page = WebflowPage::create([
            'webflow_collection_id' => $project['_cid'],
            'webflow_item_id' => $project['_id'],
            'internal_id' => $id,
            'type' => config('constants.PROJECT'),
            'webflow_url' => getenv('WEBFLOW_PROJECTS_BASE_URL') . $project['slug'],
        ]);
        if ($new_page){
            return $new_page;
        }
        return null;
    }
}