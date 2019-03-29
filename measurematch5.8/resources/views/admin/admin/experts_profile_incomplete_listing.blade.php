<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/incompleteProfileExperts?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/incompleteProfileExperts?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="expert_type" data="asc" href="{{url('admin/incompleteProfileExperts?orderBy='.$orderBy.'&data-sort=up.expert_type',[],$ssl)}}">Type</a></th>
        <th><a id="describe" data="asc" href="{{url('admin/incompleteProfileExperts?orderBy='.$orderBy.'&data-sort=up.describe',[],$ssl)}}">Role Description</a></th>
        <th>Vendor Invite</th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/incompleteProfileExperts?orderBy='.$orderBy.'',[],$ssl)}}">Date Joined</a></th>                                 
        <th>Action</th>      
    </tr>
</thead>
<tbody>                             
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr  class="make-clickable" data-url="{{ url('admin/expertWithIncompleteProfileView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name }} </td>
        <td> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> </td>
        <td>{{$user['user_profile']->expert_type}}</td>
        <td>
            @if(isset( $user['user_profile']->describe) && !empty($user['user_profile']->describe))
            {{ $user['user_profile']->describe}}
            @else
            {{ '-' }}
            @endif </td>
        <td>{{(_count($user['serviceHubAssociatedExpert'])) ? fetchServiceHubNamesCommaSeparated($user['serviceHubAssociatedExpert']->toArray()) : 'NA'}}</td>
        <td> {{date('d-m-Y',strtotime($user->created_at))}} </td>
        <td><a  title="View" href="{{ url('admin/expertWithIncompleteProfileView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a></td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="6">No Result Found</td></tr>
    @endif
</tbody>