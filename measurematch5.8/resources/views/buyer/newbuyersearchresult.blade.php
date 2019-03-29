<?php
$post_id = Session::get('post_id');
?>
@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="active buyerdesktop_buyer">
        <div id="page-content-wrapper">
            <div class="page-content inset">
                <div class="col-md-3 leftSidebar">
                    @include('buyer.sidemenu')
                </div>
                <div class="col-md-9 rightcontent-panel">
                    <div class="theiaStickySidebar">
                        <div class="col-lg-12">
                            @if(Session::has('posted_from_home'))
                                <section class="successfully-posted postedfor-buyer">
                            <span class="message-content">
                                @if(Session::has('post_id'))
                                    {{Session::get('msg')}} <a href="{{url('buyer/messages/project/'.Session::get('post_id'),[],$ssl)}}" onClick="job_view_search('')">here</a>
                                @else
                                    {{Session::get('msg')}}
                                @endif
                            </span>
                                    <span id="post-close-btn" class="popup-close-btn">x</span>
                                </section>
                            @endif
                            @if(Session::has('status'))
                                <section class="successfully-posted postedfor-buyer">
                            <span class="message-content">
                                {{Session::get('status')}} <a href="{{url('buyer/messages/project/'.$post_id,[],$ssl)}}">here</a>
                            </span>
                                    <span id="post-close-btn" class="popup-close-btn">x</span>
                                </section>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="my-projects-list-view new-buyer-find-expert-block">
                                    <div class="white-box">
                                        <div class="white-box-header">
                                            <div class="buyer-search-filter-section">
                                                <form method="get" action="{{url('buyer/experts/search',[],$ssl)}}">
                                                    <div class="input-group search-filter-keywords">
                                                        <span class="input-group-addon"><img src="{{ url('images/search-filter-keywords.svg',[],$ssl)}}" width="24" /></span>
                                                        <input type="text" id="search_key" class="form-control" name="search" placeholder="Enter keywords here..." value="{{$original_keywords}}">
                                                        <span class="close-filter" id="search_key_clear" style="display: none;"></span>
                                                    </div>

                                                    <div class="input-group search-filter-keywords search-filter-location">
                                                        <span class="input-group-addon"><img src="{{ url('images/search-filter-location.svg',[],$ssl)}}" width="24" /></span>
                                                        <input type="text" id="location" tabindex="8" maxlength="40" class="form-control" name="location" placeholder="Location" value="{{$location}}" autocomplete="off">
                                                        <span class="close-filter" id="location_clear" style="display: none;"></span>
                                                        <div id="office_location_tags" class="dropdown"></div>
                                                    </div>

                                                    <div class="remote-options">
                                                        <select class="selectpicker" name="selectremoteoption" id="remote_option">
                                                            <option value="0">Choose</option>

                                                            <option  title="Onsite only" data-content="<span class='option-title'>Onsite only</span><span class='option-content'>Only show Experts available to complete the project in your office </span>" value="2" @if($remote_option==2)selected='selected'@endif>Only show Experts available to complete the project in your office</option>

                                                            <option   title="Remote only"  data-content="<span class='option-title'>Remote only</span><span class='option-content'>Only show Experts that work remotely</span>" value="1" @if($remote_option==1)selected='selected'@endif>Only show Experts that work remotely</option>

                                                            <option  title="Onsite or remote"  data-content="<span class='option-title'>Onsite or remote</span><span class='option-content'>Show experts that are available to complete the project in your office and remotely</span>" value="3" @if($remote_option==3)selected='selected'@endif>Show experts that are available to complete the project in your office and remotely</option>
                                                        </select>
                                                        <span class="close-filter" id="remote_option_clear" style="display: none;"></span>
                                                    </div>

                                                    <input type="submit" value="Search" class="search-btn" id="submit_form">
                                                </form>
                                            </div>
                                            <ul id="nav-tabs-wrapper" class="nav nav-tabs">
                                                <li class="active"><a href="#vtab1" data-toggle="tab" id="match_results">Match Results</a></li>
                                                <li class="saved-expert-li black-toltip">
                                                    <a href="#vtab2" data-toggle="tab" id="saved_experts">Saved Experts (<span id="total_saved_experts">{{(_count($all_saved_experts))}}</span>)</a>
                                                </li>
                                            </ul>

                                        </div>

                                        <div class="white-box-content">
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active match-result-section" id="vtab1">
                                                    <div class="loading-result-section" id="loading_results_div">
                                                        <div class="sk-three-bounce">
                                                            <div class="sk-child sk-bounce1"></div>
                                                            <div class="sk-child sk-bounce2"></div>
                                                            <div class="sk-child sk-bounce3"></div>
                                                        </div>

                                                        <h3>The MeasureMatch Mothership is booting up…</h3>
                                                        <h4>Loading some awesome Experts</h4>
                                                    </div>
                                                    <div class="main_search_section_result" style="display: none;">
                                                        <div class="v-align-box">
                                                            @if(_count($users))
                                                                @php $count = 0; @endphp
                                                                @foreach($users as $user)
                                                                    @php $count++; @endphp
                                                                    <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                        <div class="search-result-white-bx">
                                                                            <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['id']}}')"><span class="expert-profile-pic" style="background-image:url({{ getImage($user['complete_mandatory_fields']['profile_picture'], $ssl) }});"></span></a>
                                                                            <h4>{{userName($user['id'],1)}}
                                                                                <a href="javascript:void(0)" class="saved-expert saved-icon save_expert @if(in_array($user['id'], $all_saved_experts)) save-expert-icon @endif" user_id="{{$user['id']}}"></a>
                                                                                <div class="white-theme-tooltip active_project_listing" id="{{$user['id']}}"></div>
                                                                            </h4>
                                                                            <span class="expert-job">
                                                        @if(strlen($user['complete_mandatory_fields']['describe'])>28)
                                                                                    {{strip_tags(ucfirst(substr($user['complete_mandatory_fields']['describe'],0,28))).'...'}}
                                                                                @else
                                                                                    {{strip_tags(ucfirst($user['complete_mandatory_fields']['describe']))}}
                                                                                @endif
                                                    </span>
                                                                            <span class="country-flag">
                                                        @if(!empty($user['complete_mandatory_fields']['country']) && !empty(getCountryFlag($user['complete_mandatory_fields']['country'])))
                                                                                    <img src="{{getCountryFlag($user['complete_mandatory_fields']['country'])}}">
                                                                                @endif
                                                                                {{getTruncatedContent($user['complete_mandatory_fields']['current_city'], 28)}}
                                                    </span>
                                                                            <p>
                                                                                {{strip_tags(getTruncatedContent($user['complete_mandatory_fields']['summary'], 142))}}
                                                                            </p>

                                                                            <div class="view-profile-block">
                                                                                <div class="bottom-white-bx">
                                                                                    <a href="javascript:void(0)" title="View Profile" onclick="searchExpertDetails('{{$user['id']}}')">View Profile</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @if($count%3 == 0) <div class="clearfix clearline"></div> @endif
                                                                @endforeach
                                                            @else
                                                                <div class="no-result-founded">
                                                                    <img src="{{ url('images/empty-state-icon.svg',[],$ssl)}}" alt="empty-state-icon" />
                                                                    <h3>Doh! No results</h3>
                                                                    <h4>Try modifying your search filters to find more Experts</h4>
                                                                    <a href="javascript:void(0)" class="clear-search-filter" id="clear_search" title="Clear search filters">Clear search filters</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($show_load_more_button == TRUE)
                                                        <div class="loadmore-btn-section" id="main_search_section_load_more_div_result" style="display: none;">
                                                            <a class="loadmore-btn standard-btn" href="javascript:void(0)" id="main_search_section_load_more_result" title="Load more">Load more</a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade match-result-section saved-expert-result" id="vtab2">
                                                    <div class="saved_experts_listing_section"></div>
                                                    <div class="loadmore-btn-section" id="saved_expert_section_load_more_div" style="display: none;">
                                                        <a class="loadmore-btn standard-btn" href="javascript:void(0)" id="saved_expert_section_load_more" title="Load more">Load more</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('include.buyer_mobile_body')
    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/buyer_search.js?js='.$random_number,[],$ssl) }}"></script>
    @include('include.footer')
    <script  type="text/javascript">
        function searchExpertDetails(id) {
            localStorage.clear();
            var new_url = base_url + '/buyer/expert-profile/' + id;
            var win = window.open(new_url, '_blank');
            win.focus();
        }
    </script>
@endsection
