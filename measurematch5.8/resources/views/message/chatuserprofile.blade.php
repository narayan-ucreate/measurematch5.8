    <div class="expert_profile_inner_panel expert-profile-pannel">
        <div class="profile-display-section">
            <input type="hidden" id="buyer_company_name" value="{{isset($user_profile['company_name']) ? explode(' ', $user_profile['company_name'])[0] : ''}}">
            @if(!empty($user_type) && !empty($user_profile))
            @php
            if (isset($user_profile['profile_picture']) && !empty($user_profile['profile_picture'])) {
            $img_src = $user_profile['profile_picture'];
            } else {
            $img_src = "../images/avatar_updated.svg";
            }
            $username = userName($user_profile['user_id'], 1);
            @endphp
            @if($user_type==1)
            <div id="show-buyer-profile">
                <a target="_blank"  class="stop-link-propagation buyerprofile-link"
                   {{ $project_detail['hide_company_name'] ? '' : 'href=' . url('viewbuyerprofile?id='.$user_profile['user_id'].'&breadcrumb-page=expertMessaging') }}>
                    <span class="seller-pic expert-left-img-msg">
                        <div class="profilepicture" style="background-image: url({{$img_src}})"></div>
                    </span>
                    <span class="seller-info">
                        <span class="seller-name">{{$username}}</span>

                    </span>
                </a>
                @if($project_detail['type']=='project')

               <a class="buyer-project-link" target="_blank" href="{{url('/projects_view?sellerid='.$project_detail['id'],[],getenv('APP_SSL'))}}"> <span class="seller-name expert-type">View {{explode(' ', $username)[0]}}'s Project</span></a>
                @else
                    <a class="buyer-project-link" target="_blank" href="{{url('/servicepackage/detail/'.$project_detail['id'],[],getenv('APP_SSL'))}}"> <span class="seller-name expert-type">View Service Package</span></a>
                @endif
            </div>
            @else
            <div id="show-expert-profile">
                <a target="_blank" class="msgUnLst stop-link-propagation" href="{{url('/buyer/expert-profile/'.$user_profile['user_id'],[],getenv('APP_SSL'))}}">
                    <span class="seller-pic expert-left-img-msg">
                        <div class="profilepicture" style="background-image: url('{{$img_src}}')"></div>
                    </span>
                    <span class="seller-info">
                        <span class="seller-name">{{$username}}</span>
                        <span class="seller-name expert-type">@if($user_profile['expert_type']=='Independent'){{'Independent Consultant'}} @else {{$user_profile['expert_type']}} @endif</span>

                    </span>
                 </a>
            </div>
            @endif
           @if(Auth::user()->user_type_id == config('constants.ADMIN'))
           <span class="profile-email font-14">   {{userInfo($user_profile['user_id'])[0]->email}}</span>
           @endif

            @endif
        </div>
        <div class="waiting-checklist-panel" id="show-user-contracts">
            @include('message.contracts')
        </div>
    </div>
</div>

