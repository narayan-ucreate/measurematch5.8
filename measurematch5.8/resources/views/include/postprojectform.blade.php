@php $current_method = last(explode('/', url()->current())); @endphp
<div class="project-overview-step-one margin-top-32">
    {{ csrf_field() }}
    @if (_count($errors->all()))
    <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
    </div>
    @endif
    <h4 class="font-20 gilroyregular-bold-font">1. Project brief overview</h4>

    <div class="input-box project-title-box margin-top-28">
        <label>What is the title of this Project?
            <a href="javascript:void();" class="help-info-icon gilroyregular-font mob-help-icon">
                <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
                <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" />
                <span>Here you should include a top-line, punchy title that communicates the Project clearly.</span>
            </a>
        </label>
        <textarea id="title" name="title" rows="3" maxlength="150" tabindex="1" class="input-field-design font-16" placeholder="A top-line, punchy title describing what you need help with." cols="50">{{old('title')}}</textarea>
        <div id="title_validation_error" class="error-message"></div>
    </div>

    <div class="input-box project-title-box margin-top-32 describe-project-field">
        <label>Please describe this Project. <a href="javascript:void();" class="help-info-icon gilroyregular-font">
            <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
            <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" />
            <span>To give you an example, here is an Project description taken from a previous brief: We would like to optimize our analytics setup in order to better understand the traffic on our Booking pages. At the moment, it is difficult to analyse properly what the users do on the page.</span></a>
        </label>
        <textarea id="description" name="description" rows="3" maxlength="5000" tabindex="2" class="input-field-design font-16" placeholder="Here, explain in some detail the problem or opportunity you’re looking to address for you, your team, your business and - if relevant - your client(s)." cols="50">{{old('description')}}</textarea>
        <div id="description_validation_error" class="error-message"></div>
    </div>
    <div class="input-box project-title-box deliverable-panel margin-top-32">
        <label>Break down the deliverables you’re expecting for this Project.
            <a href="javascript:void();" class="help-info-icon gilroyregular-font">
                <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
                <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" />
                <span>Here are some example deliverables to help spark some ideas:
                    <ul>
                        <li>Create tracking specifications for websites and marketing campaigns</li>
                        <li>Setup marketing dashboards and reports to assist analysis</li>
                        <li>Analyse how campaigns are performing and how to improve them</li>
                    </ul>
                </span>
            </a>
        </label>
        <textarea  name="deliverables[]" maxlength="2000" value="" rows="3" tabindex="3" class="input-field-design deliverables font-16" placeholder="Describe a deliverable here." cols="50"></textarea>
        <div class="clearfix"></div>
        <div id="deliverables_validation_error" class="error-message"></div>
    </div>
    <div class="input-box margin-top-10">
        <a  id="add-deliverable-link" class="add-another-deliverable gilroyregular-semibold pull-right font-18" href="javascript:void(0);" title="Add another deliverable">Add another deliverable</a>
    </div>

    <div class="input-box project-title-box margin-top-32 attachment-box">
        <label class="font-18">(Optional) Would you like to attach a file to your Project brief?
            <a href="javascript:void();" class="help-info-icon gilroyregular-font">
                <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
                <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" /><span>If there are any files, images or presentations you'd like to add to the brief, here's the place to do it.</span></a>
        </label>
        <div class="file-upload attach-file-btn">
          <span>Attach file(s)</span>
            <input type="file" name="attachments[]" id="attachments" title=" " tabindex="4" class="upload">
        </div>
        <div id="uploaded_files"></div>
        <div id="upload_validation_error" class="error-message"></div>
    </div>
    <div class="input-box project-title-box margin-top-32 brief-company-panel">
        <label class="font-18">(Optional) Would you like to hide your company name from the brief? </label>
        <div class="check-box-design">
            <input id="hide_company_name" type="checkbox" tabindex="5" name="hide_company_name" value="1" default_type="@if(!empty($buyer_detail[0]['type_of_organization_id']) && $buyer_detail[0]['type_of_organization_id']){{$buyer_detail[0]['type_of_organization_id']}}@endif">
            <label for="hide_company_name" class="font-16"><span><span></span></span>Yes, I’d like to hide my company name</label>
        </div>
        <div id="type_of_org_block" class="select-box choose-org-type-dropdown margin-top-20" style="display:none;">
            <label>Choose your organization type </label>
            <select name="type_of_organization" id="type_of_organization"  tabindex="6" placeholder="Choose organization type" class="selectpicker select-dropdown-style col-md-6 col-xs-12" >
                <option value="">Choose organization type</option>
                @foreach($type_of_org_list as $type_of_org_id => $type_of_org_value)
                @if($type_of_org_value != 'Other Industry')
                <option value="{{$type_of_org_id}}" @if(!empty($buyer_detail[0]['type_of_organization_id']) && $buyer_detail[0]['type_of_organization_id'] == $type_of_org_id) selected='selected' @endif>{{$type_of_org_value}}</option>
                @endif
                @endforeach
                <option value="Other Industry">Other Industry</option>
            </select>
            <div id="type_of_org_validation_error" class="error-message"></div>
        </div>
    </div>
