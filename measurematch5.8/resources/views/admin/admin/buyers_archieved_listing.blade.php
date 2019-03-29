<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/archivedBuyersListing?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/archivedBuyersListing?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th>Action</th>
    </tr>
</thead>
<tbody>

    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr   class="make-clickable" data-url="{{ url('admin/buyerArchievedView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name }} </td>
        <td>
            @if($user->user_type_id=='1')
            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> 
            @else
            {{$user->email}}
            @endif
        </td>
        <td>
            @if($user->user_type_id=='2')
            <a  title="View" href="{{ url('admin/buyerArchievedView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
            @else
            <a  title="View" href="{{ url('admin/expertArchievedView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
            @endif
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>