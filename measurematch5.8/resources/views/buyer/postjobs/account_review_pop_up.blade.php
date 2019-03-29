<?php
$user_id =  Auth::user()->id;
?>
<div class="modal lightbox-design review-detail-popup" id="account_review_pop_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="https://measurematch.herokuapp.com/images/cross-black.svg"></span></button>
                <div class="modal-body text-align-center">
                    <div class="review-detail-content">
                    <img src="{{ url('images/account-locked.svg',[],$ssl) }}" alt="" />
                    <h3 class="font-28 margin-top-30 text-align-center">Your account is currently under review!</h3>

                    <h4 class="font-18 text-align-center gilroyregular-font margin-bottom-30">
                        All new @if(Auth::user()->user_type_id == config('constants.BUYER')){{'Clients'}}@else{{'Vendors'}}@endif are reviewed by the MeasureMatch team before they are approved to the platform. You'll hear from us within 72 hours.
                    </h4>
                    <!-- Calendly link widget begin -->
                    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
                    <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript"></script>
                    <!-- Calendly link widget end -->
                    <h4 class="font-18 text-align-center gilroyregular-font margin-bottom-20">
                    Want to speed up the process? <a href="" class="gilroyregular-font"
                    onclick="Calendly.showPopupWidget('https://calendly.com/measurematch/30');return false;">Schedule a chat</a> 
                    with one of our Account Managers.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
