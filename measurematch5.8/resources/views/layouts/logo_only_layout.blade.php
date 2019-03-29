<!DOCTYPE html>
<html>
        @include('include.landing_head_script')
        <script type="text/javascript">
            base_url = "{{ url('/',[],$ssl) }}";
        </script>
        <div class="vertical_align_middle">
            <nav class="navbar navbar-default navbar-fixed-top topnav">
                <div class="container">
                    <a class="pull-left logo" href="{{ homeUrlWebflow()}}">
                        <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                        <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
                    </a>
                    <?php echo getUserType(); ?>           

                    @if(request()->segment(1) == 'signup')
                    <a class="nav-login-btn" href="{{url('login',[],$ssl)}}" title="Login">Login</a>
                    @endif
                </div>
            </nav> 
        
        <body>
        @include('include.googleTagsScriptsBody')
        @yield('content')
        @include('include.global_layout_parent')
        @yield('footer')
        </body>
</html>