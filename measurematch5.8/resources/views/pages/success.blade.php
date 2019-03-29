@extends('layouts.centralizedsignuplayout')
@section('content');
<div class="mainwrap">
  <div class="container">
       <div class="confirmation-email-design col-lg-5 col-md-6 col-sm-8 col-xs-11">
           <div class="alert alert-success" id="success" style="display: none;">
          Email has been successfully sent.
        </div>
        <div class="alert alert-warning" id="warning" style="display: none;">
            Email could not be sent. Please try again!
        </div>
      	 <a href="javascript:void()" class="email-icon"><img src="{{url('images/email-confirmation.svg',[],$ssl)}}" /></a> 
         <h3>Great! Now check your email inbox.</h3>
         <div class="user-email-box" id="user_email">{{$signup_email}}</div>
         <p>Didn’t receive an email from us? <a href="javascript:void(0)" class="request-new-link" id="resend_email">Request a new one.</a></p>
         <p>Email confirmation helps us to ensure your data stays safe.</p>
      </div>

  </div>
</div>
@stop