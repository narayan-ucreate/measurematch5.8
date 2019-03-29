@php
$expert_name = trim($expert_profile->expertBasicInfo->name). ' '.substr($expert_profile->expertBasicInfo->last_name, 0, 1);
@endphp
<div class="create-package-panel">
    <div class="expert-profile-container expert-profile-section">
        <div class="row block">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="expert-img-section">
                <div class="expert-pic">
                    <a href="{{route('expert-profile', [$user_id])}}" class="stop-link-propagation" target="_blank"> <span class="expert-profile-pic" style="background-image:url({{getImage($expert_profile->profile_picture,$ssl)}});">
                           </span>
                    </a>
                </div>
                <div class="expert-info-container">
                    <h3 class="expet-name gilroyregular-semibold">
                        <a class="stop-link-propagation" href="{{route('expert-profile', [$user_id])}}" target="_blank"> {{ucfirst($expert_name)}}</a>
                    </h3>
                    <span class="expert-job-profile font-16">
                        <span class="font-16 expert-profile-title"> @if($expert_profile->expert_type == config('constants.EXPERT_TYPE_INDEPENDENT')){{'Independent Consultant'}} 
                            @else {{$expert_profile->expert_type }} @endif</span>
                        <span class="expert-location font-16">
                                @if(!empty($expert_profile->current_city))
                                {{trim($expert_profile->current_city)}}@if(!empty($expert_profile->country)){{trim(', '.$expert_profile->country)}}@endif
                            @endif
                            </span>
                        </span>
                        @if(Auth::user()->user_type_id === config('constants.EXPERT'))
                        <span class="font-14">
                            {{trim($expert_profile->expertBasicInfo->email)}}
                        </span>
                        @endif
                    @if(Auth::user()->user_type_id !== config('constants.EXPERT')
                    && isset($applicant_info)
                    && $applicant_info->status == config('constants.PENDING'))
                    <button
                        class="btn standard-btn approve-expert"
                        user-id = '{{$user_id}}'
                        vendor-id = '{{$applicant_info->serviceHub->user_id}}'
                        expert-first-name = '{{ucfirst($expert_profile->expertBasicInfo->name)}}'
                        service-hub-name = '{{$applicant_info->serviceHub->name}}'
                        >Approve Expert</button>
                    <button
                        class ="btn decline-button decline-expert"
                        user-id = '{{$user_id}}'
                        vendor-id = '{{$applicant_info->serviceHub->user_id}}'
                        applicant-id = '{{$applicant_info->applicantDetail->id}}'
                        expert-first-name = '{{ucfirst($expert_profile->expertBasicInfo->name)}}'
                        service-hub-name = '{{$applicant_info->serviceHub->name}}'
                        >Decline Expert</button>
                    @endif
                </div>
                </div>
            </div>
        </div>
        @if(isset($applicant_info->applicantDetail))
        <div class="row  block">
            <div class="col-lg-12">
                <div class="msg-bg">

                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                        <h4 class="font-16 gilroyregular-semibold"> Message: </h4>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel read-more-section">
                        @php
                            $message = $applicant_info->applicantDetail->recent_case_study ?? '';
                            $class = '';
                            $years_experience = $applicant_info->applicantDetail->total_experience ?? '';
                        @endphp
                        <p  class="margin-bottom-0"><span class="gilroyregular-semibold">Recent client case study:</span>
                        @if (strlen($message) > config('constants.EXPERT_PROFILE_MESSAGE_LIMIT'))
                            @php  $class = 'hide' @endphp
                            <div class="short-description-text short-description">
                                {{substr($message, 0, config('constants.EXPERT_PROFILE_MESSAGE_LIMIT'))}}
                                ... <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-more">Read more</a>
                            </div>
                        @endif
                        
                        <div class="full-description-text full-description {{$class}}">
                            {!! nl2br(e( $message )) !!}
                            @if ($class !== '')
                                <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-less margin-bottom-10">Read less</a>
                            @endif
                        </div>
                        </p>
                        
                        <div class="full-description margin-top-10">
                            <p class="margin-bottom-0"> 
                                <span class="gilroyregular-semibold">Years of {{$applicant_info->serviceHub->name}} experience:</span>
                            <div class="short-description-text short-description">@if(!empty($years_experience)) {{$years_experience}} {{ str_plural('year', $years_experience) }} @endif</div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->user_type_id  !== config('constants.EXPERT'))
        <div class="row block">
            <div class="col-lg-12">
                <div class="block-pedding">
                    <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                        <h4 class="font-16 gilroyregular-semibold">Bio:</h4>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9 right-panel read-more-section">
                        @php
                            $bio = $expert_profile->summary ?? '';
                            $class = '';
                        @endphp
                        @if (strlen($bio) > config('constants.EXPERT_PROFILE_SUMMARY_LIMIT'))
                            @php  $class = 'hide' @endphp
                            <div class="short-description-text short-description">
                                {!! substr($bio, 0, config('constants.EXPERT_PROFILE_SUMMARY_LIMIT')) !!}
                                ... <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-more">Read more</a>
                            </div>
                        @endif
                        <div class="full-description-text full-description {{$class}}">
                            {!! nl2br(e($bio)) !!}
                            @if ($class !== '')
                                <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-less margin-bottom-10">Read less</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="solution-advisory-skill-section">
            <div class="row block">
                <div class="col-lg-12">
                    <div class="block-pedding">
                        <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                            <h4 class="font-16 gilroyregular-semibold">Skills:</h4>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 skills-section-display right-panel">
                            @php $count = 0; @endphp
                            @forelse($expert_profile->userSkills as $index => $skill)
                                @php $count += 1; @endphp
                                <span class="skill-button font-16 @if ($index >= config('constants.MINIMUM_SKILLS_AND_TOOLS_COUNT')) hide extra-skills @endif">{{ucfirst($skill->name)}}</span>
                            @empty
                                <div class="profile-empty-state">
                                    <span  class="font-16 pull-left empty-state-panel">{{ucfirst($expert_profile->expertBasicInfo->name)}} has not added any strategic or advisory skills.</span>
                                </div>
                            @endforelse
                            @php $extra = $count - config('constants.MINIMUM_SKILLS_AND_TOOLS_COUNT'); @endphp
                            @if ($extra >0)
                                <span class="font-16 pull-left less-more-teextblock">
                                    <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold hide read-less-skills read-more">Less</a>
                                    @if ($extra > 0)
                                        <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-more read-more-skills read-more">+{{$extra}} more</a>
                                    @endif
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            @if(_count($expert_profile->userServicePackages))
                <div class="service-package-section">
                    <div class="row block">
                        <div class="col-lg-12">
                            <div class="block-pedding">
                                <div class="col-lg-3 col-md-3 col-sm-3 left-panel">
                                    <h4 class="font-16 gilroyregular-semibold">Service Packages:</h4>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 right-panel">
                                    @forelse($expert_profile->userServicePackages as $service_package)

                                        <div class="service-package-container read-more-section">
                                            <div class="service-inner-container">
                                                <h4 class="font-16 gilroyregular-semibold">{{ucfirst($service_package->name)}}</h4>
                                                @php
                                                    $description = $service_package->description;
                                                    $class = '';
                                                @endphp
                                                @if (strlen($description) > config('constants.SERVICE_PACKAGE_DESCRIPTION_LENGTH'))
                                                    @php  $class = 'hide' @endphp
                                                    <div class="short-description">
                                                        <span class="provided gilroyregular-semibold pull-left">Description:</span>
                                                        {{substr($description, 0, config('constants.SERVICE_PACKAGE_DESCRIPTION_LENGTH'))}} ...
                                                    </div>
                                                @endif

                                                <p class="expert-skills pull-left font-14"><span class="provided gilroyregular-semibold pull-left">Skills provided:</span>
                                                    @php $tags = [] @endphp
                                                    @foreach($service_package->servicePackageTags as $tags_name)
                                                        @php  $tags [] = $tags_name->toArray()['tags']['name'] @endphp
                                                    @endforeach
                                                    {{implode(', ', $tags)}}

                                                </p>
                                            </div>

                                            <div class="service-package-footer">
                                                <span class="font-14 pull-left"><span class="gilroyregular-semibold">Guide Budget:</span> ${{number_format($service_package->price)}}<span class="buyer-month">@if($service_package->subscription_type !="one_time_package"){{'/month'}} @endif</span> </span>
                                                @if(Auth::user()->user_type_id !== config('constants.ADMIN'))
                                                    <a class="gilroyregular-semibold" target="_blank" href="{{route('view-service-package', [$service_package->id])}}">View Service Package</a>
                                                @else
                                                    <a class="gilroyregular-semibold" target="_blank" href="{{ url('/admin/approvedservicepackage') . '/' . $service_package->id }}">View Service Package</a>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="profile-empty-state">
                                            <strong class="gilroyregular-semibold no-service-package"> {{ucfirst($expert_profile->expertBasicInfo->name)}} has not created any Service Packages </strong>
                                            <span  class="font-16 pull-left empty-state-panel margin-top-10 margin-bottom-10">MeasureMatch Experts can further showcase their talent, experience and services by creating Service Packages</span>
                                            <a href="{{url('servicepackage/types',[],$ssl)}}" class="pull-left browser-service-pacckage-link gilroyregular-semibold font-16 ">Browse Service Packages</a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php $class = 'hide' @endphp
            @else
                @php $class = '' @endphp
            @endif
            @if(Auth::user()->user_type_id !== config('constants.ADMIN'))
                <div class="view-profile user-full-profile gilroyregular-semibold">
                    <a href="{{route('expert-profile', [$user_id])}}" target="_blank">
                        View {{ucfirst(trim($expert_profile->expertBasicInfo->name))}}'s Full Profile
                    </a>
                </div>
            @else
                <div class="view-profile user-full-profile gilroyregular-semibold">
                    <a href="{{ url('admin/expertView') . '/' . $user_id}}" target="_blank">
                        View {{ucfirst(trim($expert_profile->expertBasicInfo->name))}}'s Full Profile
                    </a>
                </div>
            @endif
        </div>
        @endif
    </div>