@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-login-middle-block">
                <div class="signup_landing_page user_login_section">
                    <h2>Reset Your Password</h2>
                    <p>Enter your new password for your account.</p>
                    <form class="reset-form"  role="form" method="POST" action="{{ url('/password/reset',[],$ssl) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                      
                        <input id="valid_email" type="hidden" class="form-control" name="valid_email" value="{{ $valid_email }}"/>
                        <div class="input-bx">
                            <label for="password" class="control-label">New Password<span class="notification_star">*</span></label>
                            <input tabindex="2" id="password" type="password" maxlength="30" class="form-control {{ $errors->has('password') ? ' error' : '' }}" name="password">
                            <div class=" validation_error1{{ $errors->has('password') ? ' has-error' : '' }}" style="color:red;">{{ $errors->first('password') }}</div>
                        </div>
                        <div class="input-bx">
                            <label for="password-confirm" class="control-label">Confirm New Password<span class="notification_star">*</span></label>
                            <input tabindex="3" id="password-confirm" type="password" maxlength="30" class="form-control {{ $errors->has('password-confirm') ? ' error' : '' }}" name="password_confirmation">
                            <div class="validation_error2{{ $errors->has('password_confirmation') ? ' has-error' : '' }}" style="color:red;">{{ $errors->first('password_confirmation') }}</div>
                        </div>
                        <div class="form-group">
                            <button tabindex="4" type="submit" class="continue-btn standard-btn" id="saveAndContinueInReset">
                                <i class="fa fa-btn fa-refresh"></i> Change my password
                            </button>
                        </div>
                     </form>
                   </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>