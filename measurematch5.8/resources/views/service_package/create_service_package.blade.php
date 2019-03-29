@extends('layouts.layout')
@section('content')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 create-package-panel">
        <div class="white-box">

            <div class="white-box-content left-padding-0 right-padding-0 top-padding-0">
                @if(Session::has('status'))
                    <span class="error_msg fade_error_message error-message">{{Session::get('status')}}</span>
                @endif
                <form id="creat_service_package_form" class="describe-opportunity" method="post" enctype="multipart/form-data" action="{{url('servicepackage/save',[],$ssl)}}">

                    {{ csrf_field() }}
                    <input type="hidden" value="{{$welcome_service_package_value}}" id="welcome_service_package_status" name="welcome_service_package_status">
                    <input type="hidden" value="{{$service_package_welcome_popup_count}}" id="service_package_welcome_popup_count" name="service_package_welcome_popup_count">
                    <div class="togglable-tabs-design">
                        <ul class="nav nav-tabs">
                            <li id="tag-3" class="active">
                                <a href="javascript:void(0)">Create Service Package</a>
                                <span class="form-step">Steps:
                                                            <span class="step-1 step-circle current-step">1</span>
                                                            <span class="step-2 step-circle">2</span>
                                                            <span class="step-3 step-circle">3</span>
                                                            <span class="step-4 step-circle">4</span>
                                                        </span>
                            </li>
                        </ul>
                    </div>
                    <div class="divider-green"></div>
                    {{ csrf_field() }}
                    <div id="service-package-step-1">
                        <div class="form-inner-container">
                            <div class="input-bx-panel">
                                <label>Give your Package a name <span class="notification-star-buyer">*</span></label>
                                <input tabindex="2" maxlength="250" size="30" value=""  id="name" name="name" placeholder="e.g. Marketo automation strategy & implementation" type="text" />
                                <div class="validation_error_name error-message"></div>
                            </div>
                            <div class="input-bx-panel">
                                <label>Describe your Service <span class="notification-star-buyer">*</span></label>
                                <textarea value="" maxlength="10000" id="description" name="description" class="add_description"  placeholder="How will the Client be more successful after your Package?"></textarea>
                                <div class="validation_error_description error-message"></div>
                            </div>
                            <div class="input-bx-panel multi-selectpicer select-dropdown-style">
                                <label>One-Time Package or Monthly Retainer? <span class="notification-star-buyer">*</span></label>
                                <select class="selectpicker" name="subscription_type" tabindex="3"  id="subscription_type">
                                    <option value="">Choose</option>
                                    <option value="one_time_package" title="One-Time Package" data-content="<span class='option-title'>One-Time Package</span><span class='option-content'>
                                                                    Select this option for a service package framework defined by specific time and deliverables commitments within a declared timeframe.
                                                                    This service package can be purchased as-is (i.e. off-the-shelf) or customized to meet each client's needs before locking into a service contract. </span>"
                                            value="2">
                                        Select this option for a service package framework defined by specific time and deliverables commitments within a declared timeframe.
                                        This service package can be purchased as-is (i.e. off-the-shelf) or customized to meet each client's needs before locking into a service contract.</option>
                                    <option value="monthly_retainer" title="Monthly Retainer"  data-content="<span class='option-title'>Monthly Retainer</span>
                                                                    <span class='option-content'>Select this option for a service package that you'd like to sell and service on a month-by-month retained basis.
                                                                    This package can also be customized to meet each client's needs before locking into a retainer service contract.</span>" value="1">
                                        Select this option for a service package that you'd like to sell and service on a month-by-month retained basis.
                                        This package can also be customized to meet each client's needs before locking into a retainer service contract.</option>
                                </select>

                                <div class="validation_error_subscription_type error-message"></div>
                            </div>
                            <div class="input-bx-panel require-info-panel">
                                <label class="required-fields">*required fields</label>
                            </div>
                        </div>

                        <div class="input-bx-panel">
                            <input id="create-package-step-1" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">
                            <span class="muted-text">You can edit these Package details later by the way.</span>
                        </div>
                    </div>

                    <div id="service-package-step-2" class="" style='display:none'>
                        <div class="form-inner-container">
                            <div class="input-bx-panel multi-selectpicer select-dropdown-style">
                                <label>Select a Service Package Category<span class="notification-star-buyer">*</span></label>
                                <select id="service_package_category" class="selectpicker" name="service_package_category" >
                                    <option value="">Choose</option>

                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}" title="{{$category['name']}}"  data-content="{{$category['name']}}" value="2">{{$category['name']}}</option>
                                    @endforeach
                                </select>

                                <div class="validation_error_service_package_category error-message"></div>
                            </div>
                            <div class="package-type-autocomplete input-bx-panel multi-selectpicer custom-dropdown-style select-dropdown-style">
                                <label>Select or Add a Service Package Type<span class="notification-star-buyer">*</span></label>
                                <select id="service_package_type_featured" class="selectpicker" name="service_package_type" >
                                    <option value="">Choose</option>
                                    @foreach($featured_listing as $service_package_type)
                                        <option value="{{$service_package_type}}" title="{{$service_package_type}}"  data-content="{{$service_package_type}}">{{$service_package_type}}</option>
                                    @endforeach
                                    <option value="Other" title="Other"  data-content="Other">Other</option>
                                </select>
                                <input  tabindex="3" type="text"  id="add_service_package_type_manually" class="skill-input" autocomplete="off" value="" name="service_package_type_other" placeholder="Add your own Service Package Type here..." maxlength="150" style="display: none;"/>
                                <div class="validation_error_service_package_type error-message"></div>
                            </div>
                            <div class="input-bx-panel multi-selectpicer select-dropdown-style tag-style">
                                <label>Service Package Tags (minimum 3) <span class="notification-star-buyer">*</span><a class="info-icon info-icon-left-arrow">? <span> Add skills, technologies and other language to maximise findability.</span></a></label>
                                <div class="add-skill-button-block margin-bottom-10">
                                    <div class="addskill"></div>
                                    <input  tabindex="3" type="text"  id="addskill_manually" class="skill-input"  autocomplete="off" value=""  name="addskill_manually" placeholder="e.g. JavaScript, Python, Enterprise Web Analytics"/>
                                    <input type="hidden"  id="manual_skills"  value=""  name="tags" />

                                </div>
                                <label class="directions-in-blue">* Use comma or enter for separate tags</label>
                                <div class="error-message validation_error_manual_skills{{ $errors->has('tags') ? ' has-error' : '' }}"></div>
                            </div>
                            <div class="input-bx-panel require-info-panel">
                                <label class="required-fields">*required fields</label>
                            </div>
                        </div>
                        <div class="input-bx-panel">
                            <input id="service-package-back-1" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                            <input id="create-package-step-2" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">

                            <span class="muted-text">You can edit these Package details later by the way.</span>
                        </div>
                    </div>

                    <div id="service-package-step-3" style="display:none">
                        <div class="form-inner-container">
                            <div class="input-bx-panel deliverable-panel bottom-margin-0">
                                <label>List the deliverables of your Package <span class="notification-star-buyer">*</span></label>
                                <textarea name="deliverables[]" maxlength="2500" value="" class="deliverables add_description"  placeholder="Deliverable 1"></textarea>
                                <textarea name="deliverables[]" maxlength="2500" value="" class="deliverables add_description"  placeholder="Deliverable 2"></textarea>
                                <textarea name="deliverables[]" maxlength="2500" value="" class="deliverables add_description"  placeholder="Deliverable 3"></textarea>
                                <div class="clearfix"></div>

                                <div class="validation_error_deliverables error-message"></div>
                            </div>
                            <div class="input-bx-panel top-margin-0">
                                <a href="javascript:void(0);" class="add-deliverable-link">Add another deliverable</a> </div>
                            <div class="input-bx-panel top-margin-0">
                                <label>Please add what do you need from the Client <span class="notification-star-buyer">*</span></label>
                                <textarea name="buyer_remarks" id="buyer_remarks" maxlength="2500" value="" class="add_description"  placeholder="e.g. Credentials for Google Analytics" style="min-height:80px;"></textarea>
                                <div class="validation_error_buyer_remarks error-message"></div>
                            </div>
                            <div class="input-bx-panel require-info-panel">
                                <label class="required-fields">*required fields</label>
                            </div>
                        </div>

                        <div class="input-bx-panel">
                            <input id="service-package-back-2" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                            <input id="create-package-step-3" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">

                            <span class="muted-text">You can edit these Package details later by the way.</span>
                        </div>

                    </div>
                    <div id="service-package-step-4" style="display:none">
                        <div class="form-inner-container">
                            <div class="input-bx-panel">
                                <label id="package"> </label><span class="notification-star-buyer">*</span>
                                <div class="clearfix"></div>
                                <div class="post-rate-input-bx">
                                    <div class="input-group post-rate">
                                        <span class="input-group-addon">$</span>
                                        <input tabindex="8" maxlength="7" size="30"  name="price" id="price" value="" type="text">
                                    </div>
                                </div>
                                <div class="validation_error_price error-message"></div>
                            </div>
                            <div class="input-bx-panel maxiimum-time select-dropdown-style custom-dropdown-style">
                                <label id="commitment_duration">Expected time commitment </label>
                                <span class="notification-star-buyer">*</span>
                                <a class="info-icon info-icon-left-arrow">?
                                    <span>  <strong>30 day contracts</strong> Contracts are limited to 30 days or less, but easily extended on completion.
                                                            </span>
                                </a>
                                <div class="clearfix"></div>
                                <select class="selectpicker" name="duration" id="duration">
                                    <option value="">Choose</option>
                                    @for($date=1; $date<=30;$date++)
                                        @php $days=($date==1)?'day':'days'; @endphp
                                        <option  title="{{$date.' '.$days}}" data-content="{{$date.' '.$days}}" value="{{$date}}">{{$date.' '.$days}}</option>
                                    @endfor
                                </select>

                                <div class="validation_error_duration error-message"></div>

                            </div>

                            <div class="input-bx-panel">
                                <div class="budget-breakdown-block" id="est_budget_div">
                                    <h3><div id="budget_breakdown">Budget Breakdown</div></h3>
                                    <span>85% paid to you <span class="pull-right"><span class="paid_exp">-</span><span class="sub_type"></span></span></span>
                                    <span>15% paid to MeasureMatch <span class="pull-right"><span class="paid_mm">-</span><span class="sub_type"></span></span></span>
                                </div>
                            </div>

                            <div class="input-bx-panel require-info-panel">
                                <label class="required-fields">*required fields</label>
                            </div>
                        </div>
                        <div class="input-bx-panel">
                            <input id="service-package-back-3" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                            <input id="submit-preview" value="Save & Preview" class="continue-btn green_gradient standard-btn" type="button">
                            <input type="hidden" id="publish" name="publish" value="TRUE">
                            <span class="muted-text">You can edit these Package details later by the way.</span>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 package-draft-panel">
        <div class="draft-post-block">
            <h4>Drafts @if(_count($drafts))({{count($drafts)}})@endif</h4>
            <div class="auto-scroll">
                @if(_count($drafts))
                    @foreach($drafts as $draft)
                        <div class="draft-post">
                            <a href="{{url('servicepackage/edit/'.$draft['id'],[],$ssl)}}">
                                <span class="posted-job-title">{{ucfirst($draft['name'])}}</span>
                                <span class="job-location">@if($draft['subscription_type']=="one_time_package")One-Time @else Monthly Retainer @endif</span>
                            </a></div>
                    @endforeach
                @else
                    <div class="draft-post no-draft-message">
                        <span>You currently have no drafts</span>
                    </div>
                @endif </div></div>
    </div>
    <div id="service-package-preview"  class="modal fade bs-example-modal-lg lightbox-design package-preivew new-modal-theme" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
                            <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                        </span></button>
                </div>
                <div class="modal-body">
                    <h2>Review your Package</h2>

                    <div class="pakage-name-panel">
                        <h4>Package name</h4>
                        <p id="package_name"></p>
                    </div>
                    <div class="pakage-name-panel">
                        <h4>Package description</h4>
                        <p id="package_description"></p>
                    </div>
                    <div class="pakage-name-panel">
                        <h4>Package category</h4>
                        <p id="sp_categories_name"></p>
                    </div>

                    <div class="package-overview-panel">
                        <h4>Overview</h4>
                        <p>Guide Package Price: <strong id="package_price"></strong></p>
                        <p>Expert time commitment: <strong id="package_days"></strong></p>
                        <p id="package_description"></p>
                    </div>

                    <div class="package-deliverable-panel">
                        <h4>Deliverables</h4>
                        <ul id="package_deliverables">

                        </ul>
                    </div>

                    <div class="package-require-panel">
                        <h4>Required for completion</h4>
                        <p id="package_buyer_remarks"></p>
                    </div>
                    <div class="pakage-name-panel">
                        <h4>Tags</h4>
                        <p id="sp_tags_name"></p>
                    </div>
                    <div class="popup-btn-panel">
                        <input id="continue-editing" value="Continue Editing" class="white-bg-btn preview white-btn" type="button">
                        <input id="submit_service_package" name="submit_service_package" data-toggle="modal" data-target="#service-package-reivew-thankyou" value="Create Package" class="continue-btn green_gradient standard-btn" type="button">
                        <input id="save_to_drafts_service_package" value="Save to Drafts" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="welcome_to_service_package" class="modal fade bs-example-modal-lg  welcome-package lightbox-design package-preivew new-modal-theme" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">

                            <img src="{{ url('images/cross-black.svg',[],$ssl)}}" />
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>It's really easy to get started</h2>
                    <h5>A new way for you to showcase and sell your services.<br /> Here's how it works:</h5>

                    <div class="col-md-4 col-sm-4 col-xs-4 service-package-block">
                        <img src="{{ url('images/create-service-package-step-1.svg',[],$ssl)}}" />
                        <span>Step 1</span>
                        <h3>Create a Service Package</h3>
                        <p>Describe a specific service you're amazing at providing, including expected deliverables, your expected time commitment, give it a "from" price and submit!</p>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 await-approval-block">
                        <img src="{{ url('images/await-approval-step-2.svg',[],$ssl)}}" />

                        <span>Step 2</span>
                        <h3>Await Approval</h3>
                        <p>Like the Experts on MeasureMatch, we screen and approve all Service Packages, too. Hang tight. We'll be quick.</p>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 live-on-measurematch-block">
                        <img src="{{ url('images/live-on-measurematch-step-3.svg',[],$ssl)}}" />

                        <span>Step 3</span>
                        <h3>Live on MeasureMatch!</h3>
                        <p>This is your time to shine. We will promote Service Packages to both existing and prospective MeasureMatch Client account holders. Thank you and ask us for any help!</p>
                    </div>

                    <div class="popup-btn-panel">

                        <input id="got_it_tooltip_button" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    <script src="{{url('js/service-package.js?js='.$random_number,[],$ssl)}}"></script>
    <script src="{{url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