</div>
<div class="project-overview-step-one margin-top-30">
    <h4 class="font-20 gilroyregular-bold-font">2. Expert specifics</h4>
    <div class="input-box preference-location-panel margin-top-24">
        <label class="font-18">What is your location preference...</label>
        <div class="input-bx select-box rate-section col-md-12">
            <div class="radio-button-style">
                <input type="radio" name="work_location" tabindex="7" id="work_location" value="3">
                <label for="work_location"><span class="radio-circle"><span></span></span>Expert can work on-site or remotely</label>
            </div>
        </div>
        <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
            <input type="radio" name="work_location" id="on_site" value="2">
            <label for="on_site"><span class="radio-circle"><span></span></span>Expert must work on-site</label>
        </div>
        <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
            <input type="radio" name="work_location" id="remotely" value="1">
            <label for="remotely"><span class="radio-circle"><span></span></span>Expert must work remotely</label>
        </div>
        <div id="work_location_error" class="validation_error error-message"></div>
    </div>
    <div class="input-box project-title-box margin-top-24 custom-dropdown-style location-dropdown-style">
        <label>(Optional) Which tools and/or technologies do you need help with?</label>
        <div class="project-subtitle-box">{{ config('constants.PRESS_ENTER_TOOL') }}</div>
        <div class="add-tools-button-block">
            <div class="add-more-tools"></div>
            <input type="text" id="add_tools_manually" class="skill-input input-md-field-design font-16" tabindex="8"  autocomplete="off" value="" placeholder="E.g. Google Analytics, Adobe Target, Salesforce etc."/>
            <input type="hidden" id="manual_tools" value="" name="tools" />
        </div>
        <div class="error-message validation_error_add_tools"></div>
    </div>
    <div class="input-box project-title-box margin-top-32 location-dropdown-style">
        <label>(Optional) Which advisory and/or solution skills do you require?</label>
        <div class="project-subtitle-box">{{ config('constants.PRESS_ENTER_SKILL') }}</div>
        <div class="add-skills-button-block">
            <div class="add-more-skills"></div>
            <input  type="text"  id="add_skills_manually"  class="skill-input input-md-field-design font-16" tabindex="9"  autocomplete="off" value="" placeholder="E.g. Data Collection Strategy, Customer Segmentation, Solutions Architecting etc."/>
            <input type="hidden"  id="manual_skills"  value=""  name="skills" />
        </div>
        <div class="error-message validation_error_add_skills"></div>
    </div>
