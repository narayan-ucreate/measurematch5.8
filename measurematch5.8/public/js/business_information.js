var business_type_in_database = $('#business_type_in_database').val();
function scroll_to_error(selector) {
    var subtract_value = 0
    if ($('body').find('.send-proposal-header').hasClass('make-header-fix')) {
        subtract_value = 200;
    }
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - (100+ subtract_value))
    }, 400);
}
function manupulateMessage(errors) {
   $('.validation_error').remove();
    for(var key in errors) {
      $('#'+key).after('<span class="validation_error">'+errors[key][0]+'</span>');
    }
    $('.validation_error').delay(800).fadeOut(6000);
}
$('.business-type-block').click(function () {
    $('.business-type-block').removeClass('tab-active');
    $(this).addClass('tab-active');
});

settings_business_type = 101;
settings_business_details = 102;
settings_vat_details = 103;
settings_business_address = 104;

$('body').on('submit', '#business_information', function(e) {
    e.preventDefault();
    if ($('#business_type').val() == 2) {
        submitBusinessAddress();
        return false;
    }
    var data = $(this).serialize();
    ajaxBusinessInformation(data);
});
function submitBusinessAddress() {
    var user_data = {
        'business_type': $('#business_type').val(),
        'first_address': $('#first_address').val(),
        'second_address': $('#second_address').val(),
        'business_city': $('#business_city').val(),
        'business_state': $('#business_state').val(),
        'business_postal_code': $('#business_postal_code').val(),
        'business_country': $('#business_country').val(),
        'business_registered_country': $('#business_registered_country').val(),
    };
    ajaxBusinessInformation(user_data);
}

function ajaxBusinessInformation(user_data) {
    $.ajax({
        type: 'post',
        url: base_url + '/updatesellerbuisness',
        data: user_data,
        statusCode: {
            422 : function(data) {
               var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response);
                scroll_to_error($('.validation_error:first'));

            }
        },
        success: function (response) {
            if (response.successful == true) {
                business_type_in_database = response.business_type;
                var send_proposal_page = $('#edit_proposal_url');
                if (response.business_type == 2) {
                    refreshBusinessDetailsForm();
                    $('#company_role').removeClass('value-already-filled');
                    $('#company_country').removeClass('value-already-filled');
                    $('#vat_detail').addClass('hide');
                } else {
                    $('#company_role').addClass('value-already-filled');
                    $('#company_country').addClass('value-already-filled');
                }
                if (send_proposal_page.length != 0) {
                    autoSave(true, 'step-3-status');
                    var url = send_proposal_page.val();
                    var res = url.substring(0, url.length - 1);
                    window.location = res + 4
                } else {
                    $("#success_msg_expert_edit").html("<div class='bg-success'>" + response.msg + "</div>").fadeIn('fast').delay(2000).fadeOut('fast');
                    scroll_to_error('#success_msg_expert_edit');
                    return false;
                }
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        },
    });
}

