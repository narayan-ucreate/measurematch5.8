var successful = 1;
var fail = 2;
function goBack(url) {
    window.location.href = url;
}
$(document).ready(function (e) {
    $('#projectSharedByExpert').on('click', function (e) {
        e.preventDefault();
        var referral_first_name = $('#referral_first_name').val().trim();
        var referral_last_name = $('#referral_last_name').val().trim();
        var referral_email = $('#referral_email').val().trim();
        var logged_in_user_email = $('#loggedInUserEmail').val().trim();
        var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var login_string = referral_email.toLowerCase().trim();
        var project_id = $('#projectId').val().trim();
        if (referral_first_name == '') {
            $('.validate_expert_first_name_error').html('Please enter first name').fadeIn('fast').delay(2000).fadeOut();
            return false;
        }if (referral_last_name == '') {
            $('.validate_expert_last_name_error').html('Please enter last name').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (referral_email == '') {
            $('.validate_expert_error').html('Please enter email address').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (login_string == logged_in_user_email) {
            $('.validate_expert_error').html('You cannot share project with yourself').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (!emailformat.test(referral_email))
        {
            $('.validate_expert_error').html('Please add valid email').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (emailformat.test(referral_email)) {
            $.ajax({
                type: 'post',
                url: base_url + '/checkShareProject',
                data: {email: referral_email, projectId: project_id},
                async: false,
                success: function (response) {
                   if (response == '0') {
                        $.ajax({
                            type: 'post',
                            url: base_url + '/shareProject',
                            data: {email: referral_email, expert_name: referral_first_name+' '+referral_last_name, projectId: project_id},
                            async: false,
                            success: function (response) {
                                if (response == '1') {
                                    $("#refer_expert").trigger('reset');
                                    $('.success').html('Thank you for sharing the project.').fadeIn('fast').delay(4000).fadeOut();
                                    $('.close').trigger('click');
                                    $("html, body").animate({scrollTop: 0}, "slow");
                                }
                            }
                        });
                    } else {
                        $('.validate_expert_error').html("You've been already shared this project with this user.").fadeIn('fast').delay(2000).fadeOut();
                        return false;
                    }
                }
            });
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#cover_letter_message").on("input", function () {
        $('#cover_letter_error_message').html("");

        if (this.value.length > 5000) {
            $('#cover_letter_error_message').html("Only 5000 characters are allowed.").fadeIn('fast').delay(2000).fadeOut(3000);
        }
    });
    $('.show-interest').on('click',function(){
        $('#show_interest').modal('show');
        $('#show_interest textarea').focus();
    });
    $('#show-interst-button').on('click', function (event) {
        $('#cover_letter_error_message').html("");
        event.preventDefault();
        var user_id = $(this).attr('user-id');
        var buyer_id = $(this).attr('buyer-id');
        var job_id = $(this).attr('job-id');
        var job_title = $("#job_title").text();
        var cover_letter_message = $('#cover_letter_message').val();
    
        if (cover_letter_message.length > 5000) {
            $('#cover_letter_error_message').html("Only 5000 characters are allowed.").fadeIn('fast').delay(2000).fadeOut(3000);
            return false;
        } else {
             $('#show-interst-button').attr('disabled', 'disabled');
            $.ajax({
                type: 'post',
                url: base_url + '/showInterest',
                dataType: 'json',
                data: {user_id: user_id, buyer_id: buyer_id, job_post_id: job_id, job_title: job_title, cover_letter_message: cover_letter_message},
                success: function (result) {
                    if (result.success == successful) {
                        $('#show_interest').modal('hide');
                        $("#show-interst-button").remove();
                        $("div#interest_section").html("<button data-text-original='Interest Shown' id='remove_interest' \n\
                        class='show-intrest-btn standard-btn interest-expressed-by-expert' job-id=" + job_id + " \n\
                        user-id=" + user_id + " buyer-id=" + buyer_id + ">You've Expressed Interest</button><p \n\
                        class='margin-top-10 view-express-intrest-btn'><a class='gilroyregular-bold-font' \n\
                        href="+ base_url + "/expert/messages?communication_id="+ result.data.communications_id +">View your \n\
                        Expression of Interest</a></p>");
                        if($('.posted-project-view .job-view-intrest-shown').length>0) {
                            $('#number_of_interests').text(parseInt($('.posted-project-view #number_of_interests').text()) + parseInt(1));
                        } else {
                            $('.posted-project-view .job-view-feedback').after("<span class='job-view-intrest-shown'><span id='number_of_interests'>1</span> expressed interest</span>");
                        }
                        $(".invite-send-btn").prop("disabled", false);
                        $('#acknowledge_interest_shown').modal('show');
                        var show_interest_by_expert = {type: 'show_interest_by_expert', receiver: user_id, communication_id: result.data.communications_id, sender: buyer_id, sendername: "", text: result.data.msg, expertlink: result.data.expert_link, project_type:$('#project_type').val(), id:$('#project_id').val()};
                        socket.emit('sendmessage', show_interest_by_expert);
                    }
                    if (result.success == fail) {
                        $('#shown-messages').text(result.msg);
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },

            });
        }

    });

    $('#remove_interest').on('click', function (event) {
        event.preventDefault();
        return false;
        var user_id = $(this).attr('user-id');
        var buyer_id = $(this).attr('buyer-id');
        var job_id = $(this).attr('job-id');
        var job_title = $("#job_title").text();
        $('#remove_interest').attr('disabled', 'disabled');

        $.ajax({
            type: 'get',
            url: base_url + '/removeInterest',
            dataType: 'json',
            data: {user_id: user_id, buyer_id: buyer_id, job_post_id: job_id, job_title: job_title},
            success: function (result) {
                if (result.success == successful) {
                    $("#remove_interest").remove();
                    $("div#interest_section").html("<button type='button' id='example-one' job-id=" + job_id + " user-id=" + user_id + " buyer-id=" + buyer_id + " data-text-swap='Show interest' \n\
                    class='show-intrest-btn standard-btn' data-text-original='Show interest'>Show interest</button>\n\
                    <a data-toggle='modal' data-target='#share_project' href='javascript:void(0)' title='Share this project' class='share-project-btn white-btn'><i aria-hidden='true' class='fa fa-share-alt'></i>Share this project</a>");
                }
                if (result.success == fail) {
                    $('#shown-messages').text(result.msg);
                }
            },
            error: function (response) {
                alert('Error: Please refresh the page');
            }
        })
    });

    $(document).on({
        mouseenter: function () {
            $(this).addClass('cross-sign');
        },
        mouseleave: function () {
            $(this).removeClass('cross-sign');
        }
    }, "#remove_interest");

});
