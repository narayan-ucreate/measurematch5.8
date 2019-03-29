<!DOCTYPE html>
<html>
    <head>
        @php
        $segment_one = request()->segment(1);
        $segment_two = request()->segment(2);
        @endphp
        @include('include.layout_head')
    </head>
    <body class="post-job-page buyer-profile-page buyer-dashboard-page">
        <script type="text/javascript">  var base_url = "{{ url('/',[],$ssl) }}";</script>
        @include('include.googleTagsScriptsBody')
        <nav class="navdesktopviewbuyer navbar navbar-default navbar-fixed-top topnav @if($segment_one == 'project' && $segment_two == 'create' && (Auth::user()->admin_approval_status != config('constants.APPROVED'))) post-project-page @endif">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
                    <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                    <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
                </a>
                <?php echo getUserType(); ?>
                <div class="pull-right top-menu">
                    <ul class="nav navbar-nav support-menu">
                        <li>
                            <a class="message-mm-support" href="javascript:void(0)" title="Support"><span class="support-link">Support</span> </a>@include('htmlpanels.mm_support_panel')
                        </li>
                    </ul>
                    @include('include.buyerrightdropdown')
                </div>
            </div>
        </nav>
        @include('include.buyer_mobile_nav')
        <div id="wrapper" class="active buyerdesktop_buyer">
            <div id="page-content-wrapper">
                <div class="page-content inset">
                    <div class="col-md-3 leftSidebar">
                        @include('buyer.sidemenu')
                    </div>
                    <div class="col-md-9 rightcontent-panel">
                        <div class="theiaStickySidebar">
                            <div class="col-lg-12">
                                @yield('content')
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        @if(Auth::check() && Auth::user()->admin_approval_status != config('constants.APPROVED'))
            @include('buyer.postjobs.account_review_pop_up')
        @endif
        <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/side-menu.js?js='.$random_number,[],$ssl) }}"></script>
        <script>
            var base_url = "{{ url('/',[],$ssl) }}";
        </script>
        @yield('script')
        <script type="text/javascript" src="{{ url('js/common_buyer_pages.js?js='.$random_number,[],$ssl)}}"></script>
        @include('include.global_layout_parent')
    </body>
</html>
