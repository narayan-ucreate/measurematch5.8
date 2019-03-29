@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="active buyerdesktop_buyer">
        <div id="page-content-wrapper">
            <div class="page-content inset">
                    <div class="col-md-3 leftSidebar">
                        @include('buyer.sidemenu')
                    </div>
                    @if (!$hub_info)
                        <div class="col-md-9 rightcontent-panel">

                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 my-projects-list-view ul-tab-block">
                                <div class="white-box margin-b-17">
                                    <div class="white-box-content all-project-list-panel">
                                        <div class="create-service-hub-section">
                                            <img width="110" class="create-vendor-icon" src="{{url('images/create-vendor-hub.svg',[],$ssl) }}" />
                                            <h3>Create Your Service Hub</h3>
                                            <p class="col-lg-7 col-md-7 col-xs-12 vendor-hub-content">MeasureMatch’s Service Hubs are for technology vendors in need of an organized and scalable way to ensure clients can get easy access to the best possible professional services to advance systems and data capabilities.</p>
                                            <a class="create-hub-btn create-hub-btn-desktop create-hub-by-vendor @if(Auth::check() && !Auth::user()->admin_approval_status){{'pending-account-approval'}}@endif"
                                               title="Create Hub"
                                               href="{{url('service-hubs/create/step-1',[],$ssl)}}">
                                                Create Hub
                                            </a>
                                            <a class="create-hub-btn create-hub-btn-mobile create-hub-by-vendor @if(Auth::check() && !Auth::user()->admin_approval_status){{'pending-account-approval'}}@endif"
                                               title="Create Hub"
                                               href="javascript:void(0)">
                                                Create Hub
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rightcontent-panel vendor-rightsection col-md-12">
                            <div class="message-user-herder hide">
                                <a id="vendor-back" class="font-14 gilroyregular-semibold" href="#"><img class="project-links-img" src="{{url('/images/chevron-back.svg')}}" alt="Back">Back</a>
                            </div>
                            @if(Session::has('success'))
                                <div class="col-md-12">
                                    <div class="alert alert-info fade in alert-dismissable">
                                        <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Service Hub created successfully.</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="theiaStickySidebar">
                                <div class="col-md-12">
                                <div class="myproject-section">
                                    <div id="myproject_section_header" class="myproject-section-header">
                                        <div class="left-section pull-left">
                                            <h2 class="section-header-title"> My Vendor Hub</h2>
                                        </div>
                                        <div class="right-section pull-right">
                                            <ul class="project-links">
                                                <li class="view-project-link">
                                                    <a
                                                        class = "project-details-popup-button view-service-hub"
                                                        href = "JavaScript:void(0);"
                                                        service-hub-id = '{{$hub_info->id}}'>
                                                        <img class="project-links-img" src="{{url('/images/view-project-icon.svg')}}" alt="view project" />
                                                        View Hub
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{route('service-hubs-create', [config('constants.VENDOR_HUB_STEP_1')])}}"><img class="project-links-img" src="{{url('/images/edit-project-icon.svg')}}" alt="" />
                                                        Edit Hub
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
    @if ($hub_info)
    <div id="service_hub_details" class="proect-deatil-pop modal fade" role="dialog">
        <div class="modal-dialog modal-lg view-project-modal view-hub-modal">
            <div class="modal-innner-content">
                <div class="modal-content">
                    <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
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

    <div id="my_hubs_mobile_warning_pop_up" class="modal create-hub-popup-mobile fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-innner-content">
                <div class="modal-content">
                    <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                    <div class="modal-body login-from-desktop-message">
                        @include('include.login_from_desktop_my_hub')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/service_hub.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
    @include('include.footer')
    @endsection
