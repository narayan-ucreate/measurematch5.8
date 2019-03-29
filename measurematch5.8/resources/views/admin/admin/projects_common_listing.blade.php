@extends('layouts.adminlayout')
@section('content')
<?php
$orderBy = orderBy($_REQUEST);
$total = $result->total();
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
                        <h1 class="box-heading">Projects</h1>
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
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2) == 'pendingProjects')
                                        @php 
                                            $class = 'active';
                                            $status = 'Pending'
                                        @endphp
                                    @endif
                                    <a class="{{$class}}" href="{{ url('admin/pendingProjects',[],$ssl) }}">
                                        Pending ({{$projects_count['pending']}})
                                    </a>
                                </li>
                                <li>
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2) == 'liveProjects')
                                        @php 
                                            $class = 'active';
                                            $status = 'Live'
                                        @endphp
                                    @endif
                                    <a class="{{$class}}" href="{{ url('admin/liveProjects',[],$ssl) }}">
                                        Live ({{$projects_count['live']}})
                                    </a>
                                </li>
                                <li>
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2) == 'inContractProjects')
                                        @php 
                                            $class = 'active';
                                            $status = 'In Contract'
                                        @endphp
                                    @endif
                                    <a class="{{$class}}" href="{{ url('admin/inContractProjects',[],$ssl) }}">
                                        In Contract ({{$projects_count['in_contract']}})
                                    </a>
                                </li>
                                <li>
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2)=='completedProjects')
                                        @php
                                            $class = 'active';
                                            $status = 'Completed'
                                        @endphp
                                    @endif
                                    <a class="{{$class}}" href="{{ url('admin/completedProjects',[],$ssl) }}">
                                        Completed ({{$projects_count['completed']}})
                                    </a>
                                </li>
                                <li>
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2) == 'expiredProjects')
                                        @php
                                            $class = 'active';
                                            $status = 'Expired'
                                        @endphp
                                    @endif
                                    <a class="{{$class}}" href="{{ url('admin/expiredProjects',[],$ssl) }}">
                                        Expired ({{$projects_count['expired']}})
                                    </a>
                                </li>
                                <li>
                                    @php
                                    $class = '';
                                    if(app('request')->segment(2) == 'rebookingProjects'){
                                            $class = 'active';
                                            $status = 'Rebookings';
                                    }
                                    @endphp
                                    <a class="{{$class}}" href="{{ url('admin/rebookingProjects',[],$ssl) }}">
                                        Rebookings ({{$projects_count['rebookings']}})
                                    </a>
                                </li>
                                <li>
                                    @php $class = ''; @endphp
                                    @if(app('request')->segment(2) == 'archivedProjects')
                                        @php 
                                            $class = 'active';
                                            $status = 'Archived'
                                        @endphp
                                    @endif
                                    <a @if(app('request')->segment(2) == 'archivedProjects') 
                                        class="{{$class}}" @endif href="{{ url('admin/archivedProjects',[],$ssl) }}">
                                        Archived ({{$projects_count['archived']}})
                                    </a>
                                </li>
                            </ul>
                        </div>  
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            @if(app('request')->segment(2) == 'liveProjects')
                                @include('admin.admin.live_projects')
                                @php
                                    $export_url = '/admin/exportProjectsInformation/'.config("constants.APPROVED");
                                    $export_label = 'Export All Live Projects';
                                @endphp
                            @elseif(app('request')->segment(2) == 'pendingProjects')
                                @include('admin.admin.pending_projects')
                                @php
                                    $export_url = '/admin/exportProjectsInformation/'.config("constants.PROJECT_PENDING");
                                    $export_label = 'Export All Pending Projects';
                                @endphp
                            @elseif(app('request')->segment(2) == 'inContractProjects')
                                @include('admin.admin.in_contract_projects')
                                @php
                                    $export_url = '/admin/exportInContractProjects/';
                                    $export_label = 'Export All In Contract Projects';
                                @endphp
                            @elseif(app('request')->segment(2) == 'expiredProjects')
                                @include('admin.admin.expired_projects')
                                @php
                                    $export_url = '/admin/exportProjects/';
                                    $export_label = 'Export All Expired Projects';
                                @endphp
                            @elseif(app('request')->segment(2) == 'rebookingProjects')
                                @include('admin.admin.rebooking_projects')
                                @php
                                    $export_url = '/admin/exportRebookingProjects/';
                                    $export_label = 'Export All Rebooking Projects';
                                @endphp
                            @elseif(app('request')->segment(2) == 'archivedProjects')
                                @include('admin.admin.archived_projects')
                                @php
                                    $export_url = '/admin/exportArchivedProjects';
                                    $export_label = 'Export All Archived Projects';
                                @endphp
                            @elseif(app('request')->segment(2)=='completedProjects')
                                @include('admin.admin.completed_projects')
                                @php
                                    $export_url = '/admin/exportCompletedProjects';
                                    $export_label = 'Export All Completed Projects';
                                @endphp
                            @endif
                        </table>

                        <div class="pagination">
                            {!! $result->links() !!}
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
