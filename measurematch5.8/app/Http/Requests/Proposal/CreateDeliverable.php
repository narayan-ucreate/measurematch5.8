<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class CreateDeliverable extends FormRequest
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
        if (isset($inputs['action']) && $inputs['action'] == 'delete_deliverable') {
            return [
                'index' => 'required'
            ];
        }
        if (isset($inputs['action']) && $inputs['action'] == 'auto-save') {
            return [
                $inputs['name'] => 'required'
            ];
        }
        return [
            'title' => 'sometimes|required|max:100',
            'description' => 'sometimes|required|max:1000',
            'rate_type' => 'sometimes|required|in:'.implode(',', array_keys(config('constants.RATE_TYPE'))),
            'quantity' => 'sometimes|required_if:rate_type,2,3',
            'price' => ['sometimes', 'required', 'min:1', 'max:7', 'regex:/^[0-9]+(\s*\,\s*[0-9]+)*$/'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'It seems like you\'ve missed the title, please enter the title to continue',
            'rate_type.required' => 'Whoops! You forgot to add your rate type, please choose your rate type to continue',
            'description.required' => 'Oh no, you\'ve missed section. Please fill in this section before continuing',
            'price.required' => 'you\'ve missed a section. Please enter the amount before continuing',
            'price.numeric' => 'Oh no, you\'ve entered an invalid amount. Please enter the correct amount to continue',
            'price.minimum' => 'Oh no, you\'ve entered an invalid amount. Please enter the correct amount to continue',
            'summary.required' => 'Oops! Please fill in this section before continuing',
            'introduction.required' => 'Oops! Please fill in this section before continuing',
            'job_start_date.required' => 'Oh dear! it looks like you forgot to add a start date. Please add a date to continue.',
            'job_end_date.required' => 'Oh dear! it looks like you forgot to add the finish date. Please add a date to continue.',
            'price.regex' => 'Oh no, you\'ve entered an invalid amount. Please enter the correct amount to continue.',

        ];
    }
}
