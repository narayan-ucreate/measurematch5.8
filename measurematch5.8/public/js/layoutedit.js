/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function closeButton(id, daily_rate, rate_variable, currency) {
    $('#rate_variable').val();
}

function restform() {
    $('.success').remove();
}
function courseid(id, name, institute, start_date, end_date, description)
{

     $('#courseid').val(id);
     $('#ecoursename').val(name);
     $('#einstitute').val(institute);
    $('#etotime').val(end_date);
     $('#editcousrsemyModal').modal('show');
}


$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#logoutbtn').on('click', function (e) {
        localStorage.clear();
        window.location.href = base_url + '/logout';
    });
//4th part
    $('.indexes').on('keydown', function (e) {
        if (e.keyCode == 13)
        {
            e.preventDefault();
            return false;
        }
    });
    $('.btnskill, .btnskills').on('click', function (e) {
        e.preventDefault();
        $(this).parent().addClass('selected');
        var current_value = $(this).val();
        var last_value = $('#skill_set').val();
        if (last_value == '') {
            $('#skill_set').val(current_value);
        } else {
            var skill_array = $('#skill_set').val().split(',');
            var is_exist = false;
            $.each(skill_array, function (key, skill) {
                if (skill == current_value) {
                    is_exist = true;
                    return false;
                }
            });
            if (!is_exist) {
                $('#skill_set').val(last_value + ',' + current_value);
            }
        }
    });
