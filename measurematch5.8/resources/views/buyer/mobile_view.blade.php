<!DOCTYPE html>	
<html>	
    <head>	
        @php	
        $segment_one = request()->segment(1);	
        $segment_two = request()->segment(2);	
        @endphp	
        @include('include.layout_head')
    </head>	
    <body class="buyer-mobile-bg">	
        <script type="text/javascript">  var base_url = "{{ url('/',[],$ssl) }}";</script>
        @include('include.googleTagsScriptsBody')
        <nav class="navbar navbar-default navbar-fixed-top topnav @if($segment_one == 'project' && $segment_two == 'create' && (Auth::user()->admin_approval_status != config('constants.APPROVED'))) post-project-page @endif">	
            <div class="container-fluid">	
                <!-- Brand and toggle get grouped for better mobile display -->	
                <a href="javascript:void(0)" class="pull-left logo" title="MeasureMatch">	
                    <img class="img-responsive" src="{{ url('images/MM_logo_mobile.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />	
                </a>	
                <div class="pull-right top-menu">	
                    <ul class="nav navbar-nav support-menu">	
                        <li><a class="message-mm-support" href="javascript:void(0)" ><span class="support-link">Logout</span> </a></li>	
                    </ul>	
                </div>	
            </div>	
        </nav>	
        <section class="buyer_mobile_secton">	
            <div class="container">	
                <div class="row">	
                    <div class="col-sm-12">	
                        <div class="block">	
                            <img class="" src="{{ url('images/buyer-mobile-computer.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />	
                            <h1 class="block_title gilroyregular-semibold">MeasureMatch only works on Desktop</h1>	
                            <p class="block_text gilroyregular-font">Please visit MeasureMatch via your Desktop (on your laptop or computer) as we do not currently support a mobile experience.</p>	
                            <p class="block_text gilroyregular-font">Need help with something specific?</p>	
                            <a class="standard-btn" href="mailto:support@measurematch.com">Contact Us</a>	
                        </div>	

                     </div>	
                </div>	
            </div>	
        </section>	
        @yield('content')	
        <script type="text/javascript" src="{{ url('js/common_buyer_pages.js?js='.$random_number,[],$ssl)}}"></script>	
        @include('include.global_layout_parent')	
    </body>	
</html>