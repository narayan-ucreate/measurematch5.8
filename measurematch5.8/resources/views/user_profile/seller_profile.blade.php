@extends('layouts.layoutedit')
@section('content')
@php
$userId = $auth_user->id;
$first_time_logged_in_status = $auth_user->first_time_logged_in;
@endphp
<nav class="navbar navbar-default navbar-fixed-top topnav @if($first_time_logged_in_status ==0){{'get-started-expert'}}@endif">
 <div class="container-fluid">
   <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
     <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
     <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
   </a><?php echo getUserType(); ?>
   <div class="pull-right top-menu">
     <ul class="nav navbar-nav support-menu expert-support-menu">
        <li><a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span></a>@include('htmlpanels.mm_support_panel')</li>
     </ul>
     <div class="navbar-header">
       <ul class="nav navbar-nav navbar-right hide-small-screen">
         <li class="active username_li"><span class="dropdown"> <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('expert/profile-summary',[],$ssl) }}">{{ucwords($auth_user->name .' '.$auth_user->last_name)}}</a> <span class="caret"></span> </button>
           <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
             <li><a href="{{ url('expert/settings',[],$ssl) }}">Settings</a></li>
             <?php if (isset($auth_user->id)) { ?><li><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li><?php } ?>
           </ul> </span></li>
                </ul>
            </div>
        </div>
 </div>
    </nav>
    <div id="wrapper" class="active profile-page-content expert-profile-view">
      <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar custom-left-sidebar">
                @include('sellerdashboard.sidemenu')
            </div>

          <div class="col-md-9 rightcontent-panel">
            <div class="theiaStickySidebar">
               @if(Session::has('expertRefeeralMessage'))
                 <div class="expertRefeeralMessage">
                   {{ Session::get('expertRefeeralMessage') }}
                 </div>
                 @endif
                 <div class="col-md-12">
                 @php
                 $calculated_profile_percentage = calculateProfileCompletePercentageStatus();
                 @endphp
                 @if($calculated_profile_percentage['basic_profile_completness']==TRUE && $user_profile['admin_approval_status']!=config('constants.APPROVED'))
                 <div class="alert alert-info fade in alert-dismissable">
                    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <strong>Thank you!</strong> The core elements of your profile are now complete. Please add the remaining items in your profile and expect to hear from us shortly.
                 </div>
                @endif
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-left-section">
              <div class="expert-left-side">
                 @if(isset($user_profile['name']) || isset($user_profile['last_name']))
                    <h3 class="expert-name gilroyregular-bold-font">{{ ucwords($user_profile['name'].' '.$user_profile['last_name']) }}</h3>
                    @endif
                    <div class="modal-body">
                        <span id="imageerrormsg" class="error-message"></span>
                        <input type="hidden" id="image_url" name="image_url"/>
                        <input type="hidden" id="zoomlevel" name="zoomlevel"/>
                        <input type="hidden" id="cropitimage" name="cropitimage"/>
                        <input type="hidden" value="{{url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl)}}" id="default_avatar">
                        <input type="hidden" value="" id="default_avatar_base64">
                        <form method="post" name="uploadpic" id="uploadpic" action="{{url('/sellerlogo',[],$ssl)}}" enctype="multipart/form-data">
                        <div class="profile-image upload_add user_pic">
                           <span id="blah1" class="profilespan"></span>
                             {{ csrf_field() }}
                           <input type="hidden" name="base64image" id="base64image" value=""/>
                           <img class="uploaded_img" id="blah" src="javascript:void(0)" alt="" style="display:none;" />
                           <span class="fileinput-new uploaded-profile-pic" id="show_image_pop">
                                <div style="background-image:url({{ getImage($user_profile['user_profile']['profile_picture'],$ssl) }});"
                                    alt="profile-image" class="profilepicture" >
                                </div>
                                <img class="camera_small" alt="Camera_small" src="{{ url('images/Camera_small_demo.png',[],$ssl) }}">
                                <span>upload photo</span> 
                           </span>
                           <span class="fileinput-exists" id="show_change_image_pop" style="display:none" >
                              @if ((!empty($user_profile['user_profile']['summary'])) 
                                    && (!empty($user_profile['user_skills'])) 
                                    && (!empty($user_profile['user_employment_detail']))) { ?>
                                 <img class="camera_small" alt="Camera_small" src="{{ url('images/Camera_small_demo.png',[],$ssl) }}">
                              @endif
                           </span>
                        </div>
                        </form>
                    </div>
                    <div class="summary-section summary-info-seller">
                        <span class="error-message display-block text-center" id="image_error">@if(Session::has('image_error')){{Session::get('image_error')}}@endif</span>
                        <div class="edit-profile-section edit_view">
                            <div class="remove_view">
                                    @include('user_profile.expert_profile_left_section_view')
                            </div>
                        </div>
                        <div style="display:none" id="edit_profile">
                            <div class="education-section new-edit-section">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-innner-content">
                                       <form method="post" name="expert_basic_information" id="expert_basic_information"
                                             action="{{url('expertbasicinformation',[],$ssl)}}" enctype="multipart/form-data">
                                        <div class="modal-body">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="user_profile_id" id="user_profile_id"
                                                       value="@if(isset($user_profile['user_profile']['id'])
                                                       && !empty($user_profile['user_profile']['id']))
                                                       {{$user_profile['user_profile']['id']}}@endif">
                                                <div class="input-bx select-box">
                                                    <label>Consultant type <span class="notification_star">*</span></label>
                                                    <select  class="selectpicker" name="expert_type" id="expert_type">
                                                        <option value="">Choose</option>
                                                        <option @if($user_profile['user_profile']['expert_type'] == 'Independent')
                                                                 selected="selected" @endif value="Independent">
                                                                 Independent Consultant
                                                        </option>
                                                        <option @if($user_profile['user_profile']['expert_type'] == 'Consultancy')
                                                                 selected="selected" @endif value="Consultancy">Consultancy/Agency
                                                        </option>
                                                    </select>
                                                    <span id="validation_error_expert_type" class="validation_success">&nbsp;</span>
                                                </div>
                                                <div class="input-bx select-box" style="@if($user_profile['user_profile']['expert_type']=='Independent')
                                                     display: none; @endif" id="number_of_experts_div">
                                                    <label>How many Experts work for your consultancy? <span class="notification_star">*</span></label>
                                                    <select  class="selectpicker" name="experts_count_lower_range" id="experts_count">
                                                        <option value="">Choose</option>
                                                        <option value="2" @if($user_profile['user_profile']['experts_count_lower_range'] == 2)
                                                                selected="selected" @endif>2-10 Experts</option>
                                                        <option value="11" @if($user_profile['user_profile']['experts_count_lower_range'] == 11)
                                                                selected="selected" @endif>11-30 Experts</option>
                                                        <option value="31" @if($user_profile['user_profile']['experts_count_lower_range'] == 31)
                                                                selected="selected" @endif>31-99 Experts</option>
                                                        <option value="101" @if($user_profile['user_profile']['experts_count_lower_range'] == 101)
                                                                selected="selected" @endif>100+ Experts</option>
                                                    </select>
                                                    <span id="validation_error_experts_count_lower_range" class="validation_success">&nbsp;</span>
                                                </div>
                                                <div class="input-bx">
                                                    <label>Your MeasureMatch profile title <span class="notification_star">*</span></label>
                                                    <input tabindex="1" type="text" value="@if(isset($user_profile['user_profile']['describe'])
                                                           && !empty($user_profile['user_profile']['describe'])){{$user_profile['user_profile']['describe']}}@endif"
                                                           maxlength="60" class="input-error-message" id="describe" name="describe" placeholder="e.g. Enterprise Cloud Consultant">
                                                    <span id="validation_error_describe" class="validation_success">&nbsp;</span>
                                                </div>
                                                <div class="input-bx custom-dropdown-style">
                                                    <label>Your location (city) <span class="notification_star">*</span></label>
                                                    <input tabindex="2" value="@if(isset($user_profile['user_profile']['current_city'])
                                                           && !empty($user_profile['user_profile']['current_city']) 
                                                           ||isset($user_profile['user_profile']['country']) 
                                                           && !empty($user_profile['user_profile']['country'])){{$user_profile['user_profile']['current_city'] . ', ' . $user_profile['user_profile']['country']}}@endif"
                                                           type="text" maxlength="40" class="input-error-message"
                                                           id="expert_profile_city" name="city" autocomplete="off">
                                                    <input type="hidden" id="expert_profile_city_name" name="city_name"
                                                           value="@if(isset($user_profile['user_profile']['current_city'])
                                                           && !empty($user_profile['user_profile']['current_city']))
                                                           {{$user_profile['user_profile']['current_city']}}@endif">
                                                    <input type="hidden" id="expert_profile_country_name" name="country_name"
                                                           value="@if(isset($user_profile['user_profile']['country'])
                                                           && !empty($user_profile['user_profile']['country']))
                                                           {{$user_profile['user_profile']['country']}}@endif">
                                                    <div id="expert_profile_tags" class="dropdown"></div>
                                                    <span id="validation_error_location" class="validation_success">&nbsp;</span>
                                                </div>
                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('city') }}
                                                        </strong> 
                                                    </span>
                                                @endif
                                                <div class="input-bx expert-rate">
                                                    <label>Your Daily Rate <span class="notification_star">*</span>
                                                        <a class="info-icon info-icon-left-arrow">?
                                                            <span>Don't fret. This number will not be shown to MeasureMatch Clients. It's for internal use only.
                                                            </span>
                                                        </a>
                                                    </label>
                                                    @if ($errors->has('currency'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('currency') }}</strong> 
                                                        </span>
                                                    @endif
                                                    <div class="input-group expert-day-rate-field">
                                                        <div class="input-group-addon">$</div>
                                                        @php $daily_rate = isset($user_profile['user_profile']['daily_rate']) && !empty($user_profile['user_profile']['daily_rate']) ? $user_profile['user_profile']['daily_rate'] : '';

                                                        @endphp
                                                        <input tabindex="3" onkeypress="javascript:return isInputKeyNumber(event)" value="{{$daily_rate}}" type="text" placeholder="e.g. 400" maxlength="4" size="30"  name="daily_rate" id="daily_rate"  />
                                                        <input type="hidden" name="currency" value="$" id="currency">
                                                        <div class="input-group-addon dayrate-lbl">/day</div>
                                                    </div>
                                                    <span id="validation_error_daily_rate" class="validation_success">&nbsp;</span>
                                                    @if ($errors->has('daily_rate')) <span class="help-block"> <strong>{{ $errors->first('daily_rate') }}</strong> </span> @endif
                                                </div>
                                                <div class="input-bx select-box">
                                                    <label>Location preference <span class="notification_star">*</span></label>
                                                    <input type="hidden" name="rate_variable" value="$" id="rate_variable">
                                                    <select  class="selectpicker" name="remote_id" id="remote_work">
                                                        <option value="">Choose</option>
                                                        <option @if($user_profile['user_profile']['remote_id'] == '1')
                                                                 selected="selected" @endif value="1" id="1">
                                                                 Only work remotely
                                                        </option>
                                                        <option @if($user_profile['user_profile']['remote_id'] == '2')
                                                                 selected="selected" @endif value="2" id="2">
                                                                 Only work on site
                                                        </option>
                                                        <option @if($user_profile['user_profile']['remote_id'] == '3')
                                                                 selected="selected" @endif value="3" id="3">
                                                                 Can work remotely and on site
                                                        </option>
                                                    </select>
                                                    <span id="validation_error_remote_id" class="validation_success">&nbsp;</span>
                                                </div>
                                                @if ($errors->has('remote_work')) <span class="help-block"> <strong>{{ $errors->first('remote_work') }}</strong> </span> @endif
                                                <input tabindex="4" type="submit" value="Done" class="blue-bg-btn standard-btn cancel_profile" name="editsummary" id="editsummary">
                                                {{--<a href="javascript:void(0)" class="add-grey-btn cancel_profile gilroyregular-bold-font cancel-btn-text">Cancel</a>--}}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 profile-content-main">
                    <div class="profile-content-section">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li role="presentation" class="active"><a href="#experience-tab" data-section="profile-summary" aria-controls="home" role="tab" data-toggle="tab" id="summary_a">Summary</a></li>
                            <li role="presentation"><a href="#skills-tab" aria-controls="profile" data-section="profile-skills" role="tab" data-toggle="tab" id="skill_a">Skills</a></li>
                            <li role="presentation"><a href="#workhistory-tab" aria-controls="profile" data-section="work-history" role="tab" data-toggle="tab" id="workhistory_a">Work History</a></li>
                            <li role="presentation"><a href="#education-tab" aria-controls="messages" role="tab"  data-section="profile-education"  data-toggle="tab" id="education_a">Education</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content margin-b-17">
                           <div role="tabpanel" class="tab-pane fade in active profile-summary" id="experience-tab">
                                @if ($expert_info_section == 'profile-summary')
                                    @include('user_profile.expert_profile_bio')
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade profile-skills" id="skills-tab">
                                @if ($expert_info_section == 'profile-skills')
                                    @include('user_profile.expert_profile_skills')
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade work-history" id="workhistory-tab">
                                @if ($expert_info_section == 'work-history')
                                    @include('user_profile.expert_experience_detail')
                                 @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade profile-education" id="education-tab">
                                @if ($expert_info_section == 'profile-education')
                                    @include('user_profile.expert_educational_details')
                                @endif
                            </div>
                        </div>
                    </div>
                  </div>
