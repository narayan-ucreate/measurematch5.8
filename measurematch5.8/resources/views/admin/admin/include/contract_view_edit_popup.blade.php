@if($contract)
<div class="modal lightbox-design admin-contract-popup" id="viewcontract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>{{$contract->alias_name}} Details</h2>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="https://measurematch.herokuapp.com/images/cross-black.svg"></span></button>
                </div>

                <div class="modal-body">
                    <form id="update_contract_details">
                         <div id='contract_updated' class="pull-right message-section">
                            <p class="success" style="color:green"></p>
                            <p class="warning error-message"></p>
                        </div>
                        <div class="input-box project-title-box">
                            <label class="font-18">Contract start date:</label>
                            <div class="project-start-date">
                                <input id="contract_start_date" name="job_start_date" value="" type="text" size="45" readonly="readonly"  placeholder="Click to add date" tabindex="11" maxlength="150" class="input-md-field-design font-16" />
                                <input id="default_contract_start_date" type="hidden" value="{{ $contract->job_start_date}}">
                            </div>
                            <div id="contract_start_date_error" class="validation_error error-message" ></div>
                        </div>
                        <div class="input-box project-title-box">
                            <label class="font-18">Contract end date:</label>
                            <div class="project-start-date">
                                <input id="contract_end_date" name="job_end_date" value="" type="text" size="45" readonly="readonly"  placeholder="Click to add date" tabindex="11" maxlength="150" class="input-md-field-design font-16" />
                                <input id="default_contract_end_date" type="hidden" value="{{ $contract->job_end_date}}">
                            </div>
                            <div id="contract_end_date_error" class="validation_error error-message" ></div>
                        </div>
                        <div class="input-box select-box rate-section">
                            <label class="font-18">Value of project</label>
                            <div class="form-group project-budget-input">
                                <div class="input-group">
                                    <div class="input-group-addon currency-hint ">  {{$contract->rate_variable.' '.convertToCurrencySymbol($contract->rate_variable)}}</div>
                                    <input type="tel" id="contract_budget" value="{{number_format($contract->rate)}}" name="rate"  maxlength="10" tabindex="14" class="form-control"  placeholder="Type your Project budget here...">
                                </div>
                            </div>
                            <div id="contract_budget_error" class="validation_error error-message" ></div>
                        </div>
                        <input id="update_project_details" contract_id="{{ $contract->id}}" type="button" tabindex="17" value="Update" class="font-18 btn standard-btn gilroyregular-semibold finish-submit-btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{url('js/admin_contract_edit.js?js='.$random_number,[],$ssl)}}"></script>
@endif