@if(buyerAuth())
    @php $layout = 'layouts.technographic_logged_in_buyer'; @endphp
@else
    @php $layout = 'layouts.technographic'; @endphp
@endif
@extends($layout)
@section('content')
<section class="loading-section" id="loading_section">
    <input type="hidden" value="{{$domain}}" id="domain_name">
    <input type="hidden" value="{{$name}}" id="company_name">
    <input type="hidden" value="{{$logo}}" id="logo">
    @if(!buyerAuth())
        <div class="container">
            @endif
            <div class="row">
                <div class="col-lg-12 text-center">
                        <div class="sk-circle tm-web-loader">
                            <div class="sk-circle1 sk-child"></div>
                            <div class="sk-circle2 sk-child"></div>
                            <div class="sk-circle3 sk-child"></div>
                            <div class="sk-circle4 sk-child"></div>
                            <div class="sk-circle5 sk-child"></div>
                            <div class="sk-circle6 sk-child"></div>
                            <div class="sk-circle7 sk-child"></div>
                            <div class="sk-circle8 sk-child"></div>
                            <div class="sk-circle9 sk-child"></div>
                            <div class="sk-circle10 sk-child"></div>
                            <div class="sk-circle11 sk-child"></div>
                            <div class="sk-circle12 sk-child"></div>
                        </div>
                    
                        <div class="loader-data tm-mobile-loader">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                        <div class="loading-data-web">
                            <h2>Loading your matches for {{$name}}...</h2>
                            <p>Sit tight. This could take a few seconds.</p>
                        </div>
                        <div class="loading-data-mobile">
                            <h2>Loading your matches...</h2>
                        </div>
                </div>
            </div>
            <div class="row">
                @if(!buyerAuth())
                    <div class="col-lg-10 col-lg-offset-1">
                @else
                    <div class="col-lg-12 tm-loading-withlogin">
                @endif
                    <div class="fartech-data">
                        <div class="far-data">
                            <img src="{{$logo}}" alt="" width="48" />
                            <h4>{{$name}}</h4>
                            <a href="#">{{$domain}}</a>
                        </div>
                    </div>
                </div>
            </div>
                @if(!buyerAuth())
        </div>
            @endif
    </section>
<div id="search_results"></div>
<div id="expert_detail_overlay" class="tm-pop-overlay modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7 col-md-12">
                            <div class="tm-pop-overlay-left similar-search-section">
                                <img class="overlay-skill-logo" src="{{ url('images/tm-overlay/new-balance.png',[],$ssl) }}" alt="" width="80" />
                                <h2><span class="overlay-heading"></span> Experts are available on-demand.</h2>
                                <div class="expert-view" id="expert-view-overlay">

                                </div>
                                <div class="tm-overlay-btns">
                                    <a href="
                                       @if (!Auth::user())
                                       {{url('signup?buyer',[],$ssl)}}
                                       @else
                                       {{url('project/create',[],$ssl)}}

                                       @endif
                                       " class="btn standard-btn">Create a Free Account</a>
                                    <p>or</p>
                                    <a class="btn btn-defult tm-login" href="{{url('login',[],$ssl)}}">Login</a>
                                </div>
                                <p>*Only pay for work completed.</p>
                                <p class="terms">Psst. Be sure to check out our <a target="_blank" href="https://web.measurematch.com/terms-of-service">Terms of Service!</a></</p>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-12">
                            <div class="tm-pop-overlay-right">
                                <h2>How MeasureMatch Works:</h2>
                                <ul class="tm-benifit">
                                    <li>
                                        <span class="gilroyregular-semibold">1. Declare your tech/data needs</span>
                                        In 3 minutes or less, get started by submitting a project brief
                                    </li>
                                    <li>
                                        <span class="gilroyregular-semibold">2. Get free proposals from relevant Experts</span>
                                        Choose from amazing, vetted independent practitioners & agencies/consultancies
                                    </li>
                                    <li>
                                        <span class="gilroyregular-semibold">3. Get to work</span>
                                        It’s easy to create a services contract and get going
                                    </li>
                                    <li>
                                        <span class="gilroyregular-semibold">4. Pay, Rate & Review</span>
                                        Securely & easily release funds from escrow & provide feedback
                                    </li>
                                </ul>
                                <h2 class="text-center tm-logo-title">MEASUREMATCH EXPERTS ARE TRUSTED BY:</h2>
                                <ul class="tm-logos">
                                    <li><img src="{{ url('images/tm-overlay/new-balance.png',[],$ssl) }}" alt="" /></li>
                                    <li><img src="{{ url('images/tm-overlay/barclays.png',[],$ssl) }}" alt="" /></li>
                                    <li><img src="{{ url('images/tm-overlay/toyota.png',[],$ssl) }}" alt="" /></li>
                                    <li><img src="{{ url('images/tm-overlay/nivea.png',[],$ssl) }}" alt="" /></li>
                                    <li><img src="{{ url('images/tm-overlay/samsung.png',[],$ssl) }}" alt="" /></li>
                                    <li><img src="{{ url('images/tm-overlay/amex.png',[],$ssl) }}" alt="" /></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script src="{{ url('js/technographic.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
