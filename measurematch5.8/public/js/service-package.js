var THOUSAND = 1000;
var FIVE_HUNDRED = 500;
var TWO_THOUSAND = 2000;
var source;
var tag_values = [];
var welcome_service_package_status = $('#welcome_service_package_status').val().trim();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});
function udpateUserSettings(data_to_update) {
    var result = '';
    $.ajax({
        type: 'put',
        url: base_url + '/updateusersetting',
        async: false,
        data: data_to_update,
        success: function (response) {
            result = response;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return result;
}

function emptyAmounts(){
    $('.paid_exp').text('-');
    $('.paid_mm').text('-')
}

function priceValidations(rate) {
    var numbers = /^[0-9]+$/;
    if (rate == "")
    {
        $(".validation_error_price").text("Please add service package price");
        emptyAmounts();
        return false;
    } else if (!numbers.test(rate))
    {
        $(".validation_error_price").text("Please enter only digits in price");
        emptyAmounts();
        return false;
    } else {
        if (rate < 200) {
            $(".validation_error_price").text("Service Packages must be a minimum of $200 in value");
            emptyAmounts();
            return false;
        } else if (rate > 100000) {
            $(".validation_error_price").text("Service Packages cannot be more than $100,000 in value");
            emptyAmounts();
            return false;
        } else {
            $(".validation_error_price").text("");
            return true;
        }
    }
}
$(document).ready(function (e) {
    if($('.skill-button').length){
       $(".skill-button").each(function(){
            tag_values.push($(this).text());
        });
    }
    var rate = $('#price').val();
    if ($('#price').val() != '' && priceValidations(rate.replace(/\,/g, ""))) {
        budgetBreakdownCalculation(rate.replace(/\,/g, ""));
        if($('.paid_exp').text() != '-' && $('.paid_exp').text() != '' && $("#subscription_type option:selected").val() == 'monthly_retainer'){
            $('.sub_type').text('/month')
        }
    }
    
    if (welcome_service_package_status == '') {
        $('#welcome_to_service_package').modal('show');
    }
    $('#create-package-step-1').on('click', function () {
        var error_count = 0;
        $(".error-message").text("");
        var name = $('#name').val();
        var description = $('#description').val();
        var subscription_type = $('#subscription_type').val();
        $("label[id='package']").html("");
        if (subscription_type == "one_time_package") {
            $("label[id='package']").html("One-Time Package Price");
            $(".month-lable").remove();
        } else {
            $("label[id='package']").html("Subscription Package Price (per month)");
            $(".month-lable").remove();
        }
        if (name == '') {
            ++error_count;
            $(".validation_error_name").text("Please add package name").show().fadeOut(5000);
        }
        if (description == '') {
            ++error_count;
            $(".validation_error_description").text("Please add package description").show().fadeOut(5000);
        }
        if (subscription_type == '') {
            ++error_count;
            $(".validation_error_subscription_type").text("Please select package subscription").show().fadeOut(5000);
        }

        if (error_count > 0) {
            var body = $("html, body");
            body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

            });
            return false;
        } else {
            var body = $("html, body");
            body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

            });
            $('.step-1').removeClass('current-step');
            $('.step-2').addClass('current-step');
            $('#service-package-step-1').hide().fadeOut(THOUSAND);
            $('#service-package-step-2').show().fadeIn(THOUSAND);
        }


    });
    $('#create-package-step-2').on('click', function () {
        var error_count = 0;
        $(".error-message").text("");
        var manual_skills = $('.add-skill-button-block').find('span');
        var other_skill = $('#add_service_package_type_manually').val();
        var all_manual_skills = [];
        $(manual_skills).each(function (i, e) {
            all_manual_skills.push($(this).text().trim());
        });
        var value_replaced_manually = $('#addskill_manually').val().replace(/,+$/, '');
        if ($.inArray(value_replaced_manually.charAt(0).toUpperCase() + value_replaced_manually.substr(1), all_manual_skills) != '-1') {
            $(".validation_error_manual_skills").text("You cannot have same tag again").addClass('has_error');
            $('.validation_error_manual_skills').fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
            ++error_count;
        } else {
            if (value_replaced_manually.trim().length != 0) {
                $(".addskill").append('<span class="skill-button">' + value_replaced_manually.charAt(0).toUpperCase() + value_replaced_manually.substr(1) + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
            }

            $("#addskill_manually").val("");

            all_manual_skills.push(value_replaced_manually.trim().charAt(0).toUpperCase() + value_replaced_manually.trim().substr(1));
            $('#manual_skills').val(all_manual_skills);
            var manual_skills = $('.add-skill-button-block').find('span');
            var service_package_category = $('#service_package_category').val();
            var service_package_type = $("#service_package_type_featured option:selected").val();
            if (service_package_type == '') {
                ++error_count;
                $(".validation_error_service_package_type").text("Please select package type").show().fadeOut(5000);
            } else if (service_package_type == 'Other' && other_skill == ''){
                ++error_count;
                $(".validation_error_service_package_type").text('Hold your horses! As you chose "Other", please define the Service Package Type here').show().fadeOut(5000);
            }
            if (service_package_category == '') {
                ++error_count;
                $(".validation_error_service_package_category").text("Please select  package category").show().fadeOut(5000);
            }
            if (manual_skills.length == 0) {
                $(".validation_error_manual_skills").text("Please add package tags").show().fadeOut(5000);
                ++error_count;
            } else if ((manual_skills.length) < 3)
            {
                $(".validation_error_manual_skills").text("Please enter at least 3 tags").show().fadeOut(5000);
                ++error_count;
            }

            if (error_count > 0) {
                var body = $("html, body");
                body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

                });
                return false;
            } else {
                var body = $("html, body");
                body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

                });
                $('.step-2').removeClass('current-step');
                $('.step-3').addClass('current-step');
                $('#service-package-step-2').hide().fadeOut(THOUSAND);
                $('#service-package-step-3').show().fadeIn(THOUSAND);
            }
        }



    });
    $('#create-package-step-3').on('click', function () {
        var deliverable_value = [];
        var error_count = 0;
        $('.deliverables').each(function () {
            if ($(this).val().trim() != '') {
                deliverable_value.push($(this).val());
            }
        });
        var buyer_remarks = $('#buyer_remarks').val();
        if (deliverable_value.length == 0) {
            ++error_count;
            $(".validation_error_deliverables").text("Please add at least 1 deliverable for your package").show().fadeOut(5000);
        }
        if (buyer_remarks == '') {
            ++error_count;
            $(".validation_error_buyer_remarks").text("Please add what do you need from the Client").show().fadeOut(5000);
        }

        if (error_count > 0) {
            var body = $("html, body");
                body.stop().animate({scrollTop: $('.error-message:visible:first').offset().top-100}, FIVE_HUNDRED, 'swing', function () {
            });
            return false;
        } else {
            var body = $("html, body");
                body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {
            });
            $('.step-3').removeClass('current-step');
            $('.step-4').addClass('current-step');
            $('#service-package-step-3').hide().fadeOut(THOUSAND);
            $('#service-package-step-4').show().fadeIn(THOUSAND);
        }


    });
    $('#submit-preview').on('click', function () {
        var deliverable_value = [];
        var error_count = 0;
        var price = $('#price').val();
        var duration = $('#duration').val();
        var publish = $('#publish').val();

        if (!priceValidations(price.replace(/\,/g, ""))) {
            ++error_count;
            $(".validation_error_price").show().fadeOut(5000);
        }
        if (duration == '') {
            ++error_count;
            $(".validation_error_duration").text("Please add package commitment time").show().fadeOut(5000);
        }
        if (error_count > 0) {
            var body = $("html, body");
            body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

            });
            return false;
        } else {
            var name = $('#name').val().replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,"");
            var description = $('#description').val().replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,"").replace(/\r\n|\r|\n/g,"<br />");
            var subscription_type = $('#subscription_type').val();
            $('.deliverables').each(function () {
                if ($(this).val().trim() != '') {
                    deliverable_value.push($(this).val().replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,"").replace(/\r\n|\r|\n/g,"<br />"));
                }

            });
            var buyer_remarks = $('#buyer_remarks').val().replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,"").replace(/\r\n|\r|\n/g,"<br />");
            if (subscription_type == 'one_time_package') {
                $('#package_price').text('$' + price);
                $('#package_days').text(duration + ' days');
            } else {
                $('#package_price').text('$' + price + '/month');
                $('#package_days').text(duration + ' days/month');
            }
            $('#package_name').text(name);
            $('#package_description').html(description);
            $('#sp_categories_name').text("");
            $('#sp_categories_name').text($('#service_package_category :selected').text());
            $("#package_deliverables").html("");
            $(deliverable_value).each(function (index, element) {
                $("#package_deliverables").append("<li>" + element + "</li>");
            });
            $('#package_buyer_remarks').html(buyer_remarks);
            $("#sp_tags_name").html("");
            var tags_value = $('#manual_skills').val();

            $(tags_value.split(',')).each(function (index, element) {
                if (element != '') {
                    $("#sp_tags_name").append("<span class='skill-button'>" + element + "</span>");
                }
            });
            $('#package_buyer_remarks').html(buyer_remarks);
            if (publish == '') {
                $('#update_service_package').val('Publish');
                $('#save_to_draft').html('<input id="save-to-draft" value="Save to Drafts" class="continue-btn green_gradient standard-btn" type="button">');
            }
            $('#service-package-preview').modal('show');
        }
    });
    $('#got_it_tooltip_button').on('click', function () {
        var response = udpateUserSettings({service_package_welcome_email: true});
        if (response.success == 1) {
            $('#welcome_to_service_package').modal('hide');
        }
    });
    $('#service-package-back-1').on('click', function () {
        var body = $("html, body");
        body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

        });
        $('.step-2').removeClass('current-step');
        $('.step-1').addClass('current-step');
        $('#service-package-step-2').hide().fadeOut(THOUSAND);
        $('#service-package-step-1').show().fadeIn(THOUSAND);
    });
    $('#service-package-back-2').on('click', function () {
        var body = $("html, body");
        body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

        });
        $('.step-3').removeClass('current-step');
        $('.step-2').addClass('current-step');
        $('#service-package-step-3').hide().fadeOut(THOUSAND);
        $('#service-package-step-2').show().fadeIn(THOUSAND);
    });
    $('#service-package-back-3').on('click', function () {
        var body = $("html, body");
        body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {

        });
        $('.step-4').removeClass('current-step');
        $('.step-3').addClass('current-step');
        $('#service-package-step-4').hide().fadeOut(THOUSAND);
        $('#service-package-step-3').show().fadeIn(THOUSAND);
    });
    $('#continue-editing').on('click', function () {
        var body = $("html, body");
        body.stop().animate({scrollTop: 10}, FIVE_HUNDRED, 'swing', function () {
        });
        $('.step-2').removeClass('current-step');
        $('.step-3').removeClass('current-step');
        $('.step-4').removeClass('current-step');
        $('.step-1').addClass('current-step');
        $('#service-package-preview').modal('hide');
        $('#service-package-step-2').hide().fadeOut(THOUSAND);
        $('#service-package-step-3').hide().fadeOut(THOUSAND);
        $('#service-package-step-4').hide().fadeOut(THOUSAND);
        $('#service-package-step-1').show().fadeIn(THOUSAND);
    });
    $('#subscription_type').on('change', function () {
        if ($(this).val() == 'one_time_package') {
            $('#service-package-step-3 label#package').text("Subscription Package Price (one time)");
            $('#service-package-step-3 label#commitment_duration').text("Maximum time commitment (one time)");
            $('#budget_breakdown').text('Budget Breakdown');
            $('#commitment_duration').text('Expected time commitment');
            $('.sub_type').text('')
        } else if ($(this).val() == 'monthly_retainer') {
            $('#service-package-step-3 label#package').text("Subscription Package Price (per month)");
            $('#service-package-step-3 label#commitment_duration').text("Maximum time commitment (per month)");
            $('#budget_breakdown').text('Monthly Budget Breakdown');
            $('#commitment_duration').text('Expected monthly time commitment');
            if($('.paid_exp').text() != '-' && $('.paid_exp').text() != ''){
                $('.sub_type').text('/month')
            }
            
        } else {
            return false;
        }

    });
    $('#service_package_type_featured').on('change', function () {
        var current_element = $(this);
        if (current_element.val() == 'Other'){
            $('#add_service_package_type_manually').show();
        } else {
            $('#add_service_package_type_manually').val('');
            $('#add_service_package_type_manually').hide();
        }
    });
    $('#price').on("keyup", function (event) {
        var rate = $(this).val().trim();

        if (event.which >= 37 && event.which <= 40)
            return;

        $(this).val(function (index, value) {
            return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    ;
        });
        budgetBreakdownCalculation(rate.replace(/\,/g, ""));
        event.preventDefault();
        $('.sub_type').text('')
        if($('.paid_exp').text() != '-' && $('.paid_exp').text() != '' && $("#subscription_type option:selected").val() == 'monthly_retainer'){
            $('.sub_type').text('/month')
        }
    });
    var maxField = 10;
    var addButton = $('.add-deliverable-link');
    var wrapper = $('.deliverable-panel');
    var fieldHTML = '<div>\n\
                        <textarea name="deliverables[]" value="" class="deliverables add_description" \n\
                        placeholder="Deliverables" style="min-height:80px;"></textarea><a href="javascript:void(0);" \n\
                        class="remove_button" title="Remove">Remove</a>\n\
                    </div>';
    var x = 1;
    $(addButton).click(function () {
        if (x < maxField) {
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
    $("#submit_service_package").on("click", function () {
        $(this).attr('disabled', 'disabled');
        $('#price').val($('#price').val().replace(/\,/g, ""));
        $("#creat_service_package_form").submit();
    });
    $("#update_service_package").on("click", function () {
        $(this).attr('disabled', 'disabled');
        $('#price').val($('#price').val().replace(/\,/g, ""));
        $("#publish").val("True");
        $("#update_service_package_form").submit();
    });

    $("#save_to_drafts_service_package").on("click", function () {
        $(this).attr('disabled', 'disabled');
        $("#publish").val("False");
        $("#creat_service_package_form").submit();
    });

    $('.summaryT').keypress(function (e) {
        if (e.which != 13) {
            $(this).blur();
        }
    });

    $("body").on('click', function () {
        if (!$("#addskill_manually").is(":focus")) {
            $(".add-skill-button-block").removeClass("service-tag-top");
        }
    });
    $("body #addskill_manually").on('click focus', function () {
        $(".add-skill-button-block").addClass("service-tag-top");
    });

});
$("#addskill_manually").on('click keyup', function (event) {
    $('.add-skill-button-block').addClass('service-tag-top');
    if (event.keyCode === 13 || event.keyCode === 188) {

        var val = $(this).val().replace(/,+$/, '');

        if (val.trim().length != 0) {
            if ($.inArray(val.charAt(0).toUpperCase() + val.substr(1).trim(), tag_values) == '-1') {
                tag_values.push(val.charAt(0).toUpperCase() + val.substr(1).trim());
                if (val.trim().length < 61) {
                    $(".addskill").append('<span class="skill-button">' + val.charAt(0).toUpperCase() + val.substr(1) + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
                    $("#addskill_manually").val("");
                    $(this).blur();
                    $(this).focus();
                } else {
                    $(".validation_error_manual_skills").text("Skill can't be more than 60 characters").addClass('has_error');
                    $('.validation_error_manual_skills').fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');

                }
            } else {
                $(".validation_error_manual_skills").text("You cannot have same tag again").addClass('has_error');
                $('.validation_error_manual_skills').fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');

            }
        }

    }
    
    if (event.keyCode === $.ui.keyCode.TAB) {
        event.preventDefault();
    }
});

var textType = $('#add_service_package_type_manually').val();
$.ajax({
    type: 'get',
    url: base_url + '/servicepackagetypes?exclude_featured=true',
    data: {'textType': textType},
    success: function (data) {
        source = data;
    },
    error: function (jqXHR, exception) {
        displayErrorMessage(jqXHR, exception);
    }
});
$(document).on("click", "#save-to-draft", function () {
    $(this).attr('disabled', 'disabled');
    $('#price').val($('#price').val().replace(/\,/g, ""));
    $("#publish").val("False");
    $("#update_service_package_form").submit();
});
$(document).on('click', '.skill-button .black_cross_link', function () {
    var index = tag_values.indexOf($(this).parents('.skill-button').text());
    if (index > -1) {
        tag_values.splice(index, 1);
    }
    $(this).parents('.skill-button').remove();
});
$(document).on('click focus', '#add_service_package_type_manually', function () {
    var srcs = source;
    $("#add_service_package_type_manually").autocomplete({
        open: function (event, ui) {
            if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
            }
        },
        classes: {
            "ui-autocomplete": "autocomplete-drop-custom",
        },
        source: srcs, minLength: 0, autoFocus: true
    }).focus(function () {
        $(this).data("uiAutocomplete").search('');
    });
});
