@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
    <div id="page-content-wrapper">
        <div class="page-content inset">
            <div class="col-md-3 leftSidebar">
                    @include('buyer.sidemenu')
            </div>
            <input type="hidden" value="{{$id}}" id="type">
            <div class="col-md-9 rightcontent-panel">
                <div class="theiaStickySidebar">
                    <div class="col-md-12">
                        <div class="breadcrumb-bg project-details-breadcrumb">
                            <ul>
                                <li><a href="{{ url('servicepackage/types',[],$ssl)}}">Find a Service Package</a></li>
                                <li>{{$title}}</li>
                            </ul>
                        </div>
                        <div class="create-package-panel">
                            <div class="white-box my-projects-list-view new-buyer-find-expert-block search-package-panel">
                                <div class="white-box-header">
                                    <div class="typewiselist_packgaes">
                                        <div class="remote-options custom-dropdown-style">
                                            <select class="selectpicker" name="selectremoteoption" id="featured_packages">
                                                @foreach($featured_list as $value => $featured)
                                                <option title="@if(isset($total_records) && $total_records && $title != 'Search All') {{$total_records.' '}} @endif {{$title}}" value="{{$value}}" @if((isset($id) && is_numeric($id) && $id==$value) || (null !== app('request')->input('selected_featured_package') && app('request')->input('selected_featured_package')==$value)) selected='selected' @endif>{{$featured}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="buyer-search-filter-section">
                                        <form method="get" action="{{url('servicepackages/type/search', [], $ssl)}}" id="service_package_search_form">
                                            <input type="hidden" name="selected_featured_package" id="selected_featured_package" value="{{$id}}"/>
                                            <div class="input-group search-filter-keywords">
                                                <span class="input-group-addon"><img src="{{ url('images/search-filter-keywords.svg',[],$ssl)}}" width="24" /></span>
                                                <input type="text" id="search_key" class="form-control" name="search" placeholder="Enter keywords here..." value="{{ app('request')->input('search') }}">
                                                <span class="close-filter" id="search_key_clear" style="display: none;"></span>
                                            </div>

                                            <div class="input-group search-filter-keywords search-filter-location">
                                                <span class="input-group-addon"><img src="{{ url('images/search-filter-location.svg',[],$ssl)}}" width="24" /></span>
                                                <input type="text" id="location" tabindex="8" maxlength="40" class="form-control" name="location" placeholder="Location" value="{{ app('request')->input('location') }}" autocomplete="off">
                                                <span class="close-filter" id="location_clear" style="display: none;"></span>
                                                <div id="office_location_tags" class="dropdown"></div>
                                            </div>

                                            <div class="remote-options">
                                                <select class="selectpicker" name="selectremoteoption" id="remote_option">
                                                    <option value="0">Choose</option>

                                                    <option  title="Onsite only" data-content="<span class='option-title'>Onsite only</span><span class='option-content'>Only show Experts available to complete the project in your office </span>" value="2" @if(app('request')->input('selectremoteoption')==2) selected='selected' @endif>Only show Experts available to complete the project in your office</option>

                                                    <option   title="Remote only"  data-content="<span class='option-title'>Remote only</span><span class='option-content'>Only show Experts that work remotely</span>" value="1" @if(app('request')->input('selectremoteoption')==1) selected='selected' @endif>Only show Experts that work remotely</option>

                                                    <option  title="Onsite or remote"  data-content="<span class='option-title'>Onsite or remote</span><span class='option-content'>Show experts that are available to complete the project in your office and remotely</span>" value="3" @if(app('request')->input('selectremoteoption')==3) selected='selected' @endif>Show experts that are available to complete the project in your office and remotely</option>
                                                </select>
                                                <span class="close-filter" id="remote_option_clear" style="display: none;"></span>
                                            </div>

                                          <input type="submit" value="Search" class="search-btn" >
                                        </form>
                                    </div>
                                    <ul id="nav-tabs-wrapper" class="nav nav-tabs">
                                        <li class="active"><a href="#Servicepackage" data-toggle="tab" id="match_results">Service Packages</a></li>
                                        <li class="saved-expert-li">
                                            <a href="#saved_package" data-toggle="tab" id="saved_experts">Saved Service Packages</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="white-box-content">
                                    <div class="tab-content wisesearch_listing">
                                        <div role="tabpanel" class="tab-pane active match-result-section" id="Servicepackage">
                                            <div class="main_section">
                                                @if(_count($service_packages))
                                                @php $count = 0; @endphp
                                                @foreach($service_packages as $service_package)
                                                @php $count++; @endphp
                                                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 expert-detail-col servicepackage-panel">
                                                    <div class="search-result-white-bx typewiselist">
                                                        <div class="typewiselist_header">
                                                            <h4>
                                                                {{getTruncatedContent($service_package['name'], 120)}}
                                                                <a href="javascript:void(0)" class="saved-expert saved-icon
                                                                   @if(in_array($service_package['id'], $saved_packages_list))
                                                                   save-expert-icon unsave_the_package
                                                                   @else save_the_package @endif"
                                                                   buyer-id="{{Auth::user()->id}}" service-package-id="{{$service_package['id']}}"
                                                                   saved_id='@if(in_array($service_package["id"], $saved_packages_list))
                                                                   {{array_search($service_package["id"], $saved_packages_list)}}@endif'></a>
                                                            </h4>
                                                        </div>
                                                        <div class="typewiselist_desc">
                                                            <p class="gilroyregular-font">
                                                            {{getTruncatedContent(strip_tags($service_package['description']), config('constants.SERVICE_PACKAGE_DESCRIPTION_LENGTH'))}}
                                                            </p>
                                                            <div class="typewiselist_userinfo">
                                                                <a href="{{url('buyer/expert-profile/'.$service_package['user_details']['id'], [], $ssl)}}">
                                                                    <div class="userimg" style="background-image:url({{$service_package['user_details']['user_profile']['profile_picture']}});"></div>
                                                                    <div class="expert-package-details">
                                                                        <strong class="gilroyregular-bold-font">{{ucfirst($service_package['user_details']['name'])}} {{ucfirst(substr($service_package['user_details']['last_name'], 0, 1))}}</strong>
                                                                        <span class="gilroyregular-font">{{ucfirst($service_package['user_details']['user_profile']['describe'])}}</span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="typewiselist_footer">
                                                            <div class="total_budget">
                                                                Guide Budget: <span class="gilroyregular-bold-font">${{number_format($service_package['price'])}}@if($service_package['subscription_type']=='monthly_retainer')/month @endif</span>
                                                            </div>
                                                            <a href="{{url('servicepackage/'.$service_package['id'],[],$ssl)}}" class="gilroyregular-bold-font">View Package</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($count%2 == 0) <div class="clearfix clearline"></div> @endif
                                                @endforeach
                                                @else
                                                <div class="no-result-founded">
                                                    <img src="{{ url('images/empty-state-icon.svg',[],$ssl)}}" alt="empty-state-icon" />
                                                    <h3>Doh! No results</h3>
                                                    @if(!isset($is_searched))
                                                    <h4>We couldn't find any Packages for this category</h4>
                                                    <h4>Please search for other category to find the best available Packages</h4>
                                                    @else
                                                    <h4>Try modifying your search filters to find more Packages</h4>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @if($show_load_more_button == TRUE)
                                            <div class="loadmore-btn-section" id="main_section_load_more_div">
                                                <a class="loadmore-btn standard-btn" href="javascript:void(0)" id="load_more_result" title="Load more">Load more</a>
                                            </div>
                                            @endif
                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="saved_package">
                                            <h5 class="gilroyregular-bold-font">ALL SAVED PACKAGES (<span id="saved_packages_count">0</span>)</h5>
                                            <div class="saved_packages_section"></div>
                                            <div class="loadmore-btn-section" id="saved_packages_section_load_more_div" style="display: none;">
                                                <a class="loadmore-btn standard-btn" href="javascript:void(0)" id="saved_packages_section_load_more" title="Load more">Load more</a>
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
<script src="{{url('js/searchservicepackage.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
