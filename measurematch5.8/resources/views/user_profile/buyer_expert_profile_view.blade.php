@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="active buyerdesktop_buyer">
        <div id="page-content-wrapper">
            <div class="page-content inset find-service-package-panel">
                <div class="col-md-3 leftSidebar">
                    @include('buyer.sidemenu')
                </div>
                <div class="col-md-9 rightcontent-panel">
                    <div class="theiaStickySidebar">
                        <div>
                            <div class="create-package-panel">
                                <div class="col-md-9 clearfix">
                                    <div class="row">
                                        <div class="expert-profile-container">
                                            <div class="expert-pic col-md-3 col-sm-3 col-xs-12">
                                                <span class="expert-profile-pic" style="background-image:url({{getImage($expert['user_profile']->profile_picture,$ssl)}});"></span>
                                            </div>

                                            <div class="expert-info-container col-md-9 col-sm-9 col-xs-12">
                                               <h3 class="expet-name gilroyregular-semibold font-28">{{$name}}</h3>
                                                 <span class="gilroyregular-semibold expert-job-profile font-18">@if($expert['user_profile']->expert_type == config('constants.EXPERT_TYPE_INDEPENDENT')){{'Independent Consultant'}} @else {{$expert['user_profile']->expert_type }} @endif
                                                    <span class="expert-location font-18">
                                                       @if(!empty($expert['user_profile']->country) && !empty(getCountryFlag($expert['user_profile']->country))) <img src="{{getCountryFlag($expert['user_profile']->country)}}"> @endif
                                                        @if(!empty($expert['user_profile']->current_city)){{$expert['user_profile']->current_city}}@if(!empty($expert['user_profile']->country)){{', '.$expert['user_profile']->country}} @endif @endif
                                                    </span>
                                                </span>

                                                @if(strlen($expert['user_profile']->summary) > config('constants.EXPERT_PROFILE_SUMMARY_LIMIT'))
                                                    <p class="expert-bio font-16" id="truncated_description">{!! mb_substr(ucfirst(trim($expert['user_profile']->summary)),0,config('constants.EXPERT_PROFILE_SUMMARY_LIMIT'))."..." !!}<a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold" id="show_more"> Read more</a></p>
                                                    <p class="expert-bio font-16" id="full_description" style="display: none;"> {!! nl2br(e(  $expert['user_profile']->summary )) !!}<a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold" id="show_less"> Read less</a></p>
                                                @else
                                                    <p class="expert-bio font-16">{!!  nl2br(e( $expert['user_profile']->summary )) !!}</p>
                                                @endif
                                            </div>

                                            <div class="invite-to-discuss-project">
                                                <span class="gilroyregular-semibold font-18">{{ucfirst($expert['name'])}} is bookable <br />on-demand</span>
                                                <a href="javascript:void(0)" id="view_project_overview" class="standard-btn font-16" data-toggle="modal" data-target="#inviteseller">Invite to Discuss a Project</a>
                                            </div>
                                            @php
                                                $results = !$is_vendor ? $expert->userServiceHubs : $vendor_service_hubs;
                                            @endphp

                                            @if(isset($results) && count($results))
                                                <div class="certificate-course-section buyer-feedback-section">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                            <h4 class="font-20 gilroyregular-semibold">Verified by Vendors</h4>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                            @foreach($results as $service_hub)
                                                            <div class="verified-by-vendor-verndor-account">
                                                                <div class="verified-by-vendor-section">
                                                                    <span class="service-hub-logo-img" style="background-image: url({{$is_vendor ? $service_hub->logo : $service_hub->serviceHub->logo}})"></span>
                                                                    <div class="view-more-hub-section">
                                                                        <span class="vendor-title">{{$is_vendor ? $service_hub->name : $service_hub->serviceHub->name}}</span>
                                                                        <a target="_blank" href="{{$is_vendor ? route('service-hubs') : route('vendor-service-hubs-details', $service_hub->serviceHub->id)}}">View Service Hub</a>
                                                                    </div>                                                                    
                                                                </div>                                                               
                                                            </div>   
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="service-package-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold">Service Packages</h4>
                                                    </div>

                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        @if(_count($service_packages))
                                                            @foreach($service_packages as $service_package)
                                                                <a href="{{url('servicepackage/'.$service_package['id'],[],$ssl)}}">
                                                                    <div class="service-package-container">
                                                                        <div class="service-inner-container">
                                                                            <h4 class="font-20 gilroyregular-semibold">{{ucfirst($service_package['name'])}}</h4>
                                                                            <p class="font-14"><strong class="gilroyregular-semibold">Description:</strong>
                                                                                @if(strlen($service_package['description'])> config('constants.EXPERT_PROFILE_SUMMARY_LIMIT'))
                                                                                    {{strip_tags(ucfirst(substr($service_package['description'],0,config('constants.EXPERT_PROFILE_SUMMARY_LIMIT')))).'...'}}
                                                                                @else
                                                                                    {{strip_tags(ucfirst($service_package['description']))}}
                                                                                @endif
                                                                            </p>
                                                                            <p class="expert-skills pull-left font-14"><strong class="gilroyregular-semibold pull-left">Skills provided:</strong>
                                                                                @if(_count($service_package['service_package_tags'] ))
                                                                                    @foreach($service_package['service_package_tags'] as $tags_name)
                                                                                    <lable class="skill-button">{{ucwords($tags_name['tags']['name'])}}</lable>
                                                                                @endforeach @endif
                                                                            </p>
                                                                        </div>

                                                                        <div class="service-package-footer">
                                                                            @php $price=( $service_package['subscription_type']== config('constants.ONE_TIME_PACKAGE'))? number_format($service_package['price']): number_format($service_package['price']).'/month'; @endphp
                                                                            <span class="font-14 pull-left">Guide Budget: <strong class="gilroyregular-semibold">{{'$'.$price}}</strong></span>
                                                                            <p class="pull-right gilroyregular-semibold font-14 view-service-package-link">View Service Package</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                            @else

                                                            <div class="profile-empty-state">
                                                                <strong class="gilroyregular-semibold no-service-package"> {{ucfirst($expert['name'])}} has not created any Service Packages </strong>
                                                                <span  class="font-16 pull-left empty-state-panel margin-top-10 margin-bottom-10">MeasureMatch Experts can further showcase their talent, experience and services by creating Service Packages</span>
                                                                <a href="{{url('servicepackage/types',[],$ssl)}}" class="pull-left browser-service-pacckage-link gilroyregular-semibold font-16 ">Browse Service Packages</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="solution-advisory-skill-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold">Strategic & Advisory Skills</h4>
                                                    </div>

                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        @if(_count($skills))
                                                            @foreach($skills as $skill)
                                                                <span class="skill-button font-16">{{ucfirst($skill->name)}}</span>
                                                            @endforeach
                                                        @else
                                                            <div class="profile-empty-state">
                                                                <span class="font-16 pull-left empty-state-panel">{{ucfirst($expert['name'])}} has not added any strategic or advisory skills.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="solution-advisory-skill-section tool-tec-expertise-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold">Tools & Technology Expertise</h4>
                                                    </div>

                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        @if(_count($tools))
                                                            @foreach($tools as $tool)
                                                                <span class="skill-button font-16">{{ucfirst($tool->name)}}</span>

                                                            @endforeach
                                                        @else
                                                            <div class="profile-empty-state">
                                                                <span class="font-16 pull-left empty-state-panel">{{ucfirst($expert['name'])}} has not added any tools or technology skills.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="work-history-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold">Work History</h4>
                                                    </div>

                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        <div class="clearfix">
                                                            @if(_count($expert['user_employment_detail']))
                                                                @foreach($expert['user_employment_detail'] as $work_history)
                                                                    <div class="work-history-panel">
                                                                        @php
                                                                            $start_date = $work_history->start_date;
                                                                            $end_date = $work_history->end_date;
                                                                        @endphp
                                                                        <h4 class="font-16 gilroyregular-semibold pull-left">{{ucfirst($work_history->position_title)}}</h4>
                                                                        <div class="clearfix"></div>
                                                                        <span class="font-16 work-history-company"> @if($work_history->company_name){{ ucfirst(trim($work_history->company_name))}}@if($work_history->location){{', '.ucfirst(str_replace(",","",$work_history->location) )}}@endif @endif</span>
                                                                        @if(!empty($start_date) && !empty($end_date))
                                                                            @if($work_history->is_current =='TRUE')
                                                                                <span class="pull-right font-14 work-history-date">{{date( "M Y", strtotime ( $work_history->start_date))}} - Present <br />({{ differenceInYearsMonths($start_date, date("Y-m-d"))}})</span>
                                                                            @else
                                                                                <span class="pull-right font-14 work-history-date">{{date( "M Y", strtotime ( $work_history->start_date))}} - {{date( "M Y", strtotime ( $work_history->end_date))}} <br />({{ differenceInYearsMonths($start_date, $end_date)}})</span>
                                                                            @endif
                                                                        @endif
                                                                        <p class="font-14">{{strip_tags(ucfirst($work_history->summary))}}</p>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="profile-empty-state">
                                                                    <span class="font-16 pull-left empty-state-panel">{{ucfirst($expert['name'])}} has not added any work history.</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="certificate-course-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold hidden-sm-heading">Certificates/Courses</h4>
                                                        <h4 class="font-20 gilroyregular-semibold hidden-xs-heading">Certificates/ Courses</h4>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        @if(_count($expert['user_certification']))
                                                            @foreach($expert['user_certification'] as $certificates)
                                                                <div class="certifcate-panel margin-bottom-20">
                                                                    <h4 class="font-16 gilroyregular-semibold pull-left">{{$certificates->name}}</h4>

                                                                    <div class="certifcation-name">
                                                                        <span class="font-16">{{$certificates->institute}}</span>
                                                                    </div>
                                                                    <span class="pull-right certifcation-date"> @if(!empty($certificates->start_date)){{date( "M Y", strtotime ( $certificates->start_date))}}@endif </span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="profile-empty-state">
                                                                <span class="font-16 pull-left empty-state-panel">{{ucfirst($expert['name'])}} has not added any certificates or courses.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="certificate-course-section expert-education-section">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                        <h4 class="font-20 gilroyregular-semibold hidden-sm-heading">College/University</h4>
                                                        <h4 class="font-20 gilroyregular-semibold hidden-xs-heading">College/ University</h4>
                                                    </div>

                                                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                        @if(_count($expert['user_education_detail']))
                                                            @foreach($expert['user_education_detail'] as $education)
                                                                <div class="certifcate-panel margin-bottom-20">
                                                                    <div class="row">
                                                                        <h4 class="font-16 col-md-6 col-sm-6 gilroyregular-semibold pull-left">{{$education->field_of_study}}</h4>
                                                                    </div>

                                                                    <div class="certifcation-name">
                                                                        <span class="font-16">{{$education->name}}</span>
                                                                        <span class="pull-right certifcation-date">{{date( "M Y", strtotime ( $education->start_date))}} - {{date( "M Y", strtotime ( $education->end_date))}}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @else
                                                            <div class="profile-empty-state">
                                                                <span  class="font-16 pull-left empty-state-panel">{{ucfirst($expert['name'])}} has not added any college or university education.</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if(_count($expert['contract_feedbacks']))
                                                <div class="certificate-course-section buyer-feedback-section">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                                            <h4 class="font-20 gilroyregular-semibold">Client Feedback</h4>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                                            @foreach($expert['contract_feedbacks'] as $feedback)
                                                                <div class="certifcate-panel margin-bottom-10">
                                                                    <div class="row">
                                                                        <h4 class="font-16 col-md-9 col-sm-9 gilroyregular-semibold pull-left">
                                                                            @if($feedback['type']=='project')
                                                                                {{ getJob($feedback['job_post_id'],0)}}
                                                                            @else
                                                                                {{ getServicePackageName($feedback['service_package_id'],0) }}
                                                                            @endif
                                                                        </h4>
                                                                    </div>
                                                                    <div class="feedback-buyer-location">
                                                                        @php $buyer=buyerInfo($feedback['buyer_id'])[0]; @endphp
                                                                        <span class="font-16">{{ ucfirst( $buyer->name)}} from @php echo ucfirst(trim($buyer->company_name)).', '.ucfirst($buyer->office_location) @endphp</span>
                                                                    </div>
                                                                    <span class="pull-right certifcation-date col-md-3 col-md-3">{{date('F Y', strtotime($feedback['feedback_time']))}}</span>
                                                                    <span  class="expert-star-rating"><div  id="feedback-{{$feedback['communications_id']}}"  name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx show_rating" expert_rating="{{$feedback['expert_rating']}}"></div></span>
                                                                    <p class="font-14 margin-top-10 clearfix">{!! $feedback['feedback_comment'] !!}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <h2 class="font-20 gilroyregular-semibold text-align-center more-expert margin-top-30 margin-bottom-10">More Amazing Experts:</h2>
                                        <div class="row margin-b-17">
                                            @if(_count($other_experts))
                                                @php $count = 0; @endphp
                                                @foreach($other_experts as $user)
                                                    @php $count++; @endphp
                                                    <div class="col-md-6 col-sm-6 invite-expert-list expert-detail-col">
                                                        <div class="search-result-white-bx">
                                                            <div class="inner-container">
                                                                <a href="{{url('buyer/expert-profile/'.$user['user_id'],[],$ssl)}}"  title="View Profile">
                                                                    <span class="expert-profile-pic" style="background-image:url({{getImage($user['profile_picture'],$ssl)}});"></span>
                                                                </a>
                                                                <h4 class="font-16 gilroyregular-bold-font text-align-center">{{userName($user['user_id'],1)}}
                                                                    <div class="white-theme-tooltip active_project_listing" id="{{$user['user_id']}}"></div>
                                                                </h4>
                                                                <span class="expert-job text-align-center">
                                                                    @if(strlen($user['describe'])>28)
                                                                        {{strip_tags(ucfirst(substr($user['describe'],0,28))).'...'}}
                                                                    @else
                                                                        {{strip_tags(ucfirst($user['describe']))}}
                                                                    @endif
                                                                </span>

                                                                <span class="country-flag text-align-center">
                                                                    @if(!empty($user['country']) && !empty(getCountryFlag($user['country'])))
                                                                        <img src="{{getCountryFlag($user['country'])}}">
                                                                    @endif
                                                                    {{$user['current_city']}}, {{$user['country']}}
                                                                </span>
                                                                <p class="other-ex-bio font-14 clearfix">
                                                                    {{strip_tags(getTruncatedContent($user['summary'], 142))}}
                                                                </p>
                                                            </div>
                                                            <div class="view-profile-block">
                                                                <div class="bottom-white-bx">
                                                                    <a href="{{url('buyer/expert-profile/'.$user['user_id'],[],$ssl)}}"> View Profile</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($count%2 == 0) <div class="clearfix clearline"></div> @endif
                                                @endforeach
                                            @else
                                                <div class="no-result-founded">
                                                    <img src="{{ url('images/empty-state-icon.svg',[],$ssl)}}" alt="empty-state-icon" />
                                                    <h3>Doh! No results</h3>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 invite-expert-list rightSidebar">
                                    <div class="other-expert-panel">
                                        <div class="col-md-3 col-sm-3 col-xs-3 other-ex-pic">
                                            <img  src="{{getImage($expert['user_profile']->profile_picture,$ssl)}}" class="img-responsive" />
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 other-ex-name-profile">
                                            <h4 class="font-14 gilroyregular-semibold">{{$name}}</h4>
                                            <h5 class="font-14 gilroyregular-font">@if($expert['user_profile']->expert_type =='Independent'){{'Independent Consultant'}} @else {{$expert['user_profile']->expert_type }} @endif </h5>
                                        </div>
                                        <span class="expert-profile font-14">@if(!empty($expert['user_profile']->describe)) {{$expert['user_profile']->describe}} @endif</span>
                                        <span class="expert-location font-14"> @if(!empty($expert['user_profile']->country) && !empty(getCountryFlag($expert['user_profile']->country))) <img src="{{getCountryFlag($expert['user_profile']->country)}}"> @endif
                                            @if(!empty($expert['user_profile']->current_city)){{$expert['user_profile']->current_city}}@if(!empty($expert['user_profile']->country)){{', '.$expert['user_profile']->country}} @endif @endif
                                        </span>

                                        <span class="font-14"> @if(!empty($expert['user_profile']['remote_work']->id)){{ expertWorkLocation($expert['user_profile']['remote_work']->id)}}@endif</span>
                                        <a href="javascript:void(0)" class="invite-expert-link font-16 gilroyregular-semibold" data-toggle="modal" data-target="#inviteseller">Invite to Discuss a Project</a>
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

