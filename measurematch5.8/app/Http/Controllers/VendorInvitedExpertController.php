<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Model\VendorInvitedExpert;
use App\Http\Requests\ServiceHub\StoreInvitedExperts;

class VendorInvitedExpertController extends Controller
{
    public function store(StoreInvitedExperts $request)
    {
        $form_data = $request->all();
        $updated_form_data = $this->managerAlreadyExistingExperts($form_data['service_hub_id'], $form_data);
        $prepared_data = $this->preparedData($updated_form_data);
        if(_count($prepared_data) && !VendorInvitedExpert::insert($prepared_data))
        {
            $message_bag = new MessageBag;
            $message_bag->add('general_error', trans('custom_validation_messages.service_hub.general_error'));
            return \Response::json($message_bag, 422);
        }
        return ['redirect_url' => route('service-hubs-create', [ config('constants.VENDOR_HUB_STEP_3')])];
    }
    
    private function preparedData($form_data)
    {
        $input_data = [];
        foreach($form_data['first_name'] as $key => $expert) {
            if(!empty($form_data['first_name'][$key])
                && !empty($form_data['last_name'][$key])
                && !empty($form_data['email'][$key]))
            {
                $input_data [] = [
                    'first_name' => $form_data['first_name'][$key],
                    'last_name' => $form_data['last_name'][$key],
                    'email' => $form_data['email'][$key],
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                    'service_hub_id' => $form_data['service_hub_id']
                ];
            }
        }
        return $input_data;
    }
    
    private function managerAlreadyExistingExperts($service_hub_id, $form_data)
    {
        $experts_to_delete = [];
        $vendor_invited_expert = new VendorInvitedExpert();
        $existing_expert_count = 0;
        $existing_experts = $vendor_invited_expert->findWithConditions(['service_hub_id' => $service_hub_id]);
        foreach($existing_experts as $key => $expert_information)
        {
            if(in_array($expert_information['email'], $form_data['email']))
            {
                $existing_expert_count++;
                $existing_key = array_search($expert_information['email'], $form_data['email']);
                $existing_experts[$key]['first_name'] = $form_data['first_name'][$existing_key];
                $existing_experts[$key]['last_name'] = $form_data['last_name'][$existing_key];
                $existing_experts[$key]['email'] = $form_data['email'][$existing_key];
                unset($form_data['first_name'][$existing_key]);
                unset($form_data['last_name'][$existing_key]);
                unset($form_data['email'][$existing_key]);
            }
            else
            {
                $experts_to_delete[] = $expert_information['id'];
            }
        }
        if($existing_expert_count)
        {
            foreach ($existing_experts as $existing_expert)
            {
                $id = $existing_expert['id'];
                unset($existing_expert['id']);
                $vendor_invited_expert->updateWithConditions(['id' => $id], $existing_expert);
            }
        }
        if(_count($experts_to_delete))
        {
            $vendor_invited_expert->deleteWithConditions($experts_to_delete);
        }
        return $form_data;
    }
}
