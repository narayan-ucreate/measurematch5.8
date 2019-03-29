<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{PostJob};
use App\Http\Requests;
use Auth;

class HomeController extends Controller
{
    public function faq(){ 
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/faq');
        return view('landingpages.faq');
    }
    
    public function aboutUs(){
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/about-us');
        return view('landingpages.aboutUs');
    }
    
    public function contactUs(){
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/contact-us');
        return view('landingpages.contactus');
    }
    
    public function termsOfService(){
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/terms-of-service');
        return view('landingpages.termsOfService');
    }
    
    public function privacyPolicy(){
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/privacy-policy');
        return view('landingpages.privacyPolicy');
    }
    
    public function cookiePolicy(){
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/cookie-policy');
        return view('landingpages.cookiePolicy');
    }
    public function defaultExperts() {
        redirectToWebFlow(config('constants.STATIC_PAGES_DOMAIN').'/our-experts');
        return view('landingpages.defaultexperts');
    }
    public function redirectAfterLogin(){
        $userType = Auth::user()->user_type_id;
        Switch ($userType) {
            Case "1":
                session()->put('first_login', 1);
                return redirect()->intended('/expert/profile-summary');
            Case "2":
                $number_of_projects_posted = PostJob::findByCondition(['user_id' => Auth::user()->id], [], ['type' => 'count']);
                if($number_of_projects_posted || Auth::user()->admin_approval_status != config('constants.APPROVED')){
                    return redirect()->intended('/project/create');
                }
                return redirect()->intended('/myprojects');
            default:
                return redirect()->intended('/');
        }
    }
}
