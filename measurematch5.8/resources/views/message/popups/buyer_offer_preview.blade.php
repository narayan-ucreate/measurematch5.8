<div aria-labelledby="myModalLabel" role="dialog" data-dismiss="modal" tabindex="-1" id="preview_before_make_offer" class="modal perposalview-offer got-match-popup seller-contract-popup invite-seller-popup lightbox-design-small lightbox-design fade in">
    <div class="modal-dialog modal-lg">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-body">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                    <div class="modal-container">
                        <h3 class="margin-bottom-25">Send a Proposal to <span id="preview_company_name"> </span></h3>
                        <div class="row perposalview-blocks">
                            <div class="col-md-6">
                                <h5>Project Start Date</h5>
                                <p id="preview_start_date"> </p>
                            </div>
                            <div class="col-md-6 ">
                                <h5>Estimated Completion Date</h5>
                                <p id="preview_end_date"></p>
                            </div>
                        </div>
                        <div class="perposalview-blocks">
                            <h5>Total value of proposal</h5>
                            <p id="preview_project_price"> </p>
                        </div>
                        <div class="job-post-deliverable  perposalview-blocks">
                            <h5>Deliverables for the project</h5>
                            <p id='preview_deliverable' ></p>
                        </div>
                        <div class="perposalview-blocks">
                        <h5>Attachments</h5>

                        <span id="preview_attachment" class="no_attachment_block font-14">
                        </span>
                        </div>
                        <div class="contract-popup-actions">
                            <input class="standard-btn" onclick="submitPreview(this);" id="contract_preview_submit" value="Send Proposal" type="button">
                            <span class="cancel-btn" id="edit_contract_before_make_offer">Go Back & Edit</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

