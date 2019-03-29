<thead>
    <tr>

        <th width="200"><a id="name" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=buyer_profile.company_name',[],$ssl)}}">Client Company</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">Project Name</a> </th>
        <th><a id="date_register" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=post_jobs.publish_date',[],$ssl)}}">Date Posted</a></th>
        <th><a id="date_register" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=post_jobs.publish',[],$ssl)}}">Status</a></th>
        <th><a id="date_register" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=communication_count',[],$ssl)}}">No. of EOIs</a></th>
        <th><a id="date_register" data="asc" href="{{url('admin/expiredProjects?orderBy='.$orderBy.'&data-sort=post_jobs.rate',[],$ssl)}}">Value</a></th>
        <th width="15%">Action</th>
    </tr>
</thead>
<tbody>

    @if(isset($total) && !empty($total))
    @foreach ($result as $job)
    @php 
        $user = $job['user']; 
        $expert_name = '--';
        $currency = convertToCurrencySymbol($job['currency']);
        if(!empty($job['name']))
            $expert_name = $job['name'].' '.$job['last_name'];
        $project_budget = ($job['rate']) ? $currency.number_format($job['rate']): "Negotiable (".$currency.")";
    @endphp
    <tr class="make-clickable" data-url="{{ url('admin/project', $job['project_id'], $ssl) }}">
        <td width="200"> {{ $job['company_name']}}</td>
        <td> {{ $job['job_title'] }} </td>
        <td width="120"> {{ date('d-m-Y',strtotime($job['publish_date'])) }} </td>
        <td> {{ $status }} </td>
        <td> {{ $job['communication_count'] }} </td>
        <td> {{ $project_budget }} </td>
        <td width="50">
            <a  title="View" href="{{ url('admin/project',$job['project_id'],$ssl) }}?redirect-url=expiredProjects">
                <i class="fa fa-fw fa-eye"></i>
            </a>  | 
            <a  title="Edit" href="{{ url('admin/project/edit/'.$job['project_id'],[],$ssl) }}?redirect-url=expiredProjects">
                <i data-id="" class="fa fa-pencil editUser"></i>
            </a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No Result Found</td></tr>
    @endif
</tbody>