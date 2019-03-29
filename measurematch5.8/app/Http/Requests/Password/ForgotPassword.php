<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassword extends FormRequest
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
            'email' => 'required|email|exists:users,email'
        ];
    }
    
    public function messages() {
        return [
          'email.exists' => 'We have not found a MeasureMatch account with this email address.',
            'email.required' => 'Please enter the valid email address.',
            'email.email' => 'Please enter the valid email address.',
        ];
    }
}
