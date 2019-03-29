<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/buyerListing?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/buyerListing?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/buyerListing?orderBy='.$orderBy.'&data-sort=b.company_name',[],$ssl)}}">Company Name</a> </th>
        <th><a id="date_register" data="asc" href="{{url('admin/buyerListing?orderBy='.$orderBy.'&data-sort=users.created_at',[],$ssl)}}">Date Joined</a></th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)
    <tr  class="make-clickable" data-url="{{ url('admin/buyerView', $user->id, $ssl) }}">
        <td> {{ $user->full_name }} </td>
        <td class="email-column"> {{ $user->email }} </td>
        <td> {{ $user['buyer_profile']->company_name }} </td>
        <td> {{date('d-m-Y',strtotime($user->created_at))}} </td>
        <td>
            <a  title="View" href="{{ url('admin/buyerView',$user->id,$ssl) }}">
                <i class="fa fa-fw fa-eye"></i>
            </a> | <a  title="Edit" href="{{ url('admin/buyerEdit',$user->id,$ssl) }}">
                <i data-id="{{$user->id}}" class="fa fa-pencil editUser"></i>
            </a> |
            <a  title="Block" href="javascript:void(0);">
                <i data-id="{{$user->id}}" class="fa fa-ban blockUser"></i>
            </a> |
            <a  title="Projects" href="{{ url('admin/getProjects',$user->id,$ssl)}}">
                <i data-id="{{$user->id}}" class="fa fa-book "></i>
            </a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>