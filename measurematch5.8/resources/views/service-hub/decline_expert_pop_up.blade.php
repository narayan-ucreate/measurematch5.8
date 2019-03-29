<div class="modal fade decline-expert-pop-up" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                <div class="modal-body conversation_start_container">
                    <div class="start-conversation">
                        <form method="post" id="decline_applicant_form">
                            <button type="button" class="close" data-dismiss="modal"></button>
                            <span class="expert-profile-pic" style="background-image:url();"></span>
                            <h2 class="gilroyregular-semibold margin-bottom-25">Decline <span class="applicant-first-name"></span>'s Application</h2>
                            <h3 class="font-16 gilroyregular-semibold">Write a short note to <span class="applicant-first-name"></span> explaining your decision:</h3>
                            <textarea class="form-control decline-note" name="message" placeholder="Start typing here..." autofocus></textarea>
                            <input id="applicant_id" type="hidden" name="service_hub_applicant_id">
                            <input id="expert_id" type="hidden" name="expert_id">
                            <input id="vendor_company_name" type="hidden" name="vendor_company_name">
                            <div  class="error-message  message_validation_error hide has_error">
                                Please add a message
                            </div>
                            <div class="start-con-btn-group text-center">
                                <button type="button" class="disable-btn font-16 margin-top-10 decline-applicant">Decline <span class="applicant-first-name"></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>