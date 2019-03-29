@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="active buyerdesktop_buyer">
        <div id="page-content-wrapper">
            <div class="page-content inset find-service-package-panel">
                <div class="col-md-3 leftSidebar">
                    @include('buyer.sidemenu')
                </div>
                <div class="col-md-9 rightcontent-panel">
                    <div class="theiaStickySidebar">
                        <div class="col-md-12">
                            <div class="create-package-panel">
                                <div class="white-box my-projects-list-view new-buyer-find-expert-block search-package-panel">
                                    <div class="white-box-header">
                                        <div class="buyer-search-filter-section">
                                            <h3 class="gilroyregular-bold-font">Find a Service Package</h3>
                                            <form method="get" action="{{url('servicepackages/type/search', [], $ssl)}}">
                                                <div class="input-group search-filter-keywords">
                                                    <span class="input-group-addon"><img src="{{ url('images/search-filter-keywords.svg',[],$ssl)}}" width="24" /></span>
                                                    <input type="text" id="search_key" class="form-control" name="search" placeholder="Enter keywords here..." value="">
                                                    <span class="close-filter" id="search_key_clear" style="display: none;"></span>
                                                </div>

                                                <div class="input-group search-filter-keywords search-filter-location">
                                                    <span class="input-group-addon"><img src="{{ url('images/search-filter-location.svg',[],$ssl)}}" width="24" /></span>
                                                    <input type="text" id="location" tabindex="8" maxlength="40" class="form-control" name="location" placeholder="Location" value="" autocomplete="off">
                                                    <span class="close-filter" id="location_clear" style="display: none;"></span>
                                                    <div id="office_location_tags" class="dropdown"></div>
                                                </div>

                                                <div class="remote-options">
                                                    <select class="selectpicker" name="selectremoteoption" id="remote_option">
                                                        <option value="0">Choose</option>

                                                        <option  title="Onsite only" data-content="<span class='option-title'>Onsite only</span><span class='option-content'>Only show Experts available to complete the project in your office </span>" value="2">Only show Experts available to complete the project in your office</option>

                                                        <option   title="Remote only"  data-content="<span class='option-title'>Remote only</span><span class='option-content'>Only show Experts that work remotely</span>" value="1">Only show Experts that work remotely</option>

                                                        <option  title="Onsite or remote"  data-content="<span class='option-title'>Onsite or remote</span><span class='option-content'>Show experts that are available to complete the project in your office and remotely</span>" value="3">Show experts that are available to complete the project in your office and remotely</option>
                                                    </select>
                                                    <span class="close-filter" id="remote_option_clear" style="display: none;"></span>
                                                </div>

                                                <input type="submit" value="Search" class="search-btn" >
                                            </form>
                                        </div>
                                        <ul id="nav-tabs-wrapper" class="nav nav-tabs">
                                            <li class="active"><a href="#Servicepackage" data-toggle="tab">Service Packages</a></li>
                                            <li class="saved-expert-li">
                                                <a href="#Savedpackage" data-toggle="tab" id="saved_experts">Saved Service Packages</a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div class="white-box-content">
                                        <div class="tab-content wisesearch_listing">
                                            <div role="tabpanel" class="tab-pane active match-result-section" id="Servicepackage">
                                                <div class="v-align-box">
                                                    @php $total_service_packages = 0; @endphp
                                                    @foreach($service_package_types as $service_package_type)
                                                        @if($service_package_type['count'])
                                                            @php $total_service_packages+= $service_package_type['count']; @endphp
                                                            @if($service_package_type['name'] == 'Commerce Analytics')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/commerce-analytics.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Organizational, Technology & Data Strategies, System Deployments, Data Engineering & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Data Collection & Storage')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/dataCollection-and-storage.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Data Lake Strategies, Identity Resolution, Tag Management, On-Premise and Enterprise Cloud & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Data Management Platforms')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/dataManagement-and-platform.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            System Evals and Deployments, Platform Integrations, Audience Segmentation & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Data Science')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/data-science.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Deep and Broad Data Engineering, R, Python, ML/AI Algos, Predictive Analytics & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Data Visualization/Dashboards')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/data-visualization.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Business Questions, Data Cleaning, ETL, Data Blending, R, Python, JS Visuals & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Marketing Automation & CRM')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/marketingAutomation-and-CRM.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Customer Comms Strategy, Systems Deployments, Migrations, Integrations, Troubleshooting & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Growth Marketing Execution')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/marketing-execution.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            Comms Strategy, Messaging, Data-Driven Ad Targeting, Acquisition Targets, Reporting & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Mobile App Analytics')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/Mobile-analytics.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            System Evals, Deployments, Data Layer Design and Execution, Platform Integrations & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Website Analytics')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/sales-automation.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            System Evals, Deployments, Data Layer Design and Execution, Platform Integrations & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'Social Analytics')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/social-analytics.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            System Evals, Deployments, Reporting Configurations, Platform Integrations & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if($service_package_type['name'] == 'A/B Testing & Personalization')
                                                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                                    <div class="search-result-white-bx">
                                                        <span class="expert-profile-pic">
                                                            <img src="{{ url('images/findservicepackage/testing-and-personalization.svg',[],$ssl)}}" />
                                                        </span>

                                                                        <h4>{{$service_package_type['name']}}
                                                                        </h4>

                                                                        <p>
                                                                            System Evals, Deployments, Data Collection Design, Test/Control Experiments, Platform Integrations & Related
                                                                        </p>

                                                                        <div class="view-profile-block">
                                                                            <div class="bottom-white-bx">
                                                                                <a href="{{ url('servicepackages/type/'.$service_package_type['id'],[],$ssl)}}" title="View Packages">View @if($service_package_type['count']) {{$service_package_type['count']}} @endif Packages</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @if($other_records_count)
                                                        @php $total_service_packages+= $other_records_count; @endphp
                                                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                                            <div class="search-result-white-bx">
                                                    <span class="expert-profile-pic">
                                                        <img src="{{ url('images/findservicepackage/testing-and-personalization.svg',[],$ssl)}}" />
                                                    </span>

                                                                <h4>
                                                                    Other Awesome Packages
                                                                </h4>

                                                                <p>
                                                                    Additional service packages that sit outside of the buckets above
                                                                </p>

                                                                <div class="view-profile-block">
                                                                    <div class="bottom-white-bx">
                                                                        <a href="{{ url('servicepackages/type/other',[],$ssl)}}" title="View Packages">View @if($other_records_count) {{$other_records_count}} @endif Packages</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if(!$total_service_packages)
                                                        <div class="no-result-founded">
                                                            <img src="{{ url('images/search-icon.svg',[],$ssl)}}" alt="empty-state-icon" />
                                                            <h3>No Service Packages Available</h3>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div role="tabpanel" class="tab-pane" id="Savedpackage">
                                                <div class="main_section">
                                                    <h5 class="gilroyregular-bold-font">ALL SAVED PACKAGE (<span id="saved_packages_count">0</span>)</h5>
                                                    <div class="saved_packages_section"></div>
                                                    <div class="loadmore-btn-section" id="saved_packages_section_load_more_div" style="display: none;">
                                                        <a class="loadmore-btn standard-btn" href="javascript:void(0)" id="saved_packages_section_load_more" title="Load more">Load more</a>
                                                    </div>
                                                </div></div>
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
    @if(!$welcome_pop_up_checked)
        <div class="modal fade lightbox-design welcome-service-package-popup search_pack_popup" id="search_service_package_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
                            <img src="{{ url('images/black_cross.png',[],$ssl)}}" width="14" />
                        </span>
                    </div>
                    <div class="modal-body">

                        </button>
                        <h2>Welcome to Service Packages!</h2>

                        <p>MeasureMatch Experts have hand-curated a range of their best offerings into what we call Service Packages. Vetted and industry tested, these are worth taking a look at.</p>
                        <div class="popup-btn-panel">
                            <input value="Got it" class="continue-btn green_gradient standard-btn" id="got_it_service_package" type="button">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
    @include('include.buyer_mobile_body')
    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{url('js/searchservicepackage.js?js='.$random_number,[],$ssl)}}"></script>
    @include('include.footer')
@endsection
