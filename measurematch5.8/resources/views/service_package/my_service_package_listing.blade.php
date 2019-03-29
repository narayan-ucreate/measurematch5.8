@extends('layouts.layout')
@section('content')

    <input type="hidden" value="{{$service_package_listing_show_popup}}" id="service_package_listing_show_popup" >
    <input type="hidden" value="{{$service_package_listing_welcome_popup_count}}" id="service_package_listing_welcome_popup_count" >

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 create-package-panel my-service-package-panel expert-my-service-package">
        <span class="service-package-error-message" style="@if(Session::has('success')) display: block; @else display: none; @endif">
            <div class="alert alert-info fade in alert-dismissable">
                <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><span id="success_msg">{{Session::get('success')}}</span>
            </div>
        </span>
        <div class="white-box">
            <div class="white-box-header">
                <h4>My Service Packages</h4>
            </div>
            <div class="white-box-content left-padding-0 right-padding-0 top-padding-0  margin-0">
                <div class="project-list-header">
                    <div class="col-md-11 col-sm-10 col-xs-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 job-title">
                                <h4 class="hidden-xs">Service Package</h4>
                            </div>
                            <div class="col-md-3 col-sm-4 package-type-panel-heading">
                                <h4 class="hidden-xs">Type</h4>
                            </div>

                            <div class="col-md-2 col-sm-2 my-service-views-panel">
                                <h4 class="hidden-xs text-align-center">Views</h4>
                            </div>

                            <div class="col-md-2 col-sm-2 my-service-eoi-panel">
                                <h4 class="hidden-xs text-align-center">EOIs</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-2">
                        <h4 class="hidden-xs"></h4>
                    </div>
                </div>

                <div class="auto-scroll global-scroll">
                    @if(sizeof($my_service_package_listing))
                        @foreach($my_service_package_listing as $my_service_package_data)
                            <div class="project-list-content awaiting-approval-pannel">
                                <div class="col-md-11 col-sm-10 col-xs-12">
                                    <div class="row">
                                        <a href="{{ url('/',[],$ssl) }}/servicepackage/detail/{{$my_service_package_data['id']}}">
                                            <div class="col-md-4 col-sm-4 col-xs-12 project-list-name">
                                                <h4 class="visible-xs">Service Package</h4>
                                                <h5>{{$my_service_package_data['name']}}</h5>
                                                @if($my_service_package_data['is_hidden']==1)
                                                    <span class="live-package hidden-package">Hidden from Clients</span>
                                                @elseif(empty($my_service_package_data['is_approved']) && empty($my_service_package_data['is_rejected']))
                                                    <span class="await-approval-package">Awaiting approval</span>
                                                @elseif(!empty($my_service_package_data['is_rejected']) && empty($my_service_package_data['is_approved']))
                                                    <span class="await-approval-package">Rejected by MeasureMatch</span>
                                                @else
                                                    <span class="live-package">Live on Measurematch</span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-xs-4 project-views-list package-type-content-heading">
                                                <h4 class="visible-xs">Type</h4>
                                                <span>@if($my_service_package_data['subscription_type']=="one_time_package") One-Time @else Monthly Retainer @endif</span>
                                            </div>

                                            <div class="col-md-2 col-sm-2 col-xs-4 project-views-list">
                                                <h4 class="visible-xs">Views</h4>
                                                <span>@if(countServicePackageViewers($my_service_package_data['id'])> 0 ) {{countServicePackageViewers($my_service_package_data['id'])}} @else - @endif</span>
                                            </div>

                                            <div class="col-md-2 col-sm-2 col-xs-4 project-views-list">
                                                <h4 class="visible-xs">EOIs</h4>
                                                <span>@if(getCountEOI($my_service_package_data['id'],'service_package')> 0 ) {{getCountEOI($my_service_package_data['id'],'service_package')}} @else - @endif</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-2 col-xs-12 keyboard-control dropup">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="images/3-dots.svg">
                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                        <li><a href="{{ url('/',[],$ssl) }}/servicepackage/edit/{{$my_service_package_data['id']}}">Edit Package</a></li>
                                        <li><a href="javascript:void(0)" class="@if($my_service_package_data['is_hidden']==1) unhide_package @else hide_package @endif" package_id="{{$my_service_package_data['id']}}">@if($my_service_package_data['is_hidden']==1) Unhide @else Hide @endif</a></li>
                                        @if(!_count($my_service_package_data['contract']) && !_count($my_service_package_data['communication']))
                                            <li><a class="delete-service-package" href="javascript:void(0)" id="{{$my_service_package_data['id']}}">Delete</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-services-package-message service-package-notification-message">
                            <h4>You haven’t created any Service Packages yet.</h4>
                        </div>
                    @endif
                </div>
                <div class="col-lg-12 pull-left bottom-white-panel">
                    <a href="{{ url('/',[],$ssl) }}/servicepackage/create" class="blue-bg-btn standard-btn" id="addservicepackage">Create a new Service Package</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 package-draft-panel">
        <div class="draft-post-block">
            <h4>Drafts @if(_count($drafts))({{count($drafts)}})@endif</h4>
            <div class="auto-scroll">
                @if(_count($drafts))
                    @foreach($drafts as $draft)
                        <div class="draft-post">
                            <a href="{{url('servicepackage/edit/'.$draft['id'],[],$ssl)}}">
                                <span class="posted-job-title">{{ucfirst($draft['name'])}}</span>
                                <span class="job-location">@if($draft['subscription_type']=="one_time_package")One-Time @else Monthly Retainer @endif</span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="draft-post no-draft-message">
                        <span>You currently have no drafts</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade lightbox-design welcome-service-package-popup" id="servicePackageWelcomeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="{{ url('images/cross-black.svg',[],$ssl)}}" /></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="welcome-package-img">
                        <img src="{{ url('images/welcome-to-service-packages.png',[],$ssl)}}" width="100%" />
                    </div>

                    <h2>Welcome to Service Packages</h2>
                    <p>Easily create a set of deliverables, choose a package price, and share it with the Clients on the MeasureMatch platform.</p>
                    <div class="popup-btn-panel">
                        <input value="Got it" class="continue-btn green_gradient standard-btn" id="got_it_service_package" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="package_created" type="hidden" value="@if(Session::has('package_created')){{Session::get('package_created')}}@endif">
    <input id="publish_status" type="hidden" value="@if(Session::has('publish_status')){{Session::get('publish_status')}}@endif">
    <div id="service-package-reivew-thankyou" class="modal fade bs-example-modal-lg text-center welcome-package lightbox-design package-preivew new-modal-theme" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross"></span></button>
                </div>
                <div class="modal-body">
                    <img class="package-is-under" src="{{ url('images/package-is-under-review.svg',[],$ssl)}}" />
                    <h2>Your New Service Package is under Review!</h2>
                    <h5>Thank you for submitting a Service Package to MeasureMatch.<br /> Our team will confirm within the next 72 hours.</h5>
                    <div class="popup-btn-panel">
                        <input id="package_created_got_it" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="hide_package_from_buyer" class="modal fade bs-example-modal-lg hide-package-popup lightbox-design package-preivew new-modal-theme">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                    </span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Hide this Package from Clients?</h2>
                    <input type="hidden" name="package_id" value="" id="package_id">
                    <div class="popup-btn-panel">
                        <p>Not quite ready to service clients yet on this package? Or taking a vacation? Don't worry, we've got you covered.</p>
                        <input id="hide_service_package" value="Yes, hide Package" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="unhide_package_from_buyer" class="modal fade bs-example-modal-lg lightbox-design hide-package-popup package-preivew new-modal-theme">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                    </span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Make Package visible to Clients?</h2>
                    <input type="hidden" name="package_unhide_id" value="" id="package_unhide_id">
                    <div class="popup-btn-panel">
                        <p>You'll start receiving Expressions of Interest from Clients and asked for your availability to deliver your Package.</p>
                        <input id="service_package_unhide" value="Yes, make Package visible" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('include.footer')
@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    <script src="{{url('js/my_service_packages_list.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
