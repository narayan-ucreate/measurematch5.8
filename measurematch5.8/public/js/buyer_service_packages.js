$(document).ready(function () {
    if ($(window).width() < 1024) {
        $('body').addClass('buyer-mobile-display');
    }
    $('#eoi-in-service-package').on('click', function () {
        $('#expression-of-interest-pop-up').modal('show');
        $('#cover_letter_message').focus();
    });
    $('.close-negotiable-link').on('click', function () {
        $('.package-negotiable-column').hide();
    });
    if($('#show_rating').length){
        var rating_by_buyer = $('#show_rating').attr('average_rating');
        $("#show_rating").rateYo({
            rating: rating_by_buyer,
            numStars: 5,
            precision: 2,
            readOnly: true,
            ratedFill: "#1e70b7",
            starWidth: "15px"
        });
        $("#show_rating_bottom").rateYo({
            rating: rating_by_buyer,
            numStars: 5,
            precision: 2,
            readOnly: true,
            ratedFill: "#1e70b7",
            starWidth: "15px"
        });
    }
    
    if($('.buyer_ratings').length){
        $(".buyer_ratings").each(function(e){
            getRating(this.id, $(this).attr('rating'));
        });
    }
});
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
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});
$(document).on('click', '.save_the_package', function () {
    var buyer_id = $(this).attr('buyer-id');
    var service_package_id = $(this).attr('service-package-id');
    $.ajax({
        type: 'post',
        url: base_url + '/service/saveservicepackage',
        async: false,
        data: {buyer_id: buyer_id, service_package_id: service_package_id},
        success: function (response) {
            if (response) {
                $('#save_the_package').attr('saved_id', response);
                $('#save_the_package').html('Unsave this Service Package');
                $('#save_the_package').removeClass('save_the_package');
                $('#save_the_package').addClass('unsave_the_package');
            } else {
                return false;
            }

        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});
$(document).on('click', '.unsave_the_package', function () {
    var saved_service_package_id = $(this).attr('saved_id');

    $.ajax({
        type: 'post',
        url: base_url + '/deletesavedservicepackage',
        data: {saved_service_package_id: saved_service_package_id},
        async: false,
        success: function (response) {
            if (response == 1) {
                $('#save_the_package').html('Save this Service Package')
                $('#save_the_package').removeClass('unsave_the_package');
                $('#save_the_package').addClass('save_the_package');
            } else {
                return false;
            }

        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});
$(document).on('click', '#service_package_show_interest', function () {
    var show_intrerest_data = new FormData($("#service-package-show-interest-by-buyer")[0]);
    var service_package_id = $('#service_package_id').val()
    var sender_name = $("#sender_name");
        $(this).prop('disabled', true);
        $.ajax({
        type: 'post',
        url: base_url + '/showinterestinservicepackage',
        data: show_intrerest_data,
        processData: false,
        contentType: false,
        success: function (response) {
              if (response.success == true) {
               $('.modal').modal('hide');
               $("#show_interest_by_buyer_button").text("You've expressed interest").removeClass("express-interest-btn").addClass("interest-expressed-btn");
               $("#show_interest_by_buyer_button").attr("data-target","");
               $(this).prop('disabled', false);
               var interest_shown_by_buyer_auto_message = {type: 'interest_shown_by_buyer_auto_message', receiver: response.auto_message.receiver_id, communication_id:response.auto_message.communication_id, sender: response.auto_message.sender_id, sendername: sender_name, text: response.auto_message.msg, expertlink: response.auto_message.msg.expert_link};
               socket.emit('sendmessage', interest_shown_by_buyer_auto_message);
               if(response.hasOwnProperty('message')){
                   var interest_shown_by_buyer = {type: 'interest_shown_by_buyer', receiver: response.message.receiver_id, communication_id:response.message.communication_id, sender: response.message.sender_id, sendername: sender_name, text: response.message.msg, expertlink: response.message.expert_link};
                    socket.emit('sendmessage', interest_shown_by_buyer);
               }
               window.location.href = base_url + '/buyer/messages/service_package/' + service_package_id + "?communication_id=" + response.auto_message.communications_id;
            } else {
                $('#cover_letter_error_message').html("Something went wrong, please try again!!").fadeIn('fast').delay(2000).fadeOut(3000);
                $(this).prop('disabled', false);
                return false;
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});