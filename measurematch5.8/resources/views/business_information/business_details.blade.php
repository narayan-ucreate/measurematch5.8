<form name="business_information" id="business_information" method="post" action="">
    @if(Auth::user()->user_type_id == config('constants.EXPERT'))
    <div class="business-type-wrap">
        <label>Please confirm your business type:</label>
        <div class="registered-company business-type-block @if(isset($business_type) && $business_type == config('constants.REGISTERD_COMPANY') || empty($business_type)) tab-active @endif">
            <div class="business-type-image"></div>
            <p>Registered Company</p>
        </div>
        <div class="sole-trader business-type-block expert-settings @if(isset($business_type) && $business_type == config('constants.SOLE_TRADER')) tab-active @endif">
            <div class="business-type-image"></div>
            <p>Sole Trader / Sole Proprietor</p>
            <input type="hidden" id='business_type' name='business_type' value="{{ $business_type ?? config('constants.REGISTERD_COMPANY') }}">
        </div>
    </div>
    @else
    <input type="hidden" id='business_type' name='business_type' value="{{ config('constants.REGISTERD_COMPANY') }}">
    @endif
    <div id="business_details" class="business-address-details registered-company-section @if (isset($business_type) && $business_type == config('constants.SOLE_TRADER' ))hide @endif">
        <div class="input-bx">
            <label class="font-18">Business Details</label>
            <div class="business-details-description">
                <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content='We need your business details so we can perform the necessary "Know Your Customer" KYC compliance checks.'>
                    Learn why we need to know your business details
                </a>
            </div>
        </div>
        <div class="input-bx">
            <label>What is your company’s registered name?</label>
            <input tabindex="6" maxlength="40" size="30" placeholder="e.g. MeasureMatch Ltd." type="text" id="company_name" name="company_name" autocomplete="new-password" value="{{$company_name}}">
        </div>
        <div class="input-bx">
            <label>What is your company’s website address?</label>
            <input tabindex="6" maxlength="40" size="30" placeholder="https://" type="text" id="company_website" name="company_website" autocomplete="new-password" value="{{$company_website}}">
        </div>
        <div class="input-bx">
            <label>What is your role in the company?</label>
            <select id="company_role" name="company_role" tabindex="12" class="selectpicker select-box-dropdown @if(isset($business_details->role)) value-already-filled @endif)">
                <option value="">Choose</option>
                <option value="Director" @if(isset($business_details->role) && $business_details->role == 'Director') selected @endif>Director</option>
                <option value="Owner" @if(isset($business_details->role) && $business_details->role == 'Owner') selected @endif>Owner</option>
                <option value="Other" @if( isset($business_details->role) && $business_details->role == 'Other') selected @endif>Other</option>
            </select>
        </div>
        <div class="input-bx">
            <label>Which country is your company registered in?</label>
            <div class="county-name">
                <div class="country-drop new-custom-dropdown-style">
                    <input id="company_country" name="company_country" placeholder="e.g. United Kingdom" value="@if(isset($business_details->company_country)){{$business_details->company_country}}@endif" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
                    <input id="vat_country" name="vat_country" value="@if(isset($business_details->vat_country)){{$business_details->vat_country}}@endif" type="hidden">
                    <input id="is_eu" name="is_eu" value="" type="hidden">
                </div>
            </div>
        </div>
    </div>
    <div id="vat_detail" class="vat-detail-block business-address-details registered-company-section 
         @if(isset($business_type)
         && ($business_type == config('constants.SOLE_TRADER' ))
         || (!isset($business_details))
         || (!isset($business_details['vat_country']))
         || (isset($business_details['vat_country'])
         && getCountryVatDetails($business_details['vat_country'])['vat'] == 0)
         || (!isset($business_type))
         ) hide 
         @endif">
        <div class="input-bx">
            <label class="font-18">VAT Status</label>
            <div class="business-details-description">
                <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content="Your VAT status is required in order for us to calculate the correct VAT due for your services. The VAT applicable to your fees is for you to process accordingly.">
                    Learn why we need to know your VAT details
                </a>
            </div>
        </div>
        @if($countries)
        <div id="vat_block" class="input-bx">
            <label>Please confirm your VAT status:</label>
            <p class="vat-stand-text">VAT stands for “Value Added Tax”. In some markets, it’s also known as “GST” or “IVA”.</p>
            <div class="radio-button-style">
                <input id="vat_registered" type="radio" @if(isset($business_details->vat_status) && $business_details->vat_status) checked="true" @endif name="vat_status"  value="on" >
                <label class="vat-radio-lable" for="vat_registered"><span class="radio-circle"><span></span></span>Yes, we are a VAT registered company</label>
                <div class="vat-country-input">
                    <input tabindex="4" maxlength="25" size="30" placeholder="Add your VAT number here…" type="text" name="vat_number"
                           value="{{ isset($business_details->vat_country) ? $business_details->vat_number : '' }}" id="vat_number" />
                    <div class="country-code vat-country-code">{{ ($business_details->vat_country) ?? '' }}</div>
                </div>
            </div>
            <div class="radio-button-style">
                <input id="no_vat_registered"  type="radio" name="vat_status" value="off" @if(isset($business_details->vat_status) && !$business_details->vat_status) checked="true" @endif>
                <label class="vat-radio-lable" for="no_vat_registered"><span class="radio-circle"><span></span></span>Nope, we are not VAT registered</label>
            </div>
        </div>
        @endif
    </div>
    <div id="business_address" class="business-address-details">
        <div class='sole-block sole-trader-block'>
            <div class="input-bx">
                <label class="font-18">Sole Trader Details</label>
            </div>
            <div class="input-bx">
                <label>Which country are you a registered sole trader in?</label>
                <div class="county-name">
                    <div class="country-drop">
                        <input id="business_registered_country"  name="business_registered_country" placeholder="e.g. United Kingdom" value="@if(isset($business_address->business_registered_country)){{$business_address->business_registered_country}}@endif" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id='business_type_in_database' value="@if(!empty($business_type)){{$business_type}}@else{{''}}@endif">
        <input type="hidden" id='country_search_source' value="{{$countries}}">
        <div class="input-bx">
            <label>Your Business Address</label>
            <div class="business-details-description">
                <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content='Your business address is required on the invoice that we issue on your behalf.'>Learn why we need to know your business address</a>
            </div>
        </div>
        <div class="input-bx">
            <label>Address Line 1</label>
            <input tabindex="6" maxlength="40" size="30" type="text" id="first_address" name="first_address"
                   value="{{ isset($business_address->first_address) ? $business_address->first_address : '' }}" autocomplete="new-password">
        </div>
        <div class="input-bx">
            <label>Address Line 2</label>
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
            <div class="country-drop new-custom-dropdown-style">
                <input id="business_country"  name="business_country" placeholder="e.g. United Kingdom" value="@if(isset($business_address->country)){{$business_address->country}}@endif" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
            </div>
        </div>
        <div class="input-bx"> 
            @if(Route::current()->getName() == 'send-proposal')
            <input id="submit_business_info" tabindex="11" type="submit" class="info-save-btn standard-btn proposal-btn full-width-btn" value="Save & continue to final step"> 
            @else
            <input id="submit_business_info" tabindex="11" type="submit" class="info-save-btn standard-btn " value="Save & Update" >
            @endif      
        </div>
    </div>
</form>