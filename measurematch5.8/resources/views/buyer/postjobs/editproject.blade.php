@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
    <div id="page-content-wrapper">
        <div class="page-content inset postproject-panel">
            <a href="javascript:void(0)" id="back_button" class="gilroyregular-bold-font go-back-btn font-14" onclick="goBackButton()" title="Go back">Go back</a>
            <div class="row">
                <div class="col-md-3 leftSidebar">

                        @include('buyer.sidemenu')

                </div>
                
                <div class="col-md-9 rightcontent-panel">
                    <div class="theiaStickySidebar">
                        <div class="col-lg-8 col-sm-12 col-md-8 col-xs-12 submit-project-brief-panel">
                          <div class="heading-container">
                            <h3 class="font-24 gilroyregular-bold-font">Edit your Project</h3>
                            <span class="help-link font-16">Edit the details of your Project brief here.</span>
                          </div>
                            <form method="post" enctype="multipart/form-data" id="submit_project_form" name="submit_project_form" action="{{url('project/save', [], $ssl)}}">
                            <div class="project-overview-step-one margin-top-24">
                                  {{ csrf_field() }}
                                <input type="hidden" id="project_id" name="project_id" value="{{$project->id}}">
                                <input type="hidden" id="publish" name="publish" value="{{$project->publish}}">
                                <h4 class="font-20  gilroyregular-bold-font">1. Project brief overview</h4>
                                <div class="input-box project-title-box margin-top-28">
                                    <label>What is the title of this Project? <a href="javascript:void();" class="help-info-icon gilroyregular-font mob-help-icon"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>Here you should include a top-line, punchy title that communicates the Project clearly.</span></a></label>
                                    <textarea id="title" name="title"  rows="3" maxlength="150" class="input-field-design font-16" placeholder="A top-line, punchy title describing what you need help with." cols="50">{{$project->job_title}}</textarea>
                                     <div id="title_validation_error" class="error-message"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-24 describe-project-field">
                                    <label>Please describe this Project.
                                        <a href="javascript:void();" class="help-info-icon gilroyregular-font"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>To give you an example, here is an Project description taken from a previous brief: We would like to optimize our analytics setup in order to better understand the traffic on our Booking pages. At the moment, it is difficult to analyse properly what the users do on the page.</span></a></label>
                                    <textarea id="description"  name="description" maxlength="5000" rows="3" class="input-field-design font-16" placeholder="Here, explain in some detail the problem or opportunity you’re looking to address for you, your team, your business and - if relevant - your client(s)." cols="50">{{$project->description}}</textarea>
                                     <div id="description_validation_error" class="error-message"></div>
                                </div>
                                
                                <div class="input-box project-title-box  deliverable-panel margin-top-24">
                                    <label>Break down the deliverables you’re expecting for this Project. <a href="javascript:void();" class="help-info-icon gilroyregular-font"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>Here are some example deliverables to help spark some ideas:
                                        <ul>
                                          <li>Create tracking specifications for websites and marketing campaigns</li>
                                          <li>Setup marketing dashboards and reports to assist analysis</li>
                                          <li>Analyse how campaigns are performing and how to improve them</li>
                                        </ul>
                                    </span></a>
                                    </label>
                                      @if(_count($deliverables))
                                      @foreach($deliverables as $key=>$deliverable)
                                      <div class="remove-filed-btn"> @if($key!=0) <a href="javascript:void(0);" class="remove_button" title="Remove">X</a>@endif
                                      <textarea  name="deliverables[]" maxlength="2000" rows="3" class="input-field-design deliverables font-16" placeholder="Describe a deliverable here." cols="50">{!! $deliverable->deliverable !!}</textarea></div>
                                       @endforeach
                                    @endif
                                    <div class="clearfix"></div>
                                    <div id="deliverables_validation_error" class="error-message"></div>
                                </div>
                                <div class="input-box margin-bottom-20"> <a  id="add-deliverable-link"  class="add-another-deliverable gilroyregular-semibold pull-right font-18" href="javascript:void(0);" title="Add another deliverable">Add another deliverable</a> </div>
                                <div class="input-box project-title-box margin-top-32 attachment-box">
                                    <label class="font-18">(Optional) Would you like to attach a file to your Project brief? <a href="javascript:void();" class="help-info-icon gilroyregular-font"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>If there are any files, images or presentations you'd like to add to the brief, here's the place to do it.</span></a></label>
                                    <input type="hidden" name="attachments_from_db" id="attachments_from_db" value="{{$project->upload_document}}">
                                    <div class="file-upload attach-file-btn"> <span>Attach file(s)</span>
                                        <input type="file" name="attachments[]" title=" " id="attachments" class="upload">
                                    </div>
                                    @php
                                    $uploaded_documents = [];
                                    if($project->upload_document){
                                        $uploaded_documents = json_decode($project->upload_document, TRUE);
                                    }
                                    @endphp
                                      <div id="uploaded_files">
                                        @if(_count($uploaded_documents))
                                        @foreach($uploaded_documents as $document)
                                        <br />
                                        <div class="remove-attachement">
                                            <a href="javascript:void(0);" class="remove_button" title="Remove">X</a>
                                            <span class="attached-file"><a target="_blank" href="{{$document}}">{{getFileName($document)}} </a></span>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div id="upload_validation_error" class="error-message"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32 attachment-box">
                                    <label class="font-18">(Optional) Would you like to hide your company name from the brief? </label>
                                    <div class="check-box-design font-16">
                                        <input id="hide_company_name" type="checkbox" @if($project->hide_company_name==1){{'checked'}}@endif name="hide_company_name" value="1" default_type="@if(!empty($buyer_detail[0]['type_of_organization_id']) && $buyer_detail[0]['type_of_organization_id']){{$buyer_detail[0]['type_of_organization_id']}}@endif" >
                                        <label class="font-16" for="hide_company_name"><span><span></span></span>Yes, I’d like to hide my company name</label>
                                    </div>
                                    <div id="type_of_org_block" class="select-box choose-org-type-dropdown" style="@if($project->hide_company_name !=1){{'display:none;'}} @endif">
                                        <label>Choose your organization type </label>
                                        <select name="type_of_organization" id="type_of_organization"  placeholder="Choose organization type" class="selectpicker select-dropdown-style col-md-6 col-xs-12" >
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
                            <div class="project-overview-step-one margin-top-64">
                                <h4 class="font-20 gilroyregular-bold-font">2. Expert specifics</h4>
                                <div class="input-box preference-location-panel margin-top-24">
                                    <label class="font-18">What is your location preference...</label>
                                    <div class="input-bx select-box rate-section col-md-12">
                                        <div class="radio-button-style">
                                            <input type="radio" name="work_location" value="3"  @if($project->remote_id==3){{'checked'}}@endif id="work_location" >
                                            <label for="work_location"><span class="radio-circle"><span></span></span>Expert can work on-site or remotely</label>
                                        </div>
                                    </div>
                                    <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
                                        <input type="radio" name="work_location" id="on_site" value="2"  @if($project->remote_id==2){{'checked'}}@endif >
                                        <label for="on_site"><span class="radio-circle"><span></span></span>Expert must work on-site</label>
                                    </div>
                                    <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
                                        <input type="radio" name="work_location" id="remotely" value="1"  @if($project->remote_id==1){{'checked'}}@endif>
                                        <label for="remotely"><span class="radio-circle"><span></span></span>Expert must work remotely</label>
                                    </div>
                                    <div  id="work_location_error" class="validation_error error-message"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32">
                                    <label>(Optional) Which tools and/or technologies do you need help with?</label>
                                    <div class="project-subtitle-box">{{ config('constants.PRESS_ENTER_TOOL') }}</div>
                                    <div class="add-tools-button-block" >
                                        <div class="add-more-tools">
                                          @php $all_tools=[];
                                            if (!empty($tools)) {
                                                foreach ($tools as $tool) {
                                                    $all_tools[]=$tool->name;
                                                    echo'<span class="skill-button">' . trim($tool->name) . '<a class="black_cross_link" href="javascript:void(0)"><img src=' . url("images/black_cross.png", [], $ssl) . ' alt="black_cross" class="black_cross" /></a></span>';
                                                }
                                                $tools = is_array($all_tools) ? implode(',', $all_tools) : '';
                                                $all_tools = rtrim($tools, ", \t\n");
                                            } else {
                                                $all_tools = '';
                                            }
                                            @endphp
                                        </div>
                                        <input type="text"  id="add_tools_manually"   class="skill-input input-field-design font-16"  autocomplete="off" value=""  placeholder="E.g. Google Analytics, Adobe Target, Salesforce etc."/>
                                    <input type="hidden"  id="manual_tools"  value="{{$all_tools}}"  name="tools" />
                                    </div>
                                    <div class="error-message validation_error_add_tools"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32 location-dropdown-style">
                                    <label>(Optional) Which advisory and/or solution skills do you require?</label>
                                    <div class="project-subtitle-box">{{ config('constants.PRESS_ENTER_SKILL') }}</div>
                                    <div class="add-skills-button-block" >
                                        <div class="add-more-skills">
                                            @php $all_skills=[];
                                            if (_count($skills)) {
                                                foreach ($skills as $skill) {
                                                $all_skills[]=$skill->name;
                                                echo'<span class="skill-button">' . trim($skill->name) . '<a class="black_cross_link" href="javascript:void(0)"><img src=' . url("images/black_cross.png", [], $ssl) . ' alt="black_cross" class="black_cross" /></a></span>';
                                                }
                                                $skills = is_array($all_skills) ?  implode(',', $all_skills) : '';
                                                $all_skills = rtrim($skills, ", \t\n");
                                            } else {
                                              $all_skills = '';
                                            }
                                            @endphp
                                        </div>
                                        <input  type="text"  id="add_skills_manually"  class="skill-input input-field-design font-16"  autocomplete="off" value="" placeholder="E.g. Data Collection Strategy, Customer Segmentation, Solutions Architecting etc."/>
                                        <input type="hidden"  id="manual_skills"  value="{!! $all_skills !!}"  name="skills" />
                                    </div>
                                    <div class="error-message validation_error_add_skills"></div>
                                </div>
                            </div>

                            <div class="project-overview-step-one margin-top-64">
                                <h4 class="font-20 gilroyregular-bold-font">3. Office Location, Timeline & Budget</h4>
                                <div class="input-box project-title-box margin-top-24 office-location-panel">
                                    <label>Where is your office location? </label>
                                    <div class="col-md-8 account_info new-custom-dropdown-style">
                                     <input id="add_office_location"  name="office_location"  placeholder="E.g. London, UK" value="{{$office_location}}" type="text" maxlength="150" class="input-md-field-design font-16" autocomplete="off">
                                    <div id="office_location_tags" class="dropdown"></div>
                                    </div>
                                    <div id="office_location_error" class="validation_error error-message" ></div>
                                </div>

                                <div class="input-box project-title-box margin-top-32">
                                    <label class="font-18">When would you like a MeasureMatch Expert to start working on your project?</label>
                                        <div class="col-md-8 project-start-date">
                                        <input id="end_date" name="end_date" type="text" size="45" readonly="readonly" placeholder="Click to add date"   maxlength="150" class="input-md-field-design font-16" /></div>
                                        <input type="hidden" value="{{date('d-m-Y', strtotime($project->job_end_date))}}" id="default_end_date"/>
                                        <div id="end_date_error" class="validation_error error-message" ></div>
                                </div>
                                @if($project->visibility_date)
                                <div class="input-box project-title-box margin-top-32">
                                    <label class="font-18">Project visibility expiry date</label>
                                        <div class="col-md-8 project-start-date">
                                        <input id="project_visibility_date" name="visibility_date" type="text" size="45" readonly="readonly" placeholder="Visibility Expiry Date"   maxlength="150" class="input-md-field-design font-16" /></div>
                                        <input type="hidden" value="{{date('d-m-Y', strtotime($project->visibility_date))}}" id="default_visibility_date"/>
                                        <div id="end_date_error" class="validation_error error-message" ></div>
                                </div>
                                @endif

                                <div class="input-box project-title-box margin-top-32 select-box project-time-field">
                                    <label class="font-18">How much time do you think will be required for this Project?
                                      <a href="javascript:void();" class="help-info-icon gilroyregular-font"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>If you know how long the Project will take to deliver, or have a deadline to work with, add it here. Of course you may have no idea on how long the Project will be, so we have added the field "I don't know" in this case.</span></a>
                                    </label>
                                    <select id="project_duration" name="project_duration"  class="selectpicker select-dropdown-style col-md-8 col-xs-12">
                                        <option value="">Choose</option>
                                        <option value="4" @if(convertDaysToWeeks($project->project_duration)['number_of_days']<= 4) selected='selected' @endif >Less than 1 week</option>
                                        <option value="5" @if(convertDaysToWeeks($project->project_duration)['number_of_days'] == 5) selected='selected' @endif >1 week (5 working day)</option>
                                        @for($i=2;$i<=12;$i++)
                                        <option value="{{(5*$i)}}" @if(convertDaysToWeeks($project->project_duration)['number_of_days'] == 5*$i) selected='selected' @endif>{{$i}} weeks ({{(5*$i)}}  working days)</option>
                                        @endfor
                                        <option value="65" @if(convertDaysToWeeks($project->project_duration)['number_of_days']== 65) selected='selected' @endif >More than 12 weeks</option>
                                        <option value="0" @if(convertDaysToWeeks($project->project_duration)['number_of_days']== 0) selected='selected' @endif >I don't know</option>
                                    </select>
                                      <div id="project_duration_error" class="validation_error error-message" ></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32 select-box project-time-field">
                                    <label class="font-18">Do you have a budget approved for this Project?
                                    </label>
                                    <select id="budget_approval_status" name="budget_approval_status" tabindex="12" class="selectpicker select-dropdown-style col-md-8 col-xs-12">
                                        <option value="">Choose</option>
                                        <option value="1" @if($project->budget_approval_status == 1) selected='selected' @endif >I own/control the budget for investments like these</option>
                                        <option value="2" @if($project->budget_approval_status == 2) selected='selected' @endif >I have access to budget or it has been pre-approved</option>
                                        <option value="3" @if($project->budget_approval_status == 3) selected='selected' @endif >I don't have budget approval; I need proposals first</option>
                                    </select>
                                    <div id="budget_approval_status_error" class="validation_error error-message"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32 select-box project-time-field {{$is_currency_editable}}">
                                    <label class="font-18">Which currency do you intend to pay with?
                                    </label>

                                    <select id="currency" name="currency" tabindex="12" class="selectpicker select-dropdown-style col-md-8 col-xs-12">
                                        @foreach( __('currencies') as $key=>$currency)
                                        <option  @if($project->currency == $key) selected='selected' @endif value="{{$key}}">{{$currency}}</option>
                                        @endforeach
                                    </select>
                                    <div id="currency_error" class="validation_error error-message"></div>
                                </div>
                                <div class="input-box project-title-box margin-top-32 project-budget-panel">
                                    <label class="font-18">What is your budget for this Project? <a href="javascript:void();" class="help-info-icon gilroyregular-font mob-help-icon"><img src="{{url('images/hover-help.svg',[],$ssl)}}" alt="hover-help" /><span>You may not know the exact budget of the Project, so we have added an option, "I don't know my budget". This will show as "Negotiable" in the Project brief to Experts.</span></a></label>
                                    <input type="hidden" id="rate" value="{{number_format($project->rate)}}" >
                                    @php
                                    $rate_variable=$project->rate_variable;
                                    if($project->rate_variable =='fixed'){
                                    $rate=number_format($project->rate);
                                    }else if($project->rate_variable =='daily_rate'){
                                    $rate=(number_format($project->rate));
                                    }else{
                                    $rate="";
                                    }
                                    @endphp
                                    <div class="input-bx select-box rate-section col-md-12 margin-top-10">
                                        <div class="radio-button-style">
                                            <input type="radio" name="rate_variable" id="fixed_rate"  value="fixed" autocomplete="off"  @if( $project->rate_variable=='fixed' ) {{'checked'}} @endif >
                                            <label for="fixed_rate"><span class="radio-circle"><span></span></span>I have a Project budget in mind</label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div id="project_budget_input" class="form-group col-md-8 col-xs-12 project-budget-input" >
                                            <div class="input-group"  style="@if($project->rate_variable != 'fixed' ) {{'display:none;'}} @endif">
                                                <div class="input-group-addon currency-hint">{{$project->currency}} {{convertToCurrencySymbol($project->currency)}}</div>
                                                <input type="text" id="project_budget" name="project_budget" value="@if($rate_variable=='fixed'){{$rate}}@endif"  maxlength="10" class="form-control"  placeholder="Type your Project budget here...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="radio-button-style option-budget-inputbx input-bx col-md-12 rate-section">
                                        <input type="radio" name="rate_variable" id="daily_rate" value="daily_rate" @if( $project->rate_variable=='daily_rate' ) {{'checked'}} @endif>
                                        <label for="daily_rate">
                                          <span class="radio-circle"><span></span></span>I have a "Day Rate" budget in mind</label>
                                        <div class="clearfix"></div>
                                        <div id="daily_budget_input" class="form-group col-md-8 col-xs-12 project-budget-input project-daybudget-input">
                                             <div class="input-group" style="@if($project->rate_variable != 'daily_rate' ) {{'display:none;'}} @endif">
                                                <div class="input-group-addon currency-hint ">{{$project->currency}} {{convertToCurrencySymbol($project->currency)}}</div>
                                               <input type="text" class="form-control" id="daily_project_budget" value="@if($rate_variable=='daily_rate'){{$rate}}@endif" name="daily_project_budget" maxlength="10"   autocomplete="off" placeholder="Type your amount here...">
                                               <div class="input-group-addon day-price">/day</div>
                                             </div>
                                            </div>
                                    </div>
                                    <div class="radio-button-style option-budget-inputbx input-bx col-md-12">
                                        <input type="radio" name="rate_variable" id="negotiable" value="negotiable" @if($project->rate_variable=='negotiable'){{'checked'}}@endif>
                                        <label for="negotiable"><span class="radio-circle"><span></span></span>I don't know my budget (Experts will see "Negotiable" in the Project brief)</label>
                                    </div>
                                    <div id="rate_variable_error" class="validation_error error-message" ></div>
                                </div>
                            </div>
                            <div class="project-overview-step-one review-submit-panel margin-top-64">
                                <h4 class="font-20 gilroyregular-bold-font">4. Review & Submit</h4>
                                <p class="font-18 margin-top-24">Take a quick look for typos or missing content before submitting to the MeasureMatch team for approval.</p>
                                <p class="font-18">By clicking "Finish & Submit" you are reconfirming acceptance of the <a href="javascript:void(0)" data-toggle="modal" data-target="#termsservcies" id="tnc_link">MeasureMatch Terms of Service.</a></p>
                                <div class="clearfix"></div>
                                <input id="submit_project" type="submit" type="button" value="Finish & Submit" class="btn standard-btn finish-submit-btn margin-top-20" />
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
$project_start_date_from_db =  date('d-m-Y',strtotime($project->job_end_date));
if(strtotime(date('d-m-Y'))> strtotime($project_start_date_from_db))
 $project_start_date_from_db = date('d-m-Y');
@endphp
<script type="text/javascript">
var start_date = "{{$project_start_date_from_db}}";
</script>
@include('include.buyer_mobile_body')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/project.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
