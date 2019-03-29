@if ($experts_result)  
@if(!buyerAuth())
<section class="webflow-result-section active-results withoutlogin">
    @else
    <section class="webflow-result-section active-results">
     @endif
    @if(!buyerAuth())
    <div class="container">
        @endif
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>{{$company_name}}'s </h2>
                <h2> Technographic Match&trade;<span class="tm-beta">BETA</span></h2>
            </div>
            <div class="col-md-6 col-md-offset-3 text-center">
                <h4>The fastest way to access systems and data expertise.</h4>
            </div>
        </div>
        <div class="row margin-top-10 text-center">
            <div class="col-lg-12 text-center">
                <div class="total-result">
                    <ul>
                        <li> {{ _count($expert_ids) }} <span>Experts</span></li>
                        <li> {{ $countries_count }} <span>Countries</span></li>
                        <li> {{ _count($experts_result) }} <span>Systems</span></li>
                        <li> {{ _count($experts_result) }} <span>Skills</span></li>
                    </ul>
                </div>
            </div>
            @if(buyerAuth())
                <div class="col-sm-12 col-md-6 col-md-offset-3 text-center margin-top-25 buyer-login-tmsearch">
                    <form class="buyer-techno-search">
                        <input class="form-control search-company" placeholder="Start typing a company name…" value="{{$domain}}" name=""  type="text">
                        <a class="cross clear-search @if ($domain == '') hide @endif" href="#">
                            <img class="img-responsive" src="{{ url('images/cross-blue.svg',[],$ssl) }}" alt="MeasureMatch" />
                        </a>
                    </form>
                    <div class="noresult-content-suggestions">
                        <p>
                            Or try some popular brand searches:
                            <a href="technographic-info?name=ASOS&logo=https://logo.clearbit.com/asos.com&domain=asos.com">ASOS</a>,
                            <a href="technographic-info?name=Deliveroo&logo=https://logo.clearbit.com/deliveroo.co.uk&domain=deliveroo.co.uk">Deliveroo</a>,
                            <a href="technographic-info?name=John Lewis&logo=https://logo.clearbit.com/johnlewis.com&domain=johnlewis.com">JohnLewis</a>
                        </p>
                    </div>
                </div>
            @else
                <div class="col-sm-12 col-md-12 text-center margin-top-42 buyer-login-tmsearch">
                    <div class="noresult-content-suggestions text-center font-16">
                        Try these popular brands:
                        <a class="font-bold" href="technographic-info?name=Walmart&logo=https://logo.clearbit.com/walmart.com&domain=walmart.com">Walmart</a>,
                        <a class="font-bold" href="technographic-info?name=Target&logo=https://logo.clearbit.com/target.com&domain=target.com">Target</a>,
                        <a class="font-bold" href="technographic-info?name=John Lewis&logo=https://logo.clearbit.com/johnlewis.com&domain=johnlewis.com">John Lewis</a>,
                        <a class="font-bold" href="technographic-info?name=Best%20Buy&logo=https://logo.clearbit.com/bestbuy.com&domain=bestbuy.com">BestBuy</a>,
                        <a class="font-bold" href="technographic-info?name=McDonald%27s&logo=https://logo.clearbit.com/mcdonalds.com&domain=mcdonalds.com">McDonald's</a>,
                        <a class="font-bold" href="technographic-info?name=Nike&logo=https://logo.clearbit.com/nike.com&domain=nike.com">Nike</a>
                    </div>
                    <div class="noresult-content-suggestions text-center font-14">
                        Want to try more brands? <a class="font-bold" href="{{ url('signup?buyer',[],$ssl) }}"> Sign up now </a>
                    </div>
                </div>
            @endif
        </div>
        @if(!buyerAuth())
    </div>
    @endif
</section>

