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
        <div class="view-profile-block">
            <div class="bottom-white-bx">
                <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$approved_expert->expertDetail->id}}')">View Profile</a>
            </div>
        </div>
    </div>
</div>