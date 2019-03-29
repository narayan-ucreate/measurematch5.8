@php
    $pending_account_approval=(Auth::check() && !Auth::user()->admin_approval_status)?'pending-account-approval':'display-expert-overlay';
@endphp
@foreach($popular_skills as $skill)

    <div class="col-lg-3 ">
        <a href="javascript:void(0)" class="{{$pending_account_approval}}" buyer-logged-in='{{buyerAuth()}}'>
            <div class="box tex-center">
                <div class="box-body">
                    @php
                    $expected_image_name = strtolower(str_replace(' ', '-', $skill->skill_name));
                    $image_url = url('images/skills-icons/unknown-logo-icon.png',[],$ssl);
                    if($skill->logo_url != '')
                        $image_url = $skill->logo_url;
                    @endphp
                    <img class="icon-logo" src="{{ $image_url }}" alt=""  width="40" />
                    <p class="gilroyregular-semibold font-16">{{getTruncatedContent($skill->skill_name, config('constants.TRUNCATION_LIMIT_TECHNOGRAPHIC'))}}</p>
                    <span class="skill-name hide">{{ucfirst($skill->skill_name)}}</span>

                    <div class="expert-view">
                        @php
                            $images = explode(',', $skill->images);
                            $more_experts_count = $skill->total_users-config('constants.NO_OF_USER_DISPLAY_ON_RESULT');
                        @endphp
                        @if (_count($images) > config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                            @foreach($images as $image)
                                @if ($loop->iteration <= config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                                    <span class="expert-profile-pic" style="background-image: url({{$image}});"></span>
                                @endif
                            @endforeach
                        @endif
                        @if ($skill->total_users > config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                            <p class="view-expressions">+{{$more_experts_count}} @if($more_experts_count>1) Experts @else Expert @endif</p>
                        @endif

                    </div>
                </div>
                <div class="box-footer">
                    View Experts <img src="{{ url('images/arrow.svg',[],$ssl) }}" alt="" />
                </div>
            </div>
        </a>
    </div>

@endforeach
