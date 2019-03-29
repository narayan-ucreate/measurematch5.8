<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Model\User;
use App\Model\PostJob;
use Auth;
use Illuminate\Http\Request;
use App\Components\LoginComponent;
use App\Components\PostProjectComponent;
use App\Components\Email;
use App\Components\SegmentComponent;
use App\Model\BuyerProfile;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request) {
        $inputs = $request->all();
        $email = $inputs['email'] ?? '';
        $auth_user = Auth::user();
        if ($auth_user) {
            $redirect_url = $auth_user->user_type_id == config('constants.ADMIN') ? 'admin/buyerListing' :
                $auth_user->user_type_id == config('constants.EXPERT') ? '/expert/projects-search' :
                '/myprojects';
            return redirect($redirect_url);
        }
        return view('auth.login', compact('email'));
    }

    public function login() {
        try {
            $form_data = \Request::all();
            $redirect_back = ($form_data['posted_project_from_homepage'] == config('constants.TRUE')) ? 'post-project-login-from-homepage' : 'login';
            if ((!empty($form_data['mm_email'])) && (!empty($form_data['mm_password']))) {
                $user_with_truncated_entries = User::findByCondition(['email' => $form_data['mm_email']], [], ['with_trashed' => TRUE]);
                if (_count($user_with_truncated_entries)) {
                    if (!empty($user_with_truncated_entries[0]->deleted_at)) {
                        \Auth::logout();
                        return redirect($redirect_back)->withErrors(['error' => config('constants.DELETED_EMAIL_MESSAGE')]);
                    }
                }
                if (Auth::attempt(['email' => trim($form_data['mm_email']), 'password' => $form_data['mm_password'], 'user_type_id' => config('constants.EXPERT')])
                    || Auth::attempt(['email' => trim($form_data['mm_email']), 'password' => $form_data['mm_password'], 'user_type_id' => config('constants.BUYER')])
                    || Auth::attempt(['email' => trim($form_data['mm_email']), 'password' => $form_data['mm_password'], 'user_type_id' => config('constants.VENDOR')])) {
                    $project_from_home = LoginComponent::getDataFromCookieForPostAProjectFromHome($_COOKIE, $form_data);
                    return $this->redirectAfterLogin($project_from_home, $redirect_back);
                } else {
                    Auth::logout();
                    if (array_key_exists('project_from_home', $_COOKIE)) {
                        return redirect('post-project-login-from-homepage')->withErrors(['error' => config('constants.CREDENTIAL_DONOT_MATCH')]);
                    }
                    $redirect_back = $redirect_back === 'login' ? $redirect_back.'?email='.$form_data['selected_email'] : $redirect_back;
                   return redirect($redirect_back)->withErrors(['error' => config('constants.CREDENTIAL_DONOT_MATCH')]);
                }
            } else {
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.BLANK_PASSWORD_EMAIL')]);
            }
        } catch (\Exception $e) {
            return __('custom_validation_messages.global.general_error');
        }
    }
    
    private function redirectAfterLogin($project_from_home, $redirect_back) {
        $user = Auth::user();
        $status = $user->status;
        $is_deleted_status = $user->is_deleted;
        $admin_approval_status = $user->admin_approval_status;

        switch (true){
            case ($status == config('constants.APPROVED')) && ($is_deleted_status == config('constants.FALSE')):
                LoginComponent::setCookieForLogedInUser();
                return $this->successfulLoginRedirects($project_from_home);
            case ($status == config('constants.PENDING')) &&
                    ($is_deleted_status == config('constants.FALSE')) &&
                    ($admin_approval_status == config('constants.APPROVED') || $admin_approval_status == config('constants.PENDING')):
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.VERIFY_ACTIVATION_LINK')]);
            case ($status == config('constants.PENDING')) &&
                    ($is_deleted_status == config('constants.FALSE')) &&
                    $admin_approval_status == config('constants.PENDING'):
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.UNDER_REVIEW_ACTIVATION_LINK')]);
            case ($status == config('constants.REJECTED')) &&
                    ($is_deleted_status == config('constants.TRUE')) &&
                    ($admin_approval_status == config('constants.REJECTED')):
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.REJECTED_ACTIVATION_LINK')]);
            case $status == config('constants.REJECTED'):
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.BLOCKED_ACTIVATION_LINK')]);
            case ($status == config('constants.APPROVED')) &&
                    ($is_deleted_status == config('constants.FALSE')) &&
                    $admin_approval_status == config('constants.PENDING'):
                LoginComponent::setCookieForLogedInUser();
                return redirect('expert/profile-summary');
            case ($status == config('constants.SIDE_HUSTLER')) &&
                    ($is_deleted_status == config('constants.FALSE')) &&
                    $admin_approval_status == config('constants.PENDING'):
                LoginComponent::setCookieForLogedInUser();
                return redirect('expert/account-frozen');
            default:
                Auth::logout();
                return redirect($redirect_back)->withErrors(['error' => config('constants.DELETED_EMAIL_MESSAGE')]);
        }
    }

    private function successfulLoginRedirects($project_from_home) {
        if ((Auth::user()->user_type_id == config('constants.BUYER')) && $project_from_home != '') {
            $response = $this->postProjectWithLogin($project_from_home);
            if (Auth::user()->admin_approval_status != config('constants.APPROVED'))
                return Redirect::To('/');
            if ($response['response'] == config('constants.TRUE')) {
                return $redirect = redirect('buyer/experts/search')->with('posted_from_home', TRUE)->with('post_id', $response['post_id'])->with('msg', $response['msg']);
            } else {
                return redirect()->intended('buyer/experts/search')->with('posted_from_home', TRUE)->with('msg', $response['msg']);
            }
        } else {
            return redirect()->intended('/');
        }
    }
    
    private function postProjectWithLogin($project_from_home) {
        $email_componenet = new Email();
        $project_from_home['user_id'] = Auth::user()->id;
        if ($post_job = PostJob::create($project_from_home)) {
            (new SegmentComponent)->projectTracking(Auth::user()->id, $post_job->id, $post_job->job_title, "Project Created");
            if(array_key_exists('type_of_organization', $project_from_home) && !empty(trim($project_from_home['type_of_organization']))){
               BuyerProfile::updateBuyerInformation(Auth::user()->id, ['type_of_organization_id' => $project_from_home['type_of_organization']]); 
            }
            PostProjectComponent::saveDeliverables($post_job->id, $project_from_home['deliverables'],'project',false);
            PostProjectComponent::saveSkills($project_from_home, $post_job->id,false);
            $email_componenet->sendPostProjectMail(['buyer_id' => Auth::user()->id, 'project_id' => $post_job->id]);
            if(hasSubscribed(Auth::user()->id)){
              $email_componenet->waitingProjectApprovalEmail(['buyer_id' =>Auth::user()->id , 'project_id' => $post_job->id]);  
            }
            $response['response'] = 1;
            $response['post_id'] = $post_job->id;
            $response['msg'] = config('constants.VIEW_JUST_POSTED_PROJECT');
            LoginComponent::removeCookie('project_from_home');
            return $response;
        }
    }

    public function logout(Request $request) {
        try {
            if (Auth::check()) {
                session()->forget('post_project_page_visited_' . Auth::user()->id, 1);
                session()->forget('built_with_response_' . Auth::user()->id);
                Auth::logout();
                \Session::flush();
                LoginComponent::removeCookie('project_from_home');
                LoginComponent::clearAllCookiesAfterlogout();
            }
            return redirect('/');
        } catch (Exception $ex) {
            return $ex;
        }
    }

}
