<thead>
    <tr>
        <th><a id="name" data="asc" href="{{url('admin/liveHubs',[],$ssl)}}">Vendor</a></th>
        <th><a id="email" data="asc" href="{{url('admin/liveHubs',[],$ssl)}}">Vendor Hub Name</a></th>
        <th><a id="company_name" data="asc" href="{{url('admin/liveHubs',[],$ssl)}}">Email</a> </th>
        <th><a id="date_register" data="asc" href="{{url('admin/liveHubs',[],$ssl)}}">Verified Experts</a></th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(!empty($total))
	    @foreach ($result as $hub)
		    <tr class="make-clickable" data-url="{{ url('admin/hub', $hub->id, $ssl) }}">
		        <td> {{ ucwords($hub->vendor_profile->company_name) }} </td>
		        <td> {{ $hub->name }} </td>
		        <td> {{ $hub->vendor_user->email }} </td>
				<td> {{ $hub->approve_experts_count ?? '0' }} </td>
		        <td><a  title="View" href="{{ url('admin/hub', $hub->id, $ssl) }}"><i class="fa fa-fw fa-eye"></i></a>
		        </td>
		    </tr>
	    @endforeach
    @else
    	<tr><td colspan="5">No Result Found</td></tr>
    @endif
</tbody>