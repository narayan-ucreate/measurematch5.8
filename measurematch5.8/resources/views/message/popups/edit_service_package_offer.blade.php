@if(isset($contract_detail))
@php $start_date= date('d-m-Y',strtotime($contract_detail['job_start_date'])); @endphp
@php $end_date= date('d-m-Y',strtotime($contract_detail['job_end_date'])); @endphp
@if(strtotime($start_date) < strtotime(date('d-m-Y')))
@php $start_date = date("d-m-Y"); @endphp
@endif
@if(strtotime($end_date) < strtotime(date('d-m-Y')))
@php $end_date = date("d-m-Y"); @endphp
@endif

 <div class="modal-dialog modal-lg billing-detail-pending new-theme-modal makeoffer-popup">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross">
                        </span>
                    </button>
                    <div id="contract-detail">
                        <h3 class="gilroyregular-bold-font">Update Proposal</h3>
                        <form id="update_service_package_contract" name="service-package-contract"  method="post" enctype="multipart/form-data">
                              {{ csrf_field() }}
                            <div class="send_contract_form">

                                     <div class="input-bx select-box add-time-period">
                                            <label>When can you start working on this package?</label>
                                            <div class="select-box ">
                                                <input name="start_time" id="start_time" value="{{$start_date}}" placeholder="From" type="text">
                                                <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                            </div>

                                            <div id="start_time_error" class=" error-message"></div>
                                </div>
                                <div class="input-bx select-box add-time-period">
                                    <div class="estimation-lbl-form">
                                            <label>When do you estimate you’ll finish the package? @if($contract_detail['subscription_type']=="one_time_package")@endif</label>

                                            <div id="contract_end_time" class="select-box">
                                                @if($contract_detail['subscription_type']=="monthly_retainer")
                                                <span class="monthly-retainer-lbl">Monthly Retainer (cancel anytime)</span>
                                                <p>Based on a contract start date of <span class="monthly-start-date">{{$start_date}}</span>, you will be billed on the
                                                    <span class="monthly-billing-day">{{ date('dS', strtotime('+ 30 days', strtotime($start_date)))}}</span> of every month, starting on
                                                    <span class="monthly-end-date">{{ date('d-M-Y', strtotime('+ 30 days', strtotime($start_date)))}}</span>. You can finish or cancel this retainer at any time.</p>

                                                @else
                                                <input name="end_time" id="end_time" value="{{date('d-m-Y',strtotime($end_date))}}" placeholder="To" type="text">
                                                    <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                                @endif
                                            </div>
                                            <div id="end_time_error" class=" error-message"></div>
                                            <span id="someplace"></span>
                                        </div>
                                </div>
                                @if($contract_detail['subscription_type']=="monthly_retainer")
                                <div class="input-bx maxiimum-time select-dropdown-style custom-dropdown-style monthly-tooltip-design">
                                    <label id="commitment_duration">Expected monthly time commitment</label>
                                    <div class="clearfix"></div>
                                    <select class="selectpicker" name="monthly_days_commitment" id="monthly_days_commitment">
                                        <option value="">Choose</option>
                                        @for($date=1; $date<=30;$date++)
                                        @php $days=($date==1)?'day /month':'days /month'; @endphp
                                        <option  title="{{$date.' '.$days}}" data-content="{{$date.' '.$days}}" @if($contract_detail['monthly_days_commitment'] == $date) selected='selected' @endif value="{{$date}}">{{$date.' '.$days}}</option>
                                        @endfor
                                    </select>
                                    <div id="monthly_days_commitment_error" class=" error-message"></div>

                                </div>
                                @endif
                                <div class="input-bx select-box">
                                    <label>Package Cost </label>
                                    <div class="rates_select-box form-group static-select-bx">
                                        <input name="rate_variable" value="USD" id="rate_variable" type="hidden">
                                        <div id="contract_rate_variable_error" class=" error-message"></div>
                                    </div>
                                    <div class="form-group input-group">
                                        <div class="job-details-lbl">
                                        <div class="input-group-addon">USD $</div>
                                         @php if($contract_detail['subscription_type']=="one_time_package"){ $price= number_format($contract_detail['rate']); }else{ $price= number_format($contract_detail['rate'])." / month";} @endphp
                                        <input maxlength="13" size="30" class="@if($contract_detail['subscription_type'] !='monthly_retainer') price-format-validation @endif" name="rate" value="{{$price}}" id="price" type="text">
                                        </div>
                                    </div>
                                    <div id="contract_price_error" class=" error-message"></div>
                                </div>

                                <div class="input-bx-panel deliverable-panel bottom-margin-0">
                                    <label>Deliverables </label>
                                        @php $counter = 1; @endphp
                                         @foreach ($deliverables as $deliverable)
                                         <textarea name="deliverables[]" value="{{$deliverable->deliverable}}" class="deliverables add_description"  placeholder="Deliverable {{$counter}}">{{$deliverable->deliverable}}</textarea>
                                         @endforeach
                                    <div class="clearfix"></div>


                                </div>
                                <div>
                                    <a href="javascript:void(0);" class="add-deliverable-link gilroyregular-semibold" title="Add another deliverable">Add another deliverable</a>
                                    <div  id="contract_deliverables_error" class="validation_error_deliverables error-message"></div>
                                </div>

                                <div class="input-bx supporting_document">
                                    <label>
                                        Attach a supporting agreement or document</label>
                                    <div class="file-upload">
                                        <span class="gilroyregular-bold-font">Attach file</span>
                                        <input name="upload_file" id="upload" class="upload" type="file">
                                        <div id="upload_file"></div>
                                    </div>
                                     @if(isset($contract_detail['upload_document']) && !empty($contract_detail['upload_document']) && ($contract_detail['upload_document'] !=' '))
                                <?php
                                $imageExplode = explode('/', $contract_detail['upload_document']);
                                $final_img = explode('_', end($imageExplode));
                                unset($imgexpl);
                                foreach ($final_img as $key => $img) {
                                    if ($key != 0) {
                                        $imgexpl[] = $img;
                                    }
                                }
                                $img_names = implode($imgexpl);
                                ?>
                                <a class="attached-files-link" target="_blank" title="Attach file(s)" href="@if(isset($contract_detail['upload_document']) && !empty($contract_detail['upload_document']) && ($contract_detail['upload_document'] !=' ')) {{$contract_detail['upload_document'] }} @else {{ 'javascript:void(0)' }} @endif">{{ $img_names }}</a> @else
                                <span class="no_attachment_block font-14">N/A</span>
                                @endif
                                </div>

                                <input name="radio" value="1" type="hidden">

                                <div class="input-bx send_contract_btn_section">
                                    <input type="hidden" id="coupon_code_applied" name="coupon_code_applied" value=""/>
                                    <input type="hidden" id="duration" name="duration" value="{{$contract_detail['id']}}"/>
                                    <input type="hidden" id="subscription_type" name="subscription_type" value="{{$contract_detail['subscription_type']}}"/>
                                    <input type="hidden" id="service_package_id" name="service_package_id" value="{{$contract_detail['service_project_id']}}"/>
                                    <input type="hidden" id="contract_id" name="contract_id" value="{{$contract_detail['id']}}"/>
                                    <input name="sender_id" value="{{Auth::user()->id}}" id="sender_id" type="hidden">
                                    <input name="receiver_id" value="" id="receiver_id" type="hidden">
                                    <input name="communication_id" value="{{$contract_detail['communications_id']}}" id="communication_id" type="hidden">
                                    <input value="1" id="sp_contract_update" type="hidden">
                                    <input data-toggle="modal"  class="send-contract-btn standard-btn" id="submit_service_package_contract" value="Update Proposal" type="button">

                                    <input class="cancel-btn" type="button" id="contract_preview_update" data-dismiss="modal" value="Cancel">
                                </div>


                            </div>
                        </form>
                    </div></div>
            </div>
        </div>
    </div>
<script src="{{ url('js/service_package_contract.js',[],$ssl) }}"></script>
@endif
