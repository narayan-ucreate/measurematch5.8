<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/notverifiedexperts?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/notverifiedexperts?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="expert_type" data="asc" href="{{url('admin/notverifiedexperts?orderBy='.$orderBy.'&data-sort=up.expert_type',[],$ssl)}}">Type</a></th>
        <th><a id="describe" data="asc" href="{{url('admin/notverifiedexperts?orderBy='.$orderBy.'&data-sort=up.describe',[],$ssl)}}">Role Description</a></th>
        <th>Vendor Invite</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @php
    $total = $result->total();
    @endphp
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
        <tr  class="make-clickable" data-url="{{ url('admin/notverifiedexpertView', $user->id, $ssl) }}">
            <!-- Task Name -->
            <td> {{ $user->full_name }} </td>
            <td> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> </td>
            <td>{{$user['user_profile']->expert_type}}</td>
            <td>
            @if(isset( $user['user_profile']->describe) && !empty($user['user_profile']->describe))
                {{ $user['user_profile']->describe}}
            @else
                {{ '-' }}
            @endif
            </td>
            <td>
            @if($user['user_profile']->expert_type === config('constants.EXPERT_TYPE_SIDE_HUSTLER'))
                {{ 'NA' }}
            @else
                {{(_count($user['serviceHubAssociatedExpert'])) ? fetchServiceHubNamesCommaSeparated($user['serviceHubAssociatedExpert']->toArray()) : 'NA'}}
            @endif
            </td>
            <td>
                <a title="View" href="{{ url('admin/notverifiedexpertView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> |
                <a title="Block" href="javascript:void(0);"><i data-id="{{$user->id}}" class="fa fa-ban blockUser"></i></a> |
                <a title="Resend Email" href="javascript:void(0);"><i data-id="{{$user->id}}" class="fa fa-envelope fa-1 Resendemail"></i></a>
            </td>
        </tr>
    @endforeach
    @else
        <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>