</div>
<div class="project-overview-step-one margin-top-30">
    <h4 class="font-20 gilroyregular-bold-font">3. Office Location, Timeline & Budget</h4>
    <div class="input-box project-title-box margin-top-24 office-location-panel">
        <label>Where is your office location? </label>
        <div class="col-md-8 account_info new-custom-dropdown-style">
            <input id="add_office_location"  name="office_location"   placeholder="E.g. London, UK" value="" tabindex="10" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="off">
            <div id="office_location_tags" class="dropdown"></div>
        </div>
        <div id="office_location_error" class="validation_error error-message" ></div>
    </div>
    <div class="input-box project-title-box margin-top-32">
        <label class="font-18">When would you like a MeasureMatch Expert to start working on your project? </label>
        <div class="col-md-8 project-start-date">
            <input id="end_date" name="end_date" type="text" size="45" readonly="readonly"  placeholder="Click to add date" tabindex="11" maxlength="150" class="input-md-field-design font-16" /></div>
        <div id="end_date_error" class="validation_error error-message" ></div>
    </div>
    <div class="input-box project-title-box margin-top-32 select-box project-time-field">
        <label class="font-18">How much time do you think will be required for this Project?
            <a href="javascript:void();" class="help-info-icon gilroyregular-font">
                <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
                <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" />
                <span>If you know how long the Project will take to deliver, or have a deadline to work with, add it here. Of course you may have no idea on how long the Project will be, so we have added the field "I don't know" in this case.</span></a>
        </label>
        <select id="project_duration" name="project_duration" tabindex="12" class="selectpicker select-dropdown-style col-md-8 col-xs-12">
            <option value="">Choose</option>
            <option value="4">Less than 1 week</option>
            <option value="5">1 week (5 working day)</option>
            @for($i=2;$i<=12;$i++)
            <option value="{{(5*$i)}}">{{$i}} weeks ({{(5*$i)}}  working days)</option>
            @endfor
            <option value="65" >More than 12 weeks</option>
            <option value="0">I don't know</option>
        </select>
        <div id="project_duration_error" class="validation_error error-message"></div>
    </div>
    <div class="input-box project-title-box margin-top-32 select-box project-time-field">
        <label class="font-18">Do you have a budget approved for this Project?
        </label>
        <select id="budget_approval_status" name="budget_approval_status" tabindex="12" class="selectpicker select-dropdown-style col-md-8 col-xs-12">
            <option value="">Choose</option>
            <option value="1">I own/control the budget for investments like these</option>
            <option value="2">I have access to budget or it has been pre-approved</option>
            <option value="3">I don't have budget approval; I need proposals first</option>
        </select>
        <div id="budget_approval_status_error" class="validation_error error-message"></div>
    </div>
    <div class="input-box project-title-box margin-top-32 select-box project-time-field">
        <label class="font-18">Which currency do you intend to pay with?
        </label>
   
        <select id="currency" name="currency" tabindex="12" class="selectpicker select-dropdown-style col-md-8 col-xs-12">
            @foreach( __('currencies') as $key=>$currency)
            <option value="{{$key}}">{{$currency}}</option>
            @endforeach
         </select>
        <div id="currency_error" class="validation_error error-message"></div>
    </div>
    <div class="input-box project-title-box margin-top-32 project-budget-panel">
        <label class="font-18">What is your budget for this Project?
            <a href="javascript:void();" class="help-info-icon gilroyregular-font mob-help-icon">
                <img src="{{url('images/hover-help.svg',[],$ssl)}}" class="hover-help" alt="hover-help" />
                <img class="hover-help-green" src="{{url('images/hover-help-green.svg',[],$ssl)}}" alt="hover-help" /><span>You may not know the exact budget of the Project, so we have added an option, "I don't know my budget". This will show as "Negotiable" in the Project brief to Experts.</span></a>
        </label>
        <div class="input-bx select-box rate-section col-md-12 margin-bottom-10">
            <div class="radio-button-style">
                <input type="radio" checked name="rate_variable" id="fixed_rate"  tabindex="13" value="fixed">
                <label for="fixed_rate"><span class="radio-circle"><span></span></span>I have a Project budget in mind</label>
            </div>
            <div class="clearfix"></div>
            <div id="project_budget_input" class="form-group col-md-8 col-xs-12 project-budget-input">
                <div class="input-group">
                    <div class="input-group-addon currency-hint "> GBP £</div>
                    <input type="tel" id="project_budget" name="project_budget"  maxlength="10" tabindex="14" class="form-control"  placeholder="Type your Project budget here...">
                </div>
            </div>
        </div>
        <div class="radio-button-style option-budget-inputbx input-bx col-md-12 rate-section  margin-bottom-10">
            <input type="radio" name="rate_variable" id="daily_rate"  tabindex="15" value="daily_rate">
            <label for="daily_rate"><span class="radio-circle"><span></span></span>I have a “Day Rate” budget in mind</label>
            <div class="clearfix"></div>
            <div id="daily_budget_input" class="form-group col-md-8 col-xs-12 project-budget-input project-daybudget-input">
                <div class="input-group" style="display: none" >
                    <div class="input-group-addon currency-hint"> GBP £</div>
                    <input type="text" class="form-control" id="daily_project_budget" value="" tabindex="15" name="daily_project_budget" maxlength="10"  autocomplete="off" placeholder="Type your amount here...">
                    <div class="input-group-addon day-price">/day</div>
                </div>
            </div>
        </div>
        <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
            <input type="radio" name="rate_variable" id="negotiable" tabindex="16" value="negotiable">
            <label for="negotiable"><span class="radio-circle"><span></span></span>I don't know my budget (Experts will see "Negotiable" in the Project brief)</label>
        </div>
        <div id="rate_variable_error" class="validation_error error-message" ></div>
    </div>
</div>
<div class="project-overview-step-one review-submit-panel margin-top-30">
    <h4 class="font-20 gilroyregular-bold-font">4. Review & Submit</h4>
    <p class="font-18 margin-top-24">Take a quick look for typos or missing content before submitting to the MeasureMatch team for approval.</p>
    @if($current_method != 'postproject')
    <p class="font-18">By clicking "Finish & Submit" you are reconfirming acceptance of the <a href="javascript:void(0)"  title="Terms of service" data-toggle="modal" data-target="#termsservcies" id="tnc_link">MeasureMatch Terms of Service.</a></p>
    @endif
    <div class="clearfix"></div>
    <input id="submit_project" type="button"  tabindex="17" value="Finish & Submit" class="btn standard-btn finish-submit-btn margin-top-20" />
</div>
