@extends('layouts.layout')
@section('content')
<link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
    <div class="breadcrumb-bg project-details-breadcrumb">
        <ul>
            <li><a href="{{url('servicepackages',[],$ssl)}}" alt='All Projects'>My Service Packages</a></li>
            <li>@if(strlen($service_package_details[0]['name'])>=17){{ucfirst(substr($service_package_details[0]['name'],0,17))}}@if(strlen($service_package_details[0]['name'])>17){{'...'}}@endif @else{{ucfirst($service_package_details[0]['name'])}}@endif</li>
        </ul>
    </div>
    <div class="row">
        <div class="create-package-panel my-service-package-details-panel">
            <span class="service-package-error-message"></span>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="white-box">
                    <div class="white-box-header">
                        <h6>PACKAGE NAME</h6>
                        <h3>{{$service_package_details[0]['name']}}</h3>
                        <div class="package-status">
                            @if($service_package_details[0]['is_hidden']==1)
                                <span class="live-package hidden-package">Hidden from Clients<a id="unhide_package_button" data-status="{{$service_package_details[0]['is_hidden']}}" data-id="{{$service_package_details[0]['id']}}" href="javascript:void(0)">(Make visible)</a></span>
                            @elseif(empty($service_package_details[0]['is_approved']) && empty($service_package_details[0]['is_rejected']))
                                <span class="await-approval-package">Awaiting approval</span>
                            @elseif(empty($service_package_details[0]['is_approved']) && !empty($service_package_details[0]['is_rejected']))
                                <span class="await-approval-package">Rejected by MeasureMatch</span>
                            @else
                                <span class="live-package">Live on MeasureMatch</span>
                            @endif
                        </div>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#packagedetails" aria-controls="home" role="tab" data-toggle="tab">Package Details</a></li>
                            <li role="presentation"><a href="#EOIs" aria-controls="profile" role="tab" data-toggle="tab"> EOIs @if(_count($service_package_eoi['new_expression_of_interests'])>0)<span id="eoi-count" class="count-style">@php echo _count($service_package_eoi['new_expression_of_interests']); @endphp</span>@endif</a></li>
                            <li role="presentation"><a href="#Contracts" aria-controls="Contracts" role="tab" data-toggle="tab">Contracts</a></li>
                            <li role="presentation"><a href="#Reviews" aria-controls="Reviews" role="tab" data-toggle="tab">Reviews</a></li>
                        </ul>

                    </div>

                    <div class="white-box-content left-padding-0 right-padding-0 top-padding-0">
                        <div class="tab-content left-padding-0 right-padding-0">
                            <div role="tabpanel" class="tab-pane active" id="packagedetails">

                                <div class="package-overview-panel">
                                    <h4 class="gilroyregular-bold-font">Overview</h4>
                                    <p>@if($service_package_details[0]['subscription_type']=="one_time_package"){{'One-Time Package Price:'}}@else {{'Monthly Retainer Package Price:'}} @endif <strong>${{number_format($service_package_details[0]['price'])}}@if($service_package_details[0]['subscription_type']!="one_time_package"){{'/month'}} @endif</strong></p>
                                    <p>Expert time commitment:
                                        <strong>{{$service_package_details[0]['duration']}} @php if($service_package_details[0]['duration']>1){echo 'days'; }else{echo 'day'; }
                                                    if($service_package_details[0]['subscription_type']!="one_time_package"){echo '/month'; } @endphp</strong></p>
                                    <p>{!! nl2br(e( $service_package_details[0]['description'] )) !!}</p>
                                </div>

                                <div class="package-deliverable-panel">
                                    <h4 class="gilroyregular-bold-font">Deliverables</h4>
                                    <ul>
                                        @if(sizeof($deliverables))
                                            @foreach($deliverables as $deliverable_detail)
                                                <li>{!! $deliverable_detail['deliverable'] !!}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="package-require-panel">
                                    <h4 class="gilroyregular-bold-font">Required for completion</h4>
                                    <ul>
                                        <li>{!! $service_package_details[0]['buyer_remarks'] !!}</li>
                                    </ul>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="EOIs">
                                @if(_count($service_package_eoi['new_expression_of_interests']) + _count($service_package_eoi['actioned_expression_of_interests']) + _count($service_package_eoi['archived_expression_of_interests'])==0)
                                    <div class="no-services-package-message content-align-center no-data-panel">
                                        <img src="{{ url('images/EOIs-emptyState.svg',[],$ssl)}}" />
                                        <h4>You have no EOI yet.</h4>
                                    </div>
                                @else
                                    <div class="new-eoi-panel new-eios-panel">
                                        <h4 class="gilroyregular-bold-font">NEW EOIs <span>(<span id="new-count">@php echo _count($service_package_eoi['new_expression_of_interests']); @endphp</span>)</span></h4>
                                        <input type="hidden" id="new-eio-count" name="new-eio-count" value="@php echo _count($service_package_eoi['new_expression_of_interests']); @endphp">

                                        @if(_count($service_package_eoi['new_expression_of_interests']) > 0)
                                            @foreach($service_package_eoi['new_expression_of_interests'] as $new_service_eoi)
                                                <div class="eoi-listing eoi-{{$new_service_eoi['id']}}">
                                                    <div class="col-md-8 col-xs-6">
                                                        @php
                                                            $buyer_name = userName($new_service_eoi['buyer_id'],1); @endphp
                                                        <h3>{{$buyer_name}}</h3>
                                                        <span>Expressed Interest on {{date('d M Y',strtotime($new_service_eoi['created_at']))}} </span>
                                                    </div>

                                                    <div class="keyboard-control dropup pull-right archive-dropdown-opt">
                                                        <a class="white-bg-btn white-bg" href="{{url('expert/messages?communication_id='.$new_service_eoi['id'],[],$ssl)}}">View Messages</a>
                                                        <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img src="{{ url('images/3-dots.svg',[],$ssl)}}" />


                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                            <li><a id="{{$new_service_eoi['id']}}" data-buyer-name="{{$buyer_name}}" data-created-at="{{date('d M Y',strtotime($new_service_eoi['created_at']))}}" data-type="new-eio-count" href="javascript:void(0)" class="archieve-expressions-of-interest"><strong>Archive this EOI</strong><span>Save this EOI for later on by archiving it</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach                                                                                        @endif
                                    </div>

                                    <div class="new-eoi-panel actioned-eoi-panel">
                                        <h4 class="gilroyregular-bold-font">ACTIONED EOIs <span>(<span id="actioned-count">@php echo _count($service_package_eoi['actioned_expression_of_interests']); @endphp</span>)</span></h4>
                                        <input type="hidden" id="actioned-eio-count" name="actioned-eio-count" value="@php echo _count($service_package_eoi['actioned_expression_of_interests']); @endphp">
                                        @if(_count($service_package_eoi['actioned_expression_of_interests']) > 0)

                                            @foreach($service_package_eoi['actioned_expression_of_interests'] as $actioned_service_eoi)
                                                <div class="eoi-listing eoi-{{$actioned_service_eoi['id']}}">
                                                    <div class="col-md-8 col-xs-6">
                                                        @php
                                                            $buyer_name = userName($actioned_service_eoi['buyer_id'],1); @endphp
                                                        <h3>{{$buyer_name}}</h3>
                                                        <span>Expressed Interest on @php echo date('d M Y',strtotime($actioned_service_eoi['created_at'])); @endphp </span>
                                                    </div>

                                                    <div class="keyboard-control archive-dropdown-opt dropup pull-right">

                                                        <a class="white-bg-btn white-bg" href="{{url('expert/messages?communication_id='.$actioned_service_eoi['id'],[],$ssl)}}">View Messages</a>
                                                        <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img src="{{ url('images/3-dots.svg',[],$ssl)}}" />


                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                            <li><a class="archieve-expressions-of-interest" id="{{$actioned_service_eoi['id']}}" data-buyer-name="{{$buyer_name}}" data-created-at="{{date('d M Y',strtotime($actioned_service_eoi['created_at']))}}" data-type="actioned-eio-count" href="javascript:void(0)"><strong>Archive this EOI</strong><span>Save this EOI for later on by archiving it</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>

                                    <div class="new-eoi-panel archieved-eoi-panel">
                                        <h4 class="gilroyregular-bold-font">ARCHIVED EOIs <span>(<span id="archieve-count">@php echo _count($service_package_eoi['archived_expression_of_interests']); @endphp</span>)</span></h4>
                                        <input type="hidden" id="archieved-eio-count" name="archieved-eio-count" value="@php echo _count($service_package_eoi['archived_expression_of_interests']); @endphp">
                                        @if(_count($service_package_eoi['archived_expression_of_interests'])>0)

                                            @foreach($service_package_eoi['archived_expression_of_interests'] as $archieved_service_eoi)
                                                @php
                                                    $buyer_name = userName($archieved_service_eoi['buyer_id'],1); @endphp
                                                <div class="eoi-listing eoi-{{$archieved_service_eoi['id']}}">
                                                    <div class="col-md-8 col-xs-6">
                                                        <h3>@php echo userName($archieved_service_eoi['buyer_id'],1); @endphp</h3>
                                                        <span>Expressed Interest on @php echo date('d M Y',strtotime($archieved_service_eoi['created_at'])); @endphp</span>
                                                    </div>

                                                    <div class="keyboard-control archive-dropdown-opt dropup pull-right">
                                                        <a class="white-bg-btn white-bg" href="{{url('expert/messages?communication_id='.$archieved_service_eoi['id'],[],$ssl)}}">View Messages</a>
                                                        <button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img src="{{ url('images/3-dots.svg',[],$ssl)}}" />


                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                            <li><a href="javascript:void(0)" id="{{$archieved_service_eoi['id']}}" data-buyer-name="{{$buyer_name}}" data-created-at="@php echo date('d M Y',strtotime($archieved_service_eoi['created_at'])); @endphp" href="javascript:void(0)" class="unarchieve-expressions-of-interest"><strong>Unarchive this EOI</strong><span>Unarchive EOI to use now</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>                                                                                             @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div role="tabpanel" class="tab-pane buyer-expert-contract-panel" id="Contracts">
                                <div class="no-services-package-message content-align-center" style="display: none;">

                                </div>
                                @if(_count($service_package_contracts)>0)
                                    <div class="project-list-header">
                                        <div class="col-md-3 col-sm-2 job-title text-align-left contract-buyer-name">
                                            <h4 class="hidden-xs">Client Name</h4>
                                        </div>

                                        <div class="col-md-2 col-sm-3 contract-status text-align-left">
                                            <h4 class="hidden-xs">Status</h4>
                                        </div>

                                        <div class="col-md-2 col-sm-2 contract-start-date">
                                            <h4 class="hidden-xs">Start date</h4>
                                        </div>

                                        <div class="col-md-2 col-sm-2 contract-end-date">
                                            <h4 class="hidden-xs">End Date</h4>
                                        </div>

                                        <div class="col-md-2 col-sm-2 contract-price">
                                            <h4 class="hidden-xs">Value</h4>
                                        </div>

                                        <div class="col-md-1 col-sm-2 no-data-panel">
                                            <h4 class="hidden-xs"></h4>
                                        </div>
                                    </div>
                                @endif
                                <div class="contract-details-listing">
                                    @if(_count($service_package_contracts)>0)
                                        @foreach($service_package_contracts as $contract)
                                            <a class="view-contract" href="{{url('expert/messages?view_communication_contract='.$contract['communications_id'], [], $ssl)}}" target="_blank">
                                                <div class="project-list-content">
                                                    <div class="col-md-3 col-sm-2 job-title contract-buyer-name text-align-left">
                                                        <h4 class="visible-xs">Client Name</h4>
                                                        <h4 class="gilroyregular-bold-font">{{userName($contract['buyer_id'],1)}}</h4>
                                                        <span>{{ucwords(buyerInfo($contract['buyer_id'])[0]['company_name'])}}</span>
                                                    </div>
                                                    <div class="col-md-2 col-sm-3 contract-status">
                                                        <h4 class="visible-xs">Status</h4>
                                                        <h4 class="gilroyregular-bold-font contract-status-update-{{$contract['id']}}">
                                                            @if($contract['status']==1 && ($contract['expert_complete_status']==1 || $contract['complete_status']==1))
                                                                {{'Completed'}}
                                                            @elseif($contract['status']==1)
                                                                {{'On-going'}}
                                                            @elseif($contract['status']==2)
                                                                {{'Contract Rejected'}}
                                                            @else
                                                                {{'Contract Offered'}}
                                                            @endif

                                                        </h4>
                                                    </div>

                                                    <div class="col-md-2 col-sm-2 contract-start-date">
                                                        <h4 class="visible-xs">Start date</h4>
                                                        <h4 class="gilroyregular-bold-font">{{date('dS M y',strtotime($contract['job_start_date']))}}</h4>
                                                    </div>

                                                    <div class="col-md-2 col-sm-2 contract-end-date">
                                                        <h4 class="visible-xs">End Date</h4>
                                                        <h4 class="gilroyregular-bold-font">{{($contract['subscription_type']=='monthly_retainer')?'(Rolling monthly)':date('dS M y',strtotime($contract['job_end_date']))}}</h4>
                                                    </div>

                                                    <div class="col-md-2 col-sm-2 contract-price">
                                                        <h4 class="visible-xs">Value</h4>
                                                        <h4 class="gilroyregular-bold-font">${{number_format($contract['rate'])}}@if($contract['subscription_type']=='monthly_retainer')/month @endif</h4>
                                                    </div>

                                                </div>

                                            </a>
                                        @endforeach
                                    @else
                                        <div class="no-services-package-message content-align-center no-data-panel">
                                            <img src="{{ url('images/contracts-emptyState.svg',[],$ssl)}}" />
                                            <h4>Once you’ve agreed on package terms with <br /> the Clients, the contracts will live here.</h4>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="Reviews">
                                @if(_count($service_package_feedbacks))
                                    <div class="expert-package-review-panel my-service-package-reviews-panel">
                                        @foreach ($service_package_feedbacks as $feedbacks)
                                            <div class="service-provider-profile">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <img src="{{getImage($feedbacks['buyer']['profile_picture'], $ssl)}}">
                                                        <div class="service-provider-profile-detail">
                                                            <span class="service-provider-name gilroyregular-bold-font">{{userName($feedbacks['buyer_id'], 1)}}</span>
                                                            <span>{{date('F Y', strtotime($feedbacks['feedback_time']))}}</span>
                                                        </div></div></div>

                                                <div id="{{$feedbacks['buyer_id'].'_'.$feedbacks['id']}}" class="rateyo-readonly-widg buyer_ratings" rating="{{$feedbacks['expert_rating']}}"></div>
                                                <p>{!! $feedbacks['feedback_comment'] !!}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-services-package-message content-align-center  no-data-panel">
                                        <img src="{{ url('images/reviews-emptyState.svg',[],$ssl)}}" />
                                        <h4>Once you’ve completed a Package for a Client, the<br /> reviews will live here.</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 edit-delete-widget expertedit-panel">
            <div class="white-box">
                <div class="white-box-content">
                    <ul>
                        <li class="edit-icon">
                            <a href="{{ url('/',[],$ssl) }}/servicepackage/edit/{{$service_package_details[0]['id']}}">
                                <img src="{{ url('images/pen.png',[],$ssl)}}" alt="" /><span>Edit Package</span></a>
                        </li>
                        <li class="hide-package-icon"><a class="@if($service_package_details[0]['is_hidden']=='1'){{'hide-package-button-from-buyer'}}@endif" data-id="{{$service_package_details[0]['id']}}" data-status="{{$service_package_details[0]['is_approved']}}" id="hide_package_button" href="javascript:void(0)">
                                <img src="{{ url('images/hide-package.svg',[],$ssl)}}" alt="" /> <span>Hide Package from Clients</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        <div id="hide_package_from_buyer" class="modal fade bs-example-modal-lg hide-package-popup lightbox-design package-preivew new-modal-theme">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                    </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>Hide this Package from Clients?</h2>
                        <input type="hidden" name="package_id" value="" id="package_id">
                        <input type="hidden" name="package_status" value="" id="package_status">
                        <div class="popup-btn-panel">
                            <p>Not quite ready to service clients yet on this package? Or taking a vacation? Don't worry, we've got you covered.</p>
                            <input id="package-hide-button" value="Yes, hide Package" class="continue-btn green_gradient standard-btn" type="button">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="unhide_package_from_buyer" class="modal fade bs-example-modal-lg lightbox-design hide-package-popup package-preivew new-modal-theme">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">

                        <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                    </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>Make Package visible to Clients?</h2>
                        <input type="hidden" name="package_unhide_id" value="" id="package_unhide_id">
                        <input type="hidden" name="package_unhide_status" value="" id="package_unhide_status">
                        <input type="hidden" value="{{$service_package_details[0]['is_approved']}}" id="package_approval_status">
                        <div class="popup-btn-panel">
                            <p>You'll start receiving Expressions of Interest from Clients and asked for your availability to deliver your Package.</p>
                            <input id="package-unhide-button" value="Yes, make Package visible" class="continue-btn green_gradient standard-btn" type="button">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="accept_contract_stage_popups">
        </div>

        @include('include.footer')
        @endsection

        @section('scripts')
            @include('include.basic_javascript_liberaries')
            <script src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{url('js/my_service_packages_list.js?js='.$random_number,[],$ssl)}}"></script>
@endsection