<?php
$bio_class = "";
$skill_class = "";
$education_class = "";
$employer_class = "";
$course_class = "";
$profile_completion_overlay = "display:none;";
if ((empty($user_profile['user_certification'])) && (!empty($user_profile['user_education_detail'])) && (!empty($user_profile['user_profile']['summary'])) && (areSkillsAndToolsAdded($userId)==True)  && (!empty($user_profile['user_employment_detail']))) {
    $bio_class = "complete";
    $skill_class = "complete";
    $education_class = "complete";
    $employer_class = "complete";
    $course_class = "active";
} else if ((empty($user_profile['user_education_detail'])) && (!empty($user_profile['user_profile']['summary'])) && (areSkillsAndToolsAdded($userId)==True)  && (!empty($user_profile['user_employment_detail']))) {
    $bio_class = "complete";
    $skill_class = "complete";
    $education_class = "count active";
    $employer_class = "complete";
    $course_class = "complete";
} else if ((empty($user_profile['user_employment_detail'])) && (areSkillsAndToolsAdded($userId)==True) && (!empty($user_profile['user_profile']['summary']))) {
    $bio_class = "complete";
    $skill_class = "complete";
    $education_class = "count";
    $employer_class = "count active";
    $course_class = "count";
    $profile_completion_overlay = "display:block;";
} else if ((areSkillsAndToolsAdded($userId)==True)  && (!empty($user_profile['user_profile']['summary']))) {
    $bio_class = "complete";
    $skill_class = "count active";
    $education_class = "count";
    $employer_class = "count";
    $course_class = "count";
    $profile_completion_overlay = "display:block;";
} else if (empty($user_profile['user_profile']['summary'])) {
    $bio_class = "count active";
    $skill_class = "count";
    $education_class = "count";
    $employer_class = "count";
    $course_class = "count";
    $profile_completion_overlay = "display:block;";
}
//echo "<pre>"; print_r($user_profile);
if (!empty($user_profile['user_profile']['summary'])) {
    $bio_class = "complete";
}
if (areSkillsAndToolsAdded($userId)== TRUE){
    $skill_class = "complete";
}
if (!empty($user_profile['user_employment_detail'])) {
    $employer_class = "complete";
}
if (!empty($user_profile['user_education_detail']) && !empty($user_profile['user_certification'])) {
    $education_class = "complete";
}
$calculated_profile_percentage['profileCompletePercentage'] = $calculated_profile_percentage['profileCompletePercentage'] . '%';
if ($first_time_logged_in_status == 0) {
    ?>
    <div class="welcome-popup expert-welcome-popup profile-page-popup profile-picture-popup">
        <div class="modal-dialog">
            <div class="expert-left-side expert-right-side">
                <div class="profile-completion">
                    <img class="champagne-bottle" src="{{ url('images/champagne-bottle.svg',[],$ssl)}}" />
                    <h3 class="gilroyregular-bold-font">Nearly there!</h3>
                    <div class="describe-welcome-detail">Please complete these core elements of<br /> your profile to get it ready for review:</div>
                    <ol>
                        <li>Profile photo</li>
                        <li>Answer "Your MeasureMatch profile title?"</li>
                        <li>City & Country </li>
                        <li>Daily Rate (for MeasureMatch use only; not displayed)</li>
                        <li>Work Location preference(s)</li>
                        <li>Your Story</li>
                        <li>Skills</li>
                    </ol>
                    <input class="standard-btn btn-small" type="button" data-status='<?php echo $userId; ?>' id="get_started" name="get_started" value="Let's go">
                </div>
            </div>
        </div>
    </div>
    <div class="profile-completition-overlay" id="profile-completition-overlay" style="{{ $profile_completion_overlay  }};"></div>
    <?php
} elseif ($calculated_profile_percentage['profileCompletePercentage'] != '100%') {
    ?>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-completion-wrap">
        <div class="expert-right-side">

            <div class="profile-completion expert-profile-complete-panel">
              <h3 class="font-18 gilroyregular-bold-font">Profile completion</h3>
            </div>
            <div class="profile-completion-steps">
                <div data-id="summary-right" class="step {{ $bio_class }}">
                    <h5 class="font-16">Please complete these core elements of your profile to get it ready for review:</h5>
                     <ol>
                        <li class="{{ $core_elements_missing['profile_photo'] }}">Profile photo</li>
                        <li id="missing_describe" class="{{ $core_elements_missing['profile_title'] }}">Answer "Your MeasureMatch profile title</li>
                        <li id="missing_city_name__country_name" class="{{ $core_elements_missing['city_country'] }}">City & Country</li>
                        <li id="missing_daily_rate" class="{{ $core_elements_missing['daily_rate'] }}">Daily Rate (for MeasureMatch use only; not displayed)</li>
                        <li id="missing_remote_id" class="{{ $core_elements_missing['remote_id'] }}">Work Location preference(s)</li>
                        <li class="{{ $core_elements_missing['summary'] }}">Your Story</li>
                        <li id="skills_flag" class="{{ $core_elements_missing['skills'] }}">
                        @php
                            $total_skills_number = $total_user_skills + $total_user_tools;
                            $minimum_skills_required = config('constants.MINIMUM_SKILLS_AND_TOOLS_COUNT_FOR_PROFILE_COMPLETEION');
                        @endphp
                        Skills <span class="skills-details {{ $core_elements_missing['skills_details'] }}">
                            (<span class="total-skills">{{ $total_skills_number }}</span>/{{ $minimum_skills_required }})
                                <a class="info-icon info-icon-left-arrow">?
                                    <span>Add
                                        <strong class="total-skills-left">
                                            {{ $minimum_skills_required - $core_elements_missing['total_skills'] }}
                                        </strong>
                                        more Skill(s) or Tool(s) to complete your profile.
                                    </span>
                                </a>
                            </span>
                        </li>
                    </ol>
                 </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>
</div>
</div>
    </div>
</div>
<a href="javascript:void(0)" data-toggle="modal" data-target="#zoompopup" id="zoompopupshow"></a>
<div class="modal lightbox-design lightbox-design-small fade add_current_role_popup car_edit_upload profilezoompopup" id="zoompopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close btn" data-dismiss="modal" aria-hidden="true"><img src="{{ url('/images/cross-black.svg',[],$ssl) }}" /></button>
            </div>
            <div class="modal-body">
                <div class="carupload_popup_head">
                    <h3 class="gilroyregular-bold-font">Adjust Photo</h3>
                    <p>Drag the image to adjust position.</p>
                </div>
                <div id="image-cropper" class="img-crop-section">
                    <div class="cropwrapper">
                        <div class="preview-wrapper">
                            <div class="image-cropper">
                                <div class="cropit-preview"></div>
                            </div>
                        </div>
                    </div>
                   <div class="carupload_popup_head profile-update change-photo-section">
                   <div class="col-md-12 img-rotate-block">
                    <div class="rotation-btns">
                      <span class="rotate-img rotate-cw-btn"><img src="{{ url('/images/rotateIcon.svg',[],$ssl) }}" />Rotate image</span>
                    </div>
                   </div>
                   <div class="range_block">
                     <div class="range-slider user-img slider-wrapper">
                        <span aria-hidden="true" class="glyphicon glyphicon-user zoom-xs"></span>
                        <input  tabindex="63" type="range" id="rangedupli" class="range-slider__range cropit-image-zoom-input" />
                        <span aria-hidden="true" class="glyphicon glyphicon-user zoom-lg"></span>
                      </div>
                    </div>
                    <div class="popup-btns">
                        <label class="change_car btn-file btn btn-light-white white-btn"><span> Change Image</span>
                        <input  tabindex="64" type="file" id="cropimageadd" class="cropit-image-input" accept="image/*" alt=""/></label>
                        <button data-dismiss="modal" aria-hidden="true" class="savecropimage btn btn-primary">Save</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.footer')
<div class="modal fade profile-page-popup profile-info-popup" id="myModalbio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="">
                <div class="modal-header">
                    <h3 class="gilroyregular-bold-font">Edit Bio informationsss</h3>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade profile-page-popup lightbox-design lightbox-design-small" id="confirm_cancellation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img  alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                </div>
                <div class="modal-body">
                    <h3 class="gilroyregular-bold-font">Do you want to save your changes?</h3>
                    <span id="imageerrormsg" class="error-message"></span>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile-pic-inner">
                        <button id="yesediting" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">Yes</button>
                        <button id="noediting" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
<script> base_url = "{{ url('/',[],$ssl) }}";

    $(window).load(function(){
        buyerRating();
    });

    var bas64url = "{{ url('getbase64',[],$ssl) }}";
</script>
@if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
@if($auth_user->admin_approval_status!=config('constants.APPROVED'))
@include('expert_profile_admin_unapproved_modal')
@endif
@else
@include('expert_profile_incomplete_modal')
@endif
@endsection
