@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<link href="{{ url('css/signup-bg.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"/>

<section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
        <div class="row">
            <div class="user-login-middle-block">


                <div class="signup_landing_page user_login_section">
<h2>Login</h2>
                    <form  role="form" method="POST" action="{{ url('/login',[],$ssl) }}">
                        {!! csrf_field() !!}


                        <div class="form-group{{ $errors->has('error') ? ' has-error' : '' }}">
                            @if ($errors->has('error'))
                            <span class="help-block">
                                <strong>{{ $errors->first('error') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('success'))
                            <span class="help-block" >
                                <strong style="color:green">{{ $errors->first('success') }}</strong>
                            </span>
                            @endif
                             <span class="help-block" >
                                <strong style="color:green">{{ session('success') }}</strong>
                            </span>
                            @if ($errors->has('expire'))
                            <span class="help-block">
                                <strong style="color:green">{{ $errors->first('expire') }}</strong>
                            </span>
                            @endif

                            <label for="email" class="control-label">Email address <span class="notification_star">*</span></label>

                            <input tabindex="1" id="email" type="text" class="form-control" placeholder="you@your-business.com"  name="mm_email" style="text-transform:lowercase;" >
                        </div>

                        <div class="login-password-group form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password <span class="notification_star">*</span></label>
                         <input id="posted_project_from_homepage" type="hidden" class="form-control" value="@if(strpos(URL::previous(), 'postproject/finalstep') !== false || strpos(URL::previous(), 'post-project-login-from-homepage') !== false){{'1'}} @else{{'0'}} @endif" name="posted_project_from_homepage">

                            <input  tabindex="2" id="password" type="password" class="form-control" placeholder="Your password" name="mm_password" maxlength="50">


                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif

                        </div>
                        <div class="check-box-design">
                            <a class="btn btn-link pull-right gilroyregular-bold-font forgot-password-link" href="{{ url('/password/reset',[],$ssl) }}">Forgot password?</a>
                        </div>

                        <button type="submit" class="continue-btn standard-btn">
                             Login
                        </button>
<span class="no-account-lbl">Not signed up? <a class="gilroyregular-bold-font" href="{{url('signup',[],$ssl)}}" title="Create an account">Create an account</a></span>
                        

                        
                        
                        <span class="no-account-lbl">By logging in you are reconfirming acceptance of our <a href="{{url('terms-of-service',[],$ssl)}}" title="Terms of Service">Terms of Service</a>.</span>
                    </form>

                </div>
            </div>
        </div>
    </div>
   </section>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
@endsection