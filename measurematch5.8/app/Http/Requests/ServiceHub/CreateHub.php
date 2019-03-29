<?php

namespace App\Http\Requests\ServiceHub;

use Illuminate\Foundation\Http\FormRequest;

class CreateHub extends FormRequest
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
        if (isset($inputs['steps']) && $inputs['steps'] == 3) {
            return [];
        }
        return [
            'logo' => 'required_if:action,create|mimes:jpg,png,jpeg|max:1024',
            'name' => 'required',
            'sales_email' => 'required|email',
            'service_website' => ['required', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'description' => 'required|max:600',
            'service_category_name.*' => 'required',
            'service_category_name' => 'required',
            'terms_and_condition' => 'required',
            'code_of_conduct' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'service_category_name.*.required' => 'This field is required',
            'logo.required_if' => 'Please add a logo for your Service Hub',
            'name.required' => 'Please add a name for your Service Hub',
            'sales_email.required' => 'Please add an email address so prospective clients can contact your sales department',
            'service_website.required' => 'Please add a website link so prospective clients can learn more about your offering',
            'service_website.regex' => 'The website address is invalid',
            'description.required' => 'Please add description of your Service Hub',
            'service_category_name.*.required' => 'Please add at least 1 service category',
            'terms_and_condition.required' => 'Please read and agree to the MeasureMatch Terms of Service before continuing',
            'code_of_conduct.required' => 'Please read and agree to the MeasureMatch Code of Conduct before continuing'
        ];
    }
}
