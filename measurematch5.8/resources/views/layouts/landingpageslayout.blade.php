<!DOCTYPE html>
<html lang="en">
    @include('include.landing_head_script')

    @if(!Auth::check())
    <body class="white-bg fixednavbody">
        @include('include.landingheader')
        @else
    <body class="white-bg fixednavbody">
        @include('include.loggedinnav')
        @endif

        @include('include.googleTagsScriptsBody')
        <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
        @yield('content')
        <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
        @if(Auth::check())
        @include('include.footer')
        @else
        @include('include.footer')
        @endif
        @include('include.global_layout_parent')
    </body>
</html>