@extends('layouts.adminlayout')
@section('content')
<?php
$expert_id = Request::segment(4);
$orderBy = orderBy($_REQUEST);
?>
<section class="content admin-section">
    
    <div class="container">
       <div class="row">
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                <ul>
                    <li class="admin-logo-sidebar">
                        <a href="{{url('admin/buyerListing',[],$ssl)}}"><img src="{{url('images/logo-sm.svg',[],$ssl) }}" alt="logo icon" />
                            <span>Admin</span>
                        </a>
                    </li>
                    <li class="active"><a href="{{ url('admin/expert/servicepackages',[$expert_id],$ssl)}}" title="Published Service Packages">Published Service Packages</a></li>
                    <li><a href="{{ url('admin/expert/draftedservicepackages',[$expert_id],$ssl) }}" title="Drafted Service Packages">Drafted Service Packages</a></li>
                </ul>
            </div>
            <div class="col-xs-10 col-sm-10  col-lg-9 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h1 class="box-heading">Expert • Approved</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('admin/expertListing',[],$ssl) }}" class="back-buttton">Approved</a></li>
                            <li>Published Service Packages</li>
                        </ol>
                        <div class="pull-right message-section">
                            <p class="success"> @if(Session::has('success'))
                                {{Session::get('success')}}
                                @endif </p>
                        </div>
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            <thead>
                                <tr>
                                    <th><a id="company_name" data="asc" href="{{url('admin/expert/servicepackages/'.$expert_id.'?orderBy='.$orderBy,[],$ssl)}}">Service Package Name ({{$published_service_package_count}})</a></th>                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(_count($service_packages))
                                @foreach ($service_packages as $service_package)
                                <tr>
                                    <td>{{$service_package->name}}</td>    
                                    <td class="published-project-action">
                                        <a title="View" href="{{ url('admin/publishedservicepackage',$service_package->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> 
                                        <a title="Edit" href="{{ url('admin/editservicepackage',$service_package->id,$ssl) }}">
                                            <i data-id="{{$service_package->id}}" class="fa fa-pencil editUser"></i></a>
                                    </td>                               
                                </tr>
                                @endforeach 
                                @else
                                <tr><td colspan="5">This expert has no packages</td></tr>
                                @endif
                            </tbody>
                        </table>
                        @include('admin.admin.servicePackagePagination')
                        @if($published_service_package_count)
                            <a href="{{ url("admin/exportexperteservicepackages",[$expert_id],$ssl) }}" class="export-report pull-right">Export all Published Service Packages of expert</a>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</section>

<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>

@endsection
