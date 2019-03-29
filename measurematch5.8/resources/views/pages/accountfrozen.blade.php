@extends('layouts.userscommonlayout')
@section('content')
    <div class="mainwrap">
        <div class="container">
            <div class="confirmation-email-design col-lg-5 col-md-6 col-sm-8 col-xs-11">
                <div class="alert alert-success" id="success" style="display: none;">
                    Email has been successfully sent.
                </div>
                <div class="alert alert-warning" id="warning" style="display: none;">
                    Email could not be sent. Please try again!
                </div>
                <a href="javascript:void();" class="email-icon"><img src="{{ url('images/side-hustler.svg',[],$ssl) }}"/></a>
                <h3>Unfortunately, we're not taking on Experts looking for side projects. </h3>
                <p>Thank you for your interest in MeasureMatch! We currently only onboard full-time independent consultants & consultancies.
                If and when you become a full-time independent consultant, please 
                <a href="mailto:contact@measurematch.com" class="font-bold">get in touch</a> to change the status of your profile.</p>
            </div>
        </div>
    </div>
@endsection