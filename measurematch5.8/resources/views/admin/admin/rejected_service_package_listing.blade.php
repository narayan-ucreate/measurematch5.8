<thead>
    <tr>
        <th width="25%"><a id="name" data="asc" href="{{url('admin/rejectedservicepackages?orderBy='.$orderBy.'&data-sort=u.name',[],$ssl)}}">Expert Name</a></th>
        <th width="25%"><a id="email" data="asc" href="{{url('admin/rejectedservicepackages?orderBy='.$orderBy.'&data-sort=u.email',[],$ssl)}}">Email</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/rejectedservicepackages?orderBy='.$orderBy.'&data-sort=service_packages.name',[],$ssl)}}">Service Package Name</a></th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/rejectedservicepackages?orderBy='.$orderBy.'&data-sort=service_packages.created_at',[],$ssl)}}">Date Created</a></th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(_count($service_packages))
    @foreach ($service_packages as $service_package)
    @php $user=$service_package['userDetails']; @endphp
    <tr  class="make-clickable" data-url="{{ url('admin/rejectedservicepackage', $service_package->package_id, $ssl) }}">
        <td> {{ ucwords($user['name']) }} {{ ucwords($user['last_name']) }} </td>
        <td> {{ $user['email'] }} </td>
        <td> {{ $service_package['package_name'] }} </td>

        <td> {{ date('d-m-Y',strtotime($service_package['package_date'])) }} </td>
        <td>
            <a title="View" href="{{ url('admin/rejectedservicepackage',$service_package->package_id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
        </td>                               
    </tr>
    @endforeach 
    @else
    <tr><td colspan="5">No rejected packages available</td></tr>
    @endif
</tbody>