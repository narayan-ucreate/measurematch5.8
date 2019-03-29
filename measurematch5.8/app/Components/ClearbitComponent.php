<?php

namespace App\Components;

class ClearbitComponent
{
    private $api_key = null;
    private $person_base_url = 'https://person.clearbit.com/v2/';
    private $company_base_url = 'https://company.clearbit.com/v2/';

    private function getApiKey(){
        if (!$this->api_key) {
            $this->api_key = getenv('CLEARBIT_API_KEY');
        }
        return $this->api_key;
    }

    public function getPersonData($email, $combined = false){

        $type = 'people';
        if($combined){
            $type = 'combined';
        }
        $request_url = $this->person_base_url . "{$type}/find?email=" . $email;
        return $this->request($request_url);
    }

    public function getCompanyData($domain){

        $request_url = $this->company_base_url . 'companies/find?domain=' . $domain;
        return $this->request($request_url);
    }

    public function buildPersonDataArray($api_response){

        $response_array = $api_response;
        if(isset($api_response['person'])){
            $response_array = $api_response['person'];
        }
        $data_array = array();
        $data_array['first_name'] = $response_array['name']['givenName'];
        $data_array['last_name'] = $response_array['name']['familyName'];
        $data_array['company_name'] = $response_array['employment']['name'];
        $data_array['company_website'] = $response_array['employment']['domain'];
        $data_array['title'] = $response_array['employment']['title'];
        $data_array['city'] = $response_array['geo']['city'];
        $data_array['state'] = $response_array['geo']['state'];
        $data_array['country'] = $response_array['geo']['countryCode'];
        return $data_array;
    }

    public function buildCombinedDataArray($api_response){

        $data_array = array();
        $person_data = null;
        if(isset($api_response['person'])){
            $person_data = $api_response['person'];
        }
        $company_data = null;
        if (isset($api_response['company'])){
            $company_data = $api_response['company'];
        }
        $data_array['first_name'] = $person_data['name']['givenName'];
        $data_array['last_name'] = $person_data['name']['familyName'];
        $data_array['company_name'] = $company_data['name'];
        $data_array['company_website'] = $company_data['domain'];
        return $data_array;
    }

    public function request($request_url){
        $api_key = $this->getApiKey();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERPWD,"$api_key:");
        $result = curl_exec($ch);
        curl_close($ch);
        $json_string = json_decode($result, true);
        if (isset($json_string['error']) && $json_string['error']['type'] === 'queued'){
           $this->request($request_url);
        }
        return $json_string;
    }
}