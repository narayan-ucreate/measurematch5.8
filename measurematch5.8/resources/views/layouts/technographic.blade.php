<!DOCTYPE html>
<html>
@include('include.landing_head_script')
<body class="webflow-bg company-search-dropdown">
@include('include.googleTagsScriptsBody')

<link rel="stylesheet" href="{{ url('css/measurematch-hero.webflow.css?css='.$random_number,[],$ssl) }}">
<link rel="stylesheet" href="{{ url('css/webflow.css?css='.$random_number,[],$ssl) }}">
<link rel="stylesheet" href="{{ url('css/font-awesome.min.css?css='.$random_number,[],$ssl) }}">
<nav class="navbar navbar-default topnav header-menu webflow-header">
    <div class="container">
        <a href="{{homeUrlWebflow()}}" class="navbar-brand" title="MeasureMatch"><img class="img-responsive" src="{{ url('images/logo.svg',[],$ssl) }}" alt="MeasureMatch" /></a>
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav pull-right header-login-btn" style="@if(!$is_ajax_request) display: none; @endif" id="default_nav">
                @if(Auth::check())
                    <li class="webflow-search">
                        <input class="form-control company-search" placeholder="Start typing a company name…" value="{{$domain}}" name=""  type="text">
                        <a class="cross clear-search @if ($domain == '') hide @endif" href="#">
                            <img class="img-responsive" src="{{ url('images/cross-blue.svg',[],$ssl) }}" alt="MeasureMatch" />
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{url('login',[],$ssl)}}" title="Login">Login</a>
                </li>
                <li class="gilroyregular-semibold"><a class="btn standard-btn" href="@if(Auth::Check()) {{url('login',[],$ssl)}} @else {{url('signup',[],$ssl)}} @endif" title="">Sign up</a></li>
            </ul>
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>

@yield('content')
<footer class="webflow-footer" style="@if(!$is_ajax_request) display: none; @endif" id="default_footer">
    <div class="container">
        <div class="row">
            <div class="footer-border">
                <div class="col-md-6">
                    <a href="{{homeUrlWebflow()}}" class="" title="MeasureMatch"><img class="img-responsive" src="{{ url('images/logo.svg',[],$ssl) }}" alt="MeasureMatch" /></a>
                    <p>
                        Amazing Technology & Data Expertise for Every Ambitious marketing, Commerce & Customer Experience Business Leader.<br> On Demand.
                    </p>
                    <p class="copyright">© 2016 - {{date('Y')}} MeasureMatch Ltd</p>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li><a href="https://web.measurematch.com/terms-of-service" target="_blank" title="Terms of service">Terms of Service</a></li>
                        <li class="blog-bullet">•</li>
                        <li><a href="https://web.measurematch.com/privacy-policy" target="_blank">Cookie Policy</a></li>
                        <li class="blog-bullet">•</li>
                        <li><a href="https://web.measurematch.com/code-of-conduct" target="_blank">Code of Conduct</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>


<script>
    var base_url = "{{ url('/',[],$ssl) }}";
</script>
@yield('script')

</body>
</html>
