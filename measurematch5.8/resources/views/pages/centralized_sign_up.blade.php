@extends('layouts.centralizedsignuplayout')
@section('content')
    @php
    saveRefererUrl();
    @endphp
    <div class="mainwrap">
        <div class="container">
            <div id="choose_account_type_panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 choose-account-panel text-align-center
            @if($user_type != '')
             display-none
            @endif

            ">
                <h3 class="font-16 gilroyregular-semibold margin-top-32">Sign up to MeasureMatch</h3>
                <h2 class="font-32 gilroyregular-bold-font margin-top-20">Choose your account type:</h2>
                <div class="signup-option-section">
                    <div class="singup-block">    
                <a id="select_buyer_account" href="{{url('/signup?buyer',[],$ssl)}}" class="buyer-option-panel text-align-center">
                    <h4 class="font-24 gilroyregular-semibold margin-top-32">Client</h4>
                    <h6 class="font-18 margin-top-20">I'd like to <span class="gilroyregular-semibold">book</span> an Expert</h6>
                    <button class="buyer-account-btn margin-top-10 font-16 standard-btn gilroyregular-semibold">Create Client Account</button>
                    <div class="font-16 padding-20 primary-text-color-lighter">For brands, agencies, consultancies and systems integrators in need of extra pairs of hands.</div>
                </a>
                    </div> 
                    <div class="singup-block"> 
                <a id="select_expert_account" href="{{url('/signup?expert',[],$ssl)}}" class="expert-option-panel text-align-center">
                    <h4 class="font-24 gilroyregular-semibold margin-top-32">Expert</h4>
                    <h6 class="font-18 margin-top-20">I'd like to <span class="gilroyregular-semibold">provide</span> expertise</h6>
                    <button class="buyer-account-btn margin-top-10 font-16 standard-btn gilroyregular-semibold">Create Expert Account</button>
                    <div class="font-16 padding-20 primary-text-color-lighter">For independent consultants, consultancies and agencies to sell amazing services.</div>
                </a>
                    </div>
                    <div class="singup-block vendor-block"> 
                <a id="select_expert_account" href="{{url('/signup?vendor',[],$ssl)}}" class="vendor-register-block expert-option-panel text-align-center">
                    <h4 class="font-24 gilroyregular-semibold margin-top-32">Vendor</h4>
                    <h6 class="font-18 margin-top-20">I'd like to <span class="gilroyregular-semibold">support</span> our service partners</h6>
                    <button class="buyer-account-btn margin-top-10 font-16 standard-btn gilroyregular-semibold">Create Vendor Account</button>
                    <div class="font-16 padding-20 primary-text-color-lighter">For enterprise marketing, cloud, commerce, data management, analytics and related software systems providers.</div>
                </a>
                    </div>    
                </div>    
            </div>
            <div class="row
             @if($user_type == '')
                    display-none
            @endif
            ">
                <div class="col-lg-12 text-center margin-top-42 signup-title">
                        <h1 class="gilroyregular-bold-font font-32 ">
                            Create a MeasureMatch @if(_count(app('request')->input()) && array_key_exists('buyer', app('request')->input())){{'Client'}}@else @if ($user_type == 'expert') Expert @else <br /> Technology Vendor @endif @endif Account
                        </h1>
                        <h3 class=" gilroyregular-font font-18 margin-top-20">Fast & easy setup. No credit card required.</h3>
                </div>
                <div id="signup_panel" class="col-md-6 col-sm-12 post-project-from-home-form">
                    <div class="new-signup-screen mm-signup-form" id="myRadioGroup">
                            <div id="account_type_buyer" class="tab-content" style="display:none">
                            <div class="signup-form-panel">
                                @if ($user_type == 'buyer' || $user_type == 'vendor')
                                <form method="post" action="{{url('/saveUser',[],$ssl)}}" id="buyer_signup_form">
                                    {{ csrf_field() }}
                                    <div class="signup-first-step buyer-signup-first-step">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Your work email address</label>
                                                    <input
                                                        tabindex="1"
                                                        id="buyer_email"
                                                        class="input-bx-style work_email"
                                                        name='email'
                                                        size="60"
                                                        maxlength="60"
                                                        placeholder="you@your-company.com"
                                                        type="email"
                                                        value = "{{trim(old('email'))}}"/>
                                                    <span class="email_error @if ($errors->has('email')) has_error @endif">
                                                        @if ($errors->has('email')){{ $errors->first('email') }}@endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-password-block">
                                                    <label>Create a password <span>(minimum 6 characters including 1 number)</span></label>
                                                    <div class="relative">
                                                    <input
                                                        tabindex="2"
                                                        class="input-bx-style password"
                                                        name='psswrd'
                                                        size="30"
                                                        maxlength="30"
                                                        placeholder="Enter your password"
                                                        type="password"
                                                        autocomplete="new-password"/>
                                                    <a href="javascript:void(0)" class="show-charter hide"><img src="{{url('images/eye.svg',[],$ssl) }}" alt="show" /></a>
                                                    <input  type="hidden" name="user_type" value="{{config('constants.'.strtoupper($user_type))}}">
                                                    </div>
                                                    <span class="password_error @if ($errors->has('psswrd')) has_error @endif">
                                                        @if ($errors->has('psswrd')){{ $errors->first('psswrd') }}@endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group agree-terms-chkbx">
                                                    <div class="check-box-design">
                                                        <input tabindex="9" id="terms_and_conditions" class="terms-and-conditions" type="checkbox" value="">
                                                        <label for="terms_and_conditions"><span><span></span></span><p>I have read & agree to the </p> <a id="tnc_link" title="Terms of service" href="{{ url('terms-of-service',[],$ssl) }}" target="_blank">MeasureMatch Terms of Service</a></label>
                                                        <span class="terms-and-conditions-error"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-submit-block">
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="posted_a_project_from_homepage"  value="0">
                                                    <input value="Next" class="complete-buyer-first-step btn gilroyregular-semibold btn-primary margin-top-0 create-account-btn font-16" type="button">
                                                    <span class="already-have-account-link margin-top-25">
                                                        <p class="already-have-account-text">Already have an account?</p> <a class="" href="{{url('login',[],$ssl)}}">Login</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="loader-data hide">
                                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                    </div>
                                    <div class="signup-last-step hide buyer-signup-last-step">

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group name-fields">
                                                    <label>Your name</label>
                                                    <input tabindex="3" class="input-bx-style first_name" id="buyer_first_name" name='first_name' size="30" maxlength="30" placeholder="First" type="text" value="{{ old('first_name') }}"/>
                                                    <input tabindex="4" class="input-bx-style last_name" id="buyer_last_name" name='last_name' size="30" maxlength="30" placeholder="Last" type="text" value="{{ old('last_name') }}"/>
                                                    <span class="fname_error @if ($errors->has('first_name')) has_error @endif">@if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Your mobile number</label>
                                                    <input tabindex="5" type="tel" class="input-bx-style mobile_number_buyer mobile_number" name='mobile_number' size="15" maxlength="20" placeholder="e.g. 7123456789" value = "{{ old('mobile_number') }}">
                                                    <input type="hidden" name="country_code" class="country_code"/>
                                                    <span class="mobile_error @if ($errors->has('mobile_number')) has_error @endif">@if ($errors->has('mobile_number')){{ $errors->first('mobile_number') }}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group margin-bottom-25">
                                                    <label>Your company name</label>
                                                    <input tabindex="6" class="input-bx-style" name='company_name' id="company_name" size="100" maxlength="100" placeholder="Enter your company name" type="text" value="{{ old('company_name') }}"/>
                                                    <span id="company_name_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group margin-bottom-25">
                                                    <label>
                                                    @if ($user_type == 'buyer')
                                                    Your website address
                                                    @else
                                                    Your company website address
                                                    @endif
                                                   </label>
                                                    <input tabindex="7" class="input-bx-style" name='company_website' id="company_website" size="100" maxlength="100" placeholder="https://" type="text" value="{{ old('company_website') }}"/>
                                                    <span id="company_website_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($user_type == 'buyer')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group margin-bottom-25">
                                                    <label>How soon do you need to get a project done?</label>
                                                    <select tabindex="8" class="project-done-select form-control selectpicker" name="expected_project_post_time" id="expected_project_post_time">
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
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-submit-block">
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="posted_a_project_from_homepage"  value="0">
                                                    <input tabindex="9" value="Create Free Account" class="create_account btn gilroyregular-semibold btn-primary margin-top-0 create-account-btn font-16" type="submit">
                                                    <span class="already-have-account-link margin-top-25">
                                                        <p class="already-have-account-text">Already have an account?</p> 
                                                        <a href="{{url('login',[],$ssl)}}">Login</a> 
                                                    </span>
                                                    <div class="vendor-terms-block"> 
                                                        <p>By continuing, you confirm you have read & agree to the</p> <a id="tnc_link" title="Terms of service" href="{{ url('terms-of-service',[],$ssl) }}" target="_blank">MeasureMatch Terms of Service</a> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                    @endif
                            </div>
                            </div>
                            <div class="signup-form-panel tab-content" id="account_type_expert" style="display: none;">
                                @if ($user_type == 'expert')
                                <form method="post" action="{{url('/saveUser',[],$ssl)}}" id="expert_signup_form">
                                    {{ csrf_field() }}
                                    <div class="expert-signup-first-step">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Your email address</label>
                                                    <input
                                                        tabindex="1"
                                                        class="input-bx-style work_email"
                                                        name='email'
                                                        size="60"
                                                        maxlength="60"
                                                        placeholder="you@your-company.com"
                                                        type="email"
                                                        value = "{{ trim(old('email')) }}">
                                                    <span class="email_error @if ($errors->has('email')) has_error @endif">@if ($errors->has('email')){{ $errors->first('email') }}@endif</span>
                                                    <input  type="hidden" name="user_type" value="{{config('constants.EXPERT')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-password-block">
                                                    <label>Create a password <span>(minimum 6 characters including 1 number)</span></label>
                                                    <div class="relative">
                                                        <input
                                                            tabindex="2"
                                                            class="input-bx-style password"
                                                            name='psswrd'
                                                            size="30"
                                                            maxlength="30"
                                                            placeholder="Enter your password"
                                                            type="password"
                                                            autocomplete="new-password"/>
                                                        <a href="javascript:void(0)" class="show-charter hide">
                                                            <img src="{{url('images/eye.svg',[],$ssl) }}" alt="show" />
                                                        </a>
                                                    </div>
                                                    <span class="password_error @if ($errors->has('psswrd')) has_error @endif">@if ($errors->has('psswrd')){{ $errors->first('psswrd') }}@endif</span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group agree-terms-chkbx">
                                                    <div class="check-box-design">
                                                        <input tabindex="3" id="terms_and_conditions_expert" class="terms-and-conditions" type="checkbox">
                                                        <label for="terms_and_conditions_expert">
                                                            <span><span></span></span>
                                                            <p>I have read & agree to the</p> <a id="tnc_link" title="Terms of service" href="{{ url('terms-of-service',[],$ssl) }}" target="_blank">MeasureMatch Terms of Service</a>
                                                        </label>
                                                        <span class="terms-and-conditions-error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-submit-block">
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="posted_a_project_from_homepage"  value="0">
                                                    <div class="clearfix"></div>
                                                    <input tabindex="3" value="Next" class="complete-expert-first-step btn gilroyregular-semibold btn-primary margin-top-0 create-account-btn font-18" type="submit">
                                                    <span class="already-have-account-link">
                                                        <p class="already-have-account-text">Already have an account?</p> <a class="gilroyregular-bold-font" href="{{url('login',[],$ssl)}}">Login</a> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="expert-signup-last-step hide">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group  name-fields">
                                                    <label>Your name</label>
                                                    <input tabindex="4" class="input-bx-style first_name" name='first_name' size="30" maxlength="30" placeholder="First" type="text" value="{{ old('first_name') }}"/>
                                                    <input tabindex="5" class="input-bx-style last_name" name='last_name' size="30" maxlength="30" placeholder="Last" type="text" value="{{ old('last_name') }}"/>
                                                    <span class="fname_error @if ($errors->has('first_name')) has_error @endif">@if ($errors->has('first_name')){{ $errors->first('first_name') }}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group margin-bottom-20">
                                                    <label>Your mobile number</label>
                                                    <input type="text" tabindex="6" class="input-bx-style mobile_number" name='mobile_number' size="15" maxlength="20" placeholder="e.g. 7123456789" value = "{{ old('mobile_number') }}">
                                                    <input type="hidden" name="country_code" class="country_code"/>
                                                    <span class="mobile_error @if ($errors->has('mobile_number')) has_error @endif">@if ($errors->has('mobile_number')){{ $errors->first('mobile_number') }}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group select-box service-provider-field">
                                                    <label>What type of service provider are you?</label>
                                                    <select tabindex="7" class="selectpicker rate_select select-dropdown-style" name="expert_type" id="expert_type">
                                                        <option value="">Choose</option>
                                                        <option value="3">{{ config('constants.EXPERT_TYPE_DESCRIPTION.FULL_TIMER') }}</option>
                                                        <option value="1">{{ config('constants.EXPERT_TYPE_DESCRIPTION.INDEPENDENT') }}</option>
                                                        <option value="2">{{ config('constants.EXPERT_TYPE_DESCRIPTION.CONSULTANCY') }}</option>
                                                    </select>
                                                    <span class="expert_type_error @if ($errors->has('expert_type')) has_error @endif">@if ($errors->has('expert_type')){{ $errors->first('expert_type') }}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group signup-submit-block">
                                                    <div class="clearfix"></div>
                                                    <input type="hidden" name="posted_a_project_from_homepage" value="0">
                                                    <div class="clearfix"></div>
                                                    <input tabindex="9" value="Create Free Account" class="create_account btn gilroyregular-semibold btn-primary margin-top-0 create-account-btn font-18" type="submit">
                                                    <span class="already-have-account-link">
                                                        <p class="already-have-account-text">Already have an account?</p>
                                                        <a class="gilroyregular-bold-font" href="{{url('login',[],$ssl)}}">Login</a>
                                                    </span>
                                                    <div class="vendor-terms-block">
                                                        <p>By continuing, you confirm you have read & agree to the</p>
                                                        <a id="tnc_link" title="Terms of service" href="{{ url('terms-of-service',[],$ssl) }}" target="_blank">MeasureMatch Terms of Service</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                    @endif
                            </div>
                    </div>
                    </div>
                    <div id="right_banner" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 signupside hide" >
                        <div id="buyer_right_banner">
                            <div class="signup-why-block">
                                <h3 class="font-14 gilroyregular-semibold">Why businesses are using MeasureMatch:</h3>
                                <ul>
                                    <li>Quickly address important systems & data needs fast</li>
                                    <li>Find an Expert in days, not months</li>
                                    <li>Every single MeasureMatch Expert is screened & vetted</li>
                                </ul>
                            </div>
                            <div class="signup-question-block">
                                <h3 class="font-14 gilroyregular-semibold">Have a question?</h3>
                                <ul>
                                    <li><a href="#">Speak with us now</a></li>
                                    <li><a href="https://web.measurematch.com/faq" target="_blank">Visit our FAQ</a></li>
                                </ul>
                            </div>
                            <div class="signup-testimonial-block">
                                <p>
                                    "As a Talent Manager, I am often asked to find interim resource quickly.
                                    MeasureMatch proved to be an invaluable, agile route to securing a hands-on enterprise analytics contractor who could otherwise be really tricky and very time-consuming to find."
                                </p>
                                <div class="testimonial-detail">
                                    <div class="testimonial-user-img-buyer">
                                        <img src="{{ url('images/syneos_health_logo.svg',[],$ssl) }}" alt="show" />
                                    </div>
                                    <div class="testimonial-user-name">
                                        <h4 class="font-14 gilroyregular-semibold">Kayleigh McDonald</h4>
                                        <p>Syneos Health</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="expert_right_banner">
                            <div class="signup-why-block">
                                <h3 class="font-14 gilroyregular-semibold">Why consultants & consultancies are using MeasureMatch:</h3>
                                <ul>
                                    <li>Pitch for valuable software systems and data projects</li>
                                    <li>Proposals, contracts & invoicing admin handled in the platform</li>
                                    <li>Augment your profile with directly bookable Service Packages</li>
                                </ul>
                            </div>
                            <div class="signup-question-block">
                                <h3 class="font-14 gilroyregular-semibold">Have a question?</h3>
                                <ul>
                                    <li><a href="#">Speak with us now</a></li>
                                    <li><a href="https://web.measurematch.com/faq" target="_blank">Visit our FAQ</a></li>
                                </ul>
                            </div>
                            <div class="signup-testimonial-block">
                                <p>“We've been loving MeasureMatch and are thrilled to be part of it as it grows. We can't wait to see where you take it.”</p>
                                <div class="testimonial-detail">
                                    <div class="testimonial-user-img">
                                        <img src="{{url('images/maxwell.png',[],$ssl) }}" alt="show" />
                                    </div>
                                    <div class="testimonial-user-name">
                                        <h4 class="font-14 gilroyregular-semibold">Maxwell A</h4>
                                        <p>MeasureMatch Expert</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="vendor_right_banner">
                            <div class="signup-why-block">
                                <h3 class="font-14 gilroyregular-semibold">Why technology vendors are using MeasureMatch::</h3>
                                <ul>
                                    <li>To provide clients with unparalleled access to verified professional services providers.</li>
                                    <li>To gain powerful new market intelligence.</li>
                                    <li>To lift visibility across a broad range of new prospective clients and grow sales.</li>
                                </ul>
                            </div>
                            <div class="signup-question-block">
                                <h3 class="font-14 gilroyregular-semibold">Have a question?</h3>
                                <ul>
                                    <li><a href="#">Speak with us now</a></li>
                                    <li><a href="https://web.measurematch.com/faq" target="_blank">Visit our FAQ</a></li>
                                </ul>
                            </div>
                            <div class="signup-testimonial-block">
                                <p>"The globalisation of both talent and technology is now freeing up companies so they can experiment in new ways to fill critical skills gaps while staying lean."</p>
                                <div class="testimonial-detail">
                                    <div class="testimonial-user-img">
                                        <img src="{{url('images/unilever-logo.png',[],$ssl) }}" alt="show" />
                                    </div>
                                    <div class="testimonial-user-name">
                                        <h4 class="font-14 gilroyregular-semibold">Richard Jerrett</h4>
                                        <p>Unilever</p>
                                    </div>
                                </div>
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
