<div id="page-content-wrapper">
    <div class="page-content inset">
        <div class="col-md-3 leftSidebar">
            @if (isBuyerAndVendor($user_type))
                @include('buyer.sidemenu')
            @else
                @include('sellerdashboard.sidemenu')
            @endif
        </div>
        <div class="col-md-9 rightcontent-panel">
            <div class="message-user-herder">
                <a class="font-14 gilroyregular-semibold" href='{{route('buyer-my-projects')}}'>
                    <img class="project-links-img" src="{{url('/images/chevron-back.svg')}}" alt="Back"/>
                    Back
                </a>
                <div class="message-user-info">
                    <span class="receiver-user-image"></span>
                    <div class="message-user-info-right">
                    <h5 class="receiver-user-name gilroyregular-bold-font"></h5>
                    <p class="receiver-user-company"></p>
                    </div>
                </div>
            </div>
            <div class="theiaStickySidebar">
                <div class="col-md-12">
                    <div class="myproject-section mobile--empty message-block">
                        @if (isBuyerAndVendor($user_type))
                            <input type="hidden" id="project_id"  value="{{$id}}">
                            <div id="make_offer_stage_popups"></div>
                            <div id="myproject_section_header" class="myproject-section-header">
                                <div class="left-section pull-left">
                                    <ul class="myproject-breadcum">
                                        <li><a href="{{url('myprojects',[],$ssl) }}" >My Projects</a></li>
                                        <li><img src="{{url('/images/chevron-right.svg',[],$ssl)}}" alt="" /></li>
                                        <li>{{getJob($id,config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}</li>
                                    </ul>
                                    <h2 class="section-header-title">{{getJob($id,config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}</h2>
                                </div>
                                <div class="right-section pull-right">
                                    <ul class="project-links">
                                        <li><a class="project-details-popup-button" data-buyer-id='{{Auth::user()->id}}' href="JavaScript:void(0);">
                                                <img class="project-links-img" src="{{url('/images/view-project-icon.svg',[],$ssl) }}" alt="" />
                                                View Project
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('editProject',$id)}}"><img class="project-links-img" src="{{url('/images/edit-project-icon.svg',[],$ssl) }}" alt="" />
                                                Edit Project
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="message-listing-herder">
                                <h3 class="gilroyregular-semibold font-24 @if(isBuyerAndVendor($user_type)){{'buyer-desktop-message-title'}}@endif">Messages</h3>
                                @if(isBuyerAndVendor($user_type))
                                    <h3 class="gilroyregular-semibold font-24 buyer-start-conversation-title">
                                        @if ($type == config('constants.PROJECT'))
                                            {{getJob($id,config('constants.FALSE'))}}
                                        @else
                                            {{getServicePackageName($id, config('constants.FALSE'))}}
                                        @endif
                                    </h3>
                                @endif
                            </div>
                            <div  class="project-brife-section  margin-b-32">
                                <div id="chat-box" class="brife-detail">
                                    @if ($project_approved_status == config('constants.PROJECT_PENDING'))
                                        <div class="block text-center first-block">
                                            <img src="{{url('/images/project-under-review.svg',[],$ssl) }}" alt="" />
                                            <h2>Your Project Brief is under review!</h2>
                                            <p>All Projects are reviewed by the MeasureMatch team before they are submitted live to the network.</p>
                                            <!-- Calendly link widget begin -->
                                            <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
                                            <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript"></script>
                                            <!-- Calendly link widget end -->
                                            <p>If you'd like to speed up the process, please <span class="calendly-link"><a href="" onclick="Calendly.showPopupWidget('https://calendly.com/measurematch/30');return false;">book a call with us.</a></span></p>
                                        </div>
                                        <div class="block question-bg">
                                            <div class="question-block">
                                                <h4>How long does the review process take?</h4>
                                                <p>To set your expectations a little, it can take up to 48 hours for the review process to be completed.</p>
                                            </div>
                                            <div class="question-block">
                                                <h4>Why does MeasureMatch review Projects submitted to the platform?</h4>
                                                <p>To ensure our network of Experts have the skills and experience required for your Project. We don’t want to waste anyone’s time.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="block text-center first-block">
                                            <img src="{{url('/images/project-live.svg',[],$ssl) }}" alt="" />
                                            <h2>Your Project Brief is now live!</h2>
                                            <p>Your Project is now live and viewable by our nework of Experts to “Express Interest” in your Project.
                                                From there, you decide which Experts you’d like to engage in a conversation.</p>
                                        </div>
                                        <div class="block question-bg">
                                            <div class="question-block">
                                                <h4>What happens now my Project is live?</h4>
                                                <p>Your Project is now open for Experts in our network to “Express Interest“ in.
                                                    Typically, these come in within the 1st hour of your Project brief being live.</p>
                                            </div>
                                            <div class="question-block">
                                                <h4>What happens after I receive EOIs (Expressions of Interest)?</h4>
                                                <p>You’ll decide which Experts you’d like to engage in a conversation to learn more about their skills and experience,
                                                    as well as start a pricing and scoping negotiation.</p>
                                            </div>
                                        </div>
                                    @endif
                                    <a class="btn standard-btn gilroyregular-semibold margin-top-20"  href="{{url('project/create',[],$ssl)}}">Submit a new Project Brief</a>
                                </div>
                            </div>
                            <div id="one-to-one-chatbox"></div>
                        @else
                            <div class="message-contianer welcome-message-screen-panel">
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 message-white-box padding-left-0 padding-right-0">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 message-outer-border scrollbar user-list-panel">
                                        <div class="bhoechie-tab-menu">
                                            <div class="list-group">
                                                <a class="list-group-item text-center msgLst active" href="javascript:void(0)" start-conversation=""
                                                   communication-id="" receiver-id="" sender-id="" user-type="">
                                                    <span class="seller-info">
                                                        <span class="seller-name receiver-name">James Sandoval</span>
                                                        <span class="seller-company-name">Welcome to measurematch</span>
                                                        <span class="time"> {{date('d M, Y h:i a', strtotime(Auth::user()->created_at))}}</span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Expert Middle panel start from here-->
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 conversation-section cnvtn chat-block">
                                        <div class="chat-top-panel message-white-box">
                                            <span class="seller-name">James Sandoval</span>
                                        </div>
                                        <div id="chat-box" class="conversation-block convstn welcome-contianer-panel">
                                            <div class=" welcome-message-content">
                                                @php
                                                    $complete_status = calculateProfileCompletePercentageStatus()['basic_profile_completness'];
                                                @endphp
                                                <div class="welcome-message-inner-container">
                                                    <div class="welcome-message-content-01">
                                                        <div class="welcome-message-content-inner-01">
                                                            <p>Hi,<br /></p> <p>
                                                                This is James, MeasureMatch’s Founder & CEO. I’m thrilled to have you here.
                                                                Both automated email notifications and messages between you and MeasureMatch Clients will flow through here.
                                                                We look forward to doing great things together with you.
                                                                Please always feel free to get in touch with questions, comments or concerns.
                                                            </p><p> Thank you! <br> James</p>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <a href="{{url('/expert/projects-search',[],$ssl)}}"
                                                       class="getStarted btn-standard-middle begin-btn standard-btn @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
                                                       @if(Auth::user()->admin_approval_status!=1)expert_profile_admin_unapproved @endif @else expert_profile_incomplete @endif">Browse Projects</a>
                                                </div>

                                                <div id="one-to-one-chatbox"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--Expert right panel start from here-->
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 message-right-pannel">
                                    <div class="scrollbar message-white-box buyer-profile-panel">
                                        <div class="expert_profile_inner_panel expert-profile-pannel" style="height: 102px;">
                                            <div class="profile-display-section">
                                                <div id="show-buyer-profile">

                                                    <span class="seller-pic expert-left-img-msg">
                                                        <div class="profilepicture" style="background-image:url({{url('images/james_sandoval.jpg', [], $ssl)}})"></div>
                                                    </span>
                                                    <span class="seller-info">
                                                        <span class="seller-name">James Sandoval</span>
                                                        <span class="seller-company-name">MeasureMatch Founder & CEO </span>
                                                        <span class="seller-email">james@measurematch.com</span>
                                                    </span>
                                                </div>

                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ url('js/common_buyer_pages.js?js='.$random_number,[],$ssl)}}"></script>