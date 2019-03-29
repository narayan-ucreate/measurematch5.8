<thead>
    <tr>

        <th width="200"><a id="name" data="asc" href="{{url('admin/liveProjects?orderBy='.$orderBy.'&data-sort=buyer_profile.company_name',[],$ssl)}}">Client Company</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/liveProjects?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">Project Name</a> </th>
        <th><a id="date_register" data="asc" href="{{url('admin/liveProjects?orderBy='.$orderBy.'&data-sort=post_jobs.created_at',[],$ssl)}}">Date Posted</a></th>
        <th><a id="date_register" data="asc" href="{{url('admin/liveProjects?orderBy='.$orderBy.'&data-sort=post_jobs.publish',[],$ssl)}}">Status</a></th>
        <th><a id="date_register" data="asc" href="{{url('admin/liveProjects?orderBy='.$orderBy.'&data-sort=communication_count',[],$ssl)}}">No. of EOIs</a></th>
        <th width="15%">Action</th>
    </tr>
</thead>
<tbody>

    @if(isset($total) && !empty($total))
    @foreach ($result as $job)
    @php $user = $job['user']; @endphp
    <tr class="make-clickable" data-url="{{ url('admin/project', $job['id'], $ssl) }}">
        <td width="200"> {{ $job['buyer']['company_name']}}</td>
        <td> {{ $job['job_title'] }} </td>
        <td width="120"> {{ date('d-m-Y',strtotime($job['created_at'])) }} </td>
        <td> {{ $status }} </td>
        <td> {{ $job['communication_count'] }} </td>
        <td width="50">
            <a  title="View" href="{{ url('admin/project',$job['id'],$ssl) }}?redirect-url=liveProjects">
                <i class="fa fa-fw fa-eye"></i>
            </a>  | 
            <a  title="Edit" href="{{ url('admin/project/edit/'.$job['id'],[],$ssl) }}?redirect-url=liveProjects">
                <i data-id="" class="fa fa-pencil editUser"></i>
            </a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>