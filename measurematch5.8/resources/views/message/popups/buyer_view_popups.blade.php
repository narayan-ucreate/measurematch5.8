<div id="buyer_empty_popup_container"></div>
<!-- Start of Edit/update contract popup -->
<div class="modal invite-seller-popup send_contract_popup lightbox-design lightbox-design-small fade" id="edit_contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<!-- Start of view contract popup -->
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_contract_preview" class="@if(!_count($contract_detail['deliverables']) || (_count($contract_detail['deliverables']) && empty($contract_detail['deliverables'][0]['title'])))new-theme-modal @endif review-proposal modal lightbox-design fade in" style="padding-left: 13px;">
</div>
<!--coupon code  preview starts-->
<div aria-labelledby="myModalLabel"  data-dismiss="modal" role="dialog"  id="apply_coupon_pop_up" class="modal mark_completed_project got-match-popup lightbox-design-small lightbox-design coupon-code-popup invite-seller-popup fade in" style=""></div>
<!--mark project as complete preview starts-->
<div class="modal fade mark_completed_project lightbox-design lightbox-design-small profile-page-popup profile-picture-popup profile-picture-popup-seller" id="mark_as_complete_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>