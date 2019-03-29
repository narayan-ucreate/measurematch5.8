@if(_count($past_matches))
@php $count=0;@endphp
@foreach($past_matches as $key=>$expert)
@php $count++;
$full_name = getFullName($expert['expert']);
@endphp
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 expert-widget">
    <div class="row widget-white-box">
        <span style="background-image:url({{ url($expert['expert']['user_profile']['profile_picture'])}}" class="expert-profile-pic"></span>                                            
        <div class="expert-info-block">
            <h4>{{$full_name}}</h4>
            <span class="expert-name">@if(strlen($expert['expert']['user_profile']['describe'])>45){{substr($expert['expert']['user_profile']['describe'],0,45).'...'}}@else{{$expert['expert']['user_profile']['describe']}}@endif</span>
            <span class="expert-location">@if(strlen($expert['expert']['user_profile']['current_city'])>25){{substr($expert['expert']['user_profile']['current_city'],0,25).'...'}}@else{{$expert['expert']['user_profile']['current_city']}}@endif</span>
        </div>
        <a href="{{url('buyer/expert-profile/' . $expert['user_id'], [], $ssl)}}" class="view-profile-btn">View Profile</a>
    </div>
</div>
@if($count==3)
<div class="clearfix"></div>
@endif
@endforeach
<div class="pagination-default-theme past_experts_pagination">{{ $past_matches->links() }}</div>
@else
<h6>No past matches available</h6>
@endif
<script>
    $(function () {
        if ($(".past_experts_pagination ul.pagination li:first").hasClass('disabled')) {
            $('.past_experts_pagination ul.pagination li span:first').text('<< Previous');
        } else {
            $('.past_experts_pagination ul.pagination li a:first').text('<< Previous');
            $('.past_experts_pagination ul.pagination li:first').addClass('active-li-pagination');
        }

        if ($('.past_experts_pagination ul.pagination li a:last').attr('rel') == 'next') {
            $('.past_experts_pagination ul.pagination li a:last').text('Next >>');
            $('.past_experts_pagination ul.pagination li:last').addClass('active-li-pagination');
        } else {
            $('.past_experts_pagination ul.pagination li span:last').text('Next >>');
        }
    })
</script>