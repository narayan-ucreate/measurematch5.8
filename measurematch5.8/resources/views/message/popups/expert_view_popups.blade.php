<div id="accept_contract_stage_popups">
</div>
<div class="modal invite-seller-popup send_contract_popup lightbox-design lightbox-design-small fade" id="edit_contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div aria-labelledby="myModalLabel"  data-dismiss="modal" role="dialog"  id="apply_coupon_pop_up" class="modal mark_completed_project got-match-popup lightbox-design-small lightbox-design coupon-code-popup invite-seller-popup fade in" style=""></div>
<!--mark project as complete preview starts-->
<div class="modal fade mark_completed_project lightbox-design lightbox-design-small profile-page-popup profile-picture-popup profile-picture-popup-seller" id="mark_as_complete_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="referExpert" class="modal seller-contract-popup lightbox-design-small lightbox-design fade in" style="display: none; padding-left: 13px;">

    <div class="modal-dialog" role="document">

        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                </div>

                <div class="modal-body">
                    <h3>Refer an expert</h3>
                    <div class="validate_expert_error error_message"></div>
                    <form id="refer_expert" action="{{ url('referExpert',[],$ssl)}}" method="post">
                        {{csrf_field()}}
                        <div class="input-bx"><label>Expert Name</label> <input id="referral_name" type="text" name="referral_name"/></div>                      

                        <div class="input-bx"><label>Expert Email</label> <input id="referral_email" type="text" name="referral_email"/></div>
                        <div class="clerfix"></div>
                        <input  type="button" id="refer_experts" value="Refer expert" class="new_blue_btn standard-btn btn-display-block" />

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>