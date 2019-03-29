<div class="modal-dialog modal-lg">

    <div class="modal-innner-content">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
            </div> 

            <div class="modal-body">
                <h3> Apply a Promo Code</h3> 

                <div class="contract_coupon">
                    <p>Got a code? Pop it in here to take advantage of a friendly gift from the MeasureMatch team.</p>
                    <div style="display:block" id="redeemCoupon" class="apply-coupon-field">
                        <label>Apply a Promo Code</label>
                        <input id="redeemCouponValue-{{ $contract_detail['id'] }}" type="text" name="redeemCouponValue-{{ $contract_detail['id'] }}" placeholder="Enter Promo Code Here" value="">
                        <div class="validate_expert_error coupon-code-error"></div>
                        <input type="button" name="redeemSubmit" data-contract_id="{{ $contract_detail['id'] }}" data-rate="{{$contract_detail['rate']}}"  class="redeemSubmit standard-btn" value="Submit"/>


                    </div> 
                    <a data-toggle="modal"  data-dismiss="modal" contract_id="{{ $contract_detail['id'] }}" class=" sta coupon_popup_back" href="javascript:void(0)">Cancel/Go back</a>
                </div>

            </div>
        </div>
    </div>
</div>