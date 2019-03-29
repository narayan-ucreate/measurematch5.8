@extends('layouts.expert_layout')
@section('content')
    <link href="{{ url('css/international-phone-codes.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
    <div id="wrapper" class="active profile-page-content">
        <div id="page-content-wrapper">
            <div class="page-content inset">


                <div class="col-md-12 rightcontent-panel">
                    <a href="javascript:void(0)" id="back_button" class="gilroyregular-bold-font go-back-btn font-14" onclick="goBackButton()" title="Go back">Go back</a>
                    @include('include.footer_left')
                    <div class="theiaStickySidebar margin-top-10">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="row">
                                <div class="my_acc_sidebar">
                                    <ul class="nav nav-tabs tabs-left">
                                        <li class="@if(session()->has('accountInformationExist')==''){{'active'}}@endif">
                                            <a href="#basicinformation" data-toggle="tab" title="Basic Information">Personal Information</a>
                                        </li>
                                        <li><a href="#baddress" title="Business Information" data-toggle="tab">Business Information</a></li>
                                        <li><a href="#binfo" title="Account Information" data-toggle="tab">Account Information</a></li>
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
                                    @if(session()->has('warning'))
                                        <div class="bg-success">
                                            {!! session()->get('warning') !!}
                                        </div>
                                    @endif
                                </div>

                                <div class="basic-information tab-pane @if(session()->has('accountInformationExist')==''){{'active'}}@endif" id="basicinformation">
                                    <div class="rectangle col-md-12">
                                        <h5>Personal Information</h5>
                                        <p>Please keep your personal information updated here.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <form name="editseller" id="editseller" method="post" action="{{ url('updateselleraccount',[],$ssl) }}">
                                            {{ csrf_field() }}
                                            <div class="input-bx account_info">
                                                <label>First name <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="1" maxlength="50" size="30" placeholder="e.g. Tom" type="text" name="name" value="{{ ucwords($user_data->name) }}" id="seller_fname">
                                                <div class="error-message" id="seller_fname_error"></div>
                                                @if ($errors->has('name'))
                                                    <span class="validation_error">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                            <div class="input-bx">
                                                <label>Last name <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="2" maxlength="50" size="30" placeholder="e.g. Ray" type="text" name="last_name" value="{{ ucwords($user_data->last_name) }}" id="seller_lname">
                                                <div class="error-message" id="seller_lname_error"></div>
                                            </div>

                                            <div class="input-bx account_info">
                                                <label>Email <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="3" maxlength="75" size="30" placeholder="e.g. tom@ucreate.it" type="text" name="email" value="{{ $user_data->email }}" id="seller_email" >
                                                <div class="error-message" id="seller_email_error"></div>
                                            </div>

                                            <div class="input-bx">
                                                <label>Your phone number <span class="notification-star-buyer">*</span></label>
                                                <input type="hidden" name="country_code" id="country_code"/>
                                                <input tabindex="4" maxlength="25" size="30" placeholder="e.g. 07965387625" type="text" name="phone_num" value="{{ $user_data->phone_num }}" id="seller_phone">
                                                <div class="error-message" id="seller_phone_error"></div>
                                            </div>

                                            <div class="input-bx">
                                                <label>Your Date of Birth
                                                </label>
                                                <div class="date-of-birth-block">
                                                    <div class="field-container">
                                                        <select name="dob_month" class="dob-month selectpicker">
                                                            <option value="">Month</option>
                                                            <option value="01">January</option>
                                                            <option value="02">February</option>
                                                            <option value="03">March</option>
                                                            <option value="04">April</option>
                                                            <option value="05">May</option>
                                                            <option value="06">June</option>
                                                            <option value="07">July</option>
                                                            <option value="08">August</option>
                                                            <option value="09">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>
                                                        </select>
                                                    </div>
                                                    <div class="field-container">
                                                        <select name="dob_day" class="dob-day selectpicker">
                                                            <option value="">Day</option>
                                                        </select>
                                                    </div>
                                                    <div class="field-container">
                                                        <select name="dob_year" class="dob-year selectpicker">
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="date_of_birth" value="{{ $user_data->date_of_birth != '' ? date('d-m-y', strtotime($user_data->date_of_birth)) : '' }}" id="date_of_birth">
                                                </div>
                                            </div>
                                            <div class="input-bx">
                                                <input tabindex="5" type="button" class="info-save-btn standard-btn" value="Update" id="submit_btn">
                                            </div>
                                            <div class="input-bx">
                                                <p class="required-text">* Required fields</p>
                                            </div>

                                        </form>
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
                                <div class="basic-information mm-acc-info-block tab-pane" id="binfo">
                                    <div class="rectangle col-md-12">
                                        <h5>Account Information</h5>
                                        <p>Customise your account.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <div class="input-bx">
                                            <label>Unique MeasureMatch number</label>
                                            <h3 class="mm-number">{{ $user_data->mm_unique_num }}</h3>
                                        </div>
                                        <form name="seller_account_info" id="seller_account_info" method="post" action="{{ url('updateselleraccountinfo',[],$ssl) }}">
                                            {{ csrf_field() }}
                                            <div class="select-cate-block">
                                                <label>Privacy</label>
                                                <div class="check-box-design">
                                                    <input id="platform" type="checkbox" name="hide_profile" @if($user_data->hide_profile =="1")  checked="true" value="1"
                                                            @endif>
                                                    <label for="platform" ><span><span></span></span>Hide my profile</label>
                                                </div>
                                            </div>
                                            <div class="input-bx">
                                                <input type="button" class="info-save-btn standard-btn" value="Save" id="submit_expert_account">
                                            </div>

                                        </form>

                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block tab-pane" id="contracts_area">
                                    <div class="rectangle col-md-12">
                                        <h5>Contracts</h5>
                                        <p>A record of all your MeasureMatch contracts.</p>
                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        @if(_count($contracts))
                                            @foreach($contracts as $contract)
                                                <div class="contract-list-panel">
                                                    <div class="col-md-6 col-xs-6 text-align-left contract-list-info-panel">
                                                        <h5 class="gilroyregular-bold-font font-16">@if($contract->type =='project'){{getjob($contract->job_post_id,1,48)}} @else{{getServicePackageName($contract->service_package_id,1,48)}} @endif</h5>
                                                        @if($contract->is_extended){{totalContractExtentionCount($contract->communications_id).' contracts'}}
                                                        @else
                                                            <span class="font-12">{{date('j M Y', strtotime($contract->job_start_date))}} - @if(!empty($contract->job_end_date)){{date('j M Y', strtotime($contract->job_end_date))}}@else Rolling monthly @endif</span>
                                                        @endif
                                                    </div>


                                                    <div class="col-md-6 col-xs-6 text-align-right contract-action-panel">
                                                        <a class="view-expert-contract font-14 gilroyregular-bold-font" contract-id="{{$contract->id}}" buyer-id="{{$contract->buyer_id}}"
                                                           user-id="{{$contract->user_id}}" communication-id="{{$contract->communications_id}}"
                                                           href="javascript:void(0)">View @if(!$contract->is_extended) Contract @else Contracts @endif
                                                        </a>
                                                        @if($contract->subscription_type!='monthly_retainer')
                                                            @if((!$contract->is_extended && $contract->status == config('constants.ACCEPTED')) || ($contract->is_extended && acceptedContractsCount($contract->id)==1))
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-align-right contract-action-panel pull-right keyboard-control">
                                                                    <a id="download_contract" class="font-14" href="{{ url("contract/$contract->id/download",[],$ssl) }}" target="_blank">Download Contract</a>
                                                                </div>
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
                                            <a href="{{url('expert/projects-search',[],$ssl)}}"
                                               class="info-save-btn  btn-middle-align margin-top-20 standard-btn
                                            @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
                                                @if(Auth::user()->admin_approval_status!=1)
                                                       expert_profile_admin_unapproved
                                                @endif
                                            @else
                                                       expert_profile_incomplete
                                            @endif" title="Browse Projects">Browse Projects</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="basic-information mm-acc-info-block tab-pane" id="passwordchange">
                                    <div class="rectangle col-md-12">

                                        <h5>Password</h5>
                                        <p>Change your password or recover your current one.</p>

                                    </div>
                                    <div class="col-md-12 info-right-side">
                                        <form method="post" action="{{ url('updateExpertPassword',[],$ssl) }}" id="update_expert_pwd_form" name="update_expert_pwd_form">
                                            {{ csrf_field() }}

                                            <div class="input-bx">
                                                <label>Current password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="34" maxlength="30" size="30" type="password" name="old_password" id="old_password">
                                                <div style="display: none;" class="error-message" id="validation_error_existing_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <label>New password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="35" maxlength="30" size="30" type="password" name="new_password" id="new_password">
                                                <div style="display: none;" class="error-message" id="validation_error_reset_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <label>Confirm new password <span class="notification-star-buyer">*</span></label>
                                                <input tabindex="36" maxlength="30" size="30" type="password" name="confirm_password" id="confirm_password">
                                                <div style="display: none;" class="error-message" id="validation_error_confirm_password"></div>
                                            </div>
                                            <div class="input-bx">
                                                <button tabindex="37" type="button" class="info-save-btn standard-btn" id="expert_update_pass_btn">Save</button>
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
                                        </div>
                                        <form name="editseller_communication" id="editseller_communication" method="post" action="{{ url('updatesellerCommunication',[],$ssl) }}">
                                            {{ csrf_field() }}
                                            <div class="select-cate-block">
                                                <div class="check-box-design">
                                                    <input tabindex="38" id="user_communication_1" type="checkbox" name="user_communication[]" value="1" @if(!empty($user_communication[0]) && ($user_communication[0]=='1'))  checked="true"
                                                            @endif >
                                                    <label for="user_communication_1" ><span><span></span></span>You have completed an action on the website</label>
                                                </div>

                                                <div style="display: none;" class="error-message" id="seller_communication_error"></div>
                                            </div>
                                            <div class="input-bx">
                                                <a href="javascript:void()" class="info-save-btn standard-btn" id="save_seller_communication">Save</a>
                                            </div>
                                        </form>
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
                                            <a href="javascript:void()" class="info-save-btn standard-btn" id="request_data_download">Request a download of my data</a>
                                            <span class="success-message margin-top-10 gilroyregular-bold-font" id="download_data_success_message"></span>
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

                                            <input type="button" class="btn btn-default standard-btn delete-account-button pull-left margin-bottom-30" value="Archive account" id="delete_account">
                                        </div>
                                        <div class="input-bx">
                                            <h3 class="mm-number">Request to delete my account</h3>
                                            <p class="font-14">Send a request to the MeasureMatch team for your account to be deleted. It can take 3-5 business days for your request to be handled.</p>
                                        </div>
                                        <div class="input-bx">
                                            <a href="javascript:void(0);" class="info-save-btn standard-btn margin-bottom-20" id="request_to_delete_account">Request account deletion</a>
                                            <span class="success-message gilroyregular-semibold" id="delete_account_success_message"></span>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div></div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade profile-page-popup delete-account-popup change-psw-popup  lightbox-design lightbox-design-small" id="confirm_cancellation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">

            <div class="modal-innner-content">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img  alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                    </div>
                    @if(notAllowUserArchive() == 0)

                        <div class="modal-body text-align-left">

                            <h3 class="gilroyregular-bold-font font-24 text-align-center">Archive account</h3>
                            <p class="margin-bottom-20 font-14">By confirming you wish to archive your account you will no longer have access to the MeasureMatch platform. Your account will become inactive but we may keep a record of your activity.</p>
                            <span id="imageerrormsg" class="error-message"></span>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-align-center">
                                <button id="noediting" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">Cancel</button>
                                <button id="yesediting" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">Confirm</button>
                            </div>

                        </div>
                    @else

                        <div class="modal-body text-align-left">
                            <h3 class="gilroyregular-bold-font font-24 text-align-center">Archiving has been disabled for your account</h3>
                            <p class="margin-bottom-10 font-14">After completing the following actions in the MeasureMatch platform, you are not permitted to archive your account:</p>
                            <span id="imageerrormsg" class="error-message"></span>
                            <p>
                                - You've created a Service Package<br/>
                                - You've expressed interest in a project<br/>
                                - You've been invited to discuss a project by a Client
                            </p>
                            <p>Please get in touch with <a href="mailto:contact@measurematch.com" class="gilroyregular-bold-font">contact@measurematch.com</a> for more information.<p/>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('common_pop_ups.request_my_data_confirmation')
    @include('common_pop_ups.delete_my_account_confirmation')
    <div id="view_contract_popup"></div>
    @include('include.basic_javascript_liberaries')
    <script>
        var birth_date = '{{$birth_day}}';
        var birth_month = '{{$birth_month}}';
        var birth_year = '{{$birth_year}}';
    </script>
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/international-phone-codes.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/course.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/date_of_birth_dropdowns.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
    <script src="{{url('js/selleracc.js?js='.$random_number,[],$ssl)}}"></script>
    <script src="{{ url('js/business_information.js?js='.$random_number,[],$ssl) }}"></script>
    <script src="{{url('js/common_settings_page.js?js='.$random_number,[],$ssl)}}"></script>
    @include('include.footer')
    @if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
        @if(Auth::user()->admin_approval_status!=1)
            @include('expert_profile_admin_unapproved_modal')
        @endif
    @else
        @include('expert_profile_incomplete_modal')
    @endif
@endsection
