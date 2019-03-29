@extends('layouts.layout')
@php
    $deliverables = $deliverables['details'] ? json_decode($deliverables['details'], 1) : ['deliverables' => []];
    $first_step = $deliverables['step-1-status'] ?? false;
    $second_step = $deliverables['step-2-status'] ?? false;
    $third_step = $deliverables['step-3-status'] ?? false;
    $project_type_name = $project_type == config('constants.PROJECT') ? 'project' : 'package';
@endphp
@section('content')
    <link href="{{ url('css/international-phone-codes.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
    <div class="expert-find-project preview-send-propsoal">
        <div class="send-proposal-wrap">
            <div class="send-proposal-header">
                <div class="row">
                    <div class="send-proposal-header-top">
                    <div class="col-md-8">
                        <h4>Prepare & Send Your Proposal</h4>
                        <div class="breadcrumbs-wrap">
                            <a href="{{route('expertMessage').'?communication_id='.$communication_id}}">Messages with {{$buyer_information->buyer->first_name}} from {{getCompanyFirstName($buyer_information->buyer->company_name)}}</a>
                            <img src="{{url('images/chevron-right.svg',[],$ssl) }}" alt="logo icon" /> <span>Prepare & Send Your Proposal</span>
                        </div>
                    </div>
                    <div class="col-md-4 pull-right text-right margin-top-10">
                        @if($project_type == config('constants.PROJECT'))
                    <input type="button" class="white-button edit-proposal-fields" data-communication-id="{{$communication_id}}" data-project-id='{{$project_id}}'
                           data-buyer-id="{{$project_info['user_id']}}" value="View project details" data-project-type='{{$project_type}}' id="view_project_details">
                    @else
                    <input type="button" class="white-button edit-proposal-fields" data-project-type='{{$project_type}}' data-project-id='{{$project_id}}'
                           data-communication-id='{{$communication_id}}' value="View package details" id="view_project_details">
                    @endif
                    </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="proposal-step-1 @if ($step == 1) active @endif @if ($first_step) complete @endif">
                        <a href="{{route('send-proposal', [$communication_id, 1])}}">
                            <span>Step 1</span>
                            @if (isset($contract_info->id))
                                Update
                            @else
                                Create
                            @endif
                             Proposal
                        </a>
                    </li>
                    <li role="presentation"
                        class="proposal-step-2 proposal-action-tabs @if ($step == 2) active @endif @if ($second_step) complete @endif">
                        <a
                                @if ($first_step)
                                href="{{route('send-proposal', [$communication_id, 2])}}"
                                @endif
                        >
                            <span>Step 2</span>
                            Confirm Personal Info
                        </a>
                    </li>
                    <li role="presentation"
                        class="proposal-step-3 proposal-action-tabs @if ($step == 3) active @endif @if ($third_step) complete @endif">
                        <a
                                @if ($first_step && isset($deliverables['step-2-status']) && $deliverables['step-2-status'] == true))
                                href="{{route('send-proposal', [$communication_id, 3])}}"
                                @endif
                        >
                            <span>Step 3</span>
                            Confirm Business Info
                        </a>
                    </li>
                    <li role="presentation"
                        class="proposal-step-4 proposal-action-tabs @if ($step == 4) active @endif @if ($first_step && $second_step && $third_step) complete @endif">
                        <a
                                @if ($first_step && isset($deliverables['step-3-status']) && $deliverables['step-3-status'] == true))
                                href="{{route('send-proposal', [$communication_id, 4])}}"
                                @endif
                        >
                            <span>Step 4</span>
                            Review & Submit
                        </a>
                    </li>
                </ul>
                    </div>
                </div>
                 
            </div>
            <div class="send-proposal-tabs">
                

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane
                         @if ($step == 1)
                            active
                        @endif
                            " id="create_proposal">

                        <div class="send-proposal-section">
                            <div class="col-md-12 info-right-side">
                                <div class="business-address-details registered-company-section">
                                    <div class="from-center">
                                        <div class="input-bx">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>Why are you right for this {{$project_type_name}}?
                                                        <p>Add a personal, persuasive message to {{$buyer_name}} explaining why you (or your team) should be selected for this {{$project_type_name}}.</p>
                                                    </label>
                                                    @php $introduction = ($deliverables['introduction']) ?? '' @endphp
                                                    <textarea name="introduction" value="" class="adding-text" id="introduction" placeholder="Start typing..." data-updated='0'>{{$introduction}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-bx select-box">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <label>In your words, summarise your understanding of the client's needs and requirements.</label>
                                                    @php $summary = ($deliverables['summary']) ?? '' @endphp
                                                    <textarea name="summary" value="" class="adding-text" id='summary'
                                                              placeholder="e.g. I’ve prepared this proposal because you described in our conversations that you need the following things done:&#10;1. You need to setup a measurement framework for your web app…" data-updated='0'>{{$summary}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-bx select-box add-time-period">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label>When can you start working on this {{$project_type_name}}?</label>
                                                    <div class="select-box ">
                                                        @php $job_start_date = ($deliverables['job_start_date']) ?? '' @endphp
                                                        <div class="calander-block">
                                                            <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                                            <input name="job_start_date" id="job_start_date" class="adding-text"
                                                                   value="{{$job_start_date}}" placeholder="Click to add date" type="text" data-updated='0'>
                                                        </div>
                                                    </div>

                                                    <div id="start_time_error" class=" error-message"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>What's your estimated or desired completion date for this {{$project_type_name}}?</label>
                                                    <div class="select-box ">
                                                        @php $job_end_date = ($deliverables['job_end_date']) ?? '' @endphp
                                                        <div class="calander-block">
                                                            <img src="{{url('images/calendar-icon.svg',[],$ssl) }}" alt="logo icon" />
                                                            <input name="job_end_date" id="job_end_date" class="adding-text" value="{{$job_end_date}}"
                                                                   placeholder="Click to add date" type="text" data-updated='0'>
                                                        </div>
                                                    </div>

                                                    <div id="start_time_error" class=" error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-deliverable-section">
                                        <label>Specify the deliverables & fees for this {{$project_type_name}}:</label>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 project-deliverable-listing">
                                            <input type="hidden" id="manage-deliverable-url" value="{{route('manage-deliverable', [$communication_id])}}">
                                            <div class="row deliverable-container">

                                                @include('proposal.list_deliverable')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="from-center">
                                        <div class="input-bx select-box">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label>MeasureMatch Terms</label>
                                                    <div class="check-box-design" id="mm_conditions_block">
                                                        <input  type="hidden" name="stay_safe_confirm"  value="0">
                                                        @php $job_end_date = ($deliverables['job_end_date']) ?? date('d-m-Y') @endphp
                                                        <input id="stay_safe_confirm" type="checkbox" name="stay_safe_confirm" 
                                                               @if (isset($deliverables['stay_safe_confirm']) && $deliverables['stay_safe_confirm'] == 'true') ? {{'checked'}} @endif>
                                                        <label for="stay_safe_confirm" class="stay-safe-label"><span><span></span></span>I have read & I consent to the
                                                            <a href="https://web.measurematch.com/terms-of-service" target="_blank" title="Terms of service" class="links">MeasureMatch Terms of Service</a>
                                                        </label>
                                                        <input id="code_of_conduct" type="checkbox" name="code_of_conduct" value="1" 
                                                        @if (isset($deliverables['code_of_conduct']) && $deliverables['code_of_conduct'] == 'true') ? {{'checked'}} @endif>
                                                        <label for="code_of_conduct" class="code-of-conduct-label"><span><span></span></span>I have read & I consent to the
                                                            <a href="https://web.measurematch.com/code-of-conduct" target="_blank" class="links">MeasureMatch Code of Conduct</a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="terms-container">
                                            @include('proposal.list_terms')
                                        </div>
                                        <div class="input-bx proposal-form-text-block">
                                            <p>Examples of terms other Experts have used include:</p>
                                            <ul>
                                                <li>All time and materials required incrementally to the deliverables defined above will require new scoping and pricing.</li>
                                                <li>The effective start time and date of this contract begins when all required resources have been provided. E.g. systems access</li>
                                                <li>50% of the {{$project_type_name}} budget is due within 5 business days of accepting the proposal.</li>
                                            </ul>
                                        </div>
                                        <div class="input-bx proposal-form-text-block">
                                            <a href="javascript:void(0)" class="btn standard-btn proposal-btn full-width-btn " id="save_step_1" data-redirect-url='{{route('send-proposal', [$communication_id, 2])}}'>Save & continue to next step</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane @if ($step == 2)
                            active
                          @endif" id="confirm_personal_info">
                        <div class="col-md-12 info-right-side personal-info">
                            <form name="update_basic_information" id="update_basic_information" method="post" action="{{ url('update-basic-information',[],$ssl) }}">
                                {{ csrf_field() }}
                                <div class="input-bx select-box add-time-period">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label>First name <span class="notification-star-buyer">*</span></label>
                                            <input id="first_name" autocomplete="off" tabindex="1" maxlength="50" size="30" placeholder="e.g. Tom" type="text" name="first_name" value="{{ ucwords($user_data->name) }}" >

                                        </div>
                                        <div class="col-lg-6">
                                            <label>Last name <span class="notification-star-buyer">*</span></label>
                                            <input id="last_name" autocomplete="off" tabindex="2" maxlength="50" size="30" placeholder="e.g. Ray" type="text" name="last_name" value="{{ ucwords($user_data->last_name) }}" >
                                        </div>
                                    </div>

                                </div>
                                <div class="input-bx">
                                    <label>Your phone number <span class="notification-star-buyer">*</span></label>
                                    <input type="hidden" name="country_code" id="country_code"/>
                                    <input id="phone_num" autocomplete="off" tabindex="4" maxlength="25" size="30" placeholder="e.g. 07965387625" type="text" name="phone_num" value="{{ $user_data->phone_num }}" >

                                </div>
                                <div class="input-bx">
                                    <label>Your date of Birth<span class="notification-star-buyer">*</span>
                                        <p>
                                            <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content='We need to know your date of birth so we can perform the necessary "Know Your Customer" KYC compliance checks.'>Learn why we need to know your date of birth</a>
                                        </p>
                                    </label>
                                    <div class="date-of-birth-block">
                                        <div class="field-container">
                                            <select name="dob_month" class="dob-month selectpicker">
                                                <option value="">Month</option>
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                        <div class="field-container">
                                            <select name="dob_day" class="dob-day selectpicker">
                                                <option value="">Day</option>
                                            </select>
                                        </div>
                                        <div class="field-container">
                                            <select name="dob_year" class="dob-year selectpicker">
                                            </select>
                                        </div>
                                    </div>
                                    <input id="date_of_birth" type="hidden" name="date_of_birth" value="{{ $user_data->date_of_birth != '' ? date('d-m-y', strtotime($user_data->date_of_birth)) : '' }}" >
                                </div>
                                <div class="input-bx">
                                    <input  id="submit_basic_information" tabindex="5" type="submit" class="info-save-btn standard-btn full-width-btn proposal-btn" value="Save & continue to next step" >
                                </div>
                            </form>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane
                            @if ($step == 3)
                            active @endif
                              " id="confirm_business_info">
                        <div class="col-md-12 info-right-side business-information">
                            @include('business_information.business_details')
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane
                            @if ($step == 4)
                            active
                            @endif
                            " id="review_submit_proposal">
                        @if ($step == 4)
                            <div class="listing-details project-detail-block">
                                <div class="row hide server-errors">
                                    @foreach($errors->all() as $single_error)
                                    <div class="col-md-12 validation_error">{{$single_error}}</div>
                                    @endforeach
                                </div>
                                <div class="row margin-bottom-40">
                                    <div class="col-md-3">
                                        <h5  class="font-18 margin-top-5 gilroyregular-semibold">Why you</h5>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="font-16 hover-bg">
                                            {!! ($deliverables['introduction']) ? nl2br($deliverables['introduction']) : '' !!}
                                       </p>
                                   </div>
                                   <div class="col-md-2">
                                       <a href="{{route('send-proposal', [$communication_id, 1])}}?id=introduction" class="white-button edit-proposal-fields">
                                           <i class="fa fa-pencil"></i> Edit
                                       </a>
                                   </div>
                               </div>
                               <div class="row margin-bottom-40">
                                   <div class="col-md-3">
                                       <h5  class="font-18 margin-top-5 gilroyregular-semibold">{{ucfirst($project_type_name)}} goals</h5>
                                   </div>
                                   <div class="col-md-7">
                                       <p class="font-16 hover-bg">
                                           {!! ($deliverables['summary']) ? nl2br($deliverables['summary']) : '' !!}
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{route('send-proposal', [$communication_id, 1])}}?id=summary" class="white-button edit-proposal-fields">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </div>
                                </div>
                                <div class="row margin-bottom-40">
                                    <div class="col-md-3">
                                        <h5  class="font-18 margin-top-5 gilroyregular-semibold">Timelines</h5>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="hover-bg hoverdate">
                                            <div class="col-md-4">
                                                <p class="font-16 margin-bottom-0">{{ucfirst($project_type_name)}} Start Date:</p>
                                                <p class="font-16 gilroyregular-semibold">{{isset($deliverables['job_start_date']) ? date('D d M Y', strtotime($deliverables['job_start_date'])) : ''}}</p>                                                
                                            </div>
                                            <div class="col-md-6">
                                                <p class="font-16 margin-bottom-0">Estimated Completion Date:</p>
                                                <p class="font-16 gilroyregular-semibold">{{isset($deliverables['job_end_date']) ? date('D d M Y', strtotime($deliverables['job_end_date'])) : ''}}</p>
                                            </div>
                                            </div>        
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <a href="{{route('send-proposal', [$communication_id, 1])}}?id=job_start_date" class="white-button edit-proposal-fields"><i class="fa fa-pencil"></i> Edit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="add-deliverable-section">
                                <label class="font-18 margin-bottom-25">Deliverables &amp; Fees</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 project-deliverable-listing">
                                    <div class="row deliverable-container">
                                        @include('proposal.list_deliverable')
                                    </div>
                                </div>
                            </div>
                            @if (isset($deliverables['terms']) && _count($deliverables['terms']))
                                <div class="listing-details terms-detail-block">
                                    <div class="row margin-bottom-40">
                                        <div class="col-md-3">
                                            <h5  class="font-18 margin-0 gilroyregular-semibold">Terms</h5>
                                        </div>
                                        <div class="col-md-9">
                                            <ul>
                                            @foreach($deliverables['terms'] as $key => $term)
                                                <li class="hover-bg">{!! nl2br($term['term']) !!}
                                                    <a href="{{route('send-proposal', [$communication_id, 1])}}?terms={{$key}}" class="white-button edit-proposal-fields">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                </li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="review-proposal-footer">
                                <form method='post' id="send_proposal_form" action="{{route('send-contract', [$buyer_information->buyer_id, Auth::user()->id])}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="job_post" value="{{$project_id}}">
                                    <input type="hidden" name="type" value="{{$project_type}}">
                                    <input type="hidden" name="currency" value="{{$project_info->currency ?? 'USD'}}">
                                    @if (isset($contract_info->id))
                                        <input type="hidden" name="contract_id" value="{{$contract_info->id}}">
                                    @endif
                                    <input class="btn standard-btn font-18 @if (isset($contract_info->id)) update-contract-by-expert @else send-proposal-by-expert  @endif" 
                                           type="button" value="Send
                                           @if (isset($contract_info->id)){{'Updated'}}@endif Proposal to {{getCompanyFirstName($buyer_information->buyer->company_name)}}">
                                </form>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="expert_proposal_screen_pop_ups"></div>
    <div id="one-to-one-chatbox"></div>
    <div id="chat-box"></div>
    <input type="hidden" id="step" value="{{$step}}">
    <input type="hidden" id="edit_proposal_url" value="{{route('send-proposal', [$communication_id, 1])}}">
    <input type="hidden" id="step_1_url" value="{{route('send-proposal', [$communication_id, 1])}}">
    <input type="hidden" id="step_2_url" value="{{route('send-proposal', [$communication_id, 2])}}">
    <input type="hidden" id="step_3_url" value="{{route('send-proposal', [$communication_id, 3])}}">
    <input type="hidden" id="step_4_url" value="{{route('send-proposal', [$communication_id, 4])}}">

@endsection
@section('scripts')
    <script type="text/javascript">
        var index = true;
        var sender_id = "{{Auth::user()->id}}";
        var current_user_type = "{{Auth::user()->user_type_id}}";
        var sender_name = "@php echo ucfirst(Auth::user()->name) . ' ' . ucfirst(substr(Auth::user()->last_name, 0, 1)); @endphp"
        var is_admin_panel_view=false;
        var birth_date = '{{$birth_day}}';
        var birth_month = '{{$birth_month}}';
        var birth_year = '{{$birth_year}}';
    </script>
    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/international-phone-codes.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
    <script src="{{ url('js/proposal.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/date_of_birth_dropdowns.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/chat.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/business_information.js?js='.$random_number,[],$ssl) }}"></script>
   
@endsection
