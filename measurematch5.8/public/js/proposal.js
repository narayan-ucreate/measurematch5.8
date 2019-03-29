$(document).ready(function(e) {
    var sub_total_value = parseInt($(document).find('#subtotal_value').val());
    if (sub_total_value < 1000) {
        $('.proposal-action-tabs a').attr('title', validationErrors().subtotal_error).tooltip();
        $('.proposal-action-tabs a').attr('href', 'javascript:void(0)')
    }
    if ($('#step').val() == 1) {
        var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        if (url) {
            var first_param = url[0];
            first_param = first_param.split('=');
            var value = first_param[1];
            var key = first_param[0];
            setTimeout(function () {
                if (key == 'deliverable') {
                    ele = $(".edit-deliverable[data-id='" + value + "']");
                    openEditMode(ele)

                } else if (key == 'terms') {
                    ele = $(".edit-term[data-id='" + value + "']");
                    editTerms(ele)

                } else {
                    var orginal_data = $('#' + value).val();
                    $('#' + value).focus().val("").val(orginal_data);
                }
            }, 200)
            setTimeout(function () {
                var uri = window.location.href.toString();
                if (uri.indexOf("?") > 0) {
                    var clean_uri = uri.substring(0, uri.indexOf("?"));
                    window.history.replaceState({}, document.title, clean_uri);
                }
            }, 300);
        }
    }
    if (birth_date != '') {
        setTimeout(function () {
            $('.dob-day').val(birth_date);
            $('.dob-month').val(birth_month);
            $('.dob-year').val(birth_year);
            $('.selectpicker').selectpicker('refresh');
        }, 100);
    }
})


$('body').on('change keyup', '.common-deliverable-form', function(e) {
    var title = $(document).find('#title').val();
    var description = $(document).find('#description').val();
    var rate_type = $(document).find('#rate_type option:selected').val();
    var price = $(document).find('#price').val();
    var quantity = $(document).find('#quantity option:selected').val();
    var button_ele = $('body').find('#submit_deliverable');
    if (title == '' || description == '' || rate_type == '' || price == '') {
        button_ele.addClass('disable-btn');
    } else {
        if ((rate_type == 2 || rate_type == 3) && quantity == '') {
            button_ele.addClass('disable-btn');
        } else {
            button_ele.removeClass('disable-btn');
        }
    }
});

$('body').on('submit', '#create_deliverable', function(e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method:'post',
        data : data,
        url : $(this).attr('action'),
        statusCode: {
            422 : function(data) {
                var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response.errors);
            }
        },
        success: function(response) {
            $('.deliverable-container').html(response);
            $('#submit_deliverable').text('Submit');
            if ($(document).find('#update_index').val() >=0) {
                $('.create-deliverable-section').addClass('hide');
                $('#all_deliverable_list').removeClass('hide')
                $('.add-another-deliverable').removeClass('hide')
            }
            $(document).find('#update_index').val('');
            $('#create_deliverable')[0].reset();
            $('#submit_deliverable').val('Save deliverable');
            $('body').find('.selectpicker').selectpicker('refresh');
            var sub_total_value = parseInt($(document).find('#subtotal_value').val());
            if (sub_total_value < 1000) {
                $('.proposal-action-tabs a').attr({
                    'title': validationErrors().subtotal_error,
                    'data-original-title': validationErrors().subtotal_error,
                    'href': 'javascript:void(0)'
                }).tooltip();
            } else {
                $('.proposal-step-2 a').attr('href', $('#step_2_url').val());
                $('.proposal-step-3 a').attr('href', $('#step_3_url').val());
                $('.proposal-step-4 a').attr('href', $('#step_4_url').val());
                $('.proposal-action-tabs a').removeAttr('title data-original-title');
            }
        }
    })
});
$('body').on('click', '#add_another_deliverable', function(e) {
    $(this).parents('.add-another-deliverable').addClass('hide');
    $('body').find('.create-deliverable-section').removeClass('hide')
    $('#create_deliverable')[0].reset();
    $('body').find('.selectpicker').selectpicker('refresh');
})
$('body').on('click', '.cancel-add-deliverable', function(e) {
    $('body').find('.create-deliverable-section').addClass('hide')
    $('body').find('.add-another-deliverable').removeClass('hide')
    $('#submit_deliverable').val('Save deliverable');
    $('#all_deliverable_list').removeClass('hide')
    $('.add-another-deliverable').removeClass('hide')
})