<div class="modal invite-seller-popup send_message_expert_popup lightbox-design lightbox-design-small fade" id="inviteseller" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-innner-content">
                <div class="modal-content discuss-project-popup">
                    <button type="button" class="close close-opacity" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="{{ url('images/cross-black.svg',[],$ssl) }}" alt="cross" /></span></button>
                    <div class="modal-body text-align-center">
                        <form name="invite_expert_to_discuss_project" id="invite_expert_to_discuss_project" method="post" >
                            {{ csrf_field() }}

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="expert-pic">
                                    <span class="expert-profile-pic" style="background-image:url({{getImage($expert['user_profile']->profile_picture,$ssl)}});"></span>
                                </div>
                            </div>
                            @if (_count($projects_list))
                                <h2 class="contract-offer gilroyregular-semibold"> Invite {{ ucfirst($expert->name) }} to discuss a Project</h2>
                                <p>Choose Project to discuss with {{ ucfirst($expert->name) }}:</p>
                                @php
                                    $class_name = '';
                                    $project_count = _count($projects_list);
                                @endphp
                                @if ($project_count !== 1)
                                    @php $class_name = 'hide'; @endphp
                                @endif
                                <select tabindex="1" name="jobTitle" class="selectpicker select-project" id="job_title">
                                    @if ($project_count !== 1)
                                        <option class="abc" value="">Choose Project</option>
                                    @endif
                                    @foreach($projects_list as $key=>$project)
                                        <option
                                                value="{{ $project['id'] }}"
                                                @if ($project_count === 1) selected @endif
                                                @if (false !== $key = array_search($project['id'], $active_projects))
                                                data-in-conversation="1"
                                                data-communication-id="{{$key}}"
                                                @else
                                                data-in-conversation="0"
                                                @endif
                                                data-status="{{$project['publish']}}"> {{ trim($project['job_title'])}} </option>
                                    @endforeach
                                </select>

                                <div class="in-covnersation-section hide common-section">
                                    <p class="font-14 margin-0">You’re already in conversation with {{ ucfirst($expert->name) }} about this project! </p>
                                    <a class="read-more gilroyregular-semibold view-conversation" href="#">View Messages with {{ ucfirst($expert->name) }}</a>
                                </div>

                                <div class="new-message-section hide common-section">
                                    <p>Begin your conversation with {{ ucfirst($expert->name) }} by sending a message. Here is some guidance on what to write:</p>
                                    <ol>
                                        <li>Suggest one or more dates/times to schedule a call or send direct access to your calendar (highly recommended)</li>
                                        <li>Provide any necessary extra context about the project</li>
                                    </ol>
                                    <textarea tabindex="2" name="sendMessage" id="invite_message" placeholder="Start typing here..."></textarea>
                                    <span id="error_upload" class="margin-bottom-10 font-14 margin-0"></span>
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $expert['user_profile']->user_id }}">
                                    <input type="hidden" id="buyer_id" name="buyer_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" id="expert_name" name="expert_name" value="{{ ucfirst($expert->name) }}">
                                    <input type="hidden" id="business_detail_id" value="{{$business_detail_id}}">
                                    <div class="modal-footer">
                                        @if(isset($projects_list) && !empty($projects_list))
                                            <button type="button" id="submit_invite_expert" name="send"  class="btn standard-btn send-message-profile">Send Message</button>
                                        @endif
                                        <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            @else
                                <h2 class="contract-offer gilroyregular-semibold"> Please submit a Project Brief to start a conversation with {{ ucfirst($expert->name) }} </h2>
                                <p>A Project Brief needs to be submitted and approved before you can start a discussion with a MeasureMatch Expert.</p>
                                <a href="{{route('create-project')}}" class="standard-btn font-14 gilroyregular-semibold margin-0">Submit a Project Brief</a>
                                <p class="project-brife-p">
                                    Don’t have time to write a project brief, or not exactly sure what you need help with? Don’t fret. Check out Service Packages.
                                </p>
                                <a href="{{route('find-service-package')}}" class="btn btn-transparent font-14 gilroyregular-semibold margin-0">Browse Service Packages</a><br>

                            @endif
                            <input type="hidden" value="{{config('constants.PROJECT_PENDING')}}" id="pending_status">
                            <input type="hidden" value="{{config('constants.PUBLISHED')}}" id="publish_status">
                            <input type="hidden" value="{{route('buyerProjectMessages', ['project', ''])}}" id="project_url">
                            <input type="hidden" value="{{route('create-project')}}" id="create_project_url">

                            <div class="under-review common-section hide" id="under_review_project">
                                <img width="59" height="79" src="{{ url('images/project-under-review.svg',[],$ssl) }}" alt=""/>
                                <div class="under-review-p">
                                    <h2 class="gilroyregular-semibold">This Project Brief is under review!</h2>
                                    <p>You can start inviting Experts into a conversation once your Project Brief is live. Sit tight! <a href="#" id="learn_more_project" class="read-more gilroyregular-semibold">Learn more</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="make_offer_stage_popups"></div>
    @include('include.buyer_mobile_body')
    <link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
    @include('include.basic_javascript_liberaries')
    <script src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{url('js/business_information.js?js='.$random_number,[],$ssl)}}"></script>
    <script src="{{url('js/buyer_expert_profile_view.js?js='.$random_number,[],$ssl)}}"></script>
    @include('include.footer')
@endsection