//close skill button
    $('.btnclose').on('click', function (e) {
        var detail = $(this).attr('attr').split('_');
        $(this).parent().removeClass('selected');
        if ($(this).parent().hasClass('a_wraper') == true) {
            $(this).parent().remove();
        }
        var id = $(this).attr('id').split('_');
        var list = $('#skill_set').val();
        var value = detail[0];
        var new_value = list.replace(new RegExp(",?" + value + ",?"), function (match) {
            var first_comma = match.charAt(0) === ',',
                    second_comma;
            if (first_comma &&
                    (second_comma = match.charAt(match.length - 1) === ',')) {
                return ',';
            }
            return '';
        });
        $("#skill_set").val(new_value);
    });

    $('#addlang').on('click', function (e) {
        var user_language = $("#userlanguage").val().trim();
        var language_proficiency = $("#language_proficiency").val();
        if (user_language == "")
        {
            $(".validation_error").text("Please enter language name.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (user_language != '') {
            $('#addlanguage').submit();
        }
    });

    $('#addcolleges').on('click', function (e) {
        var education_name = $("#eduname").val().trim();
        var university = $("#university").val().trim();
        var start_month = $("#startMonth").val();
        var start_year = $("#startYear").val();
        var end_month = $("#endMonth").val();
        var end_year = $("#endYear").val();

        if (education_name == "")
        {
            $("#eduname_error").text("Please enter course name");
             $('#eduname_error').fadeIn('fast').delay(2000).fadeOut();

        }
        if (university == "")
        {
            $("#university_error").text("Please enter university name");
             $('#university_error').fadeIn('fast').delay(2000).fadeOut();

        }
        if (start_month == '' && start_year == '') {
            $("#start_date_error").text("Please select Start Date");
             $('#start_date_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }
        if (start_month != '' && start_year == '') {
            $("#start_date_error").text("Please select Start Year");
             $('#start_date_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (start_month == '' && start_year != '') {
            $("#start_date_error").text("Please select Start Month");
             $('#start_date_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (end_month == '' && end_year == '') {
            $("#end_date_error").text("Please select End Date");
             $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }
        if (end_month == '' && end_year != '') {
            $("#end_date_error").text("Please select End Month");
             $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (end_month != '' && end_year == '') {
            $("#end_date_error").text("Please select End Year");
             $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (start_month != '' && start_year != '' && end_month == '' && end_year != '') {
            $("#end_date_error").text("Please select End Month");
             $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
        }
        if (start_month != '' && start_year != '' && end_year == '' && end_month != '') {
            $("#end_date_error").text("Please select End Year");
             $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
        }

        if (start_month != '' && start_year != '' && end_month != '' && end_year != '') {
            var start_date_object = new Date(start_year, start_month - 1, 1);
            var end_date_object = new Date(end_year, end_month - 1, 1);

            var start_date = start_date_object.getTime();


            var end_date = end_date_object.getTime();
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), 1).getTime();

            if (end_date < start_date) {
                $("#end_date_error").text("End date cannot be less than Start date.");
                 $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
                return false;
            }

            if (end_date > today) {
                $("#end_date_error").text("End date cannot more than the current date.");
                $('#end_date_error').fadeIn('fast').delay(2000).fadeOut();
                return false;
            }
        }
        if (start_month == '' || start_year == '' || end_month == '' || end_year == '' || education_name == '' || university == '') {
            return false;
        } else if (education_name != '' || university != '') {
            $('#addcollege').submit();
            return false;
        }

    });

//update summary detail
    $('#uploadpic').on('change', function (e) {

        var profile_img = $("#img_show").val();

        if (profile_img == "")
        {
            $("#image-error").text("Please choose profile pic");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }
        var extension = profile_img.split('.')[profile_img.split('.').length - 1].toLowerCase();

        if (profile_img != '') {
            if (extension != "jpg" && extension != "gif" && extension != "png" && extension != "jpeg")
            {
                $(".validation_error").text("Images are allowed only of .jpg, .png or .gif format");
                $('.validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                return false;
            }
        }
        $('.loadingsignup').html('<img  rel="nofollow" alt="loading" src="' + base_url + '/images/loading.gif">');
        $("#uploadpic input[type=button]").attr("disabled", "disabled");

        e.preventDefault();
        var profileimg_data = new FormData($(this)[0]);

        $.ajax({
            type: 'post',
            url: base_url + '/sellerlogo',
            data: profileimg_data,
            async: false,
            success: function (response) {

                if (response == 1) {
                    window.location.href = base_url + '/expert/profile-summary';
                }

            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });
    $('.editlang').on('click', function (e) {

        var user_language = $("#edit_user_language").val().trim();
        var language_proficiency = $("#edit_language_proficiency").val();

        var charact_pattren = /^[a-z A-Z]+$/;

        if (user_language == "")
        {
            $(".validation_error").text("Please enter language name.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (!charact_pattren.test(user_language)) {
            $(".validation_error").text("Only characters are allowed.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            $(this).focus();
            return false;
        } else if (user_language != '') {

            $('#editlanguage').submit();

        }
    });



    $('#addcourses').on('click', function (e) {
             var course_name = $("#coursename").val().trim();
             var institute = $("#institute").val().trim();
     
         if (course_name == "")
               {
                       $("#coursename_error").text("Please enter course name");
                       $('#coursename_error').fadeIn('fast').delay(2000).fadeOut();
               }    
               if (institute == "")
               {
                       $("#institute_error").text("Please enter institute name");
                       $('#institute_error').fadeIn('fast').delay(2000).fadeOut();
               }
               if (course_name != '' && institute != '') {
                       e.preventDefault();
                       $('#addcourse').submit();           
              } 
                else {
                      return false;
        }
    });

//update education detail
    $('#editcourse').on('submit', function (e) {

               var e_coursename = $("#ecoursename").val().trim();
               var e_institute = $("#einstitute").val().trim();
     
         if (e_coursename == "")
               {
                       $(".validation_error").text("Please enter course name");
                       $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                       return false;
               }    
     
     
        else if (e_institute == "")
               {
                       $(".validation_error").text("Please enter institute name");
             $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                       return false;
               }

               else if (e_coursename != '' && e_institute != '') {
     
                     e.preventDefault();
                     var courseData = new FormData($(this)[0]);

                          $.ajax({
                               type: 'post',
                               url: base_url + '/editcourse',
                               data: courseData,
                               async: false,
                               success: function (response) {
           
                                    if (response == 1) {
                        window.location.href = base_url + 'expert/profile-summary';
                    }

                                    if (response == 'E_COURSE') {
                        alert('Please enter course');
                        $(".validation_error").text("Please enter  course");
                        return false;
                    }
                                    if (response == 'E_INSTITUTE') {
                        $(".validation_error").text("Please enter institute name");
                        return false;
                    }
               

                               },
                               error   : function (response)
                               {
                                     alert('Error: Please refresh the page');
                               },
                               cache: false,
                               contentType: false,
                               processData: false
                         });
              }
    });
     
    $(".stars_rating").each(function() {
    $(this).html(buyerRating($(this).attr('contract_id')));
    });
    
});

