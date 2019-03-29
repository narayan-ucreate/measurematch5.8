<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/archivedExpertsListing?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/archivedExpertsListing?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th>Vendor Invite</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>

    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr  class="make-clickable" data-url="{{ url('admin/expertArchievedView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name }} </td>
        <td>
            @if($user->user_type_id=='1')
            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> 
            @else
            {{$user->email}}
            @endif
        </td>
        <td>{{(_count($user['serviceHubAssociatedExpert'])) ? fetchServiceHubNamesCommaSeparated($user['serviceHubAssociatedExpert']->toArray()) : 'NA'}}</td>
        <td>
            <a  title="View" href="{{ url('admin/expertArchievedView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>