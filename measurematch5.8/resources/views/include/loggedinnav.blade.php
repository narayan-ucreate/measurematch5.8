@if(Auth::user()->user_type_id == 2)
<nav class="navbar navbar-default navbar-fixed-top topnav faq-navigation faq-navigation-buyer">
                <div class="container-fluid">
                    <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
                        <img class="img-responsive" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                    </a>
                    <?php echo getUserType(); ?>
                         <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="pull-right top-menu">
                    <ul class="nav navbar-nav support-menu expert-support-menu">
                    <li><a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span></a>@include('htmlpanels.mm_support_panel')</li>
                    </ul>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$word = substr($actual_link, strrpos($actual_link, '/') + 1);
?>
        <ul class="nav navbar-nav navbar-right">
            @if(Auth::user()->admin_approval_status == config('constants.APPROVED'))
                <li class="find-expert-icon @if(strpos($actual_link, 'buyer/experts/search') !== false || strpos($actual_link, 'expert-profile-detail') !== false) <?php echo 'active'; ?>@endif"><a href="{{url('buyer/experts/search',[],$ssl)}}" title="Browse Experts">Browse Experts</a></li>
                <li class="project-icon dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Projects">Projects<span class="caret"></span></a>
                  <ul class="dropdown-menu  @php if( ($word=='myprojects') ||(strpos($actual_link, 'post') !== false || strpos($actual_link, 'editpost') !== false || strpos($actual_link, 'publish_projects_view') !== false)){ echo 'collapse in'; } @endphp" id="products">
                    <li class="@if( $word=='myprojects') @php echo 'active'; @endphp @endif"><a href="{{url('myprojects',[],$ssl)}}" title="All Projects">My Projects</a></li>

                    <li class="@if (strpos($actual_link, 'post') !== false || strpos($actual_link, 'editpost') !== false || strpos($actual_link, 'publish_projects_view') !== false) @php echo 'active'; @endphp @endif post-job-btn"><a  href="{{url('project/create',[],$ssl)}}" title="Submit a Project">Submit a Project</a></li>
                   </ul>
                </li>

                <li class="package-icon dropdown">
                    <a data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="javascript:void(0)" class="dropdown-toggle" title="Service Package">Service Package<span class="caret"></span></a>
                       <ul class="@if(( $word=='types') ||($word=='servicepackages')) @php echo 'collapse in'; @endphp @endif dropdown-menu" id="packages">
                       <li class="@if( $word=='types')  @php echo 'active'; @endphp @endif"><a href="{{url('servicepackage/types',[],$ssl)}}" title="Find a Service Package">Browse Service Packages</a></li>
                       <li class="@if( $word=='myservicepackages')  @php echo 'active'; @endphp @endif"><a href="{{url('myservicepackages',[],$ssl)}}" title="My Service Packages">My Service Packages</a></li>
                   </ul>
                </li>
                <li class="message-icon @if($word=='messages') <?php echo 'active'; ?>@endif"><a href="{{ url('buyer/messages',[],$ssl) }}" title="Messages">Messages <span class="unread-count">{{ allUnreadMessages() }}</span></a></li>
            @endif
            <?php if (isset(Auth::user()->id)) { ?>
            <!--<li class="signout-icon"><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li><?php }  ?>                            -->
            <li class="username_li @if($word=='profile-summary' || $word=='account' || $word=='dashboard'||$word=='myprojects') {{'active'}}@endif">
              <span class="dropdown"> <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('buyer/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</a> <span class="caret"></span> </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
                    @if(Auth::user()->admin_approval_status == config('constants.APPROVED'))
                        <li class="profile-icon @if($word=='profile-summary') <?php echo 'active'; ?>@endif"><a href="{{url('buyer/profile-summary',[],$ssl)}}" title="Profile">Profile</a></li>
                    @endif
                    <li @if($word=='settings') class=<?php echo 'active'; ?>@endif><a href="{{ url('buyer/settings',[],$ssl) }}">Settings</a></li>
                    <?php if (isset(Auth::user()->id)) {?>
                    <li><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li><?php }
                    ?>
                </ul>
              </span>
            </li>
        </ul>
      </div>
   </div>
</div>
</nav>

@elseif(Auth::user()->user_type_id == 1)
<nav class="navbar navbar-default navbar-fixed-top topnav faq-navigation faq-navigation-expert">
    <div class="container-fluid">
        <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch"><img class="img-responsive" src="{{url('images/logo.svg',[],$ssl)}}" width="172" alt="MeasureMatch"  /></a>
            <?php echo getUserType(); ?>
      <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
      <div class="pull-right top-menu">
        <ul class="nav navbar-nav support-menu">
            <li>
              <a class="message-mm-support" href="javascript:void(0)" title="Support">
                <span class="support-link">Support</span>
              </a>@include('htmlpanels.mm_support_panel')
            </li>
         </ul>
        <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
          <div class="pull-right top-menu">
            <div class="navbar-header">
              <ul class="nav navbar-nav navbar-right">
                  <?php
                  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                  $word = substr($actual_link, strrpos($actual_link, '/') + 1);
                  ?>
                  <li class="home-icon @if (strpos($actual_link, 'dashboard') !== false || strpos($actual_link, 'projects_view') !== false || strpos($actual_link, 'project_dashboardview') !== false || strpos($actual_link, 'unsecured_roles') !== false || strpos($actual_link, 'secured_roles') !== false || strpos($actual_link, 'contract_view') !== false) <?php echo 'active'; ?>@endif"><a href="{{url('expert/dashboard',[],$ssl)}}" title="Home">Home</a></li>
                  <li class="find-expert-icon @if (strpos($actual_link, 'projects-search') !== false || strpos($actual_link, 'project_view') !== false) <?php echo 'active'; ?>@endif"><a href="{{url('expert/projects-search',[],$ssl)}}" title="Projects" class="@if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_admin_unapproved @endif @else expert_profile_incomplete @endif">Projects</a></li>
                  <li class="message-icon @if($word=='messages') <?php echo 'active'; ?>@endif"><a href="{{ url('expert/messages',[],$ssl) }}" title="Messages">Messages {{ allUnreadMessages() }}</a></li>
                  <li class="package-icon username_li collapsed left-sub-menu">
                      <a href="javascript:void(0)" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Service Package">Service Package<span class="caret"></span> </a>
                     <ul aria-labelledby="dropdownMenuDivider" class="dropdown-menu @if(( $word=='create') ||($word=='servicepackages') || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo 'collapse in'; @endphp @endif submenu sub-menu collapse" id="products">
                      <li class="@if( $word=='create')  @php echo 'active'; @endphp @endif @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif"><a href="{{url('servicepackage/create',[],$ssl)}}" title="Create Service Package">Create Service Package</a></li>
                      <li class="@if( $word=='servicepackages' || $word=='servicepackages?visible=true' || $word=='servicepackages?hidden=true' || $word=='servicepackages?deleted=true') @php echo 'active'; @endphp @endif post-job-btn @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_unapproved @endif @else expert_profile_incomplete @endif"><a href="{{url('servicepackages',[],$ssl)}}" title="My Service Packages">My Service Packages</a></li>
                    </ul>
                </li>
                <li class="username_li">
                <a id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="{{ url('expert/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}} <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
                    <li class="profile-icon @if($word=='profile-summary') <?php echo 'active'; ?>@endif"><a href="{{url('expert/profile-summary',[],$ssl)}}" title="Profile">Profile</a></li>
                    <li @if($word=='settings') class=<?php echo 'active'; ?>@endif><a href="{{ url('expert/settings',[],$ssl) }}">Settings</a></li>
                    <?php if (isset(Auth::user()->id)) { ?><li><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li><?php } ?>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

@endif
