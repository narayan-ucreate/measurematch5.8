@extends('layouts.logo_only_layout')
@section('content')
<link rel="stylesheet" href="{{ url('css/style_signup.css?css='.$random_number,[],$ssl)}}">
<section class="unsubscribe_wrapper mainwrap">
  <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="signup_landing_page">
                @if(Session::has('warning'))
                <div class="alert alert-warning"> <strong>Warning!</strong>  {{Session::get('warning')}}</div> 
                @else
                <div class="alert alert-success"> <strong>Success!</strong>    Email unsubscribed successfully.</div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@include('include.basic_javascript_liberaries')
<script src="{{ url('/js/unsubscribe.js?js='.$random_number,[],$ssl) }}"></script>