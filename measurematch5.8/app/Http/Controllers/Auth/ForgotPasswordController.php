<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use DB;
use Event;
use Password;
use Postmark\PostmarkClient;
use App\Model\User;
use Validator;
use App\Model\PasswordReset;
use App\Http\Requests\Password\ForgotPassword;

class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function triggerReset(Request $request) {
        $user = \Auth::createUserProvider('users')->retrieveByCredentials($request->only('email'));
        $status = false;
        $message = 'Password can not be reset right now. Please try again.';
        if (is_null($user)) {
            $message = 'Invalid email address.';
        }
        if ($user['is_deleted'] == 1 || $user['status'] == 2) {
            $message = 'Invalid email address.';
        }
        $token = str_random(60);
        (new PasswordReset)->insertData([
            'email' => $request->email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s') 
        ]);
        $email = $request->email;
        $link = url('password/reset', $token, getenv('APP_SSL'));
        try {
            $response = \App\Components\Email::resetPassword(['user_id' => $user['id'], 'link' => $link]);
            if ($response) {
                $message = 'A link to change your password has been sent to your email. Please check your account and follow the instructions.';
                $status = true;
            }
        } catch (Exception $e) {
            
        }
        return ['status' => $status, 'message' => $message];
    }

    /**
     * Send the reset link
     *
     * @param  Request $request
     * @return Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $form_data = $request->all();
        $messages = [
            'email.exists' => 'We have not found a MeasureMatch account with this email address.',
            'email.required' => 'Please enter the valid email address.',
            'email.email' => 'Please enter the valid email address.',
        ];

        $validator = Validator::make($form_data, [
                'email' => 'required|email|exists:users,email'
                ], $messages);

        if ($validator->fails())
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        else
        {
            $user_data = User::findByCondition(['email' => $form_data['email']]);
            $response = $this->triggerReset($request);
            \Session::flash('message', $response['message']);
            \Session::flash('status', $response['status']);
            switch ($response['status'])
            {
                case true;
                    return $request->ajax() ? route('sentMail') . '?email=' . $form_data['email'] : redirect(route('sentMail') . '?email=' . $form_data['email']);
                default:
                    return redirect('/password/reset');
            }
        }
    }

    public function sentMail() {
        $inputs = \Request::all();
        $email = $inputs['email'] ?? '';
        return view('auth.passwords.request_success', compact('email'));
    }
}
