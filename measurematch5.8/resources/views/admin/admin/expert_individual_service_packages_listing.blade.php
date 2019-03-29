<?php
$expert_id = Request::segment(3);
$orderBy = orderBy($_REQUEST);
?>
<thead>
    <tr>
        <th><a id="company_name" data="asc" href="{{url('admin/expert/servicepackages/'.$expert_id.'?orderBy='.$orderBy,[],$ssl)}}">Service Package Name</a></th>                                    
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @if(_count($service_packages))
    @foreach ($service_packages as $service_package)
    <tr>
        <td>{{$service_package->name}}</td>    
        <td class="published-project-action">
            <a title="View" href="{{ url('admin/publishedservicepackage',$service_package->id,$ssl) }}"><i class="fa fa-fw fa-eye"></i></a> 
            <a title="Edit" href="{{ url('admin/editservicepackage',$service_package->id,$ssl) }}">
                <i data-id="{{$service_package->id}}" class="fa fa-pencil editUser"></i></a>
        </td>                               
    </tr>
    @endforeach 
    @else
    <tr><td colspan="5">This expert has no packages</td></tr>
    @endif
</tbody>