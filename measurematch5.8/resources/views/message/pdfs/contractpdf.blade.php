@extends('layouts.contractpdflayout')
@section('content')
<div class="container-pdf">
    <div class="pdf-header-section">
        <table border="0" width="100%"><tr><td width="50%"><div class="topleft-section pull-left">
            <a href="{{url('/',[],$ssl)}}" class="pdf-logo"><img src="{{url('images/logo.svg',[],$ssl)}}" width="300" /></a>
            <span class="company-name font-20">www.MeasureMatch.com</span>
        </div>
</td><td width="50%">
        <div class="topright-section pull-left">
 <table border="0" width="100%"><tr><td><span class="address-of-org font-18"><strong class="gilroyregular-bold-font">Address: </strong>{{config('constants.COMPANY_ADDRESS')}}</span>
</td></tr><tr><td>            <span class="phone-of-org font-18"><strong class="gilroyregular-bold-font">Tel: </strong>{{config('constants.COMPANY_PHONE')}}</span>
            </td></tr><tr><td><span class="email-org font-18"><strong class="gilroyregular-bold-font">Email:</strong> {{config('constants.COMPANY_EMAIL')}}</span>
            </td></tr><tr><td><span class="registered-org font-18">Registered in {!!config('constants.REGISTERED_IN')!!}</span></td></tr></table>
        </div></td></tr></table>
    </div>
<br />
    <div class="pdf-content-section">
        <h4 class="gilroyregular-bold-font font-18">{{$project_label}}:</h4>
        <h2 class="gilroyregular-bold-font font-28">{{$project_name}}</h2>

        <div class="full-width row">
            <table border="0" width="100%"><tr><td colspan="2"><br /></td></tr><tr><td><div class="col-lg-6 col-md-6 buyersection-info">
                <h5 class="gilroyregular-bold-font font-18">Client:</h5>
                <p class="font-20">{{$contract['buyer']['company_name']}}, {{$buyer_name}}<br /> {{$buyer_email}}</p>
            </div>
 </td><td>
            <div class="col-lg-6 col-md-6 buyersection-info">
                <h5 class="gilroyregular-bold-font font-18">Expert:</h5>
                <p class="font-20">{{$expert_name}}<br /> {{$expert_email}}</p>
            </div>           </td></tr> </table>
        </div>

        <div class="deliverables-section">
            <h5 class="gilroyregular-bold-font font-18">Deliverables:</h5>
            <p class="font-20">
            @if(_count($contract['contract_deliverables']))
            <ul class="deliverables-points" style="margin-left:20px;">
                @foreach($contract['contract_deliverables'] as $deliverable)
                <li class="font-18">{{ucfirst($deliverable['deliverable'])}}</li>
                @endforeach
            </ul>
            @else
            {{ucfirst($contract['project_deliverables'])}}
            @endif
            </p>
        </div>

        <div class="attachment-section margin-bottom-10">
            <h5 class="gilroyregular-bold-font font-18">Attachment(s):</h5>
            @if(!empty($contract['upload_document']))
            <a href="{{$contract['upload_document']}}" class="pdf-file-link font-20" title="{{last(explode('/', $contract['upload_document']))}}" target="_blank">{{last(explode('/', $contract['upload_document']))}}</a>
            @else
            <span class="font-20">N/A</span>
            @endif
        </div>

        <div class="full-width">
            <table border="0" width="100%"><tr><td width="25%" valign="top"><div class="contract-agreed">
                <h5 class="gilroyregular-bold-font font-18">@if(!empty($contract['accepted_by_expert_on']))Contract agreed:@else Contract created: @endif</h5>
                @if(!empty($contract['accepted_by_expert_on']))
                    @php $contract_agree_date = $contract['accepted_by_expert_on']; @endphp
                @else
                    @php $contract_agree_date = $contract['created_at']; @endphp
                @endif
                <p class="font-20">{{date('d M Y', strtotime($contract_agree_date))}}</p>
            </div>
 </td><td width="25%" valign="top">
            <div class="contract-start">
                <h5 class="gilroyregular-bold-font font-18">Contract start:</h5>
                <p class="font-20">{{date('d M Y', strtotime($contract['job_start_date']))}}</p>
            </div>
</td><td width="25%" valign="top">
            <div class="contract-end">
                <h5 class="gilroyregular-bold-font font-18">Contract end:</h5>
                <p class="font-20">{{date('d M Y', strtotime($contract['job_end_date']))}}</p>
            </div>
