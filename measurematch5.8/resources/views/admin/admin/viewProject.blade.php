@extends('layouts.adminlayout')
@section('content')
@php
$buyer_id = $project->user_id;
$redirect_suffix = (!empty($redirect_url)) ? "?redirect-url=$redirect_url" : '';
@endphp
<link href="{{url('css/message.css',[],$ssl)}}" rel="stylesheet" type="text/css">
<section class="content admin-section">
    <div class="row">
        <div class="container">
            <!-- left column -->
            <div class="col-lg-2 col-sm-2 col-xs-12 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-lg-10 col-sm-10 col-xs-2 admin-right-side">
                <!-- general form elements -->
                <div class="box">
                    <!-- form start -->
                    <div class="box-header">
                        <h1 class="box-heading">{{$project_label}}@if($bread_crumb == config('constants.BUYER_PROJECT')) • {{$bread_crumb}}@endif</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    @if($bread_crumb != config('constants.BUYER_PROJECT'))
                    <div class="admin-subtab">
                        <ul>
                            <li>
                                <a @if($bread_crumb==config('constants.PENDING_PROJECT'))
                                    class="active" @endif href="{{ url('admin/pendingProjects',[],$ssl) }}">
                                    Pending ({{$projects_count['pending']}})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb==config('constants.BUYER_PROJECT') ||
                                        $bread_crumb==config('constants.LIVE_PROJECT'))
                                    class="active" @endif href="{{ url('admin/liveProjects',[],$ssl) }}">
                                    Live ({{$projects_count['live']}})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb==config('constants.BUYER_PROJECT') ||
                                        $bread_crumb==config('constants.IN_CONTRACT_PROJECT'))
                                    class="active" @endif href="{{ url('admin/inContractProjects',[],$ssl) }}">
                                     In Contract ({{$projects_count['in_contract']}})
                                </a>
                            </li>
                             <li>
                                <a @if($bread_crumb==config('constants.BUYER_PROJECT') ||
                                        $bread_crumb==config('constants.CONTRACT_COMPLETED'))
                                    class="active" @endif href="{{ url('admin/completedProjects',[],$ssl) }}">
                                    Completed ({{$projects_count['completed']}})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb==config('constants.EXPIRED_PROJECT'))
                                   class="active" @endif href="{{ url('admin/expiredProjects',[],$ssl) }}">
                                    Expired ({{$projects_count['expired']}})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb==config('constants.REBOOKING_PROJECT'))
                                   class="active" @endif href="{{ url('admin/rebookingProjects',[],$ssl) }}">
                                    Rebookings ({{$projects_count['rebookings']}})
                                </a>
                            </li>
                            <li><a @if($bread_crumb==config('constants.ARCHIVED_PROJECT')) 
                                    class="active" @endif href="{{ url('admin/archivedProjects',[],$ssl) }}">
                                    Archived ({{$projects_count['archived']}})
                                </a>
                            </li>
                        </ul>
                    </div> 
                    @endif
                    <ol class="breadcrumb">
                        <li><a href="{{ url($back_url,[],$ssl) }}">{{$bread_crumb}}</a></li>
                        <li>{{getTruncatedContent($project->job_title, config('constants.TRUNCATION_LIMIT'))}}</li>
                    </ol>
                    <div class="box-body box-style">
                        <div class="box-inner-tabing">
                            <ul>
                              <li><a class="font-16 active project-detail-tab" id="project_details_tab_link" data-tab="detail" href="#">Project Details</a></li>
                              @if(_count($user_list))<li><a class="font-16 project-detail-tab" id="communication_tab_link" data-tab="communication" href="#">Communications</a></li>@endif
                              @if(_count($contracts))<li><a class="font-16 project-detail-tab" id="contracts_tab_link" data-tab="contracts" href="#">Contracts</a></li>@endif
                            </ul>
                            @if((!_count($project->contract) || 
                                (_count($project->contract) &&
                                $project->contract->status!= config('constants.ACCEPTED'))) &&
                                $project->publish != config('constants.ARCHIVED')
                                )
                            <ul class="admin-action-btn">
                                <li role="presentation" class="dropdown">
                                    <a href="#" class="dropdown-toggle font-14 gilroyregular-semibold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                                        Actions <span class="caret"></span> 
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="gilroyregular-semibold font-14 text-center" href="{{ url('admin/project/edit/'.$project->id,[],$ssl).$redirect_suffix }}">
                                                Edit Project
                                            </a>
                                        </li>
                                        @if($webflow_url == '-' && ($bread_crumb==config('constants.EXPIRED_PROJECT') || $bread_crumb==config('constants.LIVE_PROJECT')))
                                        <li>
                                            <a class="gilroyregular-semibold font-14 text-center project-details-popup-admin" href="javascript:void(0)" data-toggle="modal">
                                            Share Project
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                            @endif
                        </div>
                        <div class="pull-right message-section">
                            <p class="success" style="color:green"></p>
                            <p class="warning error-message"></p>
                        </div>
                        <input type="hidden" name="post_id" value="{{ $project->id }}">
                        <input type="hidden" name="buyer_id" value="{{ $project->user_id }}">
                        <input type="hidden" id="project_url" value="{{url('re-route/project/' . $project->id, [],$ssl)}}">
                         <div  id="project_details_tab_content" class="box-inner-content">
                            <div class="basic-info">
                            <div class="form-group">
                                <label>Client Company :</label>
                                {{$type_of_organization}}
                                @if($project->hide_company_name==config('constants.TRUE'))
                                (Hidden from this brief)
                                @endif

                            </div>
                            <div class="form-group">
                                <label>Client Name :</label>
                                 {{ userName($project->user_id )}}  
                            </div>
                            <div class="form-group">
                                <label>Client Email :</label>
                                {{$buyer_information->email}}
                            </div>
                            </div>
                            <div class="form-group">
                                <label>Project Name :</label>
                                {{$project->job_title}}   
                            </div>
                             @if($bread_crumb==config('constants.EXPIRED_PROJECT') || $bread_crumb==config('constants.LIVE_PROJECT'))
                             <div class="form-group service-package-link">
                                 <label>Project Link:</label>
                                 <span id="project_link" class="gilroyregular-bold-font link-color"> {{ $webflow_url }} </span>
                                 <button class="copy-link-btn"  onclick="copyToClipboard('#project_link')">Copy URL</button>
                                 <span class="success-message-of-copy-url gilroyregular-bold-font" id="link_copied_message"></span>
                             </div>
                             @endif
                             <div class="form-group">
                                    <label>Date Posted : </label>
                                    <div class="job-input-bx select-box input-group date job-date-picker" id='start_time_div'>
                                        {{getFormatedDate($project->created_at, TRUE)}}
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label>Status : </label>
                                    {{ (explode(" ",$bread_crumb))[0] }}
                                    
                            </div>
                            @if($project->publish !=  config('constants.PROJECT_PENDING'))
                            <div class="form-group">
                                    <label>No. of EOIs : </label>
                                        {{_count($user_list)}}
                                   
                            </div>
                            @endif
                            <div class="form-group">
                                    <label>Estimated Project Budget :</label>

                                    <div class="project-rate-bx">
                                        @if($project->rate > 0)
                                        {{  convertToCurrencySymbol($project->currency)}}{{((is_numeric($project->rate))?number_format($project->rate):$project->rate)}}@if($project->rate_variable=='daily_rate'){{'/day'}}@endif
                                        @else
                                        Negotiable
                                        @endif
                                    </div>
                                </div>
                                @if($project->visibility_date)
                                <div class="form-group">
                                    <label>Project Visibility Expiry Date : </label>
                                    <div class="job-input-bx select-box input-group date job-date-picker" id='start_time_div'>
                                        {{getFormatedDate($project->visibility_date, TRUE)}}
                                    </div>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>Preferred Expert Start Date : </label>
                                    <div class="job-input-bx select-box input-group date job-date-picker" id='start_time_div'>
                                        {{getFormatedDate($project->job_end_date, TRUE)}}
                                    </div>

                                </div>
                               
                                
                            <div class="form-group">
                                <label>Project Description :</label>
                                <div class="admin-view-brief">{!! nl2br(e( $project->description )) !!} </div>
                            </div>
                            <div class="form-group">
                                <label>Deliverables :</label>

                                @if(_count($deliverables))
                                <div class="admin-view-brief deliverables-list-style admin-deliverable-right">
                                    <ul>
                                        @foreach($deliverables as $deliverable)
                                        <li class="white-space-pre-wrap">{{$deliverable->deliverable}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                N/A
                                @endif
                            </div>
                            <div class="form-group">

                                <label>Attachments :</label>
                                <span class="admin-view-brief">
                                    <div class="attachment-list-style">
                                        @if(_count($images))
                                        <ul>
                                            @foreach($images as $image)
                                            <li><a target='_blank' href="{{$image}}">{{getFileName($image)}}</a></li>
                                            @endforeach
                                        </ul>
                                        @else
                                        N/A
                                        @endif
                                    </div>

                                </span>
                            </div>
                            <div class="form-group">
                                <label>Required Tools & Tech Expertise :</label>
                                <span class="admin-view-brief">
                                    @if(_count($tools))
                                    @php  $tools_string=[]; @endphp
                                    @foreach($tools as $key=>$tool)
                                    @php $tools_string[]=$tool->name; @endphp
                                    @endforeach
                                    {{implode(', ',$tools_string)}}
                                    @else
                                    N/A
                                    @endif
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Other Required Skills :</label>
                                <span class="admin-view-brief">
                                    @if(_count($skills))
                                    @php  $skills_string=[]; @endphp
                                    @foreach($skills as $key=>$skill)
                                    @php $skills_string[]=$skill->name; @endphp
                                    @endforeach
                                    {{implode(', ',$skills_string)}}
                                    @else
                                    N/A
                                    @endif
                                </span>
                            </div>
                            <div class="form-group">

                                <label>Location Preference :</label>
                                <span class="admin-view-brief">
                                    @if($project->remote_id == '1')
                                    {{'Only work remotely'}}
                                    @elseif($project->remote_id  == '2')
                                    {{'Only work on site'}}
                                    @else
                                    {{'Can work remotely and on site'}}
                                    @endif

                                </span>
                            </div>
                            <div class="form-group">
                                <label>Office Location :</label>
                                <span class="admin-view-brief">   {{$office_location}}         </span>
                            </div>
                           

                            <div class="admin-contract-payment-block">
                                <div class="form-group">
                                    <label>Project Duration : </label>
                                    <div class="admin-view-brief no-project-duration">{{convertDaysToWeeks($project->project_duration)['time_frame']}}</div>


                                </div>
                                <div class="form-group">
                                    <label>Budget approval status : </label>
                                    <div class="admin-view-brief no-project-duration">{{getBudgetApprovalStatus($project->budget_approval_status)}}</div>


                                </div>
                                <div class="form-group">
                                    <label> Project Currency : </label>
                                    <div class="admin-view-brief no-project-duration">{{convertToCurrencySymbol($project->currency)}}</div>


                                </div>

                                <div class="form-group">
                                    <label>Project Id :</label>
                                    <span class="admin-view-brief">   {{$project->job_num}}         </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="is_approved" value="{{$buyer_information->admin_approval_status}}">
                                @if($project->publish==config('constants.PROJECT_PENDING'))
                                @if($buyer_information->verified_status == config('constants.APPROVED'))
                                @php $project_start_date_in_past = (strtotime($project->job_end_date)>= strtotime(date('Y-m-d')))? FALSE: TRUE; @endphp
                                <input type="button" id="approve_pending_project" data-user="{{$project->user_id}}" data-id="{{$project->id }}" data-project_start_date="{{$project_start_date_in_past}}" class="update-button" value="Approve">
                                <input  type="button" id="decline_pending_project" data-id="{{ $project->id  }}" class="decline-button" value="Reject">
                                @else
                                <h6>* This project cannot be approved currently, as client has not verified his account.</h6>
                                @endif
                                @elseif($project->publish==config('constants.PROJECT_REJECTED'))
                                <input type="button" id="reinstate_project" data-user="{{$project->user_id}}" data-project_id="{{ $project->id }}" class="update-button" value="Reinstate Project">
                                @endif
                            </div>
                        </div>    
                        <div  id="communication_tab_content" class="box-inner-content communication-inner ">
                         @include('admin.admin.include.communication_panel')
                        </div> 
                        <div id="contracts_tab_content" class="box-inner-content contract-inner hide ">
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="box-style">
                                        <div class="box-style-header">
                                            <h2 class="gilroyregular-semibold">Expert Details</h2>
                                        </div>
                                        @if($user_information)
                                        <div class="box-style-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Name:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> {{ $user_information['name'] }}  </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Email:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> {{ $user_information['email']}}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Phone number:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p>{{ $user_information['phone'] }} </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Business Details:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> <a href="javascript:void(0)" data-id="{{ config('constants.EXPERT') }}" class="view-business-details-popup bold">View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="box-style">
                                        <div class="box-style-header">
                                            <h2 class="gilroyregular-semibold">Client Details</h2>
                                        </div>
                                        <div class="box-style-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Name:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> {{ userName($project->user_id )}}  </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Email:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> {{$buyer_information->email}}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Phone number:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p>{{ $buyer_information->phone_num }} </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <p class="gilroyregular-semibold">Business Details:</p>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p> <a href="javascript:void(0)" data-id="{{ config('constants.BUYER') }}" class="view-business-details-popup">View</a> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            
                            @foreach($contracts as $contract)
                            
                            <div class="row mt-5">
                                <div class="col-lg-12">
                                    <div class="box-style">
                                        <div class="box-style-header">
                                            <h2 class="gilroyregular-semibold"> {{ $contract['alias_name']}} Details</h2>
                                            <a class="pull-right view-expert-contract font-14" contract-id="{{$contract['id']}}" buyer-id="{{$contract['buyer_id']}}"
                                               user-id="{{$contract['user_id']}}" communication-id="{{$contract['communications_id']}}"
                                               href="javascript:void(0)">View
                                            </a>
                                        </div>
                                        <div class="box-style-body">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Project name:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{ ucfirst($project->job_title)}} </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Contract Start Date:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>   {{getFormatedDate($contract['job_start_date'], TRUE)}}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Contract End Date:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{getFormatedDate($contract['job_end_date'], TRUE)}} </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Currency:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p> {{convertToCurrencySymbol($contract['rate_variable']).' '.$contract['rate_variable'] }} </p>
                                                </div>
                                            </div>
                                             <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Value of project:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'],2)}} </p>
                                                </div>
                                            </div>
                                            @php $contract_price = contractPaymentCalculationWithoutCoupon($contract['rate']); @endphp
                                             <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">15% MM Take:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{convertToCurrencySymbol($contract['rate_variable']).number_format($contract_price['mm_fee'],2)}} </p>
                                                </div>
                                            </div>
                                             <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">85% to Expert:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{convertToCurrencySymbol($contract['rate_variable']).number_format($contract_price['amount_to_be_paid_to_expert'],2)}} </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Deliverables:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>  {{ucfirst($contract['project_deliverables'])}} </p>
                                                </div>
                                            </div>
                                             <div class="row">
                                                <div class="col-lg-2">
                                                    <p class="gilroyregular-semibold">Attached files:</p>
                                                </div>
                                                <div class="col-lg-10">
                                                    <p>
                                                    @if(($contract['upload_document']))
                                                    <a target='_blank' href="{{ $contract['upload_document']}}">
                                                        {{ getFileName($contract['upload_document'])}}
                                                    </a>
                                                    @else
                                                     N/A
                                                     @endif 
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div> 
                     </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
    
