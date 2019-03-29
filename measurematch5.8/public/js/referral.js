$(document).ready(function () {

    var maximum_limit = 50; //Input fields increment limitation
    var add_button = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var initial_count = 1; //Initial field counter is 1
    var removed_array = [];
    $(add_button).on('click',function () {

        //Once add button is clicked
        if (initial_count < maximum_limit) { //Check maximum number of input fields
            initial_count++; //Increment field counter

            $(wrapper).append('<div class="referral-expert"><input class="referal_email" id="email_' + initial_count + '" type="text" name="email[]" value=""/><a id="' + initial_count + '" href="javascript:void(0);"  class="remove_button standard-btn" title="Add field">Remove email </a><div class="removeErrMsg validate_error_' + initial_count + '"></div></div>'); // Add field html
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
        e.preventDefault();
        var removed_id = $(this).attr('id');

        removed_array.push(removed_id);
        $(this).parent('div').remove(); //Remove field html
    });

    $('#refer_experts').on('click', function (e) {
        var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var email_count = 0;
        var error_count = 0;
        for (i = 1; i <= initial_count; i++) {
            if (jQuery.inArray(i.toString(), removed_array) == -1)
            {
                var referral_email = $('#email_' + i).val().trim();
                email_count++;
                if (referral_email == '') {
                    $('.validate_error_' + i).html('Please add referral expert email').fadeIn('slow').delay(2000).fadeOut();
                    error_count = 1;
                } else if (!emailformat.test(referral_email))
                {
                    $('.validate_error_' + i).html('Please add valid email').fadeIn('slow').delay(2000).fadeOut();
                    error_count = 1;
                } else if (emailformat.test(referral_email)) {
                    $.ajax({
                        type: 'post',
                        url: base_url + '/userCheckExist',
                        data: {email: referral_email, email_count: email_count},
                        async: false,
                        success: function (response) {
                            if (response == '0') {
                                error_count = 1;
                                $('.validate_error_' + i).html('Email already registered').fadeIn('slow').delay(2000).fadeOut();
                            }
                        }
                    });

                } else
                {
                    $('.removeErrMsg').html('');
                    error_count = 0;
                }
            }
        }
        if (error_count == 0) {
            var error_div = 0;
            var i, j;
            var input_class = $(".referal_email");
            for (var i = 0; i < input_class.length; i++) {
                for (j = i + 1; j < input_class.length; j++) {              // inner loop only compares items j at i+1 to n
                    if ($(input_class[i]).val() === $(input_class[j]).val()) {
                        error_div = j + 1;
                    }
                }
            }
            if (parseInt(error_div) > 0) {
                $('.validate_error_' + parseInt(error_div)).html('Please enter unique email').fadeIn('slow').delay(2000).fadeOut();
                return false;
            }
        }
        if (error_count != 1) {
            $('#email_count').val(email_count);

            var formData = new FormData($('#refer_expert')[0]);
            $.ajax({
                type: 'post',
                url: base_url + '/referExpert ',
                data: formData,
                processData: false, //Add this
                contentType: false, //Add this
                success: function (result) {
                    if (result == '1') {
                        window.location.href = base_url + '/expert/profile-summary';
                    }
                }
            });
        }
    });
});