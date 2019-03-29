<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 seller-message-list-view " style="height: 500px;">
    <div class="bhoechie-tab-menu">
        <div class="list-group">
        @if($user_type==1)
            @if(isset($user_list) && !empty($user_list))
                @foreach($user_list as $user)
                    @php
                    $is_started = ($user->status==0)?'no':'yes';
                    $unread_messages = $user->unreadProjectMessageCount->count();
                    $image_url = isset($user->profile_picture)?$user->profile_picture:url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl);
                    $hide_company_name = (isset($user->buyerCompanyNameHidden->hide_company_name) && $user->buyerCompanyNameHidden->hide_company_name) ? true : false;
                    @endphp
                    <a class="list-group-item text-center msgLst contact-list" href="javascript:void(0)"
                       start-conversation="{{ $is_started }}"  communication-id="{{ $user->communication_id }}" receiver-id="{{ $user->receiver_id }}" sender-id="{{ $user->sender_id }}" user-type="2">
                        <span class='profile-img chatlisting-buyerimg' style='background-image:url({{$image_url}})'></span>
                        <span class="seller-info">
                            @if(date('Y-m-d', strtotime($user->created_at)) != date('Y-m-d'))
                                @php $date_time = addTimeZone('M y', $user->created_at); @endphp
                            @else
                                @php $date_time = addTimeZone('h:i a', $user->created_at); @endphp
                            @endif
                            <span class="seller-name receiver-name gilroyregular-semibold">{{ getTruncatedContent(ucwords($user->name)." ".ucwords(substr($user->last_name, 0, 1)), 18) }}</span>
                            <span class="time gilroyregular-semibold">{{ $date_time }}</span>
                            <span class="seller-company-name user-title">{{ $hide_company_name ? ' ' : substr($user->company_name, 0, 30) }}</span>
                            <span class='count-message' id="communication_id_{{ $user->communication_id }}">
                            @if($unread_messages)
                                <span class="unread-message-count"> {{ $unread_messages }} </span>
                            @endif
                            </span>
                        </span>
                    </a>
                @endforeach
            @endif
        @else
            @php
            $in_conversation = $expressions_of_interest = $in_contract = "";
            $in_conversation_counter = $expressions_of_interest_counter = $in_contract_counter = 0;
            $in_conversation_profile_pictures = $expressions_of_interest_profile_pictures = "";
            @endphp
            @if(isset($user_list) && !empty($user_list))
                @foreach($user_list as $user)
                    @php
                    $unread_messages = $user->unreadProjectMessageCount->count();
                    $date_time = addTimeZone('h:i a', $user->created_at);
                    if(date('Y-m-d', strtotime($user->created_at)) != date('Y-m-d')){
                            $date_time = addTimeZone('M y', $user->created_at);
                    }
                    $expert_type=($user->expert_type=='Independent')? 'Independent Consultant' : $user->expert_type ;
                    $unread_messages=($unread_messages)?"<span class='unread-message-count'>$unread_messages</span>" : "";
                    $image_url=isset($user->profile_picture)?$user->profile_picture:url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl);
                    $location=(!empty($user->current_city))?"$user->current_city,  $user->country" : "";
                    $status= ($user->status == config('constants.TRUE'))? "yes" : "no";
                    $rebook_class = '';
                    if(strlen($user->name) > 5) {
                        $rebook_class = 'rebook-full-width';
                    }
                    $list_view="<a class='list-group-item text-center msgLst contact-list' href='javascript:void(0)' start-conversation='$status'
                             communication-id='$user->communication_id' receiver-id='$user->receiver_id' sender-id='$user->sender_id' user-type='1'>";
                    $list_view .="<span class='profile-img' style='background-image:url($image_url)'></span>";
                    $list_view .="<span class='seller-info'>";
                    $list_view .="<span class='seller-name receiver-name gilroyregular-semibold'>".getTruncatedContent(ucwords($user->name)." ".ucwords(substr($user->last_name, 0, 1)), 18)."</span>";
                    $list_view .="<span class='time  gilroyregular-semibold'>$date_time</span>";
                    $list_view .="<span class='seller_job_postion user-message-notification'>$expert_type</span>";
                    $list_view .="<span class='seller_job_postion margin-bottom-0 user-title'>$location</span>";
                    $list_view .="<span class='count-message' id='communication_id_$user->communication_id'>";
                    $list_view .="$unread_messages";
                    $list_view .="</span>";
                    $list_view .="</span>";
                    $list_view .=" </a> ";

                    if(!empty($user->relatedContract) && $type == config('constants.PROJECT') && isBuyer()){
                        $list_view .="<span class='rebooking-section {$rebook_class}'>";
                        $list_view .="<span class='rebooking-content-inner'>";
                        $list_view .="<span class='rebooking-content'>Want to work with ".$user->name." on a different project?";
                        $list_view .="</span>";
                        $list_view .="<span class='rebooking-button'>";
                        $list_view .="<span data-expert-url ='".$image_url."'
                                       data-expert-id ='".$user->receiver_id."'
                                       data-expert-name ='".$user->name."'
                                       class = 'book-again-btn rebook-project'>Book ".$user->name." Again";
                        $list_view .="</span>";
                        $list_view .="</span>";
                        $list_view .="</span>";
                        $list_view .="</span>";
                    }

                    if($user->status == config('constants.TRUE')) {
                        if(!empty($user->relatedContract))
                        {
                            $in_contract_counter++;
                            $in_contract .= $list_view;
                        }
                        else {
                            $in_conversation_counter++;
                            $in_conversation .= $list_view;
                            if ($in_conversation_counter < 4){
                                $in_conversation_profile_pictures .= "<span style='background-image:url($image_url)' alt='expert' class='expert-profile-pic'></span>";
                            }
                        }
                    } else {
                        $expressions_of_interest_counter++;
                        $expressions_of_interest .= $list_view;
                        if ($expressions_of_interest_counter < 4){
                            $expressions_of_interest_profile_pictures .= "<span style='background-image:url($image_url)' alt='expert' class='expert-profile-pic'></span>";
                        }
                    }
                    @endphp
                @endforeach
            @endif
            @if($in_contract_counter)
                <div class="project-left-block">
                    <h3 class="chat-leftsec-title gilroyregular-semibold"> Contract agreed ({{ $in_contract_counter }})</h3>
                    {!! $in_contract !!}
                </div>
            @endif
            <div class="conversation project-left-block">
                <h3 class="chat-leftsec-title gilroyregular-semibold">In conversation ({{ $in_conversation_counter }}) </h3>
                @if($in_conversation_counter)
                    <span class="hide-conversation pull-right hide-section @if($in_contract_counter){{'hide'}}@endif">Hide</span>
                    <div class="express-off-interest-block @if($in_contract_counter){{'hide'}}@endif">
                        {!! $in_conversation !!}
                    </div>
                    <div class="show-conversation-block list-group-item @if(!$in_contract_counter){{'hide'}}@endif">
                        <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font pull-left">
                            {!! $in_conversation_profile_pictures !!}
                            @if($in_conversation_counter > 3)
                                <span class="expert-profile-extend" alt="expert">+{{ $in_conversation_counter - 3 }}</span>
                            @endif
                        </span>
                        <span class="show-conversation pull-left">View all</span>
                    </div>
                @else
                    <span class="text empty-state">Your conversations with Experts about this Project will appear here.</span>
                @endif
            </div>
            @if (isset($rebook_project) && !$rebook_project)
            <div class="expressions project-left-block">
                <h3 class="chat-leftsec-title gilroyregular-semibold">Expressions of Interest ({{ $expressions_of_interest_counter }}) </h3>
                @if($expressions_of_interest_counter)
                    <span class="hide-conversation pull-right hide-section @if($in_contract_counter){{'hide'}}@endif">Hide</span>
                    <div class="express-view-off-interest-block @if($in_contract_counter){{'hide'}}@endif">
                        {!! $expressions_of_interest !!}
                    </div>
                    <div class="show-express-conversation-block show-express-conversation-block-link list-group-item @if(!$in_contract_counter){{'hide'}}@endif">
                        <span class="job-posted job-posted-awaiting-panel gilroyregular-bold-font pull-left">
                            {!! $expressions_of_interest_profile_pictures !!}
                            @if($expressions_of_interest_counter > 3)
                                <span class="expert-profile-extend" alt="expert">+{{ $expressions_of_interest_counter - 3 }}</span>
                            @endif
                        </span>
                        <span class="show-express-conversation-block-link pull-left @if(!$in_contract_counter){{'hide'}}@endif">View all</span>
                    </div>
                @else
                    <span class="text empty-state">You are in conversation with all Experts who expressed interest in your Project.</span>
                @endif
            </div>
            @endif
        @endif
        </div>
    </div>
</div>
