@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar">
                <div class="theiaStickySidebar">
                    @include('buyer.sidemenu')
                </div>
            </div>
            <div class="col-md-9 rightcontent-panel support-page-design">
                <div class="theiaStickySidebar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="feedback-panel" id="feedback_panel">

                                    <h2>Support</h2>
                                    <h3>Write your message here or send an email to <a href="mailto:support@measurematch.com">support@measurematch.com</a></h3>
                                    <div class="clearfix"></div>
                                    <form id="support_message_to_admin" name="support_message_to_admin" >
                                        <textarea  id="support_message" name="user_message"  value="" placeholder="Start typing here..." maxlength="4000" class="input-bx-style texarea-minheight-75"></textarea>
                                        <span id="support_message_error" class="error-message"> </span>
                                        <div class="clearfix"></div>
                                        <input id="submit_support_request" type="button" value="Send" class="btn btn-primary next-step-btn">
                                        <p>Need more help? Visit our <a target="_blank" href="{{url('/faq')}}">FAQ</a></p>
                                    </form>
                                </div>
                                <div class="feedback-panel" id="support_success_panel" style="display: none;">
                                    <h2>Support</h2>
                                    <p class="thankyou-for-support">Thank you for contacting us. We will get back to you shortly.</p>
                                    <p>Need more help? Visit our <a target="_blank" href="{{url('/faq')}}">FAQ</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div></div>

        </div>
    </div>
</div>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.footer')
<script src="{{ url('js/addMore.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
