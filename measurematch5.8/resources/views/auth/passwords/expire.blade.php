@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
             <div class="user-login-middle-block">
                <div class="signup_landing_page user_login_section email-check-block">
                <h2>That link has been expired</h2>
                       @if($email)
                        <p>Password reset link expire after 24 hours if unused.</p>
                       {{csrf_field()}}
                        <input type="hidden" value="{{$email}}" id="email">
                             <a class="btn submit-success" id="resent-email">Get A New Password Reset Link </a>
                       @else
                        <p>Password reset links expire once they've been used.</p>
                          <a class="btn submit-success" href="{{ url('/password/reset',[],$ssl) }}" >Get A New Password Reset Link</a>
                       @endif
                    </div>
                 <input type="hidden" value="{{ url('/password/email',[],$ssl) }}" id="url">
            </div>
        </div>
    </div>
</div>      
</section>
<script src="{{ url('js/password.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
