$(document).ready(function() {
    removeUnreadNotification();
    if ($(window).width() < 1024) {
        if (!$('.create-hub-by-vendor').hasClass('pending-account-approval')) {
            $('.create-hub-by-vendor').addClass('mobile-create-hub-view');
        }
        if (window.location.href.indexOf('service-hubs/create') !== -1) {
            window.location.href = base_url + '/service-hubs';
        }
    }
    $('.mobile-create-hub-view').click(function(){
        $('body').find('#my_hubs_mobile_warning_pop_up').modal('show');
    });
});
$('.add-another-partner').click(function(){
    var latest_service_partner_row_number = parseInt($('#latest_service_partner_row_number').val());
    var row_number = latest_service_partner_row_number + 1;
    $('#latest_service_partner_row_number').val(row_number);
    var new_row = '<div class="row margin-top-20 vendor-invites-input service-partner-' + row_number + '" service-partner-row = "' + row_number + '">\n\
                        <div class="col-md-3 right-spacing-0">\n\
                            <input type="text" name="first_name[]" value="" placeholder="First name" autofocus="">\n\
                        </div>\n\
                        <div class="col-md-3 right-spacing-0">\n\
                            <input type="text" name="last_name[]" value="" placeholder="Last name">\n\
                        </div>\n\
                        <div class="col-md-6">\n\
                            <input type="text" name="email[]" value="" placeholder="e.g. tom@measurematch.com">\n\
                        </div>\n\
                        <a class="delete-btn delete_partner" href="javscript:void()" service-partner-row="' + row_number + '">\n\
                            <i class="fa fa-trash"></i>\n\
                        </a>\n\
                    </div>';
    $('.add-more-categories-block').before(new_row);
    if ($('.delete_partner').length > 3) {
        $('.delete_partner').removeClass('hide');
    }
});

if ($('.delete_partner').length > 3) {
    $('.delete_partner').removeClass('hide');
}

$('.view-service-hub').on('click', function(e) {
    var service_hub_id = $(this).attr('service-hub-id');
    e.preventDefault();
    $.ajax({
        url: base_url + '/service-hub/approved-experts/' + service_hub_id,
        success: function(response){
            if (response != 0) {
                $('#service_hub_details .modal-body').html(response);
            }
        }
    }); 
    $('#service_hub_details').modal('show')
});

$(document).on('click', '#view_more_experts', function(){
    var page_number = parseInt($(this).attr('page-number'));
    var service_hub_id = $(this).attr('service-hub-id');
    $.ajax({
       url: base_url + '/service-hub/approved-experts/' + service_hub_id + '?page=' + page_number,
       success: function(response){
           if (response != 0) {
               $('#service_hub_details .modal-body .verified-experts-block').append(response.html);
               if (response.view_more == 0) {
                   $('#service_hub_details .modal-body #view_more_experts_block').addClass('hide');
               }
           }
       }
   });
});

$('body').on('click', '#show_more', function(e) {
    $('body') .find('#truncated_description').addClass('hide');
    $('body') .find('#full_description').removeClass('hide');
});
$('body').on('click', '#show_less', function(e) {
    $('body') .find('#truncated_description').removeClass('hide');
    $('body') .find('#full_description').addClass('hide');
})

$('input[type="file"]').change(function(){
    var files = $(this).val().split('\\')
    var file = files[files.length -1]
   $('#logo_placeholder').val(file);
});
$('.add-more').on('click', function(e) {
    e.preventDefault();
    $(this).parents('div').eq(0).find('.service-category-section').append($('.service-cat-hidden').html())
    hideShowDelete();
})
$('body').on('click', '.delete-categories', function(e) {
    e.preventDefault();
    $(this).parents('.service-cat').eq(0).remove();
    hideShowDelete();
})

$('body').on('click', '#vendor-back', function(e) {
    e.preventDefault();
   $('.message-user-herder').addClass('hide')
   $('#show-user-list').removeClass('hide');
   $('.expert-profile-section').addClass('d-none');
})

$(document).on('click', '.delete_partner', function(e) {
    var this_element = $(this);
    e.preventDefault();
    if ($('.delete_partner').length > 3) {
        this_element.parent().next('span').remove();
        this_element.parents('.vendor-invites-input').eq(0).remove();
    }
    if ($('.delete_partner').length <= 3) {
        $('.delete_partner').addClass('hide');
    }
})

