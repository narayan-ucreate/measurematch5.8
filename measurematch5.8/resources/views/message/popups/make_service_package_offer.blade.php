@if(isset($project_detail))
<div class="modal invite-seller-popup perposalview-offer send_contract_popup new-theme-modal lightbox-design fade in" id="send_contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog modal-lg billing-detail-pending">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-body">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross">
                        </span>
                    </button>
                    <div id="contract-detail">
                          <h3>Send a Proposal to {{$buyer_company}}</h3>
                           <p class="font-16">In order to lock in an agreement with a MeasureMatch client,
                               you need to send a proposal by completing the form below.</p>
                        
                        <form name="service-package-contract" id="service-package-contract" method="post" enctype="multipart/form-data">
                              {{ csrf_field() }}
                            <div class="send_contract_form">
                                
                                 <div class="input-bx select-box add-time-period">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>When can you start working on this package?</label>
                                            <div class="select-box ">
                                                <input name="start_time" id="start_time" value="{{date('d-m-Y')}}" placeholder="From" type="text">
                                                 <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                            </div>

                                            <div id="start_time_error" class=" error-message"></div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="input-bx select-box add-time-period">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>When do you estimate you’ll finish the package? </label>
                                            <div id="contract_end_time" class="select-box date-contract-end">
                                                @if($project_detail['subscription_type']=="monthly_retainer")
                                                <span class="monthly-retainer-lbl">Monthly Retainer (cancel anytime)</span>
                                                <p>Based on a contract start date of <span class="monthly-start-date">{{date('d-m-Y')}}</span>, you will be billed on the <span class="monthly-billing-day">{{ date('dS', strtotime("+30 days"))}}</span> of every month, starting on <span class="monthly-end-date">{{ date('d-m-Y', strtotime("+30 days"))}}</span>. You can finish or cancel this retainer at any time.</p>
                                                @else
                                                <input name="end_time" id="end_time" value="" placeholder="To" type="text">
                                                 <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                                @endif
                                            </div>
                                            <div id="end_time_error" class="error-message"></div>
                                            <span id="someplace"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($project_detail['subscription_type']=="monthly_retainer")
                                <div class="input-bx maxiimum-time select-dropdown-style monthly-tooltip-design custom-dropdown-style">
                                    <label id="commitment_duration">Expected monthly time commitment  </label>
                                    <div class="clearfix"></div>
                                    <select class="selectpicker" name="monthly_days_commitment" id="monthly_days_commitment">
                                        <option value="">Choose</option>
                                        @for($date=1; $date<=30;$date++)
                                        @php $days=($date==1)?'day /month':'days /month'; @endphp
                                        <option  title="{{$date.' '.$days}}" data-content="{{$date.' '.$days}}" @if($project_detail['duration'] == $date) selected='selected' @endif value="{{$date}}">{{$date.' '.$days}}</option>
                                        @endfor
                                    </select>
                                    <div id="monthly_days_commitment_error" class=" error-message"></div>

                                </div>
                                @endif
                                <div class="input-bx select-box">
                                    <label>
                                         <label>How much will you charge for this package?</label>
                                          <p>Please remember that MeasureMatch will take a 15% fee from your proposal value (it’s not added on top).</p>
                                    <div class="rates_select-box form-group static-select-bx">
                                        <input name="rate_variable" value="USD" id="rate_variable" type="hidden">
                                        <div id="contract_rate_variable_error" class=" error-message"></div>
                                    </div>
                                    <div class="form-group input-group priceinput">
                                        <div class="job-details-lbl">
                                        <div class="input-group-addon">USD $</div>
                                        @php if($project_detail['subscription_type']=="monthly_retainer"){ $price_format= "/month"; }else{$price_format= "";} @endphp
                                        <input maxlength="13" name="rate" class="@if($project_detail['subscription_type'] !='monthly_retainer') price-format-validation @endif" value="{{number_format($project_detail['price'])}}{{$price_format}}" id="price" type="text">
                                        </div>
                                    </div>
                                    <div id="contract_price_error" class=" error-message"></div>
                                </div>

                                <div class="input-bx-panel deliverable-panel bottom-margin-0">
                                    <label>Deliverables for the package </label>
                                        @php $counter = 1; @endphp
                                        @foreach ($project_detail['deliverables'] as $deliverables)
                                        <textarea name="deliverables[]" value="{{$deliverables->deliverable}}" class="deliverables add_description"  placeholder="Deliverable {{$counter}}">{{$deliverables->deliverable}}</textarea>
                                        @endforeach
                                    <div class="clearfix"></div>

                                    <div  id="contract_deliverables_error" class="validation_error_deliverables error-message"></div>
                                </div>
                                <div class="input-bx "><a href="javascript:void(0);" class="add-deliverable-link gilroyregular-semibold" title="Add another deliverable">Add another deliverable</a></div>
                                <div class="input-bx supporting_document">
                                    <label>Attach a supporting agreement or document</label>
                                    <p>This is optional</p>
                                    <div class="file-upload">
                                        <span class="gilroyregular-bold-font">Attach file</span>
                                        <input name="upload" id="upload" class="upload" type="file">
                                        <div id="upload_file"></div>
                                    </div>
                                </div>

                                <input name="radio" value="1" type="hidden">

                                <div class="input-bx send_contract_btn_section">
                                    <input type="hidden" id="coupon_code_applied" name="coupon_code_applied" value=""/>
                                    <input type="hidden" id="duration" name="duration" value="{{$project_detail['duration']}}"/>
                                    <input type="hidden" id="subscription_type" name="subscription_type" value="{{$project_detail['subscription_type']}}"/>
                                    <input type="hidden" id="service_package_id" name="service_package_id" value="{{$project_detail['id']}}"/>
                                    <input name="sender_id" value="{{Auth::user()->id}}" id="sender_id" type="hidden">
                                    <input name="receiver_id" value="" id="receiver_id" type="hidden">
                                    <input name="communication_id" value="" id="communication_id" type="hidden">
                                    <input value="0" id="sp_contract_update" type="hidden">
                                    <input data-toggle="modal"  class="send-contract-btn standard-btn" id="submit_service_package_contract" value="Send Proposal" type="button">
                                    <input data-toggle="modal"  class="cancel-btn" data-dismiss="modal" value="Cancel" type="button">
                                </div>
                                </div>
                        </form>
                    </div></div>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('js/service_package_contract.js',[],$ssl) }}"></script>
@endif