$('body').on('click', '.delete-deliverable', function() {
    var deliverables_count = $('#all_deliverable_list .deliverable-blocks').length;
    if (confirm('Are you sure you want to delete this deliverable?')) {
        $.ajax({
            method:'post',
            data : {'action' : 'delete_deliverable', index: $(this).attr('data-id')},
            url : $('#manage-deliverable-url').val(),
            success: function(response) {
                $('.deliverable-container').html(response);
                $('body').find('.selectpicker').selectpicker('refresh');
            }
        })
    }
})

$('body').on('click', '.delete-term', function() {
    if (confirm('Are you sure you want to delete this term?')) {
        $.ajax({
            method:'post',
            data : {'action' : 'delete_term', index: $(this).attr('data-id')},
            url : $('#manage-term-url').val(),
            success: function(response) {
                $('.terms-container').html(response);
            }
        })
    }
})

$(document).on('change', '#rate_type', function() {
   var selected_value = $(this).val();
   var ele = $('body').find('.quantity-section');
   var amount_ele = $('body').find('#price');
   if (selected_value == 1) {
       ele.addClass('hide');
       amount_ele.attr('placeholder', 'Enter your amount here...');
   } else if (selected_value == 2) {
       amount_ele.attr('placeholder', '/day');
       ele.find('select option:eq(0)').text('Choose no. of days');
       ele.removeClass('hide');
   } else if (selected_value == 3) {
       ele.removeClass('hide');
       amount_ele.attr('placeholder', '/hour');
       ele.find('select option:eq(0)').text('Choose no. of hours');
   } else {
       ele.addClass('hide');
       amount_ele.attr('placeholder', 'Enter your amount here...');
   }
    ele.find('select').val('')
    $('body').find('.selectpicker').selectpicker('refresh');
});

//quantity-section

$('body').on('submit', '#create_term', function(e) {
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
        success: function(response) {
            $('.terms-container').html(response);
        }
    });
});

$(document).on('click', '.edit-deliverable', function() {
    if ($('#step').val() == 1) {
        openEditMode(this)
    } else {
        window.location = $(this).attr('data-redirect-url');
    }
})



function openEditMode(ele) {
    $('.create-deliverable-section').removeClass('hide');
    $('#all_deliverable_list').addClass('hide')
    $("input[name='title']").val($(ele).attr('data-title'));
    $("select[name='quantity']").val($(ele).attr('data-quantity'));
    $("input[name='price']").val($(ele).attr('data-price'));
    $("textarea[name='description']").val($(ele).attr('data-description'));
    $("select[name='rate_type']").val($(ele).attr('data-rate_type'));
    $('#submit_deliverable').val('Update deliverable');
    $(document).find('#update_index').val($(ele).attr('data-id'))

    $('.add-another-deliverable').addClass('hide')
    $('body').find('#rate_type').trigger('change');
    $("select[name='quantity']").val($(ele).attr('data-quantity'));
    $('body').find('.selectpicker').selectpicker('refresh');
    $("html, body").animate({ scrollTop: 500}, 1000);
    $("input[name='title']").focus()
    $(document).find('.price-format-validation').trigger('keyup')
}

$(document).on('click', '.edit-term', function() {
    var this_element = $(this);
    editTerms(this_element);
})

function editTerms(this_element) {
    $('.expert-terms').removeClass('hide');
    $('.expert-term-button').removeClass('hide');
    $('.created-terms').addClass('hide');
    $('#add_another_term').addClass('hide');
    $('#save_term').val('Update term');
    $('#save_term').removeClass('disable-btn').addClass('standard-btn');
    $(document).find('#term_index').val($(this_element).attr('data-id'))
    $('#term').val($(this_element).attr('data-description'))
    $("html, body").animate({ scrollTop: $(document).height()}, 1000);
    $('#term').focus()
}

$(document).on('focusout', '.proposal-should-save', function() {
    var value = $(this).val();
    var name= $(this).attr('name');
    autoSave(value, name);
})


function autoSave(value, name, callback = '') {
    data_to_update = {'action' : 'auto-save', value: value, 'name': name};
    data_to_update[name] = value;
    $.ajax({
        method:'post',
        data : data_to_update,
        url : $('#manage-deliverable-url').val(),
        async : false,
        statusCode: {
            422: function (data) {
                var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response.errors);
            }
        },
        success: function(response) {
            if(typeof callback === 'function') callback.apply(this, [response]);
        }
    })
}

