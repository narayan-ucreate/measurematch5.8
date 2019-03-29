@extends('layouts.adminlayout')
@section('content')
<?php
$expert_id = $published_service_package['user_id'];
 ?>
<section class="content admin-section">
    <div class="row">
        <div class="container">
            <!-- left column -->
            
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-lg-10 col-sm-10 col-xs-2 admin-right-side">
                <!-- general form elements -->
                <div class="box">
                    <!-- form start -->
                    <div class="box-header"> 
                        <h1 class="box-heading">Service Package • {{$bread_crumb}}</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>                    
                    <div class="box-body">                   
                        <ol class="breadcrumb">
                            <li>
                                @if($request_type=='rejectedservicepackage')
                                <a class="back-buttton" href="{{ url('admin/rejectedservicepackages', [], $ssl) }}">{{$bread_crumb}}</a>
                                @elseif($request_type=='approvedservicepackage')
                                <a class="back-buttton" href="{{ url('admin/servicepackages', [], $ssl) }}">{{$bread_crumb}}</a>
                                @elseif($request_type=='draftedservicepackage')
                                @if($all_drafted_service_packages)
                                <a class="back-buttton" href="{{ url('admin/alldraftedservicepackages',[],$ssl) }}">{{$bread_crumb}}</a>
                                @else
                                <a class="back-buttton" href="{{ url('admin/expert/draftedservicepackages',[$expert_id],$ssl) }}">{{$bread_crumb}}</a>
                                @endif
                                @else
                                <a class="back-buttton" href="{{ url('admin/expert/servicepackages',[$expert_id],$ssl) }}">{{$bread_crumb}}</a>
                                @endif
                            </li>
                            <li>{{$published_service_package['name']}}</li>
                        </ol>
                        
                            <div class="pull-right message-section">
                                <p class="success" style="color:green">
                                    @if(session()->has('success'))
                                    {!! session()->get('success') !!}
                                    @endif
                                </p>
                                <p class="success error-message">
                                    @if(session()->has('warning'))
                                    {!! session()->get('warning') !!}
                                    @endif
                                </p>
                            </div>
                             <input type="hidden" name="post_id" value="{{ $published_service_package['id'] }}">
                             <input type="hidden" name="buyer_id" value="{{ $published_service_package['user_id'] }}">
                          <div class="form-group">
                            <label>Service Package Name :</label>
                            {{$published_service_package['name']}}
                           
                          </div>
                          @if($request_type=='approvedservicepackage')
                          <div class="form-group service-package-link">
                            <label>Service Package Link:</label>
                            <span id="service_package_link" class="gilroyregular-bold-font link-color">{{$webflow_url}}</span>
                            <button class="copy-link-btn"  onclick="copyToClipboard('#service_package_link')">Copy URL</button>
                            <span class="success-message-of-copy-url gilroyregular-bold-font" id="link_copied_message"></span>
                          </div> 
                          <div class="form-group">
                            <label>Service Package Status:</label>
                            @if($published_service_package['is_hidden']){{"Hidden"}}@else{{"Live"}}@endif
                          </div> 
                          @endif 
                           <div class="form-group">
                            <label>Category   :</label>
                            <span>
                                {{ $published_service_package['service_package_category']['name'] }}
                            </span>
                          </div>
                           <div class="form-group">
                            <label>Service Package Type   :</label>
                            <span>
                                @if(_count($published_service_package['service_package_type']))
                                    {{ $published_service_package['service_package_type']['name'] }}
                                @endif
                            </span>
                          </div>
                             <div class="form-group">
                            <label>Description :</label>
                            <div class="admin-view-brief">{!! nl2br(e( $published_service_package['description'] )) !!} </div>
                          </div>
                           <div class="form-group">
                            <label>Information required from client :</label>
                            
                            <div class="admin-view-brief">
                                {{$published_service_package['buyer_remarks']}}
                        </div>
                          </div>
                              <div class="form-group">
                            <label>Price :</label>
                            <div class="admin-view-brief">
                                ${{number_format($published_service_package['price'])}}
                            </div>
                          </div>

                           
                             <div class="admin-contract-payment-block">
                            <div class="form-group">
                                   <label>Service package duration : </label>
                            <div class="admin-view-brief job-input-bx select-box input-group date job-date-picker" id='start_time_div'>
                                {{$published_service_package['duration']}} days
                            </div>
                                
                            </div>
                            
                            <div class="form-group">
                                   <label>Subscription Type : </label>
                            <div class="admin-view-brief job-input-bx select-box input-group date job-date-picker" id='start_time_div'>
                                @if($published_service_package['subscription_type']=='one_time_package')
                                One time package
                                @else
                                Monthly retainer
                                @endif
                            </div>
                                
                            </div>
                            
                            <div class="form-group">
                                <label>Deliverables :</label>
                                <span class="admin-view-brief">
                                    {!!$deliverable!!}
                                </span>
                            </div>
                                 
                            <div class="form-group">
                                <label>Tags :</label>
                                <span class="admin-view-brief">
                                    {{$tag}}
                                </span>
                            </div>
                                 
                            </div>
                            @if($request_type=='rejectedservicepackage')
                            <div class="form-group">
                                <input type="button" id="reinstate_package" data-user="{{$published_service_package['user_id']}}" data-package_id="{{ $published_service_package['id'] }}" class="update-button" value="Reinstate Service Package">
                            </div>
                            @endif
                            @if($request_type=='draftedservicepackage' && $all_drafted_service_packages && $published_service_package['is_approved'] != TRUE)
                            <div class="form-group">
                                <input type="button" id="approve_service_project" data-user="{{$published_service_package['user_id']}}" data-id="{{ $published_service_package['id'] }}" redirect-to="all_drafted_packages" class="update-button" value="Approve">
                            </div>
                            @endif
                            @if($request_type == 'approvedservicepackage' && $webflow_url == '-')
                                <div class="form-group">
                                    <input type="button" id="servicePackageApproveWebflow" data-id="{{$published_service_package['id']}}" class="update-button" value="Send to Webflow">
                                </div>
                            @endif
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
