<thead>
    <tr>
        <th width="25%"><a id="name" data="asc" href="{{url('admin/pendingservicepackages?orderBy='.$orderBy.'&data-sort=u.name',[],$ssl)}}">Expert Name</a></th>
        <th width="20%"><a id="email" data="asc" href="{{url('admin/pendingservicepackages?orderBy='.$orderBy.'&data-sort=u.email',[],$ssl)}}">Email</a></th>
        <th width="30%"><a id="company_name" data="asc" href="{{url('admin/pendingservicepackages?orderBy='.$orderBy.'&data-sort=service_packages.name',[],$ssl)}}">Service Package Name</a> </th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/pendingservicepackages?orderBy='.$orderBy.'&data-sort=service_packages.created_at',[],$ssl)}}">Date Created</a></th>
        <th width="10%">Action</th>
    </tr>
</thead>
<tbody>

    @if($pending_service_package_count)
    @foreach ($result as $service_package)
    @php $user=$service_package['userDetails']; @endphp
    <tr  class="make-clickable" data-url="{{ url('admin/pendingservicepackage', $service_package->package_id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ ucwords($user['name']) }} {{ ucwords($user['last_name']) }} </td>
        <td class="email-column"> {{ $user['email'] }} </td>
        <td> {{ $service_package['package_name'] }} </td>

        <td> {{ date('d-m-Y',strtotime($service_package['package_date'])) }} </td>

        <td><a  title="View" href="{{ url('admin/pendingservicepackage',$service_package['package_id'],$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
            | <a  title="Edit" href="{{ url('admin/editservicepackage?approved=false',$service_package['package_id'],$ssl) }}"><i data-id="" class="fa fa-pencil editUser"></i></a></td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">You currently have no packages to approve</td></tr>
    @endif
</tbody>