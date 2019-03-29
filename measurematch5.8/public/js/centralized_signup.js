function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 110)
    }, 400);
}

function reset_errors() {
    $('.email_error').removeClass('has_error').text('');
    $('.fname_error').removeClass('has_error').text('');
    $('.lname_error').removeClass('has_error').text('');
    $('.password_error').removeClass('has_error').text('');
    $('.mobile_error').removeClass('has_error').text('');
    $('.expert_type_error').removeClass('has_error').text('');
    $('#company_name_error').removeClass('has_error').text('');
    $('#company_website_error').removeClass('has_error').text('');
}
function displayErrorMessage(jqXHR, exception){
    var msg = '';
    if (jqXHR.status === 0) {
        msg = 'Not connect.\n Verify Network.';
    } else if (jqXHR.status == 404) {
        msg = 'Requested page not found. [404]';
    } else if (jqXHR.status == 500) {
        msg = 'Internal Server Error [500].';
    } else if (exception === 'parsererror') {
        msg = 'Requested JSON parse failed.';
    } else if (exception === 'timeout') {
        msg = 'Time out error.';
    } else if (exception === 'abort') {
        msg = 'Ajax request aborted.';
    } else {
        msg = 'Uncaught Error.\n' + jqXHR.responseText;
    }
    console.log(msg);
}
function validateBuyerEmail(email){
     $.ajax({
        type: 'get',
        url: check_buyer_email_url + '/' + email.trim(),
        async: false,
        success: function (response) {
            if (response == 0) {
                setTimeout(function(){
                    $(".email_error").html('Please sign up with your work email address.').addClass('has_error');
                    scroll_to_error('.has_error:visible:first');
                    $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
                    return false
                }, 10);
            } else {
                $('.buyer-signup-first-step').addClass('hide');
                $('.loader-data').removeClass('hide');
                populateBuyerDetailsFromClearBit(email.trim());
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}

function populateBuyerDetailsFromClearBit (email) {
    $.ajax({
        type: 'get',
        url: base_url + '/get-buyer-details?email=' + email,
        success: function (response) {
            if ((!response.hasOwnProperty('error')
                || !response.hasOwnProperty('queued'))
                && response.hasOwnProperty('name')) {
                $('#buyer_first_name').val(response.name.givenName);
                $('#buyer_last_name').val(response.name.familyName);
                $('#company_name').val(response.employment.name);
                $('#company_website').val(response.employment.domain);
            }
            $('.loader-data').addClass('hide');
            $('.buyer-signup-last-step').removeClass('hide');
            $('.first_name:visible').focus();
        },
        error: function (jqXHR, exception) {
            $('.loader-data').addClass('hide');
            $('.buyer-signup-last-step').removeClass('hide');
            displayErrorMessage(jqXHR, exception);
        }
    });
}

$(function () {
    setTimeout(function () {
        $('.work_email:visible').focus();
    }, 200);
    
    $('.mobile_number').on('keypress', function(ev) {
        allowNumbersOnly(ev);
    });

    $('.mobile_number').bind("paste",function(e) {
      e.preventDefault();
    });
    
    $('.buyer-signup-first-step, expert-signup-first-step').keypress(function (e) {
        var key = e.which;
        if(key == 13)
        {
            $('input[value = Next]').click();
            return false;  
        }
    });
    
    $('.complete-buyer-first-step, .complete-expert-first-step').on('click', function(e){
        e.preventDefault();
        var is_expert = false;
        if ($(this).hasClass('complete-expert-first-step')) {
            is_expert = true;
        }
        var error_count = 0;
        var email = $('.work_email:visible').val().trim();
        var password = $(".password:visible").val().trim();
        var pass_regex = /^(?=.*[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~])(?=.*\d)[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~\d]{6,}$/;
        var email_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var terms_conditions_checkbox = $(".terms-and-conditions:visible").is(':checked');
        $('.has_error').show();
        if (email == ""){
            $(".email_error:visible").html('Please enter your email').addClass('has_error');
            error_count++;
        } else if (!email_format.test(email)) {
            $(".email_error:visible").html('Please enter a valid email address').addClass('has_error');
            error_count++;
        } else {
            $(".email_error:visible").html('').removeClass('has_error');
        }
        if (password == ""){
            $(".password_error:visible").html('Please enter password').addClass('has_error');
            error_count++;
        } else if (password.length < 6){
            $(".password_error:visible").text("Please enter minimum 6 characters").addClass('has_error');
            error_count++;
        } else if (!pass_regex.test(password)){
            $(".password_error:visible").html('Please enter a password with a minimum of 6 characters and 1 number').addClass('has_error');
            error_count++;
        } else {
            $(".password_error:visible").html('').removeClass('has_error');
        }

        if ($('.terms-and-conditions:visible').is(':visible')){
            if (!terms_conditions_checkbox){
                $(".terms-and-conditions-error:visible").text("You must read our terms of service before continuing").addClass('has_error');
                error_count++;
            } else {
                $(".terms-and-conditions-error:visible").html('').removeClass('has_error');
            }
        }


        
        if (error_count == 0) {
            $.ajax({
                type: 'get',
                url: check_email_url + '/' + email,
                async: false,
                success: function (response) {
                    if (response == 0) {
                        setTimeout(function(){
                            $(".email_error").html('Please enter a unique email. This email already exists!').addClass('has_error');
                            $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
                        }, 10);
                        return false;
                    } else {
                        if (is_expert) {
                            $('.expert-signup-first-step').addClass('hide');
                            $('.expert-signup-last-step').removeClass('hide');
                            $('.first_name:visible').focus();
                            return false;
                        }
                        validateBuyerEmail($('#buyer_email').val().trim());
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
        } else {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
            return false;
        }
    });
    $('.create_account').on('click', function (e) {
        e.preventDefault();
        var error_count = 0;
        var mobile_number = $('.mobile_number:visible').val().trim();
        var fname = $(".first_name:visible").val().trim();
        var lname = $(".last_name:visible").val().trim();

        var expected_project_post_time = $("#expected_project_post_time").length == 0 ? '' :  $("#expected_project_post_time").val().trim();
        var numbers_regex = /^[0-9]+$/;
        var urlregex = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;
        $('.has_error').show();
        reset_errors();

        if (mobile_number == ""){
            $(".mobile_error:visible").html('Please enter your mobile number').addClass('has_error');
            error_count++;
        } else if (!numbers_regex.test(mobile_number)) {
            $(".mobile_error:visible").html('Please enter only digits in phone number').addClass('has_error');
            error_count++;
        } else if (mobile_number.length < 5) {
            $(".mobile_error:visible").html('Please enter atleast 5 digits in phone number').addClass('has_error');
            error_count++;
        } else {
            $(".mobile_error:visible").html('').removeClass('has_error');
        }

        if (fname == "" || lname == ""){
            $(".fname_error:visible").text("Please enter your full name").addClass('has_error');
            error_count++;
        } else {
            $(".fname_error:visible").html('').removeClass('has_error');
        }
        if ($('#expected_project_post_time').length > 0) {
            if (expected_project_post_time == "") {
                $("#expected_project_post_time_error").text("Please select one option from list").addClass('has_error');
                error_count++;
            } else {
                $("#expected_project_post_time_error").html('').removeClass('has_error');
            }
        }
        if ($('#expert_type').is(':visible')){
            var expert_type = $("#expert_type").val().trim();
            if (expert_type == ""){
                $(".expert_type_error").html("Please choose the service provider type").addClass('has_error');
                error_count++;
              } else {
                $(".expert_type_error").html('').removeClass('has_error');
            }
        } else {
            $(".expert_type_error").html('').removeClass('has_error');
        }
        if ($('#company_name').is(':visible')){
            var company_name = $("#company_name").val().trim();
            if (company_name == ""){
                $("#company_name_error").text("Please enter your company name").addClass('has_error');
                error_count++;
              } else {
                $("#company_name_error").html('').removeClass('has_error');
            }
        } else {
            $("#company_name_error").html('').removeClass('has_error');
        }
        if ($('#company_website').is(':visible')){
            var company_website = $("#company_website").val().trim();
            if (company_website == ""){
                $("#company_website_error").text("Please enter your company website URL").addClass('has_error');
                error_count++;
            } else if (!urlregex.test(company_website)){
                $("#company_website_error").text("Please enter a valid URL").addClass('has_error');
                error_count++;
            } else {
                $("#company_website_error").html('').removeClass('has_error');
            }
        } else {
            $("#company_website_error").html('').removeClass('has_error');
        }

        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(3000).fadeOut('fast');
            return false;
        } else {
            if ($('#company_name').is(':visible')) {
                $('#buyer_signup_form').submit();
            } else {
                $('#expert_signup_form').submit();
            }
            
        }
    });
    $('.password').on('keyup', function(){
        if ($(this).val().length > 0) {
            $('.show-charter').removeClass('hide');
        } else {
            $('.show-charter').addClass('hide');
        }
    });
    $(document).on('click', '.show-charter', function(){
        $(".password:visible").attr('type', 'text');
        $(this).addClass('password-revealed');
        $(this).children('img').attr('src', base_url + '/images/hide-eye.svg');
    });
    $(document).on('click', '.password-revealed', function(){
        $(".password:visible").attr('type', 'password');
        $(this).removeClass('password-revealed');
        $(this).children('img').attr('src', base_url + '/images/eye.svg');
    });
    $('#expert_signup').click(function(e){
       $('#expert_signup_form')[0].reset();
        var new_url = base_url + '/signup?expert';
        history.pushState({}, null, new_url);
        $("#right_banner").removeClass('hide');
        $("#buyer_right_banner").addClass('hide');
        $("#expert_right_banner").removeClass('hide');
       
    });
    $('#buyer_signup').click(function(e){
        $('#buyer_signup_form')[0].reset();
        var new_url = base_url + '/signup?buyer';
        history.pushState({}, null, new_url);
        $("#right_banner").removeClass('hide');
        $("#buyer_right_banner").removeClass('hide');
        $("#expert_right_banner").addClass('hide');
    });
    if (window.location.href.indexOf("expert") > -1) { 
        $("#account_type_expert").show();
        $("#account_type_buyer").hide();
        $("#expert_signup").attr("checked", "checked");
        $("#expert_signup").parent().addClass('user-type-selection');
        $("#right_banner").removeClass('hide');
        $("#buyer_right_banner").addClass('hide');
        $("#vendor_right_banner").addClass('hide');
        $("#expert_right_banner").removeClass('hide');
    } else if (window.location.href.indexOf("buyer") > -1 || window.location.href.indexOf("vendor") > -1) {
        $("#account_type_buyer").show();
        $("#account_type_expert").hide();
        $("#buyer_signup").attr("checked", "checked");
        $("#buyer_signup").parent().addClass('user-type-selection');
        $("#right_banner").removeClass('hide');
        $("#expert_right_banner").addClass('hide');
        $("#vendor_right_banner").addClass('hide');
        $("#buyer_right_banner").addClass('hide');
        if (window.location.href.indexOf("vendor") > -1) {
            $("#vendor_right_banner").removeClass('hide');
        } else {
            $("#buyer_right_banner").removeClass('hide');
        }

    }

    $("input[name='account_type']").click(function() {
        var account_type = $(this).val();
        $("div.signup-form-panel").hide();
        $("#account_type_" + account_type).show();
    });
    $('input.inputradiostyle').click(function () {
        if ($('input:not(:checked)')) {
             $('div').removeClass('user-type-selection');
        }
        if ($('input').is(':checked')) {
            $(this).parent().addClass('user-type-selection');
        }
        
    });
    $('#resend_email').click(function(){
    $.ajax({
        type: 'get',
        url: base_url + '/resendverificationemail?email=' + $('#user_email').text().trim(),
        async: false,
        success: function (response) {
            if (response.success == 1) {
                $('#success').show().fadeIn('fast').delay(3000).fadeOut('fast');
                $('#warning').hide();
            } else {
                $('#success').hide();
                $('#warning').show().fadeIn('fast').delay(3000).fadeOut('fast');
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    });
   
});

var input = $(".mobile_number");
input.intlTelInput({
   preferredCountries: ['gb', 'us'],
   separateDialCode: true,
   formatOnDisplay: false,
   utilsScript: './js/international-phone-codes-utils.js',

});
$('body').find('.expert-signup-last-step').on('click', '.country', function() {
    setTimeout(function() {
        var getCode = input.intlTelInput('getSelectedCountryData').dialCode;
        $('body').find('.country_code').val(getCode);
    }, 200)

})
var input_buyer = $(".mobile_number_buyer");
input_buyer.intlTelInput({
    preferredCountries: ['gb', 'us'],
    separateDialCode: true,
    formatOnDisplay: false,
    utilsScript: './js/international-phone-codes-utils.js',

});
$('body').find('.buyer-signup-last-step').on('click', '.country', function() {
    setTimeout(function() {
        var getCode = input_buyer.intlTelInput('getSelectedCountryData').dialCode;
        $('body').find('.country_code').val(getCode);
    }, 200)

})

var getCode = input.intlTelInput('getSelectedCountryData').dialCode;
$('.country_code').val(getCode);





