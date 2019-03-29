@extends('layouts.layout')
@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 expert-find-project vat-block">
    <div class="send-proposal-wrap">
        <div class="send-proposal-header padding-20">
            <h4>Prepare & Send Your Proposal</h4>
            <div class="breadcrumbs-wrap">
                <a href="{{route('expertMessage').'?communication_id='.$communication_id}}">Messages with {{$buyer_name}} from {{getCompanyFirstName($company_name)}}</a>
                <img src="{{url('images/chevron-right.svg',[],$ssl) }}" alt="logo icon" />
                <span>Prepare & Send Your Proposal</span>
            </div>
        </div>
        <div class="send-proposal-section">
            <div class="col-md-12 info-right-side">
                <div class="send-proposal-content">
                    <h5>Before you start your proposal…</h5>
                    <p>In order to calculate any <a target="_blank" href="https://en.wikipedia.org/wiki/Value-added_tax">VAT/GST</a> applicable to your proposal fees, we need to know some of your business details.</p>
                </div>
                <form  method="post" action="{{url('user/store-vat-detais/'.Auth::user()->id )}}">
                    {{csrf_field()}}
                    <input type="hidden" id='business_type' name='business_type' value="{{ isset($business_information->type) ? $business_information->type : '1' }}">
                    <input type="hidden" id='communication_id' name='communication_id' value="{{ isset($communication_id) ? $communication_id: null }}">
                    <input type="hidden" id='country_search_source' value="{{$countries}}">
                    <div class="business-type-wrap">
                        <label>Please confirm your business type:</label>
                        <div class="registered-company business-type-block tab-active">
                            <div class="business-type-image"></div>
                            <p>Registered Company</p>
                        </div>
                        <div class="sole-trader business-type-block">
                            <div class="business-type-image"></div>
                            <p>Sole Trader / Sole Proprietor</p>
                        </div>
                    </div>
                    <div id="registered_company_section" class="business-address-details ">
                        <div class="input-bx">
                            <label>Which country is your company registered in?</label>
                            <input id="company_country" name="company_country" placeholder="e.g. United Kingdom" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password" value="@if(isset($business_information->businessDetails->company_country)){{$business_information->businessDetails->company_country}}@endif">
                            <input id="vat_country" name="vat_country" type="hidden" value="@if(isset($business_information->businessDetails->vat_country)){{$business_information->businessDetails->vat_country}}@endif">
                        </div>
                    <div id="vat_block" class="input-bx hide">
                        <label>Is your company VAT registered?</label>
                        <p>VAT stands for “Value Added Tax”. In some markets, it’s also known as “GST” or “IVA”.</p>
                        <div class="radio-button-style">
                            <input id="vat_registered" type="radio" name="vat_registered"  value="on" @if(isset($business_information->businessDetails->vat_status) && $business_information->businessDetails->vat_status) checked="true" @endif>
                            <label for="vat_registered"><span class="radio-circle"><span></span></span>Yes, we are a VAT registered company</label>
                        </div>
                        <div class="radio-button-style">
                            <input id="no_vat_registered" type="radio" name="vat_registered" value="off" @if(isset($business_information->businessDetails->vat_status) && !$business_information->businessDetails->vat_status) checked="true" @endif>
                            <label for="no_vat_registered"><span class="radio-circle"><span></span></span>Nope, we are not.</label>
                        </div>
                    </div>
                </div>
                <div id="sole_trader_section" class="business-address-details hide ">
                    <div class="input-bx">
                        <label>Which country are you a registered sole trader in?</label>
                        <input id="sole_trader_country" name="sole_trader_country" placeholder="e.g. United Kingdom" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="new-password">
                    </div>
                </div>
                <div class="input-bx">
                    <input tabindex="11" type="submit" class=" margin-top-20 info-save-btn disable-btn" value="Continue & Create Your Proposal" id="submit_business_information">
                </div>
        </form>
    </div>
</div>
<div>
</div>
@endsection
@section('scripts')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/business_information.js?js='.$random_number,[],$ssl) }}"></script>
@endsection