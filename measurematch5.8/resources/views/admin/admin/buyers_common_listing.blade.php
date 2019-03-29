@extends('layouts.adminlayout')
@section('content')
<?php
$orderBy = orderBy($_REQUEST);
if(isset($result)){
    $total = $result->total();
}
$buyers_count = buyersCount();
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
                        <h1 class="box-heading">Clients</h1>
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
                                <li><a @if(app('request')->segment(2)=='buyerListing' || app('request')->segment(2)=='getProjects') class="active" @endif href="{{ url('admin/buyerListing',[],$ssl) }}">Approved ({{$buyers_count['approved']}})</a></li>
                                <li><a @if(app('request')->segment(2)=='pendingBuyers') class="active" @endif href="{{ url('admin/pendingBuyers',[],$ssl) }}">Pending ({{$buyers_count['pending']}})</a></li>
                                <li><a @if(app('request')->segment(2)=='unverifiedBuyers') class="active" @endif href="{{ url('admin/unverifiedBuyers',[],$ssl) }}">Unverified ({{$buyers_count['unverified']}})</a></li>
                                <li><a @if(app('request')->segment(2)=='archivedBuyersListing') class="active" @endif href="{{ url('admin/archivedBuyersListing',[],$ssl) }}">Archived ({{$buyers_count['archived']}})</a></li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            @if(app('request')->segment(2)=='pendingBuyers')
                                @include('admin.admin.buyers_pending_listing')
                                @php
                                $export_url = '/admin/buyerExportListing?admin_approved_status='.config("constants.PENDING");
                                $export_label = 'Export All Pending Clients';
                                @endphp
                            @elseif(app('request')->segment(2)=='buyerListing')
                                @include('admin.admin.buyers_listing')
                                @php
                                $export_url = '/admin/buyerExportListing?admin_approved_status='.config("constants.APPROVED");
                                $export_label = 'Export All Approved Clients';
                                @endphp                                
                            @elseif(app('request')->segment(2)=='unverifiedBuyers')
                                @include('admin.admin.buyers_unverified_listing')
                                @php
                                $export_url = '/admin/exportNotVerifiedBuyer';
                                $export_label = 'Export All Unverified Clients';
                                @endphp
                            @elseif(app('request')->segment(2)=='archivedBuyersListing')
                                @include('admin.admin.buyers_archieved_listing')
                                @php
                                $export_url = '/admin/exportArchivedBuyer';
                                $export_label = 'Export All Archived Clients';
                                @endphp
                            @elseif(app('request')->segment(2)=='getProjects')
                                @include('admin.admin.buyer_individual_projects_listing')
                                @php
                                $export_url = '/admin/exportBuyerProjects/'.config("constants.APPROVED").'/'.app('request')->segment(3);
                                $export_label = 'Export All Client Published Projects';
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
