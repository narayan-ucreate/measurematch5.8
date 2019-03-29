<?php

namespace App\Http\Requests\ServiceHub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreInvitedExperts extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $inputs = \Request::all();
        if($inputs['inviting_experts_mandatory'])
        {
            return [
                'first_name' => 'required|array|min:3',
                'last_name' => 'required|array|min:3',
                'email' => 'required|array|min:3',
                'first_name.*' => 'required',
                'last_name.*' => 'required',
                'email.*' => 'required|distinct|email|unique:users,email',
                'service_hub_id' => 'required',
            ];
        }
        return [
            'first_name.*' => 'sometimes|required_with:last_name.*,email.*',
            'last_name.*' => 'sometimes|required_with:first_name.*,email.*',
            'email.*' => 'nullable|distinct|required_with:first_name.*,last_name.*|unique:users,email|email',
            'service_hub_id' => 'required'
        ];
    }
    
    public function messages()
    {
        return [
          'first_name.*.required' => 'Please input first name, last name and email',
          'first_name.*.required_with' => 'Please input first name, last name and email',
          'last_name.*.required' => 'Please input first name, last name and email',
          'last_name.*.required_with' => 'Please input first name, last name and email',
          'email.*.required' => 'Please input first name, last name and email',
          'email.*.required_with' => 'Please input first name, last name and email',
          'email.*.distinct' => 'Please input unique values for email',
          'email.*.unique' => 'This email already exists in our database',
          'email.*.email' => 'This email must be a valid email address',
        ];
    }
}
