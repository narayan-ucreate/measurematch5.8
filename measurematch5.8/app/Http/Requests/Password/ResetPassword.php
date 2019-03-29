<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
            'token' => 'required',
            'password_confirmation' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
    
    public function messages() {
        return [
          'password.confirmed' => 'Your passwords do not match.',
           'password.min' => 'Your new password needs to be a minimum of 6 characters in length.'
        ];
    }
}
