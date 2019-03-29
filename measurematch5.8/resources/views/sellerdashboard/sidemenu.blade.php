<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$word = substr($actual_link, strrpos($actual_link, '/') + 1);

?>
@php
    $total_notification = allUnreadMessages();
    $profile_status = calculateProfileCompletePercentageStatus()['basic_profile_completness']
@endphp
@include('include.footer_left')
<div class="theiaStickySidebar">
<div id="sidebar-wrapper" class="navbar-collapse left-menu">

    <div class="left-menu-panel expertleft-menu">
        <ul id="sidebar_menu" class="sidebar-nav menu-btn">
            <li class="sidebar-brand"  id="menu-toggle">
                <span class="hide-lg-screen logged-username" href="javascript:void()">
                    {{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}
                </span>
                <a class="hide-lg-screen account-link" href="{{ url('expert/settings',[],$ssl) }}" title="Settings">Settings</a>
                <span id="main_icon" class="glyphicon glyphicon-align-justify"></span>
            </li>
        </ul>
        <ul class="sidebar-nav nav nav-pills nav-stacked" id="sidebar">
            <li class="browser-expert-icon @if (strpos($actual_link, 'projects-search') !== false || strpos($actual_link, 'project_view') !== false) <?php echo 'active'; ?>@endif">
                <a href="{{url('expert/projects-search',[],$ssl)}}" title="Browse Projects" class="@if($profile_status == TRUE)
                    @if(Auth::user()->admin_approval_status!=1)expert_profile_admin_unapproved @endif @else expert_profile_incomplete @endif">
                    Browse Projects
                </a>
            </li>
            <li class="message-icon @if($word=='messages' || explode('?', $word)[0] == 'projects_view') active @endif">
                <a href="{{ url('expert/messages',[],$ssl) }}" title="Messages">Messages
                    <span class="project_notifications">

                        @if ($total_notification > 0)
                            <span class="unread-count">
                        {{ $total_notification }}
                    </span>
                        @endif
                    </span>
                </a>
            </li>
            <li class="package-icon collapsed left-sub-menu" data-toggle="collapse" data-target="#products">
                <a href="javascript:void(0)" title="Service Package">Service Packages</a>
            </li>
            <ul class="@if(( $word=='create') ||($word=='servicepackages') || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo 'collapse in'; @endphp @endif submenu sub-menu collapse" id="products">
                <li class="@if( $word=='create')  @php echo 'active'; @endphp @endif @if($profile_status == TRUE)
                @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif">
                    <a href="{{url('servicepackage/create',[],$ssl)}}" title="Create a Service Package">
                        Create a Service Package
                    </a>
                </li>
                <li class="@if( $word=='servicepackages' || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo 'active'; @endphp @endif post-job-btn @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif">
                    <a href="{{url('servicepackages',[],$ssl)}}" title="My Service Packages">My Service Packages</a>
                </li>
            </ul>            @php $profile_percentage = '' @endphp
            @if($profile_status == TRUE)
                @if(Auth::user()->admin_approval_status!=1)
                    @php
                        $profile_percentage = 'expert_profile_admin_unapproved';
                    @endphp
                @endif
            @else
                @php $profile_percentage = 'expert_profile_incomplete' @endphp
            @endif
            <li class="my-service-hub hide  @if($word=='vendor-service-hubs' || strpos($actual_link, 'vendor-service-hubs') !== false) {{'active'}} @endif
            @if($profile_status == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved_for_service_hub  @endif @else expert_profile_incomplete @endif
                    ">
                <a href="{{route('vendor-service-hubs')}}" title="Service Hubs">Service Hubs</a>
            </li>
            <li class="profile-icon @if($word=='profile-summary') {{'active'}} @endif">
                <a href="{{url('expert/profile-summary',[],$ssl)}}" title="Profile">Profile</a>
            </li>

            <li class="hide-lg-screen">
                <a class="account-link" href="{{ url('expert/settings',[],$ssl) }}" title="Settings">Settings</a>
            </li>
            <?php if (isset(Auth::user()->id)) { ?>
            <li class="signout-icon hide-lg-screen">
                <a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a>
            </li>
            <?php } ?>
        </ul>
    </div>
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
     <ul class="nav navbar-nav">
        <li class="browser-expert-icon @if (strpos($actual_link, 'projects-search') !== false || strpos($actual_link, 'project_view') !== false)
                'active' @endif">
                <a href="{{url('expert/projects-search',[],$ssl)}}" title="Browse Projects" class="
                                @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
                                    @if(Auth::user()->admin_approval_status!=1) expert_profile_admin_unapproved
                                    @endif
                                @else expert_profile_incomplete @endif">Browse Projects
                </a>
        </li>
        <li class="message-icon @if($word=='messages') 'active' @endif"><a href="{{ url('expert/messages',[],$ssl) }}" title="Messages">Messages
                <span class="project_notifications">
                    @if ($total_notification > 0)
                        <span class="unread-count">
                        {{ $total_notification }}
                    </span>
                    @endif
                    </span>
            </a>
        </li>

        <li class="dropdown">
           <a href="javascript:void()" class="dropdown-toggle" data-toggle="dropdown" title="Service Package">Service Packages <span class="caret"></span></a>
           <ul class="@if(( $word=='create') ||($word=='servicepackages') || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo ''; @endphp @endif submenu sub-menu collapse dropdown-menu" id="products">
               <li class="@if( $word=='create')  @php echo 'active'; @endphp @endif @if($profile_status == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif">
                   <a href="{{url('servicepackage/create',[],$ssl)}}" title="Create a Service Package">Create a Service Package</a>
               </li>
               <li class="@if( $word=='servicepackages' || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo 'active'; @endphp @endif post-job-btn @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif">
                    <a href="{{url('servicepackages',[],$ssl)}}" title="My Service Packages">My Service Packages</a>
               </li>
            </ul>
            </li>
            <li class="profile-icon @if($word=='profile-summary') 'active' @endif">
                <a href="{{url('expert/profile-summary',[],$ssl)}}" title="Profile">Profile</a>
            </li>
            <li class="hide-lg-screen support-menu">
                <a class="account-link" href="{{ url('expert/settings',[],$ssl) }}" title="Settings">Settings</a>
            </li>
            <li class="">
                <a class="message-mm-support" href="javascript:void(0)" title="Support">
                <span>Support</span> </a>@include('htmlpanels.mm_support_panel')
            </li>
            <?php if (isset(Auth::user()->id)) { ?>
            <li class="signout-icon hide-lg-screen"><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li>
            <?php } ?>
         </ul>
     </div>
</nav>