function updateStep1FieldsInDatabase(callback) {
    data_to_update = {
        'step-1-status': true,
        'action': 'update-step-1',
        'introduction': $('#introduction').val(),
        'summary': $('#summary').val(),
        'job_start_date': $('#job_start_date').val(),
        'job_end_date': $('#job_end_date').val(),
        'code_of_conduct': true,
        'stay_safe_confirm': true
    };
    $.ajax({
        method:'post',
        data : data_to_update,
        url : $('#manage-deliverable-url').val(),
        async : false,
        statusCode: {
            422: function (data) {
                var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response.errors);
            }
        },
        success: function(response) {
            if(typeof callback === 'function') callback.apply(this, [response]);
        }
    })
}

function manupulateMessage(errors) {
    $('.validation_error').remove();
    for(var key in errors) {
          $('#'+key).after('<span class="validation_error">'+errors[key][0]+'</span>');
    }
}

$(document).ready(function(){
    var popOverSettings = {
        container: 'body',
        selector: '[data-toggle="popover"]',
        trigger: "hover"
    }

    $(document).popover(popOverSettings);
    $(window).scroll(function() {
        if ($(this).scrollTop() > 12) {
            $('.send-proposal-header').addClass('make-header-fix');
        } else {
            $('.send-proposal-header').removeClass('make-header-fix');
        }

    });

});
    $('.business-type-block').click(function () {
    $('.business-type-block').removeClass('tab-active');
    $(this).addClass('tab-active');
    $('#registered_company_section, #sole_trader_section').addClass('hide');
});

function updateOnfocusOut(field){
    var field_name = field;
    $('#' + field).focusout(function(){
        if($(this).attr('data-updated') == 1){
            autoSave($(this).val(), field_name);
            $(this).attr('data-updated', 0);
            return false;
        }
    });
}

var input = $("#phone_num");
if (input) {
    input.intlTelInput({
        preferredCountries: ['gb', 'us'],
        separateDialCode: true,
        formatOnDisplay: false,
        utilsScript: base_url+'/js/international-phone-codes-utils.js'
    });
}
$('#date_of_birth').datetimepicker({
      format: 'DD-MM-YYYY',
      maxDate: new Date(),
      useCurrent: false
  });
$('body').on('submit', '#update_basic_information', function(e) {
    e.preventDefault();
    $('#date_of_birth').val(getFullDate());
    $('#country_code').val($('.selected-dial-code:visible').text());
    var data = $(this).serialize();
    $.ajax({
        method:'post',
        data : data,
        url : $(this).attr('action'),
        statusCode: {
            422 : function(data) {
               var response = jQuery.parseJSON(data.responseText);
                manupulateMessage(response);
                autoSave(false, 'step-2-status');
            }
        },
        success: function (response) {
            if (response) {
                var url = $('#edit_proposal_url').val();
                var res = url.substring(0, url.length -1);
                autoSave(true, 'step-2-status', function(returned_response){
                    if(returned_response ==  1){
                        window.location =res+3;
                    }
                });
            }
        }
    })
});
var date = new Date();
var day = date.getDate();
var month_index = date.getMonth();
var year = date.getFullYear();
var minimum_date = moment(new Date((month_index + 1) + "/" + (day) + "/" + (year)));
$('#job_start_date').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: minimum_date
});
$('#job_end_date').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: minimum_date
});
$("#job_start_date").on("dp.change", function (e) {
    var start_date = $(this).val();
    autoSave(start_date, 'job_start_date');
    setTimeout(function () {
        $('#job_end_date').data("DateTimePicker").minDate(e.date);
    }, 10);
});
$("#job_end_date").on("dp.change", function (e) {
    var end_date = $(this).val();
    setTimeout(function () {
        autoSave(end_date, 'job_end_date');
    }, 0);
    
});
function projectDetailBack(project_id, buyer_id){
    $('.project-details-popup-button').attr("disabled", true);
    $.ajax({
        url: base_url + '/buyer/overlay/project/'+project_id,
        data : {'buyer_id' : buyer_id, post_job_id: project_id},
        type: 'GET',
        success: function (response) {
            if (response.success == 1) {
                $("#expert_proposal_screen_pop_ups").html(response.content);
                $("#project_detail_popup").modal('show');
            }
        },
        error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
        $('.project-details-popup-button').attr("disabled", false);
        }
    });
}
$('body').on("click","#view_project_details",function(){
    var this_element = $(this);
    if (this_element.attr('data-project-type') == 'project') {
        projectDetailBack(this_element.attr('data-project-id'), this_element.attr('data-buyer-id'));
    } else {
        window.open(base_url + '/servicepackage/detail/' + this_element.attr('data-project-id'), '_blank');
    }
});


