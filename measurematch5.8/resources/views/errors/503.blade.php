<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>
        <meta name="description" content="Digital experience analytics and customer experience transformation is easily executed with MeasureMatch's global network of independent data scientists, data engineers and technology pros. On-demand. Do more now!">
        @include('include.googleTagsScripts')
        <link rel="stylesheet" href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}">
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}">
        <link rel="stylesheet" href="{{ url('css/error.css?css='.$random_number,[],$ssl) }}">
    </head>
    <body class="errorpage">
        @include('include.googleTagsScriptsBody')
        <div class="wrapper">
            <div class="errorwrap">
                <div class="container">
                    <div class="col-lg-12"><img  class="logo-home" src="{{ url('images/header_logo_error.png',[],$ssl) }}" style="width:400px;" /></div>
                    <div class="clearfix"></div>
                    <div class="col-md-10 col-md-offset-1">
                        <h2>Sorry...<br/>It's not for you.<br/>It's us.</h2>
                        <h3>An error occurred and your request couldn't be completed. Please try again later or contact <a href="mailto:support@measurematch.com">support@measurematch.com.</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
