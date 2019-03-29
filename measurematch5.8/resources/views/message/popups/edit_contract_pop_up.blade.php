<?php
$ssl=getenv('APP_SSL');
$random_number = getenv('CACHING_COUNTER');
?>
<div class="modal-dialog modal-lg billing-detail-pending makeoffer-popup">
    <div class="modal-innner-content">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross" /></span></button>
                <?php
                $user_id = Auth::user()->id;
                ?>

                <div id="contract-detail">
                    <h3>Update Proposal</h3>

                    <form name="edit_contract" id="edit_contract_information" method="post" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        <div class="send_contract_form">

                                <input type="hidden" value="{{ $contract_detail['post_jobs']['id'] }}" id="job_post" name="job_post">


                            <div class="input-bx select-box add-time-period">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>When can you start working on this project?</label>
                                        <div class="select-box ">
                                            <input type="text" name="start_time" id="start_time" value="" placeholder="Click to add date">
                                            <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                        </div>
                                        <div class="validation_start_time validation-message-error clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-bx select-box add-time-period">
                                <div class="row">
                                    <div class="col-lg-12 estimation-lbl-form">
                                        <label>When do you estimate you’ll finish the project? </label>
                                        <div class="select-box" >
                                            <input type="text" name="end_time" id="end_time" value="" placeholder="Click to add date">
                                            <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                        </div>
                                        <div class="validation_end_time validation-message-error clearfix"></div>
                                        <span id="someplace"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="input-bx select-box">
                                <label>How much will you charge for this project?</label>
                                <p>Please remember that MeasureMatch will take a 15% fee from your proposal value (it’s not added on top).</p>
                                <div class="rates_select-box form-group static-select-bx">
                                    <input type="hidden" name="rate_variable" value="{{$contract_detail['rate_variable']}}" id="rate_variable">
                                </div>
                                <div class="form-group input-group priceinput">
                                    <div class="job-details-lbl">
                                        <div class="input-group-addon">{!! $contract_detail['rate_variable'].' <span id="currencty_symbol">'.convertToCurrencySymbol($contract_detail['rate_variable']).'</span>' !!}</div>
                                        <input type="text" maxlength="6" size="30" placeholder="Start typing your fee here..." class="price-format-validation"  name="rate" value="{{number_format($contract_detail['rate'])}}" id="project_price"  />
                                    </div>
                                </div>
                                <div class="validation_project_price validation-message-error clearfix"></div>
                            </div>
                            
                            <div class="input-bx deliverable_bx">
                                <label>Deliverables for the project</label>
                                <textarea class="deliverable_offer" id="project_deliverable" name="deliverable" placeholder="e.g. hours/days required, agreed outcomes, etc." >{{$contract_detail['project_deliverables']}}</textarea>
                                <div class="validation_project_deliverable validation-message-error clearfix"></div>
                            </div>

                            <div class="input-bx supporting_document">
                                <label>Attach a supporting agreement or document</label>
                                <p>This is optional </p>
                                <span id="preview_attachment" class="no_attachment_block font-14"></span>
                                <div class="file-upload">
                                    <span>Attach file</span>
                                    <input type="file" name="upload_file" id="upload" class="upload">
                                    <div id="uploadFile"></div>
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

                            <input type="hidden" name="radio" value="1" >
                            <div class="input-bx send_contract_btn_section">
                                <input type="hidden" id="update_coupon_code" name="update_coupon_code" value="{{$contract_detail['is_promotional_coupon_applied']}}"/>
                                <input class="send-contract-btn standard-btn" type="button" id="contract_preview_update" value="Update Proposal">
                                <input type="hidden" id="coupon_code_applied" name="coupon_code_applied" value=""/>
                                <input type="hidden" id="sender_id" name="sender_id" value="{{Auth::user()->id}}" />
                                <input type="hidden" id="receiver_id" name="receiver_id" value="{{$contract_detail['user_id']}}" />
                                <input type="hidden" name="attachment_val" value="@if(isset($contract_detail['upload_document']) && !empty($contract_detail['upload_document']) && $contract_detail['upload_document'] !=' '){{ $contract_detail['upload_document'] }} @endif" class="upload">
                                <input type="hidden" name="contract_id" id="contract_id" value="{{$contract_detail['id']}}">
                                <input type="hidden" id="communications_id" name="communication_id" value="{{ $contract_detail['communications_id'] }} ">
                                <input class="cancel-btn" type="button" id="contract_preview_update" data-dismiss="modal" value="Cancel">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{ date('d-m-Y',strtotime($contract_detail['job_start_date'])) }}" id="start_date">
<input type="hidden" value="{{ date('d-m-Y',strtotime($contract_detail['job_end_date'])) }}" id="end_date">

@php
$job_start_date_from_db =  date('d-m-Y',strtotime($contract_detail['job_start_date']));
$job_end_date_from_db =  date('d-m-Y',strtotime($contract_detail['job_end_date']));
if(strtotime(date('d-m-Y'))> strtotime($job_start_date_from_db))
    $job_start_date_from_db = date('d-m-Y');
if(strtotime(date('d-m-Y'))> strtotime($job_end_date_from_db))
    $job_end_date_from_db = date('d-m-Y');
@endphp
<input type="hidden" value="{{ $job_start_date_from_db}}" id="job_start_date_from_db">
<input type="hidden" value="{{ $job_end_date_from_db }}" id="job_end_date_from_db">

<script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/edit_contract.js?js='.$random_number,[],$ssl)}}"></script>