$('body').on('click', '#show_more', function(){
    $('body').find('#truncated_description').addClass('hide');
    $('body').find('#full_description').removeClass('hide');
});
$('body').on('click', '#show_less', function(){
    $('body').find('#truncated_description').removeClass('hide');
    $('body').find('#full_description').addClass('hide');
});



$('body').on('click', '#show_more_deliverable', function(e) {
    $('body') .find('#deliverable_details_less').addClass('hide');
    $('body') .find('#deliverable_details').removeClass('hide');

});
$('body').on('click', '#show_less_deliverable', function(e) {
    $('body') .find('#deliverable_details_less').removeClass('hide');
    $('body') .find('#deliverable_details').addClass('hide');

});

$('body').on("click","#save_step_1",function(){
    var error_count = 0;
    $('.validation_error').remove();
    var introduction = $('#introduction').val().trim();
    var summary = $('#summary').val().trim();
    var job_start_date = $('#job_start_date').val();
    var job_end_date = $('#job_end_date').val();
    var subtotal_value = parseInt($('#subtotal_value').val());

    if (introduction == '') {
        $('#introduction').after('<span class="validation_error">'+validationErrors().introduction+'</span>');
        error_count++;
    }
    if (summary == '') {
        $('#summary').after('<span class="validation_error">'+validationErrors().summary+'</span>')
        error_count++;
    }
    if (job_start_date == '') {
        $('#job_start_date').after('<span class="validation_error">'+validationErrors().job_start_date+'</span>')
        error_count++;
    }
    if (job_end_date == '') {
        $('#job_end_date').after('<span class="validation_error">'+validationErrors().job_end_date+'</span>')
        error_count++;
    }
    if ($('#all_deliverable_list').children().length == 0) {
        $('#description').after('<span class="validation_error">'+validationErrors().description+'</span>')
        error_count++;
    }
    if ($('#stay_safe_confirm').prop('checked') == false) {
        $('.stay-safe-label').after('<span class="validation_error">'+validationErrors().stay_safe_error+'</span>')
        error_count++;
    }
    if ($('#code_of_conduct').prop('checked') == false) {
        $('.code-of-conduct-label').after('<span class="validation_error">'+validationErrors().code_of_conduct_error+'</span>')
        error_count++;
    }
    if (subtotal_value < 1000){
        console.log(subtotal_value);
        $('.sub-total-block .sub-total-h5').after('<span class="validation_error">'+validationErrors().subtotal_error+'</span>');
        error_count++;
    }

    if (error_count>0) {
        autoSave(false, 'step-1-status');
        $('html, body').animate({
            scrollTop: $('.validation_error:visible:first').offset().top-300
        }, 1000);
        $('.validation_error').fadeIn('fast').delay(3000).fadeOut('fast');
    } else {
        var redirect_url = $(this).attr('data-redirect-url');
        updateStep1FieldsInDatabase(function(returned_response){
            if(returned_response ==  1){
                window.location = redirect_url;
            }
        });
    }
});
$('body').on('click', '#stay_safe_confirm', function(){
    autoSave($(this).prop('checked'), this.id);
});
$('body').on('click', '#code_of_conduct', function(){
    autoSave($(this).prop('checked'), this.id);
});
function validationErrors(){
    var currency = $('#subtotal_currency').val();
    return {
        'introduction':'Oops! Please fill in this section before continuing',
        'summary':'Oops! Please fill in this section before continuing',
        'description':'You need to add and save at least 1 deliverable before you can continue',
        'description_required':'You need to keep least 1 deliverable before you can continue',
        'stay_safe_error':'Oops, you need to read and consent to our Terms of Service before you can continue',
        'code_of_conduct_error':'Oops, you need to read and consent to our Code of Conduct before you can continue',
        'job_start_date':'Oh dear! it looks like you forgot to add a start date. Please add a date to continue',
        'job_end_date':'Oh dear! it looks like you forgot to add the finish date. Please add a date to continue.',
        'subtotal_error':'The minimum contract size on MeasureMatch is ' + currency + '1000. Please update your deliverables accordingly.',
    };
}
