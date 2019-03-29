<div class="modal-dialog suggest-project-popup finish-package-popup lightbox-design" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                </div>
                <div class="modal-body">
                    @if(strtotime($contract_detail['job_start_date']) <= strtotime(date('Y-m-d')))
                    <h3>Finish/Cancel Retainer</h3>
                    <h4 class="gilroyregular-font text-align-center">Are you sure you would like to finish or cancel<br /> this retainer with <span id="expert_name">{{$expert_name}}</span>?</h4>
                    
                    <h6 class="gilroyregular-font">
                        (If you decide to finish or cancel now, you'll still receive the<br /> agreed package deliverables untill 
                    <span id="contract-date">
                        <span class="view_sp_contract_end_date">{{nextBillingDateForMonthlyRetainer($contract_detail['id'])}}</span>
                    </span>)</h6>
                    
                    
                    <div class="popup-btn-panel">
                      
                        <input id="confirm-finish-service-package" name="submit_service_package" data-toggle="modal" data-target="#service-package-reivew-thankyou" value="Yes, I confirm" class="continue-btn green_gradient standard-btn" type="button">
                        <input id="cancel-finish-service-package" value="No, continue with retainer" class="continue-btn white-btn" type="button">
                    </div> 
                    @else
                     <h4 class="gilroyregular-font text-align-center">This contract hasn't started yet. Therefore, you can't finish/cancel it before the start date which is {!! date('d M Y',strtotime($contract_detail['job_start_date'])) !!}.</h4>
                    <input id="cancel-finish-service-package" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                    @endif
                </div>
            </div>
        </div>
    </div>                                    