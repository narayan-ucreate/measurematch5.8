<thead>
    <tr>
        <th width="20%"><a id="name" data="asc" href="{{url('admin/servicepackages?orderBy='.$orderBy.'&data-sort=u.name',[],$ssl)}}">Expert Name</a></th>
        <th width="25%"><a id="company_name" data="asc" href="{{url('admin/servicepackages?orderBy='.$orderBy.'&data-sort=service_packages.name',[],$ssl)}}">Service Package Name</a></th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/servicepackages?orderBy='.$orderBy.'&data-sort=service_packages.created_at',[],$ssl)}}">Date Created</a></th>
        <th width="10%"><a id="date_register" data="asc" href="{{url('admin/servicepackages?orderBy='.$orderBy.'&data-sort=service_packages.is_hidden',[],$ssl)}}">Status</a></th>
        <th width="10%"><a id="date_register" data="asc" href="{{url('admin/servicepackages?orderBy='.$orderBy.'&data-sort=communication_count',[],$ssl)}}">No. of EOIs</a></th>
        <th width="10%">Action</th>
    </tr>
</thead>
<tbody>
    @if(_count($service_packages))
    @foreach ($service_packages as $service_package)
    @php $user=$service_package['userDetails']; @endphp
    <tr  class="make-clickable" data-url="{{ url('admin/approvedservicepackage', $service_package->package_id, $ssl) }}">
        <td> {{ ucwords($user['name']) }} {{ ucwords($user['last_name']) }} </td>
        <td> {{ $service_package['package_name'] }} </td>

        <td> {{ date('d-m-Y',strtotime($service_package['package_date'])) }} </td>
        <td> @if($service_package['is_hidden']){{"Hidden"}}@else{{"Live"}}@endif</td>
        <td> {{$service_package['communication_count']}}</td>
        <td class="published-project-action">
            <a title="View" href="{{ url('admin/approvedservicepackage',$service_package->package_id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> 
            <a title="Edit" href="{{ url('admin/editservicepackage?approved=true',$service_package->package_id,$ssl) }}">
                <i data-id="{{$service_package->id}}" class="fa fa-pencil editUser"></i></a>
        </td>                               
    </tr>
    @endforeach 
    @else
    <tr><td colspan="6">No approved packages available</td></tr>
    @endif
</tbody>