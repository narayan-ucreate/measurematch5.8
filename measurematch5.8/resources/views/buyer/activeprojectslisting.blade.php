@if(_count($jobs_list))
    <span class="tooltip-close-btn"></span>
    <span class="tooltip-up-arrow"></span>
    <ul class="white-theme-tooltip active_project_listing">
        <li class="title">Also save to a Project?</li>
        @foreach($jobs_list as $job)
        <li>
            <input type="checkbox" id="{{$job['id']}}_{{$expert_id}}" class="save_to_project" value="{{$job['id']}}" expert_id="{{$expert_id}}" @if(in_array( $job['id'], $saved_projects_list_for_expert)) checked='checked' disabled @endif>
            <label for="{{$job['id']}}_{{$expert_id}}">
                @if(strlen($job['job_title'])>22)
                    {{ucfirst(substr($job['job_title'],0,22)).'...'}}
                @else
                    {{ucfirst($job['job_title'])}}
                @endif
            </label>
        </li>
        @endforeach
    </ul>
@endif