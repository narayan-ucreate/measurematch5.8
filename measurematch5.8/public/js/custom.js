var base_url;
var formmodified = 0;
/*Scroll to error for validation */
function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 100)
    }, 400);
}
function confirmExit() {
    if (formmodified === 1) {
        return "New information not saved. Do you wish to leave the page?";
    }
}
function IsEmail(email) {

    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}
function tysize() {
    var window_height = $(window).outerHeight();
    var nav_height = $('nav').outerHeight();
    var footer_height = $('footer').outerHeight();
    var main_height = window_height - nav_height - footer_height;
    if ($('.content').outerHeight() > 0)
    {
        $('.content').css('min-height', main_height);
    }
}
$(window).resize(function () {
    tysize();
});
/*!
 * Signup page jquery section
 */
$(document).ready(function () {
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#success_msg_expert_edit').fadeIn('fast').delay(4000).fadeOut('fast');
    $('select.selectpicker').on('change', function () {
        var selected = $(this).val();
        if (selected == "") {
            $(this).parent().find(".dropdown-toggle").addClass('bs-placeholder');
            $(this).parent().find(".dropdown-toggle").removeClass('textcolorchange');
        } else {
            $(this).parent().find(".dropdown-toggle").addClass('textcolorchange');
            $(this).parent().find(".dropdown-toggle").removeClass('bs-placeholder');
        }
    });
    /*!
     * End of code for client side validation of buyer signup page
     */
    $("#removeAccount").on('click',function () {
        $("div.accountInfo").empty();
        $("div.accountForm").show();
    });
    $('#editbuyerbasic *').on('change',function () {
        formmodified = 1;
    });
    $('#buyer_account_info *').on('change',function () {
        formmodified = 1;
    });
    $('#editbuyer_communication *').on('change' ,function () {
        formmodified = 1;
    });
    window.onbeforeunload = confirmExit();
    $('#delete_account').on('click' ,function () {
        $('#archive_buyer_account').modal('show');
    });
    $('#cancel_edit_company').on('click' ,function () {
        $('#confirm_cancellation').modal('show');
    });
    $(document).on('click', '#yes_delete', function () {
        $.ajax({
            type: 'post',
            url: base_url + '/deleteUserAccount',
            data: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (resp) {
                window.location.href = base_url;
                window.scrollTo(0, 0);
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            },
        });
    });
    $('#buyer_phone').on('keypress', function(ev) {
        allowNumbersOnly(ev);
    });
    $('#buyer_phone').bind("paste",function(e) {
      e.preventDefault();
    });
    $("#submit_btn").on('click' ,function () {
        var buyer_fname = $('#buyer_fname').val();
        var buyer_lname = $('#buyer_lname').val();
        var buyer_email = $('#buyer_email').val();
        var numbers_regex = /^[0-9]+$/;
        var buyer_phone = $('#buyer_phone').val();
        var has_vat = $('#have_vat:checked').val();
        var error_count = 0;
        $('.has_error').show();
        if (buyer_fname === "") {
            $("#buyer_fname_error").html("First name is required.").addClass('has_error');
            error_count++;
        } else {
            $("#buyer_fname_error").html("").removeClass('has_error');
        }
        if (buyer_lname === "") {
            $("#buyer_lname_error").html("Last name is required.").addClass('has_error');

            error_count++;
        } else {
            $("#buyer_lname_error").html("").removeClass('has_error').removeClass('has_error');
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

        if (buyer_email === "") {
            $("#buyer_email_error").html("Email is required.").addClass('has_error');
            error_count++;
        } else if (IsEmail(buyer_email) === false) {
            $("#buyer_email_error").html("Email is not valid.").addClass('has_error');
            error_count++;
        } else if ((IsEmail(buyer_email) === true) && (buyer_email !== "")) {
            $.ajax({
                type: 'post',
                url: base_url + '/buyeremailCheck',
                data: {email: buyer_email},
                async: false,
                success: function (response) {
                    if (response === 1) {
                        $("#buyer_email_error").html("Email already exist.").addClass('has_error');
                        error_count++;
                    } else {
                        $("#buyer_email_error").html("").removeClass('has_error');
                    }
                }
            });
        } else {
            $("#buyer_email_error").html("").removeClass('has_error');
        }
        if (buyer_phone == ""){
            $("#buyer_phone_error").html('Please enter your phone number').addClass('has_error');
            error_count++;
        } else if (!numbers_regex.test(buyer_phone)) {
            $("#buyer_phone_error").html('Please enter only digits in phone number').addClass('has_error');
            error_count++;
        } else if ($('.selected-dial-code:visible').text() == '') {
            $("#buyer_phone_error").html('Please select your country').addClass('has_error');
            $('.selected-flag').focus();
            error_count++;
        } else {
            $("#buyer_phone_error").html('').removeClass('has_error');
        }
        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(4000).fadeOut('fast');
            return false;
        } else {
            $('#country_code').val($('.selected-dial-code:visible').text());
            $('#editbuyerbasic').submit();
        }
    });
    $('#buyer_fname').on('keyup paste', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    });
    $('#buyer_lname').on('keyup paste', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    });
    //-------Code to validate edit seller/Expert Account info (Account Page)-----
    $("#submit_buyer_account").on('click',function () {
        $("#buyer_category_error").html("");
        $("#buyer_category_error").hide();
        var buyer_account_information = new FormData($('#buyer_account_info')[0]);
        var form = $(this);
        $.ajax({
            type: 'post',
            url: base_url + '/updatebuyeraccountinfo',
            data: buyer_account_information,
            processData: false,
            contentType: false,
            success: function (resp) {
                $("#success_msg_expert_edit").html("<div class='bg-success'>" + resp.msg + "</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                $("#success_msg_expert_edit").show();
                scroll_to_error('#success_msg_expert_edit');
                return false;
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $("#buyer_update_pass_btn").on('click' ,function () {
        var old_password = $("#old_password").val().trim();
        var new_password = $("#new_password").val().trim();
        var confirm_password = $("#confirm_password").val().trim();
        var passregex = /^(?=.*[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~])(?=.*\d)[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~\d]{6,}$/;
        var ch = 0;
        if (old_password === "")
        {
            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Please enter the current password.");
            ch++;
        } else if (old_password.length < 6) {
            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Current password must be 6 characters long.");
            ch++;
        } else if (!passregex.test(old_password)) {
            $("#validation_error_existing_password").show();
            $("#validation_error_existing_password").html("Your password must contain at least one number.");
            ch++;
        } else if (passregex.test(old_password)) {
            $.ajax({
                type: 'post',
                url: base_url + '/checkUserPassword',
                data: {'old_password': old_password},
                async: false,
                success: function (response) {
                    if (response === 1) {
                        $("#validation_error_existing_password").html("Password did not match your current password.");
                        $("#validation_error_existing_password").show();
                        ch++;
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

        if (new_password === "")
        {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Please enter the new password.");
            ch++;
        } else if (new_password.length < 6) {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Password must be 6 characters long.");
            ch++;
        } else if (!passregex.test(new_password)) {
            $("#validation_error_reset_password").show();
            $("#validation_error_reset_password").html("Your password must contain at least one number.");
            ch++;
        } else {
            $("#validation_error_reset_password").hide();
            $("#validation_error_reset_password").html("");
        }

        if (confirm_password === "")
        {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Please confirm password.");
            ch++;
        } else if (confirm_password.length < 6) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Password must be 6 characters long.");
            ch++;
        } else if (!passregex.test(confirm_password)) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("Your password must contain at least one number.");
            ch++;
        } else if (new_password !== confirm_password) {
            $("#validation_error_confirm_password").show();
            $("#validation_error_confirm_password").html("New password and confirm password must be same.");
            ch++;
        } else {
            $("#validation_error_confirm_password").hide();
            $("#validation_error_confirm_password").html("");
        }
        
        if (ch > 0) {
            return false;
        } else {
            var update_buyer_pwd_form_information = new FormData($('#update_buyer_pwd_form')[0]);
            var form = $(this);
            $.ajax({
                type: 'post',
                url: base_url + '/updateBuyerPassword',
                data: update_buyer_pwd_form_information,
                processData: false,
                contentType: false,
                success: function (resp) {
                    $("#update_buyer_pwd_form")[0].reset();
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

    //---------------Submit buyer communication --------------
    $("#save_buyer_communication").on('click' ,function () {


        var edit_buyer_communication_information = new FormData($('#editbuyer_communication')[0]);
        var form = $(this);
        $.ajax({
            type: 'post',
            url: base_url + '/updateBuyerCommunication',
            data: edit_buyer_communication_information,
            processData: false,
            contentType: false,
            success: function (resp) {
                $("#success_msg_expert_edit").html("<div class='bg-success'>" + resp.msg + "</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                $("#success_msg_expert_edit").show();
                scroll_to_error('#success_msg_expert_edit');
                return false;
            },
            error: function (jqXHR, exception) {
                                        displayErrorMessage(jqXHR, exception);

            }
        });
    });

    $('#editbuyerbuisness *').on('change' ,function () {
        formmodified = 1;
    });
    window.onbeforeunload = confirmExit();

    $("#submit_buisness").on('click' ,function () {
        var first_address = $('#first_address').val().trim();
        var location = $('#location').val().trim();
        var city_name = $('#city_name').val().trim();
        var country_name = $('#country_name').val().trim();
        var post_code = $('#post_code').val().trim();
        var error_count = 0;
        $('.has_error').show();
        if (first_address === "") {
            $("#buyer_first_error").html("First Address is required.").addClass('has_error');
            error_count++;
        } else {
            $("#buyer_first_error").html("").removeClass('has_error');
        }

        if (location === "") {
            $("#buyer_city_error").html("City is required.").addClass('has_error');
           error_count++;
        } else if (location_description.length !== 0 && location_description.indexOf($("#location").val()) <= -1) {
                $("#buyer_city_error").text('Please choose a location from the dropdown list of choices').addClass('has_error');
                $('#location').val('');
                error_count++;
        } else {
            $("#buyer_city_error").html("").removeClass('has_error');
        }

        if (post_code === "") {
            $("#buyer_code_error").html("Postal Code is required.").addClass('has_error');

            error_count++;
        } else {
            $("#buyer_code_error").html("").removeClass('has_error');

        }

        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(4000).fadeOut('fast');
            return false;
        } else {
            var edit_buyer_buisness_information = new FormData($('#editbuyerbuisness')[0]);
            var form = $(this);
            $.ajax({
                type: 'post',
                url: base_url + '/updatebuyerbuisness',
                data: edit_buyer_buisness_information,
                processData: false,
                contentType: false,
                success: function (resp) {
                    $("#success_msg_expert_edit").html('<div class="bg-success">' + resp.msg + "</div>").fadeIn('fast').delay(4000).fadeOut('fast');
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
    var buyerAddress = $('#buyerAddress').val();
    tysize();
    
    $('#nav-tabs-wrapper a[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(e.target).closest('ul').hide().prev('a').removeClass('open').text($(this).text());
    });

    $('#hide_company_from_projects').on('change',function (e) {
        if ($(this).is(':checked')) {
            if ($("#type_of_org_exists").val() == "") {
                $(".account-notificatoin").show();
                $(this).prop("checked", false);
                return false;
            } else {
                $(".type_of_org_text").hide();
            }
        }

    });
    $('.fade_error_message').fadeIn('fast').delay(5000).fadeOut('fast');

    $("#post-close-btn").on('click', function () {
        $(".successfully-posted").hide();
    });
});
