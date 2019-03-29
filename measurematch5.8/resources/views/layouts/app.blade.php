<!DOCTYPE html>
<html lang="en">
<head>
<title>Data, Analytics & Marketing Technology Experts for Ambitious Marketers. On-Demand.</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta charset="utf-8">

<meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
    <!-- Fonts -->
    <link href="{{ url('css/font-awesome.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
      <link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <!-- Styles -->
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
 
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/',[],$ssl) }}">
                    MeasureMatch
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/admin/dashboard',[],$ssl) }}"></a></li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login',[],$ssl) }}"></a></li>
                        <li><a href="{{ url('/register',[],$ssl) }}"></a></li>
                    @else
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout',[],$ssl) }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <!-- JavaScripts -->
      <?php
        $ssl = getenv('APP_SSL');
        $random_number = getenv('CACHING_COUNTER');
        ?>
        <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
        @include('include.global_layout_parent')
</body>
</html>
