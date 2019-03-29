@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="active buyerdesktop_buyer">
        <div id="page-content-wrapper">
            <div class="page-content inset">
                <div class="col-md-3 leftSidebar settingleftmenu">
                    @include('buyer.sidemenu')
                </div>
                <link href="{{ url('css/international-phone-codes.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
                <div class="col-md-12 rightcontent-panel">
                    <a href="javascript:void(0)" id="back_button" class="gilroyregular-bold-font go-back-btn font-14" onclick="goBackButton()" title="Go back">Go back</a>
                    @include('include.footer_left')
                    <div class="theiaStickySidebar margin-top-10">

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="row">
                                <div class="my_acc_sidebar">
                                    <ul class="nav nav-tabs tabs-left">
                                        <li class="@if(session()->has('buyerAccountsection')==''){{'active'}}@endif"><a href="#basicinformation" title="Basic information" data-toggle="tab">Personal Information</a></li>
                                        <li><a href="#baddress" title="Business address" data-toggle="tab">Business Information</a></li>
                                        <li><a href="#ainfo" title="Account information" data-toggle="tab">Account information</a></li>
                                        <li><a href="#contracts_area" title="Contracts" data-toggle="tab">Contracts</a></li>
                                        <li><a href="#passwordchange" title="Password" data-toggle="tab">Password</a></li>
                                        <li><a href="#communication" title="Communications" data-toggle="tab">Communications</a></li>
                                        <li><a href="#my_data" title="My data" data-toggle="tab">My data</a></li>
                                        <li><a href="#delete_account_tab" title="Archive account" data-toggle="tab">Archive/Delete account</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 pull-right">
                            <div class="profile_my_account tab-content">
                                <div  id="success_msg_expert_edit">
                                    @if(session()->has('status'))
                                        <div class="bg-success" >
                                            {!! session()->get('status') !!}
                                        </div>
                                    @endif
                                </div>
                                @if(isset($status) && !empty($status))
                                    {{ $status }}
                                @endif
                                <div class="basic-information profile-page-content tab-pane @if(session()->has('buyerAccountsection')==''){{'active'}}@endif" id="basicinformation">
                                    <div class="rectangle col-md-12">
                                        <h5>Personal Information</h5>
                                        <p>Please keep your personal information updated here.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <span class="validation_error error-message"></span>@if(isset($user_data) && (!empty($user_data)))
                                            <form name="editbuyerbasic" id="editbuyerbasic" method="post" action="{{ url('updatebuyerbasic',[],$ssl) }}">
                                                {{ csrf_field() }}
                                                <div class="input-bx account_info">
                                                    <label>First name <span class="notification-star-buyer">*</span></label>
                                                    <input tabindex="1" maxlength="50" size="30" placeholder="e.g. Tom" type="text" name="first_name" value="{{ ucwords($user_data[0]['buyer_profile']['first_name']) }}" id="buyer_fname">
                                                    <div class="error-message" id="buyer_fname_error"></div>
                                                    @if ($errors->has('first_name'))
                                                        <span class="validation_error">{{ $errors->first('first_name') }}</span>
                                                    @endif
                                                </div>
                                                <div class="input-bx">
                                                    <label>Last name <span class="notification-star-buyer">*</span></label>
                                                    <input tabindex="2" maxlength="50" size="30" placeholder="e.g. Ray" type="text" name="last_name" value="{{ ucwords($user_data[0]['buyer_profile']['last_name']) }}" id="buyer_lname">
                                                    <div class="error-message" id="buyer_lname_error"></div>
                                                </div>
                                                <div class="input-bx account_info">
                                                    <label>Email <span class="notification-star-buyer">*</span></label>
                                                    <input tabindex="3" maxlength="75" size="30" placeholder="e.g. tom@ucreate.it" type="text" name="email" value="{{ $user_data[0]['email'] }}" id="buyer_email" >
                                                    <div class="error-message" id="buyer_email_error">
                                                        @if ($errors->has('email'))
                                                           {{ $errors->first('email') }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="input-bx">
                                                    <label>Your phone number <span class="notification-star-buyer">*</span>
                                                    </label>
                                                    <input type="hidden" name="country_code" id="country_code"/>
                                                    <input tabindex="4" maxlength="25" size="30" placeholder="e.g. 07965387625" type="text" name="phone_num" value="{{ $user_data[0]['phone_num'] }}" id="buyer_phone">
                                                    <div class="error-message" id="buyer_phone_error"></div>
                                                </div>
                                                    <div class="input-bx">
                                                        <input tabindex="5" type="button" class="btn btn-default standard-btn" value="Update" id="submit_btn">
                                                    </div>
                                                <div class="input-bx">
                                                <p class="required-text">* Required fields</p>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="basic-information buisness-address tab-pane" id="baddress">
                                    <div class="rectangle col-md-12">
                                        <h5>Business Information</h5>
                                        <p>Please keep your business information updated here.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        @include('business_information.business_details')
                                    </div>
                                </div>

                                <div class="basic-information mm-acc-info-block tab-pane" id="ainfo">
                                    <div class="rectangle col-md-12">
                                        <h5>Account information</h5>
                                        <p>Customise your account.</p>
                                    </div>

                                    <div class="col-md-12 info-right-side buyer_account_info">
                                        <div class="input-bx">
                                            <label>Unique MeasureMatch number</label>
                                            <h3 class="mm-number">{{ $user_data[0]['mm_unique_num'] }}</h3>
                                        </div>

                                        <form name="buyer_account_info" id="buyer_account_info" method="post" action="{{ url('updatebuyeraccountinfo',[],$ssl) }}">
                                            {{ csrf_field() }}
                                            <div class="select-cate-block gilroyregular-font">
                                                <label>Privacy</label>
                                                <div class="check-box-design">
                                                    <input type="hidden" id="type_of_org_exists" value="<?php echo (!empty($user_data[0]['buyer_profile']['type_of_organization_id'])) ? $user_data[0]['buyer_profile']['type_of_organization_id'] : ''; ?>">
                                                    <input tabindex="33" id="hide_company_from_projects" type="checkbox" name="hide_company_name" @if($user_data[0]['buyer_profile']['hide_company_name'] =="1")  checked="true" value="1" @endif>
                                                    <label for="hide_company_from_projects" ><span><span></span></span>Hide my company name from the platform</label>
                                                </div>

                                                <div class="clearfix margin-bottom-20 content-align-left">
                                                    <div style="display:none" class="account-notificatoin error-message">You must provide your organization type if you wish to hide your company name from the platform.</div>
                                                </div>
                                            </div>
                                            <div class="input-bx">
                                                <input tabindex="34" type="button" class="btn btn-default standard-btn" value="Save" id="submit_buyer_account">
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="basic-information mm-acc-info-block tab-pane" id="contracts_area">
                                    <div class="rectangle col-md-12">
                                        <h5>Contracts</h5>
                                        <p>A record of all your MeasureMatch contracts.</p>
                                    </div>

                                    <div class="col-md-12 info-right-side margin-b-32">
                                        @if(_count($contracts))
                                            @foreach($contracts as $contract)
                                                <div class="contract-list-panel">
                                                    <div class="col-lg-6 col-md-6 col-sm-5 col-xs-5 text-align-left contract-list-info-panel">
                                                        <h5 class="gilroyregular-bold-font font-16">@if($contract->type =='project'){{getjob($contract->job_post_id,1,48)}} @else{{getServicePackageName($contract->service_package_id,1,48)}} @endif</h5>
                                                        @if($contract->is_extended){{totalContractExtentionCount($contract->communications_id).' contracts'}}
                                                        @else
                                                            <span class="font-12">{{date('j M Y', strtotime($contract->job_start_date))}} - @if(!empty($contract->job_end_date)){{date('j M Y', strtotime($contract->job_end_date))}}@else Rolling monthly @endif</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7 text-align-right contract-action-panel">
                                                        <a class="view-buyer-contract font-14" contract-id="{{$contract->id}}" buyer-id="{{$contract->buyer_id}}" user-id="{{$contract->user_id}}" communication-id="{{$contract->communications_id}}" type="{{$contract->type}}" project-id="{{$contract->job_post_id}}" service-package-id="{{$contract->service_package_id}}" href="javascript:void(0)">View @if(!$contract->is_extended) Contract @else Contracts @endif</a>
                                                        @if($contract->subscription_type!='monthly_retainer')
                                                            @if((!$contract->is_extended && $contract->status == config('constants.ACCEPTED')) || ($contract->is_extended && acceptedContractsCount($contract->id)==1))
                                                                <a id="download_contract" class="font-14" href="{{ url("contract/$contract->id/download",[],$ssl) }}" target="_blank">Download Contract</a>
                                                            @else
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-align-right contract-action-panel pull-right keyboard-control dropup open">
                                                                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Download Contract">
                                                                        Download Contract
                                                                    </a>
                                                                    <ul class="dropdown-menu" aria-labelledby="drop_down_menu">
                                                                        <li><a href="javascript:void(0)" parent_contract_id="{{$contract->id}}" class="all-contracts">Download all Contracts</a></li>
                                                                        @if(isset($contract->communication['extensionContracts']))
                                                                            @foreach($contract->communication['extensionContracts'] as $extension_contract)
                                                                                @if($extension_contract->status == config('constants.ACCEPTED'))
                                                                                    <li><a href="{{ url("contract/$extension_contract->id/download",[],$ssl) }}" target="_blank" class="contract_extensions-{{$contract->id}}">Download {{$extension_contract->alias_name}}</a></li>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="gilroyregular-bold-font font-16">You haven't got into any contracts yet.</span>
                                            <div class="clearfix"></div>
                                            <a href="{{url('project/create',[],$ssl)}}" class="info-save-btn post-project-btn margin-top-20 standard-btn" title="Submit a Project">Submit a Project</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block tab-pane" id="passwordchange">
                                    <div class="rectangle col-md-12">
                                        <h5>Password</h5>
                                        <p>Change your password or recover your current one.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <form method="post" action="{{ url('updateBuyerPassword',[],$ssl) }}" id="update_buyer_pwd_form" name="update_buyer_pwd_form">
                                            {{ csrf_field() }}
                                            <div class="input-bx">
                                                <label>Current password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="35" maxlength="30" size="30" type="password" name="old_password" id="old_password">
                                                <div class="error-message" style="display: none;" id="validation_error_existing_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <label>New password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="36" maxlength="30" size="30" type="password" name="new_password" id="new_password">
                                                <div class="error-message" style="display: none;" id="validation_error_reset_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <label>Confirm new password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="37" maxlength="30" size="30" type="password" name="confirm_password" id="confirm_password">
                                                <div class="error-message" style="display: none;" id="validation_error_confirm_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <button tabindex="38" type="button" class="blue-bg-btn standard-btn" id="buyer_update_pass_btn">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block tab-pane" id="communication">
                                    <div class="rectangle col-md-12">
                                        <h5>Communications</h5>
                                        <p>When these options are selected, you will be updated via email.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <div class="input-bx">
                                            <label>You will receive an email when:</label>
                                            <form name="editbuyer_communication" id="editbuyer_communication" method="post" action="{{ url('updateBuyerCommunication',[],$ssl) }}">
                                                {{ csrf_field() }}
                                                <div class="select-cate-block gilroyregular-font">
                                                    <div class="check-box-design">
                                                        <input id="user_communication_1" type="checkbox" name="user_communication[]" value="1" @if(!empty($user_communication[0]) && ($user_communication[0]=='1'))  checked="true" @endif >
                                                        <label for="user_communication_1" ><span><span></span></span>You have completed an action on the website</label>
                                                        <div class="error-message" style="display: none;" id="seller_communication_error"></div>
                                                    </div>
                                                    <div class="input-bx">
                                                        <a href="javascript:void()" class="info-save-btn standard-btn" id="save_buyer_communication">Save</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block delete-acc-btn my-data-section tab-pane" id="my_data">
                                    <div class="rectangle col-md-12">
                                        <h5>My data</h5>
                                        <p>You can request a download of your data here.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side buyer_account_info">
                                        <div class="input-bx">
                                            <h3 class="mm-number">Request a download of my data</h3>
                                            <p class="font-14">Send a request to the MeasureMatch team for a download of your data as a csv file. It can take 3-5 business days for your request to be handled.</p>
                                        </div>
                                        <div class="input-bx">
                                            <a href="javascript:void()" class="info-save-btn standard-btn margin-bottom-10" id="request_data_download">Request a download of my data</a>
                                            <span class="success-message gilroyregular-bold-font margin-top-10" id="download_data_success_message"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block delete-acc-btn my-data-section tab-pane" id="delete_account_tab">
                                    <div class="rectangle col-md-12">
                                        <h5>Archive/Delete account</h5>
                                        <p>You can archive or delete your account here.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side buyer_account_info">
                                        <div class="input-bx">
                                            <h3 class="mm-number">Archive account</h3>
                                            <p class="font-14">By archiving your account, we keep your records in our database in-case you'd like to return to us one day.</p>
                                            <input type="button" class="btn btn-default pull-left margin-bottom-30 standard-btn delete-account-button" value="Archive account" id="delete_account">
                                        </div>
                                        <div class="input-bx">
                                            <h3 class="mm-number">Request to delete my account</h3>
                                            <p class="font-14">Send a request to the MeasureMatch team for your account to be deleted. It can take 3-5 business days for your request to be handled.</p>
                                        </div>
                                        <div class="input-bx">
                                            <a href="javascript:void(0);" class="info-save-btn request-btn standard-btn margin-bottom-20" id="request_to_delete_account">Request account deletion</a>
                                            <span class="success-message gilroyregular-semibold" id="delete_account_success_message"></span>
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
    <!-- /.content -->

    <div class="modal fade profile-page-popup delete-account-popup change-psw-popup lightbox-design lightbox-design-small" id="archive_buyer_account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-innner-content">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
              <span aria-hidden="true">
                      <img  alt="cross" src="{{url('images/cross-black.svg',[],$ssl)}}">
              </span>
                    </div>
                    <div class="modal-body text-align-left">
                        @if(notAllowUserArchive() == 0)
                            <h3 class="gilroyregular-bold-font font-24 text-align-center">Archive account</h3>
                            <p class="margin-bottom-10 font-14"> By confirming you wish to archive your account you will no longer have access to the MeasureMatch platform. Your account will become inactive but we may keep a record of your activity.</p>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-align-center">
                                <button id="noediting" class="blue-bg-btn green-gradient  standard-btn" data-dismiss="modal">Cancel</button>
                                <button id="yes_delete" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">Confirm</button>
                            </div>
                        @else
                            <h3 class="text-align-center">Archiving has been disabled for your account</h3>
                            <p class="font-14">After completing the following actions in the MeasureMatch platform, you are not permitted to archive your account:</p>
                            <p>
                                - Express Interest in a Service Package </br>
                                - Post a project
                            </p>
                            <p>Please get in touch with <a class="gilroyregular-bold-font" href="mailto:contact@measurematch.com">contact@measurematch.com</a> for more information.<p/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common_pop_ups.request_my_data_confirmation')
    @include('common_pop_ups.delete_my_account_confirmation')
    <div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_contract_preview" class="modal mark_completed_project got-match-popup seller-contract-popup invite-seller-popup lightbox-design-small lightbox-design fade in">
    </div>
    @include('include.basic_javascript_liberaries')
    <script src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/international-phone-codes.js?js='.$random_number,[],$ssl) }}"></script>
    @include('include.footer')
    <script src="{{ url('js/basicinfo.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/custom.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/business_information.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{ url('js/common_settings_page.js?js='.$random_number,[],$ssl) }}"></script>
@endsection
