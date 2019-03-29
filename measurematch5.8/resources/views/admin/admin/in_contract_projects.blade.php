<thead>
    <tr>

        <th width="200"><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=buyer_profile.company_name',[],$ssl)}}">
                Client Company
            </a>
        </th>
        <th><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=experts.name',[],$ssl)}}">
                Expert
            </a>
        </th>
        <th><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=post_jobs.job_title',[],$ssl)}}">
                Project Name
            </a>
        </th>
        <th><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=contracts.job_start_date',[],$ssl)}}">
                Contract Start
            </a>
        </th>
        <th><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=contracts.job_end_date',[],$ssl)}}">
                Contract End
            </a>
        </th>
        <th><a data="asc" href="{{url('admin/inContractProjects?orderBy='
                    .$orderBy.'&data-sort=post_jobs.rate',[],$ssl)}}">
                Value
            </a>
        </th>
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
    <tr class="make-clickable" data-url="{{ url('admin/project', $job['project_id'], $ssl) }}">
        <td width="200"> {{ $job['company_name']}}</td>
        <td> {{ $job['name'] }} {{ $job['last_name'] }} </td>
        <td> {{ $job['job_title'] }}</td>
        <td width="120"> {{ date('d-m-Y',strtotime($job['job_start_date'])) }} </td>
        <td width="120"> {{ date('d-m-Y',strtotime($job['job_end_date'])) }} </td>
        <td> {{ $project_budget }} </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>