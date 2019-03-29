@extends('layouts.adminlayout')
@section('content')
<?php
$orderBy = orderBy($_REQUEST);
if(isset($result)){
    $total = $result->total();
}else{
    $total = $published_service_package_count;
}
$experts_count = (new \App\Components\CommonFunctionsComponent)->expertsCount();
?>
<section class="content admin-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-xs-10 col-sm-10  col-lg-9">
                <div class="box">
                    <div class="box-header"> 
                        <h1 class="box-heading">Experts</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout  <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="pull-right message-section">
                            <p class="success"> @if(Session::has('success'))
                                {{Session::get('success')}}
                                @endif </p>
                        </div>
                        <div class="admin-subtab">
                            <ul>
                                <li>
                                    <a @if(app('request')->segment(2)=='expertListing'
                                        || app('request')->segment(2)=='servicepackages'
                                        || app('request')->segment(2)=='expert'
                                        )
                                        class="active" @endif href="{{ url('admin/expertListing',[],$ssl) }}">
                                        Approved ({{$experts_count['approved']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='pendingExperts')
                                        class="active" @endif href="{{ url('admin/pendingExperts',[],$ssl) }}">
                                        To Interview ({{$experts_count['to_interview']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='incompleteProfileExperts')
                                        class="active" @endif href="{{ url('admin/incompleteProfileExperts',[],$ssl) }}">
                                        Profile Incomplete ({{$experts_count['profile_incomplete']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='notverifiedexperts')
                                        class="active" @endif href="{{ url('admin/notverifiedexperts',[],$ssl) }}">
                                        Unverified ({{$experts_count['unverified']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='sideHustlersExperts')
                                       class="active" @endif href="{{ url('admin/sideHustlersExperts',[],$ssl) }}">
                                        Side Hustlers ({{$experts_count['side_hustlers']}})
                                    </a>
                                </li>
                                <li><a @if(app('request')->segment(2)=='archivedExpertsListing') 
                                        class="active" @endif href="{{ url('admin/archivedExpertsListing',[],$ssl) }}">
                                        Archived ({{$experts_count['archived']}})
                                    </a>
                                </li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            @if(app('request')->segment(2)=='expertListing')
                                @include('admin.admin.experts_listing')
                                @php
                                    $export_url = '/admin/expertExportListing?status='.config("constants.APPROVED").'&admin_approved_status='.config("constants.APPROVED");
                                    $export_label = 'Export All Approved Experts';
                                @endphp
                            @elseif(app('request')->segment(2)=='pendingExperts')
                                @include('admin.admin.experts_to_interview_listing')
                                @php
                                    $export_url = '/admin/expertExportListing?status='.config("constants.APPROVED").'&admin_approved_status='.config("constants.PENDING");
                                    $export_label = 'Export All Pending Experts';
                                @endphp
                            @elseif(app('request')->segment(2)=='incompleteProfileExperts')
                                @include('admin.admin.experts_profile_incomplete_listing')
                                @php
                                    $export_url = '/admin/exportExpertWithIncompleteProfile';
                                    $export_label = 'Export All Profile Incomplete Experts';
                                @endphp                                
                            @elseif(app('request')->segment(2)=='notverifiedexperts')
                                @include('admin.admin.experts_unverified_listing')
                                @php
                                    $export_url = '/admin/exportNotVerifiedExperts';
                                    $export_label = 'Export All Unverified Experts';
                                @endphp
                            @elseif(app('request')->segment(2)=='archivedExpertsListing')
                                @include('admin.admin.experts_archived_listing')
                                @php
                                    $export_url = '/admin/exportArchivedExpert';
                                    $export_label = 'Export All Archived Experts';
                                @endphp
                            @elseif(app('request')->segment(2)=='expert')
                                @include('admin.admin.expert_individual_service_packages_listing')
                                @php
                                    $export_url = '/admin/exportArchivedExpert';
                                    $export_label = 'Export All Archived Experts';
                                @endphp
                            @elseif(app('request')->segment(2)=='sideHustlersExperts')
                                @include('admin.admin.experts_side_hustlers_listing')
                                @php
                                    $export_url = '/admin/exportSideHustlersExperts';
                                    $export_label = 'Export All Side Hustlers Experts';
                                @endphp

                            @endif
                        </table>

                        <div class="pagination">
                            @if(isset($result))
                                {!! $result->links() !!}
                            @else
                                {!! $service_packages->links() !!}
                            @endif
                        </div>
                        @if(isset($total) && !empty($total))
                            <a href="{{ url($export_url,[],$ssl) }}" class="export-report pull-right"><img src="{{url('images/export-icon.svg',[],$ssl) }}"  alt="export-icon"/>{{$export_label}}</a>                  
                        @endif  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
