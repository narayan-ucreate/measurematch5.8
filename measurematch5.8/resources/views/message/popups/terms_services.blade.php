<div class="perposalview-offer view-proposal-stay-safe modal stay-safe-popup send_contract_popup lightbox-design lightbox-design-small" id="staysafecontract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="padding-left: 13px;">

    <div class="modal-dialog modal-lg billing-detail-pending">

        <div class="modal-innner-content">
            <div class="modal-content stay_safe_popup check-box-design">
                <div class="modal-body doright-things-text">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="{{ url('images/cross-black.svg',[],$ssl) }}" alt="cross" /></span></button>
                    <h3>Do the Right Thing</h3>
                    <p>Always make payments via MeasureMatch.</p>
                    <p>MeasureMatch grows & improves on the value it is creating for Experts & Clients like you by capturing 15% of each completed service milestone or contract</p>
                    <p>Making payments to Experts outside of the MeasureMatch platform is in violation of the Terms of service to which you agreed upon registration.</p>
                    <div class="check-box-design">
                        <input  type="hidden" name="stay_safe_confirm"  value="0">
                        <input id="stay_safe_confirm" type="checkbox" name="stay_safe_confirm" value="1" >
                        <label for="stay_safe_confirm"><span><span></span></span>I have read & I consent to the 
                            <a href="https://web.measurematch.com/terms-of-service" target="_blank" title="Terms of service">Terms of Service</a>
                        </label>
                        <input id="code_of_conduct" type="checkbox" name="stay_safe_confirm" value="1" >
                        <label for="code_of_conduct"><span><span></span></span>I have read & I consent to the 
                            <a href="https://web.measurematch.com/code-of-conduct" target="_blank">Code of Conduct</a>
                        </label>
                        <div  class="feedback_error error-message" id="terms-condition-error"></div>
                    </div>
                    
                    <div class="btn-group">
                        <a class="btn standard-btn " id="terms_servcies_confirm_popup"  href="javascript:void(0)">Continue & View Proposal</a>
                         <a class="cancel-btn" data-dismiss="modal" aria-label="Close">Cancel</a>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>