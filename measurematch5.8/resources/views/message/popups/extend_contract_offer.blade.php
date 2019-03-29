<div class="modal invite-seller-popup send_contract_popup lightbox-design lightbox-design-small fade in" id="send_contract" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
    <div class="modal-dialog modal-lg billing-detail-pending">
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
                    <div class="panel-group contract-extend-panel" id="accordion">
                        @foreach($all_contracts as $contract)
                        @php
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
                                    <a class="accordion-toggle gilroyregular-bold-font font-16 primary-text-color" data-toggle="collapse" data-parent="#accordion" href="#contract_{{$contract['id']}}">{{$contract['alias_name']}} <span class="pull-right font-14">Expand details</span></a>
                                </h4>

                            </div>
                            <div id="contract_{{$contract['id']}}" class="panel-collapse collapse contract-panel">
                                <div class="panel-body">
                                    <div class="input-bx select-box add-time-period">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 contract-startdate-panel">
                                                <label>Contract start date</label>
                                                <span class="font-14">{!! date('d M Y',strtotime($contract['job_start_date'])) !!}</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 contract-enddate-panel">
                                                <label>Contract end date</label>
                                                <span class="font-14">{!! date('d M Y',strtotime($contract['job_end_date'])) !!}</span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 contract-value-panel margin-bottom-10">
                                                <label>Contract value</label>
                                                <span class="font-14">{{convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'], 2).' ('.$expert_share.' to '.$expert_name.', '.$mm_fee.' to Measurematch)'}}</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 deliverables-panel">
                                                <label>Deliverables</label>
                                                @if(isset($contract['project_deliverables']) && !empty(trim($contract['project_deliverables'])))
                                                <span class="font-14">{!! $contract['project_deliverables'] !!}</span>
                                                @endif
                                            </div>
                                            @if(isset($contract['upload_document']) && !empty(trim($contract['upload_document'])))
                                            <div class="col-lg-12 col-md-12 supporting_document">
                                                <label>Supporting agreement/document</label>
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
                                                <a class="attached-files-link link-color"" target="_blank" title="Attach file(s)" href="@if(isset($contract['upload_document']) && !empty($contract['upload_document']))
                                                   {{$contract['upload_document'] }} @else {{ 'javascript:void(0)' }} @endif">
                                                    {{ $img_names }}</a>

                                            </div>
                                           @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="panel panel-default extend-contract-panel">
                            <div class="panel-heading">
                                @php $all_contracts_count = _count($all_contracts) ?? 0; @endphp
                                <h4>
                                    <a class="accordion-toggle gilroyregular-bold-font font-16" data-toggle="collapse" data-parent="#accordion" href="#extension">Extension: <span class="primary-text-color">Contract 1.0{{$all_contracts_count + 1}} </span></a>
                                </h4>
                            </div>

                            <div id="extension" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div id="contract-detail">

                                        <form name="extend_contract_form" id="extend_contract_form" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="send_contract_form">
                                                <div class="input-bx select-box add-time-period">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 margin-bottom-20">
                                                            <label>Extension start date</label>
                                                            <div class="select-box ">
                                                                <input name="start_time" id="extension_start_time" value="" placeholder="From" type="text">
                                                            </div>

                                                            <div id="extension_start_time_error" class="error-message"></div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 estimation-lbl-form margin-bottom-20">
                                                            <label>Extension end date</label>
                                                            <div class="select-box">
                                                                <input name="end_time" id="extension_end_time" value="" placeholder="To" type="text">
                                                            </div>
                                                            <div id="extension_end_time_error" class="error-message"></div>
                                                            <span id="someplace"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-bx select-box">
                                                    <label>Extension contract value</label>
                                                    <div class="rates_select-box form-group static-select-bx">
                                                        <input name="rate_variable" value="{{$parent_contract['rate_variable']}}" id="rate_variable" type="hidden">
                                                        <div  id="contract_rate_variable_error" class="error-message"></div>
                                                    </div>
                                                    <div class="form-group input-group">
                                                        <div class="job-details-lbl">
                                                        <div class="input-group-addon">{!! $parent_contract['rate_variable'].' <span id="currencty_symbol">'.convertToCurrencySymbol($parent_contract['rate_variable']).'</span>' !!}</div>
                                                        <input maxlength="6" size="30" class="price-format-validation" name="rate" id="rate" type="text">
                                                        </div>
                                                    </div>
                                                    <div id="contract_price_error" class="error-message"></div>
                                                </div>

                                                <div class="input-bx deliverable_bx">
                                                    <label>Extension Deliverables</label>
                                                    @if(isset($service_package_deliverables))
                                                    <input name="service_package_id" value="{{$parent_contract['service_package_id']}}" id="job_post_id" type="hidden">
                                                    <input name="subscription_type" value="{{$parent_contract['subscription_type']}}" id="subscription_type" type="hidden">
                                                    <div class="deliverable-panel">
                                                        @php $counter = 1; @endphp
                                                        @foreach ($service_package_deliverables as $deliverables)
                                                        <textarea name="deliverables[]" value="{{$deliverables->deliverable}}" class="deliverables add_description"  placeholder="Deliverable {{$counter}}">{{$deliverables->deliverable}}</textarea>
                                                        @endforeach
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);" class="add-deliverable-link gilroyregular-semibold" title="Add another deliverable">Add another deliverable</a></div>
                                                    @else
                                                        <input name="job_post_id" value="{{$parent_contract['job_post_id']}}" id="job_post_id" type="hidden">
                                                        <textarea class="deliverable_offer" id="deliverable" name="deliverable" placeholder="(e.g. hours/days required, agreed outcomes, etc.)" style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                                                    @endif
                                                    <div id="contract_deliverables_error" class="error-message"></div>
                                                </div>

                                                <input type="hidden" name="payment_mode" value="invoice">
                                                <input type="hidden" name="parent_contract_id" value="{{$parent_contract->id}}">
                                                <input type="hidden" id="contract_type" value="{{$parent_contract->type}}">

                                                <div class="input-bx supporting_document">
                                                    <label>Supporting agreement/document -optional</label>
                                                    <div class="file-upload"><span>Attach file</span>
                                                        <input name="upload" id="upload" class="upload" type="file">
                                                        <div id="uploadFile"></div>
                                                    </div>
                                                </div>
                                                @php $all_contracts_count = _count($all_contracts) ?? 0; @endphp
                                                <input name="radio" value="1" type="hidden">
                                                <input name="alias_name" value="Contract 1.0{{$all_contracts_count + 1}}" type="hidden">

                                                <div class="input-bx send_contract_btn_section text-center">
                                                    <input type="hidden" id="coupon_code_applied" name="coupon_code_applied" value=""/>
                                                    <input name="sender_id" value="{{Auth::user()->id}}" id="sender_id" type="hidden">
                                                    <input name="receiver_id" value="" id="receiver_id" type="hidden">
                                                    <input name="current_contract_id" value="" id="current_contract_id" type="hidden">
                                                    <input name="communication_id" value="" id="communication_id" type="hidden">
                                                    <input data-toggle="modal" class="send-contract-btn standard-btn margin-bottom-10 make-contract-extension-btn" id="extend_contract_submit" value="Make contract extension offer" type="button">
                                                    <input type="button" class="white-bg white-bg-btn cancel-package-btn btn-min-width" data-dismiss="modal" aria-label="Close" value="Cancel">
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
        </div>
    </div>
</div>
<script type="text/javascript">
    var date = new Date({{$extension_contract_minimum_year}}, {{$extension_contract_minimum_month-1}}, {{$extension_contract_minimum_date}});
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    var minimum_date = moment(new Date((monthIndex + 1) + "/" + (day) + "/" + (year)));
    $('.selectpicker').selectpicker();
    if (document.getElementById("upload") != null) {
           document.getElementById("upload").onchange = function () {
               var attachment = $(this).val();
               var res = attachment.replace("C:\\fakepath\\", "");
               var pass = document.getElementById('uploadFile');
               pass.innerHTML = res;
           };
    }
    if (document.getElementById("attachment") != null) {
     document.getElementById("attachment").onchange = function () {

         var attachment = $(this).val();
         var ext = attachment.split('.').pop();
         var res = attachment.replace("C:\\fakepath\\", "");
         var pass = document.getElementById('sendMsgName');
         pass.innerHTML = "File Attached";
         if (ext == 'exe') {
             $('#attachment').val('');
             document.getElementById('sendMsgName').innerHTML = '';
             var pass = document.getElementById('error_upload');
             pass.innerHTML = ".exe files are not allowed";
         }


     };
    }
  autosize(document.querySelectorAll('textarea.deliverable_offer'));
    $('#extension_start_time').datetimepicker({
       ignoreReadonly: true,
       format: 'DD-MM-YYYY',
       minDate: minimum_date
   });
   $('#extension_end_time').datetimepicker({
       ignoreReadonly: true,
       format: 'DD-MM-YYYY',
       minDate: minimum_date
   });
   $("#extension_start_time").on("dp.change", function (e) {

       $('#extension_end_time').data("DateTimePicker").minDate(e.date);
   });
   </script>
