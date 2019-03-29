@php
$total_sub = 0;
$total_vat = 0;
$currency = $project_type == config('constants.PROJECT') ? convertToCurrencySymbol($project_info->currency) : '$';
$currency_name = $project_type == config('constants.PROJECT') ? $project_info->currency : 'USD';
$buyer_company_name = $buyer_information->buyer->company_name;
@endphp
<div id="all_deliverable_list">
    @if (isset($deliverables['deliverables']) && _count($deliverables['deliverables']))
        <div class="project-deliverable-block">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-hdrow">
                <div class="table-cell"><h4>Deliverable</h4></div>
                <div class="table-cell"><h4>Unit</h4></div>
                <div class="table-cell"><h4>Quantity</h4></div>
                <div class="table-cell"><h4>Price</h4></div>
                <div class="table-cell"><h4>Amount</h4></div>
            </div>
            @forelse($deliverables['deliverables'] as $key => $deliverable)
                @php
                $deliverable['price'] = (isset($deliverables['sub_total'])) ? ($deliverable['price'])/100 : $deliverable['price'];
                $deliverable['subtotal'] = (isset($deliverables['sub_total'])) ? $deliverables['sub_total']/100 : $deliverable['subtotal'];
                    $total_sub += $deliverable['price'] * $deliverable['quantity'];
                    $unit_identifier = '';
                    if ($deliverable['rate_type'] == 2) {
                        $unit_identifier = '<span class="font-12">/day</span>';
                    } elseif ($deliverable['rate_type'] == 3) {
                        $unit_identifier = '<span class="font-12">/hour</span>';
                    }
                @endphp
                <div class="deliverable-content deliverable-blocks">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-row">
                        <div class="table-cell">
                            <h4>{{$deliverable['title']}}</h4>
                            <p class="white-space-pre-wrap">{{$deliverable['description']}}</p>
                        </div>
                        <div class="table-cell">{{explode(' ', config('constants.RATE_TYPE.'.$deliverable['rate_type']))[0]}}</div>
                        <div class="table-cell">{{$deliverable['quantity']}}</div>
                        <div class="table-cell">{{number_format($deliverable['price'], 2)}}{!! $unit_identifier !!}
                        </div>
                        <div class="table-cell">{{$currency}}{{number_format($deliverable['price']*$deliverable['quantity'], 2)}}</div>
                    </div>
                    @if(!isset($deliverables['expert']))
                    <div class="deliverables-edit-button-wrap">


                        <button type="button" class="white-button edit-deliverable edit-proposal-fields"
                               data-id="{{$key}}"
                               data-title="{{$deliverable['title']}}"
                               data-quantity="{{$deliverable['quantity']}}"
                               data-price="{{$deliverable['price']}}"
                               data-description="{{$deliverable['description']}}"
                               data-rate_type="{{$deliverable['rate_type']}}"
                               data-redirect-url="{{route('send-proposal', [$communication_id, 1])}}?deliverable={{$key}}"
                                 id="edit_deliverable"><i class="fa fa-pencil"></i> Edit deliverable</button>
                        @if (isset($step) && $step == 1)
                        <button type="button" class="white-button delete-proposal-field delete-deliverable" data-id="{{$key}}"
                                id="delete_deliverable"><i class="fa fa-trash"></i> Delete deliverable</button>
                        @endif
                    </div>
                    @endif
                </div>
            @empty
            @endforelse
        </div>
    @endif
    @php
        if(!isset($deliverables['expert']['business_information']['business_details']))
        {
            $expert_information = getExpertInformation($expert_id);
            $buyer_vat_status = (empty($buyer_information->vat_country_code)) ? FALSE : TRUE;
            $buyer_vat_information = getExpertInformation($buyer_information->buyer_id);
            $vat_details = calculateContractVATValues($total_sub,
               $expert_information->businessDetails->vat_status ?? '',
               $expert_information->businessDetails->vat_country ?? '',
               $buyer_vat_information->businessDetails->vat_status ?? '',
               $buyer_vat_information->businessDetails->vat_country ?? '');
        }

           $sub_total = ($vat_details['subtotal']) ?? $deliverables['sub_total']/100;
           $vat_percentage = (isset($vat_details['vat']) && ($vat_details['vat'] > 0)) ? $vat_details['vat'] : ($deliverables['vat']) ?? 0;
           $vat_value = ($vat_details['vat_value']) ?? ($deliverables['vat_value'])/100;
           $total_buyer_will_pay = ($vat_details['total_buyer_will_pay']) ?? $deliverables['rate'];
           $mm_fee = ($vat_details['mm_fee']) ?? $deliverables['mm_fee']/100;
           $mm_fee_vat = ($vat_details['mm_fee_vat']) ?? ((isset($deliverables['mm_fee_vat'])) ? $deliverables['mm_fee_vat']/100 : 0);
           $expert_amount = ($vat_details['total_expert_will_receive']) ?? ($deliverables['expert_amount'])/100;
           $reverse_charge_invoice = ($vat_details['reverse_charge_invoice']) ?? ($deliverables['reverse_charge_invoice']);
           $reverse_charge_mm_fee = ($vat_details['reverse_charge_mm_fee']) ?? ($deliverables['reverse_charge_mm_fee']);
    @endphp
