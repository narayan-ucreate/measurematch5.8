@extends('layouts.layout')
@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 create-package-panel my-service-package-panel expert-my-service-package">
    <div class="white-box">            
        <div class="vendor-step-3 invite-service-block">
            <div class="vendor-review-step-bg">
                <div class="d-flex col-md-12">
                    <div class="vendor-dis-block col-md-12">
                        <h4 class="gilroyregular-semibold font-24">{{$hub_info->name}}</h4>
                        @php $class = ''; @endphp
                        @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT'))
                            @php $class = 'hide'; @endphp
                            <div id="truncated_description" >
                                <p>
                                    {!! nl2br(e( closeTags(substr(trim($hub_info->description), 0, config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT') ).'...') ))!!}
                                    <a href="javascript:void(0)" id="show_more" class="read-more gilroyregular-semibold">Read more</a></p>
                            </div>
                        @endif

                        <div id="full_description" class="{{$class}}">
                            <p>
                                {!! nl2br(e($hub_info->description ?? '')) !!}
                                @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT'))
                                    <a href="javascript:void(0)" id="show_less" class="read-more  gilroyregular-semibold"> Read less</a>
                                @endif
                            </p>
                        </div>

                    </div>
                    <div class="vendor-logo">
                        <img src="{{$hub_info->logo}}" alt="" width="100">
                    </div>                    
                </div>
            </div>
           <div class="block d-flex col-md-12">
            <div class="vendor-verified-block col-md-12">
                @if(isExpert())
                    @if(!_count($hub_info->myServiceHubStatus))
                        <h4 class="gilroyregular-semibold font-18">Apply to become a {{$hub_info->name}} verified Expert</h4>
                        <p class="font-14">If your application is approved by {{$hub_info->vendor_profile->company_name}}, <br /> your profile will be updated to include the Vendor for <br /> prospective clients to see.</p>
                        <input type="button" id="apply_for_verified_expert" value="Apply to {{$hub_info->name}}’s Hub" class="btn font-16 standard-btn" />
                    @elseif ($hub_info->myServiceHubStatus->status == config('constants.SERVICE_HUB_EXPERT_STATUS.PENDING'))
                        <div class="col-md-8 col-sm-10 col-xs-12 vendor-wating-popup">
                            <div class="col-md-2 col-sm-2 col-xs-12 awaiting-notification-icon">
                                <img src="{{ url('images/project-under-review.png',[],$ssl) }}">
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12 awaiting-content">
                                <h4>You have applied to {{$hub_info->name}}’s Service Hub!</h4>
                                <span>Awaiting feedback from {{$hub_info->name}}.</span>
                            </div>
                        </div>
                    @elseif ($hub_info->myServiceHubStatus->status == config('constants.SERVICE_HUB_EXPERT_STATUS.APPROVED'))
                    <div class="col-md-8 col-sm-8 col-xs-12 vendor-wating-popup">
                        <div class="col-md-2 col-sm-2 col-xs-12 approved-notification-icon">
                            <img src="{{ url('images/project-live.png',[],$ssl) }}">
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12 awaiting-content">
                            <h4>You have been approved!</h4>
                            <span>This is now reflected in your MeasureMatch profile for prospective clients to see.</span>
                        </div>
                    </div>
                        @elseif ($hub_info->myServiceHubStatus->status == config('constants.SERVICE_HUB_EXPERT_STATUS.DECLINE'))
                    <div class="col-md-9 col-sm-9 col-xs-12 vendor-wating-popup">
                        <div class="col-md-2 col-sm-2 col-xs-12 rejected-notification-icon">
                            <img src="{{ url('images/rejected.png',[],$ssl) }}">
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-12 awaiting-content">
                            <h4>Your application has been declined</h4>
                            <span>Unfortunately, {{$hub_info->vendor_profile->company_name}} has declined your application. Here’s a message from them explaining why:</span> <br />
                            <span class="italic-text margin-top-10">“{{$hub_info->myApplicationInfo->rejectedDetails->message}}”</span>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>

            <div class="vendor-contact">
                <a href="{{createExternalUrl($hub_info->service_website ?? '')}}" target="_blank"><img alt="cross" src="{{ url('images/vendor-website.svg',[],$ssl) }}"> Website</a>
                <a href="mailto:{{$hub_info->sales_email ?? ''}}"><img alt="cross" src="{{ url('images/vendor-send.svg',[],$ssl) }}"> Contact Sales</a>
            </div>
         </div>
        @if($is_buyer)
            <div class="block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="vendor-verified-block">

                            <div class="vendor-expert-blocks">
                                <div class="row verified-experts-block">
                                    <div class="v-align-box ver-expert-row">
                                        @php $count = 0; @endphp
                                        @foreach($experts_listing as $approved_expert)
                                             @if($count != 0 && $count % 3 == 0)<div class="v-align-box ver-expert-row">@endif
                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                    <div class="search-result-white-bx">
                                                        <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$approved_expert->expertDetail->id}}')"><span class="expert-profile-pic" style="background-image:url({{ getImage($approved_expert->expertDetail->user_profile->profile_picture, $ssl) }});"></span></a>
                                                        <h4 class="font-18 gilroyregular-semibold">{{userName($approved_expert->expertDetail->id, 1)}}
                                                            <div class="white-theme-tooltip active_project_listing" id="{{$approved_expert->expertDetail->id}}"></div>
                                                        </h4>
                                                        <span class="expert-job">
                                                            @if(strlen($approved_expert->expertDetail->user_profile->describe)>28)
                                                                {{strip_tags(ucfirst(substr($approved_expert->expertDetail->user_profile->describe,0,28))).'...'}}
                                                            @else
                                                                {{strip_tags(ucfirst($approved_expert->expertDetail->user_profile->describe))}}
                                                            @endif
                                                        </span>

                                                        <span class="country-flag">
                                                            @if(!empty($approved_expert->expertDetail->user_profile->country) && !empty(getCountryFlag($approved_expert->expertDetail->user_profile->country)))
                                                                <img src="{{getCountryFlag($approved_expert->expertDetail->user_profile->country)}}">
                                                            @endif
                                                            {{getTruncatedContent($approved_expert->expertDetail->user_profile->current_city, 28)}}
                                                        </span>
                                                        <span class="expert-job">
                                                           {{strip_tags(getTruncatedContent($approved_expert->expertDetail->user_profile->summary, 142))}}
                                                        </span>

                                                        <div class="view-profile-block">
                                                            <div class="bottom-white-bx">
                                                                <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$approved_expert->expertDetail->id}}')">View Profile</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $count++; @endphp
                                                @if($count > 2 && $count % 3 == 0)</div>@endif
                                          @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($experts_listing->total() && ($experts_listing->currentPage() < $experts_listing->lastPage()))
                            <div id="view_more_experts_block" class="vendor-verified-block margin-top-10 margin-bottom-10 text-align-center">
                                <a class="loadmore-btn standard-btn"
                                   id = "view_more_experts"
                                   href = "javascript:void(0)"
                                   page-number = '{{($experts_listing->currentPage()+1)}}'
                                   service-hub-id = '{{$hub_info->id}}'>View more</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
      </div> 
   </div>
</div>
<div id="apply_to_service_hub" class="proect-deatil-pop modal fade verify-expert-vendor-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                    <div class="modal-body">                    
                        <div class="col-md-12">
                            <img class="verdor-logo-img margin-top-30 margin-bottom-30" height="82" src="{{$hub_info->logo}}"  />
                            <form method="POST" action="{{route('apply-to-service-hub')}}" id="send_expert_verification">
                                {{csrf_field()}}
                                <h4 class="margin-bottom-10">Apply to become a verified {{$hub_info->name}} Expert</h4>
                                <input type="hidden" name="hub_id" value="{{$hub_info->id}}" id="hub_id">
                                <div class="form-group margin-top-20 col-md-12 col-sm-12 col-xs-12">
                                    <label>Describe a recent client case study for a {{$hub_info->name}} project:</label>
                                    <textarea class="form-control textarea-style" placeholder="e.g. Configure multiple dashboards within Google Analytics" id="case_study"></textarea>
                                    <div class="validate_case_study_error error_message"></div>
                                </div>

                                <div class="form-group margin-top-30 col-md-12 col-sm-12 col-xs-12">
                                    <label>How many years of {{$hub_info->name}} experience do you have?</label>
                                    @php
                                        $experience = range(1,30);
                                    @endphp
                                    <select class="selectpicker" name="total_experience" id="total_experience" >
                                        <option selected disabled hidden>Choose</option>
                                        @foreach($experience as $value)
                                            <option value="{{$value}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <div class="validate_total_experience_error error_message"></div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <input type="submit" id="apply_to_service_hub_btn"  value="Send Application" class="info-save-btn float-none disable-btn margin-bottom-30 margin-top-20 font-16" />
                                </div>
                            </form>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    @include('include.footer')
    <script src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/vendor_service_hub.js?js='.$random_number,[],$ssl)}}"></script>

@endsection
