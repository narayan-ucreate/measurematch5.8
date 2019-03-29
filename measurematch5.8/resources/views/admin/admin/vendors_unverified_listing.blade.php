<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/unverifiedVendors?orderBy='.$orderBy.'&data-sort=name',[],$ssl)}}">Name</a></th>
        <th><a id="email" data="asc" href="{{url('admin/unverifiedVendors?orderBy='.$orderBy.'&data-sort=users.email',[],$ssl)}}">Email</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/unverifiedVendors?orderBy='.$orderBy.'&data-sort=b.company_name',[],$ssl)}}">Company Name</a> </th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @php
    $total = $result->total();
    @endphp
    @if(isset($total) && !empty($total))
    @foreach ($result as $user)

    <tr  class="make-clickable" data-url="{{ url('admin/viewUnverifiedVendor', $user->id, $ssl) }}">
        <td> {{ $user->full_name }} </td>
        <td> {{ $user->email }} </td>
        <td> {{ $user['buyer_profile']['company_name'] }} </td>

        <td><a  title="View" href="{{ url('admin/viewUnverifiedVendor',$user->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> | <a  title="Block" href="javascript:void(0);"><i data-id="{{$user->id}}" class="fa fa-ban blockUser"></i></a> | <a  title="Resend Email" href="javascript:void(0);"><i data-id="{{$user->id}}" class="fa fa-envelope fa-1 Resendemail"></i></a>
        </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>