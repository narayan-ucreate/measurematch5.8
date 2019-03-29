var THOUSAND = 1000;

var welcome_service_package_status = $('#welcome_service_package_status').val();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});
$('body').find('.view-packages').on('click', function(e) {
    var service_package_id = $(this).attr('data-service-package-id');
    redirectToDetailPage(service_package_id);
});

function redirectToDetailPage(service_package_id){
    $.ajax({
        type: 'get',
        url: base_url + '/addtosession',
        async: false,
        data: {service_package_id: service_package_id},
        success: function (response) {
            window.location.href = base_url + '/servicepackage/' + service_package_id;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}

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
$(document).ready(function () {
    if($('.buyer_ratings').length){
        $(".buyer_ratings").each(function(e){
            getRating(this.id, $(this).attr('rating'));
        });
    }
    function getRating(rating_id, rate){
        $("#" + rating_id).rateYo({
            rating: rate,
            numStars: 5,
            precision: 2,
            readOnly: true,
            ratedFill: "#1e70b7",
            starWidth: "15px"
        });
    }
    if(window.location.href.indexOf('deleted=true') !== -1){
        $('#success_msg').text('Service package has been deleted!');
        $('.service-package-error-message').show();
        window.history.pushState(null, null, base_url + '/servicepackages');
    }

    if(window.location.href.indexOf('hidden=true') !== -1){
        $('#success_msg').text('Service package has been hidden!');
        $('.service-package-error-message').show();
        window.history.pushState(null, null, base_url + '/servicepackages');
    }

    if(window.location.href.indexOf('visible=true') !== -1){
        $('#success_msg').text('Service package has been unhidden!');
        $('.service-package-error-message').show();
        window.history.pushState(null, null, base_url + '/servicepackages');
    }

    if ($("#service_package_listing_show_popup").val() == "") {
        $('#servicePackageWelcomeModal').modal('show');
    }
    $("#got_it_service_package").on('click', function () {
        var response = udpateUserSettings({service_package_listing_show_popup: true});
        if (response.success == 1) {
            $('#servicePackageWelcomeModal').modal('hide');
        }
    });
    if ($("#package_created").val() && $("#publish_status").val() !='False') {
        if ($("#package_created").val().trim() === '1') {
            $("#service-package-reivew-thankyou").modal('show');
            $("#package_created_got_it").click(function () {
                $('.close').click();
            });
        }
    }

    $('#hide_package_button').on('click', function () {
        $('#package_id').val($(this).attr('data-id'));
        $('#package_status').val($(this).attr('data-status'));
        $('#hide_package_from_buyer').modal('show');
    });
    $('.hide_package').on('click', function () {
        $('#package_id').val($(this).attr('package_id'));
        $('#hide_package_from_buyer').modal('show');
    });
    $('#hide_service_package').on('click', function () {
        var service_package_id = $('#package_id').val();
        var response = hidePackage(service_package_id);
        if (response) {
            window.location.href= base_url+'/servicepackages?hidden=true';
        }
    });

    $('.unhide_package').on('click', function () {
        $('#package_unhide_id').val($(this).attr('package_id'));
        $('#unhide_package_from_buyer').modal('show');
    });
    $('#service_package_unhide').on('click', function () {
        var service_package_id = $('#package_unhide_id').val();
        var response = unhidePackage(service_package_id);
        if(response){
            window.location.href= base_url+'/servicepackages?visible=true';
        }
    });

    $('#package-hide-button').on('click', function () {
        $('#hide_package_button').addClass('hide-package-button-from-buyer');
        var service_package_id = $('#package_id').val();
        var service_package_status = $('#package_status').val();
        var response = hidePackage(service_package_id);
        if (response) {
            $('#hide_package_from_buyer').modal('hide');
            $('.service-package-error-message').html('<div class="col-md-12"><div class="alert alert-info fade in alert-dismissable"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Your Package has been hidden from MeasureMatch Clients.</div></div>').show();
            $('.package-status').html("<span class='hidden-package live-package'>Hidden from Clients<a id='unhide_package_button' data-status='" + service_package_status + "' data-id='" + service_package_id + "' href='javascript:void(0)'>(Make visible)</a></span>");
        }
    });

    $(document).on('click', '.expert-mark-as-complete', function (e) {
        var comm_id = $(this).attr('id');
        var receiver_id = $(this).attr('user_id');
        var sender_id = $(this).attr('sender_id');
        var communications_id = $(this).attr('communications_id');
        $('.expert-mark-as-complete').css('pointer-events', 'none');
        $.ajax({
            type: 'post',
            url: base_url + '/expertmarkcontractcomplete',
            data: {contract_id: comm_id, receiver_id: receiver_id, communications_id: communications_id},
            success: function (result) {
                if (result.success == 1) {
                    $('.contract-status-update-' + comm_id).text('Completed');
                    $('#check-expert-project-compelted-' + sender_id).attr('checked', 'checked');
                    $('#expert-project-completed-' + sender_id).hide();
                    $('.close').trigger('click');
                    $("div.modal-backdrop").remove();
                    $('.expert-mark-as-complete').css('pointer-events', 'auto');
                }

            }
        });
    });
});

function hidePackage(service_package_id){
    var result = 0;
    $.ajax({
        type: 'post',
        url: base_url + '/servicepackage/hidepackage',
        async: false,
        data: {service_package_id: service_package_id},
        success: function (response) {
            if (response) {
                result = response;
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return result;
}

function unhidePackage(service_package_id){
    var result = 0;
    $.ajax({
        type: 'post',
        url: base_url + '/servicepackage/unhidepackage',
        async: false,
        data: {service_package_id: service_package_id},
        success: function (response) {
            if (response) {
                result = response;
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return result;
}

$(document).on('click', '.archieve-expressions-of-interest', function () {
    var service_package_communication_id = $(this).attr('id');
    var buyer_name = $(this).attr('data-buyer-name');
    var created_at = $(this).attr('data-created-at');
    var data_type = $(this).attr('data-type');

    $.ajax({
        type: 'post',
        url: base_url + '/servicepackage/eoiarchieve',
        async: false,
        data: {service_package_communication_id: service_package_communication_id},
        success: function (response) {
            if (response) {
                if (data_type == 'new-eio-count') {
                    var new_eio_count = $('#new-eio-count').val();
                    var archieved_eio_count = $('#archieved-eio-count').val();
                    $('#new-eio-count').val(--new_eio_count);
                    $('#new-count').text(new_eio_count);
                    $('#eoi-count').text(new_eio_count);

                    if (archieved_eio_count == 0) {
                        $('.archieved-eoi-panel .no-services-package-message').remove();
                    }
                    $('#archieve-count').text(++archieved_eio_count);
                    var archieved_eio_count = $('#archieved-eio-count').val(archieved_eio_count);
                } else if (data_type == 'actioned-eio-count') {
                    var actioned_eio_count = $('#actioned-eio-count').val();
                    var archieved_eio_count = $('#archieved-eio-count').val();
                    $('#actioned-eio-count').val(--actioned_eio_count);
                    $('#actioned-count').text(actioned_eio_count);

                    if (archieved_eio_count == 0) {
                        $('.archieved-eoi-panel .no-services-package-message').remove();
                    }
                    $('#archieve-count').text(++archieved_eio_count);
                    var archieved_eio_count = $('#archieved-eio-count').val(archieved_eio_count);
                }
                $('.eoi-' + service_package_communication_id).hide();
                $('.archieved-eoi-panel').append('<div class="eoi-listing eoi-' + service_package_communication_id + '"><div class="col-md-8 col-xs-12"><h3>' + buyer_name + '</h3><span>Expressed Interest on ' + created_at + '</span></div><div class="keyboard-control archive-dropdown-opt dropup pull-right"><a class="white-bg-btn white-bg" href="'+base_url+'/expert/messages?communication_id='+service_package_communication_id+'">View Messages</a><button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="' + base_url + '/images/3-dots.svg"></button><ul class="dropdown-menu" aria-labelledby="drop_down_menu"><li><a id="' + service_package_communication_id + '" data-buyer-name="' + buyer_name + '" data-created-at="' + created_at + '" href="javascript:void(0)" class="unarchieve-expressions-of-interest"><strong>Unarchive this EOI</strong><span>Unarchive EOI to use now</span></a></li></ul></div></div>');
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});
$(document).on('click', '.unarchieve-expressions-of-interest', function () {
    var service_package_communication_id = $(this).attr('id');
    var buyer_name = $(this).attr('data-buyer-name');
    var created_at = $(this).attr('data-created-at');
    $.ajax({
        type: 'post',
        url: base_url + '/servicepackage/eoiunarchieve',
        async: false,
        data: {service_package_communication_id: service_package_communication_id},
        success: function (response) {
            if (response) {
                var archieved_eio_count = $('#archieved-eio-count').val();
                var actioned_eio_count = $('#actioned-eio-count').val();
                if (actioned_eio_count == 0) {
                    $('.actioned-eoi-panel .no-services-package-message').remove();
                }
                $('#actioned-eio-count').val(++actioned_eio_count);
                $('.eoi-' + service_package_communication_id).hide();
                $('#actioned-count').text(actioned_eio_count);
                $('#archieved-eio-count').val(--archieved_eio_count);
                $('#archieve-count').text(archieved_eio_count);

                $('.actioned-eoi-panel').append('<div class="eoi-listing eoi-' + service_package_communication_id + '"><div class="col-md-8 col-xs-12"><h3>' + buyer_name + '</h3><span>Expressed Interest on ' + created_at + '</span></div><div class="keyboard-control archive-dropdown-opt dropup pull-right"><a class="white-bg-btn white-bg" href="'+base_url+'/expert/messages?communication_id='+service_package_communication_id+'">View Messages</a><button class="btn btn-default dropdown-toggle" type="button" id="drop_down_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="' + base_url + '/images/3-dots.svg"></button><ul class="dropdown-menu" aria-labelledby="drop_down_menu"><li><a id="' + service_package_communication_id + '" data-buyer-name="' + buyer_name + '" data-created-at="' + created_at + '" data-type="actioned-eio-count" href="javascript:void(0)" class="archieve-expressions-of-interest"><strong>Archive this EOI</strong><span>Save this EOI for later on by archiving it</span></a></li></ul></div></div>');


            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });

});
$(document).on('click', '#unhide_package_button', function () {
    $('#package_unhide_id').val($(this).attr('data-id'));
    $('#package_unhide_status').val($(this).attr('data-status'));
    $('#unhide_package_from_buyer').modal('show');
});
$(document).on('click', '#package-unhide-button', function () {
    var service_package_id = $('#package_unhide_id').val();
    $('#hide_package_button').removeClass('hide-package-button-from-buyer');
    var approval_status = $('#package_approval_status').val();
    var response = unhidePackage(service_package_id);
    if (response) {
        $('#unhide_package_from_buyer').modal('hide');
        if (approval_status == '') {
            $('.service-package-error-message').html('<div class="col-md-12"><div class="alert alert-info fade in alert-dismissable"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Your Service Package is still under review. Hold tight.</div></div>').show();
            $('.package-status').html('<span class="await-approval-package">Awaiting approval</span>');
        } else {
            $('.service-package-error-message').html('<div class="col-md-12"><div class="alert alert-info fade in alert-dismissable"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Your Package is now live & visible to Buyers.</div></div>').show();
            $('.package-status').html('<span class="live-package">Live on MeasureMatch</span>');
        }

    }
});
$(document).on('click', '.accept-contract-btn', function (e) {

    var contract_id = $(this).attr('id');
    var communication_id = $(this).attr('data-commid');
    var sender_id = $(this).attr('data-receiver');
    var receiver_id = $(this).attr('data-sender');
    var data_contract_id = $(this).attr('data-contract');
    var end_date = $(this).attr('data-contract-enddate');
    var rate = $(this).attr('data-contract-rate');
    var contract_job_title = $('#Contract_Job_title').text();
    var discount_applied = $('#discount_applied').val();
    var contract_confirm = $(this).attr('contract_confirm');
    var project_id = $(this).attr('project_id');
    var payment_mode = $(this).attr('payment_mode');

    if (data_contract_id == 0) {
        $("div#errorMsg").show();
        return false;
    }
    if (discount_applied == '' && contract_confirm == '0') {
        $('.mark_completed_project').modal('hide');
        $('#discount_confirm_box').modal('show');
        return false;
    }
    $('.accept-contract-btn').css('pointer-events', 'none');
    $.ajax({
        type: 'post',
        url: base_url + '/acceptservicepackagebyexpert',
        data: {contract_id: contract_id, payment_mode: payment_mode, comm_id: communication_id, endDate: end_date, rate: rate, expert_id: sender_id, buyer_id: receiver_id, project_id: project_id, contract_job_title: contract_job_title},
        success: function (result) {
            if (result.success == 1) {
                $('.contract-status-update-' + contract_id).text('On-going');
                $('#expert-action-accepted-offer-' + sender_id).attr('checked', 'checked');
                $('.expert-view-offer-option').hide();
                $('#expert-contract-preview').hide();
                $('div.modal-backdrop').hide();
                $('#expert-project-completed-' + sender_id).show();
            }
            $('.close').trigger('click');
            $("div.modal-backdrop").remove();
        }
    });
});
$(document).on('click', '.delete-service-package', function () {
    var service_package_id = $(this).attr('id');

    if (confirm("Do you want to delete this Service Package?") == true) {
        $.ajax({
            type: 'post',
            url: base_url + '/servicepackages/deleteservicepackage',
            data: {service_package_id: service_package_id},
            success: function (result) {
                if (result == 1) {
                   window.location.href= base_url+'/servicepackages?deleted=true';
                }
            }
        })
    } else {
        return false;
    }

});
$(document).ready(function() {
if($(".auto-scroll").outerHeight() > 300){
      $(".auto-scroll").addClass('my-service-packages-scroll');
    }
});
