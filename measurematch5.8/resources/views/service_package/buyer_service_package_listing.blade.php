@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar">
                <div class="theiaStickySidebar">
                    @include('buyer.sidemenu')
                </div>
            </div>
            <div class="col-md-9 rightcontent-panel">
                <div class="theiaStickySidebar">
                    <div class="row">
                        <div class="col-md-12 create-package-panel my-service-package-panel buyer-srevice-package">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="white-box">
                                   <div class="white-box-header">
                                      <h4>My Service Packages</h4>
                                   </div>
                                   <div class="white-box-content left-padding-0 right-padding-0 top-padding-0">
                                       @if(_count($communication_detail))
                                        <div class="project-list-header">
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <div class="row">
                                                   <div class="col-md-6 col-sm-6 job-title">
                                                      <h4 class="hidden-xs">Package Title</h4>
                                                   </div>

                                                   <div class="col-md-3 col-sm-3 package-type-panel-heading">
                                                       <h4 class="hidden-xs">Package Type</h4>
                                                   </div>

                                                   <div class="col-md-3 col-sm-3">
                                                       <h4 class="hidden-xs">Package Cost</h4>
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-3">
                                                <h4 class="hidden-xs"></h4>
                                            </div>
                                        </div>
                                       @endif

                                       <div class="auto-scroll">
                                            @if(_count($communication_detail))
                                            @foreach($communication_detail as $communication)

                                            <div class="project-list-content">
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div class="row">
                                                            <a href="javascript:void(0)" onclick="redirectToDetailPage({{$communication['service_package_detail']['id']}})">
                                                                <div class="col-md-6 col-sm-6 col-xs-12 project-list-name">
                                                                    <h4 class="visible-xs">Package Title</h4>
                                                                    <h5>{{ucfirst($communication['service_package_detail']['name'])}}</h5>
                                                                    <span class="expressed-intrest-time">{{contractCurrentStage($communication['id'])}} <span class="days">{{timeElapsedString($communication['contract_action_date'])}}</span></span>
                                                                </div>

                                                                <div class="col-md-3 col-sm-3 col-xs-6 project-views-list package-type-content-heading">
                                                                    <h4 class="visible-xs">Package Type</h4>
                                                                    <span>@if(_count($communication['related_contract']))
                                                                            @if($communication['related_contract']['subscription_type']=='monthly_retainer') {{'Monthly-Retainer'}} @else {{'One-Time'}} @endif</span>
                                                                          @else
                                                                            @if($communication['service_package_detail']['subscription_type']=='monthly_retainer') {{'Monthly-Retainer'}} @else {{'One-Time'}} @endif</span>
                                                                          @endif
                                                                </div>

                                                                <div class="col-md-3 col-sm-3 col-xs-6 project-views-list package-type-content-heading">
                                                                    <h4 class="visible-xs">Package Cost</h4>
                                                                    <span>$@if(_count($communication['related_contract'])){{number_format($communication['related_contract']['rate'])}}@else{{number_format($communication['service_package_detail']['price'])}}@endif<?php if($communication['service_package_detail']['subscription_type']=='monthly_retainer'){echo '/month';}?>
                                                                    </span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>

                                                <div class="col-md-3 col-sm-3 col-xs-12 project-views-list package-type-content-heading text-align-center">
                                                    <a href="{{url('buyer/messages/'.$communication['type'].'/'.$communication['service_package_id'].'?communication_id='.$communication['id'], [], $ssl)}}" target="_blank" title="View Message" class="view-message-btn pull-right white-btn white-bg-btn font-14">View Messages</a>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                                <div class="no-services-package-message pull-left">
                                                    <h4 class="gilroyregular-font">You haven't expressed interest in any Service Packages yet.</h4>
                                                </div>
                                            @endif

                                      </div>
                                       <div class="col-lg-12 pull-left bottom-white-panel">
                                                <a href="{{ url('/servicepackage/types',[],$ssl) }}" class="blue-bg-btn standard-btn" id="addservicepackage">Find a Service Package</a>
                                            </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.buyer_mobile_body')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/my_service_packages_list.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
