function tysize() {
    var windowHeight = $(window).outerHeight();
    var navHeight = $('nav').outerHeight();
    var footerHeight = $('footer').outerHeight();
    var mainheight = windowHeight - navHeight - footerHeight;
    if ($('.content').outerHeight() > 0)
    {
        $('.content').css('min-height', mainheight);
    }

}

$(window).resize(function () {
    tysize();
});

function scroll_to_error(selector) {
    $('html, body').animate({
        scrollTop: ($(selector).offset().top - 100)
    }, 400);
}
$('.selectpicker').selectpicker();
$(function () {
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
    $('#date_of_birth').datetimepicker({
        format: 'DD-MM-YYYY',
        maxDate: new Date(),
        useCurrent: false
    });


    tysize();
    var formmodified = 0;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*open billing account details tab */
    if (window.location.href.indexOf("actDetail") > -1) {
        $('#actDetail').click();
        var new_base_url = base_url + '/expert/settings';
        history.pushState({}, null, new_base_url);
    }

    $('#success_msg_expert_edit').fadeIn('fast').delay(4000).fadeOut('fast');
    $('#delete_account').click(function () {
        $('#confirm_cancellation').modal('show');
    });

    $(document).on('click', '#yesediting', function () {
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

    $(".removeAccount").on('click', function () {
        $("div.accountInfo").hide();
        $("div.accountForm").show();

        $(".account_info_hide").show();
    });
    
    $('#editsellerbuisness *').on('change', function () {
        formmodified = 1;
    });
    window.onbeforeunload = confirmExit();
    function confirmExit() {
        if (formmodified == 1) {
            return "New information not saved. Do you wish to leave the page?";
        }
    }


    $("#submit_buisness").on('click', function () {
        var first_address = $('#first_address').val().trim();
        var city = $('#location').val().trim();
        var post_code = $('#post_code').val().trim();
        var country_val = $('#countryId').val();
        var error_count = 0;
        $('.has_error').show();
        if (first_address == "") {
            $("#buyer_first_error").html("First Address is required.").addClass('has_error');
            error_count++;
        } else {
            $("#buyer_first_error").html("").removeClass('has_error');
        }
        if (city == "") {
            $("#buyer_city_error").html("City is required.").addClass('has_error');
            error_count++;
        } else if (location_description.length !== 0 && location_description.indexOf($("#location").val()) <= -1) {
                $("#buyer_city_error").text('Please choose a location from the dropdown list of choices').addClass('has_error');
                $('#location').val('');
                error_count++;
        } else {
            $("#buyer_city_error").html("").removeClass('has_error');
        }


        if (country_val == -1 || country_val == "") {
            $("#buyer_country_error").html("Country is required.").addClass('has_error');
            error_count++;
        } else {
            $("#buyer_country_error").html("").removeClass('has_error');
        }
        if (post_code == "") {
            $("#seller_code_error").html("Postal Code is required.").addClass('has_error');
            error_count++;
        } else {
            $("#seller_code_error").html("").removeClass('has_error');
        }

        if (error_count > 0) {
            scroll_to_error('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(4000).fadeOut('fast');
            return false;
        } else {
            var edit_seller_business_information = new FormData($('#editsellerbuisness')[0]);
            var form = $(this);
            $.ajax({
                type: 'post',
                url: base_url + '/updatesellerbuisness',
                data: edit_seller_business_information,
                processData: false,
                contentType: false,
                success: function (resp) {

                    if (resp == 1) {
                        $("#success_msg_expert_edit").html("<div class='bg-success'>Business address updated</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                        scroll_to_error('#success_msg_expert_edit');
                        return false;
                    } else {

                        $("#success_msg_expert_edit").html("<div class='bg-success'>Please try again,due to some problem unable to update.</div>").fadeIn('fast').delay(4000).fadeOut('fast');
                        scroll_to_error('#success_msg_expert_edit');
                        return false;
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);

                },
            });
        }
    });
    $("#save_seller_communication").on('click', function () {
        var edit_seller_communication_information = new FormData($('#editseller_communication')[0]);
        var form = $(this);
        $.ajax({
            type: 'post',
            url: base_url + '/updatesellerCommunication',
            data: edit_seller_communication_information,
            processData: false,
            contentType: false,
            success: function (resp) {

                $("#success_msg_expert_edit").html("<div class='bg-success'>" + resp.msg + "</div>").fadeIn('fast').delay(2000).fadeOut('fast');
                $("#success_msg_expert_edit").show();
                return false;
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);

            },
        });
    });
});

$(document).on('click', '.dropdown span', function () {
    if ($('#location').is(":visible")) {
        $('#location').val($(this).text());
        $("#city_name").val($(this).find("input#city").val());
        $("#country_name").val($(this).find("input#country").val());
    } else {
        $('#billing_address_city').val($(this).text());
        $("#billing_address_city_name").val($(this).find("input#city").val());
        $("#billing_address_country_name").val($(this).find("input#country").val());
    }
     $('#tags , #billing_address_tags').hide();
});

$("#location").on('keyup', function (e) {
    var location = document.getElementById('location').value;
    if (e.keyCode === 13 || location == '') {
        $("#tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#tags"));
            return false;
        }
    }
    findLocation(location, $("#tags"));
});
$("#billing_address_city").on('keyup', function (e) {
    var location = document.getElementById('billing_address_city').value;
    if (e.keyCode === 13 || location == '') {
       $("#billing_address_tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#billing_address_tags"));
            return false;
        }
    }
    findLocation(location, $("#billing_address_tags"));
});
$('#have_vat').on('change', function() {
    if ($(this).is(':checked')) {
        $('.vat-section').removeClass('hide');
    } else {
        $('.vat-section').addClass('hide');
    }
})
$(".view-expert-contract").on('click', function (e) {
    var communication_id= $(this).attr("communication-id");
    var contract_id= $(this).attr("contract-id");
    var user_id= $(this).attr("user-id");
    var buyer_id= $(this).attr("buyer-id");
    var data = {communication_id: communication_id, buyer_id: buyer_id, expert_id: user_id};
    $.ajax({
        url: base_url + '/expertcontractviewpopup/' + contract_id,
        type: 'POST',
        data: data,
        async: false,
        success: function (response) {
            if (response.success == 1) {
              $("#view_contract_popup").html(response.content);
              $('#gotmatchpopup-' + contract_id).modal("show");
              $('.finish-cancel-btn').hide();
              $("#view_contract_popup").find(".contract-popup-actions").html('<a href="'+base_url+'/expert/messages?communication_id='+communication_id+'" class="btn standard-btn ">View Messages</a>');
            }else{
             $("#success_msg_expert_edit").html('<div id="error-msg-alert" class="alert alert-danger">Something went wrong!! Please try again.</div>').fadeIn('fast').delay(2600).fadeOut('fast');
            }
        }
    });
});
$(document).on('click', '.panel-heading a', function(e){
     $('.panel-heading a').css('pointer-events','auto');
     if(!$(this).hasClass('collapsed')){
         $(this).css('pointer-events','none');
     }
 });

$('.all-contracts').click(function () {
    $.each($(".contract_extensions-" + $(this).attr("parent_contract_id")), function (key, val) {
        val.click();
    });
});

var input = $("#seller_phone");
input.intlTelInput({
   preferredCountries: ['gb', 'us'],
   separateDialCode: true,
   formatOnDisplay: false,
   utilsScript: '../js/international-phone-codes-utils.js'
});