$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

$('.redeemSubmit').click(function (e) {
    var redeem_coupon_value = $('#redeemCouponValue').val();
    var contract_id = $('#contract_id').val();

    if (redeem_coupon_value == '') {
        $('.validate_expert_error').html('Please add coupon code.').fadeIn('slow').delay(2000).fadeOut();
        return false;
    } else if (redeem_coupon_value) {
        $.ajax({
            type: 'post',
            url: base_url + '/checkValidCouponCode',
            data: {'coupon': redeem_coupon_value, 'contract_id': contract_id},
            async: false,
            success: function (response) {

                if (response.status == 0) {
                    $('.validate_expert_error').html(response.message).fadeIn('slow').delay(2000).fadeOut();
                    return false;
                } else {
                    $('.validate_expert_error').html(response.message).fadeIn('slow').delay(2000).fadeOut();
                    $('.redeemPoints').hide();
                    $('#mm_fee').html("-" + response.mm_fee);
                    $('.coupon_applied_daily_rate_parent').html('<span class="coupon_applied_daily_rate">Discount applied:<span id="mm_fee"> $20 </span> </span>');
                    $('.coupon_applied_daily_rate_parent').show();
                    $('#what_you_will_get').html(response.what_you_will_get);
                    $('.coupon_popup_back-' + contract_id).click();
                    $('#discount_applied').val("1");
                    $("#" + contract_id).attr("contract_confirm", "1");
                    $('.coupon-code-block').hide()
                    return false;
                }

            }
        })
    }
});



$('#refer_experts').on('click', function (e) {
    e.preventDefault();
    var referral_name = $('#referral_name').val().trim();
    var referral_email = $('#referral_email').val().trim();
    var email_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

    if (referral_name == '') {
        $('.validate_expert_error').html('Please add referral expert name');
        return false;
    } else if (referral_email == '') {
        $('.validate_expert_error').html('Please add referral expert email');
        return false;
    } else if (!email_format.test(referral_email))
    {
        $('.validate_expert_error').html('Please add valid email');
        return false;
    } else if (email_format.test(referral_email)) {

        $.ajax({
            type: 'post',
            url: base_url + '/userCheckExist',
            data: {email: referral_email},
            async: false,
            success: function (response) {

                if (response == '1') {
                    var formData = new FormData($('#refer_expert')[0]);
                    $.ajax({
                        type: 'post',
                        url: base_url + '/referSingleExpert ',
                        data: formData,
                        processData: false, //Add this
                        contentType: false, //Add this
                        success: function (result) {
                            $('.referral_success_message').html('Your request has been sent. Thankyou.').show().fadeOut(6000);
                            $('.close').trigger('click');
                        }
                    });
                } else {
                    $(".validate_expert_error").html("Email already exist.");

                    return false;
                }
            }
        });
    } else {

    }


});
$('#referExperts').on('click', function (e) {
    $('.validate_expert_error').html('');
    $('#refer_expert')[0].reset();
});