</td><td width="25%" valign="top">
            <div class="contract-id">
                <h5 class="gilroyregular-bold-font font-18">Contract ID:</h5>
                <p class="font-20">{{$contract['unique_id']}}</p>
            </div>                       </td></tr></table>
        </div>

        <div class="contract-value-section">
            <table border="0" width="100%"><tr><td width="50%" valign="top"> <div class="col-lg-12 col-md-12 contract-value-block">
                <h4 class="gilroyregular-bold-font font-18">Contract Value:</h4>
                <div class="value-section">
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td align="left" width="60%" class="pdf-project-value" valign="top">
                                <h4 class="font-20 gilroyregular-font">Project Value</h4>
                                <p class="font-18 pdf-grey-text">Expert Take (85%)</p>
                                <p class="font-18 pdf-grey-text">MeasureMatch Take (15%)</p>
                            </td>

                            <td align="left" width="40%" class="contract-rate-pdf" valign="top">
                                <h4 class="font-20 gilroyregular-font">{{$contract_rate}}</h4>
                                <p class="font-18 pdf-grey-text">{{$amount_to_be_paid_to_expert}}</p>
                                <p class="font-18 pdf-grey-text">{{$mm_fee}}</p>
                            </td>
                        </tr>
                        @if($vat_amount)
                        <tr>
                            <td valign="top" colspan="2"><table width="100%" class="pdf-top-boarder"><tr><td align="left" width="50%" valign="top">
                                <h4 class="font-20 gilroyregular-font margin-0">VAT ({{config('constants.VAT')}}%)</h4>
                            </td>
                            <td valign="top" align="left" width="50%" style="text-align:right; ">
                                <h4 class="font-20 gilroyregular-font margin-0 pull-right">{{$vat_amount}}</h4>
                            </td></tr></table></td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2"><table class="pdf-top-boarder" width="100%"><tr><td align="left" width="50%">
                                <strong class="font-20 gilroyregular-bold-font">Total</strong>
                            </td>
                            <td align="right" width="50%">
                                <strong class="font-20 gilroyregular-bold-font">{{$total}}</strong>
                            </td></tr></table></td>
                        </tr>
                    </table>
                </div>

                @if($vat_amount)
                <p class="font-18">The total contract value of {{$contract['rate_variable']}}{{number_format($contract['rate'], 2)}} was converted
                    at a rate of {{number_format($contract['rate']/($contract['rate_in_gbp']/100), 2)}} from USD ($) to GBP (£) on the day
                    the contract was agreed ({{date('d M Y', strtotime($contract_agree_date))}}) using <a href="https://fixer.io/" target="_blank">fixer.io</a>.
                </p>
                @endif
            </div> </td> <td width="50%" valign="top"> <div class="col-lg-12 col-md-12 mm-payment-details">
                <h4 class="font-18 gilroyregular-bold-font">MeasureMatch Payment Details:</h4>
                <h4 class="font-18 gilroyregular-bold-font margin-bottom-0">Bank Name & Branch Address:</h4>
                <p class="font-18">{!! config('constants.BANK_DETAILS') !!}</p>

                <h4 class="font-18 gilroyregular-bold-font margin-bottom-0">Account name:</h4>
                <p class="font-18">{{config('constants.ACCOUNT_NAME')}}</p>

                <h4 class="font-18 gilroyregular-bold-font margin-bottom-0">Account Address: </h4>
                <p class="font-18">{{config('constants.ACCOUNT_ADDRESS')}}</p>

                <table border="0" width="100%">
                    <tr>
                        <td class="font-18 gilroyregular-bold-font">Sort Code: <br /><span class="gilroyregular-font">{{config('constants.SORT_CODE')}}</span></td>
                        <td class="font-18 gilroyregular-bold-font">Account Number: <br /> <span class="gilroyregular-font">{{config('constants.ACCOUNT_NUMBER')}}</span></td>
                    </tr><tr><td colspan="2"><br /></td></tr>
                </table>

                <h4 class="font-18 gilroyregular-bold-font margin-bottom-0">IBAN: </h4>
                <p class="font-18">{{config('constants.IBAN')}}</p>

                <h4 class="font-18 gilroyregular-bold-font margin-bottom-0">SWIFT: </h4>
                <p class="font-18">{{config('constants.SWIFT')}}</p>
            </div></td></tr></table>
        </div>

        <h4 class="font-20 gilroyregular-bold-font">MeasureMatch Terms of Service</h4>
        <p class="font-20 margin-bottom-20">By signing up to MeasureMatch, you agreed to our <a href="{{getTermConditionsLink()}}" title="Terms of Service" target="_blank">Terms of Service</a>.</p>
    </div>
</div>
@endsection
