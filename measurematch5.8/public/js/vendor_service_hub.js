/**
 * Created by ucreateit on 11/3/19.
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#apply_for_verified_expert').on('click', function() {
    $('#apply_to_service_hub').modal('show');
})
$('body').on('click', '#show_more', function(e) {
    $('body') .find('#truncated_description').addClass('hide');
    $('body') .find('#full_description').removeClass('hide');
});
$('body').on('click', '#show_less', function(e) {
    $('body') .find('#truncated_description').removeClass('hide');
    $('body') .find('#full_description').addClass('hide');
})
$(document).on('click', '#view_more_experts', function(){
    var page_number = parseInt($(this).attr('page-number'));
    var service_hub_id = $(this).attr('service-hub-id');
    $.ajax({
        url: base_url + '/service-hub/approved-experts/' + service_hub_id + '?page=' + page_number,
        success: function(response){
            if (response != 0) {
                $('#view_more_experts').attr('page-number', page_number + 1)
                $('.verified-experts-block').append(response.html);
                if (response.view_more == 0) {
                    $('#view_more_experts_block').addClass('hide');
                }
            }
        }
    });
});


$('#send_expert_verification').on('submit', function (event) {
    event.preventDefault();
    var hub_id = $('#hub_id').val();
    var total_experience = $('#total_experience').val();
    var case_study = $("#case_study").val();

    if (total_experience == '') {
        $('.validate_total_experience_error').html('Please enter your total experience.').fadeIn('fast').delay(2000).fadeOut();
        return false;
    } else if (case_study == '') {
        $('.validate_case_study_error').html('Please enter your recent case study.').fadeIn('fast').delay(2000).fadeOut();
        return false;
    }

    $('#send_expert_verification').attr('disabled', 'disabled');

    $.ajax({
        type: 'post',
        url: $(this).attr('action'),
        dataType: 'json',
        data: {service_hub_id: hub_id, total_experience: total_experience, recent_case_study: case_study},
        statusCode: {
            500: function (data) {
                location.reload();
            }
        },
        success: function (result) {
           if(result){
               location.reload();
               return false;
           }
           return false;
        }

    });

    return false;
});

$('#case_study').on('keyup', function() {
    validateForm();
})

function validateForm() {
    var case_study = $('#case_study').val();
    var experience = $('#total_experience').val();
    var valid = false;
    if (case_study != '' && parseInt(experience) > 0) {
        valid =  true;
    }

    if (valid) {
        $('body').find('#apply_to_service_hub_btn').removeClass('disable-btn').addClass('standard-btn');
    } else {
        $('body').find('#apply_to_service_hub_btn').addClass('disable-btn').removeClass('standard-btn');
    }
}

$('#total_experience').on('change', function() {
    validateForm()
})
