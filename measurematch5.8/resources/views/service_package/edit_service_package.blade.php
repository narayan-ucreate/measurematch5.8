@extends('layouts.expert_layout')
@section('content')
<div id="wrapper" class="active">

    <div id="page-content-wrapper">
        <div class="page-content inset">


            <div class="col-md-3 leftSidebar custom-left-sidebar">
                @include('sellerdashboard.sidemenu')
            </div>
            <div class="col-md-9 rightcontent-panel">
                <div class="theiaStickySidebar">
            <div class="row">
                <div class="col-md-12 create-package-panel  ">
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="white-box">

                            <div class="white-box-content left-padding-0 right-padding-0 top-padding-0">
                                @if(Session::has('status'))
                                <span class="error_msg fade_error_message error-message">{{Session::get('status')}}</span>
                                @endif
                                <form id="update_service_package_form" class="describe-opportunity" method="post" enctype="multipart/form-data" action="{{url('servicepackage/update/'.$service_package_details->id,[],$ssl)}}">
                                    <input type="hidden" value="1" id="welcome_service_package_status" name="welcome_service_package_status">
                                    {{ csrf_field() }}
                                    <div class="togglable-tabs-design">
                                        <ul class="nav nav-tabs">


                                            <li id="tag-3" class="active"><a href="javascript:void(0)">Edit Service Package</a><span class="form-step">Steps: <span class="step-1 step-circle current-step">1</span><span class="step-2 step-circle">2</span><span class="step-3 step-circle">3</span><span class="step-4 step-circle">4</span></span></li>
                                        </ul>
                                    </div>

                                    <div class="divider-green"></div>
                                    {{ csrf_field() }}
                                    <div id="service-package-step-1">
                                        <div class="input-bx-panel">
                                            <label>Give your Package a name <span class="notification-star-buyer">*</span></label>
                                            <input type="text" maxlength="250" value="{{$service_package_details->name}}" id="name" name="name"  placeholder="e.g. Marketo automation strategy & implementation" />
                                            <div class="validation_error_name error-message"></div>
                                        </div>
                                        <div class="input-bx-panel">
                                            <label>Describe your Service <span class="notification-star-buyer">*</span></label>
                                            <textarea id="description" name="description" value="" maxlength="10000" class="add_description" placeholder="How will the Client be more successful after your Package?">{{ $service_package_details->description }}</textarea>
                                            <div class="validation_error_description error-message"></div>
                                        </div>
                                        
                                        
                                        <div class="input-bx-panel multi-selectpicer select-dropdown-style">
                                            <label>One-Time Package or Monthly Retainer? <span class="notification-star-buyer">*</span></label>
                                            <select class="selectpicker" name="subscription_type" id="subscription_type">
                                                <option value="">Choose</option>
                                                <option value="one_time_package" title="One-Time Package" 
                                                        data-content="<span class='option-title'>One-Time Package</span>
                                                        <span class='option-content'>
                                                        Select this option for a service package framework defined by specific time and deliverables commitments within a declared timeframe.
                                                        This service package can be purchased as-is (i.e. off-the-shelf) or customized to meet each client's needs before locking into a service contract. </span>"
                                                        @if($service_package_details->subscription_type == 'one_time_package' ) selected='selected' @endif>One time packages </option>
                                                <option value="monthly_retainer" title="Monthly Retainer" data-content="<span class='option-title'>Monthly Retainer</span>
                                                        <span class='option-content'>Select this option for a service package that you'd like to sell and service on a month-by-month retained basis. 
                                                        This package can also be customized to meet each client's needs before locking into a retainer service contract.</span>" 
                                                        @if($service_package_details->subscription_type == 'monthly_retainer' ) selected='selected' @endif>Monthly Retainer</option>
                                            </select>
                                            <div class="validation_error_subscription_type error-message"></div>
                                        </div>
                                        <div class="input-bx-panel require-info-panel">
                                            <label class="required-fields">*required fields</label>
                                        </div>
                                        <div class="input-bx-panel">
                                            <input id="create-package-step-1" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">
                                            <span class="muted-text">You can edit these Package details later by the way.</span>
                                        </div>
                                    </div>

                                    <div id="service-package-step-2" class="" style='display:none'>
                                        <div class="input-bx-panel multi-selectpicer">
                                            <label>Select a Service Package Category<span class="notification-star-buyer">*</span></label>
                                            <select id="service_package_category" class="selectpicker" name="service_package_category" >
                                                <option value="">Choose</option>
                                                @foreach($categories as $category)
                                                <option value="{{$category['id']}}" title="{{$category['name']}}"  data-content="{{$category['name']}}" @if($service_package_details->service_packages_category_id==$category['id']) selected='selected' @else '' @endif>{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                            <div class="validation_error_service_package_category error-message"></div>
                                        </div>
                                         <div class="package-type-autocomplete input-bx-panel multi-selectpicer custom-dropdown-style select-dropdown-style">
                                            <label>Select or Add a Service Package Type<span class="notification-star-buyer">*</span></label>
                                            <select id="service_package_type_featured" class="selectpicker" name="service_package_type" >
                                                <option value="">Choose</option>
                                                @foreach($featured_listing as $service_package_type)
                                                <option value="{{$service_package_type}}" title="{{$service_package_type}}"  data-content="{{$service_package_type}}" @if($service_package_type==$service_package_details->servicePackageType->name) selected='selected' @endif>{{$service_package_type}}</option>
                                                @endforeach
                                                <option value="Other" title="Other"  data-content="Other" @if(!in_array($service_package_details->servicePackageType->name, $featured_listing)) selected='selected' @endif>Other</option>
                                            </select>
                                            <input  tabindex="3" type="text" id="add_service_package_type_manually" class="skill-input" autocomplete="off" name="service_package_type_other" placeholder="Add your own Service Package Type here..." value="@if(!in_array($service_package_details->servicePackageType->name, $featured_listing)) {{$service_package_details->servicePackageType->name}} @endif" style="@if(in_array($service_package_details->servicePackageType->name, $featured_listing)) display: none; @endif"/>
                                            <div class="validation_error_service_package_type error-message"></div>
                                        </div>
                                         <div class="input-bx-panel tag-style skill-style">
                                             <label>Service Package Tags (minimum 3)<span class="notification-star-buyer">*</span><a class="info-icon info-icon-left-arrow">? <span> Add skills, technologies and other language to maximise findability.</span></a></label>
                                            <div class="add-skill-button-block margin-bottom-10">
                                                @php
                                                if (!empty($tags)) {
                                                    foreach ($tags as $skill) {
                                                        echo'<span class="skill-button">' . ucwords($skill) . '<a class="black_cross_link" href="javascript:void(0)"><img src=' . url("images/black_cross.png", [], $ssl) . ' alt="black_cross" class="black_cross" /></a></span>';
                                                    }
                                                }
                                                @endphp                                                <div class="addskill add-skill-edit-service-package"></div>
                                                <input  tabindex="3" type="text"  id="addskill_manually" class="skill-input"  autocomplete="off" value=""  name="addskill_manually" placeholder="e.g. JavaScript, Python, Enterprise Web Analytics"/>
                                                <input type="hidden"  id="manual_skills"  value=""  name="tags" />
                                            </div>
                                             <label class="directions-in-blue">* Use comma or enter for separate tags</label>
                                            <div class="error-message validation_error_manual_skills{{ $errors->has('tags') ? ' has-error' : '' }}"></div>
                                        </div>
                                        <div class="input-bx-panel require-info-panel">
                                                        <label class="required-fields">*required fields</label>
                                                    </div>
                                         <div class="input-bx-panel">
                                                <input id="service-package-back-1" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                                                <input id="create-package-step-2" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">
                                                <span class="muted-text">You can edit these Package details later by the way.</span>
                                            </div>
                                    </div>

                                    <div id="service-package-step-3" class="" style='display:none'>

                                        <div class="input-bx-panel deliverable-panel bottom-margin-0">
                                            <label>List the deliverables of your Package <span class="notification-star-buyer">*</span></label>

                                            @php $counter = 0; @endphp

                                            @foreach ($service_package_details->deliverables as $deliverables)
                                                @php $counter++; @endphp
                                                <textarea name="deliverables[]" value="{{$deliverables->deliverable}}" class="deliverables add_description"  placeholder="Deliverable {{$counter}}">{{$deliverables->deliverable}}</textarea>
                                            @endforeach
                                            <div class="clearfix"></div>

                                            <div class="validation_error_deliverables error-message"></div>
                                        </div>
                                        <div class="input-bx-panel top-margin-0">
                                             <a href="javascript:void(0);" class="add-deliverable-link">Add another deliverable</a> </div>

                                        <div class="input-bx-panel top-margin-0">
                                            <label>Please add what do you need from the Client <span class="notification-star-buyer">*</span></label>
                                            <textarea name="buyer_remarks" id="buyer_remarks" value="" class="add_description" maxlength="2500" placeholder="e.g. Credentials for Google Analytics" style="min-height:80px;">{{$service_package_details->buyer_remarks}}</textarea>
                                            <div class="validation_error_buyer_remarks error-message"></div>
                                        </div>
                                        <div class="input-bx-panel require-info-panel">
                                                        <label class="required-fields">*required fields</label>
                                                    </div>
                                        <div class="input-bx-panel">
                                            <input id="service-package-back-2" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                                            <input id="create-package-step-3" value="Save &amp; Continue" class="continue-btn green_gradient standard-btn" type="button">
                                            <span class="muted-text">You can edit these Package details later by the way.</span>
                                        </div>
                                    </div>

                                    <div id="service-package-step-4" style="display:none">
                                        <div class="input-bx-panel">
                                            <label id="package">Subscription Package Price (per month) </label><span class="notification-star-buyer">*</span>
                                            <div class="clearfix"></div>
                                            <div class="post-rate-input-bx">
                                                <div class="input-group post-rate">
                                                    <span class="input-group-addon">$</span>
                                                    <input tabindex="8" maxlength="7" size="30" name="price" id="price" value="{{number_format($service_package_details->price)}}" type="text" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="validation_error_price error-message"></div>
                                        </div>
                                        <div class="input-bx-panel maxiimum-time  select-dropdown-style">
                                            <label id="commitment_duration">@if($service_package_details->subscription_type == 'monthly_retainer') Expected monthly time commitment @else Expected time commitment @endif
                                            <span class="notification-star-buyer">*</span></label><a class="info-icon info-icon-left-arrow">? <span> <strong>30 day contracts</strong> Contracts are limited to 30 days or less, but easily extended on completion.</span></a>
                                            <div class="clearfix"></div>
                                            <div class="custom-dropdown-style">
                                            <select class="selectpicker" name="duration" id="duration">
                                                <option value="">Choose</option>
                                                @for ($date = 1; $date <= 30; $date++)
                                                @php $days=($date==1)?'day':'days'; @endphp
                                                <option  title="{{$date.' '.$days}}" data-content="{{$date.' '.$days}}" value="{{$date}}" @if($service_package_details->duration == $date) selected='selected' @endif @if($service_package_details->duration==1){{'day'}}@else{{'days'}}@endif>{{$date.' '.$days}}</option>
                                                @endfor
                                            </select>
                                                </div>
                                            <div class="validation_error_duration error-message"></div>
                                        </div>
                                        <div class="input-bx-panel">
                                            <div class="budget-breakdown-block" id="est_budget_div">
                                                <h3><div id="budget_breakdown">@if($service_package_details->subscription_type == 'monthly_retainer') Monthly Budget Breakdown @else Budget Breakdown @endif</div></h3>
                                                <span>85% paid to you <span class="pull-right"><span class="paid_exp">-</span><span class="sub_type"></span></span></span>
                                                <span>15% paid to MeasureMatch <span class="pull-right"><span class="paid_mm">-</span><span class="sub_type"></span></span></span>
                                            </div>
                                        </div>
                                        <div class="input-bx-panel require-info-panel">
                                                        <label class="required-fields">*required fields</label>
                                                    </div>
                                        <div class="input-bx-panel">
                                            <input id="service-package-back-3" value="Go Back" class="white-bg-btn preview white-btn" type="button">
                                            <input id="submit-preview" value="Save & Preview" class="continue-btn green_gradient standard-btn" type="button">

                                            <input tabindex="8" maxlength="7" size="30" name="publish" id="publish" value="{{$service_package_details->publish}}" type="hidden" autocomplete="off">
                                            <span class="muted-text">You can edit these Package details later by the way.</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div></div>

        </div>
    </div>



    <div id="service-package-preview"  class="modal fade bs-example-modal-lg lightbox-design package-preivew new-modal-theme" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="{{url('images/cross-black.svg',[],$ssl)}}" alt="cross"></span></button>
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
                        <input id="update_service_package" name="update_service_package" data-toggle="modal" data-target="#service-package-reivew-thankyou" value="Update Package" class="continue-btn green_gradient standard-btn" type="button">
                        <span id="save_to_draft"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{url('js/service-package.js?js='.$random_number,[],$ssl)}}"></script>
    @include('include.footer')
@endsection

