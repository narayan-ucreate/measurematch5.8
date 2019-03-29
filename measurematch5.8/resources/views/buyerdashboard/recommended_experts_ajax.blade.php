@if(_count($random_experts))
    @php $count=0;@endphp
    @foreach($random_experts as $key=>$expert)
      @php $count++;
          if(strlen($expert['active_expert']['name'])>17){
              $full_name=ucfirst(substr($expert['active_expert']['name'],0,17)).'...';
          }else{
              $full_name=ucfirst($expert['active_expert']['name']).' '.ucfirst(substr($expert['active_expert']['last_name'],0,1));
          }
      @endphp
          <div class="col-lg-4 col-md-4 col-sm-4 expert-widget">
              <div class="row widget-white-box">

                  <span style="background-image:url({{ url($expert['active_expert']['user_profile']['profile_picture'])}} " class="expert-profile-pic"></span>                                            

                  <div class="expert-info-block">
                      <span id="error_{{$expert['user_id']}}" class="validation_error"></span>
                      <a href="javascript:void(0)" class="saved-expert saved-icon save_remove_expert @if(checkIfExpertSavedByBuyer($expert['user_id'], Auth::user()->id, 'project_progress')>0) selected-expert @endif" expert_id='{{$expert['user_id']}}'></a>
                      <h4>{{$full_name}}</h4>
                      <span class="expert-name">@if(strlen($expert['active_expert']['user_profile']['describe'])>57){{substr($expert['active_expert']['user_profile']['describe'],0,57).'...'}}@else{{$expert['active_expert']['user_profile']['describe']}}@endif</span>
                      <span class="expert-location">@if(strlen($expert['active_expert']['user_profile']['current_city'])>27){{substr($expert['active_expert']['user_profile']['current_city'],0,27).'...'}}@else{{$expert['active_expert']['user_profile']['current_city']}}@endif</span>
                  </div>
                  <a href="{{url('buyer/expert-profile/' . $expert['user_id'], [], $ssl)}}" class="view-profile-btn">View Profile</a>
              </div>
          </div>
      @if($count==3)
        <div class="clearfix"></div>
      @endif
    @endforeach
@else
<h6>No experts found</h6>
@endif