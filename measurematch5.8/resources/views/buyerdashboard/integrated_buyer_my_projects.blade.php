@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="row">
                <div class="col-md-3 leftSidebar">
                        @include('buyer.sidemenu')
                </div>
                <div class="col-md-9 rightcontent-panel">
                    @if(Session::has('project-posted'))
                    <div class="col-md-12">
                        <div class="alert alert-info fade in alert-dismissable">
                            <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            <strong>Thank you for submitting a Project brief!</strong> It is now in queue for review by a MeasureMatch team member.
                        </div>
                    </div>
                    @endif
                    @if(Session::has('pending-project-edited'))
                    <div class="col-md-12">
                        <div class="alert alert-info fade in alert-dismissable">
                            <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            <strong>You have edited your project brief!</strong> It is now in a queue for review by a MeasureMatch team member.
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 my-projects-list-view ul-tab-block">
                        <div class="white-box margin-b-17">
                            <div class="white-box-header">
                                <ul id="nav-tabs-wrapper" class="nav nav-tabs project-list-tab-style">
                                    <li class="active allproject"><a href="#vtab1" data-toggle="tab">My Projects</a></li>
                                    <li class=" pull-right d-none"><a class="submit-project-brief"  href="{{url('project/create',[],$ssl)}}">Submit a new Project brief</a></li>
                                </ul>
                            </div>
                            <div class="white-box-content all-project-list-panel">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="vtab1">
                                        @if(_count($posted_projects) || _count($communication_detail))
                                            @if(isset($posted_projects) && _count($posted_projects))
                                            <div class="project-list-header  d-none">
                                                <div class="col-md-5 col-sm-5 job-title">
                                                    <h4 class="gilroyregular-bold-font">Project Title </h4>
                                                </div>
                                                <div class="col-md-2 col-sm-2 d-none">
                                                    <h4 class="hidden-xs">Views</h4>
                                                </div>
                                                <div class="col-md-2 col-sm-2 d-none">
                                                    <h4 class="hidden-xs">EOIs</h4>
                                                </div>
                                                <div class="col-md-2 col-sm-2 d-none">
                                                    <h4 class="hidden-xs">Conversations</h4>
                                                </div>
                                                <div class="col-md-1 col-sm-1 d-none">
                                                </div>
                                            </div>
                                            <div class="auto-scroll">
                                                @foreach($posted_projects as $posted_project)
                                                <div class="project-list-content project-list-panel project_row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12 project-list-name">
                                                        <h4 class="hidden gilroyregular-bold-font">Project Title</h4>
                                                        <h4 class="project-name"><span class="pull-left" id="unread-project-count-{{$posted_project->id}}">
                                                                @php
                                                                    $total_unread = $posted_project->communicationList->filter(function($communication) 
                                                                    {
                                                                        return $communication->unreadProjectMessageCount->count() > 0;
                                                                    })->map(function($message) 
                                                                    { 
                                                                        return $message->unreadProjectMessageCount->count();
                                                                    }
                                                                    );

                                                                $total_unread = array_sum($total_unread->toArray());
                                                                @endphp
                                                                @if ($total_unread > 0)
                                                                    <span class="unread-count-specific">{{$total_unread}}</span>
                                                                @endif

                                                            </span>{{ getTruncatedContent($posted_project->job_title, config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING')) }}</h4>
                                                        <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font">
                                                            @php
                                                                $status = getProjectCurrentStatus($posted_project);
                                                                $number_of_communications = _count($posted_project->communicationAscendingOrder);
                                                            @endphp
                                                            
                                                            @if(!is_numeric($status))
                                                                @if($number_of_communications)
                                                                    @if(!empty($posted_project->accepted_contract_id))
                                                                        <span style="background-image:url({{ $posted_project->acceptedContract->expert->user_profile->profile_picture }})" alt="expert" class="expert-profile-pic"></span>
                                                                    @endif
                                                                    <p class="view_expressions project-posted-date d-none">{!! $status !!}</p>
                                                                @else
                                                                    <p class="project-posted-date d-none">
                                                                        @if($posted_project->publish == config('constants.PROJECT_PENDING'))
                                                                            <span class="job-pending job-posted-time">
                                                                        @endif
                                                                        {!! $status !!}
                                                                        @if($posted_project->publish == config('constants.PROJECT_PENDING'))
                                                                            </span>
                                                                        @endif
                                                                    </p>
                                                                @endif
                                                            @endif
                                                                                
                                                            @php
                                                                $engagements_count = engagementCount(Auth::user()->id , $posted_project->id);
                                                                $communications_exceeding_five = $number_of_communications-5;
                                                                $count = 0;
                                                            @endphp
                                                            @if($number_of_communications && !$status)
                                                                @foreach($posted_project->communicationAscendingOrder as $communications)
                                                                    @php $count++; @endphp
                                                                    @if($count<=5)
                                                                    <span style="background-image:url({{$communications->expertProfilePicture->profile_picture}})" alt="expert" class="expert-profile-pic"></span>
                                                                    @endif
                                                                @endforeach
                                                                @if($communications_exceeding_five>0)
                                                                    <span class="expert-profile-extend" alt="expert">+{{$communications_exceeding_five}}</span>
                                                                @endif
                                                                @if($engagements_count > 0)
                                                                    <p class="view_expressions text-decoration">View your {{ $engagements_count > 1 ? 'conversations' : 'conversation' }}</p>
                                                                @else
                                                                    <p class="view_expressions text-decoration">View your {{ $count > 1 ? 'Expressions' : 'Expression' }} of Interest</p>
                                                                @endif
                                                            @endif
                                                            @if(!empty($posted_project->acceptedContract))
                                                                <a href="#" class="book-again-btn gilroyregular-semibold rebook-project"
                                                                   data-expert-url="{{$posted_project->acceptedContract->expert->user_profile->profile_picture}}"
                                                                   data-expert-id="{{$posted_project->communicationAscendingOrder[0]->expertProfilePicture->user_id}}"
                                                                   data-expert-name="{{$posted_project->acceptedContract->expert->name}}">Book {{$posted_project->acceptedContract->expert->name}} Again</a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 col-xs-4 project-views-list  d-none">
                                                        <h4 class="visible-xs">Views</h4>
                                                        <span>{{countJobViewers($posted_project->id)}}</span>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 col-xs-4  d-none">
                                                        <h4 class="visible-xs">EOIs</h4>
                                                        <span>{{getCountEOI($posted_project->id)}}</span>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 col-xs-4  d-none">
                                                        <h4 class="visible-xs">Conversations</h4>
                                                        <span>{{ $engagements_count }}</span>
                                                    </div>
                                                    <div class="col-md-1 col-sm-1 col-xs-4 keyboard-control hidden-xs dropup  d-none">
                                                        <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img src="images/3-dots.svg" />
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                            <li>
                                                                <a class="project-details-popup-button-myprojectpage"  project_id="{{$posted_project->id}}" data-buyer-id='{{Auth::user()->id}}' href="JavaScript:void(0);" >View Project</a>
                                                            </li>
                                                            @if(empty($posted_project->accepted_contract_id))
                                                            <li>
                                                                <a href="{{url('project/edit/'.$posted_project->id,[],$ssl)}}">Edit Project</a>
                                                            </li>
                                                            @endif
                                                            @if($posted_project->publish==config('constants.PROJECT_PENDING'))
                                                            <li>
                                                                <a id="open_delete_box" href="javascript:void(0)" data-toggle="modal" data-project_id="{{$posted_project->id}}"  data-target="#myModal">Delete Project</a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <a class="project-list-hover" href="{{url("buyer/messages/project/$posted_project->id",[],$ssl)}}"></a>
                                                </div>
                                                @endforeach
                                            @endif
                                            @if(_count($communication_detail))
                                                <div class="project-list-header @if (_count($posted_projects)) service-package-header @endif">
                                                            <div class="col-md-5 col-sm-5 job-title">
                                                                <h4 class="job-title-desktop">Service Package Title</h4>
                                                                <h4 class="job-title-mobile">My Service Packages</h4>
                                                            </div>

                                                            <div class="col-md-2 col-sm-2 package-type-panel-heading">
                                                                <h4 class="d-none">Package Type</h4>
                                                            </div>

                                                            <div class="col-md-2 col-sm-2">
                                                                <h4 class="d-none">Package Cost</h4>
                                                            </div>

                                                    <div class="col-md-1 col-sm-1 pull-right">
                                                        <h4 class="d-none"></h4>
                                                    </div>
                                                </div>
                                                @foreach($communication_detail as $communication)

                                                <div class="project-list-content project-list-panel project_row">
                                                                <div class="col-md-5 col-sm-5 col-xs-12 project-list-name">
                                                                    <h4 class="visible-xs d-none">Package Title</h4>
                                                                    <h4 class="project-name">
                                                                        @php
                                                                            $total_message = _count($communication['unread_service_packages_message_count']);
                                                                        @endphp
                                                                           <span class="pull-left" id="unread-service-packages-count-{{$communication['service_package_detail']['id']}}">
                                                                            @if ($total_message)
                                                                            <span class="unread-count-specific">
                                                                            {{$total_message}}
                                                                                    </span>
                                                                                @endif
                                                                        </span>
                                                                        {{getTruncatedContent($communication['service_package_detail']['name'] , config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}
                                                                    </h4>
                                                                    <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font service-package-block">
                                                                        <span class="project-posted-date d-none">{{ contractCurrentStage($communication['id']) }}</span>
                                                                        <span style="background-image:url({{ $communication['expert_profile_picture']['profile_picture'] }})" alt="expert" class="expert-profile-pic"></span>
                                                                        <p class="view_expressions">View your conversation</p>
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-2 col-sm-2 col-xs-6 project-views-list package-type-content-heading d-none">
                                                                    <span>@if(_count($communication['related_contract']))
                                                                        @if($communication['related_contract']['subscription_type']=='monthly_retainer') {{'Monthly-Retainer'}} @else {{'One-Time'}} @endif</span>
                                                                    @else
                                                                    @if($communication['service_package_detail']['subscription_type']=='monthly_retainer') {{'Monthly-Retainer'}} @else {{'One-Time'}} @endif</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-2 col-sm-2 col-xs-6 project-views-list package-type-content-heading d-none">
                                                                    <span>$@if(_count($communication['related_contract'])){{number_format($communication['related_contract']['rate'])}}@else{{number_format($communication['service_package_detail']['price'])}}@endif<?php
                                                                        if ($communication['service_package_detail']['subscription_type'] == 'monthly_retainer')
                                                                        {
                                                                            echo '/month';
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </div>
                                                                <div class="col-sm-offset-2 col-md-1 col-sm-1 col-xs-4 keyboard-control hidden-xs dropup pull-right">
                                                        <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img src="images/3-dots.svg" />
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                            <li>
                                                                <a  class="view-packages" href="javascript:void(0)" data-service-package-id="{{$communication['service_package_detail']['id']}}">View Package</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <a class="project-list-hover" href="{{url('buyer/messages/'.$communication['type'].'/'.$communication['service_package_id'].'?communication_id='.$communication['id'], [], $ssl)}}"></a>
                                                </div>
                                                @endforeach
                                            @endif
                                            </div>
                                        @else
                                        <div class="col-lg-12">
                                            <span class="text-muted d-none">You haven’t posted any projects to the platform yet. </span>
                                            <span class="text-muted no-projects-added-mobile">Please visit MeasureMatch via your laptop or desktop to submit a project</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="vtab2">
                                        <div class="col-lg-12">
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
</div>
@if(isset($pending_project) && isset($pending_project[0]->id))
<div class="modal fade delete-project-popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h3>Delete this project?</h3>
                <p>This project will be permanently deleted and cannot be recovered.
                </p>
                <button id="project_delete_button" class="btn btn-primary btn-xs btn-danger delete_project" project_id="{{$pending_project[0]->id}}">Delete Project</button>
                <a href="javascript:void(0)" class="cancel-btn btn" data-dismiss="modal" aria-label="Close">Cancel</a>
            </div>
        </div>
    </div>
</div>
@endif
<input type="hidden" id="is_welcome" value="{{$welcome}}">
<div class="modal lightbox-design buyer-reivew-popup" id="welcome_measurematch"  data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            
            
            <div class="modal-content">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="https://measurematch.herokuapp.com/images/cross-black.svg"></span></button>
                <div class="modal-body text-align-center">
                    <div class="buyer-reivew-content">
                        <img src="{{ url('images/welcome.svg',[],$ssl) }}" alt="" />
                        <h3 class="font-28 margin-top-30 text-align-center">Welcome to MeasureMatch!</h3>
                        <h4 class="font-18 margin-bottom-30 text-align-center gilroyregular-font">Get started by selecting one of the options below.</h4>
                        <div class="options-section margin-bottom-30">
                            <div class="option">
                                <h4>Submit a Project brief</h4>
                                <p class="font-16 text-align-center gilroyregular-font">This is the fastest route to engaging a pro. A project needs to be submitted to unlock the communication facility with MeasureMatch Experts.</p>
                                <a href="project/create" class="btn standard-btn">Submit a Project Brief</a>
                            </div>
                            <div class="option pull-right">
                                <h4>Check out Technographic Match&trade;</h4>
                                <p class="font-16 text-align-center gilroyregular-font">Instantly see relevant technology, data & analytics pros matched to the technographic profile of your business (or any business)!</p>
                                <a href="{{url('technographic-search-results',[],$ssl)}}" class="btn standard-btn">Try Technographic Match&trade;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div id="make_offer_stage_popups"></div>
@include('common_pop_ups.project_rebook')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/project_progress.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
<script type="text/javascript" src="{{ url('js/my_service_packages_list.js?js='.$random_number,[],$ssl)}}"></script>

<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>


<script src="{{ url('js/buyer_empty_messages.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.footer')
@endsection
