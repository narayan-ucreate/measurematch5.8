@extends('layouts.adminlayout')
@section('content')
<?php
$orderBy = orderBy($_REQUEST);
if(isset($result)){
    $total = $result->total();
}
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
                        <h1 class="box-heading">Hubs</h1>
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
                                    <a @if(app('request')->segment(2)=='liveHubs' || app('request')->segment(2)=='getProjects') class="active" @endif href="{{ url('admin/liveHubs',[],$ssl) }}">
                                        Live ({{$hubs_count['live']}})
                                    </a>
                                </li>
                                <li><a @if(app('request')->segment(2)=='pendingHubs') class="active" @endif href="{{ url('admin/pendingHubs',[],$ssl) }}">
                                        Pending ({{$hubs_count['pending']}})
                                    </a>
                                </li>
                                <li>
                                    <a @if(app('request')->segment(2)=='archivedHubs') class="active" @endif href="{{ url('admin/archivedHubs',[],$ssl) }}">
                                        Archived ({{$hubs_count['archived']}})
                                    </a>
                                </li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            @switch(app('request')->segment(2))
                                @case('liveHubs')
                                    @include('admin.admin.hubs_live_listing')
                                    @php
                                    $export_url = '/admin/buyerExportListing?admin_approved_status='.config("constants.PENDING");
                                    $export_label = 'Export All Live Hubs';
                                    @endphp
                                    @break
                                @case('pendingHubs')
                                    @include('admin.admin.hubs_pending_listing')
                                    @php
                                    $export_url = '/admin/buyerExportListing?admin_approved_status='.config("constants.APPROVED");
                                    $export_label = 'Export All Pending Hubs';
                                    @endphp
                                    @break
                                @case('archivedHubs')
                                    @include('admin.admin.hubs_archived_listing')
                                    @php
                                    $export_url = '/admin/exportNotVerifiedBuyer';
                                    $export_label = 'Export All Archived Hubs';
                                    @endphp
                                    @break
                            @endswitch
                        </table>

                        <div class="pagination">
                            @if(isset($result))
                                {!! $result->links() !!}
                            @elseif(isset($published_projects) && _count($published_projects))
                                {!! $published_projects->links() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
