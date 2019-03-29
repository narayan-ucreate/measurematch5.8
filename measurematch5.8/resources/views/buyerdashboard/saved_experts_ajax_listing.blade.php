@if(_count($saved_experts))
@php $count=0;@endphp
@foreach($saved_experts as $key=>$expert)
@php $count++;
$full_name = getFullName($expert['expert']);
@endphp
<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 expert-widget">
    <div class="row widget-white-box">
        <span style="background-image:url({{ url($expert['expert']['user_profile']['profile_picture'])}}" class="expert-profile-pic"></span>                                            
        <div class="expert-info-block">
            <span id="error_{{$expert['user_id']}}" class="validation_error"></span>
            <a href="javascript:void(0)" class="saved-expert saved-icon save_remove_expert @if(checkIfExpertSavedByBuyer($expert['expert_id'], Auth::user()->id, 'project_progress')>0) selected-expert @endif" expert_id='{{$expert['expert_id']}}'></a>
            <h4>{{$full_name}}</h4>
            <span class="expert-name">@if(strlen($expert['expert']['user_profile']['describe'])>57){{substr($expert['expert']['user_profile']['describe'],0,57).'...'}}@else{{$expert['expert']['user_profile']['describe']}}@endif</span>
            <span class="expert-location">@if(strlen($expert['expert']['user_profile']['current_city'])>27){{substr($expert['expert']['user_profile']['current_city'],0,27).'...'}}@else{{$expert['expert']['user_profile']['current_city']}}@endif</span>
        </div>
        <a href="{{url('buyer/expert-profile/' . $expert['expert_id'], [], $ssl)}}" class="view-profile-btn">View Profile</a>
    </div>
</div>
@if($count==3)
<div class="clearfix"></div>
@endif
@endforeach
<div class="pagination-default-theme saved_experts_pagination">{{ $saved_experts->links() }}</div>
@else
<h6>No experts have saved yet</h6>
@endif
<script>
    $(function () {
        if ($(".saved_experts_pagination ul.pagination li:first").hasClass('disabled')) {
            $('.saved_experts_pagination ul.pagination li span:first').text('<< Previous');
        } else {
            $('.saved_experts_pagination ul.pagination li a:first').text('<< Previous');
            $('.saved_experts_pagination ul.pagination li:first').addClass('active-li-pagination');
        }

        if ($('.saved_experts_pagination ul.pagination li a:last').attr('rel') == 'next') {
            $('.saved_experts_pagination ul.pagination li a:last').text('Next >>');
            $('.saved_experts_pagination ul.pagination li:last').addClass('active-li-pagination');
        } else {
            $('.saved_experts_pagination ul.pagination li span:last').text('Next >>');
        }
    });
</script>