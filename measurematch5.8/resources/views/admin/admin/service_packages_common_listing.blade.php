@extends('layouts.adminlayout')
@section('content')
@php
$orderBy = orderBy($_REQUEST);
$service_package_count = servicePackagesCount();
@endphp
<section class="content admin-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-xs-10 col-sm-10  col-lg-9">
                <div class="box">
                    <div class="box-header"> 
                        <h1 class="box-heading">Service Packages</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
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
                                    <a @if(app('request')->segment(2)=='servicepackages')
                                        class="active" @endif href="{{ url('admin/servicepackages',[],$ssl) }}">
                                        Approved ({{$service_package_count['approved']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='pendingservicepackages')
                                        class="active" @endif href="{{ url('admin/pendingservicepackages',[],$ssl) }}">
                                        Pending ({{$service_package_count['pending']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='alldraftedservicepackages')
                                        class="active" @endif href="{{ url('admin/alldraftedservicepackages',[],$ssl) }}">
                                        Drafted ({{$service_package_count['drafted']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='rejectedservicepackages')
                                        class="active" @endif href="{{ url('admin/rejectedservicepackages',[],$ssl) }}">
                                        Rejected ({{$service_package_count['rejected']}})
                                    </a>
                                </li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            @if(app('request')->segment(2)=='servicepackages')
                                @include('admin.admin.approved_service_package_listing')
                                @if($published_service_package_count)
                                @php
                                    $export_url = '/admin/exportapprovedeservicepackages';
                                    $export_label = 'Export All Approved Service Packages';
                                @endphp
                                @endif
                            @elseif(app('request')->segment(2)=='pendingservicepackages')
                                @include('admin.admin.pending_service_package_listing')
                                @if($pending_service_package_count)
                                @php
                                    $export_url = '/admin/exportpendingeservicepackages';
                                    $export_label = 'Export All Pending Service Packages';
                                @endphp
                                @endif
                            @elseif(app('request')->segment(2)=='alldraftedservicepackages')
                                @include('admin.admin.drafted_service_package_listing')
                                @if($drafted_service_package_count)
                                @php
                                    $export_url = '/admin/exportalldraftedservicepackages';
                                    $export_label = 'Export All Drafted Service Packages';
                                @endphp
                                @endif
                            @elseif(app('request')->segment(2)=='rejectedservicepackages')
                                @include('admin.admin.rejected_service_package_listing')
                                @if($rejected_service_package_count)
                                @php
                                    $export_url = '/admin/exportrejectedservicepackages';
                                    $export_label = 'Export All Rejected Service Packages';
                                @endphp
                                @endif
                            @endif
                        </table>

                        <div class="pagination">
                            @if(isset($result))
                                {!! $result->links() !!}
                            @else
                                {!! $service_packages->links() !!}
                            @endif
                        </div>
                        @if(isset($export_url) && !empty($export_url))
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
