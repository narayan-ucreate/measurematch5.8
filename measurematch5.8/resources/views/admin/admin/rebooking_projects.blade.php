<thead>
    <tr>
        <th width="200">
            <a data="asc" href="{{url('admin/rebookingProjects?orderBy='.$orderBy.'&data-sort=buyer_profile.company_name',[],$ssl)}}"> Client Company </a>
        </th>
        <th>
            <a data="asc" href="{{url('admin/rebookingProjects?orderBy='.$orderBy.'&data-sort=experts.name',[],$ssl)}}"> Expert Name </a>
        </th>
        <th>
            <a data="asc" href="{{url('admin/rebookingProjects?orderBy='.$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}"> Project Name </a>
        </th>
        <th>
            <a data="asc" href="{{url('admin/rebookingProjects?orderBy='.$orderBy.'&data-sort=contracts.job_start_date',[],$ssl)}}"> Date Posted </a>
        </th>
        <th width="15%"> Action </th>
    </tr>
</thead>
<tbody>
@if(isset($total) && !empty($total))
    @foreach ($result as $job)
        @php
            $currency = convertToCurrencySymbol($job['currency']);
            $user = $job['user'];
            $project_budget = ($job['rate']) ? $currency.number_format($job['rate']): "Negotiable (".$currency.")";
        @endphp
        <tr class="make-clickable" data-url="{{ url('admin/project?source=rebook', $job['project_id'], $ssl) }}">
            <td width="200"> {{ $job['company_name']}}</td>
            <td> {{ $job['name'] }} {{ $job['last_name'] }} </td>
            <td> {{ $job['job_title'] }}</td>
            <td width="120"> {{ date('d-m-Y',strtotime($job['publish_date'])) }} </td>
            <td width="50">
                <a title="View" href="{{ url('admin/project',$job['project_id'],$ssl) }}?redirect-url=rebookingProjects&source=rebook">
                    <i class="fa fa-fw fa-eye"></i>
                </a> |
                <a title="Edit" href="{{ url('admin/project/edit/'.$job['project_id'],[],$ssl) }}?redirect-url=rebookingProjects&source=rebook">
                    <i data-id="" class="fa fa-pencil editUser"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="5"> No Results Found </td></tr>
@endif
</tbody>