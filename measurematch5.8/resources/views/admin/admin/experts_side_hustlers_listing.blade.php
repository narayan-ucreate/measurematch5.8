<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/sideHustlersExperts?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/sideHustlersExperts?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th width="15%"><a id="date_register" data="asc" href="{{url('admin/sideHustlersExperts?orderBy='.$orderBy.'',[],$ssl)}}">Date Joined</a></th>
        <th>Action</th>      
    </tr>
</thead>
<tbody>                             
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr  class="make-clickable" data-url="{{ url('admin/sideHustlerView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name }} </td>
        <td> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> </td>
        <td> {{date('d-m-Y',strtotime($user->created_at))}} </td>
        <td><a  title="View" href="{{ url('admin/sideHustlerView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a></td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="6">No Result Found</td></tr>
    @endif
</tbody>