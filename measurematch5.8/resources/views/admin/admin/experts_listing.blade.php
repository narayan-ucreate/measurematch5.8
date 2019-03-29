<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/expertListing?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/expertListing?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="expert_type" data="asc" href="{{url('admin/expertListing?orderBy='.$orderBy.'&data-sort=up.expert_type',[],$ssl)}}">Type</a></th>
        <th><a id="describe" data="asc" href="{{url('admin/expertListing?orderBy='.$orderBy.'&data-sort=up.describe',[],$ssl)}}">Role Description</a></th>
        <th>Vendor Invite</th>
        <th width="12%"><a id="date_register" data="asc" href="{{url('admin/expertListing?orderBy='.$orderBy.'',[],$ssl)}}">Date Joined</a></th>
        <th width="11%">Action</th>
    </tr>
</thead>
<tbody>                             
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)

    <tr   class="make-clickable" data-url="{{ url('admin/expertView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name}} 
        </td>
        <td class="email-column"> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> </td>
        <td>{{$user['user_profile']->expert_type}}</td>
        <td>
            @if(isset( $user['user_profile']->describe) && !empty($user['user_profile']->describe))
            {{ $user['user_profile']->describe}}
            @else
            {{ '-' }}
            @endif
        </td>
        <td>{{(_count($user['serviceHubAssociatedExpert'])) ? fetchServiceHubNamesCommaSeparated($user['serviceHubAssociatedExpert']->toArray()) : 'NA'}}</td>
        <td> {{date('d-m-Y',strtotime($user->created_at))}} </td>
        <td>
            <a  title="View" href="{{ url('admin/expertView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
            | <a  title="Edit" href="{{ url('admin/expertEdit',$user->id,$ssl) }}"><i data-id="{{$user->id}}" class="fa fa-pencil editUser"></i></a> | 
            <a  title="Block" href="javascript:void(0);">
                <i data-id="{{$user->id}}" class="fa fa-ban expertblockUser"></i></a> | 
            <a  title="Service Packages" href="{{ url('admin/expert/servicepackages',$user->id,$ssl)}}"><img data-id="{{$user->id}}" src="../images/admin-service-packages.svg" />
            </a>

        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="6">No Result Found</td></tr>
    @endif
</tbody>