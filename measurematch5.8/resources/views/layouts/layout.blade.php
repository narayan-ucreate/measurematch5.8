<!DOCTYPE html>
<html>
<head>
    @include('include.layout_head')
</head>
<body>

<script type="text/javascript">  var base_url = "{{ url('/',[],$ssl) }}";</script>
@include('include.googleTagsScriptsBody')
<nav class="navbar navbar-default navbar-fixed-top topnav">
    <div class="container-fluid">
        <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
            <img class="img-responsive logo-lg" src="{{url('images/logo.svg',[],$ssl)}}" width="172" alt="MeasureMatch"  />
            <img class="img-responsive logo-md" src="{{url('images/mm-logo-stealth.svg',[],$ssl)}}" width="44" alt="MeasureMatch"  />
        </a>
        <?php echo getUserType(); ?>

        <div class="pull-right top-menu">
            <ul class="nav navbar-nav support-menu expert-support-menu">
                <li><a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span> </a>@include('htmlpanels.mm_support_panel')</li>
            </ul>
            <div class="navbar-header">
                <ul class="nav navbar-nav navbar-right hide-small-screen">
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $word = substr($actual_link, strrpos($actual_link, '/') + 1);
                    ?>
                    <li class="username_li">
                                <span class="dropdown"> <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('expert/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</a> <span class="caret"></span> </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">

                                        <li @if($word=='settings') class=<?php echo 'active'; ?>@endif><a href="{{ url('expert/settings',[],$ssl) }}">Settings</a></li>
                                        <?php if (isset(Auth::user()->id)) { ?><li>
                                            <a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign out</a></li>
                                               <form id="logout-form" action="{{ url('/logout',[],$ssl) }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>

                                        <?php } ?>
                                    </ul>
                                </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>


<div id="wrapper" class="active">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar custom-left-sidebar">
                @if (\Auth::user()->user_type_id === config('constants.EXPERT'))
                    @include('sellerdashboard.sidemenu')
                @else
                    @include('buyer.sidemenu')
                @endif
            </div>
            <div class="col-md-9 rightcontent-panel">
                <div class="theiaStickySidebar">
                    <div class="row">
                        <div class="col-md-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@yield('footer')
@yield('scripts')
@include('include.global_layout_parent')
<script type="text/javascript" src="{{ url('js/common_expert_pages.js?js='.$random_number,[],$ssl)}}"></script>
</body>
</html>