function removeUnreadNotification() {
    if($(document).find('.expert-profile-container').is(':visible'))
    {
        $(document).find('#show-user-list .unapproved-applicant:first .count-message').remove();
    }
}

function hideShowDelete() {
    var total = $(document).find('.service-cat').length;
    if (total > 2) {
        $('body').find('.delete-btn').removeClass('hide');
    } else {
        $('body').find('.delete-btn').addClass('hide');
    }
}

$('#create-hub-first-step').on('submit', function(e) {
    e.preventDefault();
    var form_data = new FormData();
    form_data.append('logo', $('#logo').val());
    form_data.append('steps', 1);
    $.ajax({
        method: 'post',
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url: $(this).attr('action'),
        statusCode: {
            422: function (data) {
                var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response.errors);
            }
        },
        success: function(data) {
            if (data) {
                window.location = data.redirect_url;
            }
        }
    });
})
var description_character_limit = 600;
$('#description').bind('change keyup input', function(e) {
    var total_character = $(this).val().length
    $('.validation_error').remove();
    $('.total_character_pending').text(description_character_limit - $('#description').val().length)
    if (total_character >= description_character_limit) {
        charLimit(this, description_character_limit);
        $(this).after('<span class="validation_error">Service hub description should not be more than ' + description_character_limit + ' characters.</span>');
        return false;
    }

})
var character_limit = $('#description').length > 0 ? $('#description').val().length : 0;
$('.total_character_pending').text(description_character_limit - character_limit)
$('#description').on('keypress', function(e) {
    var total_character = $(this).val().length
    if (total_character >= description_character_limit && e.which != 8) {
        return false;
    }
});

$('#create-hub-second-step').on('submit', function(e) {
    e.preventDefault();
    var form_data = new FormData();
    form_data.append('steps', 2);

    $.ajax({
        method: 'post',
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url: $(this).attr('action'),
        statusCode: {
            422: function (data) {
                rearrangeServicePartnerRows(length);
                $(document).find('.validation_error').remove();
                var response = jQuery.parseJSON(data.responseText);
                if (response.hasOwnProperty('general_error')) {
                    $('.service-partners').before('<span class="validation_error">'+response['general_error'][0]+'</span>');
                } else {
                    step2ValidationErrors(combinedErrors(response.errors));
                }
            }
        },
        success: function(data) {
            $(document).find('.validation_error').remove();
            if (data) {
                window.location = data.redirect_url;
            }
        }
    });
})

$(document).on('click', '.unapproved-applicant', function(e){
    reasignActiveClass(e);
    var this_element = $(this);
    var service_hub_associated_expert_id = this_element.attr('service_hub_associated_expert_id');
    var unread_message = $(this_element).find('.count-message');
    var unread_message_length = $(this_element).find('.count-message').length;
    var applicant_id = this_element.attr('applicant_id');
    $.ajax({
        url: base_url + '/applicants/' + service_hub_associated_expert_id + '/profile',
        type: 'GET',
        data: { applicant_id: applicant_id },
        async: false,
        success: function (response) {
            $('.default-live-hub-block').html(response);
            if ($(window).width() < 1024) {
                $('#show-user-list').addClass('hide');
                $('.expert-profile-section').removeClass('d-none');
                 $('.message-user-herder').removeClass('hide');
            }
            if(unread_message_length) {
                unread_message.remove();
            }
            windowresize();
        }
    });
});

$(document).on('click', '.approve-expert', function(){
    var expert_id = $(this).attr('user-id');
    var vendor_id = $(this).attr('vendor-id');
    var expert_name = $(this).attr('expert-first-name');
    var vendor_company_name = $(this).attr('service-hub-name');
    var background_image_url = $('.expertshow-msg-block .expert-pic .expert-profile-pic').css('background-image');
    $('.approve-expert-pop-up .expert-profile-pic').css('background-image', background_image_url);
    $('.approve-expert-pop-up .confirm-approval').attr('expert-id', expert_id);
    $('.approve-expert-pop-up .confirm-approval').attr('vendor-company-name', vendor_company_name);
    $('.approve-expert-pop-up .confirm-approval').attr('vendor-id', vendor_id);
    $('.approve-expert-pop-up .applicant-first-name').text(expert_name);
    $('.approve-expert-pop-up').modal('show');
});

