@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<link href="{{ url('css/signup-bg.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"/>
    <section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-login-middle-block">
                    <div class="signup_landing_page user_login_section">
                    <h2>Reset Your Password</h2>
                    <p>To reset your password, enter the email address you use to sign in to MeasureMatch.</p>
                   <form class="reset-email-form"  role="form" method="POST" action="{{ url('/password/email',[],$ssl) }}">
                        {{ csrf_field() }}

                     <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            @if (session('status'))
                            <span class="help-block">
                                <strong @if(session('status') == true) style="color: green;" @else style="color: red;" @endif >{{session('message')}}</strong>
                                        </span>
                                        @endif
                                        <div class="input-bx">
                                            <label for="email" class="control-label">Your email address:</label>
                                             <input tabindex="1" id="email" type="email" class="form-control @if ($errors->has('email')) error @endif" name="email"  placeholder="you@your-business.com" value="{{ old('email') }}">
                                            @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }} <strong>
                                            </span>
                                            @endif
                                         </div>
                    </div>
                <button tabindex="2" type="submit" class="continue-btn standard-btn">
                <i class="fa fa-btn fa-envelope"></i> Get Password Reset Link
                 </button>
                 </form>
                    </div>
                </div></div>
             </div>      </div>

   </section>
@endsection
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
