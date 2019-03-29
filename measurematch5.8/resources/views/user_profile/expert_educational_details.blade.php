<div class="education-section content-block">
    <div class="education-section-content">
        <h4>College/University</h4>
        <div class="profile-info" id="appendedu"> @if(isset($user_profile['user_education_detail'])
                            && !empty($user_profile['user_education_detail']))
                @foreach($user_profile['user_education_detail'] as $education_detail)
                    @php
                    $start_date = date('d-m-Y', strtotime($education_detail['start_date']));
                    if ($start_date == '01-01-1970') {
                        $start_date = "";
                    }
                    $start_month_year = date('M Y', strtotime($education_detail['start_date']));
                    if ($start_month_year == 'Jan 1970') {
                        $start_month_year = "";
                    }
                    $end_date = date('d-m-Y', strtotime($education_detail['end_date']));
                    if ($end_date == '01-01-1970') {
                        $end_date = "";
                    }
                    $end_month_year = date('M Y', strtotime($education_detail['end_date']));
                    if ($end_month_year == 'Jan 1970') {
                        $end_month_year = "";
                    }
                    @endphp
                    <div class="edit_view edit_college" id="{{$education_detail['id']}}" data-id="{{$education_detail['id']}}">
                        <h5>{{ucfirst($education_detail['field_of_study'])}} @if(empty($start_date) || empty($end_date)) <a title="edit" href="javascript:void(0)" class="edit_icon"   data-id="{{$education_detail['id']}}">
                                <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}"></a> @else <a title="edit" href="javascript:void(0)" class="edit_icon" data-id="{{$education_detail['id']}}">
                                <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}"></a> @endif </h5>
                        <h5 class="grey-text">{{ucfirst($education_detail['name'])}}
                        </h5>
                        @if(!empty($start_date) AND !empty($end_date))
                            <p class="grey-text"> {{$start_month_year}}-{{$end_month_year}}</p>
                        @endif
                        @if(empty($education_detail['start_date']) || empty($education_detail['end_date']))
                            <ul>
                                <li><a href="javascript:void(0)" class="edit_view edit_college"  data-id="{{$education_detail['id']}}">Add Time Period</a></li>
                            </ul>
                        @endif
                    </div>
                    <div style="display:none;" class="college-id edit_college-{{$education_detail['id']}}">
                        <div class="modal-dialog" role="document">
                            <div class="modal-innner-content">
                                <?php
                                $start_month = date('m', strtotime($education_detail['start_date']));
                                $start_year = date('Y', strtotime($education_detail['start_date']));
                                if ($start_month == '01' && $start_year == '1970') {
                                    $start_month = "";
                                    $start_year = "";
                                }
                                $end_month = date('m', strtotime($education_detail['end_date']));
                                $end_year = date('Y', strtotime($education_detail['end_date']));
                                if ($end_month == '01' && $end_year == '1970') {
                                    $end_month = "";
                                    $end_year = "";
                                }
                                ?>
                                <div class="modal-body">
                                    <form method="post" name="editeducation" id="editeducation-{{ $education_detail['id'] }}" action="{{ url('editeducation',[],$ssl) }}">
                                        <input type="hidden" name="eduid" id="eduid" value="{{$education_detail['id']}}">
                                        <input type="hidden" value="{{$start_month}}" name="empcurrent" id="original-college-start-month-{{$education_detail['id']}}" >
                                        <input type="hidden" value="{{$end_month}}" name="empcurrent" id="original-college-end-month-{{$education_detail['id']}}" >
                                        <input type="hidden" value="{{$start_year}}" name="empcurrent" id="original-college-start-year-{{$education_detail['id']}}" >
                                        <input type="hidden" value="{{$end_year}}" name="empcurrent" id="original-college-end-year-{{$education_detail['id']}}" >
                                        {{ csrf_field() }}
                                        <div class="input-bx">
                                            <label>Degree <span class="notification_star">*</span></label>
                                            <input tabindex="38" type="text" placeholder="e.g Physics" name="eeduname" id="eeduname{{ $education_detail['id'] }}" value="@if(isset($education_detail['field_of_study']) && !empty($education_detail['field_of_study'])){{ $education_detail['field_of_study'] }} @endif" maxlength="50"> <span class="validation_error eeduname_error"></span>
                                        </div>
                                        <div class="input-bx">
                                            <label>College/University <span class="notification_star">*</span></label>
                                            <input tabindex="39" type="text" value="@if(isset($education_detail['name']) && !empty($education_detail['name'])){{ $education_detail['name'] }} @endif" placeholder="e.g Kings College London" name="euniversity" id="euniversity{{ $education_detail['id'] }}" maxlength="100"><span class="validation_error euniversity_error"></span>
                                        </div>
                                        <div class="input-bx add-time-period">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <label>From <span class="notification_star">*</span></label>
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
                                                        <select tabindex="40" name="startMonth" class="selectpicker form-control edit-college-start-month-{{$education_detail['id']}}" id="startMonth-{{$education_detail['id']}}">
                                                            <option value="" >Month</option>
                                                            @for($i=1; $i<=12; $i++)
                                                                <option value="{{ $i }}" @if($start_month == $i) selected="selected" @endif>
                                                                    {{monthName($i)}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="end-date-education">
                                                        <select tabindex="41" name="startYear" class="selectpicker form-control edit-college-start-year-{{$education_detail['id']}}"  id="startYear-{{$education_detail['id']}}">
                                                            <option value="">Year</option>
                                                            @for($k=date('Y'); $k>=date('Y')-67; $k--)
                                                                <option value="{{ $k }}" @if($start_year == $k) selected="selected" @endif>
                                                                    {{$k}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <span class="validation_error estartDate_error"></span>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <label>To <span class="notification_star">*</span></label>
                                                    <div class="start-date-education">
                                                        @php
                                                            $end_months='';
                                                            $end_year ='';
                                                            if(isset($education_detail['end_date']) && !empty($education_detail['end_date'])){
                                                            $end_year = date('Y', strtotime($education_detail['end_date']));
                                                            }
                                                            if(!empty($end_year) && $end_year==$current_year){
                                                            $current_month =date('j');
                                                            $end_months = $current_month+1;
                                                            }else{
                                                            $end_months = 12;
                                                            }
                                                        @endphp
                                                        <select tabindex="42" name="endMonth" class="selectpicker form-control edit-college-end-month-{{$education_detail['id']}}"  id="endMonth-{{$education_detail['id']}}">
                                                            <option value="">Month</option>
                                                            @for($j=1; $j<=12; $j++)
                                                                <option value="{{$j}}" @if($end_month == $j) selected="selected" @endif>
                                                                    {{monthName($j)}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="end-date-education">
                                                        <select tabindex="43" name="endYear" class="selectpicker form-control edit-college-end-year-{{$education_detail['id']}}"  id="endYear-{{$education_detail['id']}}">
                                                            <option value="">Year</option>
                                                            @for($l=date('Y'); $l>=date('Y')-67; $l--)
                                                                <option value="{{$l}}" @if($end_year == $l) selected="selected" @endif>{{$l}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <span class="validation_error eendDate_error" ></span>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <input tabindex="44" type="submit" id="{{$education_detail['id']}}" class="editedusubmit blue-bg-btn standard-btn" name="editedusubmit" value="Save">
                                <a href="javascript:void(0)" class="add-grey-btn cancel-edit-college" data-id="{{$education_detail['id']}}">Cancel</a>
                                <a class="cross-icon cross-college-university delete-data-btn pull-right" href="javscript:void(0)" data-id="{{$education_detail['id']}}">Delete</a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="nodatatext">Adding your educational background increases the chances of you getting hired.</div>
            @endif
            <div class="" id="add_class" style="display:none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-innner-content">
                        <div class="">
                            <div class="modal-header">
                                <h3 class="gilroyregular-bold-font">Add a college/university</h3>
                            </div>
                            <div class="modal-body">
                                <form method="post" name="addcollege" id="addcollege" action="{{ url('addeducation',[],$ssl) }}">
                                    <!--span class="validation_error"></span!--> {{ csrf_field() }}
                                    <div class="input-bx">
                                        <label>Degree <span class="notification_star">*</span></label>
                                        <input tabindex="45" type="text" placeholder="e.g Physics" name="eduname" id="eduname" maxlength="50">
                                        <span id="eduname_error" class="validation_error"></span>
                                        <input type="hidden" id="clicked_button">
                                    </div>
                                    <div class="input-bx">
                                        <label>College/University <span class="notification_star">*</span></label>
                                        <input tabindex="46" type="text" placeholder="e.g Kings College London" name="university" id="university" maxlength="100">
                                        <span id="university_error" class="validation_error"></span>
                                    </div>
                                    <div class="input-bx add-time-period">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <label>From <span class="notification_star">*</span></label>
                                                <div class="start-date-education">
                                                    <select tabindex="47" name="startMonth" class="selectpicker form-control add-college-from-month" id="startMonth">
                                                        <option value="" >Month</option>
                                                        @for($i=1; $i<=12; $i++)
                                                            <option value="{{ $i }}">
                                                                {{monthName($i)}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="end-date-education">
                                                    <select tabindex="48" name="startYear" class="selectpicker form-control add-college-from-year"  id="startYear">
                                                        <option value="">Year</option>
                                                        @for($k=date('Y'); $k>=date('Y')-67; $k--)
                                                            <option value="{{$k}}">{{$k}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <span id="start_date_error" class="validation_error"></span>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <label>To <span class="notification_star">*</span></label>
                                                <div class="start-date-education">
                                                    <select tabindex="49" name="endMonth" class="selectpicker form-control add-college-to-month"  id="endMonth">
                                                        <option value="">Month</option>
                                                        @for($i=1; $i<=12; $i++)
                                                            <option value="{{ $i }}">
                                                                {{monthName($i)}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="end-date-education">
                                                    <select tabindex="50" name="endYear" class="selectpicker form-control add-college-to-year"  id="endYear">
                                                        <option value="">Year</option>
                                                        @for($l=date('Y'); $l>=date('Y')-67; $l--)
                                                            <option value="{{$l}}">{{$l}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <span id="end_date_error" class="validation_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button tabindex="51" type="submit" class="blue-bg-btn standard-btn" name="addcollege" id="addcolleges">Save</button>
                                    <a href="javascript:void(0)" class="add-grey-btn gilroyregular-bold-font cancel-btn-text cancel-edit-college">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($user_profile['user_education_detail']) && !empty($user_profile['user_education_detail'])) {
    $value = "Add another college/university";
} else {
    $value = "Add college/university";
}
?>
<input class="add-grey-btn" id="addclgbuttom" type="button" value="{{$value}}" onClick="restform()" data-toggle="modal" data-target="#myModal3">
<div class="certificate-section content-block">
    <h4>Certificates/Courses</h4>
    <div id="appendcourses"> @if(isset($user_profile['user_certification']) && !empty($user_profile['user_certification']))
            @foreach($user_profile['user_certification'] as $certification)
                <?php
                $start_date = date('d-m-Y', strtotime($certification['start_date']));
                if ($start_date == '01-01-1970') {
                    $start_date = "";
                }
                $start_month_year = date('M Y', strtotime($certification['start_date']));
                if ($start_month_year == 'Jan 1970') {
                    $start_month_year = "";
                }
                $end_date = date('d-m-Y', strtotime($certification['end_date']));
                if ($end_date == '01-01-1970') {
                    $end_date = "";
                }
                $end_month_year = date('M Y', strtotime($certification['end_date']));
                if ($end_month_year == 'Jan 1970') {
                    $end_month_year = "";
                }
                ?>
                <div class="edit_view edit_course"   id="{{$certification['id']}}" onClick="courseid({{$certification['id']}},'{{$certification['name']}}','{{$certification['institute']}}','{{ $start_date}}','{{ $end_date}}')" data-id="{{$certification['id']}}">
                    <h5>{{ucfirst($certification['name'])}}
                        @if(empty($certification['start_date']) || empty($certification['end_date'])) <a title="edit" href="javascript:void(0)" class="edit_icon">
                            <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}"></a> @else <a title="edit" href="javascript:void(0)" class="edit_icon" data-toggle="modal" onClick="courseid({{$certification['id']}},'{{$certification['name']}}','{{$certification['institute']}}','{{ $start_date}}','{{ $end_date}}')" data-id="{{$certification['id']}}"> <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}"></a> @endif </h5>
                    <h5 class="grey-text">{{ucfirst($certification['institute'])}}</h5>
                    <p class="grey-text">{{$start_month_year}} @if(!empty($end_month_year)) - {{$end_month_year}} @endif </p>
                    @if(empty($certification['start_date']))
                        <ul>
                            <li><a href="javascript:void(0)" class="edit_view edit_course" data-id="{{$certification['id']}}">Add Time Period</a></li>
                        </ul>
                    @endif
                </div>
                <div style="display:none;" class="course-id edit_course-{{$certification['id']}}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-innner-content">
                            <div class="">
                                <div class="modal-body">
                                    <?php
                                    $start_month = date('m', strtotime($certification['start_date']));
                                    $start_year = date('Y', strtotime($certification['start_date']));
                                    if ($start_month == '01' && $start_year == '1970') {
                                        $start_month = "";
                                        $start_year = "";
                                    }
                                    ?>
                                    <form method="post" method="post" name="editcourse" id="editcourse-{{ $certification['id'] }}" action="{{ url('editcourse',[],$ssl) }}">
                                        <input type="hidden" name="courseid" id="courseid" value="{{$certification['id']}}">
                                        {{ csrf_field() }}
                                        <div class="input-bx">
                                            <label>Title<span class="notification_star">*</span></label>
                                            <input tabindex="52" type="text" placeholder="e.g Management course"  name="ecoursename" id="ecoursename{{$certification['id']}}" value="@if(isset($certification['name']) && !empty($certification['name'])){{ $certification['name'] }} @endif" maxlength="50">
                                            <input type="hidden" id="clicked_button">
                                            <input type="hidden" name="original-start-month" id="original-start-month-{{$certification['id']}}" value="{{$start_month}}">
                                            <span id="ecoursename_error_{{$certification['id']}}" class="validation_error"></span>
                                        </div>
                                        <div class="input-bx">
                                            <label>Organization <span class="notification_star">*</span></label>
                                            <input  tabindex="53"  type="text" placeholder="e.g Management London" name="einstitute" id="einstitute{{$certification['id']}}" value="@if(isset($certification['institute']) && !empty($certification['institute'])){{ $certification['institute'] }} @endif" maxlength="75">
                                            <span id="einstitute_error_{{$certification['id']}}" class="validation_error"></span>
                                        </div>
                                        <div class="input-bx add-time-period date-awarded-block">
                                            <label>Date completed</label>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 awarded-month-block">
                                                    <select tabindex="54"  name="startYear" class="selectpicker form-control edit-course-year"  id="startYearc-{{$certification['id']}}">
                                                        <option value="">Year</option>
                                                        @for($k=date('Y'); $k>=date('Y')-67; $k--)
                                                            <option value="{{ $k }}" @if($start_year == $k) selected="selected" @endif>
                                                                {{$k}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
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
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 awarded-year-block">
                                                    <select  tabindex="55"  name="startMonth" class="selectpicker form-control edit-course-month" id="startMonthc-{{$certification['id']}}">
                                                        <option value="" >Month</option>
                                                        @for($i=1; $i<=$start_months; $i++)

                                                            <option value="{{ $i }}" @if($start_month == $i) selected="selected" @endif>
                                                                {{monthName($i)}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <input  tabindex="56"  type="submit" id="{{$certification['id']}}" class="editcoursesubmit blue-bg-btn standard-btn" name="editcoursesubmit" value="Save">
                                <a href="javascript:void(0)" class="add-grey-btn cancel-edit-course cancel-btn-text gilroyregular-bold-font" data-id="{{$certification['id']}}">Cancel</a>
                                <a href="javascript:void(0)" class="cross-icon crossCourseCertificates delete-data-btn delete-btn-text pull-right" data-id="{{$certification['id']}}" >Delete</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="nodatatext">Adding any certificates/courses increases the chances of you getting hired.</div>
        @endif
        <?php
        if (isset($user_profile['user_certification']) && !empty($user_profile['user_certification'])) {
            $value = "Add another certificate/course";
        } else {
            $value = "Add certificate/course";
        }
        ?>
        <input class="add-grey-btn addCourse" type="button" value="{{$value}}" id="add_course_button">
        <div class="add-course" id="add_course" style="display:none">
            <div class="modal-dialog" role="document">
                <div class="modal-innner-content">
                    <div class="">
                        <div class="modal-header">
                            <h3 class="gilroyregular-bold-font">Add a certificate/course</h3>
                        </div>
                        <div class="modal-body">
                            <form method="post" name="addcourse" id="addcourse" action="{{ url('addcourse',[],$ssl) }}">
                                {{ csrf_field() }}
                                <div class="input-bx">
                                    <label>Title<span class="notification_star">*</span></label>
                                    <input  tabindex="57" type="text" placeholder="e.g Management course"  name="coursename" id="coursename" maxlength="50">
                                    <span id="coursename_error" class="validation_error"></span>
                                </div>
                                <div class="input-bx">
                                    <label>Organization <span class="notification_star">*</span></label>
                                    <input  tabindex="58" type="text" placeholder="e.g Management London" name="institute" id="institute" maxlength="75">
                                    <span id="institute_error" class="validation_error"></span>
                                </div>
                                <div class="input-bx add-time-period date-awarded-block">
                                    <label>Date completed</label>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 awarded-month-block">
                                            <select  tabindex="59" name="startYear" class="selectpicker form-control add-course-year"  id="startYearc">
                                                <option value="">Year</option>
                                                @for($k=date('Y'); $k>=date('Y')-67; $k--)
                                                    <option value="{{$k}}">{{$k}}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 awarded-year-block">
                                            <select tabindex="60" name="startMonth" class="selectpicker form-control add-course-month" id="startMonthc">
                                                <option value="" >Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <input tabindex="61" type="submit" class="blue-bg-btn standard-btn" name="addcourse" value="Save" id="addcourses"/>
                                <a href="javascript:void(0)" class="add-grey-btn cancel-edit-course cancel-btn-text gilroyregular-bold-font">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('js/expertEdit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/sellerprofile.js?js='.$random_number,[],$ssl)}}"></script>
<script type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>