<!DOCTYPE html>
<html>
<head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta name="msapplication-TileColor">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta charset="utf-8">

<meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Data, Analytics & Marketing Technology Experts for Ambitious Marketers. On-Demand.</title>
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
<!-- <link href="{{ url('css/full-slider.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css"> -->
@include('include.googleTagsScripts')
<script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/jquery-ui.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/jasny-bootstrap.min.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
<link rel="stylesheet" href="{{ url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}">
<link href="{{ url('css/bootstrap-datetimepicker.css?css='.$random_number,[],$ssl)}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{ url('css/jquery-ui.css?css='.$random_number,[],$ssl) }}">
<link rel="stylesheet" href="{{ url('css/left_menu.css?css='.$random_number,[],$ssl) }}">
<link rel="stylesheet" href="{{ url('css/jasny-bootstrap.min.css?css='.$random_number,[],$ssl) }}">
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900italic,900,700italic,700,600italic,600,400italic,300italic,300,200italic,200' rel='stylesheet' type='text/css'>
<link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
<link href="{{ url('css/demo_style.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css">
<link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
<link href="{{ url('css/mobile-ipad-nav.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css">
<script src="{{ url('js/jquery.cropit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('/js/cropit_exif_fix.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.segment_script')
@yield('header')
</head>
<body>
@include('include.googleTagsScriptsBody')
@yield('content')

@yield('footer')

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ url('/js/layoutedit.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.global_layout_parent')
<!--end of edit course-->
</body>
</html>
