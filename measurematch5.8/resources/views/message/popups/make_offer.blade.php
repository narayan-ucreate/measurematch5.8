@if(isset($project_detail))
<div class="modal invite-seller-popup perposalview-offer send_contract_popup makeoffer-popup lightbox-design lightbox-design-small fade in" id="send_contract" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
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
                        <h3>Send a Proposal to <span id="buyer_company_name">{{$buyer_company}}</span></h3>
                        
                        <p>In order to lock in an agreement with a MeasureMatch client, you need to send a proposal by completing the form below.</p>

                        <form name="submitcontract" id="submitcontract" method="post" enctype="multipart/form-data">
                              {{ csrf_field() }}
                            <div class="send_contract_form">


                                <div class="input-bx select-box add-time-period">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <label>When can you start working on this project?</label>
                                            <div class="select-box ">
                                                <input name="start_time" id="start_time" value="" placeholder="Click to add date" type="text">
                                                
                                                <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                            </div>

                                            <div id="start_time_error" class="error-message"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-bx select-box add-time-period">
                                    <div class="row">
                                        <div class="col-lg-12 estimation-lbl-form">
                                            <label>When do you estimate you’ll finish the project? </label>
                                            <div class="select-box">
                                                <input name="end_time" id="end_time" value="" placeholder="Click to add date" type="text">
                                                <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                            </div>
                                            <div id="end_time_error" class="error-message"></div>
                                            <span id="someplace"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-bx select-box">
                                    <label>How much will you charge for this project?</label>
                                    <p>Please remember that MeasureMatch will take a 15% fee from your proposal value (it’s not added on top).</p>
                                    <div class="rates_select-box form-group static-select-bx">
                                        <input name="rate_variable" value="{{$project_detail->currency}}" id="rate_variable" type="hidden">
                                        <input value="{{convertToCurrencySymbol($project_detail->currency)}}" id="rate_variable_symbol" type="hidden">
                                        <div  id="contract_rate_variable_error" class="error-message"></div>
                                    </div>
                                    <div class="form-group input-group">
                                        <div class="job-details-lbl">
                                        <div class="input-group-addon">{{ $project_detail->currency.' '.convertToCurrencySymbol($project_detail->currency)}}</div>
                                        <input maxlength="7" size="30" name="rate" placeholder="Start typing your fee here..."  class="price-format-validation" value="{{(($project_detail->rate)>0)? number_format($project_detail->rate):''}}" id="rate" type="text">
                                        </div>
                                    </div>
                                    <div id="contract_price_error" class="error-message"></div>
                                </div>

                                <div class="input-bx deliverable_bx">
                                    <label>Deliverables for the project</label>
                                    <textarea name="deliverable" id="deliverable" placeholder="e.g. hours/days required, agreed outcomes, etc."></textarea>
                                    <div id="contract_deliverables_error" class="error-message"></div>
                                </div>


                                <div class="input-bx supporting_document">
                                    <label>Attach a supporting agreement or document</label>
                                    <p>This is optional</p>
                                    <div class="file-upload"><span>Attach file</span>
                                        <input name="upload" id="upload" class="upload" type="file">
                                        <div id="uploadFile"></div>
                                    </div>
                                </div>

                                <input name="radio" value="1" type="hidden">

                                <div class="input-bx send_contract_btn_section">
                                    <input type="hidden" id="coupon_code_applied" name="coupon_code_applied" value=""/>
                                    <input name="sender_id" value="{{Auth::user()->id}}" id="sender_id" type="hidden">
                                    <input name="receiver_id" value="" id="receiver_id" type="hidden">
                                    <input name="communication_id" value="" id="communication_id" type="hidden">
                                    <input name="job_post_id" value="{{$project_detail->id}}" id="job_post_id" type="hidden">
                                    <input data-toggle="modal"  class="send-contract-btn standard-btn" id="contract_preview" value="Continue & Review Proposal" type="button">
                                    <input data-toggle="modal"  class="cancel-btn" data-dismiss="modal" value="Cancel" type="button">

                                </div>
                            </div>
                        </form>
                    </div></div>
            </div>
        </div>
    </div>
</div>
@endif
@include('message.popups.buyer_offer_preview')
<script type="text/javascript">
    var date = new Date();
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
    $('#start_time').datetimepicker({
       ignoreReadonly: true,
       format: 'DD-MM-YYYY',
       minDate: minimum_date
   });
   $('#end_time').datetimepicker({
       ignoreReadonly: true,
       format: 'DD-MM-YYYY',
       minDate: minimum_date
   });
   $("#start_time").on("dp.change", function (e) {

       $('#end_time').data("DateTimePicker").minDate(e.date);
   });
   
</script>