<div id="contract_popups">
</div>
    <div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_contract_preview"
         class="modal mark_completed_project got-match-popup seller-contract-popup invite-seller-popup lightbox-design-small lightbox-design fade in" style="padding-left: 13px;">
    </div>
    <div class="modal lightbox-design" id="view_webflow_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-innner-content">
                <div class="modal-content">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                            <span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span>
                        </button>

                    <div class="modal-body">
                         <h2>{{$type_of_organization}}'s Project</h2>
                         <p class="font-16 text-center">Edit the project content below. Remove any company and/ or sensitive information before sending to Webflow.</p>
                        <div class="input-bx">
                            <label>Project Name :</label>
                            <input id="project_title" type="text" value="{{$project->job_title}}">
                        </div>
                        <div class="input-bx">
                            <label>Project Description :</label>
                            <textarea name="description" cols="80" class="edit_description" id ="description" value="{!! $project->description !!}">{!! $project->description !!}</textarea>
                        </div>
                        <div class="input-bx">
                            <input type="button" id="projectApproveWebflow" data-id="{{ $project->id }}" class="update-button" value="Send to Webflow">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.admin.include.business_details_popup')
    <div id="view_contract_popup"></div>
    <script src="{{url('js/admin_contract_edit.js?js='.$random_number,[],$ssl)}}"></script>
</section>

<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
