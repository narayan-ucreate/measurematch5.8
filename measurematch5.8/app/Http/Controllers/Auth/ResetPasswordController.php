<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use DB;
use App\Model\PasswordReset;
use App\Model\User;
use App\Http\Requests\Password\ResetPassword;

class ResetPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     *
     * @param Request $request
     * @param type $token
     * @return type
     */
    public function showResetForm(Request $request, $token = null) {
        if (!empty($token)) {
            $date = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $info = (new PasswordReset)->getInfo($token);
            if (!$info || ($info && $info->created_at < $date)) {
                $email = $info->email ?? '';
                return view('auth.passwords.expire', compact('email'));
            } else {
                return view('auth.passwords.reset')->with(
                    ['token' => $token, 'valid_email' => $info->email]
                );
            }
        }
    }

    public function reset(Request $request)
    {
        $inputs = $request->all();
        $messages = [
            'password.confirmed' => 'Your passwords do not match.',
            'password.min' => 'Your new password needs to be a minimum of 6 characters in length.'
        ];

        $validator = \Validator::make($inputs, [
                'token' => 'required',
                'password_confirmation' => 'required',
                'password' => 'required|confirmed|min:6',
                ], $messages);

        if ($validator->fails())
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        else
        {
            $password_reset_object = (new PasswordReset);
            $token = $inputs['token'];
            try
            {
                \DB::beginTransaction();
                $info = $password_reset_object->getInfo($token);
                $password_reset_object->deleteData($token);
                (new User)->updateUserInfoByEmail($info->email, ['password' => bcrypt($inputs['password'])]);
                DB::commit();
                return redirect(route('successReset') . '?email=' . $info->email);
            }
            catch (Exception $ex)
            {
                DB::rollback();
                \Session::flash('error', 'Oops! something went wrong.');
                return redirect()->back();
            }
        }
    }

    public function successReset(Request $request) {
        $inputs = $request->all();
        $email = $inputs['email'] ?? '';
        return view('auth.passwords.reset_success', compact('email'));
    }

}
