<!DOCTYPE html>
<html>
    <head>
        <!-- Google Tag Manager -->
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="cache-control" content="no-cache">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta charset="utf-8">
        <meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Data, Analytics & Marketing Technology Experts for Ambitious Marketers. On-Demand.</title>
        @include('include.googleTagsScripts')
        <link rel="apple-touch-icon" sizes="57x57" href="{{ url('fav.ico/apple-icon-57x57.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ url('fav.ico/apple-icon-60x60.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ url('fav.ico/apple-icon-72x72.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ url('fav.ico/apple-icon-76x76.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ url('fav.ico/apple-icon-114x114.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ url('fav.ico/apple-icon-120x120.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ url('fav.ico/apple-icon-144x144.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ url('fav.ico/apple-icon-152x152.png',[],$ssl) }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('fav.ico/apple-icon-180x180.png',[],$ssl) }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ url('fav.ico/android-icon-192x192.png',[],$ssl) }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('fav.ico/favicon-32x32.png',[],$ssl) }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ url('fav.ico/favicon-96x96.png',[],$ssl) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('fav.ico/favicon-16x16.png',[],$ssl) }}">
        <link rel="manifest" href="{{ url('fav.ico/manifest.json',[],$ssl) }}">
        <meta name="msapplication-TileColor">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
        <link href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css">
        <link href="{{url('css/message.css',[],$ssl)}}" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            base_url = "{{ url('/',[],$ssl) }}";
        </script>
        <link rel="stylesheet" href="{{ url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}">
        <link rel="stylesheet" href="{{ url('css/font-awesome.min.css?css='.$random_number,[],$ssl)}}">
        <link rel="stylesheet" href="{{ url('css/left_menu.css?css='.$random_number,[],$ssl)}}">
        <link href="{{ url('css/bootstrap-datetimepicker.css?css='.$random_number,[],$ssl)}}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ url('css/jasny-bootstrap.min.css?css='.$random_number,[],$ssl) }}">
        <link rel="stylesheet" href="{{ url('css/bootstrap.vertical-tabs.min.css?css='.$random_number,[],$ssl)}}">
        <link rel="stylesheet" href="{{ url('css/jquery-ui.css?css='.$random_number,[],$ssl) }}">
        <link href="{{ url('css/mobile-ipad-nav.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
        @include('include.segment_script')
</head>
@if ($user_type == config('constants.BUYER'))
  <nav class="navbar navbar-default navbar-fixed-top topnav">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
                        <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
    </a><?php echo getUserType(); ?>
      <div class="pull-right top-menu">
          <ul class="nav navbar-nav support-menu">
                        <li><a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span> </a>@include('htmlpanels.mm_support_panel')</li>
                    </ul>
          @include('include.buyerrightdropdown')
</div>

    </div><!-- /.container-fluid -->
  </nav>
@php $current_url = url()->current(); @endphp
@if (strpos($current_url, 'messages') == false)
    @include('include.buyer_mobile_nav')
@endif
@else

    <nav class="navbar navbar-default navbar-fixed-top topnav">
        <div class="container-fluid">
            <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch"><img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  /></a><?php echo getUserType(); ?>
            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="pull-right top-menu">
            <ul class="nav navbar-nav support-menu expert-support-menu">
            <li><a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span></a>@include('htmlpanels.mm_support_panel')</li>
            </ul>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-header">
                <ul class="nav navbar-nav pull-right hide-small-screen">
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $word = substr($actual_link, strrpos($actual_link, '/') + 1);
                    ?>

                    <li class="username_li"><span class="dropdown"> <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('expert/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</a> <span class="caret"></span> </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
                            @if(Auth::check() && Auth::user()->status != config('constants.SIDE_HUSTLER'))
                                <li @if($word=='settings') class="{{ 'active' }}" @endif><a href="{{ url('expert/settings',[],$ssl) }}"> Settings </a></li>
                            @endif
                            @if (isset(Auth::user()->id)) <li><a id="signout" title="Sign out" href="{{ url('/logout',[],$ssl) }}"> Sign out </a></li> @endif
                            </ul>
                        </span>
                    </li>
                </ul>
            </div>
            </div>

        </div>
    </nav>

@endif

<body>
    @include('include.googleTagsScriptsBody')
    <script type="text/javascript"  src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
    @include('include.global_layout_parent')
    @yield('content')

    @yield('footer')
    @if(Auth::user()->admin_approval_status != config('constants.APPROVED'))
        @include('buyer.postjobs.account_review_pop_up')
    @endif
    @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
        @if(Auth::user()->admin_approval_status!=1)
            @include('expert_profile_admin_unapproved_modal')
        @endif
    @else
        @include('expert_profile_incomplete_modal')
    @endif
    <script  type="text/javascript" src="{{url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src="{{url('js/moment-timezone.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
    <script  type="text/javascript" src="{{url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src="{{url('js/jquery-ui.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src="{{url('js/jasny-bootstrap.min.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src="{{url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
    <script  type="text/javascript" src='{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}'></script>
    <script type="text/javascript" src="{{ url('js/business_information.js?js='.$random_number,[],$ssl) }}"></script>
    <script  type="text/javascript" >
        var base_url = "{{url('/',[],$ssl)}}";
        var loading_url_img = "{{ url('/images/loading.gif',[],$ssl) }}";
        $('#timezone').val(moment.tz.guess());
    </script>
    <script  type="text/javascript" src="{{url('js/course.js?js='.$random_number,[],$ssl)}}"></script>

</body>
</html>
