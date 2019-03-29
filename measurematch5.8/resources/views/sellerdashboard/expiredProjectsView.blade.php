@extends('layouts.expert_layout')
@section('content')
<div id="wrapper" class="active find_next_opportunity_wrap">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar">

                    @include('sellerdashboard.sidemenu')
            </div>
            <div class="col-md-9 rightcontent-panel">
                <div class="theiaStickySidebar">
                    <div class="col-lg-12">
                        <div class="expired-section">
                            <div class="expired-content">
                            <img alt="cross" src="{{ url('images/close-eye.svg',[],$ssl) }}">
                            <h2 class="font-20 gilroyregular-semibold">This Project is no longer available</h2>
                            <p class="margin-0">Yikes! The project brief you were hoping to see did one of the following:</p>
                            <p>- Reached the maximum visibility time (project briefs are visible for only 3 days, unless extended by the Client)<br>
                                - Was moved into a hidden state because the Client received lots of amazing EOIs (Expressions of Interest)<br>
                                - Has gone into contract (project briefs get snapped up fast here)</p>
                            <p class="margin-top-32">But fear not! More project briefs are on the way ;). Keep MeasureMatch on speed dial! Thank you.</p>
                            <a href="{{url('expert/projects-search',[],$ssl)}}" title="Browse Projects" class="btn standard-btn gilroyregular-semibold">Browse Projects</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('include.basic_javascript_liberaries')
<script src="{{url('js/sellerJobView.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
