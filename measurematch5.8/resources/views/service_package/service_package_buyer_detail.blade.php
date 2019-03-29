@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">

    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar">
                    @include('buyer.sidemenu')
            </div>
            <div class="col-md-9 rightcontent-panel">

                <div class="col-md-12">

                <div class="breadcrumb-bg project-details-breadcrumb">
                    <ul>
                    @if(!$last_page_buyer_service_package_listing)
                        <li><a href="{{url('servicepackage/types', [], $ssl)}}" alt='Back to Service Packages'>Find a Service Package</a></li>
                        <li><a href="{{$category_page_url}}" alt='Back to Service Packages'>{{$bread_crumb_category}}</a></li>
                        <li>{{getTruncatedContent($service_package_details[0]['name'], 28)}}</li>
                    @else
                        <li><a href="{{url('myprojects', [], $ssl)}}" alt='Back to My Projects'>My Projects</a></li>
                        <li>{{getTruncatedContent($service_package_details[0]['name'], 28)}}</li>
                    @endif
                    </ul>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class=" my-service-package-details-panel buyer-services-nav">
                        <span class="service-package-error-message"></span>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="@if(!$average_rating) service-package-buyer-view @endif">

                                <div class="white-box-content left-padding-0 right-padding-0 top-padding-0">
                                    <div class="tab-content min-height left-padding-0 right-padding-0">
                                        <div role="tabpanel" class="tab-pane active" id="packagedetails">

                                            <div class="package-overview-panel package-negotiable-column">
                                                <h4 class="gilroyregular-bold-font">All Service Package Pricing is Negotiable</h4>
                                                <p>The rate you see is a guide, so go in prepared for a healthy negotiation. Of course, if the price is right, plus both the service description and the Expert look awesome, and the Expert is available (of course), lock this Service Package into a contract and get to work!</p>
                                                <span class="close-negotiable-link ">
                                                <img src="{{ url('images/icon-cancel.svg',[],$ssl)}}" />
                                                </span>
                                            </div>
                                            <div class="package-overview-panel buyer-servies-name-col package-raiting-panel">
                                                <h4 class="gilroyregular-bold-font">{{ucfirst($service_package_details[0]['name'])}}</h4>
                                                @if($average_rating)
                                                @php $contract_feedback_count = (_count($service_package_details[0]['contract_feedbacks'])) ?? 0 @endphp
                                                <div class="package-rating-section">
                                                <div id="show_rating" name="expert_rating" class="rateyo-readonly-widg" average_rating="{{$average_rating}}">
                                                </div>
                                                <span class="package-review gilroyregular-bold-font">({{$contract_feedback_count}} reviews)</span>
                                                </div>
                                                @endif
                                                <p>{!! nl2br(e( $service_package_details[0]['description'])) !!}
                                                </p>
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
                                            <div class="package-require-panel skill-provide-col">
                                                <h4 class="gilroyregular-bold-font">Skills provided</h4>
                                                @foreach($tags as $tags_name)
                                                <lable class="skill-button">{{ucwords($tags_name)}}</lable>
                                                @endforeach
                                            </div>
                                            <div class="package-require-panel service-provider-profile">
                                                <a href="{{url('buyer/expert-profile/'.$user_information['user_id'].'?breadcrumb-page=view-service-package&service_package_id='.$service_package_details[0]['id'].'&title='.ucfirst($service_package_details[0]['name']))}}">
                                                <img src="{{$user_information['profile_picture']}}">
                                                <div class="service-provider-profile-detail">
                                                    <span class="service-provider-name gilroyregular-bold-font">{{userName($user_information['user_id'], 1)}}</span>
                                                    <span class="service-provider-skill">{{getTruncatedContent($user_information['describe'], 40)}}</span>
                                                </div>
                                                </a>

                                                <p>{!!getTruncatedContent($user_information['summary'], config('constants.SERVICE_PACKAGE_DESCRIPTION_LENGTH')) !!}</p>
                                                <a class="learn-user gilroyregular-bold-font" href="{{url('buyer/expert-profile/'.$user_information['user_id'].'?breadcrumb-page=view-service-package&service_package_id='.$service_package_details[0]['id'].'&title='.ucfirst($service_package_details[0]['name']))}}">
                                                    Learn more about {{$user_information['name']}}</a>
                                            </div>
                                            @if($average_rating)
                                            <div class="expert-package-review-panel">
                                                <h4 class="gilroyregular-bold-font">Reviews</h4>
                                                @if(_count($service_package_details[0]['contract_feedbacks']))
                                                    @foreach ($service_package_details[0]['contract_feedbacks'] as $feedbacks)
                                                    <div class="package-require-panel service-provider-profile">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                        <img src="{{getImage($feedbacks['buyer']['profile_picture'], $ssl)}}">
                                                        <div class="service-provider-profile-detail">
                                                            <span class="service-provider-name gilroyregular-bold-font">{{userName($feedbacks['buyer_id'], 1)}}, {{$feedbacks['buyer']['company_name']}}</span>
                                                            <p>{{date('F Y', strtotime($feedbacks['feedback_time']))}}</p>
                                                        </div></div></div>

                                                        <div id="{{$feedbacks['buyer_id'].'_'.$feedbacks['id']}}" class="rateyo-readonly-widg buyer_ratings" rating="{{$feedbacks['expert_rating']}}"></div>
                                                        <p>{!! $feedbacks['feedback_comment'] !!}</p>
                                                    </div>
                                                    @endforeach
                                                @else
                                                <p>This package has not received any reviews yet.</p>
                                                @endif
                                            </div>
                                            @endif
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="Deliverables">
                                            Coming Soon
                                        </div>

                                        <div role="tabpanel" class="tab-pane buyer-expert-contract-panel" id="Required">

                                            <div class="contract-details-listing">
                                                Coming Soon

                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane buyer-expert-contract-panel" id="Experts">


                                            <div class="contract-details-listing">
                                                Coming Soon

                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="Reviews">
                                             <div class="contract-details-listing">
                                                Coming Soon

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 edit-delete-widget buyer-guide-col">
                            <div class="white-box">
                                <div class="white-box-content">
                                    <div class="buyer-guide-col-head">
                                        <div class="buyer-guide-col-head-text">
                                            <p>Guide Package Price:</p>
                                            <span class="gilroyregular-bold-font">${{number_format($service_package_details[0]['price'])}}<span class="buyer-month">@if($service_package_details[0]['subscription_type']!="one_time_package"){{'/month'}} @endif</span></span>
                                        </div>
                                    </div>
                                    <ul>
                                        @if($looked_at_service_package)
                                            <li class="edit-icon looked-icon">
                                                <span class="looked-count gilroyregular-bold-font">@if($looked_at_service_package == 1){{$looked_at_service_package.' View'}}@else{{number_format($looked_at_service_package).' Views'}}@endif</span>
                                            </li>
                                        @endif
                                        <li class="edit-icon buyer-icon">
                                            <span class="looked-count gilroyregular-bold-font">@if($service_package_brought == 1){{$service_package_brought.' Client'}}@else{{$service_package_brought.' Clients'}}@endif</span>
                                            <span class="looked-text">Have bought this package</span>
                                        </li>
                                    </ul>
                                    @php
                                    $savedstatus = getSavedServicePackageStatus(Auth::user()->id,$service_package_details[0]['id']);
                                    @endphp
                                    <div class="guide-package-save-col">
                                        @if($is_interest_shown)
                                            <a class="btn btn-primary interest-expressed-btn gilroyregular-bold-font" href="javascript:void(0)" data-toggle="modal" >You've expressed interest </a>
                                        @else
                                            <a id="show_interest_by_buyer_button" class="btn @if(!Auth::user()->admin_approval_status) interest-expressed-btn @else express-interest-btn @endif btn-primary gilroyregular-bold-font" href="javascript:void(0)" data-toggle="modal" data-target="#expression-of-interest-pop-up" >Express Interest in Service Package</a>
                                        @endif
                                        @if(empty($savedstatus))
                                        <a buyer-id="{{Auth::user()->id}}" service-package-id="{{$service_package_details[0]['id']}}" saved_id="" id="save_the_package" class="save_the_package white-bg-btn white-btn save-service-package gilroyregular-bold-font" href="javascript:void(0)" >
                                            <span></span>Save this Service Package</a>          @else
                                        <a buyer-id="{{Auth::user()->id}}" service-package-id="{{$service_package_details[0]['id']}}" saved_id="{{$savedstatus[0]['id']}}" id="save_the_package" class="unsave_the_package white-bg-btn white-btn save-service-package gilroyregular-bold-font" href="javascript:void(0)" >
                                            <span></span>Unsave this Service Package</a>
                                        @endif

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
<div id="expression-of-interest-pop-up" class="modal suggest-project-popup lightbox-design coverletter-popup lightbox-design-small">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                </div>
                <div class="modal-body">
                    <h3>Express Interest in {{$service_package_details[0]['name']}}</h3>
                    <h4 class="gilroyregular-font text-align-center">Send an optional accompanying message to the Expert to answer any questions you have.</h4>
                    <form id="service-package-show-interest-by-buyer">
                        {{csrf_field()}}
                        <div class="coverletter-box">
                            <label>Send a message <span>(optional)</span></label>
                            <textarea id="cover_letter_message" maxlength="1000" name="cover_letter_message" placeholder="Start typing here..." value=""></textarea>
                            <div id="cover_letter_error_message" class="error_message"></div>
                            <input type="hidden" id="buyer_id" name="buyer_id" value="{{Auth::user()->id}}">
                            <input type="hidden" id="expert_id" name="expert_id" value="{{$service_package_details[0]['user_id']}}">
                            <input type="hidden" id="service_package_id" name="service_package_id" value="{{$service_package_details[0]['id']}}">
                            <input type="hidden" id="sender_name" name="sender_name" value="{{userName(Auth::user()->id)}}">
                        </div>
                        <button type="button" id="service_package_show_interest" data-text-swap="Send Message & Express Interest" class="show-intrest-btn standard-btn" data-text-original="Send Message & Express Interest">Send Message & Express Interest</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.buyer_mobile_body')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/buyer_service_packages.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
<link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
@include('include.footer')
@endsection