</div>
<div class="project-deliverable-edit-wrap">
    <div class="create-deliverable-section
    @if (isset($deliverables['deliverables']) && _count($deliverables['deliverables']))
            hide
    @endif
            ">
        @if  (isset($step) && $step == 1)
        @include('proposal.create_deliverable_form')
        @endif
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-another-deliverable
    @if (!isset($deliverables['deliverables']) || !_count($deliverables['deliverables']))
      hide
    @endif
            ">
        @if  (isset($step) && $step == 1)
        <div class="button-wrap">
            <input tabindex="11" type="button" class="info-save-btn standard-btn" value="Add another deliverable" id="add_another_deliverable">
        </div>
            @endif
    </div>
</div>
@if (isset($deliverables['deliverables']) && _count($deliverables['deliverables']))
    <div class="sub-vat-total">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block">
                <div class="col">
                    <h5 class="sub-total-h5">Subtotal</h5>
                </div>
                <div class="col text-right">
                    <input id="subtotal_value" type="hidden" value="{{ $sub_total }}">
                    <input id="subtotal_currency" type="hidden" value="{{ $currency }}">
                    <p>{{$currency}}{{number_format($sub_total, 2)}}</p>
                </div>
            </div>
            <div class="sub-total-block">
                <div class="col">
                    <h5>VAT ({{($vat_value!=0 ? $vat_percentage : '0') }}%)</h5>
                    @if($reverse_charge_invoice)
                    <div class="font-12 service-subject">Services subject to <a target="_blank" href="https://www.avalara.com/vatlive/en/eu-vat-rules/eu-vat-returns/reverse-charge-on-eu-vat.html">Reverse Charge</a></div>
                    @endif
                </div>
                <div class="col text-right">
                    <p>{{$currency}}{{number_format($vat_value, 2)}}</p>
                </div>
            </div>
        </div>
    </div>
    @if(!isset($is_pop_up))
    <div class="totalpayment-block">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block">
            <div class="col">
                <h5>Total {{ $buyer_company_name }} will pay</h5>
            </div>
            <div class="col text-right">
                <p>{{$currency}}{{number_format($total_buyer_will_pay, 2)}}</p>
            </div>
            </div>
        </div>
    </div>
    @endif
    <div class="totalpayment-block">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block border-top padding-bottom">
                <div class="col">
                    <h5 class="blue-text">MeasureMatch Fee <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content="MeasureMatch charges a 15% fee from the total contract value.">
                      <i class="fa fa-question-circle"></i></a>
                    </h5>
                </div>
                <div class="col text-right">
                    <p class="blue-text">-{{$currency}}{{number_format($mm_fee, 2)}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="totalpayment-block">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block padding-top">
                <div class="col">
                    <h5 class="blue-text">MeasureMatch Fee VAT ({{$mm_fee_vat > 0 ? config('constants.VAT') : '0'}}%)</h5>
                    @if($reverse_charge_mm_fee)
                        <div class="font-12 service-subject">Services subject to <a target="_blank" href="https://www.avalara.com/vatlive/en/eu-vat-rules/eu-vat-returns/reverse-charge-on-eu-vat.html">Reverse Charge</a></div>
                    @endif
                </div>
                <div class="col text-right">
                    <p class="blue-text">-{{$currency}}{{number_format($mm_fee_vat, 2)}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="totalpayment-block">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block border-top border-bottom">
                <div class="col">
                    <h5 class="
                        @if(!isset($is_pop_up))
                        font-20
                        @endif
                        ">Total @if(!isset($is_pop_up)){{'you'}}@else{{ucfirst($expert_name)}}@endif will receive</h5>
                </div>
                <div class="col text-right">
                    <p class="
                       @if(!isset($is_pop_up))
                        font-20
                        @endif
                        ">{{$currency}}{{number_format($expert_amount, 2)}}</p>
                </div>
            </div>
        </div>
    </div>
    @if(isset($is_pop_up))
    <div class="totalpayment-block">
        <div class="col-md-6 col-md-offset-6">
            <div class="sub-total-block">
            <div class="col">
                <h5
                    class="
                    @if(isset($is_pop_up))
                        font-20
                        @endif"
                    >Total cost to {{ $buyer_company_name }}</h5>
            </div>
            <div class="col text-right">
                <p>{{$currency}}{{number_format($total_buyer_will_pay, 2)}}</p>
            </div>
            </div>
        </div>
    </div>
    @endif
 @endif
