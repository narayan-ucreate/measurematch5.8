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
            
                    <form  role="form" method="POST" action="{{ url('/password/reset',[],$ssl) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                             @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                        	<div class="input-bx">
                            <label for="email" class="control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        	<div class="input-bx">
                            <label for="password" class="control-label">Password</label>


                                <input size="30" maxlength="30" id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <div class="input-bx">
                            <label for="password-confirm" class="control-label">Confirm Password</label>

                                <input size="30" maxlength="30" id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">

                                <button type="submit" class="continue-btn standard-btn">
                                    <i class="fa fa-btn fa-refresh"></i> Reset Password
                                </button>

                        </div>
                    </form>

            </div></div>
        </div>
    </div>
</div>
@endsection
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>