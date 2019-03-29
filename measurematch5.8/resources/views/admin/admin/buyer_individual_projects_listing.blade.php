@php
$buyer_id = Request::segment(3);
$orderBy = orderBy($_REQUEST);
@endphp
<thead>
    <tr>
        <th><a id="company_name" data="asc" href="{{url('admin/getProjects/'.$buyer_id.'?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">Projects ({{$total}})</a></th>                                    
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @php
    $currentDate = date('Y-m-d');
    @endphp
    @if(_count($published_projects))
    @foreach ($published_projects as $project)
    <tr class="make-clickable" data-url="{{ url('admin/project?from_buyer_project_listing=true', $project->id, $ssl) }}">
        <td>{{$project['job_title']}}</td>    
        <td class="published-project-action">
            <a title="View" href="{{ url('admin/project?from_buyer_project_listing=true',$project->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> 
            <a class="@if((!empty($project->contract)  && $project->contract->status== config('constants.ACCEPTED'))){{'disable-button'}}@endif" 
               title="Edit" href='{{ url("admin/project/edit?from_buyer_project_listing=true&&redirect-url=getProjects/$buyer_id",$project->id,$ssl) }}'>
                <i data-id="{{$project->id}}" class="fa fa-pencil editUser"></i></a>
        </td>                               
    </tr>
    @endforeach 
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>