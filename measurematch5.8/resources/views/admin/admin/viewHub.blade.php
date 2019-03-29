@extends('layouts.adminlayout')
@section('content')
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
                        <h1 class="box-heading">Vendor Hubs</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">
                            Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" />
                        </a>
                    </div>
                    <div class="admin-subtab">
                        <ul>
                            <li>
                                <a @if($bread_crumb == config('constants.ADMIN_HUBS_BREADCRUMB.LIVE_HUBS'))
                                   class="active" @endif href="{{ url('admin/liveHubs',[],$ssl) }}">
                                    Live ({{ $hubs_count['live'] }})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb == config('constants.ADMIN_HUBS_BREADCRUMB.PENDING_HUBS'))
                                   class="active" @endif href="{{ url('admin/pendingHubs',[],$ssl) }}">
                                    Pending ({{$hubs_count['pending']}})
                                </a>
                            </li>
                            <li>
                                <a @if($bread_crumb == config('constants.ADMIN_HUBS_BREADCRUMB.ARCHIVED_HUBS'))
                                   class="active" @endif href="{{ url('admin/archivedHubs',[],$ssl) }}">
                                    Archived ({{$hubs_count['archived']}})
                                </a>
                            </li>
                        </ul>
                    </div>

                    <ol class="breadcrumb">
                        <li><a href="{{ url($back_url,[],$ssl) }}">{{$bread_crumb}}</a></li>
                        <li>{{ $hub->name }}</li>
                    </ol>
                    <div class="box-body box-style">
                        <div class="box-inner-tabing">
                            <ul>
                                <li><a class="font-16 active project-detail-tab" id="hub_details_tab_link" data-tab="detail" href="#">Hub Details</a></li>
                                <li><a class="font-16 project-detail-tab" id="communication_tab_link" data-tab="communication" href="#">Hub Experts</a></li>
                                <li>
                                    <a class="font-16 project-detail-tab view-service-hub" service-hub-id="{{$hub_info->id}}" data-buyer-id='' href="JavaScript:void(0);"> View Hub </a>
                                </li>
                            </ul>
                        </div>
                        <div class="pull-right message-section">
                            <p class="success" style="color:green"></p>
                            <p class="warning error-message"></p>
                        </div>
                        <div id="project_details_tab_content" class="box-inner-content">
                            <div class="basic-info">
                                <div class="form-group">
                                    <label>Vendor :</label>
                                    {{ $hub->vendor_profile->company_name }}
                                </div>
                                <div class="form-group">
                                    <label>Vendor User :</label>
                                    {{ ucfirst($hub->vendor_profile->first_name) . ' ' . ucfirst($hub->vendor_profile->last_name) }}
                                </div>
                                <div class="form-group">
                                    <label>Vendor User Email :</label>
                                    {{$hub->vendor_user->email}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Service Hub Name :</label>
                                {{ $hub->name }}
                            </div>
                            <div class="form-group">
                                <label>Service Hub Logo :</label>
                                <img src="{{ $hub->logo }}" width="100px">
                            </div>
                            <div class="form-group">
                                <label>Sales Contact :</label>
                                {{ $hub->sales_email }}
                            </div>
                            <div class="form-group">
                                <label>Service Website :</label>
                                <a target="_blank" href="{{ createExternalUrl($hub->service_website) }}">{{ $hub->service_website }}</a>
                            </div>
                            <div class="form-group">
                                <label>Service Hub Description :</label>
                                <div class="admin-view-brief">{!! nl2br(e( $hub->description )) !!} </div>
                            </div>
                            <div class="form-group">
                                <label>Service Categories :</label>
                                <span class="admin-view-brief">
                                @if(!empty($hub->serviceCategories) && _count($hub->serviceCategories))
                                    <ul>
                                    @foreach($hub->serviceCategories as $category)
                                        <li>{{ $category->name }}</li>
                                    @endforeach
                                    </ul>
                                @else
                                    N/A
                                @endif
                                </span>
                            </div>
                            @if(!$hub_info->status)
                                <div class="form-group">
                                    <input type = "button" data-id = '{{$hub_info->id}}' data-status = '{{config('constants.APPROVED')}}' class = "update-button approve-reject-hub" value = "Approve">
                                    <input type="button" data-id='{{$hub_info->id}}' data-status = '{{config('constants.REJECTED')}}'  class = "decline-button approve-reject-hub" value="Reject">
                                </div>
                            @endif
                        </div>    
                        <div id="communication_tab_content" class="box-inner-content communication-inner hub-admin-right">
                            <div class="seller-message-state-block expert-msg-attachment">
                                <div class="msgOrg">
                                    <div class="expert_message_panel">
                                        <div class="expert_profile_inner_panel col-xs-12 col-lg-12 col-md-12 col-sm-12">

                                            <div class="expert-message-outer-panel">
                                                <div id="show-user-list">
                                                    {!! $all_experts_listing !!}
                                                </div>
                                                <div class="expertshow-msg-block">
                                                    <div class="expert-profile-section cnvtn chat-block col-lg-9 col-md-9 col-sm-9 col-xs-12 d-none">
                                                        <div class="create-package-panel default-live-hub-block">
                                                            {!! $right_hand_block !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div id="contracts_tab_content" class="box-inner-content contract-inner hide" service-hub-id="{{$hub_info->id}}">
                            View Hub
                        </div>
                     </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
    @if ($hub_info)
    <div id="service_hub_details" class="proect-deatil-pop modal fade" role="dialog">
        <div class="modal-dialog modal-lg view-project-modal view-hub-modal">
            <div class="modal-innner-content">
                <div class="modal-content">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                        <span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span>
                    </button>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($hub_info)
        @include('service-hub.approve_expert_pop_up')
        @include('service-hub.decline_expert_pop_up')
    @endif
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/service_hub.js?js='.$random_number,[],$ssl)}}"></script>
@endsection