$(document).on('click', '.decline-expert', function(){
    $("#decline_applicant_form")[0].reset();
    var applicant_id = $(this).attr('applicant-id');
    var vendor_id = $(this).attr('vendor-id');
    var expert_id = $(this).attr('user-id');
    var vendor_company_name = $(this).attr('service-hub-name');
    var expert_name = $(this).attr('expert-first-name');
    var background_image_url = $('.expertshow-msg-block .expert-pic .expert-profile-pic').css('background-image');
    $('.decline-expert-pop-up .expert-profile-pic').css('background-image', background_image_url);
    $('.decline-expert-pop-up #applicant_id').val(applicant_id);
    $('.decline-expert-pop-up #expert_id').val(expert_id);
    $('.decline-expert-pop-up #vendor_company_name').val(vendor_company_name);
    $('.decline-expert-pop-up .applicant-first-name').text(expert_name);
    $('.decline-expert-pop-up .decline-applicant').attr('vendor-id', vendor_id);
    if (!$('.decline-expert-pop-up .decline-applicant').hasClass('disable-btn')) {
        $('.decline-expert-pop-up .decline-applicant').removeClass('standard-btn').addClass('disable-btn');
    }
    $('.decline-expert-pop-up').modal('show');
});

$(document).on('click', '.confirm-approval', function(){
    var expert_id = $(this).attr('expert-id');
    var vendor_id = $(this).attr('vendor-id');
    var vendor_company_name = $(this).attr('vendor-company-name');
    $.ajax({
        type: 'put',
        url: base_url + '/expert/approve/' + expert_id,
        async: false,
        data: {expert_id: expert_id, vendor_company_name: vendor_company_name},
        success: function (response){
            if (response == '1') {
                getExpertsList(vendor_id);
                serviceHubRightHandSection(vendor_id);
                $('.approve-expert-pop-up').modal('hide');
            }
            setTimeout(
            function () {
                windowresize();
                removeUnreadNotification();
            }, 1000);
        }
    });
});

$(document).on('click', '.decline-applicant', function(){
    var data = $('#decline_applicant_form').serialize();
    var vendor_id = $(this).attr('vendor-id');
    $.ajax({
        type: 'post',
        url: base_url + '/expert/decline',
        async: false,
        data: data,
        success: function (response){
            if (response == '1') {
                getExpertsList(vendor_id);
                serviceHubRightHandSection(vendor_id);
                $('.decline-expert-pop-up').modal('hide');
            }
            setTimeout(
            function () {
                windowresize();
                removeUnreadNotification();
            }, 1000);
        } 
    });
});

$(document).on('mousemove', '.decline-expert-pop-up', function(){
    enableDeclineApplicantButton();
});

$(document).on('keyup', '.decline-expert-pop-up .decline-note', function(){
    enableDeclineApplicantButton();
});

function reasignActiveClass(event) {
    $(document).find('#show-user-list .active').removeClass('active');
    $(event.target).parents('a:first').addClass('active')
}

function enableDeclineApplicantButton() {
    if($('.decline-note').val().trim().length > 0) {
        $('.decline-applicant').removeClass('disable-btn').addClass('standard-btn');
    } else {
        $('.decline-applicant').removeClass('standard-btn').addClass('disable-btn');
    }
}

function getExpertsList(vendor_id) {
    $.ajax({
        type: 'get',
        url: base_url + '/service-hubs-experts/' + vendor_id,
        success: function (response){
           $('#show-user-list').html(response);
        } 
    });
}

function serviceHubRightHandSection(vendor_id) {
    $.ajax({
        type: 'get',
        url: base_url + '/service-hubs-right-hand-section/' + vendor_id,
        success: function (response){
           $('.default-live-hub-block').html(response);
        } 
    });
}

