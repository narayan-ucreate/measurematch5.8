<?php

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$word = substr($actual_link,
    strrpos($actual_link,
        '/') + 1);
$ssl = getenv('APP_SSL');

?>
@php
$total_notification = allUnreadMessages();
$pending_account_approval=(!Auth::user()->admin_approval_status)?'pending-account-approval':'';
@endphp
@include('include.footer_left')
<div class="theiaStickySidebar">
    @if(Auth::user()->user_type_id == config('constants.BUYER'))
    <ul class="tm-mathes-nav">
        <li class="@if (strpos($actual_link, 'technographic-info') != false) active @endif">
            <a href="{{url('technographic-search-results',[],$ssl)}}">
                Technographic Match&trade;
            </a>
            <span>BETA</span>
        </li>
    </ul>
    @endif
    <div id="sidebar-wrapper" class="navbar-collapse left-menu">
        <div class="left-menu-panel">
            <ul id="sidebar_menu" class="sidebar-nav menu-btn">
                <li class="sidebar-brand" id="menu-toggle">
                    <span class="hide-lg-screen logged-username">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</span>

                    <span id="main_icon" class="glyphicon glyphicon-align-justify"></span>
                </li>
            </ul>

            <ul class="sidebar-nav nav nav-pills nav-stacked" id="sidebar">
                <li class="techno-side-left @if (strpos($actual_link, 'technographic-info') !== false) active @endif">
                    <a href="#">Technographic Match&trade;</a>
                    <span>BETA</span>
                </li>
                <li class="find-expert-icon @if (strpos($actual_link, 'project/create') !== false ||
                    strpos($actual_link, 'project/edit') !== false ||
                    strpos($actual_link, 'publish_projects_view') !== false
                    ) {{'active'}} @endif post-job-btn">
                    <a  href="{{url('/project/create',[],$ssl)}}" title="Submit a Project">
                        Submit a Project
                    </a>
                </li>
                <li class="project-icon @if(strpos($actual_link, 'myprojects') !== false ||
                    strpos($actual_link, 'buyer/messages/project') !== false ||
                    strpos($actual_link, 'buyer/messages/service_package') !== false
                    ) @php echo 'active'; @endphp @endif">
                    <a href="{{url('myprojects',[],$ssl)}}" title="My Projects">
                        My Projects
                        <span class="project_notifications">
                            @if ($total_notification > 0)
                            <span class="unread-count">
                                {{ $total_notification }}
                            </span>
                            @endif
                        </span>
                    </a>
                </li>
                <li class="browser-package-icon {{$pending_account_approval}} @if( $word=='types' || strpos($actual_link, 'servicepackages/type')) {{'active'}} @endif">
                    <a href="{{url('servicepackage/types',[],$ssl)}}" title="Browse Service Packages">Browse Service Packages</a>
                </li>
                <li class="browser-expert-icon {{$pending_account_approval}}
                    @if(strpos($actual_link, 'buyer/experts/search') !== false
                    || strpos($actual_link, 'expert-profile-detail') !== false) {{'active'}}
                    @endif">
                    <a href="{{url('buyer/experts/search',[],$ssl)}}" title="Browse Experts">
                        Browse Experts
                    </a>
                </li>
                @if(getenv('SHOW_SERVICE_HUB_MENU_TO_BUYERS')
                && Auth::user()->user_type_id == config('constants.BUYER'))
                    <li class="my-service-hub
                        @if(strpos($actual_link, 'vendor-service-hubs') !== false) {{'active'}}@endif">
                        <a href="{{route('vendor-service-hubs')}}" title="Vendors">
                            Vendors
                        </a>
                    </li>
                @endif
                @if(Auth::user()->user_type_id == config('constants.VENDOR'))
                    <li class="my-service-hub @if($word=='service-hubs') {{'active'}} @endif">
                        <a href="{{url('service-hubs',[],$ssl)}}" title="My Service Hub">My Service Hub</a>
                    </li>
                @endif
                @if(Auth::user()->admin_approval_status == config('constants.APPROVED'))
                <li class="hide-lg-screen profile-icon @if($word=='profile-summary') {{'active'}} @endif">
                    <a href="{{url('buyer/profile-summary',[],$ssl)}}" title="My Profile">
                        My Profile
                    </a></li>
                @endif
                <li class="hide-lg-screen"><a class="account-link" href="{{ url('buyer/settings',[],$ssl) }}" title="Settings">Settings</a></li>
                <li class="hide-lg-screen">
                    <a class="message-mm-support" href="javascript:void(0)" title="Support">
                        <span class="support-link">Support</span>
                    </a>
                    @include('htmlpanels.mm_support_panel')
                </li>
                @if (isset(Auth::user()->id))
                <li class="signout-icon hide-lg-screen">
                    <a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">
                        Sign out
                    </a>
                </li>
                @endif
            </ul>
        </div>

    </div>

    <nav class="navbar navbar-inverse sidebar device-menu" role="navigation">
        <div id="menu_for_devices" class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
            <ul class="tm-mathes-nav tm-matches-link-m">
                <li><a href="{{url('technographic-search-results',[],$ssl)}}">Technographic Match&trade;</a><span>BETA</span></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="find-expert-icon @if (strpos($actual_link, 'post') !== false
                    || strpos($actual_link, 'editpost') !== false
                    || strpos($actual_link, 'publish_projects_view') !== false) {{'active'}} @endif post-job-btn">
                    <a  href="{{url('project/create',[],$ssl)}}" title="Submit a Project">
                        Submit a Project
                    </a>
                </li>
                <li class="hide-lg-screen">
                    <a class="account-link" href="{{ url('technographic-search-results',[],$ssl) }}" title="Settings">Technographic Match&trade;</a>
                </li>
                <li class="project-icon @if( $word=='myprojects' ||  strpos($actual_link, 'buyer/messages/project') !== false
                    || strpos($actual_link, 'buyer/messages/service_package') !== false) {{'active'}} @endif">
                    <a href="{{url('myprojects',[],$ssl)}}" title="My Projects">My Projects
                        <span class="project_notifications">
                            @if ($total_notification > 0)
                            <span class="unread-count">
                                {{ $total_notification }}
                            </span>
                            @endif
                        </span>
                    </a>
                </li>
                <li class="browser-package-icon {{$pending_account_approval}}  @if( $word=='types')  {{'active'}} @endif">
                    <a href="{{url('servicepackage/types',[],$ssl)}}" title="Browse Service Packages">Browse Service Packages</a></li>
                <li class="browser-expert-icon {{$pending_account_approval}}
                    @if(strpos($actual_link, 'buyer/experts/search') !== false
                    || strpos($actual_link, 'expert-profile-detail') !== false) {{'active'}} @endif">
                    <a href="{{url('buyer/experts/search',[],$ssl)}}" title="Browse Experts">Browse Experts</a>
                </li>
                @if(Auth::user()->user_type_id == config('constants.VENDOR'))
                    <li class="my-service-hub @if($word=='service-hubs') {{'active'}} @endif">
                        <a href="{{url('service-hubs',[],$ssl)}}" title="My Service Hub">My Service Hub</a>
                    </li>
                @endif
                @if(Auth::user()->admin_approval_status == config('constants.APPROVED'))
                    <li class="hide-lg-screen myprofile-li @if($word=='profile-summary') {{'active'}} @endif">
                        <a href="{{url('buyer/profile-summary',[],$ssl)}}" title="My Profile">Profile</a>
                    </li>
                @endif
                <li class="hide-lg-screen">
                    <a class="account-link" href="{{ url('buyer/settings',[],$ssl) }}" title="Settings">Settings</a></li>
                <li class="hide-lg-screen">
                    <a class="message-mm-support" href="javascript:void(0)" title="Support">
                        <span class="support-link">Support</span></a>
                        @include('htmlpanels.mm_support_panel')
                </li>
                @if (isset(Auth::user()->id))
                    <li class="signout-icon hide-lg-screen"><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li>
                @endif
            </ul>
        </div>
    </nav>
</div>
