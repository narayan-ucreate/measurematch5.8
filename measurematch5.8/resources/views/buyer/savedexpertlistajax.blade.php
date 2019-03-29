
<div class="v-align-box">@if($load_more_results == FALSE)
<div id="saved_experts_heading"><h3>{{$saved_experts_heading}}</h3></div>
<div class="pull-right project-list-filter">
    @if(_count($saved_projects_list_for_expert))
    <select class="selectpicker" id="project_list">
        <option value="-1" @if($selected_project==-1)selected='selected'@endif>All Saved Experts</option>
        @foreach($saved_projects_list_for_expert as $key => $project)
                <option value="{{$project['post_job_id']}}" @if($project['post_job_id']==$selected_project)selected='selected'@endif>
                        @if(strlen($project['post_job']['job_title'])>22)
                            {{ucfirst(substr($project['post_job']['job_title'],0,22)).'...'}}
                        @else
                            {{ucfirst($project['post_job']['job_title'])}}
                        @endif
                </option>
        @endforeach
    </select>
    @endif
</div>
@endif

@if(_count($users))
@php $count = 0; @endphp
@foreach($users as $key => $user)
@php $count++; @endphp
    <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
        <div class="search-result-white-bx">
            <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['expert']['id']}}')"><span class="expert-profile-pic" style="background-image:url({{$user['expert']['user_profile']['profile_picture']}});"></span></a>
            <h4>{{ucfirst($user['expert']['name']) . " " . ucfirst(substr($user['expert']['last_name'], 0,1))}}
            <a href="javascript:void(0)" class="saved-expert saved-icon remove_expert save-expert-icon" user_id="{{$user['expert']['id']}}" post_job_id="{{$user['post_job_id']}}"></a>
            </h4>
            <span class="expert-job">
                @if(strlen($user['expert']['user_profile']['describe'])>28)
                    {{strip_tags(ucfirst(substr($user['expert']['user_profile']['describe'],0,28))).'...'}}
                @else
                    {{strip_tags(ucfirst($user['expert']['user_profile']['describe']))}}
                @endif
            </span>
            <span class="country-flag">
                @if(!empty($user['expert']['user_profile']['country']) && !empty(getCountryFlag($user['expert']['user_profile']['country']))) 
                    <img src="{{getCountryFlag($user['expert']['user_profile']['country'])}}">
                @endif
                @if(strlen($user['expert']['user_profile']['current_city'])>28)
                    {{ucfirst(substr($user['expert']['user_profile']['current_city'],0,28)).'...'}}
                @else
                    {{ucfirst($user['expert']['user_profile']['current_city'])}}
                @endif
            </span>
            <p>@if(strlen($user['expert']['user_profile']['summary'])>142)
                    {{strip_tags(ucfirst(substr($user['expert']['user_profile']['summary'],0,142))).'...'}}
                @else
                    {{strip_tags(ucfirst($user['expert']['user_profile']['summary']))}}
                @endif
            </p>

            <div class="view-profile-block">
                <div class="bottom-white-bx">
                    <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['expert']['id']}}')">View Profile</a>
                </div>
            </div>
        </div>
    </div>
@if($count%3 == 0) <div class="clearfix clearline"></div> @endif
@endforeach
@endif
</div>
@if($load_more_results == FALSE)
<script>
$(document).ready(function() {
  $(".selectpicker").selectpicker();
});
</script>
@endif
