<a href="javascript:void(0)" class="edit_icon">
    <img width="15" alt="pen" src="{{url('images/pen.png',[],$ssl)}}"></a>
<div id="profile_not_completed" style="display: none;">
    "Profile completion" must be completed before editing other areas of your profile.
</div>
@if(!empty($user_profile['user_profile']['expert_type']))
    <h3 class="gilroyregular-bold-font">
        @if($user_profile['user_profile']['expert_type']=='Independent')
            Independent Consultant
        @else
            Consultancy/Agency
        @endif
    </h3>
@endif
<ul>
    @php
        $string_count = strlen($user_profile['user_profile']['describe']);
        if ($string_count > 60) {
            $add_dot = '...';
        } else {
            $add_dot = '';
        }
        if ($user_profile['user_profile']['describe'] != '') {
            $describe = substr($user_profile['user_profile']['describe'], 0, 60);
        } else {
            $describe = '';
        }
    @endphp
    @if($user_profile['user_profile']['expert_type']=='Consultancy')
        <li class="seller_ic_location no-of-expert-icon">
            <img src="{{url('images/no-of-experts.svg',[],$ssl)}}" alt="ic_tag" class="location-icon ic_tag" />
            <span>
                                            @if(empty($user_profile['user_profile']['experts_count_lower_range']))
                    No. of Experts
                @else
                    {{ numberOfExpertsRange($user_profile['user_profile']['experts_count_lower_range']) }}
                @endif
                                       </span>
        </li>
    @endif
    <li class="seller_ic_location">
        <img src="{{url('images/ic_tag.svg',[],$ssl)}}" alt="ic_tag" class="location-icon ic_tag" />
        <span class="container-describe">@if(isset($user_profile['user_profile']['describe']))
                {{ $describe }}{{ $add_dot }}
            @else
                Your MeasureMatch profile title
            @endif
                                        </span>
    </li>
    <li class="seller_ic_location">
        <img src="{{url('images/ic_location.svg',[],$ssl)}}" alt="location-icon" class="location-icon" />
        <span>
                                            @if(isset($user_profile['user_profile']['current_city']))
                                                <span class="view_location">
                                                    {{ucfirst($user_profile['user_profile']['current_city'])}}@if(isset($user_profile['user_profile']['country'])), {{ucfirst($user_profile['user_profile']['country'])}}
                                                </span>

                @endif
            @else
                Your location
            @endif
                                      </span>
    </li>
    <li>
        <img src="{{url('images/dollarBill_ic.svg',[],$ssl)}}" alt="dollarBill_ic" class="location-icon dollarBill_ic" />
        <span>
                                            @if(!empty($user_profile['user_profile']['daily_rate']))
                {{ $user_profile['user_profile']['currency'].''
                .(number_format($user_profile['user_profile']['daily_rate'])?? '')}}
                {{ '/day' }}
            @else
                Your daily rate
            @endif
                                        </span>
    </li>
    <li class="seller_ic_remote_working">
        <img src="{{url('images/ic_remote_working.svg',[],$ssl)}}" alt="ic_remote_working" class="location-icon" />
        <span class="profile-italic" id="view_location_preference" data-remote-id="{{$user_profile['user_profile']['remote_work']['id']}}">
                                            @if(!empty($user_profile['user_profile']['remote_work']))
                {{$user_profile['user_profile']['remote_work']['name']}}
            @else
                <a href="javascript:void(0)" data-toggle="modal"  class="addbtn"
                   onClick="editSummaryDetail({{$user_profile['user_profile']['id']}},
                           '{{$user_profile['user_profile']['current_city']}}',
                           '{{$user_profile['user_profile']['remote_work']['id']}}',
                           '{{ str_replace("'", "\'", $user_profile['user_profile']['describe']) }}')">
                                                Add Remote working
                                            </a>
            @endif
                                        </span>
    </li>
</ul>