@extends('layouts.adminlayout')
@section('content')
<section class="content-header admin-content-panel">
    <div class="container">
        <div class="col-lg-6 col-md-6">
            <h1>{{ $bread_crumb }}</h1>
        </div>
        <div class="col-lg-6 col-md-6">
            <h3><a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a></h3>
        </div>
        <ol class="breadcrumb">
            <li><a href="{{ url('paneladmin1',[],$ssl) }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">{{ $bread_crumb }}</li>
        </ol>
    </div>
</section>
<section class="content">
    
    
    <div class="container">
       
        
       <div class="row">
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-xs-10 col-sm-10  col-lg-9 col-xs-12">
                <div class="box">
                    <div class="box-header"> </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="pull-right message-section">
                            <p class="success"> @if(Session::has('success'))
                                {{Session::get('success')}}
                                @endif </p>
                        </div>
                        <table class="table table-bordered table-hover dataTable" id="example1">
                            <thead>
                                <tr>
                                    <th>Projects Name</th>                                  
                         
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                            <tr><td colspan="5">No Result Found</td></tr>
                            </tbody>
                        </table>
                        <div class="pagination">  </div>
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
