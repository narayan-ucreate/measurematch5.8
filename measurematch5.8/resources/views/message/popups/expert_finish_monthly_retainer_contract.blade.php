<?php
$user_id =  Auth::user()->id;
?>
<div aria-labelledby="myModalLabel"  data-dismiss="modal" role="dialog"  class="modal lightbox-design-small lightbox-design fade in" style="" id="expert-cancel-confirm-pop-up">
    <div class="modal-dialog suggest-project-popup finish-package-popup lightbox-design" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                </div>
                <div class="modal-body">
                    @if(strtotime($contract_detail['job_start_date']) <= strtotime(date('Y-m-d')))
                    <h3>Are you sure you'd like to finish/cancel this contract?</h3>
                    <h4 class="gilroyregular-font text-align-center">You'll still need to deliver your expected time commitment of {{$contract_detail->monthly_days_commitment}} days/month before the next payment date of {{nextBillingDateForMonthlyRetainer($contract_detail->id)}}</h4>

                    <div class="popup-btn-panel">
                        <input id="expert-confirm-finish-service-package" type="button" id="{{  $contract_detail->id }}" name="submit_service_package" data-toggle="modal" data-target="#service-package-reivew-thankyou" value="Yes, I confirm" class="continue-btn green_gradient standard-btn" type="button">
                        <input id="cancel-finish-service-package" value="Cancel" class="continue-btn white-btn" type="button">
                    </div> 
                     @else
                     <h4 class="gilroyregular-font text-align-center">This contract hasn't started yet. Therefore, you can't finish/cancel it before the start date which is {!! date('d M Y',strtotime($contract_detail['job_start_date'])) !!}.</h4>
                     <div class="text-align-center">
                            <input id="cancel-finish-service-package" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                    </div>                            
                    @endif
                </div>
            </div>
        </div>
    </div>                                    
</div>