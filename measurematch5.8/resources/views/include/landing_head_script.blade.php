<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/gif" sizes="16x16">
   @php
    $ssl = getenv('APP_SSL');
    $random_number = getenv('CACHING_COUNTER');
     @endphp
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="public">
    <meta http-equiv="Cache-control" content="private">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache-control" content="no-store">
    <title>Data, Analytics & Marketing Technology Experts for Ambitious Marketers. On-Demand.</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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

    <!-- Bootstrap -->
    <link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
    <link href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
    <!-- <link href="{{ url('css/full-slider.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"> -->
    <link href="{{ url('css/homenavigation.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900italic,900,700italic,700,600italic,600,400italic,300italic,300,200italic,200' rel='stylesheet' type='text/css'>
    @include('include.segment_script')
</head>
