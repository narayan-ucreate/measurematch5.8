@extends('layouts.centralizedsignuplayout')
@section('content')
<?php
saveRefererUrl();
?>
<div class="mainwrap">
    <div class="container">
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="new-signup-screen mm-signup-form newsignup-form-design">
                <div id="myTabContent" class="tab-content">
                    <span class="error-message full-width-block display-block text-align-center margin-bottom-20">
                        @if(Session::has('general_error'))
                        {{trim(Session::get('general_error'))}}
                        @endif
                    </span>
                    <div class="tab-pane active in" id="buyer-tab">
                        <form method="post" action="{{url('/saveUser',[],$ssl)}}" id="buyer_signup_form">
                            @php
                            $referral_expert_email_id = Session::get('referral_expert_email_id');
                            @endphp
                            {{ csrf_field() }}
                            <input type="hidden" name="source" value="signup_after_project_post">
                            <h2 class="font-32 gilroyregular-bold-font">Sign up to MeasureMatch</h2>
                            <h3 class="font-20 gilroyregular-font">You’re almost there! The final step is create a MeasureMatch account.</h3>
                            <div class="row margin-top-30">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>First name</label>
                                        <input tabindex="1" class="input-bx-style first_name" name='first_name' size="30" maxlength="30" placeholder="Your first name" type="text" value="{{ old('first_name') }}"/>
                                        <span class="fname_error @if ($errors->has('first_name')) has_error @endif">@if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Last name</label>
                                        <input tabindex="2" class="input-bx-style last_name" name='last_name' size="30" maxlength="30" placeholder="Your last name" type="text" value="{{ old('last_name') }}"/>
                                        <span class="lname_error @if ($errors->has('last_name')) has_error @endif">@if ($errors->has('last_name')){{ $errors->first('last_name') }}@endif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Your work email</label>
                                <input tabindex="3" class="input-bx-style work_email" name='email' size="60" maxlength="60" placeholder="Enter your work email" type="email" value = "@if(isset($referral_expert_email_id) && !empty($referral_expert_email_id)){{$referral_expert_email_id}}@elseif((old('email'))){{ trim(old('email')) }}@endif" @if(isset($referral_expert_email_id) && !empty($referral_expert_email_id)) style="pointer-events: none;" @endif>
                                       <span class="email_error @if ($errors->has('email')) has_error @endif">@if ($errors->has('email')){{ $errors->first('email') }}@endif</span>
                            </div>
                            <div class="form-group signup-password-block">
                                <label>Create a password (min 6 characters, including 1 number)</label>
                                <input tabindex="4" class="input-bx-style password" name='psswrd' size="30" maxlength="30" placeholder="Create a password" type="password" autocomplete="new-password"/>
                                <span class="password_error @if ($errors->has('psswrd')) has_error @endif">@if ($errors->has('psswrd')){{ $errors->first('psswrd') }}@endif</span>
                                <div class="check-box-design">
                                    <input  type="hidden" name="show_characters" value="0">
                                    <input  type="hidden" name="user_type" value="{{config('constants.BUYER')}}">
                                    <input tabindex="5" id="show_characters_expert" class="show_characters" type="checkbox" name="show_characters" value="1">
                                    <label class="font-18 gilroy-semibold" for="show_characters_expert"><span><span></span></span>Show characters</label>
                                </div>
                            </div>

                            <div class="form-group phone-numberblock">
                                <label>Your phone number</label>
                                <input type="tel" tabindex="6" class="input-bx-style mobile_number" name='mobile_number' size="15" maxlength="20" placeholder="Your phone number" value = "{{ old('mobile_number') }}">
                                <input type="hidden" name="country_code" class="country_code"/>
                                <span class="mobile_error @if ($errors->has('mobile_number')) has_error @endif">@if ($errors->has('mobile_number')){{ $errors->first('mobile_number') }}@endif</span>
                            </div>


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Your company name</label>
                                        <input tabindex="7" class="input-bx-style" name='company_name' id="company_name" size="30" maxlength="30" placeholder="Your company name" type="text" value="{{ old('company_name') }}"/>
                                        <span id="company_name_error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Your company URL</label>
                                        <input tabindex="8" class="input-bx-style" name='company_website' id="company_website" size="30" maxlength="30" placeholder="Your company URL" type="text" value="{{ old('company_website') }}"/>
                                        <span id="company_website_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group margin-bottom-25">
                                        <label>How soon do you need to get a project done?</label>
                                        <select class="project-done-select form-control selectpicker" name="expected_project_post_time" id="expected_project_post_time">
                                            <option value="">Choose</option>
                                            @foreach(config('constants.EXPECTED_PROJECT_POST_TIME') as $key => $time)
                                                <option value="{{$key}}">{{$time}}</option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix">&nbsp;</div>
                                        <span id="expected_project_post_time_error"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group agree-terms-chkbx">
                                <div class="clearfix"></div>
                                <input type="hidden" name="posted_a_project_from_homepage " id="posted_a_project_from_homepage" value="0">

                                <div class="check-box-design margin-bottom-30">
                                    <input tabindex="9" id="terms_and_conditions" class="terms-and-conditions" type="checkbox" >
                                    <label class="font-18 gilroyregular-font" for="terms_and_conditions"><span><span></span></span>I have read and agree to the <a id="tnc_link" title="Terms of service" href="{{ url('terms-of-service',[],$ssl) }}" target="_blank">MeasureMatch Terms of Service</a>.</label>
                                    <span class="terms-and-conditions-error"> </span>
                                </div>
                                <input tabindex="10" value="Create Account" class="create_account btn gilroyregular-semibold btn-primary margin-top-0 create-account-btn font-18" type="submit">
                                <span class="muted-text display-inline-text font-18 already-have-account-link">Already have an account? <a class="gilroyregular-bold-font" href="{{url('post-project-login-from-homepage',[],$ssl)}}">Login</a> </span>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
        <div id="right_banner" class="col-lg-5 col-md-5 col-sm-5 col-xs-12 signupside" >
                <div class="signup-info-right">
                    <div  class="text-align-center margin-top-30 logoinfo">
                    <img src="{{url('images/mm-logo-white.svg',[],$ssl) }}" alt="mm-logo-white" />
                    </div>
                    <ul class="signupinfo-right-ul gilroyregular-semibold">
                        <li>
                            <i class="fa fa-star" aria-hidden="true"></i> Address important systems and data needs fast
                        </li>
                        <li>
                            <i class="fa fa-star" aria-hidden="true"></i> Use Technographic Match&trade; to find & book service providers
                        </li>
                        <li>
                            <i class="fa fa-star" aria-hidden="true"></i> Every single MeasureMatch Expert interviewed
                        </li>
                        <li>
                            <i class="fa fa-star" aria-hidden="true"></i>Only pay for work completed
                        </li>
                    </ul>
                    <h2 class="signupinfo-logo-title gilroyregular-semibold">OUR EXPERTS ARE TRUSTED BY:</h2>
                    <ul class="signupinfo-logo">
                        <li><img src="{{url('images/signupimg/new-balance.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/barclays.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/toyota.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/dyson.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/nivea.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/samsung.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/amex.png',[],$ssl) }}" alt="" /></li>
                        <li><img src="{{url('images/signupimg/Nike.png',[],$ssl) }}" alt="" /></li>
                    </ul>
                    <div class="tweet-box">
                        <p class="gilroyregular-semibold">
                            “Everything went great. Our MeasureMatch Expert visited us in our office twice and really understood our challenge. 
                            She will come again today to complete the deliverables and close the project.”
                        </p>
                        <div class="user-info">
                            <span class="user-img"></span>    
                            <p>Jean-Marie, MeasureMatch Client</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>
<script type="text/javascript">
    check_email_url = '{{url("checkUniqueEmail", [], $ssl)}}';
    check_buyer_email_url = '{{url("checkbuyeremailtobebusinessemail", [], $ssl)}}';
</script>
@stop
