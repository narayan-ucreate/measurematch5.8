<div class="col-sm-12 col-xs-12 seller-message-list-view col-lg-3 col-md-3 col-sm-3">
    <div class="bhoechie-tab-menu">
        <div class="list-group">
            <div class="conversation project-left-block section-container">
                <h3 class="chat-leftsec-title gilroyregular-semibold">Applicants ({{$applicants_count}})
                    @if($applicants_count)
                        <span class="hide-conversation pull-right hide-section"> Hide</span>
                    @endif
                </h3>
                <div class="express-off-interest-block express-view-off-interest-block">
                    @php
                        $total_no_of_user_display = config('constants.NO_OF_USER_DISPLAY_ON_RESULT');
                        $count = 0;
                        $experts = [];
                        $approved_emails = [];
                        $applicant_count = 0;
                    @endphp
                    @if($applicants_count)
                        @foreach($hub_info->unapprovedApplicants as $unapproved_applicant)
                        @php
                            $applicant_detail = $unapproved_applicant->applicantDetail;
                            $location = (!empty($applicant_detail->expertDetail->user_profile->current_city)) ? $applicant_detail->expertDetail->user_profile->current_city.', '.$applicant_detail->expertDetail->user_profile->country : "";
                            $image_url = isset($applicant_detail->expertDetail->user_profile->profile_picture) ? $applicant_detail->expertDetail->user_profile->profile_picture:url(config('constants.DEFAULT_PROFILE_IMAGE'));
                            $expert_type = ($applicant_detail->expertDetail->user_profile->expert_type == 'Independent')? 'Independent Consultant' : 'Consultancy/Agency';
                            $experts [] = [
                                        'image_url' => $image_url
                                    ];
                        @endphp
                        <a class= 'list-group-item text-center msgLst contact-list unapproved-applicant
                            @if($applicant_count == 0){{'active'}}@endif'
                            href= 'javascript:void(0)'
                            service_hub_associated_expert_id= '{{$unapproved_applicant->id}}'
                            applicant_id = '{{$applicant_detail->id}}'>
                                <span class= 'profile-img' style= 'background-image:url({{$image_url}})'></span>
                                <span class= 'seller-info service-hub-applicant'>
                                    <span class= 'seller-name receiver-name gilroyregular-semibold'>{{getTruncatedContent(ucwords($applicant_detail->expertDetail->name)." ".ucwords(substr($applicant_detail->expertDetail->last_name, 0, 1)), 18)}}</span>
                                    <span class= 'time gilroyregular-semibold'>{{date('M d', strtotime($applicant_detail->created_at))}}</span>
                                    <span class= 'seller_job_postion user-message-notification'>{{$expert_type}}</span>
                                    <span class= 'seller_job_postion margin-bottom-0 user-title'>{{$location}}</span>
                                    @if(!$applicant_detail->is_read && Auth::user()->user_type_id !== config('constants.ADMIN'))
                                        <span class="count-message"><span class="unread-message-count">1</span></span>
                                    @endif
                                </span>
                        </a>
                        @php $applicant_count++; @endphp
                        @endforeach
                    @else
                    <a class="list-group-item text-center msgLst contact-list no-listing @if($total_applications === 0){{'active'}}@endif" href="javascript:void(0)">
                            <span class="seller-info no-applicant-message">
                                @if($total_applications === 0)
                                    <span class="seller-name gilroyregular-semibold">You've not had any applicants yet.</span>
                                    <span class="seller_job_postion margin-bottom-0 user-title">Sit tight.</span>
                                @else
                                    <span class="seller-name gilroyregular-semibold">No pending applications.</span>
                                @endif
                            </span>
                    </a>
                    @endif
                </div>
                <div class="show-express-conversation-block  list-group-item thumbnail-user-list hide">
                     <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font pull-left">
                        @foreach($experts as $expert)
                             @php   $count += 1; @endphp
                             @if($count <= $total_no_of_user_display)
                                 <span style="background-image:url({{$expert['image_url']}})" alt="expert" class="expert-profile-pic"></span>
                             @endif
                         @endforeach

                         @if($count > $total_no_of_user_display)
                             <span class="expert-profile-extend" alt="expert">+{{$count-$total_no_of_user_display}}</span>
                         @endif
                    </span>
                    <span class=" pull-left show-conversation view-all-user">View all</span>
                </div>
            </div>
            @if($hub_info && _count($hub_info->approveExperts))
                @php
                $count = 0;
                $experts = [];
                $approved_experts_count = _count($hub_info->approveExperts) ?? 0;
                @endphp
            <div class="expressions project-left-block section-container">
                <h3 class="chat-leftsec-title gilroyregular-semibold">Approved ({{$approved_experts_count}})
                    @if (_count($hub_info->approveExperts))
                        <span class="hide-conversation pull-right hide-section"> Hide</span>
                    @endif
                </h3>
                <div class="express-view-off-interest-block @if($applicants_count){{'hide'}}@endif">
                    @php $approved_expert_loop_count = 0; @endphp
                    @foreach($hub_info->approveExperts as $expert)
                        @php
                            $expert_type = ($expert->expertDetail->user_profile->expert_type == 'Independent')? 'Independent Consultant' : 'Consultancy/Agency';
                            $location = (!empty($expert->expertDetail->user_profile->current_city)) ? $expert->expertDetail->user_profile->current_city.', '.$expert->expertDetail->user_profile->country : "";
                            $approved_emails [] = $expert->expertDetail->email;
                            $image_url = isset($expert->expertDetail->user_profile->profile_picture) ? $expert->expertDetail->user_profile->profile_picture:url(config('constants.DEFAULT_PROFILE_IMAGE'));
                            $experts [] = [
                                'image_url' => $image_url
                            ];
                        @endphp
                        <a class = "list-group-item text-center msgLst contact-list unapproved-applicant @if(($applicant_count == 0) && ($approved_expert_loop_count == 0)){{'active'}}@endif"
                           href = "javascript:void(0)"
                           user_id = '{{$expert->expertDetail->id}}'
                           service_hub_associated_expert_id = '{{$expert->id}}'>
                                  <span class= 'profile-img' style= 'background-image:url({{$image_url}})'></span>
                                <span class="seller-info service-hub-applicant">
                                    <span class="seller-name receiver-name gilroyregular-semibold">{{getTruncatedContent(ucwords($expert->expertDetail->name)." ".ucwords(substr($expert->expertDetail->last_name, 0, 1)), 18)}}</span>
                                    <span class="time  gilroyregular-semibold">{{date('M d', strtotime($expert->expertDetail->created_at))}}</span>
                                     <span class= 'seller_job_postion user-message-notification'>{{$expert_type}}</span>
                                    <span class= 'seller_job_postion margin-bottom-0 user-title'>{{$location}}</span>
                                </span>
                        </a>
                        @php $approved_expert_loop_count++; @endphp
                    @endforeach
                </div>
                <div class="show-express-conversation-block list-group-item thumbnail-user-list @if(!$applicants_count){{'hide'}}@endif">
                     <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font pull-left">
                        @foreach($experts as $expert)
                             @php   $count += 1; @endphp
                             @if($count <= $total_no_of_user_display)
                                 <span style="background-image:url({{$expert['image_url']}})" alt="expert" class="expert-profile-pic"></span>
                             @endif
                         @endforeach
                         @if($count > $total_no_of_user_display)
                             <span class="expert-profile-extend" alt="expert">+{{$count-$total_no_of_user_display}}</span>
                         @endif
                    </span>
                    <span class=" pull-left view-all-user show-conversation">View all</span>
                </div>
            </div>
            @endif
            @php
                $invite_experts = '';
                $total_invite = 0;
                if(isset($hub_info->serviceExperts))
                {
                    foreach($hub_info->serviceExperts as $expert) {
                        if(!in_array($expert->email, $approved_emails)) {
                        $total_invite += 1;
                            $invite_experts = $invite_experts. '
                            <a class="list-group-item text-center msgLst contact-list no-pointer-vendor-hub" href="javascript:void(0)" >
                                <span class="seller-info">
                                        <span class="seller-name receiver-name gilroyregular-semibold">'.$expert->email.'</span>
                                        <span class="time  gilroyregular-semibold">'.date('M d', strtotime($expert->created_at)).'</span>
                                        <span class="seller_job_postion margin-bottom-0 user-title">Invite Sent</span>
                                    </span>
                            </a>';
                        }
                    }
                }
            @endphp
            @if($total_invite)
            <div class="expressions project-left-block">
                <h3 class="chat-leftsec-title gilroyregular-semibold">Invited ({{$total_invite}}) </h3>
                <div class="express-view-off-interest-block vendor-invited-block">
                    {!! $invite_experts !!}

                </div>
            </div>
            @endif
            @if($declined_count)
            <div class="conversation project-left-block section-container">
                <h3 class="chat-leftsec-title gilroyregular-semibold">Declined ({{$declined_count}})
                    @if($declined_count)
                        <span class="hide-conversation pull-right hide-section hide"> Hide</span>
                    @endif
                </h3>
                <div class="express-off-interest-block express-view-off-interest-block hide">
                    @if($declined_count)
                        @php $experts = []; @endphp
                    @foreach($hub_info->declinedApplicants as $declinedApplicant)
                    @php
                        $count = 0;
                        $location = (!empty($declinedApplicant->expertDetail->user_profile->current_city)) ? $declinedApplicant->expertDetail->user_profile->current_city.', '.$declinedApplicant->expertDetail->user_profile->country : "";
                        $image_url = isset($declinedApplicant->expertDetail->user_profile->profile_picture) ? $declinedApplicant->expertDetail->user_profile->profile_picture:url(config('constants.DEFAULT_PROFILE_IMAGE'));
                        $expert_type = ($declinedApplicant->expertDetail->user_profile->expert_type == 'Independent')? 'Independent Consultant' : 'Consultancy/Agency';
                        $experts [] = [
                                    'image_url' => $image_url
                                ];
                    @endphp
                    <a
                        class = 'list-group-item text-center msgLst contact-list unapproved-applicant'
                        href = 'javascript:void(0)'
                        user_id = '{{$declinedApplicant->expertDetail->id}}'
                        service_hub_associated_expert_id = '{{$declinedApplicant->id}}'
                        >
                            <span class= 'profile-img' style= 'background-image:url({{$image_url}})'></span>
                            <span class= 'seller-info service-hub-applicant'>
                                <span class= 'seller-name receiver-name gilroyregular-semibold'>{{getTruncatedContent(ucwords($declinedApplicant->expertDetail->name)." ".ucwords(substr($declinedApplicant->expertDetail->last_name, 0, 1)), 18)}}</span>
                                <span class= 'time gilroyregular-semibold'>{{date('M d', strtotime($declinedApplicant->created_at))}}</span>
                                <span class= 'seller_job_postion user-message-notification'>{{$expert_type}}</span>
                                <span class= 'seller_job_postion margin-bottom-0 user-title'>{{$location}}</span>
                            </span>
                    </a>
                    @endforeach
                    @endif
                </div>
                <div class="show-express-conversation-block  list-group-item thumbnail-user-list">
                     <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font pull-left">
                        @foreach($experts as $expert)
                             @php   $count += 1; @endphp
                             @if($count <= $total_no_of_user_display)
                                 <span style="background-image:url({{$expert['image_url']}})" alt="expert" class="expert-profile-pic"></span>
                             @endif
                         @endforeach

                         @if($count > $total_no_of_user_display)
                             <span class="expert-profile-extend" alt="expert">+{{$count-$total_no_of_user_display}}</span>
                         @endif
                    </span>
                    <span class=" pull-left view-all-user show-conversation">View all</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
