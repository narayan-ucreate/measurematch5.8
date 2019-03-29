@extends('layouts.adminlayout')
@section('content')
<style>
    .header_signup {
        background: #ffffff none repeat scroll 0 0;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12);
        float: left;
        min-height: 60px;
        position: absolute;
        top: 0;
        width: 100%;
    }
    .nav-logo-white, .nav-logo-white:focus {
        margin: 14px 0 0;
        outline: 0 none;
    }
    .admin-login-section{ margin:15% auto 0 auto; float:none; background:#2160A5; border-radius:5px;}
    .admin-login-section h2{ margin:50px 0 30px; float:left; width:100%;}
    .admin-login-section .signup_landing_page{ padding:0 10% 10% 10%;}
    .admin-login-section  label, .admin-login-section .form-group label{ color:#FFFFFF;}
    .admin-login-section .required-info{ display:block; margin-bottom:15px; color:#FFFFFF}

</style>


<header class="header_signup">
    <div class="container">
        <a class="pull-left nav-logo-white" href="{{ url('paneladmin1',[],$ssl)}}">
            <img src="{{ url('images/logo.svg',[],$ssl) }}" alt="Nav_Logo_White">
        </a>
    </div>
</header>
<section class="expert_buyer_login">
    <div class="container">
        <div class="row">
            <div class="col-md-5 admin-login-section">
                <h2>Login</h2>

                <div class="signup_landing_page">

                    <form  role="form" method="POST" action="{{ url('/admin/admin_login',[],$ssl) }}">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
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
                            @if ($errors->has('expire'))
                            <span class="help-block">
                                <strong style="color:green">{{ $errors->first('expire') }}</strong>
                            </span>
                            @endif

                            <label for="email" class="control-label">Email Address <span class="notification_star">*</span></label>


                            <input onkeyup="return forceLower(this);" id="email" type="email" class="form-control" name="mm_email" value="" >
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif


                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password <span class="notification_star">*</span></label>

                            <input id="password" type="password" class="form-control" name="mm_password">


                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif

                        </div>
                        <div class="check-box-design">
                            <input  type="hidden" name="show_characters"  value="0">
                            <input id="show_characters" type="checkbox"  name="show_characters" value="1">
                            <label for="show_characters"><span><span></span></span>Show characters</label>
                        </div>
                        <span class="required-info">* required information</span>



                        <button type="submit" class="continue-btn">
                            <i class="fa fa-btn fa-sign-in"></i> Login
                        </button>



                    </form>

                </div>
            </div>
        </div>
    </div>
   @php
$random_number = getenv('CACHING_COUNTER');
 @endphp
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
</section>
@endsection


