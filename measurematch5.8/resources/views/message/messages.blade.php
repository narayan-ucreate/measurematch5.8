@extends('layouts.userscommonlayout')
@section('content')
    <div id="wrapper" class="active @if(isBuyerAndVendor($user_type)) buyerdesktop_buyer @endif">
        <script src="{{url('js/side-menu.js',[],$ssl)}}"></script>

        @if(_count($user_list)< 1)
            @include('message.start_conversation')
        @else
            <div id="page-content-wrapper">
                <input type="hidden" id="timezone"/>
                <div class="page-content inset">
                    @if (isBuyerAndVendor($user_type))
                        <div class="col-md-3 leftSidebar">
                            @include('buyer.sidemenu')
                        </div>
                    @else
                        <div class="col-md-3 leftSidebar custom-left-sidebar">
                            @include('sellerdashboard.sidemenu')
                        </div>
                    @endif
                    <div class="col-md-9 rightcontent-panel">
                        <div class="message-user-herder">
                            <a class="font-14 gilroyregular-semibold" href='{{route('buyer-my-projects')}}'><img class="project-links-img" src="{{url('/images/chevron-back.svg')}}" alt="Back"/>Back</a>
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
                                <div class="myproject-section">
                                    @if (isBuyerAndVendor($user_type))
                                        <input type="hidden" id="project_id"  value="{{$id}}">
                                        <input type="hidden" id="project_type"  value="{{$type}}">
                                        <input type="hidden" id='country_search_source' value="{{$countries}}">
                                        @if ($type == config('constants.PROJECT')) <input type="hidden" id="project_url" value="{{url('re-route/project/' . $id, [],$ssl)}}"> @endif
                                        <div id="myproject_section_header" class="myproject-section-header">
                                            <div class="left-section pull-left">
                                                <ul class="myproject-breadcum">
                                                    <li><a  href="{{url('myprojects')}}">My Projects</a></li>
                                                    <li><img src="{{url('/images/chevron-right.svg')}}" alt="" /></li>
                                                    <li>
                                                        @if ($type == config('constants.PROJECT'))
                                                            {{getJob($id,config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}
                                                        @else
                                                            {{getServicePackageName($id,config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}
                                                        @endif
                                                    </li>
                                                </ul>
                                                <h2 class="section-header-title">
                                                    @if ($type == config('constants.PROJECT'))
                                                        {{getJob($id,config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}
                                                    @else
                                                        {{getServicePackageName($id, config('constants.TRUE'),config('constants.PROJECT_TITLE_LENGTH_IN_MESSAGEING'))}}
                                                    @endif
                                                </h2>
                                            </div>
                                            <div class="right-section pull-right">
                                                <ul class="project-links">
                                                    @if ($type == config('constants.PROJECT'))
                                                        <li class="view-project-link">
                                                            <a class="project-details-popup-button" data-buyer-id='{{Auth::user()->id}}' href="JavaScript:void(0);" >
                                                                <img class="project-links-img" src="{{url('/images/view-project-icon.svg')}}" alt="view project" />
                                                                View Project
                                                            </a>
                                                        </li>
                                                        @if(empty($accepted_contract_id))
                                                            <li>
                                                                <a href="{{route('editProject',$id)}}">
                                                                    <img class="project-links-img" src="{{url('/images/edit-project-icon.svg')}}" alt="" />
                                                                    Edit Project
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li class="view-contract-link" onclick="viewOffer(this);">
                                                                <a href="JavaScript:void(0);" >
                                                                    <img width="17" class="project-links-img" src="{{url('/images/view-contract.svg')}}" alt="view contract" />
                                                                    View Contract
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li>
                                                            <a href="{{url('servicepackage/'.$id)}}" target="_blank">
                                                                <img class="project-links-img" src="{{url('/images/view-project-icon.svg')}}" alt="view service package" />
                                                                View Service Package
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="seller-message-state-block expert-msg-attachment">
                                        <div id="ajax-account"></div>
                                        <div class="msgOrg">
                                            <div class="expert_message_panel">
                                                <div class="expert_profile_inner_panel col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <div class="expert-message-outer-panel">
                                                        <div id="show-user-list">
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
                                                            @include('message.chatuserlist')
                                                        </div>
                                                        <div id="show-message-list" class="expertshow-msg-block">
                                                            <div class="message-user-herder">
                                                                <a id="back_to_user_list" class="font-14 gilroyregular-semibold" href="javascript:void(0)"><img class="project-links-img" src="{{url('/images/chevron-back.svg')}}" alt="Back"/>Back</a>
                                                                <div class="message-user-info">
                                                                    <span class="receiver-user-image"></span>
                                                                    <div class="message-user-info-right">
                                                                    <h5 class="receiver-user-name gilroyregular-bold-font"></h5>
                                                                    <p class="receiver-user-company"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @include('message.messagelist')
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="show-chat-user-profile" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 seller_message_profile_preview hide">
                                                    @include('message.chatuserprofile')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div id="make_offer_stage_popups">
            </div>

            <input type="hidden" id="check_initital_message" value="0">
            <input type="hidden" id="proposal_sent" value="{{$proposal_sent ?? ''}}">
            <div id="start_conversation_pop_up" class="modal fade" role="dialog">
                <div class="modal-dialog modal-md">
                    <div class="modal-innner-content">
                        <div class="modal-content">
                            <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                            <div class="modal-body conversation_start_container">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($user_type != config('constants.BUYER'))
            <div id="proposal_success_info" class="modal perposal-success-popup lightbox-design perposalview-offer">
                <div class="modal-dialog" role="dialog">
                    <div class="modal-innner-content">
                        <div class="modal-content">
                            <div class="modal-body text-align-center">
                                <button aria-label="Close" data-dismiss="modal" class="close close-proposal-sent-pop-up" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                                <img class="party-popper margin-bottom-25 margin-top-10" src="{{ url('images/party-popper.png',[],$ssl) }}">
                                <div class="clearfix"></div>
                                <h3 class="font-24">Your proposal was sent to <span class="buyer-company-name"></span>!</h3>
                                <p class="text-align-center font-16 margin-bottom-25">Nice job! Your proposal has been sent over to <span class="buyer-name"></span>. Sit tight while you wait for a response.</p>
                                <p class="text-align-center font-16 margin-bottom-20">P.S. A top tip: It's a great idea to follow up with <span class="buyer-name"></span> if you don't hear anything back after a couple of days ;)</p>
                                <div class="clearfix"></div>
                                <a href="javascript:void(0)" data-dismiss="modal" class="browse-more-btn font-16 clearfix standard-btn close-proposal-sent-pop-up"> Go to your messages with <span class="buyer-name"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @include('common_pop_ups.project_rebook')
            @include('include.footer')
            <script src="{{url('js/socketio.js',[],$ssl)}}"></script>
            <script type="text/javascript">
                var index = true;
                var sender_id = "{{Auth::user()->id}}";
                var current_user_type = "{{Auth::user()->user_type_id}}";
                var sender_name = "@php echo ucfirst(Auth::user()->name) . ' ' . ucfirst(substr(Auth::user()->last_name, 0, 1)); @endphp"
                var socket = io.connect('@php echo config('constants.MM_SOCKET_APP'); @endphp', {query: "id=" + sender_id
                    }
                );
              var is_admin_panel_view=false;
            </script>
            <script src="{{ url('js/chat.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{ url('js/buyer_empty_messages.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{url('js/jquery.validate.min.js',[],$ssl)}}"></script>



@endsection

