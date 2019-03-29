@php
    $random_number = getenv('CACHING_COUNTER');
    $ssl = getenv('APP_SSL');
@endphp
<div id="vat_details_popup" class="vat-detail-popup modal fade" role="dialog">
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
                                <h5 class="font-24">Before your message is sent to {{ucfirst($user_name)}}... </h5>
                                <p class="font-16">Please confirm some of your company details.</p>
                            </div>
                            <form id="submit_vat_details" method="post" action="{{url('user/store-vat-detais/'.Auth::user()->id )}}">
                                {{csrf_field()}}
                                <input type="hidden" id='business_type' name='business_type'
                                      value="{{ isset($business_information->type) ? $business_information->type : '1' }}">
                                <input type="hidden" id='start_conversation_popup' name='start_conversation_popup' value="1">
                                <input type="hidden" id='country_search_source' value="{{$countries}}">
                                <div id="registered_company_section" class="business-address-details ">
                                    <div class="input-bx">
                                        <label>What country is your company registered in?</label>
                                        <input id="vat_company_country" name="company_country" placeholder="e.g. United Kingdom" value="@if(isset($business_information->businessDetails->company_country)){{$business_information->businessDetails->company_country}}@endif" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
                                        <input id="vat_country" name="vat_country" value="@if(isset($business_information->businessDetails->vat_country)){{$business_information->businessDetails->vat_country}}@endif" type="hidden">
                                    </div>
                                <div id="vat_block" class="input-bx @if(!isset($business_information->businessDetails->vat_status)) hide @endif">
                                    <label>Is your company VAT registered?</label>
                                    <p>VAT stands for “Value Added Tax”. In some markets, it’s also known as “GST” or “IVA”.</p>
                                    <div class="radio-button-style">
                                        <input id="vat_registered" type="radio" name="vat_registered" value="on" @if(isset($business_information->businessDetails->vat_status) && $business_information->businessDetails->vat_status) checked="true" @endif>
                                        <label class="gilroyregular-font" for="vat_registered"><span class="radio-circle"><span></span></span>Yes, we are a VAT registered company</label>
                                    </div>
                                    <div class="radio-button-style">
                                        <input id="no_vat_registered" type="radio" name="vat_registered" value="off" @if(isset($business_information->businessDetails->vat_status) && !$business_information->businessDetails->vat_status) checked="true" @endif>
                                        <label class="gilroyregular-font" for="no_vat_registered"><span class="radio-circle"><span></span></span>Nope, we are not.</label>
                                    </div>
                                </div>
                                    <div class="input-bx margin-bottom-40 padding-bottom">
                                <input tabindex="11" type="submit" class="info-save-btn @if(!isset($business_information->businessDetails->vat_status)){{'disable-btn'}}@else
                                       {{'standard-btn'}}@endif full-width-btn" 
                                       value="Continue & View Your Messages With {{ucfirst($user_name)}}" 
                                       id="submit_business_information_on_start_conversation">
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
<script src="{{ url('js/vat_details_pop_up.js?js='.$random_number,[],$ssl) }}"></script>