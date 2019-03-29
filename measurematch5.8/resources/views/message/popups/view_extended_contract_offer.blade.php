<div class="modal-dialog billing-detail-pending">
    <div class="modal-innner-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross">
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel-group contract-extend-panel" id="view_extended_contract_accordion">
                    @php $count=0; @endphp
                    @foreach($all_contracts as $contract)
                    @php
                    $count++;
                    if($count==1){
                    $parent_contract_id = $contract['id'];
                    }
                    $payment_calculation = contractPaymentCalculationWithoutCoupon($contract['rate']);
                    $expert_share = convertToCurrencySymbol($contract['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2);
                    $mm_fee = convertToCurrencySymbol($contract['rate_variable']).number_format($payment_calculation['mm_fee'], 2);
                    $extension_contract_minimum_year = date('Y', strtotime($contract['job_end_date']. ' +1 day'));
                    $extension_contract_minimum_month = date('m', strtotime($contract['job_end_date']. ' +1 day'));
                    $extension_contract_minimum_date = date('d', strtotime($contract['job_end_date']. ' +1 day'));
                    @endphp
                    <div class="panel panel-default contract-panel">
                        <div class="panel-heading">
                            <h4>
                                <a
                                    class="accordion-toggle gilroyregular-bold-font font-16 primary-text-color"
                                    data-toggle="collapse"
                                    data-parent="#view_extended_contract_accordion"
                                    @if($count === _count($all_contracts)) style="pointer-events: none" @endif
                                    href="#view_extended_contract_{{$contract['id']}}">{{$contract['alias_name']}}
                                    <span class="pull-right font-14">Expand details</span>
                                </a>
                            </h4>
                        </div>

                        <div id="view_extended_contract_{{$contract['id']}}" class="panel-collapse collapse @if($count === _count($all_contracts)) in @endif contract-panel">
                            <div class="panel-body">
                                <div class="input-bx select-box add-time-period">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 contract-startdate-panel text-align-left margin-bottom-10">
                                            <label>Contract start date</label>
                                            <span class="font-14">{!! date('d M Y',strtotime($contract['job_start_date'])) !!}</span>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 contract-enddate-panel text-align-left margin-bottom-10">
                                            <label>Contract end date</label>
                                            <span class="font-14">{!! date('d M Y',strtotime($contract['job_end_date'])) !!}</span>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 contract-value-panel text-align-left margin-bottom-10">
                                            <label>Contract value</label>
                                            <span
                                                class="font-14">{{convertToCurrencySymbol($contract['rate_variable']).
                                                            number_format($contract['rate'], 2).
                                                            ' ('.$expert_share.' to '.$expert_name.
                                                            ', '.$mm_fee.' to Measurematch)'}}
                                            </span>
                                        </div>
                                        <div class="col-lg-12 col-md-12 deliverables-panel text-align-left margin-bottom-10">
                                            <label>Deliverables</label>
                                            @if($contract['type']=='project')
                                            <span class="font-14">{!! $contract['project_deliverables'] !!}</span> 
                                            @else
                                            <div class="deliverable-panel job-post-deliverable">
                                                @php $counter = 1; @endphp
                                                <ul class="list-group contact-deliverable-list">
                                                    @foreach ($contract['contractDeliverables'] as $deliverable)
                                                    <li class="font-14">{!!$deliverable->deliverable !!}</li> 
                                                    @endforeach
                                                    </li> 
                                            </div>
                                            @endif
                                        </div>
                                        @if(isset($contract['upload_document']) && !empty(trim($contract['upload_document'])))
                                        <div class="col-lg-12 col-md-12   supporting_document">
                                            <label>Supporting agreement/document-optional</label>
                                            <span id="preview_attachment" class="no_attachment_block font-14"></span>                                                
                                            @php
                                            $imageExplode = explode('/', $contract['upload_document']);
                                            $final_img = explode('_', end($imageExplode));
                                            unset($imgexpl);
                                            foreach ($final_img as $key => $img) {
                                            if ($key != 0) {
                                            $imgexpl[] = $img;
                                            }
                                            }
                                            $img_names = implode($imgexpl);
                                            @endphp
                                            <a
                                                class="attached-files-link link-color"
                                                target="_blank"
                                                title="Attach file(s)"
                                                href="@if(isset($contract['upload_document']) && !empty($contract['upload_document'])) 
                                               {{$contract['upload_document'] }} @else {{ 'javascript:void(0)' }} @endif">
                                                {{ $img_names }}
                                            </a>
                                        </div>
                                        @endif
                                        @if($count != _count($all_contracts) && (null !== app('request')->input('source')) && (app('request')->input('source')=='messages'))
                                        <div class="col-lg-12 text-center">
                                            <a
                                                title="Download Contract"
                                                class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 message-download-contract-btn white-btn-middle contract_extensions"
                                                href="{{ url("contract/".$contract['id']."/download",[],$ssl) }}"
                                                target="_blank">Download Contract
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    @if($count === _count($all_contracts))
                                        @if($contract['status']==0)
                                        <a
                                            data-toggle="modal"
                                            id='edit_contract'
                                            data-dismiss="modal"
                                            onclick="viewEditExtendedOffer()"
                                            class="jobpost-confirm-button standard-btn btn-standard-middle clearfix"
                                            href="javascript:void(0)">Edit contract
                                        </a>
                                        @else
                                            @if(null !== app('request')->input('source') && app('request')->input('source')=='messages')
                                            <a
                                                title="Download Contract"
                                                class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 message-download-contract-btn white-btn-middle contract_extensions"
                                                href="{{ url("contract/".$contract['id']."/download",[],$ssl) }}"
                                                target="_blank">Download Contract
                                            </a>
                                        @endif
                                    @endif
                                </div>  
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if(null !== app('request')->input('source') && app('request')->input('source')=='messages' && acceptedContractsCount($all_contracts[0]['id'])>1)
            <div class="col-lg-12  text-center margin-bottom-30">
                <a title="Download All Contracts" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 message-download-contract-btn white-btn-middle all-contracts-buyer" href="javascript:void(0)">Download All Contracts</a>
            </div>
            @endif
        </div>
    </div>
</div>