function refreshBusinessDetailsForm(){
    $('#company_name').val('');
    $('#company_website').val('');
    $('#company_country').val('');
    $('#company_role .selectpicker').selectpicker('refresh');
}
function refreshSelectPicker(){
    $(".selectpicker").each(function(index, value) {
        if(!$(this).hasClass('value-already-filled')){
            $(this).val('');
            $(this).selectpicker('refresh');
        }
    });
}
$(document).ready(function () {
    if ($('#business_type').val() == 2) {
        $('.sole-trader').click(); 
        $('#sole_trader_section ').removeClass('hide');
    } else {
        $('.sole-trader-block').addClass('hide');
    }
    
    $('.business-type-block').click(function () { 
        $('.business-type-block').removeClass('tab-active');
        $(this).addClass('tab-active');
        $('#registered_company_section, #sole_trader_section').addClass('hide');
        refreshSelectPicker();
        $('#vat_block').addClass('hide');
        $('#submit_business_information').removeClass('standard-btn').addClass('disable-btn');
    });

    $('.sole-trader').click(function () {
        $('.sole-trader-block').removeClass('hide');
        $('#sole_trader_section').removeClass('hide');
        $('#business_details, #vat_detail ').addClass('hide');
        $('#business_type').val(2);
    });
    $('.expert-settings').click(function () {
        refreshSelectPicker();
    });

    $(document).on('click','.registered-company', function () {
       $('.sole-trader-block').addClass('hide');
       if(business_type_in_database == 1) {
           $('#vat_detail').removeClass('hide');
           $('#vat_block').removeClass('hide');
       }
       $('#registered_company_section, #business_details').removeClass('hide');
       $('#business_type').val(1);
    });
    
    $('.adding-text').on('input propertychange paste', function() {
        var field = this.id;
        $(this).attr('data-updated', 1);
        updateOnfocusOut(field);
    });
    
    $(document).on('input propertychange paste', '.expert-terms', function() {
        if ($(this).val().trim() != '') {
            $('.expert-term-button').removeClass('disable-btn').addClass('standard-btn');
        } else {
            $('.expert-term-button').removeClass('standard-btn').addClass('disable-btn');
        }
        
    });
    
    $(document).on('click', '#add_another_term', function(){
        $('.expert-terms').removeClass('hide');
        $('.expert-term-button').removeClass('hide');
        $(this).addClass('hide');
    });
    
    $('#vat_status').on('click', function () {
        if ($(this).prop("checked") == true) {
            $(".countryblock").removeClass('hide');
        } else {
            $(".countryblock").addClass('hide');
        }
    });
    
    $(document).on('click', '#submit_and_start_conversation', function (e) {
        if ($('#vat_country_confirmation_pop_up').val() == 0) {
            e.preventDefault();
            showVatDetailsPopupToBuyer($(this).attr('buyer_id'), $(this).attr('expert_name'));
        }
    });
    
    $('body').on('submit', '#submit_vat_details', function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: 'post',
            data: data,
            url: $(this).attr('action'),
            statusCode: {
                422: function (data) {
                    var response = jQuery.parseJSON(data.responseText);
                    manupulateMessage(response.errors);
                }
            },
            success: function (response) {
                if (response) {
                    $(document).find("#send_initial_message, #invite_expert_to_discuss_project").submit();
                }
            }
        })
    });
    
    $('body').on('submit', '#save_business_address', function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: 'post',
            data: data,
            url: $(this).attr('action'),
            async: false,
            statusCode: {
                422: function (data) {
                    var response = jQuery.parseJSON(data.responseText);
                    manupulateMessage(response);
                }
            },
            success: function (response) {
                if (response) {
                    $('#business_address_popup .close').trigger('click');
                    setTimeout(
                        function () {
                            $("#make_offer_stage_popups").html("");
                            $('.view-edit-offer-details-' + response.user_id).trigger('click');
                        }, 300);
                }
            }
        })
    });
    
    $(document).on('mousemove', '#make_offer_stage_popups #business_address_popup', function(){
        enableSubmitBusinessAddressButton();
    });
    
    $(document).on('keydown', "#make_offer_stage_popups #business_address_popup .input-bx", function(e){
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            enableSubmitBusinessAddressButton();
        } 
    });
    
    $('#no_vat_registered').on('click', function(){
        $('#vat_number').val('');
    });

    if (undefined !== $('#country_search_source').val() && $('#country_search_source').val().length > 0) {
       input_data = $.map(
            JSON.parse($('#country_search_source').val()),
            function (value, key) {
                return {
                    'label': value.country_name,
                    'value': value.country_name,
                    'vat_registered_status': value.vat,
                    'country_code': value.country_code,
                    'is_eu': value.eu,
                };
        });
        
        $('#company_country, #business_registered_country, #sole_trader_country, #business_country').autocomplete({
            source: input_data,
            minLength: 0
        }).bind('focus', function(){
            $(this).autocomplete("search");
        });

        $('#company_country, #business_registered_country, #sole_trader_country, #business_country').autocomplete({
            select: function( event, ui ) {
                if (event.target.id == 'company_country' 
                        || event.target.id == 'sole_trader_country') {
                    var country_vat_status = ui.item.vat_registered_status;
                    var country_code = ui.item.country_code;
                    if(country_vat_status == '1') {
                        $('.vat-country-code').text(country_code);
                        $('#vat_country').val(country_code);
                        $('#is_eu').val(ui.item.is_eu);
                        $('#vat_detail').removeClass('hide');
                        $('#vat_registered').attr('checked', 'checked');
                        $(document).find('#vat_block').removeClass('hide');
                    } else {
                        $('#vat_detail').addClass('hide');
                        $(document).find('#vat_block').addClass('hide');
                    }
                    $(document).find('#submit_business_information').addClass('standard-btn').removeClass('disable-btn');
                }
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    setTimeout(function () {
                        $(this).val("");
                        $(this).focus();
                        $('#vat_block').addClass('hide');
                        $(document).find('#submit_business_information').addClass('disable-btn').removeClass('standard-btn');
                    }, 300);
                }
            }
        });
    }
});

function showVatDetailsPopupToBuyer(buyer_id, expert_name) {
    $.ajax({
        url: base_url + '/get-vat-details-popup/' + buyer_id,
        type: 'GET',
        async: false,
        data: {expert_name: expert_name},
        success: function (response) {
            if (response.success) {
                if ($('#start_conversation_popup').val() == 1) {
                    $("#start_conversation_pop_up").modal("hide");
                }
                $("#inviteseller").modal("hide");
                $("#make_offer_stage_popups").html(response.content);
                $("#vat_details_popup").modal("show");
            }
        }
    });
}

function enableSubmitBusinessAddressButton() {
    if($(document).find('#make_offer_stage_popups #business_address_popup').length == 0) {
        return false;
    }
    var first_address = $('#first_address').val().trim();
    var business_city = $('#business_city').val().trim();
    var business_state = $('#business_state').val().trim();
    var business_postal_code = $('#business_postal_code').val().trim();
    var business_country = $('#buyer_business_country').val().trim();
    
    if (first_address != '' &&
        business_city != '' &&
        business_state != '' &&
        business_postal_code != '' &&
        business_country != '' &&
        $('#stay_safe_confirm').prop("checked") == true &&
        $('#code_of_conduct').prop("checked") == true) {
        $('#submit_business_info').removeClass('disable-btn').addClass('standard-btn');
    } else {
        $('#submit_business_info').removeClass('standard-btn').addClass('disable-btn');
    }
}