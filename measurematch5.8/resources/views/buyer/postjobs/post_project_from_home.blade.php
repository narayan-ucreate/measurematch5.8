<!DOCTYPE html>
<html lang="en">
    @php
    saveRefererUrl();
    @endphp
    @include('include.landing_head_script')
    <link rel="stylesheet" href="{{ url('css/font-awesome.min.css?css='.$random_number,[],$ssl) }}">
    <body class="postproject-home-page">
        @include('include.googleTagsScriptsBody')
        @include('include.landingheader')
        <div id="wrapper" class="active buyerdesktop_buyer">
            <div id="page-content-wrapper">
                <div class="page-content inset postproject-panel">
                    <div id="submit_project_panel" class="container">
                        <div class="col-lg-8 col-sm-12 col-md-8 col-xs-12 submit-project-brief-panel margin-top-20">
                          <div class="heading-container">
                            <h3 class="font-24 gilroyregular-bold-font">Submit a Project brief</h3>
                            <span class="help-link font-16">Writing a brief shouldn't be torture. This form is designed to make it easy and manageable.</span>
                          </div>
                            <form method="post" enctype="multipart/form-data" id="submit_project_form" name="submit_project_form" action="{{url('homepage/postproject/save', [], $ssl)}}">
                                @include('include.postprojectform')
                            </form>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 postproject-rightside leftSidebar margin-top-42">
                            <div class="theiaStickySidebar">
                                <div class="post-description-box">
                                    <img src="{{url('images/what-happens-next.svg',[],$ssl)}}" alt="what-happens-next" />
                                    <h3 class="font-18 gilroyregular-semibold">What happens after I submit my Project brief?</h3>
                                    <p class="font-16"><strong class="gilroyregular-bold-font">Review:</strong> The MeasureMatch team will check it for relevance & clarity.</p>
                                    <p class="font-16"><strong class="gilroyregular-bold-font">Publish:</strong> It's posted to the MeasureMatch platform and relevant Experts are notified.</p>
                                    <p class="font-16"><strong class="gilroyregular-bold-font">Pitch:</strong> You'll start to receive Expressions of Interest from MeasureMatch Experts to start discovery, scoping & pricing conversations!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="code_of_conduct_panel" class="code-of-conduct-panel container">
                        <a id="go_back_to_submit_project" class="goback-btn gilroyregular" href="javascript:void(0)" >Go Back</a>
                        <div class="row">
                            <h3 class="col-md-12 gilroyregular-bold-font font-26">Our Code of Conduct</h3>
                            <p class="col-md-12 font-18 margin-0 p-line-height">All Projects that are submitted to MeasureMatch have to understand</p>
                            <p class="col-md-12 font-18 margin-0 p-line-height">and agree to these elements to make it to the next step. Here they are,</p>
                            <p class="col-md-12 font-18 margin-bottom-20 p-line-height"> so we're all on the same page:</p>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 code-conduct-widget margin-bottom-30">
                                <div class="img-panel">
                                    <img src="{{ url('images/communication.svg',[],$ssl)}}">
                                </div>
                                <h4 class="gilroyregular-semibold font-20 margin-top-20">Communicate through the platform</h4>
                                <p class="font-16 p-line-height">We ask that you communicate with Experts only within the MeasureMatch platform message facility and not use external emails or chat, unless you organise a voice or video call.</p>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 code-conduct-widget margin-bottom-30">
                              <div class="img-panel">
                                  <img src="{{ url('images/committment.svg',[],$ssl)}}">
                              </div>
                                <h4 class="gilroyregular-semibold font-20 margin-top-20">Be committed and available</h4>
                                <p class="font-16 p-line-height">If your Project changes down the road or is no longer your priority, please make that clear rather than leaving Experts hanging.</p>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 code-conduct-widget margin-bottom-30">
                              <div class="img-panel">
                                  <img src="{{ url('images/payment.svg',[],$ssl)}}">
                              </div>
                                <h4 class="gilroyregular-semibold font-20 margin-top-20">Pay on time</h4>
                                <p class="font-16 p-line-height">We are committed to ensuring a uniquely valuable experience to both Clients and Experts, which includes ensuring work done is paid for, fairly and on time. We ask that you deal with this promptly to enable us to pay your Expert with the minimum of fuss.</p>
                            </div>

                        </div>
                        <input id="code_of_conduct_submit_project" type="button"  tabindex="17" value="I understand" class="btn standard-btn margin-bottom-80 gilroyregular-semibold finish-submit-btn" />
                    </div>
                </div>
            </div>
        </div>
        @include('include.buyer_mobile_body')
        @include('include.footer')
        <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/side-menu.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{url('js/jquery-ui.js?js='.$random_number,[],$ssl)}}"></script>
        <script src="{{url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
        <script src="{{url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
        <script src="{{url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
        <script src="{{url('js/project.js?js='.$random_number,[],$ssl)}}"></script>
        @include('include.global_layout_parent')
    </body>
</html>
