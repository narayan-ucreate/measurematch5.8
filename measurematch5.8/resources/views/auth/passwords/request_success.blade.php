@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<link href="{{ url('css/signup-bg.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"/>

<section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
        <div class="row">
            <div class="user-login-middle-block">


                <div class="signup_landing_page email-check-block">
                    <img src="{{url('images/email-confirmation.svg',[],$ssl)}}" alt="" />
                    <h2>Check your email!</h2>
                    <p>An email has been sent to:</p>
                    <div class="email-success-reset">{{$email}}</div>

                </div>
            </div>
        </div>
    </div>
</section>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
