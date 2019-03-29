function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 100)
    }, 400);
}
function closeButton(id, daily_rate, rate_variable, currency) {
    $('#rate_variable').val();
 }

/*!
 * Edit SummaryDetails
 */
function editSummaryDetail(id, location, remote_work, describe)
{
    $('#user_profile_id').val(id);
    $('#city').val(location);
    $('.selectpicker').selectpicker('val', remote_work);
    $('#describe').val(describe);
}

/*!
 * Edit language details
 */
function editLanguageDetail(id, language, language_proficiency)
{
    $('#edit_language_id').val(id);
    $('#edit_user_language').val(language);
    $('.selectpicker').selectpicker('val', language_proficiency);
}
/*!
 * Email validations
 */
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

/*!
 * This Js file is used for Addcourse and Education modules
 * 
 */
$(document).ready(function () {
    /*!
     * Ajax Call for profile image uploading
     */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});
    
    if (typeof bas64url != "undefined") {
        var base64_url = bas64url + "?url=" + $('#default_avatar').val();
        $.ajax({
            type: 'get',
            url: base64_url,
            success: function (response) {
                if (response) {
                    $('#default_avatar_base64').val(response);
                }
            },
            error: function (jqXHR, exception) {
              displayErrorMessage(jqXHR, exception);
            }
        });

        $("#cancelpopup").on("click", function () {
            $("#cropit-preview-image").attr("src", '');
        });

        var imgindex;
        $('.user_pic').on('click', function () {
            $('#cropimageadd').trigger('click');
            var imgsrc = $('#cropitimage').val();
            var zoom_value = $('#zoomlevel').val();
            if (zoom_value){
                $('#cropimageadd').addClass('visiblepopup');
            } else{
                var imgsrc = $('#default_avatar_base64').val();
                imgsrc = imgsrc.trim();
                $('.cropit-preview-background-container').remove();
                $('.cropit-preview-image-container').remove();
                $('#image-cropper').cropit('destroy');
                $('#image-cropper').cropit({exportZoom: 1,
                    maxZoom: 1.5,
                    imageBackground: true,
                    imageBackgroundBorderWidth: 250,
                    imageBackgroundBorderSize: 100,
                    imageState: {src: {imgsrc: imgsrc}}});
            }
        });
        
        $('#cropimageadd').on('change',function () {
            if (!$('#zoompopup').is(":visible") && $(this).val().length > 0) {
                $('.cropit-preview-background-container').remove();
                $('.cropit-preview-image-container').remove();
                if (this.files && this.files[0]) {
                    var FR = new FileReader();
                    FR.addEventListener("load", function (e) {
                        $('#zoompopup').modal('show');
                        $('#image-cropper').cropit('destroy');
                        $('#image-cropper').cropit({exportZoom: 1,
                            maxZoom: 1.5,
                            imageBackground: true,
                            imageBackgroundBorderWidth: 250,
                            imageBackgroundBorderSize: 100,
                            imageState: {src: {imgsrc: e.target.result}}});
                    });

                    FR.readAsDataURL(this.files[0]);
                }
            }
        });
        
        $('.savecropimage').on('click',function () {
            var image_data = $('#image-cropper').cropit('export');
            $('#base64image').val(image_data);
            if (image_data != '') {
                $('#image_error').text('');
            }
            var zoom_value = $('#image-cropper').cropit('zoom');
            $('.profilepicture').css('background-image', 'url(' + image_data + ')');
            var upload_image_data = $('.cropit-preview-image').attr('src');
            $('.profilezoompopup .change_car #change_photo_label').text('Change photo');
            $('#cropitimage').val(upload_image_data);
            $('#output').attr('src', image_data);
            $('#image_url').val(image_data);
            $('#dealer_photo').val(image_data);
            $('#zoomlevel').val(zoom_value);
            $('.loading').show();
            $('body').addClass('bodyloader');
        });
        $('.rotate-cw-btn').click(function () {
            $('#image-cropper').cropit('rotateCW');
        });
    }
    
    /*!
     * Submit User Lanaguage Preferences
     */
    $('#addlanguage').on('submit', function (e) {
        var clicked_button = $('#clicked_button').val();
        var user_language = $("#userlanguage").val().trim();
        var language_proficiency = $("#language_proficiency").val();
        if (user_language == "") {
            $(".validation_error").text("Please enter language name.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (user_language != '') {
            e.preventDefault();
            var language_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/savelanguage",
                data: language_data,
                async: false,
                success: function (response) {
                    var parsedJson = $.parseJSON(response);
                    if (clicked_button == "save_addmore_language" && parsedJson.success == 1)
                    {
                        var append_div = "<div class='edit_view' data-toggle='modal' data-target='#myModaleditlang' onclick=editLanguageDetail(" + parsedJson.id + ",'" + parsedJson.name + "')><h5>";
                        append_div += parsedJson.name;
                        append_div += "<a class='edit_icon' href='javascript:void(0)'' title='edit' data-toggle='modal'  onclick=editLanguageDetail(" + parsedJson.id + ",'" + parsedJson.name + "')><img  rel='nofollow' src='images/pen.png' alt='pen' /></a></h5><h5 class='grey-text'>";
                        append_div += parsedJson.prof;
                        append_div += '</h5></div>';
                        $('#language_list').append(append_div);
                        $('#userlanguage').val('');
                        $('.selectpicker').selectpicker('val', '');
                        $('.success').remove();
                        $('#addlanguage').append('<p class="success">Language added successfully.</p>');
                        $('.success').fadeIn('fast').delay(2000).fadeOut();
                    }
                    if (clicked_button == "addlang" && parsedJson.success == 1) {
                        window.location.reload();
                    }
                    if (parsedJson.success == 2) {
                        $(".validation_error").text("This language has added already.");
                        $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    /*!
     * Ajax call to update education details of a user
     */

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
               } else if (e_coursename != '' && e_institute != '') {
                     e.preventDefault();
                     var course_data = new FormData($(this)[0]);
                          $.ajax({
                               type: 'post',
                               url: base_url + "/editcourse",
                               data: course_data,
                               async: false,
                               success: function (response) {
                 
                                    if (response == 1) {
                        window.location.href = base_url + '/expert/profile-summary';
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
                     error: function (jqXHR, exception) {
                 displayErrorMessage(jqXHR, exception);
                },
                               cache: false,
                               contentType: false,
                               processData: false
                     });
                  }
    });
    /*!
     * Currenecy rating for profile
     */
    $('#profile_rate_popup').on('click',function () {
        var daily_rate = $(this).attr('value');
        var currency = $(this).attr('currency');
        if (daily_rate == 'Per Day') {
            $('#editdailyrate #rate_hour .dropdown-toggle .filter-option').text('Daily');
        }

        if (currency == '$') {
            $('#editdailyrate #rate_currency .dropdown-toggle .filter-option').text('$');
        }
    });

    /*!
     * Ajax call to update educational details of a user
     */
    $('#editeducation').on('submit', function (e) {
        var e_eduname = $("#eeduname").val().trim();
        var e_university = $("#euniversity").val().trim();
        if (e_eduname == "")
        {
            $(".validation_error").text("Please enter course name");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (e_university == "")
        {
            $(".validation_error").text("Please enter university name");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (e_eduname != '' && e_university != '') {
            e.preventDefault();
            var education_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/editeducation",
                data: education_data,
                async: false,
                success: function (response) {
                    if (response == 1) {
                        window.location.href = base_url + '/expert/profile-summary';
                    }
                    if (response == 'E_COURSE') {
                        alert('Please enter course');
                        $(".validation_error").text("Please enter education course");
                        return false;
                    }
                    if (response == 'E_UNIVERSITY') {
                        $(".validation_error").text("Please enter university name");
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                   displayErrorMessage(jqXHR, exception);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    /*!
     * Ajax call to update user's bio details
     */
    $('#editbioform').on('submit', function (e) {
        var bio = $("#bio").val().trim();
        if (bio == "")
        {
            $(".validation_error").text("Please enter bio detail");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (bio != '') {
            e.preventDefault();
            var bio_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/editsellerbio",
                data: bio_data,
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
        }
    });

    /*!
     * Ajax call to update ratings
     */

    $('#editdailyrate').on('submit', function (e) {
        var daily_rate = $("#daily_rate").val();
        var rate_variable = $("#rate_variable").val();
        var currency = $("#currency").val();
        var numbers = /^[0-9]+$/;
        if (daily_rate == "")
        {
            $(".validation_error").text("Please enter Daily Rate");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (!numbers.test(daily_rate))
        {
            $(".validation_error").text("Please enter only digits in daily rate");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (rate_variable == "")
        {
            $(".validation_error").text("Please select rate variable");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (currency == "")
        {
            $(".validation_error").text("Please select currency");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (daily_rate != '' && rate_variable != '' && currency != '') {
            e.preventDefault();
            var rate_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/editsellerrate",
                data: rate_data,
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
        }
    });

    /*!
     * Update user profile summary details
     */
    $('#uploadpic').on('submit', function (e) {
        var profile_img = $("#img_show").val();
        if (profile_img == "")
        {
            $("#image-error").text("Please choose profile pic");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (profile_img != '') {
            $('.loadingsignup').html('<img  rel="nofollow" alt="loading" src=base_url+"/images/loading.gif"');
            $("#uploadpic input[type=button]").attr("disabled", "disabled");
            e.preventDefault();
            var profileimg_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/sellerlogo",
                data: profileimg_data,
                async: false,
                success: function (response) {
                    var parsedJson = $.parseJSON(response);
                    if (parsedJson.success == 1) {
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
        }
    });

    /*!
     * Udpate user lanaguage details
     */

    $('#editlanguage').on('submit', function (e) {
        var clicked_button = $('#clicked_button').val();
        var user_language = $("#edit_user_language").val().trim();
        var language_proficiency = $("#edit_language_proficiency").val();
        var character_pattern = /^[a-z A-Z]+$/;
        if (user_language == "")
        {
            $(".validation_error").text("Please enter language name.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (!character_pattern.test(user_language)) {
            $(".validation_error").text("Only characters are allowed.");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            $(this).focus();
            return false;
        } else if (user_language != '') {
            e.preventDefault();
            var language_data = new FormData($(this)[0]);
            $.ajax({
                type: 'post',
                url: base_url + "/editlanguage",
                data: language_data,
                async: false,
                success: function (response) {
                    var parsedJson = $.parseJSON(response);
                    if (parsedJson.success == 1)
                    {
                        window.location.reload();
                    }
                    if (parsedJson.success == 2)
                    {
                        window.location.reload();
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    /*!
     * Code to validate edit seller/Expert Basic account
     * Code for if data is changed but not saved and user navigate on other page
     */

    $("#success_msg_expert_edit").delay(4000).fadeOut('slow');
   var formmodified = 0;
    $('#editseller *').change(function () {
        formmodified = 1;
    });
    $('#seller_account_info *').change(function () {
        formmodified = 1;
    });
    $('#editseller_communication *').change(function () {
        formmodified = 1;
    });

        window.onbeforeunload = confirmExit();
        function confirmExit() {
            if (formmodified == 1) {
                return "New information not saved. Do you wish to leave the page?";
            }
        }
        
    $('#seller_phone').on('keypress', function(ev) {
        allowNumbersOnly(ev);
    });
    
    $('#seller_phone').bind("paste",function(e) {
      e.preventDefault();
    });
    
    /*!
     * Ajax call to update seller information 
     */
    $("#submit_btn").on('click',function () {
        var seller_fname = $('#seller_fname').val();
        var seller_lname = $('#seller_lname').val();
        var seller_email = $('#seller_email').val();
        var has_vat = $('#have_vat:checked').val();
        var numbers_regex = /^[0-9]+$/;
        var seller_phone_number = $('#seller_phone').val();
        var error_count = 0;
        $('.has_error').show();

        if (seller_fname == "") {
            $("#seller_fname_error").html("First name is required.").addClass('has_error');
            error_count++;
        } else {
            $("#seller_fname_error").html("").removeClass('has_error');
        }
        if (seller_lname == "") {
            $("#seller_lname_error").html("Last name is required.").addClass('has_error');
            error_count++;
        } else {
            $("#seller_lname_error").html("").removeClass('has_error');

        }
        if (has_vat) {
            if ($('#vat_country_code').val() == '') {
                $("#vat_number_error").html("Please choose the country which you are VAT registered in.").addClass('has_error');
                error_count++;
            } else if ($('#vat_number').val() == '') {
                $("#vat_number_error").html("Please add VAT number.").addClass('has_error');
                error_count++;
            } else {
                $("#vat_number_error").html("").removeClass('has_error');
            }
        }
        if (seller_email == "") {
            $("#seller_email_error").html("Email is required.").addClass('has_error');

            error_count++;
        } else if (IsEmail(seller_email) == false) {
            $("#seller_email_error").html("Email is not valid.");
            error_count++;
        } else if ((IsEmail(seller_email) == true) && (seller_email != "")) {
            $.ajax({
                type: 'post',
                url: base_url + "/emailUpdateCheck",
                data: {email: seller_email},
                async: false,
                success: function (response) {
                    if (response == 1) {
                        $("#seller_email_error").html("Email already exist.").addClass('has_error');
                        error_count++;
                    } else {
                        $("#seller_email_error").html("").removeClass('has_error');
                    }
                }
            });
        } else {
            $("#seller_email_error").html("").removeClass('has_error');
        }
        
        if (seller_phone_number == ""){
            $("#seller_phone_error").html('Please enter your phone number').addClass('has_error');
            error_count++;
        } else if (!numbers_regex.test(seller_phone_number)) {
            $("#seller_phone_error").html('Please enter only digits in phone number').addClass('has_error');
            error_count++;
        } else if ($('.selected-dial-code:visible').text() == '') {
            $("#seller_phone_error").html('Please select your country').addClass('has_error');
            $('.selected-flag').focus();
            error_count++;
        } else {
            $("#seller_phone_error").html('').removeClass('has_error');
        }
        
        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(4000).fadeOut('fast');
            return false;
        } else {
            $('#date_of_birth').val(getFullDate());
            $('#country_code').val($('.selected-dial-code:visible').text());
            $('#editseller').submit();
        }
    });
    $('#seller_fname').on('keyup paste', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    });
    $('#seller_lname').on('keyup paste', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    });
    $('#seller_phonenum').on('keyup paste', function () {
        this.value = this.value.replace(/[^\+\d]+/g, '');
    });
   
    /*!
     * Code to validate edit seller/Expert Account info (Account Page)
     */

    $("#submit_expert_account").on('click',function () {
        var formmodified = 0;
        $("#seller_category_error").html("");
        $("#seller_category_error").hide();
        var seller_account_information = new FormData($('#seller_account_info')[0]);
        var form = $(this);
        $.ajax({
            type: 'post',
            url: base_url + '/updateselleraccountinfo',
            data: seller_account_information,
            processData: false,
            contentType: false,
            success: function (resp) {
                if (resp == 1) {
                    $("#success_msg_expert_edit").html("<div class='bg-success'>Account information updated</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                    $("#success_msg_expert_edit").show();
                    scroll_to_error('#success_msg_expert_edit');
                    return false;
                } else {

                    $("#success_msg_expert_edit").html("<div class='bg-success'>Please try again,due to some problem unable to update.</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                    $("#success_msg_expert_edit").show();
                    scroll_to_error('#success_msg_expert_edit');
                    return false;

                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });

    /*!
     * Code to validate update password seller/Expert Account info (Account Page)
     */

    $("#expert_update_pass_btn").on('click',function () {
        var old_password = $("#old_password").val();
        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();
        var passregex = /^(?=.*[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~])(?=.*\d)[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~\d]{6,}$/;
        var error_count = 0;

        if (old_password == "")
        {

            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Please enter the current password.");
            error_count++;
        } else if (old_password.length < 6) {

            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Current password must be 6 characters long.");
            error_count++;
        } else if (!passregex.test(old_password)) {

            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Your password must contain at least one number.");
            error_count++;
        } else if (passregex.test(old_password)) {
            $.ajax({
                type: 'post',
                url: base_url + "/checkUserPassword",
                data: {'old_password': old_password},
                async: false,
                success: function (response) {
                    if (response == 1) {
                        $("#validation_error_existing_password").html("Password did not match your current password.");
                        $("#validation_error_existing_password").show();
                        error_count++;
                    } else {

                        $("#validation_error_existing_password").html("");
                        $("#validation_error_existing_password").hide();

                    }
                }
            });
        } else {
            $("#validation_error_existing_password").hide();
            $("#validation_error_existing_password").html("");
        }

        if (new_password == "")
        {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Please enter the new password.");
            error_count++;
        } else if (new_password.length < 6) {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Password must be 6 characters long.");
            error_count++;
        } else if (!passregex.test(new_password)) {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Your password must contain at least one number.");
            error_count++;
        } else {
            $("#validation_error_reset_password").hide();
            $("#validation_error_reset_password").html("");
        }

        if (confirm_password == "")
        {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Please confirm password.");
            error_count++;
        } else if (confirm_password.length < 6) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Password must be 6 characters long.");
            error_count++;
        } else if (!passregex.test(confirm_password)) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Your password must contain at least one number.");
            error_count++;
        } else if (new_password != confirm_password) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("New password and confirm password must be same.");
            error_count++;
        } else {
            $("#validation_error_confirm_password").hide();
            $("#validation_error_confirm_password").html("");
        }
        if (error_count > 0) {
            return false;
        } else {

            var update_expert_pwd_form_information = new FormData($('#update_expert_pwd_form')[0]);
            var form = $(this);
            $.ajax({
                type: 'post',
                url: base_url + '/updateExpertPassword',
                data: update_expert_pwd_form_information,
                processData: false,
                contentType: false,
                success: function (resp) {
                    $("#update_expert_pwd_form")[0].reset();
                    $("#success_msg_expert_edit").html("<div class='bg-success'>" + resp.msg + "</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                    $("#success_msg_expert_edit").show();
                    scroll_to_error('#success_msg_expert_edit');
                    return false;

                },
                error: function (jqXHR, exception) {
                   displayErrorMessage(jqXHR, exception);
                }
            });
        }
    });
    
    if (typeof birth_date != 'undefined' && birth_date != '') {
        $('.dob-day').val(birth_date);
        $('.dob-month').val(birth_month);
        $('.dob-year').val(birth_year);
        $('.selectpicker').selectpicker('refresh');
    }

});
$('.indexes').on('keydown', function (e) {
    if (e.keyCode == 13)
    {
        e.preventDefault();
        return false;
    }
});
