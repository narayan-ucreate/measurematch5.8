<div id="business_address_popup" class="business-address-popup modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}">
                    </span>
                </button>
                <div class="modal-body conversation_start_container">
                    <div id="vat_details_section_on_start_conversation" class="send-proposal-section">
                        <div class="col-md-12 info-right-side">
                            <div class="send-proposal-content">
                                <h5 class="font-24">Before you view {{ucfirst($contract_detail['expert']['name'])}}'s proposal… </h5>
                                <p class="font-16">Because you’re about to look at your first proposal sent from a MeasureMatch Expert, we need to know some of your business details so we can factor in the necessary Value Added Tax (VAT) calculations.</p>
                            </div>
                            <form id="save_business_address" method="post" action="{{url('buyer/save-business-address/'.Auth::user()->id )}}">
                                <input type="hidden" name="communication_id" value="{{$communication_id}}">
                                <input type="hidden" id='country_search_source' value="{{$countries}}">
                                <div id="business_address" class="business-address-details">                                    
                                    <div class="input-bx">
                                        <label>Business Address Line 1</label>
                                        <input tabindex="6" maxlength="40" size="30" type="text" id="first_address" name="first_address"
                                               value="{{ isset($business_address->first_address) ? $business_address->first_address : '' }}">
                                    </div>
                                    <div class="input-bx">
                                        <label>Business Address Line 2</label>
                                        <input tabindex="6" maxlength="40" size="30" type="text" id="second_address" name="second_address"
                                               value="{{ isset($business_address->second_address) ? $business_address->second_address : '' }}">
                                    </div>
                                    <div class="input-bx">
                                        <label>City/Town</label>
                                        <input id="business_city" name="business_city" tabindex="8" type="text" maxlength="40"
                                               value="{{ isset($business_address->city) ? $business_address->city : '' }}">
                                    </div>
                                    <div class="input-bx">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>State / Region / County</label>
                                                <input tabindex="10" maxlength="30" size="30" type="text" id="business_state" name="business_state"
                                                       value="{{ isset($business_address->state) ? $business_address->state : '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Postal / ZIP Code</label>
                                                <input tabindex="10" maxlength="10" size="30"type="text" id="business_postal_code" name="business_postal_code"
                                                       value="{{ isset($business_address->postal_code) ? $business_address->postal_code : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-bx">
                                        <label>Country</label>
                                        <input id="buyer_business_country" name="business_country" placeholder="e.g. United Kingdom" value="@if(isset($business_address->country)){{$business_address->country}}@endif" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
                                    </div>
                            </div>
                                <div class="read-m-terms">
                                    <div class="read-m-inner">
                                <h3 class="font-18">Please read the MeasureMatch Terms</h3>
                                <p>MeasureMatch grows and improves on the value it is creating for Experts & Clients like you by capturing 15% of each completed service milestone or contract.</p>
                                <p>Making payments to Experts outside of the MeasureMatch platform is in violation of the Terms of service to which you agreed to upon registration. </p>
                                <div class="check-box-design">
                                    <input  type="hidden" name="stay_safe_confirm"  value="0">
                                    <input id="stay_safe_confirm" type="checkbox" name="stay_safe_confirm" value="1" >
                                    <label for="stay_safe_confirm"><span><span></span></span>I have read & I consent to the 
                                        <a href="https://web.measurematch.com/terms-of-service" target="_blank" title="Terms of service">Terms of Service</a>
                                    </label>
                                    <input id="code_of_conduct" type="checkbox" name="code_of_conduct" value="1" >
                                    <label for="code_of_conduct"><span><span></span></span>I have read & I consent to the 
                                        <a href="https://web.measurematch.com/code-of-conduct" target="_blank">Code of Conduct</a>
                                    </label>
                                    <div  class="feedback_error error-message" id="terms-condition-error"></div>
                                </div>
                                <div class="input-bx"> 
                                <input id="submit_business_info" tabindex="11" type="submit" class="info-save-btn disable-btn full-width-btn" value="Continue & View {{ucfirst($contract_detail['expert']['name'])}}'s Proposal" >
                                 
                            </div>
                                </div>
                            </div> 
                            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    input_data = $.map(
        JSON.parse($('#country_search_source').val()),
        function (value, key) {
            return {
                'label': value.country_name,
                'value': value.country_name,
                'vat_registered_status': value.vat,
                'country_code': value.country_code,
                'is_eu': value.eu,
            };
    });
    
    $('#buyer_business_country').autocomplete({
        source: input_data,
        minLength: 0
    }).bind('focus', function(){
        $(this).autocomplete("search");
    });
    
    $('#buyer_business_country').autocomplete({
        select: function( event, ui ) {
            var country_code = ui.item.country_code;
            $('#vat_country').val(country_code);
        },
        change: function(event, ui) {
            if (ui.item == null) {
                $(this).val("");
                $(this).focus();
            }
        }
    });
</script>