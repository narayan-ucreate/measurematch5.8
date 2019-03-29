@extends('layouts.adminlayout')
@section('content')
<?php
$orderBy = orderBy($_REQUEST);
if(isset($result)){
    $total = $result->total();
}
$vendors_count = (new \App\Components\CommonFunctionsComponent)->vendorsCount();
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
                        <h1 class="box-heading">Vendors</h1>
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
                                    <a @if(app('request')->segment(2)=='vendorListing') class="active" @endif href="{{ url('admin/vendorListing',[],$ssl) }}">
                                        Approved ({{$vendors_count['approved']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='pendingVendors') class="active" @endif href="{{ url('admin/pendingVendors',[],$ssl) }}">
                                        Pending ({{$vendors_count['pending']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='unverifiedVendors') class="active" @endif href="{{ url('admin/unverifiedVendors',[],$ssl) }}">
                                        Unverified ({{$vendors_count['unverified']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='archivedVendorsListing') class="active" @endif href="{{ url('admin/archivedVendorsListing',[],$ssl) }}">
                                        Archived ({{$vendors_count['archived']}})
                                    </a>
                                </li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                        @if(app('request')->segment(2)=='pendingVendors')
                            @include('admin.admin.vendors_pending_listing')
                            @php
                            $export_url = '/admin/vendorExportListing?admin_approved_status='.config("constants.PENDING");
                            $export_label = 'Export All Pending Vendors';
                            @endphp
                        @elseif(app('request')->segment(2)=='vendorListing')
                            @include('admin.admin.vendors_listing')
                            @php
                            $export_url = '/admin/vendorExportListing?admin_approved_status='.config("constants.APPROVED");
                            $export_label = 'Export All Approved Vendors';
                            @endphp
                        @elseif(app('request')->segment(2)=='unverifiedVendors')
                            @include('admin.admin.vendors_unverified_listing')
                            @php
                            $export_url = '/admin/exportNotVerifiedBuyer?user_type='.config("constants.VENDOR");
                            $export_label = 'Export All Unverified Vendors';
                            @endphp
                        @elseif(app('request')->segment(2)=='archivedVendorsListing')
                            @include('admin.admin.vendors_archived_listing')
                            @php
                            $export_url = '/admin/exportArchivedBuyer?user_type='.config("constants.VENDOR");
                            $export_label = 'Export All Archived Vendors';
                            @endphp
                        @endif
                        </table>

                        <div class="pagination">
                            @if(isset($result))
                                {!! $result->links() !!}
                            @elseif(isset($published_projects) && _count($published_projects))
                                {!! $published_projects->links() !!}
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