<section class="similar-search-section">
    @if(!buyerAuth())
    <div class="container">
        @endif
        <div class="row">
            <div class="col-lg-12">
                <h3>MeasureMatch Experts & Skills aligned to Technographic profile of <a href="//{{$domain}}" target="_blank">{{$domain}}:</a></h3>
            </div>
        </div>
        <div class="row">
        @php  $segment_system_skills=[];
        $pending_account_approval=(Auth::check() && !Auth::user()->admin_approval_status)?'pending-account-approval':'display-expert-overlay';
        @endphp
        @foreach($experts_result as $skill_name => $expert_data)
            @php
            $experts_count = _count($expert_data);
            if ($experts_count < config('constants.TM_RESULTS_MINIMUM_EXPERTS_COUNT')){
                continue;
            }
            $more_experts_count = $experts_count-config('constants.NO_OF_USER_DISPLAY_ON_RESULT');
            $image_url = 0;
            if(array_key_exists(strtolower($skill_name), $skill_logos) && !empty($skill_logos[strtolower($skill_name)]))
                $image_url = $skill_logos[strtolower($skill_name)];
            @endphp
            @if ($experts_count >= config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                @php $segment_system_skills[]=$skill_name; @endphp
                <div class="col-lg-3">
                    <a href="javascript:void(0)" class="{{$pending_account_approval}}" buyer-logged-in='{{buyerAuth()}}'>
                        <div class="box tex-center">
                            <div class="box-body">
                                @if($image_url !== 0)
                                    <img class="icon-logo" width="40" src="{{$image_url}}" alt="" />
                                @endif
                                <p class="gilroyregular-semibold font-16" @if($image_url === 0) style="padding: 28px 0;" @endif>
                                    {{ getTruncatedContent($skill_name, config('constants.TRUNCATION_LIMIT_TECHNOGRAPHIC')) }}
                                </p>
                                <span class="skill-name hide">{{ucfirst($skill_name)}}</span>
                                <div class="expert-view">
                                @foreach($expert_data as $expert)
                                    @if ($loop->iteration <= config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                                        <span class="expert-profile-pic" style="background-image: url({{$profile_pictures[$expert]}});"></span>
                                    @endif
                                @endforeach
                                @if ($experts_count > config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
                                    <p class="view-expressions"> +{{$more_experts_count}} @if($more_experts_count>1) Experts @else Expert @endif</p>
                                @endif
                                </div>
                            </div>
                            <div class="box-footer">
                                View @if($experts_count>1) Experts @else Expert @endif <img src="{{ url('images/arrow.svg',[],$ssl) }}" alt="" />
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
        @php if($domain){ submitTechnographicTracking($segment_system_skills, $company_name, $domain); } @endphp
       @if(!buyerAuth())
        <div class="col-lg-3">
            <div class="box tex-center">
                <div class="box-body moreexpert-tm">
                    <p class="gilroyregular-semibold font-16">Want to see more MeasureMatch Experts?</p>
                    <a class="gilroyregular-semibold font-16" href="{{ url('signup?buyer',[],$ssl) }}">Create a free account</a>
                </div>
            </div>
        </div>
       @endif
        </div>
    @if(!buyerAuth())
</div>
        @endif
</section>
@else
<section class="noresult-section">
    @if(!buyerAuth())
    <div class="container">
        @endif
        <div class="row">
            <div class="col-lg-12 text-center">
                <img  src="{{ url('images/search-again.svg',[],$ssl) }}" alt="" />
                <h2 class="gilroyregular-semibold">Darn.</h2>
                <form class="noresult-form">
                    <label>Doh! Our hamsters need feeding. Try again:</label>
                    <input class="form-control search-company" placeholder="Start typing a company name…" value="" name="" type="text">
                </form>
                <div class="noresult-content">
                    <p>Or take a look at some popular brand searches:</p>
                    <a href="technographic-info?name=ASOS&logo=https://logo.clearbit.com/asos.com&domain=asos.com">ASOS.com,</a>
                    <a href="technographic-info?name=Deliveroo&logo=https://logo.clearbit.com/deliveroo.co.uk&domain=deliveroo.co.uk">Deliveroo.co.uk,</a>
                    <a href="technographic-info?name=John Lewis&logo=https://logo.clearbit.com/johnlewis.com&domain=johnlewis.com">JohnLewis.com</a>
                </div>
            </div>
        </div>
        @if(!buyerAuth())
    </div>
    @endif
</section>
@endif
@if (_count($popular_skills))
<section class="similar-search-section">
    @if(!buyerAuth())
    <div class="container">
        @endif
        <div class="row">
            <div class="col-lg-12">
                <h3>Customers with similar searches also viewed...</h3>
            </div>
        </div>
        <div class="row" id="popular_skill_section">
            @include('technographic.partial_more_skill')
        </div>
        @if (_count($popular_skills) > config('constants.NO_OF_USER_DISPLAY_ON_RESULT'))
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" value="{{$found_skills_ids_json_encoded}}" id="hidden_popular_skills">
                <a href="#" class="showmore" id="show_more_popular_skill"><i class="fa fa-plus" aria-hidden="true"></i> Show more</a>
            </div>
        </div>
        @endif
        @if(!buyerAuth())
    </div>
     @endif
</section>
@endif
