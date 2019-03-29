<thead>
    <tr>
        <th width="25%"><a id="name" data="asc" href="{{url('admin/archivedProjects?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Client Name</a></th>
        <th width="20%"><a id="email" data="asc" href="{{url('admin/archivedProjects?orderBy='.$orderBy.'&data-sort=u.email',[],$ssl)}}">Email</a></th>
        <th width="30%"><a id="company_name" data="asc" href="{{url('admin/archivedProjects?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">Project Name</a> </th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/archivedProjects?orderBy='.$orderBy.'&data-sort=post_jobs.created_at',[],$ssl)}}">Date Posted</a></th>

        <th width="10%">Action</th>
    </tr>
</thead>
<tbody>

    @if(isset($total) && !empty($total))
    @foreach ($result as $job)
    @php $user = $job['user']; @endphp
    <tr  class="make-clickable" data-url="{{ url('admin/project', $job['id'], $ssl) }}">
        <!-- Task Name -->
        <td> {{ ucwords($user['name']) }} {{ ucwords($user['last_name']) }} </td>
        <td class="email-column"> {{ $user['email'] }} </td>
        <td> {{ $job['job_title'] }} </td>

        <td> {{ date('d-m-Y',strtotime($job['created_at'])) }} </td>
        <td><a  title="View" href="{{ url('admin/project',$job['id'],$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>