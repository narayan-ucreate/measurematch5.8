@php
$parameters = app('request')->input();
$expired=(isset($project->job_end_date) && (strtotime(date('d-m-Y')) > strtotime(date('d-m-Y',strtotime($project->job_end_date))))); 
$rejected=(isset($project->publish) && ($project->publish==config('constants.PROJECT_REJECTED'))); 
@endphp
<ul>
    <li class="admin-logo-sidebar">
        <a href="{{url('admin/buyerListing',[],$ssl)}}"><img src="{{url('images/logo-sm.svg',[],$ssl) }}" alt="logo icon" />
            <span>Admin</span>
        </a>
    </li>
    <li class="@if(Request::segment(2)=='pendingBuyers' || 
        Request::segment(2)=='buyerPendingView' ||
        Request::segment(2)=='buyerListing' || 
        Request::segment(2)=='buyerView' || 
        Request::segment(2)=='buyerEdit' || 
        Request::segment(2)=='getProjects' ||
        Request::segment(2)=='buyerArchievedView' ||
        Request::segment(2)=='archivedBuyersListing' ||
        (Request::segment(2)=='unverifiedBuyers' || 
        array_key_exists('from_buyer_project_listing', app('request')->input()) || 
        Request::segment(2)=='viewUnverifiedBuyer'))
        {{'active'}} @endif">
        <a href="{{ url('admin/buyerListing',[],$ssl)}}" title="Clients">
            Clients
        </a>
    </li>
    <li class="@if(in_array(Request::segment(2), config('constants.ADMIN_EXPERT_VIEWS'))) {{ 'active' }} @endif">
        <a href="{{ url('admin/expertListing',[],$ssl) }}" title="Experts">
            Experts
        </a>
    </li>
    <li class="@if(in_array(Request::segment(2), config('constants.ADMIN_VENDORS_VIEWS'))) {{ 'active' }} @endif">
        <a href="{{ url('admin/vendorListing',[],$ssl) }}" title="Vendors">
            Vendors
        </a>
    </li>
    <li class="@if(in_array(Request::segment(2), config('constants.ADMIN_HUBS_VIEWS'))) {{ 'active' }} @endif">
        <a href="{{ url('admin/pendingHubs',[],$ssl) }}" title="Hubs">
            Hubs
        </a>
    </li>
    <li class="@if(!array_key_exists('from_buyer_project_listing', app('request')->input()) &&
        (Request::segment(2)=='liveProjects' || 
        (isset($project->publish) && 
        ($project->publish == config('constants.PUBLISHED'))) && 
        (!$rejected && !$expired) ||
        Request::segment(2)=='pendingProjects' || 
        (isset($project->publish) && 
        ($project->publish==config('constants.PROJECT_PENDING'))) && 
        (!$expired) ||
        (Request::segment(2)=='archivedProjects' || 
        ($rejected)) ||
        (Request::segment(2)=='expiredProjects' 
        || (!$rejected && $expired)))
        || Request::segment(2)=='inContractProjects')
        {{'active'}}@endif">
        <a href="{{ url('admin/pendingProjects',[],$ssl)}}" title="Projects">
            Projects
        </a>
    </li>
    <li class="@if(Request::segment(2)=='alldraftedservicepackages' || 
        Request::segment(2)=='draftedservicepackage' || 
        (array_key_exists('all_draft', $parameters) && 
        $parameters['all_draft'] == 'true') ||
        Request::segment(2)=='pendingservicepackages' || 
        Request::segment(2)=='editpendingservicepackage' || 
        Request::segment(2)=='pendingservicepackage' || 
        (array_key_exists('approved', $parameters) && 
        $parameters['approved'] == 'false') || 
        Request::segment(2)=='servicepackages' || 
        Request::segment(2)=='approvedservicepackage' || 
        (array_key_exists('approved', $parameters) && 
        $parameters['approved'] == 'true') ||
        Request::segment(2)=='rejectedservicepackages' || 
        Request::segment(2)=='rejectedservicepackage')
        {{'active'}}@endif">
        <a href="{{ url('admin/servicepackages',[],$ssl) }}" title="Service Packages">
            Service Packages
        </a>
    </li>
</ul>