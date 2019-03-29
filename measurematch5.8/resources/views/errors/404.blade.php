<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
        @include('include.googleTagsScripts')
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300,100,900' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}">
        <link rel="stylesheet" href="{{ url('css/error.css?css='.$random_number,[],$ssl) }}">
        <link rel="stylesheet" href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}">
    </head>
    <body class="errorpage">
        @include('include.googleTagsScriptsBody')
         <div class="wrapper">
            <div class="errorwrap">
                <div class="container">
                    <div class="col-lg-12"><a href="{{url('/')}}"><img  class="logo-home" src="{{ url('images/header_logo_error.png',[],$ssl) }}" style="width:400px;" /></a></div>
                    <div class="clearfix"></div>
                    <h2>404 Error</h2>
                    <h3>Page doesn’t exist or some other error has occurred.<br>
                        <a href="javascript:history.go(-1);"><b>Click here</b></a> to return to the previous page.</h3>
                </div>
            </div>
        </div>
    </body>
</html>
