<!DOCTYPE html>
<html>
    <head>
       <?php
        $current_method = last(explode('/', url()->current()));
        ?>
        <title>Sign up to MeasureMatch</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta charset="utf-8">

        <meta name="description" content="MeasureMatch is the on-demand professional services marketplace where companies go to get important data, analytics & technology project work done fast."/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta property="og:site_name" value="MeasureMatch" />
        <meta property="og:title" content="Sign up to MeasureMatch" />
        <meta property="og:description" content="MeasureMatch is the on-demand professional services marketplace where companies go to get important data, analytics & technology project work done fast." />
        <meta property="og:image" content="{{url('images/mm-open-graph-signup.png',[],$ssl) }}" />
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
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="cache-control" content="no-cache">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->

        <link href="{{url('css/bootstrap.min.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        <link href="{{url('css/font-awesome.min.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        <link href="{{url('css/global_stylesheet.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        <link href="{{url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        <link href="{{url('css/international-phone-codes.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        <link href="{{url('css/mobile-ipad-nav.css?css='.$random_number,[],$ssl)}}" rel="stylesheet">
        @include('include.segment_script')
        <script type="text/javascript">
            var base_url = "{{ url('/',[],$ssl) }}";
        </script>
    </head>
    <body>
    @include('include.googleTagsScriptsBody')
        <nav class="navbar navbar-default navbar-fixed-top topnav">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <a class="navbar-brand pull-left col-lg-3 col-md-3 col-sm-4" href="{{ homeUrlWebflow() }}">
        <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
    </a>
    @if($current_method=='finalstep')
        <a class="nav-login-btn" href="{{url('post-project-login-from-homepage',[],$ssl)}}" title="Login">Login</a>
    @else
        <a class="nav-login-btn" href="{{url('login',[],$ssl)}}" title="Login">Login</a>
    @endif


    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
  </div>
  <!-- /.container-fluid -->
</nav>

@yield('content')
<script type="text/javascript" src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/international-phone-codes.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/centralized_signup.js?js='.$random_number,[],$ssl) }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@include('include.global_layout_parent')
<!-- Include all compiled plugins (below), or include individual files as needed -->

</body>
</html>
</html>
