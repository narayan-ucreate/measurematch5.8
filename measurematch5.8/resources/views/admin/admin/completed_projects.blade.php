<thead>
<tr>

    <th width="200"><a data="asc" href="{{url('admin/completedProjects?orderBy='.$orderBy.'&data-sort=buyer_profile.company_name',[],$ssl)}}">Client Company</a></th>
    <th><a data="asc" href="{{url('admin/completedProjects?orderBy='.$orderBy.'&data-sort=users.name',[],$ssl)}}">Expert</a> </th>
    <th><a data="asc" href="{{url('admin/completedProjects?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">Project Name</a> </th>
    <th><a data="asc" href="{{url('admin/completedProjects?orderBy='.$orderBy.'&data-sort=contracts.job_start_date',[],$ssl)}}">Contract Start</a></th>
    <th><a data="asc" href="{{url('admin/completedProjects?orderBy='.$orderBy.'&data-sort=contracts.job_end_date',[],$ssl)}}">Contract End</a></th>
</tr>
</thead>
<tbody>

@if(isset($total) && !empty($total))
    @foreach ($result as $job)
        <tr class="make-clickable" data-url="{{ url('admin/project', $job['project_id'], $ssl) }}">
            <td> {{ $job['company_name']}}</td>
            <td> {{ $job['name'] }} {{ $job['last_name'] }} </td>
            <td> {{ $job['job_title'] }} </td>
            <td> {{ date('d-m-Y',strtotime($job['job_start_date'])) }} </td>
            <td> {{ date('d-m-Y',strtotime($job['job_end_date'])) }} </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="7">No Result Found</td></tr>
@endif
</tbody>