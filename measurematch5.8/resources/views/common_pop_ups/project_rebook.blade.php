<div id="rebook_project" class="modal fade  comman-popup-form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span>
                </button>
                <div class="modal-body">
                    <div id="vat_details_section_on_start_conversation" class="">
                        <div class="col-md-12 info-right-side">
                            <div class="popup-content">
                                <span class="expert-profile-pic rebook-pic" ></span>
                                <h5 class="gilroyregular-semibold">Describe the project you'd like to work with <span class="rebook-expert-name"></span> </h5>
                            </div>
                            <form id="rebook_project_form" method="post" action="{{route('saveProject')}}">
                                <input type="hidden" id="token" value="{{csrf_token()}}">
                                <input type="hidden" id="expert_id" value="">
                                <input type="hidden" id="redirect_url" value="{{route('buyerProjectMessages', ['project', true])}}">

                                <div id="business_address" class="business-address-details">
                                    <div class="input-bx">
                                        <label>Project title</label>
                                        <input tabindex="1" type="text" id="job_title" class="form-field-rebook" name="title" placeholder="e.g. Google Analytics Expert Needed" value="">
                                    </div>
                                    <div class="input-bx">
                                        <label>Describe the project</label>
                                        <textarea id="description" name="description" rows="3" maxlength="5000" tabindex="2" class="input-field-design form-field-rebook" placeholder="Explain the problem or opportunity you’re looking to address for you, your team, your business and - if relevant - your client(s)." cols="50"></textarea>
                                    </div>
                                    <div class="input-bx deliverable-panel">
                                        <label>Describe the deliverable(s)</label>
                                        <textarea  name="deliverables[]" maxlength="2000" value="" rows="3" tabindex="3" class="input-field-design deliverables form-field-rebook" placeholder="e.g. Configure multiple dashboards within Google Analytics" cols="50"></textarea>
                                        <div class="clearfix"></div>
                                        <div id="deliverables_validation_error" class="error-message"></div>
                                        <a  id="add-deliverable-link" class="add-another-deliverable gilroyregular-semibold font-14" href="javascript:void(0);" title="Add another deliverable">Add another deliverable</a>
                                    </div>
                                    <div class="input-bx">
                                        <label>Which currency do you intend to pay with?</label>
                                        <select id="currency" name="currency" tabindex="12" class="selectpicker">
                                            <option value="">Choose</option>
                                            @foreach( __('currencies') as $key=>$currency)
                                                <option value="{{$key}}">{{$currency}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-bx text-center">
                                        <input tabindex="11" type="submit" class="standard-btn btn disable-btn" id="submit-rebook" value="Continue">
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

