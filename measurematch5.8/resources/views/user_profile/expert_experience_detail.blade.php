<div class="employment-history content-block">
    <div class="profile-info profile-infos">
        <h4>Work history</h4>
    </div>
    <div id="appendemployment" class="seller_emp_history"> @if(isset($user_profile['user_employment_detail']) && !empty($user_profile['user_employment_detail']))
            @foreach($user_profile['user_employment_detail'] as $employee_history)
                @php
                $start_date = date('d-m-Y', strtotime($employee_history['start_date']));
                if ($start_date == '01-01-1970') {
                    $start_date = "";
                }
                $start_month_year = date('M Y', strtotime($employee_history['start_date']));
                if ($start_month_year == 'Jan 1970') {
                    $start_month_year = "";
                }
                $end_date = date('d-m-Y', strtotime($employee_history['end_date']));
                if ($end_date == '01-01-1970') {
                    $end_date = "";
                }
                $end_month_year = date('M Y', strtotime($employee_history['end_date']));
                if ($end_month_year == 'Jan 1970') {
                    $end_month_year = "";
                }
                @endphp
                <div class="profile-info profile-infos">
                    <div class="edit_view edit_work_history " data-id="{{$employee_history['id']}}"> @if(isset($employee_history['position_title']))
                            <h5>{{ ucfirst($employee_history['position_title']) }}
                                @if(empty($employee_history['start_date']) || empty($employee_history['end_date']))
                                    <a title="edit" href="javascript:void(0)" class="edit_icon edit-work-description" data-id="{{$employee_history['id']}}" >
                                        <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}">
                                    </a>
                                @else
                                    <a title="edit" href="javascript:void(0)" class="edit_icon edit-work-description" data-id="{{$employee_history['id']}}">
                                        <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}">
                                    </a>
                                @endif
                            </h5>
                        @else
                            <h5></h5>
                        @endif
                        @if(isset($employee_history['company_name']))
                            <h5 class="grey-text">{{ucfirst($employee_history['company_name'])}}</h5>
                        @else
                            <h5 class="grey-text"></h5>
                        @endif
                        @if(empty($start_month_year) AND empty($employee_history['end_date']))
                            <p class="grey-text">{{ucfirst($employee_history['location'])}} </p>
                        @endif
                        @if(!empty($start_month_year) AND !empty($employee_history['end_date']))
                            <p class="nodatatext">@if($employee_history['is_current']=='TRUE'){{$start_month_year}}-Present (
                                @php
                                $start_date = $employee_history['start_date'];
                                $end_date = $employee_history['end_date'];
                                $diff = abs(strtotime($end_date . ' +1 month') - strtotime($start_date));
                                $years = floor($diff / (365 * 60 * 60 * 24));
                                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                if ($years > 0) {
                                    printf("%d year\n", $years);
                                }
                                if ($months > 0) {
                                    printf(" %d month\n", $months);
                                }
                                @endphp
                                )
                                @else
                                    {{$start_month_year}}-{{$end_month_year}} (
                                    @php
                                    $start_date = $employee_history['start_date'];
                                    $end_date = $employee_history['end_date'];
                                    $diff = abs(strtotime($end_date . ' +1 month') - strtotime($start_date));
                                    $years = floor($diff / (365 * 60 * 60 * 24));
                                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                    if ($years > 0) {
                                        printf("%d year\n", $years);
                                    }
                                    if ($months > 0) {
                                        printf(" %d month\n", $months);
                                    }
                                    @endphp
                                    )
                                @endif
                                @endif
                                @if(!empty($start_month_year) AND !empty($employee_history['end_date']) AND !empty($employee_history['location']))
                                    | {{ucfirst($employee_history['location'])}} </p>
                        @endif
                        @if(!empty($employee_history['summary']))
                            <p class="grey-text"> {!! $employee_history['summary'] !!}</p>
                        @endif
                        @if(empty($employee_history['start_date']) || empty($employee_history['end_date']))
                            <ul>
                                <li><a href="javascript:void(0)" class="edit_view edit_work_history" data-id="{{$employee_history['id']}}">Add Time Period</a></li>
                            </ul>
                        @endif
                        @if(empty($employee_history['location']) || empty($employee_history['location']))
                            <ul>
                                <li><a href="javascript:void(0)" class="edit_view edit_work_history" data-id="{{$employee_history['id']}}">Add Location</a></li>
                            </ul>
                        @endif
                        @if(empty($employee_history['summary']) || empty($employee_history['summary']))
                            <ul>
                                <li><a href="javascript:void(0)" class="edit_view edit_work_history" data-id="{{$employee_history['id']}}">Add Description</a></li>
                            </ul>
                        @endif
                    </div>
                </div>
                <div style="display:none" id="editempmodels-{{$employee_history['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-innner-content">
                            <div class="">
                                <div class="modal-header">
                                    <h3 class="gilroyregular-bold-font">Edit Work</h3>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $start_month = date('m', strtotime($employee_history['start_date']));
                                    $start_year = date('Y', strtotime($employee_history['start_date']));
                                    if ($start_month == '01' && $start_year == '1970') {
                                        $start_month = "";
                                        $start_year = "";
                                    }
                                    $end_month = date('m', strtotime($employee_history['end_date']));
                                    $end_year = date('Y', strtotime($employee_history['end_date']));
                                    if ($end_month == '01' && $end_year == '1970') {
                                        $end_month = "";
                                        $end_year = "";
                                    }
                                    ?>
                                    <form method="post" name="editemployment" id="editemployment-{{$employee_history['id']}}" action="{{ url('editemployment',[],$ssl) }}">
                                        <input type="hidden" value="{{$employee_history['id']}}" name="empid" id="empid" >
                                        <input type="hidden" value="{{$employee_history['is_current']}}" name="empcurrent" id="empcurrent{{$employee_history['id']}}" >
                                        <input type="hidden" value="{{$start_month}}" name="empcurrent" id="original-work-start-month-{{$employee_history['id']}}" >
                                        <input type="hidden" value="{{$end_month}}" name="empcurrent" id="original-work-end-month-{{$employee_history['id']}}" >
                                        <input type="hidden" value="{{$start_year}}" name="empcurrent" id="original-work-start-year-{{$employee_history['id']}}" >
                                        <input type="hidden" value="{{$end_year}}" name="empcurrent" id="original-work-end-year-{{$employee_history['id']}}" >

                                        {{ csrf_field() }}
                                        <div class="input-bx">
                                            <label>Role title <span class="notification_star">*</span></label>
                                            <input tabindex="15" type="text" value="@if(isset($employee_history['position_title']) && !empty($employee_history['position_title'])){{ $employee_history['position_title'] }}@endif" name="employee_title" name="employee_title" id="employee_title{{$employee_history['id']}}" class="input-error-message">
                                            <span id="eemp_title_error-{{$employee_history['id']}}" class="validation_error"></span>
                                        </div>
                                        <div class="input-bx">
                                            <label>Company <span class="notification_star">*</span></label>
                                            <input tabindex="16" type="text"  name="empcompany" value="@if(isset($employee_history['company_name']) && !empty($employee_history['company_name'])){{ $employee_history['company_name'] }}@endif"  id="empcompany{{$employee_history['id']}}" class="input-error-message">
                                            <span id="eemp_company_error-{{$employee_history['id']}}" class="validation_error"></span>
                                        </div>
                                        <div class="input-bx add-time-period">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <label>From </label>
                                                    @php
                                                        $start_months='';
                                                        $current_year =date('Y');
                                                        if($start_year==$current_year){
                                                        $current_month =date('j');
                                                        $start_months = $current_month+1;
                                                        }else{
                                                        $start_months = 12;
                                                        }
                                                    @endphp
                                                    <div class="start-date-education">
                                                        <select tabindex="17" name="startMonth" class="selectpicker form-control edit-work-start-month-{{$employee_history['id']}}" id="edit_employee_start_month-{{$employee_history['id']}}">
                                                            <option value="">Month</option>
                                                            @for($i=1; $i<=$start_months; $i++)
                                                                <option value="{{ $i }}" @if($start_month == $i) selected="selected" @endif>
                                                                    {{monthName($i)}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <span id="start_month_error-{{$employee_history['id']}}" class="validation_error"></span>
                                                    </div>
                                                    <div class="end-date-education">
                                                        <select tabindex="18" name="startYear" class="selectpicker form-control edit-work-start-year-{{$employee_history['id']}}"  id="edit_employee_start_year-{{$employee_history['id']}}">
                                                            <option value="">Year</option>
                                                            @for($k=date('Y'); $k>=date('Y')-36; $k--)
                                                                <option value="{{ $k }}" @if($start_year == $k) selected="selected" @endif>
                                                                    {{$k}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <span id="start_year_error-{{$employee_history['id']}}" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <label>To </label>
                                                    <div class="start-date-education editempyear">
                                                        @php
                                                            $end_months='';
                                                            $current_year =date('Y');
                                                            if($end_year==$current_year){
                                                            $current_month =date('j');
                                                            $end_months = $current_month+1;
                                                            }else{
                                                            $end_months = 12;
                                                            }
                                                        @endphp
                                                        <select tabindex="19" name="endMonth" class="selectpicker form-control edit-work-end-month-{{$employee_history['id']}}"  id="edit_employee_end_month-{{$employee_history['id']}}">
                                                            <option value="">Month</option>
                                                            @for($j=1; $j<=12; $j++)
                                                                <option value="{{$j}}" @if($end_month == $j) selected="selected" @endif>
                                                                    {{monthName($j)}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <span id="end_month_error-{{$employee_history['id']}}" class="validation_error"></span>
                                                    </div>
                                                    <div class="editempmonth end-date-education">
                                                        <select tabindex="20" name="endYear" class="selectpicker form-control edit-work-end-year-{{$employee_history['id']}}"  id="edit_employee_end_month-{{$employee_history['id']}}">
                                                            <option value="">Year</option>
                                                            @for($l=date('Y'); $l>=date('Y')-36; $l--)
                                                                <option value="{{$l}}" @if($end_year == $l) selected="selected" @endif>{{$l}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <span id="end_year_error-{{$employee_history['id']}}" class="validation_error"></span>
                                                    </div>
                                                    <span id="someplace1{{$employee_history['id']}}"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="check-box-design">
                                            <input  type="hidden" name="hidden_eis_current" id="hidden_eis_current{{$employee_history['id']}}" >
                                            <input class="edit_current" id="{{$employee_history['id']}}" type="checkbox" @if(isset($employee_history['is_current']) && !empty($employee_history['is_current'])){{ 'checked' }} @endif value="@if(isset($employee_history['is_current']) && !empty($employee_history['is_current'])){{ 1}} @else {{ 0 }} @endif" name="eis_current" >
                                            <label><span><span></span></span>Currently Working here</label>
                                        </div>
                                        <div class="input-bx account_info new-custom-dropdown-style">
                                            <label>Location</label>
                                            <input id="emplocation-{{$employee_history['id']}}" data-employment_id="{{$employee_history['id']}}" name="emplocation" tabindex="8"  placeholder="e.g. London" value="@if(isset($employee_history['location']) && !empty($employee_history['location'])){{ $employee_history['location'] }}@endif" type="text" maxlength="40" class="employment-history-location input-error-message" autocomplete="off">
                                            <div id="tags-{{$employee_history['id']}}" class="dropdown"></div>
                                            <span id="employee_location_error-{{$employee_history['id']}}" class="validation_error"></span>
                                        </div>
                                        <div class="input-bx">
                                            <label>Description of role</label>
                                            @if(isset($employee_history['summary']) && !empty($employee_history['summary']))
                                                <textarea tabindex="23" class="textarea-section" id="empdescription" placeholder="My responsibilities included…" maxlength="2000" name="empdescription">{{ $employee_history['summary'] }}</textarea>
                                            @else
                                                <textarea tabindex="24" class="textarea-section" id="empdescription" placeholder="My responsibilities included…" maxlength="2000" name="empdescription"></textarea>
                                            @endif
                                        </div>
                                        <input tabindex="25" type="submit" class="blue-bg-btn standard-btn editemployment" name="editemployment" id="{{$employee_history['id']}}" value="Save">
                                        <a href="javascript:void(0)" class="add-grey-btn cancel-work-history pull-left" data-id="{{$employee_history['id']}}">Cancel</a>
                                        <a href="javascript:void(0)" class="cross-icon cross-work-description delete-data-btn pull-right" data-id="{{$employee_history['id']}}" >
                                            Delete
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="nodatatext">You have not entered any work history yet.</div>
        @endif
    </div>
    <div class="profile-info profile-infos">
       @php
        if (isset($user_profile['user_employment_detail']) && !empty($user_profile['user_employment_detail'])) {
            $value = "Add another position";
        } else {
            $value = "Add position";
        }
        @endphp
        <input tabindex="26" class="add-grey-btn add-expert-position" type="button" value="{{$value}}" data-toggle="modal" data-target="#myModal" id="addEduBtn">
    </div>
    <div class="addExpertPosition" style="display:none" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-innner-content">
                <div class="">
                    <div class="modal-header">
                        <h3 class="gilroyregular-bold-font">Add Work</h3>
                    </div>
                    <div class="modal-body">
                        <form method="post" name="addemployment" id="addemployment" action="{{ url('addemployment',[],$ssl) }}">
                            {{ csrf_field() }}
                            <div class="input-bx">
                                <label>Role title <span class="notification_star">*</span></label>
                                <input tabindex="27" type="text"  name="employee_title" id="employee_title" class="input-error-message">
                                <input tabindex="28" type="hidden" id="clicked_button">
                                <span id="eemp_title_error" class="validation_error"></span>
                            </div>
                            <div class="input-bx">
                                <label>Company <span class="notification_star">*</span></label>
                                <input tabindex="29" type="text"  name="employee_company"  id="employee_company" class="input-error-message">
                                <span id="eemp_company_error" class="validation_error"></span>
                            </div>
                            <div class="input-bx add-time-period">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label>From </label>
                                        <div class="start-date-education">
                                            <div class="fetch_start_month"></div>
                                            <select tabindex="30" name="startMonth" class=" selectpicker form-control add-work-from-month" id="employee_start_month">
                                                <option value="" >Month</option>
                                            </select>
                                            <span id="start_month_error" class="validation_error"></span>
                                        </div>
                                        <div class="end-date-education">
                                            <select tabindex="31" name="startYear" class="selectpicker form-control add-work-from-year"  id="employee_start_year">
                                                <option value="">Year</option>
                                                @for($k=date('Y'); $k>=date('Y')-36; $k--)
                                                    <option value="{{$k}}">{{$k}}
                                                    </option>
                                                @endfor
                                            </select>
                                            <span id="start_year_error" class="validation_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <label>To </label>
                                        <div class="start-date-education" id="empyear">
                                            <select tabindex="32" name="endMonth" class="selectpicker form-control add-work-to-month"  id="employee_end_month">
                                                <option value="">Month</option>
                                            </select>
                                            <span id="end_month_error" class="validation_error"></span>
                                        </div>
                                        <div class="end-date-education" id="empmonth">
                                            <select tabindex="33" name="endYear" class="selectpicker form-control add-work-to-year"  id="employee_end_year">
                                                <option value="">Year</option>
                                                @for($l=date('Y'); $l>=date('Y')-36; $l--)
                                                    <option value="{{$l}}">{{$l}}
                                                    </option>
                                                @endfor
                                            </select>
                                            <span id="end_year_error" class="validation_error"></span>
                                        </div>
                                    </div>
                                    <span id="someplace" class="cureently-working-here"></span>
                                </div>
                            </div>
                            <div class="check-box-design">
                                <input  type="hidden" name="is_current"  value="0">
                                <input id="is_current" type="checkbox"  name="is_current" value="1">
                                <label class="current-working-lbl" for="is_current"><span><span></span></span>Currently Working Here</label>
                            </div>
                            <div class="input-bx account_info new-custom-dropdown-style">
                                <label>Location</label>
                                <input id="add_employee_location"  name="eemplocation" tabindex="8"  placeholder="e.g. London" value="" type="text" maxlength="40" class=" input-error-message" autocomplete="off">
                                <div id="add_employee_tags" class="dropdown">
                                </div>
                                <span id="education_location_error" class="validation_error"></span>
                            </div>
                            <div class="input-bx">
                                <label>Description of role</label>
                                <textarea tabindex="36" class="textarea-section" id="eempdescription" placeholder="My responsibilities included…" maxlength="2000" name="eempdescription"></textarea>
                            </div>
                            <input tabindex="37" type="button" class="blue-bg-btn standard-btn" name="addemployment" id="add_employments" value="Save">
                            <a href="javascript:void(0)" class="cancel-btn-text gilroyregular-bold-font add-grey-btn cancel-work-history pull-left">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('js/expertEdit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/sellerprofile.js?js='.$random_number,[],$ssl)}}"></script>