@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<link href="{{ url('css/signup-bg.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"/>

<section class="expert_buyer_login user_login_block mainwrap">
    <div class="container">
        <div class="row">
            <div class="user-login-middle-block">


                <div class="signup_landing_page email-check-block">
                    <h2>Success!</h2> 
                    <p>Groovy. Your password is now updated.</p>
                <a class="btn submit-success" href="{{route('login').'?email='.$email}}">Login to MeasureMatch now</a>
               
                    

                </div>
            </div>
        </div>
    </div>
</section>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/show_character.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
