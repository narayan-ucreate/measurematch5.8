<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class StoreTerm extends FormRequest
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
        return [
            'term' => 'max:5000'
        ];
    }
    
    public function messages()
    {
        return[
            'term.max' => 'You have exceeded the 5000 character limit. Please edit this term.'
        ];
    }
        
}
