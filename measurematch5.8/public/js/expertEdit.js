var country_name = '';

function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 100)
    }, 400);
}
function month_names() {
    var month = [];
    month[1] = "January";
    month[2] = "February";
    month[3] = "March";
    month[4] = "April";
    month[5] = "May";
    month[6] = "June";
    month[7] = "July";
    month[8] = "August";
    month[9] = "September";
    month[10] = "October";
    month[11] = "November";
    month[12] = "December";
    var month_option = '';
    month_option += '<option value="">Month</option>';
    for (var i = 1; i <= 12; i++) {
        month_option += '<option value="' + i + '">' + month[i] + '</option>'
    }
    return month_option;
}

function replaceStopEditingWithEditIcon(){
    $('.stop_editing').removeClass('stop_editing');
    $('.edit_icon_removed').addClass('edit_icon');
    $('.edit_icon').removeClass('edit_icon_removed');
}

function removeStopEditingFromNav(){
    $('#summary_a').removeClass('stop_editing');
    $('#skill_a').removeClass('stop_editing');
    $('#workhistory_a').removeClass('stop_editing');
    $('#education_a').removeClass('stop_editing');
    $('#myTab li').not('.active').find('a').attr("data-toggle", "tab");
};

$(document).ready(function (e) {
    var default_expert_type = $('#expert_type').val();
    month_option = month_names();

    $('#employee_start_month').html(month_option).selectpicker('refresh');
    $('#employee_end_month').html(month_option).selectpicker('refresh');

    var edited_summary_data = {
        expert_type: $("#expert_type").val().trim(),
        describe: $('#describe').val().trim(),
        expert_profile_city: $("#expert_profile_city").val().trim(),
        daily_rate: $("#daily_rate").val(),
        remote_work: $("#remote_work").val(),
        expert_count: $('#experts_count').val()
    };

    $('.edit_work_history').on('click', function () {
        var form_data_id = $(this).attr('data-id');

    });

    /*course date dropdown*/
    $('.edit_course').on('click', function () {
        var form_data_id = $(this).attr('data-id');
        $('#startYearc-' + form_data_id).on('change', function () {
            var selected_start_year = $('#startYearc-' + form_data_id).val();
            if (selected_start_year == "") {
                $(".validation_error").text("Please enter start year");
                $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                $('#startMonthc-' + form_data_id).html('<option value="" >Month</option>').selectpicker('refresh');
                return false;
            } else {
                var current_year = new Date().getFullYear();
                if (current_year == selected_start_year) {
                    var current_month = new Date().getMonth() + 1;

                } else {
                    var current_month = 12;
                }
                month_option = month_names();
                $('#startMonthc-' + form_data_id).html(month_option).selectpicker('refresh');
            }
        });
    });
    $('#startYearc').on('change', function () {
        var selected_start_year = $('#startYearc').val();
        if (selected_start_year == "") {
            $(".validation_error").text("Please enter start year");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            $('#startMonthc').html('<option value="" >Month</option>').selectpicker('refresh');
            return false;
        } else {
            var current_year = new Date().getFullYear();
            if (current_year == selected_start_year) {
                var current_month = new Date().getMonth() + 1;
            } else {
                var current_month = 12;
            }
            month_option = month_names();
            $('#startMonthc').html(month_option).selectpicker('refresh');
        }
    });

    $('.edit-work-description').on('click', function (e) {
        autosize(document.querySelectorAll('textarea#empdescription'));
    });

    $('.add-expert-position').on('click', function (e) {
        $('#employee_title').focus();
        autosize(document.querySelectorAll('textarea#eempdescription'));
    });
    $('.edit-profile-section').on('click', function (e) {
        if ($(this).hasClass('stop_editing')) {
            $('.remove_view').show();
           $('#edit_profile').hide();
        } else {
            $('.remove_view').hide();
            $('#edit_profile').show();
        }
    });
    $('.editProfile').on('click', function (e) {
        $('.remove_view').show();
        $('#edit_profile').hide();
    });
    $(document).on('click touchstart', '.edit_bio, .edittextarea', function (e) {
        $("textarea#bio").focus();
        autosize(document.querySelectorAll('textarea#bio'));
        $('.edit_view:visible').removeClass('edittextarea');
        if ($(this).hasClass('stop_editing')) {
            $('.remove_bio').show();
            $('.edit_bio_expert').hide();
        } else {
            $('.remove_bio').hide();
            $('.edit_bio_expert').show();
        }
        addStopEditingClass();
    });
    $(document).on('click', '.cancel_profile', function (e) {
        removeStopEditingFromNav();
        $('.edit-profile-section').removeClass('stop_editing');
        $('#edit_profile').css('display', 'none');
        $('.remove_view').css('display', 'block');
        $('.stop_editing').addClass('edit_icon');
        $('.add-bio-content .edit_view').removeClass('edit_icon');
        $('.stop_editing').removeClass('stop_editing');
    });
    $('.cancel-bio').on('click', function (e) {
        removeStopEditingFromNav();
        setTimeout(function(){
            $('.remove_bio').show();
            $('.edit_bio_expert').hide();
            $("#editbioform")[0].reset();
            replaceStopEditingWithEditIcon();
            $('.add-bio-content .edit_view').addClass('edittextarea');
        }, 10);
    });
    $('.edit_work_history').on('click', function (e) {
        var data_id = $(this).attr('data-id');
        if ($(this).hasClass('stop_editing')) {
            $('#editempmodels-' + data_id).show();
            $('.profile-infos').hide();
        } else {
            $('#editempmodels-' + data_id).show();
            $('.profile-infos').hide();
        }
    });
    $('.cancel-work-history').on('click', function (e) {
        removeStopEditingFromNav();
        var data_id = $(this).attr('data-id');
        $('#editempmodels-' + data_id).hide();
        $('.addExpertPosition').hide();
        $('.profile-infos').show();
        if(typeof data_id !== 'undefined'){
            $("#editemployment-" + data_id)[0].reset();
            $('.edit-work-start-month-' + data_id).val($('#original-work-start-month-' + data_id).val().replace(/^0+/, '')).selectpicker('refresh');
            $('.edit-work-end-month-' + data_id).val($('#original-work-end-month-' + data_id).val().replace(/^0+/, '')).selectpicker('refresh');
            $('.edit-work-start-year-' + data_id).val($('#original-work-start-year-' + data_id).val()).selectpicker('refresh');
            $('.edit-work-end-year-' + data_id).val($('#original-work-end-year-' + data_id).val()).selectpicker('refresh');
        } else {
            $("#addemployment")[0].reset();
            $('.add-work-from-year').val('').selectpicker('refresh');
            $('.add-work-from-month').val('').selectpicker('refresh');
            $('.add-work-to-year').val('').selectpicker('refresh');
            $('.add-work-to-month').val('').selectpicker('refresh');
        }
        replaceStopEditingWithEditIcon();
    });
    $('.cancel-skill').on('click', function (e) {
        removeStopEditingFromNav();
        $('#addskill').show();
        $('.add-skills-popup').hide();
        replaceStopEditingWithEditIcon();
    });
    $('.cancel-tool').on('click', function (e) {
        removeStopEditingFromNav();
        $('#addtool').show();
        $('.add-tools-popup').hide();
        replaceStopEditingWithEditIcon();
    });
    $('.add-expert-position').on('click', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            $('.profile-infos').hide();
            $('.addExpertPosition').show();
        }
    });
    $('#addskill').on('click', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            $('.add-skills-popup').show();
            $('#addskill').hide();
            $("#userlanguage").trigger('focus');
        }
    });
    $('#addtool').on('click', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            $('.add-tools-popup').show();
            $('#addtool').hide();
            $("#userlanguage").trigger('focus');
        }
    });
    $('.add_lang_button').on('click', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            $('.add-language-popup').show();
            $('#add_lang_button').hide();
            $('.edit_lang_button').hide();
        }
    });

    $('.edit_lang_button').on('click', function (e) {
        var id = $(this).attr('id');
        $('.edit_lang_button').hide();
        $('#add_lang_button').hide();
        $('.language-' + id).show();
        $("#edit_user_language").trigger('focus');
    });
    $('.edit-cancel-language').on('click', function (e) {
        removeStopEditingFromNav();
        $('.edit_lang_button').show();
        $('#add_lang_button').show();
        $('.edit-language-popup').hide();
        $('.add-language-popup').hide();
        $("#addlanguage")[0].reset();
        var data_id = $(this).attr('data-id');
        $('#language_proficiency').val('').selectpicker('refresh');
        if(typeof data_id !== 'undefined'){
            var language_proficiency = $(this).attr('language-proficiency');
            $("#editlanguage-" + data_id)[0].reset();
            $('#edit_language_proficiency_' + data_id).val(language_proficiency).selectpicker('refresh');
        }
        replaceStopEditingWithEditIcon();
    });
    $('.edit_college').on('click', function (e) {
        var id = $(this).attr('id');
        if ($(this).hasClass('stop_editing')) {
            $('.edit_college-' + id).show();
            $('.edit_college').hide();
            $('#addclgbuttom').hide();
            $('.certificate-section').hide();
            $("#eeduname" + id).trigger('focus');
        } else {

            $('.edit_college-' + id).show();
            $('.edit_college').hide();
            $('#addclgbuttom').hide();
            $('.certificate-section').hide();
            $("#eeduname" + id).trigger('focus');
        }
    });
    $('.edit_course').on('click', function (e) {
        var id = $(this).attr('id');
        if ($(this).hasClass('stop_editing')) {
            $('.edit_course-' + id).show();
            $('.edit_course').hide();
            $('.addCourse').hide();
            $('.education-section').hide();
            $("#addclgbuttom").hide();
            $("#ecoursename" + id).trigger('focus');
        } else {
            $('.edit_course-' + id).show();
            $('.edit_course').hide();
            $('.addCourse').hide();
            $('.education-section').hide();
            $("#addclgbuttom").hide();
            $("#ecoursename" + id).trigger('focus');
        }
    });
    $('.cancel-edit-college').on('click', function (e) {
        removeStopEditingFromNav();
        $('.college-id').hide();
        $('#add_class').hide();
        $('.certificate-section').show();
        $('.edit_college').show();
        $('#addclgbuttom').show();
        var data_id = $(this).attr('data-id');
        if(typeof data_id !== 'undefined'){
            $("#editeducation-" + data_id)[0].reset();
            $('.edit-college-start-month-' + data_id).val($('#original-college-start-month-' + data_id).val().replace(/^0+/, '')).selectpicker('refresh');
            $('.edit-college-end-month-' + data_id).val($('#original-college-end-month-' + data_id).val().replace(/^0+/, '')).selectpicker('refresh');
            $('.edit-college-start-year-' + data_id).val($('#original-college-start-year-' + data_id).val()).selectpicker('refresh');
            $('.edit-college-end-year-' + data_id).val($('#original-college-end-year-' + data_id).val()).selectpicker('refresh');
        } else {
            $("#addcollege")[0].reset();
            $('.add-college-from-year').val('').selectpicker('refresh');
            $('.add-college-from-month').val('').selectpicker('refresh');
            $('.add-college-to-year').val('').selectpicker('refresh');
            $('.add-college-to-month').val('').selectpicker('refresh');
        }
        replaceStopEditingWithEditIcon();
    });
    $('.cancel-edit-course').on('click', function (e) {
        removeStopEditingFromNav();
        $('.course-id').hide();
        $('#add_course').hide();
        $('.education-section').show();
        $('#addclgbuttom').show();
        $('.edit_course').show();
        $('.addCourse').show();
        var data_id = $(this).attr('data-id');
        if(typeof data_id !== 'undefined'){
            $("#editcourse-" + data_id)[0].reset();
            $('.edit-course-year').selectpicker('refresh');
            $('.edit-course-month').val($('#original-start-month-' + data_id).val().replace(/^0+/, '')).selectpicker('refresh');
        } else {
            $('.add-course-year').val('').selectpicker('refresh');
            $('.add-course-month').val('').selectpicker('refresh');
        }
        $("#addcourse")[0].reset();
        replaceStopEditingWithEditIcon();
    });
    $('#addclgbuttom').on('click', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            $('#add_class').show();
            $('.certificate-section').hide();
            $('.edit_college').hide();
            $('#addclgbuttom').show();
            $('#addclgbuttom').hide();
            $("#eduname").trigger('focus');
        }
    });

    $('.addCourse').on('click', function (e) {
        if ($(this).hasClass('stop_editing')) {
            $('.add-course').show();
            $('.addCourse').hide();
        } else {
            $('.add-course').show();
            $('.edit_course').hide();
            $('.education-section').hide();
            $('#addclgbuttom').hide();
            $('.addCourse').hide();
            $("#coursename").trigger('focus');
        }
    });

    $('#expert_type').change(function () {
        updateInformation(this)
    });

    $('#experts_count').change(function () {
        updateInformation(this)
    });

    $('#remote_work').change(function () {
        updateInformation(this)
    });
    $(document).on('click', '.dropdown span', function () {
        $("#expert_profile_country_name").val('')
        if($(this).find("input#city").val()!='false'){
            $("#expert_profile_city_name").val($(this).find("input#city").val()).trigger('change');
        } else {
            $("#expert_profile_city_name").val($(this).find("input#country").val());
        }
        country_name = $(this).find("input#country").val();
        $("#expert_profile_country_name").val(country_name);
        if ($('#expert_basic_information').is(":visible")) {
            $('#expert_profile_city').val($(this).text());
            $("#expert_profile_country_name").val(country_name);
            $("#expert_basic_information").find('#expert_profile_tags').hide();
            updateExpertBasicInformationField('city_name__country_name', $(this).find("input#city").val()+'_'+country_name, true);
        }
        if ($('#add_employee_location').is(":visible")) {
            $('#add_employee_location').val($(this).text());
        } else {
            $('.employment-history-location').val($(this).text());
        }
        $('#add_employee_tags, #expert_profile_tags ').hide();


    });
    function updateInformation(ele){
        var value = $(ele).val().trim();
        var key = $(ele).attr('name');
        updateExpertBasicInformationField(key, value);
    }
    $('#describe, #daily_rate').focusout(function () {
        var value = $(this).val().trim();
        var key = $(this).attr('id');
        updateExpertBasicInformationField(key, value);
    });
    $('#expert_basic_information').on('submit', function (e) {
        e.preventDefault();
        updateExpertBasicInformationField('get_info', '');


    });
    //update bio detail
    $('#editbioform').on('submit', function (e) {
        var bio = $("#bio").val().trim();
        if (bio == "")
        {
            $(".validation_error").text("Please enter your story");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }
    });

    $('.editedusubmit').on('click', function (e) {
        var formId = $(this).attr('id');
        var eeduname = $("#eeduname" + formId).val().trim();
        var euniversity = $("#euniversity" + formId).val().trim();
        var startmonth = $("#startMonth-" + formId).val();
        var startyear = $("#startYear-" + formId).val();
        var endmonth = $("#endMonth-" + formId).val();
        var endyear = $("#endYear-" + formId).val();
        if (eeduname == "")
        {
            $(".eeduname_error").text("Please enter course name");
            $('.eeduname_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (euniversity == "")
        {
            $(".euniversity_error").text("Please enter university name");
            $('.euniversity_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (startmonth == '' && startyear == '') {
            $(".estartDate_error").text("Please select Start Year");
            $('.estartDate_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }
        if (startmonth != '' && startyear == '') {
            $(".estartDate_error").text("Please select Start Year");
            $('.estartDate_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (startmonth == '' && startyear != '') {
            $(".estartDate_error").text("Please select Start Month");
            $('.estartDate_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (endmonth != '' && endyear == '') {
            $(".eendDate_error").text("Please select End Year");
            $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (endmonth == '' && endyear != '') {
            $(".eendDate_error").text("Please select End Month");
            $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (startmonth != '' && startyear != '' && endmonth == '' && endyear == '') {
            $(".eendDate_error").text("Please select End Month");
            $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (startmonth != '' && startyear != '' && endmonth == '' && endyear != '') {
            $(".eendDate_error").text("Please select End Month");
            $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (startmonth != '' && startyear != '' && endyear != '' && endmonth == '') {
            $(".eendDate_error").text("Please select End Year");
            $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (startmonth != '' && startyear != '' && endmonth != '' && endyear != '') {

            var first_date = new Date(startyear, startmonth - 1, 1);
            var second_date = new Date(endyear, endmonth - 1, 1);

            var start_date = first_date.getTime();

            var end_date = second_date.getTime();

            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), 1).getTime();


            if (end_date < start_date) {
                $(".eendDate_error").text("End date cannot be less than Start date.");
                $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
                return false;
            }

            if (end_date > today) {
                $(".eendDate_error").text("End date cannot more than the current date.");
                $('.eendDate_error').fadeIn('fast').delay(2000).fadeOut();
                return false;
            }
        }
        if (startmonth == '' || startyear == '' || endmonth == '' || endyear == '' || eeduname == '' || euniversity == '') {
            return false;
        } else if (eeduname != '' && euniversity != '') {
            e.preventDefault();
            $('#editeducation-' + formId).submit();
        }
    });

    $('.editcoursesubmit').on('click', function (e) {
        var formId = $(this).attr('id');
        var e_coursename = $("#ecoursename" + formId).val().trim();
        var e_institute = $("#einstitute" + formId).val().trim();
        if (e_coursename == "")
        {
            $("#ecoursename_error_" + formId).text("Please enter course name");
            $('#ecoursename_error_' + formId).fadeIn('fast').delay(2000).fadeOut();
        }
        if (e_institute == "")
        {
            $("#einstitute_error_" + formId).text("Please enter university name");
            $("#einstitute_error_" + formId).fadeIn('fast').delay(2000).fadeOut();
        }
        if (e_coursename != '' && e_institute != '') {

            e.preventDefault();
            $('#editcourse-' + formId).submit();
        } else {
            return false;
        }
    });

    /*from js layoutedit backup reloaded**/

    $('#add_employments').on('click', function (e) {
        var current_id = $(this).attr('id');
        var clicked_employee_button = $('#clicked_button').val();
        var employee_title = $("#employee_title").val();
        var employee_company = $("#employee_company").val();
        var start_month = $("#employee_start_month").val();
        var start_year = $("#employee_start_year").val();
        var end_month = $("#employee_end_month").val();
        var end_year = $("#employee_end_year").val();
        var is_current = $("#is_current").val();
        var location_description = $("#add_employee_location").val();

        var error_count = 0;
        $('.has_error').show();
        if ( employee_title.trim() == "")
        {
            $("#eemp_title_error").text("Please enter employment title").addClass('has_error');
            error_count++;
        } else {
            $("#eemp_title_error").html('').removeClass('has_error');
        }

        if (employee_company.trim()   == "")
        {
            $("#eemp_company_error").text("Please enter company name").addClass('has_error');
            error_count++;
        } else {
            $("#eemp_company_error").html('').removeClass('has_error');
        }


        if (start_month != '' && start_year == '') {
            $("#start_year_error").text("Please select Start Year").addClass('has_error');
            error_count++;
        } else {
            $("#start_year_error").html('').removeClass('has_error');
        }

        if (start_month == '' && start_year != '') {
            $("#start_month_error").text("Please select Start Month").addClass('has_error');
            error_count++;
        } else {
            $("#start_month_error").html('').removeClass('has_error');
        }

        if (!$('#addemployment #is_current').is(':checked')) {
            if (start_year != '' && end_month == '' && end_year == '') {
                $("#end_month_error").text("Please select End Month").addClass('has_error');
                error_count++;
            } else if (start_month != '' && start_year != '' && end_month == '' && end_year != '') {
                $("#end_month_error").text("Please select End Month").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error").html('').removeClass('has_error');
            }

            if (start_year != '' && end_month != '' && end_year == '') {
                $("#end_year_error").text("Please select End Year").addClass('has_error');
                error_count++;
            } else if (start_month != '' && start_year != '' && end_year == '' && end_month != '') {
                $(".end_year_error").text("Please select End Year").addClass('has_error');
                error_count++;
            } else {
                $("#end_year_error").html('').removeClass('has_error');
            }
        }

        if (location_description.length !== 0 && location_description.indexOf($("#add_employee_location").val()) <= -1) {
            $("#education_location_error").text('Please choose a location from the dropdown list of choices').addClass('has_error');
            $('#add_employee_location').val('');
            error_count++;
        } else {
            $("#education_location_error").html("").removeClass('has_error');
        }

        if (start_month != '' && start_year != '' && end_month != '' && end_year != '' && (location_description.length === 0 || (location_description.length !== 0 && location_description.indexOf($("#add_employee_location").val()) > -1))) {

            var first_date = new Date(start_year, start_month - 1, 1);
            var second_date = new Date(end_year, end_month - 1, 1);

            var start_date = first_date.getTime();


            var end_date = second_date.getTime();
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), 1).getTime();

            if (end_date < start_date) {

                $("#end_month_error").text("End date cannot be less than Start date.").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error").html('').removeClass('has_error');
            }

            if (!$('#addemployment #is_current').is(':checked') && end_date > today) {
                $("#end_month_error").text("End date cannot more than the current date.").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error").html('').removeClass('has_error');
            }
        }
        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
            return false;
        } else {
            if (employee_title != '' && employee_company != '') {
                e.preventDefault();

                $('#addemployment').submit();
                return false;

            }
        }
    });



    /*ediemployement code from layoutedit.blade*/

    //update employment detail
    $('.editemployment').on('click', function (e) {

        var formId = $(this).attr('id');
        var employee_title = $("#employee_title" + formId).val().trim();
        var employee_company = $("#empcompany" + formId).val().trim();
        var start_month = $("#edit_employee_start_month-" + formId).val();
        var start_year = $("#edit_employee_start_year-" + formId).val();
        var end_month = $("#edit_employee_end_month-" + formId).val();
        var end_year = $("#edit_employee_end_month-" + formId).val();
        var edit_current = $(".edit_current").val();

        var error_count = 0;
        $('.has_error').show();

        if (employee_title == "")
        {
            $("#eemp_title_error-" + formId + "").text("Please enter employment title").addClass('has_error');
            error_count++;
        } else {
            $("#eemp_title_error-" + formId + "").html('').removeClass('has_error');
        }

        if (employee_company == "")
        {
            $("#eemp_company_error-" + formId + "").text("Please enter company name").addClass('has_error');
            error_count++;
        } else {
            $("#eemp_company_error-" + formId + "").html('').removeClass('has_error');
        }


        if (start_month != '' && start_year == '') {
            $("#start_year_error-" + formId + "").text("Please select Start Year").addClass('has_error');
            error_count++;
        } else {
            $("#start_year_error-" + formId + "").html('').removeClass('has_error');
        }

        if (start_month == '' && start_year != '') {
            $("#start_month_error-" + formId + "").text("Please select Start Month").addClass('has_error');
            error_count++;
        } else {
            $("#start_month_error-" + formId + "").html('').removeClass('has_error');
        }

        if (!$('#addemployment #is_current').is(':checked')) {
            if (start_year != '' && end_month == '' && end_year == '') {
                $("#end_month_error-" + formId + "").text("Please select End Month").addClass('has_error');
                error_count++;
            } else if (start_month != '' && start_year != '' && end_month == '' && end_year != '') {
                $("#end_month_error-" + formId + "").text("Please select End Month").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error-" + formId + "").html('').removeClass('has_error');
            }

            if (start_year != '' && end_month != '' && end_year == '') {
                $("#end_year_error-" + formId + "").text("Please select End Year").addClass('has_error');
                error_count++;
            } else if (start_month != '' && start_year != '' && end_year == '' && end_month != '') {
                $(".end_year_error-" + formId + "").text("Please select End Year").addClass('has_error');
                error_count++;
            } else {
                $("#end_year_error-" + formId + "").html('').removeClass('has_error');
            }
        }

        if (location_description.length !== 0 && location_description.indexOf($("#emplocation-" + formId + "").val()) <= -1) {
            $("#employee_location_error-" + formId + "").text('Please choose a location from the dropdown list of choices').addClass('has_error');
            $("#emplocation-" + formId + "").val('');
            error_count++;
        } else {
            $("#employee_location_error-" + formId + "").html("").removeClass('has_error');
        }

        if (start_month != '' && start_year != '' && end_month != '' && end_year != '' && (location_description.length === 0 || (location_description.length !== 0 && location_description.indexOf($("#employee_location_error-" + formId + "").val()) > -1))) {

            var first_date = new Date(start_year, start_month - 1, 1);
            var second_date = new Date(end_year, end_month - 1, 1);

            var start_date = first_date.getTime();

            var end_date = second_date.getTime();

            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), 1).getTime();


            if (end_date < start_date) {

                $("#end_month_error").text("End date cannot be less than Start date.").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error").html('').removeClass('has_error');
            }

            if (!$('#editemployment-' + formId + ' #is_current').is(':checked') && end_date > today) {
                $("#end_month_error").text("End date cannot more than the current date.").addClass('has_error');
                error_count++;
            } else {
                $("#end_month_error").html('').removeClass('has_error');
            }
        }


        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
            return false;
        } else {

            if (employee_title != '' && employee_company != '') {
                $('#editemployment-' + formId).submit();
                return false;

            }
        }
    });

    var is_request_sent = false;
    function updateExpertBasicInformationField(field_name, value, multiple) {
        if (field_name == 'get_info' || (field_name != 'get_info' && value !== '')) {
            $.ajax({
                type: 'post',
                beforeSend: function() {
                    if (is_request_sent == false) {
                        is_request_sent = true
                        return true;
                    } else {
                        if (field_name == 'get_info') {
                            is_request_sent = false
                            return true;
                        }
                        return false;
                    }
                },
                url: base_url + '/expertbasicinformation',
                data: {
                    field_name: field_name,
                    value: value,
                    multiple : multiple
                },
                success: function (response) {
                    is_request_sent = false
                    var multiple = typeof multiple !== 'undefined' ? true : false;
                    if (field_name != 'get_info') {
                        var splited_field = field_name.split('_');
                        splited_field[0] = splited_field[0].charAt(0).toUpperCase()+splited_field[0].slice(1);
                        var message = multiple ? 'Location' : field_name == 'experts_count_lower_range' ? 'No of experts' : splited_field.join(' ');
                        message = field_name == 'remote_id' ? 'Preferences' : message;
                        var field_key = multiple ? 'location' : field_name;
                        if (field_key == 'describe') { message = 'Profile title'; }
                        $("#validation_error_" + field_key).text(message + " saved.");
                        $('#validation_error_'+field_key).fadeIn('fast').delay(2000).fadeOut(function () {
                            $("#validation_error_"+field_key).text(" ").append("&nbsp;").css("display", "block");
                        });
                    }
                    if (field_name == 'get_info') {
                        location.reload();
                    }
                    $("#missing_"+field_key).addClass('profile-completion-completed-element');
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
        } else {
            $('#'+field_name).val(edited_summary_data[field_name]).stopPropagation();
        }

    }

});
$("#expert_profile_city").on('keyup', function (e) {
    var location = document.getElementById('expert_profile_city').value;
    if (e.keyCode === 13 || location == '') {
        $("#expert_profile_tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#expert_profile_tags"));
            return false;
        }
    }
    findLocation(location, $("#expert_profile_tags"));
});

$("#add_employee_location").on('keyup', function (e) {
    var location = document.getElementById('add_employee_location').value;
    if (e.keyCode === 13 || location == '') {
        $('#add_employee_tags').hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#add_employee_tags"));
            return false;
        }
    }
    findLocation(location, $("#add_employee_tags"));
});

$(".employment-history-location").on('keyup', function (e) {
    var employment_id = $(this).data("employment_id");
    var location = document.getElementById('emplocation-' + employment_id).value;
    if (e.keyCode === 13 || location == '') {
        $("#tags-" + employment_id).hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#tags-" + employment_id));
            return false;
        }
    }
    findLocation(location, $("#tags-" + employment_id));
});


$('.profile-content-section a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("data-section");
    $.ajax({
            url: target,
            success: function(html) {
                $('.'+target).html(html);
                buyerRating();
                $(document).find('.selectpicker').selectpicker('refresh')
            }
        }
    )
});
$('#expert_type').change(function(){
    var chosen = $(this);
    if(chosen.val()=='Consultancy'){
        $('#experts_count').val('').selectpicker('refresh');
        $('#number_of_experts_div').show();
    } else {
        $('#number_of_experts_div').hide();
    }
});
