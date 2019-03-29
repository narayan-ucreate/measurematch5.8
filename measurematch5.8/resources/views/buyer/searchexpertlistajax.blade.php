<div class="v-align-box">@if(_count($users))
@php $count = 0; @endphp
@foreach($users as $key => $user)
@php $count++; @endphp
    <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col match-result-section browse-new-theme">
        <div class="search-result-white-bx">
            <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['id']}}')"><span class="expert-profile-pic" style="background-image:url({{$user['complete_mandatory_fields']['profile_picture']}});"></span></a>
            <h4>{{userName($user['id'],1)}}
                <a href="javascript:void(0)" class="saved-expert saved-icon save_expert @if(in_array($user['id'], $all_saved_experts)) save-expert-icon @endif" user_id="{{$user['id']}}"></a>
            <div class="white-theme-tooltip active_project_listing" id="{{$user['id']}}"></div>
            </h4>
            <span class="expert-job">
                @if(strlen($user['complete_mandatory_fields']['describe'])>28)
                    {{strip_tags(ucfirst(substr($user['complete_mandatory_fields']['describe'],0,28))).'...'}}
                @else
                    {{strip_tags(ucfirst($user['complete_mandatory_fields']['describe']))}}
                @endif
            </span>
            <span class="country-flag">
                @if(!empty($user['complete_mandatory_fields']['country']) && !empty(getCountryFlag($user['complete_mandatory_fields']['country'])))
                    <img src="{{getCountryFlag($user['complete_mandatory_fields']['country'])}}">
                @endif
                @if(strlen($user['complete_mandatory_fields']['current_city'])>28)
                    {{ucfirst(substr($user['complete_mandatory_fields']['current_city'],0,28)).'...'}}
                @else
                    {{ucfirst($user['complete_mandatory_fields']['current_city'])}}
                @endif
            </span>
            <p>@if(strlen($user['complete_mandatory_fields']['summary'])>142)
                    {{strip_tags(ucfirst(substr($user['complete_mandatory_fields']['summary'],0,142))).'...'}}
                @else
                    {{strip_tags(ucfirst($user['complete_mandatory_fields']['summary']))}}
                @endif
            </p>

            <div class="view-profile-block">
                <div class="bottom-white-bx">
                    <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['id']}}')">View Profile</a>
                </div>
            </div></div>
    </div>
@if($count%3 == 0) <div class="clearfix clearline"></div> @endif
@endforeach
@endif
</div>
