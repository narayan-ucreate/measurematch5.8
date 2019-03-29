<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/pendingBuyers?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/pendingBuyers?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/pendingBuyers?orderBy='.$orderBy.'&data-sort=b.company_name',[],$ssl)}}">Company Name</a> </th>
        <th><a id="date_register" data="asc" href="{{url('admin/pendingBuyers?orderBy='.$orderBy.'&data-sort=users.created_at',[],$ssl)}}">Date Joined</a></th>
        <th>Action</th>
    </tr>
</thead>
<tbody>


    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr class="make-clickable" data-url="{{ url('admin/buyerPendingView', $user->id, $ssl) }}">
        <!-- Task Name -->
        <td> {{ $user->full_name }} </td>
        <td> {{ $user->email }} </td>

        <td> @if(isset($user['buyer_profile']) && !empty($user['buyer_profile']))
            {{ $user['buyer_profile']['company_name'] }} 
            @else {{'-'}}
            @endif 
        </td>
        <td> {{ date('d-m-Y',strtotime($user->created_at)) }} </td>
        <td><a  title="View" href="{{ url('admin/buyerPendingView',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>