function rearrangeServicePartnerRows (length) {
    var service_partner_row_number;
    var count = 0;
    $(".vendor-invites-input").each(function(e){
        var this_element = $(this);
        service_partner_row_number = $(this).attr('service-partner-row');
        this_element.removeClass('service-partner-' + service_partner_row_number).addClass('service-partner-' + count);
        this_element.attr('service-partner-row', count);
        count++;
    });
}

function charLimit(input, max_char) {
    var len = $(input).val().length;
    if (len > max_char) {
        $(input).val($(input).val().substring(0, max_char));
    }
}

function combinedErrors(errors) {
    var combined_errors = [];
    var standalone_errors = [];
    for(var key in errors) {
        var splitted_key = key.split('.');
        if (splitted_key.length > 1) {
            combined_errors[splitted_key[1]] = errors[key][0];
        } else {
            standalone_errors.push(errors[key][0]);
        }
    }
    if (combined_errors.length > 0) {
        return combined_errors;
    }
    return standalone_errors;
}

function step2ValidationErrors(errors){
    $('.validation_error').remove();
    for(var key in errors) {
        $('.service-partners').find( ".service-partner-" +  key).after('<span class="validation_error">'+errors[key]+'</span>');
    }
}

function manupulateMessage(errors) {
    $('.validation_error').remove();
    for(var key in errors) {
        if (key == 'code_of_conduct' || key == 'terms_and_condition') {
            $('#'+key).parents('div').eq(0).find('label').after('<span class="validation_error">'+errors[key][0]+'</span>')
        } else {
            $('#'+key).after('<span class="validation_error">'+errors[key][0]+'</span>');
        }
        var array_validations = key.split('.');
        if (array_validations.length > 1) {
            $('.service-category-section').find( ".service-category-name:eq("+array_validations[1]+")" ).after('<span class="validation_error">'+errors[key][0]+'</span>');
        }
    }
    var scroll_to=$('.validation_error:visible:first').parents('.input-bx').find('label');
    scroll_to_error(scroll_to);
}


$(window).resize(function () {
    windowresize();
});

$(window).load(function () {
    windowresize();
});


function windowresize() {
    window_height = $(window).outerHeight();
    header_height = 100;
    min_height = window_height - header_height;
    var message_header_height = $('body').find('#myproject_section_header').outerHeight();
    min_height = min_height - message_header_height;
    $('.seller-message-list-view').css('height', min_height);
    $('.user-list-panel').css('height', min_height);
    $('.expert_profile_inner_panel').css('height', min_height);
    $('.create-service-hub-section').css('height', min_height);
    $('body').find('.expert-profile-section').css('height', min_height);
}

function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 60)
    }, 1000);
}

$('body').on('click', '.read-more', function() {
    var parent_ele = $(this).parents('.read-more-section').eq(0);
    parent_ele.find('.short-description-text').addClass('hide');
    parent_ele.find('.full-description-text').removeClass('hide');
});

$('body').on('click', '.read-less', function() {
    var parent_ele = $(this).parents('.read-more-section').eq(0);
    parent_ele.find('.short-description-text').removeClass('hide');
    parent_ele.find('.full-description-text').addClass('hide');
});

$('body').on('click', '.read-more-skills', function(e) {
    e.preventDefault();
    var ele = $(this).parents('.skills-section-display').eq(0);
    ele.find('.skill-button').removeClass('hide');
    ele.find('.read-less-skills').removeClass('hide');
    $(this).addClass('hide');
});


$('body').on('click', '.read-less-skills', function(e) {
    e.preventDefault();
    var ele = $(this).parents('.skills-section-display').eq(0);
    ele.find('.extra-skills').addClass('hide');
    ele.find('.read-more-skills').removeClass('hide');
    $(this).addClass('hide');
});

$('body').on('click', '.hide-section', function() {
    var ele = $(this).parents('.section-container').eq(0);
    ele.find('.thumbnail-user-list').removeClass('hide')
    ele.find('.express-view-off-interest-block').addClass('hide')
    $(this).addClass('hide');
})
$('body').on('click', '.view-all-user', function() {
    var ele = $(this).parents('.section-container').eq(0);
    ele.find('.thumbnail-user-list').addClass('hide')
    ele.find('.express-view-off-interest-block').removeClass('hide')
    ele.find('.hide-section').removeClass('hide');
})

