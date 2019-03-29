var receiver_id = '';
var filepath = '';
var offset = 0;
var months = ["Jan", "Feb", "Mar", "April", "May", "June",
    "July", "Aug", "Sept", "Oct", "Nov", "Dec"
];
var last_date = '';
var read = 1;
var user_type_expert = 1;
var user_type_buyer = 2;
var fadeout_time_limit = 4000;
var contract_ajax_flag = true;
var view_full_profile = false
$(function () {
    setLocalTime();
    var chatmodule = {
        contractDetailsForm: null,
        setwindowheight: function () {
            windowresize();
        },
        submit: function () {
            $('#send-message').submit(function (e) {
                e.preventDefault();
                if ($('#one-to-one-chatbox').val().trim().length == 0
                    && $('#message-attachment-file').val().length == 0
                    && $('#proposal-attachment-file').length == 0) {
                    $("#message_error").show().text("Please enter your message")
                        .fadeOut(fadeout_time_limit);
                    return false;
                }
                if ($('#one-to-one-chatbox').val().trim().length > 10000) {
                    $("#message_error").show().text("The character limit for messages is 10,000")
                        .fadeOut(fadeout_time_limit);
                    return false;
                }
                if (getProjectType() == 'service_package') {
                    if ($("#available_status_value").val() != "") {
                        sendExpertAvailibility(sender_id);
                    }
                }
                $('.send-btn.btn').prop("disabled", true);
                $('.mobile-send-message-button').attr("disabled", "disabled");
                var form_data = new FormData($(this)[0]);
                form_data.append('message', $('#one-to-one-chatbox').val());
                form_data.append('initial_message', $('#check_initital_message').val());
                fixForFormdataNotSuportsEmptyFileSafariBug(form_data);

                $.ajax({
                    type: 'post',
                    url: base_url + '/savemessage',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            if ($("#available_status_value").val() != "") {
                                var message_type = 'availibility_message';
                            } else {
                                var message_type = 'normal-message';
                            }
                            var message = {
                                type: message_type,
                                text: response.text,
                                receiver: response.receiver_id,
                                communication_id: response.communication_id,
                                sender: response.sender_id,
                                project_type: getProjectType(),
                                id: getProjectId(),
                                file: response.filepath,
                                sender_name: sender_name,
                                message_date: response.message_date,
                                message_time: response.message_time,
                                is_initial_message : response.is_initial_message
                            };
                            if (response.text && response.filepath) {
                                $('.existing-chat').append(senderMessageTextAndAttachmentPanel(
                                    sender_name,
                                    response.text,
                                    response.filepath,
                                    response.filepath.replace(/^.*[\\\/]/, ''),
                                    response.message_date,
                                    response.message_time));
                            } else {

                                if (response.filepath) {
                                    $('.existing-chat').append(senderMessageAttachmentPanel(
                                        sender_name,
                                        response.filepath,
                                        response.filepath.replace(/^.*[\\\/]/, ''),
                                        response.message_date,
                                        response.message_time));
                                }
                                if (response.text) {
                                    $('.existing-chat').append(senderMessageTextPanel(
                                        sender_name,
                                        response.text,
                                        false,
                                        response.message_date,
                                        response.message_time));
                                }
                            }
                            socket.emit('sendmessage', message);
                            if ($('#check_initital_message').val() == 1) {
                                $('#check_initital_message').val(0);
                                $('#initital_message').val('');
                                location.reload();
                            }
                            $(".list-group a[communication-id*='"
                                + response.communication_id + "']")
                                .find('.time')
                                .html(currentTime()
                                    .time);

                            $('.send-btn.btn').prop("disabled", false);
                            $('.mobile-send-message-button').attr("disabled", "disabled");
                            $('#message-attachment-file').val('');
                            $('#file-upload').text('');
                            $('#message_error').text('');
                            $('#one-to-one-chatbox').val('');
                            $("#available_status_value").val("")
                            resetMessageTextArea();
                            windowresize();
                            scrolldown();
                        }
                    },
                    error: function (jqXHR, exception) {
                        displayErrorMessage(jqXHR, exception);
                    }
                });
            });
        },
        addActiveClassOnLoad: function () {
            if(getParameterByName("communication_id")
                && $(".list-group a[communication-id*='"
                    + getParameterByName("communication_id")
                    + "']").length)
            {
                $(".list-group a[communication-id='"
                    + getParameterByName("communication_id")
                    + "']").addClass('active');
                if(getUserType() == user_type_buyer){
                    window.history.pushState(null, null, base_url
                        + '/expert/messages');
                }
                else
                {
                    window.history.pushState(null, null, base_url
                        + '/buyer/messages/'
                        +getProjectType()
                        +'/'+getProjectId());
                }
            }
            else if (getParameterByName("view_communication_contract")
                && $(".list-group a[communication-id*='"
                    + getParameterByName("view_communication_contract")
                    + "']").length)
            {
                var cid = (getParameterByName("view_communication_contract"));
                $(".list-group a[communication-id ='"
                    + getParameterByName("view_communication_contract")
                    + "']").addClass('active');
                $('.list-group').animate({
                    scrollTop: $(".list-group a[communication-id ='"
                        + getParameterByName("view_communication_contract")
                        + "']").offset().top
                }, 'slow');
                if (getUserType() == user_type_buyer)
                {
                    window.history.pushState(null, null, base_url
                        + '/expert/messages');
                    viewProjectByExpert();
                } else
                {
                    window.history.pushState(null, null, base_url
                        + '/buyer/messages/'
                        + getProjectType()
                        + '/' + getProjectId());
                    setTimeout(
                        function () {
                            viewOffer();
                        }, 300);
                }

            }
            else
            {
                $(".list-group a:first").addClass('active');
                resetMessageTextArea();
            }
        },
        showMessageListOnLoad: function () {
            offset = 0;
            getMessageList(getUserId(), getUserType(), getCommunicationId(), offset);
            scrolldown();
        },
        showUserProfileOnLoad: function () {
            getUserProfile(getUserId(), getUserType(), getCommunicationId());
        },
        showUserContractDetailsOnLoad: function () {
            getUserContractDetails(getCommunicationId(), getContractId());
        },
        showMessageCountOnLoad: function () {
            getMessageCount(getCommunicationId());
        },
        markAllMessagesReadOnLoad: function () {
            markAllMessagesRead(getCommunicationId());
        },
        getNotificationsOnLoad: function () {
            getNotifications(getCommunicationId());
        },
        addRemoveActiveClass: function () {
            $('.list-group').on('click', 'a', function () {
                $('.list-group').find('.active').removeClass('active');
                $(this).addClass('active');
            });
            resetMessageTextArea();
        },
        showMessageList: function () {
            $('.list-group').on('click', 'a', function () {
                var this_element = $(this);
                $('.rebooking-section').removeClass('white-bg');
                if (!this_element.next().hasClass('rebooking-section')) {
                    $('.rebooking-section').addClass('white-bg');
                }
                $('.receiver-user-image').css('background-image', this_element.find('.profile-img').css('background-image'));
                $('.receiver-user-name').text(this_element.find('span .seller-name').text());
                $('.receiver-user-company').text(this_element.find('span .user-title').text());
                $('#appendchat').empty();
                offset = 0;
                getMessageList(getUserId(), getUserType(), getCommunicationId(), offset);
                scrolldown();
                resetMessageTextArea();
            });
        },
        showUserProfile: function () {
            $('.list-group').on('click', 'a', function () {
                getUserProfile(getUserId(), getUserType(), getCommunicationId(),$(this).attr('start-conversation'));
            });
        },
        showContractDetails: function () {
            $('.list-group').on('click', 'a', function () {
                getUserContractDetails(getCommunicationId(), getContractId());
            });
        },
        showMessageCount: function () {
            $('.list-group').on('click', 'a', function () {
                getMessageCount(getCommunicationId());
            });
        },
        showMessageRead: function () {
            $('.list-group').on('click', 'a', function () {
                markAllMessagesRead(getCommunicationId());
            });
        },
        onScrollLoadMessages: function () {
            $("#chat-box").scroll(function () {
                if ($(this).scrollTop() == 0) {
                    offset = offset + 10;
                    if ($('#message-count').val() > offset) {
                        getMessageList(getUserId(), getUserType(), getCommunicationId(), offset);
                        var scrollbottoms = $(document).height() + 600;
                        $(this).animate({scrollTop: scrollbottoms}, 50);
                    }
                }
            });
        },
        isCheckedonLoad: function () {
            var check = isConversationStarted();
            if (check == 'yes') {
                isCheckedInitiateConversation();
            } else {
                notCheckedInitiateConversation();
            }
        },
        isCheckedOnClick: function () {
            $('.list-group').on('click', 'a', function () {
                var check = isConversationStarted();
                if (check == 'yes') {
                    isCheckedInitiateConversation();
                } else {
                    notCheckedInitiateConversation();
                }
            });
        }
    }

    chatmodule.setwindowheight();
    chatmodule.submit();
    chatmodule.addActiveClassOnLoad();
    chatmodule.showMessageListOnLoad();
    chatmodule.showUserProfileOnLoad();
    chatmodule.showUserContractDetailsOnLoad();
    chatmodule.addRemoveActiveClass();
    chatmodule.showMessageList();
    chatmodule.showUserProfile();
    chatmodule.showContractDetails();
    chatmodule.showMessageCountOnLoad();
    chatmodule.showMessageCount();
    chatmodule.showMessageRead();
    chatmodule.onScrollLoadMessages();
    chatmodule.isCheckedonLoad();
    chatmodule.isCheckedOnClick();
    chatmodule.markAllMessagesReadOnLoad();
    chatmodule.getNotificationsOnLoad();
 
 if (!is_admin_panel_view) { socket.on('message', function (data) {
        if (data.communication_id != getCommunicationId()) {
            $(".list-group a[communication-id*='" + data.communication_id + "']").attr('start-conversation', 'yes');
            getNotifications(data.communication_id);
            return false;
        } else {
            markAllMessagesRead(data.communication_id);
        }
        if (data.type == 'normal-message') {
            if (data.file && data.text) {
                $('.existing-chat').append(receiverMessageTextAndAttachmentPanel(data.sender_name, data.text, data.file, data.file.replace(/^.*[\\\/]/, '')));
            } else {
                if (data.text) {
                    if(data.is_initial_message == 1){
                        $(".list-group a[communication-id*='" + data.communication_id + "']").click();
                        $(".list-group a[communication-id*='" + data.communication_id + "']").attr('start-conversation', 'yes');
                        $('#send-message-box').show();
                        $('#one-to-one-chatbox').removeAttr('disabled');
                        $('#message-attachment-file').removeAttr('disabled');
                    }else{
                        $('#message-' + data.sender + ' .existing-chat').append(receiverMessageTextPanel(data.sender_name, data.text));
                    }
                }
                if (data.file) {
                    $('#message-' + data.sender + ' .existing-chat').append(receiverMessageAttachmentPanel(data.sender_name, data.file, data.file.replace(/^.*[\\\/]/, '')));
                }
            }

        }
        
        if (data.type == 'availibility_message') {
            $(".list-group a[communication-id*='" + data.communication_id + "']").attr('start-conversation', 'yes');
            $('#availability-accepted-' + data.receiver).attr('checked', 'checked');
            $('#one-to-one-chatbox').removeAttr('disabled');
            $('#message-attachment-file').removeAttr('disabled');
            if (data.file && data.text) {
                $('.existing-chat').append(receiverMessageTextAndAttachmentPanel(data.sender_name, data.text, data.file, data.file.replace(/^.*[\\\/]/, '')));
            } else {
                if (data.text) {
                    $('#message-' + data.sender + ' .existing-chat').append(receiverMessageTextPanel(data.sender_name, data.text));
                }
                if (data.file) {
                    $('#message-' + data.sender + ' .existing-chat').append(receiverMessageAttachmentPanel(data.sender_name, data.file, data.file.replace(/^.*[\\\/]/, '')));
                }
            }
        }

        if (data.type == 'make_offer') {
            if (getProjectType() == 'service_package') {
                var project_link = '/servicepackage/detail/';
            } else {
                var project_link = '/projects_view?sellerid=';
            }
            var message = receiverViewProposalBlock(receiverName(), data.contract_start_date, data.contract_end_date, data.contract_rate, sender_id, data.accepted_status);
            $("#contract_id").val(data.contract_id);
            $('#message-' + data.sender + ' .existing-chat').append(message);
            $('#offer-' + data.receiver).attr('checked', 'checked');
            $('#view_contract_text').html('<span onclick="viewProjectByExpert(this);" class="seller-name messages-view-contract"><a href="javascript:void(0)"  title="View contract">View contract</a></span>');
            $('.messages-project-name').html('<a href="' + base_url + project_link + data.project_id + '"> <span class="job_role">' + data.project_name + '</span></a>');
            $('.view-edit-offer-details-' + sender_id).show();
        }

        if (data.type == 'contract_updated') {
            var append_message = receiverMessageTextPanel( data.sendername, data.text + ' ' + data.buyer_link);
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }
        
        if (data.type == 'accept_offer') {
            $('#expert-action-accepted-offer-' + data.receiver).prop('checked', 'checked');
                if (!data.is_extended) {
                    $('#expert-project-compelted-' + data.receiver).show();
                    $('.view-proposal-' + data.receiver).addClass('buyer-accepted-proposal');
                    $('.view-proposal-' + data.receiver + ' .send-contract').removeClass('btn standard-btn').addClass('white-bg white-bg-btn').text('View Contract');
                    $('.view-proposal-' + data.receiver + ' .send-contract').attr("href", "javascript:void()");
                } else {
                    $('#buyer-accepted-offer-' + data.receiver).prop('checked', 'checked');
                    $('#buyer-project-compelted-' + data.receiver).show();
                }
            
            $('#expert-project-completed-' + data.receiver + ' #mark_contract_complete_confirmation_button').attr('contract_id', data.contract_id);
            $('#check-expert-project-compelted-' + data.receiver).prop('checked', false);
            $('#expert-project-completed-' + data.receiver).show();
            if(data.acceptance_message_to_expert != ''){
                var expert_message = receiverMessageTextPanel(receiverName(), data.acceptance_message_to_expert, true);
                $('#message-' + data.sender + ' .existing-chat').append(expert_message);
            }
            
            var admin_message = expertContractAcceptanceAutomatedMessageFromMmTextPanel(
                                        data.message_to_expert,
                                        messageTime(),
                                        messageDate(),
                                        data.contract_start_date,
                                        data.contract_end_date,
                                        data.contract_rate,
                                        data.sender
                                );
            $('#message-' + data.sender + ' .existing-chat').append(admin_message);
        }

        if (data.type == 'marked_by_buyer') {
            $('#check-expert-project-compelted-' + data.receiver).prop('checked', true);
            $('#expert-project-completed-' + data.receiver).hide();
            var append_message = receiverMessageTextPanel( data.sendername, data.text + ' ' + data.expertlink);
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }
        if (data.type == 'monthly_retainer_finished_by_buyer') {
            $('#check-expert-project-compelted-' + data.receiver).prop('checked', 'checked');
            $('#expert-project-completed-' + data.receiver).hide();
            var append_message = receiverMessageTextPanel(  data.sendername , data.text + ' ' + data.expertlink);
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }
        if (data.type == 'monthly_retainer_buyer_feedback_request') {
            var append_message = senderMessageTextPanel( data.sendername , data.text);
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }
        if (data.type == 'marked_by_buyer_feedback_request') {
            var append_message = senderMessageTextPanel( data.sendername , data.text + ' ' + data.expertlink );
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }


        if (data.type == 'marked_by_expert') {
            var append_message = receiverMessageTextPanel(  data.sendername , data.text + ' ' + data.expertlink);
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }
        if (data.type == 'monthly_retainer_finished_by_expert') {
            $('#check-buyer-project-compelted-' + data.receiver).prop('checked', 'checked');
            $('#buyer-project-compelted-' + data.receiver).hide();
            var append_message = receiverMessageTextPanel(  data.sendername , data.text + ' ' + data.buyerlink );
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
        }

        if (data.type == 'buyer_feedback') {
            var append_message = receiverMessageTextPanel(  sender_name , data.text + ' ' + data.expertlink );
            $('#message-' + data.sender + ' .existing-chat').append(append_message);
            $('#check-expert-feedback-' + data.receiver).prop('checked', 'checked');
        }
        scrolldown();
    });}
});
$(document).ready(function() {
    if ($(window).width() < 1024) {
       $('body').addClass('mobile-messaging-view');
       $('.message-attachment-icon').hide();
       $('.mobile-send-message-button').removeClass('hide');
       $('.contact-list').addClass('user-name-image-mobile');
    }
    if ($('#proposal_sent').val() == 'true') {
        var buyer_name = $(document).find('.seller-name').html().split(' ');
        $('.buyer-name').text(buyer_name[0]);
        $('.buyer-company-name').text($(document).find('#buyer_company_name').val())
        $('#proposal_success_info').modal('show');
    }

    $('.close-proposal-sent-pop-up').on('click', function() {
        var uri = window.location.href.toString();
        if (uri.indexOf("?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
    })
    
    $(document).on('click', '.user-name-image-mobile', function(){
        $('#show-user-list').hide();
        $('#show-message-list').show();
        if (!$(this).parent('div').hasClass('expressions')) {
            $('.message-user-info').removeClass('hide');
        }
    });
    
    $('#back_to_user_list').on('click', function(){
        $('#show-message-list').hide();
        $('#show-user-list').show();
        $('.message-user-info').addClass('hide');
    });
    
    $(document).on('click', '.view-contract-by-expert', function(){
        viewProjectByExpert();
    });
})
function currentTime(){
    var current_date = new Date();
    var current_time = {
        date: getDateFormatInString(current_date),
        time: getTime(current_date)
    };
    return current_time;
}
function messageDate(date){
    return (typeof date === 'undefined') ? currentTime().date: date;
}
function messageTime(time){
    return (typeof time === 'undefined') ? currentTime().time: time;
}
function setLocalTime(){
    $.ajax({
        url: base_url + '/addlocaltimezonetochat?timezone=' + $('#timezone').val(),
        type: 'GET',
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
};

$(window).resize(function () {
    windowresize();
});

$(window).load(function () {
    windowresize();
});

function numberWithCommas(x){
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function windowresize() {
    window_height = $(window).outerHeight();
    nav_height = $('nav').outerHeight();
    header_height = 100;
    if (is_admin_panel_view) {
        header_height = 300 ;
    }
    min_height = window_height - header_height;
    var message_header_height = $('body').find('#myproject_section_header').outerHeight();
    var footer_height = $('body').find("footer.inner_footer").outerHeight();
    min_height = min_height - message_header_height;
    window_message_area_height = 0;
    if (!is_admin_panel_view) {
        window_message_area_height = document.getElementById('one-to-one-chatbox').clientHeight;
    }

    $('.seller-message-list-view').css('height', min_height);
    $('.user-list-panel').css('height', min_height);
    $('.chat-block').css('height', min_height);
    $('.chat-pannel').css('height', min_height);
    $('.expert_profile_inner_panel').css('height', min_height);
    $('body').find('.expert-profile-section').css('height', min_height);
    $('.buyer-profile-panel').css('height', min_height);
    $('.seller_message_profile_preview').css('height', min_height);
    $('.welcome-contianer-panel').css('height', min_height);
    message_height = window_height - 200 - window_message_area_height;
    $('.conversation-block').css('height', message_height);
    $('.welcome-contianer-panel').css('height', (message_height + 60));
    $('#chat-box').css('height', parseInt(min_height) - parseInt($('#send-message-box').outerHeight()) - 50)
}
function resetMessageTextArea() {
    if (is_admin_panel_view) {
        return false
    }
    var element = document.querySelector('#one-to-one-chatbox');
    element.value = '';
    element.style.cssText = 'height:20px;';
    $("#counter_message").text("1000 of 1000 characters left");
}
get_messages_request_complete = false;
function getMessageList(user_id, user_type, communication_id, offset) {
    $('#show-receiver-name').text($('.list-group').find("a.active span.receiver-name").text());
    if (communication_id == '') {
        return false;
    } else {
        $.ajax({
            url: base_url + '/users/' + communication_id + '/messages?type=pagination&offset=' + offset,
            type: 'GET',
            beforeSend: function() {
                if (get_messages_request_complete === false) {
                    get_messages_request_complete = true;
                    return typeof communication_id !== 'undefined'
                } else {
                    return false;
                }
            },
            success: function (response) {
                get_messages_request_complete = false;
                if (typeof response.preview_profile !== 'undefined' && response.preview_profile === true) {
                    view_full_profile = true;
                } else {
                    view_full_profile = false;
                    var iterate = 0;
                    $(response.data).each(function (index, value) {
                        var get_message_date = new Date(value.created_at);
                        var message_time = value.message_time;
                        if (sender_id == value.sender_id) {
                            if (value.message_sender_role == "admin" && value.message_type == 'normal') {
                                receiverAutomatedMessageFromMmTextPanel(value, message_time);

                            } else if (value.message_sender_role == "admin" && value.message_type == 'expert_welcome_message') {
                                if (current_user_type == user_type_expert) {
                                    receiverAutomatedMessageFromMmTextPanel(value, message_time);
                                }
                            } else if (value.message_sender_role == "expert") {
                                if (current_user_type == user_type_buyer) {
                                    receiverAutomatedMessageFromMmTextPanel(value, message_time);
                                }

                            } else if (value.message_sender_role == "buyer") {
                                if (current_user_type == user_type_expert && value.message_type == 'buyer_accepting_contract_to_expert') {
                                    return $('.existing-chat').prepend(expertContractAcceptanceAutomatedMessageFromMmTextPanel(
                                            value.msg, 
                                            message_time, 
                                            value.show_date, 
                                            response.latest_contract.job_start_date,
                                            response.latest_contract.job_end_date,
                                            response.latest_contract.rate,
                                            value.receiver_id));
                                }
                                if (current_user_type == user_type_buyer && value.message_type == 'buyer_rebooking_project') {
                                    return $('.existing-chat').prepend(buyerRebookProjectBlock(
                                            message_time,
                                            value.show_date,
                                            response.project_details.job_title,
                                            response.project_details.description,
                                            value.sender_id
                                            )
                                    );
                                }
                                if (current_user_type == user_type_expert) {
                                    return receiverAutomatedMessageFromMmTextPanel(value, message_time);
                                }

                            } else if (value.automated_message == true && value.message_sender_role != "admin" && value.message_type == 'normal') {
                                if (current_user_type == user_type_buyer) {
                                    var link = value.buyer_link;
                                } else {
                                    var link = value.expert_link;
                                }
                                if (typeof link === 'undefined'
                                        || !link
                                        || $('body').hasClass('mobile-messaging-view')) {
                                    var link = ""
                                }
                                $('.existing-chat').prepend(senderMessageTextPanel( sender_name, value.msg + ' ' + link, false, value.show_date, message_time));
                            } else if (value.automated_message == true && value.message_type == 'initiation') {
                                $('.existing-chat').prepend(automatedMessageFromMmTextPanel(value.msg,value.show_date, message_time));
                            } else {
                                if (value.attachment && value.msg) {
                                    $('.existing-chat').prepend(
                                                    senderMessageTextAndAttachmentPanel(
                                                            sender_name,
                                                            value.msg,
                                                            value.attachment,
                                                            value.attachment.replace(/^.*[\\\/]/,''),
                                                            value.show_date,
                                                            message_time));
                                } else {
                                    if (value.attachment) {
                                        $('.existing-chat').prepend(
                                                        senderMessageAttachmentPanel(
                                                                sender_name,
                                                                value.attachment,
                                                                value.attachment.replace(/^.*[\\\/]/,''),
                                                                value.show_date,
                                                                message_time));
                                    }
                                    if (value.msg && value.message_type != 'proposal_sent_by_expert' && value.message_type != 'buyer_accepting_proposal_to_expert') {
                                        var buyer_acceptance_status = (value.message_type == 'buyer_accepting_proposal') ? true : false;

                                        $('.existing-chat').prepend(
                                                        senderMessageTextPanel(
                                                                sender_name,
                                                                value.msg,
                                                                buyer_acceptance_status,
                                                                value.show_date,
                                                                message_time));
                                    }

                                    if (value.msg && value.message_type == 'proposal_sent_by_expert' && !(jQuery.isEmptyObject(
                                            response.latest_contract))) {
                                        $('.existing-chat').prepend(
                                                        senderViewProposalBlock(
                                                                sender_name,
                                                                response.latest_contract.job_start_date,
                                                                response.latest_contract.job_end_date,
                                                                response.latest_contract.rate,
                                                                sender_id,
                                                                response.latest_contract.status,
                                                                value.show_date,
                                                                message_time));
                                    }
                                }
                            }

                        } else {
                            if (value.message_sender_role == "expert" && value.message_type == 'expression_of_interest') {
                                return false;
                            }
                            if (value.message_sender_role == "admin" && value.message_type == 'normal') {
                                receiverAutomatedMessageFromMmTextPanel(value, message_time);

                            } else if (value.message_sender_role == "admin" && value.message_type == 'expert_welcome_message') {
                                if (current_user_type == user_type_expert) {
                                    receiverAutomatedMessageFromMmTextPanel(value, message_time);
                                }
                            } else if (value.message_sender_role == "expert") {
                                if (current_user_type == user_type_buyer && value.message_type == 'buyer_accepting_contract') {
                                    return $('.existing-chat').prepend(buyerContractAcceptanceAutomatedMessageFromMmTextPanel(
                                            value.msg,
                                            message_time,
                                            value.show_date,
                                            response.latest_contract.job_start_date,
                                            response.latest_contract.job_end_date,
                                            response.latest_contract.rate,
                                            value.receiver_id));
                                } else if (current_user_type == user_type_buyer) {
                                    receiverAutomatedMessageFromMmTextPanel(value, message_time);
                                }
                            } else if (value.message_sender_role == "buyer") {
                                if (current_user_type == user_type_expert && value.message_type == 'buyer_rebooking_project') {
                                    return $('.existing-chat').prepend(expertRebookProjectBlock(
                                            message_time,
                                            value.show_date,
                                            response.project_details.job_title,
                                            response.project_details.description,
                                            value.sender_id
                                            )
                                    );
                                }
                                if (current_user_type == user_type_expert) {
                                    return receiverAutomatedMessageFromMmTextPanel(value, message_time);

                                }
                            }
                            else if (value.automated_message == true && value.message_sender_role != "admin" && value.message_type == 'normal') {
                                if (current_user_type == user_type_buyer) {

                                    var link = value.buyer_link;
                                } else {
                                    var link = value.expert_link;
                                }
                                if (typeof link === 'undefined'
                                        || !link
                                        || $('body').hasClass('mobile-messaging-view')) {
                                    var link = ""
                                }
                                $('#message-' + value.sender_id + ' .existing-chat').prepend(receiverMessageTextPanel($('.list-group a.active span.seller-name').text(), value.msg + ' ' + link, false, value.show_date, message_time));
                            } else {
                                if (value.attachment && value.msg) {
                                    $('#message-' + value.sender_id + ' .existing-chat').prepend(receiverMessageTextAndAttachmentPanel($('.list-group a.active span.seller-name').text(), value.msg, value.attachment, value.attachment.replace(/^.*[\\\/]/, ''),value.show_date, message_time));
                                } else {
                                    if (value.attachment) {
                                        $('#message-' + value.sender_id + ' .existing-chat').prepend(receiverMessageAttachmentPanel($('.list-group a.active span.seller-name').text(), value.attachment, value.attachment.replace(/^.*[\\\/]/, ''),value.show_date, message_time));
                                    }
                                    if (value.msg && (value.message_type != 'proposal_sent_by_expert' && value.message_type != 'buyer_accepting_proposal')) {
                                        buyer_acceptance_status = false
                                        if(value.message_type == 'buyer_accepting_proposal_to_expert')
                                            var buyer_acceptance_status = true
                                        
                                        $('#message-' + value.sender_id + ' .existing-chat').prepend(receiverMessageTextPanel($('.list-group a.active span.seller-name').text(), value.msg, buyer_acceptance_status, value.show_date, message_time));
                                    }
                                    if (value.msg && (value.message_type == 'proposal_sent_by_expert') && !(jQuery.isEmptyObject(response.latest_contract))) {
                                        $('#message-' + value.sender_id + ' .existing-chat').prepend(receiverViewProposalBlock($('.list-group a.active span.seller-name').text(), response.latest_contract.job_start_date , response.latest_contract.job_end_date , response.latest_contract.rate , value.receiver_id, response.latest_contract.status , value.show_date, message_time));

                                    }
                                }
                            }
                        }

                        iterate++;
                    });
                }

            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    }


}

function getUserProfile(user_id, user_type, communication_id, communication_status) {
    if (communication_id == '' ||  typeof user_id === 'undefined') {
        return false;
    } else {
        var data = {user_id: user_id, user_type: user_type, communication_id: communication_id};
        $.ajax({
            url: base_url + '/users/' + user_id + '/profile',
            type: 'GET',
            data: data,
            beforeSend: function() {
                return typeof user_id !== 'undefined'
            },
            async: false,
            success: function (response) {
                var expert_profile_ele = $('body').find('.expert_profile_inner_panel');
                var message_list_ele = $('body').find('.seller-message-list-view');
                var expert_profile_section_ele =  $('body').find('.expert-profile-section');

                if (typeof response.view_profile !=='undefined' && response.view_profile === true) {
                    expert_profile_ele.removeClass('col-lg-9 col-md-9 col-sm-9');
                    expert_profile_section_ele.removeClass('hide');
                    expert_profile_ele.addClass('col-lg-12 col-md-12 col-sm-12');
                    message_list_ele.removeClass('col-lg-4 col-md-4 col-sm-4');
                    message_list_ele.addClass('col-lg-3 col-md-3 col-sm-3');
                    expert_profile_section_ele.removeClass('col-lg-8 col-md-8 col-sm-8 col-xs-12');
                    expert_profile_section_ele.addClass('col-lg-9 col-md-9 col-sm-9 col-xs-12');
                    $('body').find('.conversation-section').addClass('hide');
                    expert_profile_section_ele.html(response.profile);
                    $('body').find('.conversation_start_container').html(response.start_conversation);
                    $('#show-chat-user-profile').html('');
                    $('#show-chat-user-profile').addClass('hide');
                    if (!is_admin_panel_view) {
                        var rating_by_buyer = $('body').find('.show_rating').attr('expert_rating');
                        $('body').find(".show_rating").rateYo({
                            rating: rating_by_buyer,
                            numStars: 5,
                            precision: 2,
                            readOnly: true,
                            ratedFill: "#29235c",
                            starWidth: "20px"
                        });
                    }   
                } else {
                    if (typeof communication_status !== 'undefined' && communication_status=='no' && is_admin_panel_view) {
                        var admin_url = window.location.href+'?communication_tab=active';
                        $(location).attr('href',admin_url);
                    }
                    expert_profile_ele.addClass('col-lg-9 col-md-9 col-sm-9');
                    expert_profile_ele.removeClass('col-lg-12 col-md-12 col-sm-12');
                    message_list_ele.addClass('col-lg-4 col-md-4 col-sm-4');
                    message_list_ele.removeClass('col-lg-3 col-md-3 col-sm-3');
                    expert_profile_section_ele.addClass('col-lg-8 col-md-8 col-sm-8 col-xs-12');
                    expert_profile_section_ele.removeClass('col-lg-9 col-md-9 col-sm-9 col-xs-12');
                    expert_profile_section_ele.addClass('hide');
                    $('body').find('.conversation-section').removeClass('hide');
                    expert_profile_section_ele.html('');
                    $('#show-chat-user-profile').removeClass('hide');
                    $('#show-chat-user-profile').html(response);

                }
                    $('#chat-box').css('height', parseInt(min_height) - parseInt($('body').find('#send-message-box').outerHeight()) - 50)
                    $('body').find('.expert-profile-section').css('height', min_height);
                return true;
            },
            error: function (error) {
                return false;
            }
        });
    }

}

$('body').on('click', '.read-more', function() {
    var parent_ele = $(this).parents('.read-more-section').eq(0);
    parent_ele.find('.short-description').addClass('hide');
    parent_ele.find('.full-description').removeClass('hide');
});

$('body').on('click', '.read-less', function() {
    var parent_ele = $(this).parents('.read-more-section').eq(0);
    parent_ele.find('.short-description').removeClass('hide');
    parent_ele.find('.full-description').addClass('hide');
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

$('body').on('click', '.display-more-profile', function(e) {
    e.preventDefault();
    $('body').find('.service-package-section').removeClass('hide');
    $(this).addClass('hide')
    $('body').find('.user-full-profile').removeClass('hide');
})

$('body').on('click', '#start_conversation', function(e) {
    e.preventDefault();
       var height_limit = 242;
        document.querySelector('#initital_message').addEventListener('keyup', autoSizeTextAreaForInititalMessage);
        function autoSizeTextAreaForInititalMessage() {
            var element = this;
            if (element.scrollHeight < height_limit) {
                setTimeout(function () {
                    element.style.cssText = 'height:auto;';
                    element.style.cssText = 'height:' + element.scrollHeight + 'px; overflow:auto;';
                }, 0);
            } else {
                setTimeout(function () {
                    element.style.cssText = 'height:' + (height_limit - 2) + 'px; overflow:auto;';
                }, 0);
            }
        }

    $('body').find('#start_conversation_pop_up').modal('show');
    setTimeout(function () {
        document.getElementById('initital_message').focus()

    }, 200);
    e.stopPropagation();
});

$('body').on('submit', '#send_initial_message', function(e) {
    e.preventDefault();
    var message = $('#initital_message').val();
    $('body').find(".message_validation_error").addClass('hide');
    if (message != '') {
       initiateConversation(e)
    } else {
        $('body').find(".message_validation_error").removeClass('hide');
    }
})
 var request_complete = false;
function getUserContractDetails(communication_id, contract_id) {
    var data = {user_id: sender_id, communication_id: communication_id, contract_id: contract_id};
    $.ajax({
        url: base_url + '/users/contractdetail',
        type: 'GET',
        data: data,
        beforeSend: function() {
            if (request_complete === false) {
                request_complete = true;
                return typeof sender_id !== 'undefined';
            } else {
                return false;
            }
        },
        success: function (response) {
            request_complete = false;
            if (response.success == 1) {
                var result = response.data;
                if (result.communication_initiated_by_buyer == 1) {
                    $('#initiated-conversation').attr('ischeck', true);
                    $('#initiated-conversation').attr('checked', 'checked');
                    $('#conversation-' + sender_id).attr('checked', 'checked');
                    $('#expert-action-availability-accepted-' + sender_id).attr('checked', 'checked');
                    $('#availability-accepted-' + sender_id).attr('checked', 'checked');
                }
                if (result.contract_has_been_offered == 1) {
                    $('#create-offer').attr('checked', 'checked');
                    $('#offer-' + sender_id).attr('checked', 'checked');
                }
                if (result.contract_has_been_accepted_by_expert == 1) {
                    $('#buyer-accepted-offer-' + sender_id).attr('checked', 'checked');
                    $('#expert-action-accepted-offer-' + sender_id).attr('checked', 'checked');
                }
                if (result.contract_has_been_marked_complete_by_buyer == 1 || result.contract_has_been_marked_complete_by_expert == 1 || (result.hasOwnProperty('packaged_ended') && result.packaged_ended != '')) {
                    $('#check-expert-project-compelted-' + sender_id).attr('checked', 'checked');
                }
                if (result.contract_has_been_marked_complete_by_buyer == 1|| (result.hasOwnProperty('packaged_ended') && result.packaged_ended != '')) {
                    $('#check-buyer-project-compelted-' + sender_id).attr('checked', 'checked');
                }
                if ((result.hasOwnProperty('payment_processed') && (result.payment_processed != '' && result.payment_processed != '0'))) {
                    $('#check-buyer-payment-' + sender_id).attr('checked', 'checked');
                    $('#check-expert-payment-' + sender_id).attr('checked', 'checked');
                }
                if (result.feedback_given_by_buyer == 1) {
                    $('#buyer-feedback-' + sender_id).attr('checked', 'checked');
                    $('#check-expert-feedback-' + sender_id).attr('checked', 'checked');
                }
                if ($('.buyer-contract-options').length > 0 && !is_admin_panel_view) {
                    getBuyerContractStatus(result);
                }

                if ($('.expert-contract-options').length > 0  && !is_admin_panel_view) {
                    getExpertContractStatus(result);
                }


            }
        },
        error: function (error) {
            return false;
        }
    });
}

function getUserId() {
    $('section.inner-content').attr('id', 'message-' + $('.list-group a.active').attr('receiver-id'));
    $('input.receiver-id').val($('.list-group a.active').attr('receiver-id')).attr('id', $('.list-group a.active').attr('receiver-id'));
    $('input.communication-id').val($('.list-group a.active').attr('communication-id'));

    return $('.list-group a.active').attr('receiver-id');
}

function isConversationStarted() {
    return $('.list-group a.active').attr('start-conversation');
}

function getExpertContractStatus(result) {
    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered == 1 && result.contract_has_been_accepted_by_expert == 1 && (result.contract_has_been_marked_complete_by_buyer == 1 || result.contract_has_been_marked_complete_by_expert == 1) && result.feedback_given_by_buyer == 1) {
        return true;
    }

    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered !== 1) {
        $('#make-offer').show();
    }

    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered == 1 && result.contract_has_been_accepted_by_expert == 1 && (result.contract_has_been_marked_complete_by_buyer !== 1 && result.contract_has_been_marked_complete_by_expert !== 1)) {
        if(result.hasOwnProperty('packaged_ended') && result.packaged_ended != ''){
            $('#expert-project-completed-' + sender_id).hide();
        } else {
            $('#expert-project-completed-' + sender_id).show();
        }
    }
}



function getBuyerContractStatus(result) {
    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered == 1 && result.contract_has_been_accepted_by_expert == 1 && result.contract_has_been_marked_complete_by_buyer == 1 && result.feedback_given_by_buyer == 1) {
        return true;
    }
    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered == 1 && result.contract_has_been_accepted_by_expert == 1 && result.contract_has_been_marked_complete_by_buyer == 1 && result.feedback_given_by_buyer !== 1) {

        $('#buyer-feedback-to-expert-' + sender_id).show();
    }

    if (result.communication_initiated_by_buyer !== 1 && result.contract_has_been_offered !== 1 && result.contract_has_been_accepted_by_expert !== 1 && result.contract_has_been_marked_complete_by_buyer !== 1 && result.feedback_given_by_buyer !== 1) {
        $('#initiate_chat-message').show();
    }

    if (result.communication_initiated_by_buyer == 1 && result.contract_has_been_offered == 1 && result.contract_has_been_accepted_by_expert == 1 && (result.contract_has_been_marked_complete_by_buyer == '' || result.contract_has_been_marked_complete_by_buyer == 0)) {
        if(result.hasOwnProperty('packaged_ended') && result.packaged_ended != ''){
            $('#buyer-project-compelted-' + sender_id).hide();
            $('#buyer-extend-contract-' + sender_id).hide();
        } else {
            $('#buyer-project-compelted-' + sender_id).show();
        }
    }
}

function getUserType() {
    return $('.list-group a.active').attr('user-type');
}
function getContractId() {
    return $('#contract_id').val();
}
function getProjectId() {
    return $('#project_id').val();
}
function getProjectType() {
    return $('#project_type').val();
}
function getContractSubscriptionType() {
    return $("#contract_subscription").val();
}

function getCommunicationId() {
    return $('.list-group a.active').attr('communication-id');
}
function receiverName() {
    return $('.list-group a.active span.receiver-name').text();
}

function getFilePath(e) {
    filepath = e.target.files[0].name;
    if (filepath) {
        $('#file-upload').text('File Uploaded');
        $("#one-to-one-chatbox").focus();
        $('.send-btn').removeClass('btn btn-primary disabled');
        $('.send-btn').removeAttr('disabled');
        if($('.add-proposal-prompt-open').length > 0){
            $(document).find('.add-proposal-prompt-open').trigger('click');
        }
        return filepath;
    } else {
        $('.send-btn').addClass('btn btn-primary disabled');
        $('.send-btn').attr('disabled');
    }


}

function enableSendButton(event) {
    var text = $(event).val();
    if (text.length > 0) {
        $('.send-btn').removeClass('btn btn-primary disabled');
        $('.send-btn').removeAttr('disabled');
        if ($('.mobile-send-message-button').is(':visible')) {
            $('.mobile-send-message-button').removeAttr('disabled');
        }
        return true;
    } else {
        $('.send-btn').addClass('btn btn-primary disabled');
        $('.send-btn').attr('disabled', 'disabled');
        if ($('.mobile-send-message-button').is(':visible')) {
            $('.mobile-send-message-button').attr('disabled', 'disabled');
        }
        return false;
    }
}

function initiateConversation(event) {
    var user_id = $('#action-initiate-conversation').attr('userid');
    var data = {
        communication_id: getCommunicationId(), 
        buyer_id: user_id, 
        expert_id: getUserId(), 
        message_text: $('#initital_message').val()
    };
    $.ajax({
        url: base_url + '/users/' + user_id + '/initiateconversation',
        type: 'POST',
        beforeSend: function() {
            return typeof user_id !== 'undefined'
        },
        data: data,
        success: function (response) {
            if (response.success == 1) {
                $('#initiated-conversation').attr('checked', 'checked');
                $('#one-to-one-chatbox').removeAttr('disabled');
                $('#message-attachment-file').removeAttr('disabled');
                $(event).hide();
                $(".list-group a.active").attr("start-conversation","yes");
                $('#check_initital_message').val(1);
                $('body').find('#one-to-one-chatbox').val($('#initital_message').val());
                $('#send-message').submit();
                
            }
        }
    });
}

function makeOffer(event) {
    var user_id = $('#action-make-offer').data('user_id');
    var data = {communication_id: getCommunicationId(), buyer_id: user_id, expert_id: getUserId()};
    $.ajax({
        url: base_url + '/showmakeofferpopup',
        type: 'POST',
        data: data,
        beforeSend: function () {
            return typeof getUserId() !== 'undefined'
        },
        success: function (response) {
            $("#make_offer_stage_popups").html(response.content);
            $("#send_contract").modal("show");
        }
    });
}

$('body').on('click', '#stay_safe_confirm', function() {
    $('#terms-condition-error').text('');
});
$('body').on('click', '#terms_servcies_confirm_popup', function(event) {
     confirmTerms(event);
});

function confirmTerms(event) {
    if (($('#stay_safe_confirm').is(':checked')) &&
            ($('#code_of_conduct').is(':checked'))) {
        $('#terms-condition-error').text('');
        $.ajax({
            url: base_url + '/makeoffer/confirmterms',
            type: 'POST',
            data: {communication_id: getCommunicationId()},
            success: function (response) {
                if (response.success == 1) {
                    $("#staysafecontract").modal('hide');
                    showContractViewPopUp(getContractId());
                }
            }
        });
    } else {
        $('#terms-condition-error').text("Please accept the Terms of Services and Code of Conduct policies");
        return false;
    }
}

$(document).on('click', '.send-proposal-by-expert', function(){
    submitPreview();
});

function submitPreview(event) {
    
    var contract_data = new FormData($('#send_proposal_form')[0]);
    fixForFormdataNotSuportsEmptyFileSafariBug(contract_data);
    if (contract_ajax_flag) {
        $.ajax({
            url: $('#send_proposal_form').attr('action'),
            type: 'POST',
            data: contract_data,
            processData: false,
            contentType: false,
            beforeSend : function()    {
                contract_ajax_flag = false;
            },
            statusCode: {
                422: function (response) {
                    var result = jQuery.parseJSON(response.responseText);
                    $('.server-errors').removeClass('hide');
                    var server_errors = '';
                    for (var key in result) {
                        server_errors += '<div class="col-md-12 validation_error">' + result[key][0] + '</div>';
                    }
                    $('.server-errors').append(server_errors);
                }
            },
            success: function (response) {
                if (response.success == 1) {
                    var make_offer = {type: 'make_offer',
                        receiver: response.data.receiver_id,
                        communication_id: response.communication_id,
                        sender: response.data.sender_id,
                        text: response.data.msg,
                        buyerlink: response.data.buyer_link,
                        contract_id: response.contract_id,
                        project_id: response.posted_job_id,
                        project_name: response.posted_job_name,
                        project_type: response.project_type,
                        id: response.posted_job_id,
                        contract_start_date: response.contract_start_date,
                        contract_end_date: response.contract_end_date,
                        contract_rate: response.contract_rate,
                        accepted_status: response.accepted_status
                    };
                    socket.emit('sendmessage',make_offer);
                    window.location.href = base_url + '/expert/messages?communication_id='+response.communication_id+'&success=true';
                }
            }
        });
    }
}

function viewProjectByExpert() {
    var data = {communication_id: getCommunicationId(), buyer_id: getUserId(), expert_id: sender_id};
    $.ajax({
        url: base_url + '/expertcontractviewpopup/' + getContractId() + '?source=messages',
        type: 'POST',
        data: data,
        async: false,
        success: function (response) {
            if (response.success == 1) {
                setTimeout(function(){
                    $("#accept_contract_stage_popups").html("");
                    $(".modal-backdrop").remove();
                    $("#accept_contract_stage_popups").html(response.content);
                    $('#gotmatchpopup-' + getContractId()).modal("show");
                      $('[data-toggle="popover"]').popover({ trigger: "hover" });
                }, 10);
            }
        }
    });
}

function acceptContract() {
    $('#expert-action-accepted-offer-' + sender_id).attr('checked', 'checked');
    $('.expert-view-offer-option').hide();
    $('#expert-contract-preview').hide();
    $('div.modal-backdrop').hide();
    $('#expert-project-completed-' + sender_id).show();
    var accept_contract = {type: 'accept_offer', receiver: getUserId(), communication_id: getCommunicationId(), sender: sender_id,  project_type:getProjectType(), id:getProjectId()}
    socket.emit('sendmessage', accept_contract);
    /*make ajax call to save */
}

function buyerMarkedProjectAsCompleted() {
    showContractViewPopUp(getContractId());
    $('#check-buyer-project-compelted-' + sender_id).attr('checked', 'checked');
    $('#buyer-project-compelted-' + sender_id).hide();
    $('#buyer-extend-contract-' + sender_id).hide();
    $('#buyer-feedback-to-expert-' + sender_id).show();
    var buyer_marked_project_as_completed = {type: 'marked_complete_by_buyer', receiver: getUserId(), communication_id: getCommunicationId(), sender: sender_id,  project_type:getProjectType(), id:getProjectId()};
    socket.emit('sendmessage', buyer_marked_project_as_completed);
}

function expertMarkedProjectAsCompleted() {
    $('#check-expert-project-compelted-' + sender_id).attr('checked', 'checked');
    $('#expert-project-completed-' + sender_id).hide();
    var expert_marked_project_as_completed = {type: 'marked_by_expert', receiver: getUserId(), communication_id: getCommunicationId(), sender: sender_id,  project_type:getProjectType(), id:getProjectId()};
    socket.emit('sendmessage', expert_marked_project_as_completed);
}

function feedbackGivenByBuyer() {
    if ($("#latest_contract_id").val() != '' && $("#latest_contract_accepted").val()== 0) {
        return false;
    }
    $.ajax({
        url: base_url + '/users/' + sender_id + '/getfeedbackbybuyerpopup/expertid/' + getUserId(),
        type: 'POST',
        data: {communication_id: getCommunicationId(), buyer_id: sender_id, expert_id: getUserId()},
        success: function (response) {
            if (response.success == 1) {
                $("#make_offer_stage_popups").html("");
                $(".modal-backdrop").remove();
                $("#make_offer_stage_popups").html(response.content);
                $("#Buyer-give-feedback-" + getContractId()).modal("show");
            }
        }
    });
}

function getCommunicationId() {
    return $('.list-group a.active').attr('communication-id');
}

function getMessageCount(communicationid) {
    if (communicationid && typeof communicationid !== 'undefined') {
        $.ajax({
            url: base_url + '/users/' + communicationid + '/messages',
            type: 'GET',
            success: function (response) {
                if (response.success == 1) {
                    $('#message-count').val(response.data);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    }
}
function markAllMessagesRead(communication_id) {
    if (communication_id && typeof communication_id !== 'undefined') {
        $.ajax({
            type: 'GET',
            url: base_url + '/users/' + communication_id + '/markallmessagesread',
            data: {communication_id: communication_id, receiver_id: sender_id},
            success: function (response) {
                if (response.success == read) {
                    if(response.all_unread_messages == 0){
                        $('.project_notifications').html('');
                    }else{
                        $('.project_notifications').html('<span class="unread-count">'+response.all_unread_messages+'</span>');
                    }
                    $(".list-group a[communication-id*='" + communication_id + "']").find('#communication_id_'+communication_id).html("");
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            },
        });
    }
}
function scrolldown() {
    var $content = $('#chat-box');
    $content[0].scrollTop = $content[0].scrollHeight;
    $content.animate({scrollTop: 2600000}, "slow");
}
var addUserTypeLabel = function (name, type) {
    var label = (type=='sender') ? 'You' : name;
    if (is_admin_panel_view) {
        label = '(Expert) '+name;
        if (current_user_type == user_type_buyer && type == 'sender') {
            label = '(Buyer) '+name;
        }
    }
    return label;
}

function senderMessageTextPanel(name, content, buyer_accepting_proposal, date, time) {
    var buyer_accepting_proposal = buyer_accepting_proposal || false;
    var buyer_proposal_acceptance_class = (buyer_accepting_proposal == true) ? 'accepted-proposal-text' : '';
    return '<div class="message-sender-outer-container"><div class="message-sender-container chat-container">\n\
    <div class="message-sender pull-right"><div class="sender-chat-panel"><div class="message-head">\n\
    <h4 class="sender-name">'+addUserTypeLabel(name,'sender')+'</h4>\n\
    <div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
    <span class="message-time">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
    <p class="msg-text '+ buyer_proposal_acceptance_class +'">' + content + '</p></div></div></div></div>';
}
function senderMessageAttachmentPanel(name, content, file_name, date, time) {
    return '<div class="message-sender-outer-container"><div class="message-sender-container chat-container">\n\
    <div class="message-sender pull-right"><div class="sender-chat-panel"><div class="message-head">\n\
   <h4 class="sender-name">'+addUserTypeLabel(name,'sender')+'</h4>\n\
   <div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
    <span class="message-time">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
    <p class="msg-text"><a href="' + content + '" target="_blank">' + file_name + '</a></p></div></div></div></div>';
}
function senderMessageTextAndAttachmentPanel(name, text, file_path, file_name,date, time) {
    return '<div class="message-sender-outer-container"><div class="message-sender-container chat-container">\n\
    <div class="message-sender pull-right"><div class="sender-chat-panel"><div class="message-head">\n\
    <h4 class="sender-name">'+addUserTypeLabel(name,'sender')+'</h4><div class="message-timing">\n\
    <span class="date_time">' + messageDate(date) + '</span><span class="message-time">' + messageTime(time) + '</span></div></div>\n\
    <p class="msg-text"><div class="clearfix"></div><p class="msg-text">' + text + '</p>\n\
    <a href="' + file_path + '" target="_blank">' + file_name + '</a></p></div></div></div></div>';
}

function receiverMessageTextPanel(name, content, buyer_accepting_proposal, date, time) {
    var buyer_accepting_proposal = buyer_accepting_proposal || false;
    var buyer_proposal_acceptance_class = ''
    if (buyer_accepting_proposal == true){
        buyer_proposal_acceptance_class = 'accepted-proposal-text';
    }
    return '<div class="message-receiver-outer-container"><div class="message-receiver-container chat-container">\n\
    <div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
    <h4 class="receiver-name">'+addUserTypeLabel(name) + '</h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
    <span class="message-time">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
    <p class="msg-text '+ buyer_proposal_acceptance_class +'">' + content + '</p></div></div></div></div>';
}

function receiverMessageAttachmentPanel( name, content, file_name, date, time) {
    return '<div class="message-receiver-outer-container"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name">'+addUserTypeLabel(name)+ '</h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
<span class="message-time">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text"><a href="' + content + '" target="_blank">' + file_name + '</a></p></div></div></div></div>';
}
function receiverMessageTextAndAttachmentPanel( name, text, file_path, file_name ,date, time) {
    return '<div class="message-receiver-outer-container"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name">'+addUserTypeLabel(name) + '</h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
<span class="message-time">' + messageTime(time) + '</span></div></div><div class="clearfix"></div><p class="msg-text">\n\
<p class="msg-text">' + text + '</p><a href="' + file_path + '" target="_blank">' + file_name + '</a></p></div></div></div></div>';
}

function receiverAutomatedMessageFromMmTextPanel(value, message_time) {
    var buyer_link_to_show = '';
    if(value.buyer_link != '' && value.buyer_link != null){
        buyer_link_to_show = value.buyer_link;
    }
    return $('.existing-chat').prepend('<div class="message-receiver-outer-container auto-message-panel"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name mm-message-logo">MeasureBot </h4><div class="message-timing"><span class="date_time">' + value.show_date + '</span>\n\
<span class="message-time font-14">' + message_time + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text">' + value.msg + ' ' + buyer_link_to_show + '</p></div></div></div></div>');
}

function expertContractAcceptanceAutomatedMessageFromMmTextPanel(message, message_time, message_date, contract_start_date, contract_end_date, rate, user_id) {
    var project = (getProjectType() != 'project') ? 'Package' : 'Project';
    var button = '<a href = "javascript:void(0)" class = "send-contract send-contract font-14 white-bg white-bg-btn margin-bottom-10 \n\
        full-width-btn" title = "View Contract">View Contract</a>';
    if ($('body').hasClass('mobile-messaging-view')) {
        var button = '<div class="login-dexktop-info">Please login to a desktop environment to view the contract</div>'
    }
    return '<div class="message-receiver-outer-container auto-message-panel"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name mm-message-logo">MeasureBot </h4><div class="message-timing"><span class="date_time">' + message_date + '</span>\n\
<span class="message-time font-14">' + message_time + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text">' + message + '</p><div class="chat-vewproposal-body">\n\
    <div class="box-style"><p class="gilroyregular-semibold">'+project+' start date:</p><p class="project_start_date">'
            + contract_start_date + '</p></div><div class="box-style"><p class="gilroyregular-semibold">Estimated completion date:</p>\n\
    <p class="estimated_completion_date">' + contract_end_date +
            '</p></div><div class="box-style"><p class="gilroyregular-semibold">Value of contract:</p><p class="proposal_value">'
            + rate + '</p></div></div><div onclick="viewProjectByExpert(this);" class="contract-process-btn-panel view-proposal-' + user_id + '" >\n\
        '+button+'</div></div></div></div></div>';
}

function buyerContractAcceptanceAutomatedMessageFromMmTextPanel(message, message_time, message_date, contract_start_date, contract_end_date, rate, user_id) {
    var project = (getProjectType() != 'project') ? 'Package' : 'Project';
    var button = '<a href = "javascript:void(0)" class = "send-contract send-contract font-14 white-bg white-bg-btn margin-bottom-10 \n\
            full-width-btn" title = "View Contract">View Contract</a>';
    if ($('body').hasClass('mobile-messaging-view')) {
        var button = '<div class="login-dexktop-info">Please login to a desktop environment to view the contract</div>'
    }
    return '<div class="message-receiver-outer-container auto-message-panel"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name mm-message-logo">MeasureBot </h4><div class="message-timing"><span class="date_time">' + message_date + '</span>\n\
<span class="message-time font-14">' + message_time + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text">' + message + '</p><div class="chat-vewproposal-body">\n\
    <div class="box-style"><p class="gilroyregular-semibold">'+project+' start date:</p><p class="project_start_date">'
            + contract_start_date + '</p></div><div class="box-style"><p class="gilroyregular-semibold">Estimated completion date:</p>\n\
    <p class="estimated_completion_date">' + contract_end_date +
            '</p></div><div class="box-style"><p class="gilroyregular-semibold">Value of contract:</p><p class="proposal_value">'
            + rate + '</p></div></div><div onclick="viewOffer(this);" class="contract-process-btn-panel view-proposal-' + user_id + '" >\n\
        '+button+'</div></div></div></div></div>';
}

function receiverViewProposalBlock(name, contract_start_date, contract_end_date, rate, user_id,latest_contract_accepted, date, time){
    var button = '<a href = "javascript:void(0)" class = "send-contract send-contract font-14 white-bg white-bg-btn margin-bottom-10 \n\
            full-width-btn" title = "View Contract">View Contract</a>';
    var contract_proposal = 'contract';
    if (latest_contract_accepted == 0) {
        var button = '<a href = "javascript:void(0)" class = "send-contract btn standard-btn gilroyregular-semibold margin-bottom-15\n\
            full-width-btn" title = "View Proposal">View Proposal</a>';
        var contract_proposal = 'proposal';
    }
    if ($('body').hasClass('mobile-messaging-view')) {
        var button = '<div class="login-dexktop-info">Please login to a desktop environment to view the ' + contract_proposal + '</div>'
    }
    var project = (getProjectType() != 'project') ? 'Package' : 'Project';
    var subscription_type = $(document).find('#contract_subscription').val();   
    var rate = (subscription_type == 'monthly_retainer') ? rate +"/month" : rate;
    return '<div class = "message-receiver-outer-container"><div class = "message-receiver-container chat-container">\n\
    <div class = "message-receiver pull-left"><div class = "receiver-chat-panel chat-vewproposal"><div class = "message-head">\n\
    <h4 class = "receiver-name">'+addUserTypeLabel(name) + '</h4><div class = "message-timing"><span class = "date_time">' + messageDate(date) + '</span>\n\
    <span class = "message-time">' + messageTime(time) + '</span></div></div><div class = "chat-vewproposal-body">\n\
    <div class = "box-style"><p class = "gilroyregular-semibold">'+project+' start date:</p><p class = "project_start_date">'
            +contract_start_date+'</p></div><div class = "box-style"><p class = "gilroyregular-semibold">Estimated completion date:</p>\n\
    <p class = "estimated_completion_date">' + contract_end_date+
            '</p></div><div class = "box-style"><p class = "gilroyregular-semibold">Value of proposal:</p><p class = "proposal_value">'
            +rate+'</p></div></div><div onclick = "viewOffer(this);" class = "contract-process-btn-panel view-edit-offer-details-' + user_id + '">\n\
        '+button+' </div></div></div></div></div>';
}
function senderViewProposalBlock(name, contract_start_date, contract_end_date, rate, user_id, latest_contract_accepted_status, date, time) {
    var button = '<a href="javascript:void(0)" userid="' + user_id + '" usertype="1" \n\
    class="send-contract white-bg white-bg-btn gilroyregular-semibold  margin-bottom-15 full-width-btn" title="View Contract" \n\
    data-toggle="modal" data-target="#expert-contract-preview">View Contract</a>';
    if (latest_contract_accepted_status == 0) {
        var button = '<a href="javascript:void(0)" userid="' + user_id + '" usertype="1" \n\
    class="send-contract btn standard-btn gilroyregular-semibold  margin-bottom-15 full-width-btn" title="View/Edit Proposal" \n\
    data-toggle="modal" data-target="#expert-contract-preview">View/Edit Proposal</a>';
    }
    
    if ($('body').hasClass('mobile-messaging-view')) {
        var button = '<div class="login-dexktop-info">Please login to a desktop environment to view and/or edit your proposal</div>'
    }
    
    var project= (getProjectType()!='project') ? 'Package' : 'Project';
    var subscription_type=$(document).find('#contract_subscription').val();   
    var rate = (subscription_type == 'monthly_retainer') ? rate +"/month" : rate;
    return'<div class="message-sender-outer-container"><div class="message-sender-container chat-container">\n\
    <div class="message-sender pull-right"><div class="sender-chat-panel chat-vewproposal"><div class="message-head">\n\
   <h4 class="sender-name">' + addUserTypeLabel(name) + '</h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
    <span class="message-time">' + messageTime(time) + '</span></div></div><div class="chat-vewproposal-body">\n\
    <div class="box-style"><p class="gilroyregular-semibold">'+project+' start date:</p><p class="project_start_date">'
            + contract_start_date + '</p></div><div class="box-style"><p class="gilroyregular-semibold">Estimated completion date:</p>\n\
    <p class="estimated_completion_date">' + contract_end_date +
            '</p></div><div class="box-style"><p class="gilroyregular-semibold">Value of proposal:</p><p class="proposal_value">'
            + rate + '</p></div></div><div onclick="viewProjectByExpert(this);" class="contract-process-btn-panel view-proposal-' + user_id + '" >\n\
        '+button+'</div>\n\
</div></div></div></div>';
}

function buyerRebookProjectBlock(message_time, date, title, description, buyer_id) {    
    return '<div class = "message-sender-outer-container"><div class = "message-sender-container chat-container">\n\
    <div class = "message-sender pull-right"><div class = "sender-chat-panel chat-vewproposal"><div class = "message-head">\n\
   <h4 class = "sender-name">' + addUserTypeLabel('','sender') + '</h4><div class = "message-timing"><span class = "date_time">' + messageDate(date) + '</span>\n\
    <span class = "message-time">' + messageTime(message_time) + '</span></div></div><div class = "chat-vewproposal-body">\n\
    <div class = "box-style"><p class = "gilroyregular-semibold">Project title</p><p>' + title + '</p></div>\n\
    <div class = "box-style full-w"><p class = "gilroyregular-semibold">Project description</p><p>' + description + '\n\
    </p></div></div><div class = "contract-process-btn-panel" ><a href = "javascript:void(0)" class = "project-details-popup-button standard-btn btn gilroyregular-semibold\n\
     margin-bottom-15" title = "View Full Project" data-buyer-id="' + buyer_id + '">View Full Project</a></div>\n\
    </div></div></div></div>';
}

function expertRebookProjectBlock(message_time, date, title, description, buyer_id) {
    return '<div class = "message-receiver-outer-container"><div class = "message-receiver-container chat-container">\n\
    <div class = "message-receiver pull-right"><div class = "receiver-chat-panel chat-vewproposal"><div class = "message-head">\n\
   <h4 class = "receiver-name">' + addUserTypeLabel(name) + '</h4><div class = "message-timing"><span class = "date_time">' + messageDate(date) + '</span>\n\
    <span class = "message-time">' + messageTime(message_time) + '</span></div></div><div class = "chat-vewproposal-body">\n\
    <div class = "box-style"><p class = "gilroyregular-semibold">Project title</p><p>' + title + '</p></div>\n\
    <div class = "box-style full-w"><p class = "gilroyregular-semibold">Project description</p><p>' + description + '\n\
    </p></div></div><div class = "contract-process-btn-panel" ><a href = "javascript:void(0)" \n\
    class = "project-details-popup-button standard-btn btn gilroyregular-semibold margin-bottom-15" title = "View Full Project" \n\
    data-buyer-id="' + buyer_id + '">View Full Project</a></div>\n\
    </div></div></div></div>';
}

function automatedMessageFromMmTextPanel(content, date, time) {
    return $('.existing-chat').prepend('<div class="message-receiver-outer-container auto-message-panel"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name mm-message-logo">MeasureBot </h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
<span class="message-time font-14">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text">' + content+'</p></div></div></div></div>');
}
function automatedMessageFromMeasureBotHtml(content, date, time) {
    return '<div class="message-receiver-outer-container auto-message-panel"><div class="message-receiver-container chat-container">\n\
<div class="message-receiver pull-left"><div class="receiver-chat-panel"><div class="message-head">\n\
<h4 class="receiver-name mm-message-logo">MeasureBot </h4><div class="message-timing"><span class="date_time">' + messageDate(date) + '</span>\n\
<span class="message-time font-14">' + messageTime(time) + '</span></div></div><div class="clearfix"></div>\n\
<p class="msg-text">' + content+'</p></div></div></div></div>';
}

function getTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var currenttime = hours + ':' + minutes + ' ' + ampm;
    return currenttime;
}
function viewOffer() {
    showContractViewPopUp(getContractId());
}
/**** OLD CODE***********/

function getDateFormatInString(date) {
    return date.getDate() + " " + months[date.getMonth()] + ", " + date.getFullYear();
}

function applyPromotionalCoupon(redeemCouponValue, contract_id, rate) {
    $.ajax({
        type: 'post',
        url: base_url + '/previewBuyerPromoCode',
        data: {'coupon': redeemCouponValue, 'contract_id': contract_id, 'rate': rate},
        async: false,
        success: function (response) {
            if (response.status == 0) {
                $('.validate_expert_error').html(response.message).fadeIn('slow').delay(4000).fadeOut();
            } else if (response.status == 1) {
                $('.validate_expert_error').html(response.message).fadeIn('slow').delay(4000).fadeOut();

                $('#previewCouponContract').modal('hide');
                $('#preview_before_make_offer').modal('show');
                $("#preview_what_you_will_get").text("$" + (parseFloat(Math.round(parseInt(rate)) - response.amount)).toFixed(2));
                $('.coupon_applied_daily_rate_parent').show();
                $('#preview_discount_coupon_button').hide();
                $('#coupon_code_applied').val(redeemCouponValue);

            } else if (response.status == 2) {

                $('.validate_expert_error').html(response.message).fadeIn('slow').delay(4000).fadeOut();
                $('.redeemPoints').hide();
                $('#mm_fee-' + contract_id).html("-" + response.mm_fee);
                $('.coupon_applied_daily_rate_parent').html('<span class="coupon_applied_daily_rate">Promo code:<span id="mm_fee"> -$100 </span><span class="remove_discount_coupon" data-contract_id="' + contract_id + '" data-rate="' + rate + '">discount code (<span>remove</span>) </span> </span>');
                $('.coupon_applied_daily_rate_parent').show();
                $('#what_you_will_get-' + contract_id).html(response.what_you_will_get);
                $('.coupon_popup_back').click();
                $('.coupon-code-block').hide();
                $('#discount_applied').val("1");
                $('.mark_completed_project .modal-header .close').trigger('click');
                $('.apply-coupon-btn').hide();
                $('#update_coupon_code').val(1);
                $('#view_contract-' + contract_id).modal('show');

                return false;
            }

        }
    });
}

function showContractPreviewPopUp() {
    var preview_company_name = $("#buyer_company_name").text();
    var preview_start_date = $("#start_time").val();
    var preview_end_date = $("#end_time").val();
    var rate = $("#rate").val();
    var deliverable = $("#deliverable").val().trim();
    var rate_variable_symbol = $("#rate_variable_symbol").val().trim();
    
    $("#preview_company_name").text( preview_company_name);
    $("#preview_start_date").text(moment(preview_start_date, 'DD-MM-YYYY').format('DD MMM YYYY'));
    $("#preview_end_date").text(moment(preview_end_date, 'DD-MM-YYYY').format('DD MMM YYYY'));
    $("#preview_deliverable").text(deliverable);
    if ($("#uploadFile").text() == "") {
        $("#preview_attachment").html('<p class="no_attachment_block font-14">No documents attached</p>');
    } else {
        $("#preview_attachment").html($("#uploadFile").text());
    }
    $("#preview_project_price").text(rate_variable_symbol + rate);
    $('#send_contract').modal('hide');
    $('#preview_before_make_offer').modal('show');
    return false;
}
function getEditContractPopup(contract_id){
    if (getProjectType() == 'project') {
        var view_edit_popup = base_url + '/getcontracteditpopup/' + contract_id;
    } else {
        var view_edit_popup = base_url + '/servicepackageeditpoup/' + contract_id;
    }
    $.ajax({
        type: 'get',
        url: view_edit_popup,
        success: function (response) {
            if (response != 0) {
                $('#view_contract_preview').modal('hide');
                $('.close').trigger('click');
                $("#make_offer_stage_popups, #view_contract_preview, #edit_contract").html("");
                $('#edit_contract').html(response).modal('show');
                contract_ajax_flag = true;
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}
function scrollToErrorInPopup(selector){
    $('.modal').animate({
        scrollTop: ($(selector).offset().top - 10)
    }, 400);
}
function getMarkAsCompleteConfirmExpertPopup(contractid){
    $.ajax({
        type: 'get',
        url: base_url + '/getmarkascompleteconfirmexpertpopup/' + contractid,
        success: function (response) {
            if (response != 0) {
                $("#accept_contract_stage_popups").html("");
                $(".modal-backdrop").remove();
                $("#accept_contract_stage_popups").html(response.content);
                $('#mark_contract_complete_confirmation_pop_up').modal("show");
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}
$(function () {
    $(document).on('click', '#contract_preview', function (e) {
        var numbers = /^[0-9]+$/;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var job_post = $("#job_post").val();
        var rate_variable = $("#rate_variable_symbol").val();
        var rate = $("#rate").val().trim();
        var deliverable = $("#deliverable").val().trim();
        var coupon_code_applied = $('#coupon_code_applied').val();
        var error_count = 0;
        $('.has_error').show();
        if (job_post == 0 || job_post == "" || job_post == "undefined") {
            $("#project_name_error").text("Please select project").addClass('has_error');
            error_count++;
        } else {
            $("#project_name_error").text("").removeClass('has_error');
        }
        if (start_time == "") {
            $("#start_time_error").text("Please enter proposal start date").addClass('has_error');
            error_count++;
        } else {
            $("#start_time_error").text("").removeClass('has_error');
        }
        if (end_time == "") {
            $("#end_time_error").text("Please enter proposal end date").addClass('has_error');
            error_count++;
        } else {
            $("#end_time_error").text("").removeClass('has_error');
        }
      
        if (rate == "") {
            $("#contract_price_error").text("Please enter proposal value").addClass('has_error');
            error_count++;
        }  else if (rate < 200) {
            $("#contract_price_error").text("Proposal cannot be made if it is under the value of "+rate_variable+"200").addClass('has_error');
            error_count++;
        } else if (rate < 1000 && coupon_code_applied != '') {
            $('#coupon_code_applied').val("");
            $('.remove_discount_coupon').click();
        } else {
            $("#contract_price_error").text("").removeClass('has_error');
        }

        if (deliverable == "") {
            $("#contract_deliverables_error").text("Please enter your deliverables").addClass('has_error');
            error_count++;
        } else {
            $("#contract_deliverables_error").text("").removeClass('has_error');
        }
        if (error_count > 0) {
            scrollToErrorInPopup('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(4000).fadeOut('fast');
            return false;
        } else {

            if (start_time != '' && end_time != '' && rate_variable != '' && rate != '' && deliverable != '') {
                e.preventDefault();
                contract_ajax_flag = true;
                if (getProjectType() == 'project') {
                    showContractPreviewPopUp();
                } else {
                    submitPreview();
                }

            }
        }
    });
    $(document).on('change', '#edit_contract_information :input', function (e) {
        e.preventDefault();
        $("#edit_contract_information").data("changed",true);
    });
    $(document).on('click', '#edit_contract_before_make_offer', function (e) {
        $('#preview_before_make_offer').modal('hide');
        $("#send_contract").modal('show');
    });

    $(document).on('click', '.apply-coupon-btn ', function (e) {
        $("#redeemCouponValue-preview").val("");
    });
      $(document).on("focus touch",'#rate', function (event) {
        $(this).parent().addClass("highlighted-price");
    });
     $(document).on("blur",'#rate', function (event) {
        $(this).parent().removeClass("highlighted-price");
    });
    $(document).on('click', '.redeemSubmit', function (e) {
        var rate = $(this).attr('data-rate');
        var contract_id = $(this).data('contract_id');
        var redeemCouponValue = $("#redeemCouponValue-" + contract_id).val().trim();
        if (redeemCouponValue == '') {
            $('.validate_expert_error').html('Please add coupon code.').fadeIn('slow').delay(4000).fadeOut();
            return false;
        } else if (redeemCouponValue) {
            var check_unique_coupon_url = base_url + '/checkcouponuniqueness';
            var coupon_can_be_applied = false;
            $.ajax({
                type: 'get',
                url: check_unique_coupon_url,
                success: function (response) {
                    if (response.status == 3) {
                        var remove_coupon = confirm(response.message);
                        if (remove_coupon == true) {
                            $.ajax({
                                type: 'post',
                                url: base_url + '/removeBuyerPromoCode',
                                data: {'contract_id': response.already_applied_contract_id},
                                async: false,
                                success: function (response) {
                                    if (response == 1) {
                                        applyPromotionalCoupon(redeemCouponValue, contract_id, rate);
                                    } else {
                                        $(".validation_error7").text("Coupon code could not be removed!!");
                                        return false;
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    } else {
                        applyPromotionalCoupon(redeemCouponValue, contract_id, rate);
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
        } else {
            $('.validate_expert_error').html('Please add valid coupon code.').fadeIn('slow').delay(4000).fadeOut();
        }
    });

    $(document).on('click', '#apply_coupon', function () {
        var contractid = $(this).attr('contract_id');
        $.ajax({
            type: 'get',
            url: base_url + '/getapplycouponpopup/' + contractid,
            success: function (response) {
                if (response != 0) {
                    $('#view_contract_preview').modal('hide');
                    $('#apply_coupon_pop_up').html(response).modal('show');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });

    $(document).on('click', '.coupon_popup_back', function () {
        var contractid = $(this).attr('contract_id');
        viewOffer(contractid);
    });

    $(document).on('click', '#buyer_mark_contract_complete_confirmation_button', function () {
        var contractid = $(this).attr('current_contract_id');
        $.ajax({
            type: 'get',
            url: base_url + '/getmarkascompleteconfirmpopup/' + contractid,
            success: function (response) {
                if (response != 0) {
                    $('#view_contract_preview').modal('hide');
                    $('#mark_as_complete_confirm').html(response).modal('show');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });

    $(document).on('click', '#mark_contract_complete_confirmation_button', function () {
        var contractid = $(this).attr('contract_id');
        getMarkAsCompleteConfirmExpertPopup(contractid)
    });

    $(document).on('click', '#contract_preview_edit', function (e) {
        var contractid = $(this).attr('contract_id');
        getEditContractPopup(contractid);
    });

    $(document).on('click', '#contract_preview_update', function (e) {
        e.preventDefault();
        var update_coupon_code = $('#update_coupon_code').val();
        var job_post = $('#edit_contract select#job_post').val();
        var project_start_date = $('#edit_contract #start_time').val();
        var project_end_date = $('#edit_contract #end_time').val();
        var project_price = $('#edit_contract #project_price').val();
        var project_deliverable = $('#edit_contract #project_deliverable').val();
        var contract_id = $('#contract_id').val();
        var payment_mode = $("input[name='payment_mode']:radio:checked");
        emptyValidationErrors();
        var error_count = 0;
        if (job_post == 0 || job_post == "" || job_post == "undefined") {
            $(".validation_posted_project_id").text("Please select project").show().fadeOut(fadeout_time_limit);
            error_count++;
        }
        if (project_start_date == "") {
            $(".validation_start_time").text("Please enter project start date").show().fadeOut(fadeout_time_limit);
            error_count++;
        }
        if (project_end_date == "") {
            $(".validation_end_time").text("Please enter project end date").show().fadeOut(fadeout_time_limit);
            error_count++;
        }
        if (project_price == "") {
            $(".validation_project_price").text("Please enter proposal value").show().fadeOut(fadeout_time_limit);
            error_count++;
        } else if (project_price < 200) {
            $(".validation_project_price").text("Proposal cannot be made if it is under the value of "+$(document).find('#currencty_symbol').text()+"200").show().fadeOut(fadeout_time_limit);
            error_count++;
        } else if (project_price < 1000 && update_coupon_code == '1') {
            removeBuyerPromoCode(contract_id, project_price)
        }
        if (project_deliverable == "")
        {
            $(".validation_project_deliverable").text("Please enter deliverable").show().fadeOut(fadeout_time_limit);
            error_count++;
        }
        if (error_count > 0) {
            return false;
        } else {
            if (project_start_date != '' && project_end_date != '' && project_price != '' && job_post != '' && project_deliverable != '') {
                editContract();

            }}
    });

    $(document).on('click', '.mark-as-complete', function (e) {
        var receiver_id = getUserId();
        var contract_type = getProjectType();
        var communications_id = $(this).attr('communications_id');
        var payment_mode = $(this).attr('payment_mode');
        $('.mark-as-complete').css('pointer-events', 'none');
        $.ajax({
            type: 'post',
            url: base_url + '/markcontractascomplete',
            data: {contract_id: getContractId(),
                receiver_id: receiver_id,
                communications_id: getCommunicationId(),
                payment_mode: payment_mode,
                contract_type:contract_type},
            success: function (result) {
                if (result.success != 0) {
                    $('#check-buyer-project-compelted-' + sender_id).attr('checked', 'checked');
                    $('#buyer-project-compelted-' + sender_id).hide();
                    $('#buyer-feedback-to-expert-' + sender_id).show();
                    $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                        result.data.message_to_expert.msg + ' '
                        + result.data.message_to_expert.buyer_link));
                    $('.existing-chat').append(receiverMessageTextPanel( receiverName() ,
                        result.data.message_to_buyer.msg + ' '
                        + result.data.message_to_buyer.buyer_link));
                    var buyer_marked_project_as_completed = {type: 'marked_by_buyer',
                        receiver: getUserId(),
                        communication_id: getCommunicationId(),
                        sender: sender_id,
                        sendername: sender_name,
                        text: result.data.message_to_expert.msg,
                        expertlink: result.data.message_to_expert.expert_link};
                    socket.emit('sendmessage', buyer_marked_project_as_completed);
                    var buyer_marked_project_as_completed_feedback_request = {
                        type: 'marked_by_buyer_feedback_request',
                        receiver: getUserId(),
                        communication_id: getCommunicationId(),
                        sender: sender_id,
                        sendername: receiverName(),
                        text: result.data.message_to_buyer.msg,
                        expertlink: result.data.message_to_buyer.expert_link,
                        project_type:getProjectType(),
                        id:getProjectId()};
                    socket.emit('sendmessage', buyer_marked_project_as_completed_feedback_request);
                    if(contract_type=="service_package"){
                        feedbackGivenByBuyer()
                    }
                } else {
                    console.log('Failure!! Contract could not be completed.');
                }
                $('.close').trigger('click');
                $("div.modal-backdrop").remove();
                scrolldown();
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $(document).on('submit', '#buyer_feedback', function (e) {
        e.preventDefault();
        var rating = $('#rating').val();
        var feedback_comment = $('#feedback_comment').val();
        var project_id = getProjectId();
        if (rating == 0)
        {
            $(".validation_error7").text("Please select rating.");
            $('.validation_error7').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (feedback_comment == "")
        {
            $(".validation_error8").text("Please add your feedback");
            $('.validation_error8').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (rating != '' && feedback_comment != '') {
            $.ajax({
                type: 'post',
                url: base_url + '/buyerfeedbacktoexpert',
                data: {contract_id: getContractId(),
                    receiver_id: getUserId(),
                    project_id: project_id,
                    communication_id: getCommunicationId(),
                    sender_id: sender_id,
                    rating: rating,
                    feedback_comment: feedback_comment},
                success: function (result) {
                    if (result.success) {
                        $('#buyer-feedback-' + sender_id).attr('checked', 'checked');
                        $('#buyer-feedback-to-expert-' + sender_id).hide();
                        $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                            result.data.msg + ' ' + result.data.buyer_link));
                        var buyer_feedback = {type: 'buyer_feedback',
                            receiver: getUserId(),
                            communication_id: getCommunicationId(),
                            sender: sender_id,
                            sendername: sender_name,
                            text: result.data.msg,
                            expertlink: result.data.expert_link,
                            project_type:getProjectType(),
                            id:project_id};
                        socket.emit('sendmessage', buyer_feedback);
                        $('.close').trigger('click');
                        scrolldown();
                        if(getProjectType()=="service_package"){
                            feedbackGivenByBuyer();
                        }
                    }
                }
            });
        }

    });
    $(document).on('click', '.accept-contract-btn', function (e) {
        var contract_id = $(this).attr('id');
        
        var comm_id = $(this).attr('data-commid');
        var receiver_id = $(this).attr('data-receiver');
        var sender_id = $(this).attr('data-sender');
        var endDate = $(this).attr('data-contract-enddate');
        var rate = $(this).attr('data-contract-rate');
        var Contract_Job_title = $('#Contract_Job_title').text();
        var discount_applied = $('#discount_applied').val();
        var contract_confirm = $(this).attr('contract_confirm');
        var project_id = $(this).attr('project_id');
        var payment_mode = $(this).attr('payment_mode');
        var contract_type = $(this).attr('data-contract_type');
        if (discount_applied == '' && contract_confirm == '0') {
            $('.mark_completed_project').modal('hide');
            $('#discount_confirm_box').modal('show');
            return false;
        }
        
        $('.accept-contract-btn').css('pointer-events', 'none');
        $.ajax({
            type: 'post',
            url: base_url + '/acceptcontractbybuyer',
            data: {contract_id: contract_id,
                payment_mode: payment_mode,
                comm_id: comm_id,
                endDate: endDate,
                rate: rate,
                expert_id: receiver_id,
                buyer_id: sender_id,
                project_id: project_id,
                contract_job_title: Contract_Job_title,
                contract_type:contract_type},
            success: function (result) {
                if (result.success) {
                    $(document).find('.view-edit-offer-details-' + sender_id + ' .send-contract').removeClass('btn standard-btn').addClass('white-bg white-bg-btn').text('View Contract');
                    $('#buyer-accepted-offer-' + sender_id).prop('checked', 'checked');
                    var contract_id_attribute = $('#buyer-project-compelted-' + sender_id).attr('current_contract_id');
                    if (typeof contract_id_attribute === typeof undefined || contract_id_attribute === false) {
                        $('#buyer-project-compelted-' + sender_id + ' a').attr('current_contract_id', contract_id);
                    }
                    $('#buyer-project-compelted-' + sender_id).show();
                    var expert_automated_msg = (result.data.message_to_both != '') ? result.data.message_to_both.msg : result.data.message_to_expert.msg;
                    var acceptance_message_to_expert = (result.data.acceptance_message_to_expert != '') ? result.data.acceptance_message_to_expert.msg : '';
                    var accept_contract = {type: 'accept_offer',
                        receiver: receiver_id,
                        communication_id: getCommunicationId(),
                        sender: sender_id,
                        message_to_expert: expert_automated_msg,
                        acceptance_message_to_expert: acceptance_message_to_expert,
                        contract_id: contract_id,
                        contract_starts_in_future: result.data.contract_starts_in_future,
                        contract_start_date: result.data.latest_contract.job_start_date,
                        contract_end_date: result.data.latest_contract.job_end_date,
                        contract_rate: result.data.latest_contract.rate,
                        is_extended: result.data.is_extended,
                        project_type:getProjectType(),
                        id:getProjectId()
                    };                    
                    socket.emit('sendmessage', accept_contract);
                    
                    if(result.data.acceptance_message_to_buyer != ''){
                        $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                        result.data.acceptance_message_to_buyer.msg, true));
                    }
                        
                    var buyer_automated_msg = (result.data.message_to_both != '') ? result.data.message_to_both.msg : result.data.message_to_buyer.msg;
                    $('#message-' + receiver_id + ' .existing-chat').append(
                            buyerContractAcceptanceAutomatedMessageFromMmTextPanel(
                                        buyer_automated_msg,
                                        messageTime(),
                                        messageDate(),
                                        result.data.latest_contract.job_start_date,
                                        result.data.latest_contract.job_end_date,
                                        result.data.latest_contract.rate,
                                        receiver_id
                                ));
                }
                $('.list-group .conversation a.active').after(
                    '<span class="rebooking-section">\n\
                        <span class="rebooking-content-inner">\n\
                            <span class="rebooking-content">\n\
                                Want to work with ' + $('.list-group .conversation a.active .receiver-name').text().split(' ').splice(0, 1) + ' on a different project?\n\
                            </span>\n\
                            <span class="rebooking-button">\n\
                                <a class="book-again-btn">\n\
                                    Book ' + $('.list-group .conversation a.active .receiver-name').text().split(' ').splice(0, 1) + ' again\n\
                                </a>\n\
                            </span>\n\
                        </span>\n\
                    </span>'
                );
                $('.close').trigger('click');
                $("div.modal-backdrop").remove();
                scrolldown();
            }
        });
    });
    $(document).on('click', '#apply_coupon_by_expert', function (e) {
        var redeem_coupon_value = $('#redeemCouponValue').val();
        var contract_id = $('#contract_id').val();
        if (redeem_coupon_value == '') {
            $('.validate_expert_error').html('Please add coupon code.').fadeIn('slow').delay(2000).fadeOut();
            return false;
        } else if (redeem_coupon_value) {
            $.ajax({
                type: 'post',
                url: base_url + '/applycouponbyexpert',
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
    $(document).on('click', '.expert-mark-as-complete', function (e) {
        var comm_id = $(this).attr('id');
        var receiver_id = $(this).attr('user_id');
        var sender_id = $(this).attr('sender_id');
        var communications_id = $(this).attr('communications_id');
        $('.expert-mark-as-complete').css('pointer-events', 'none');
        $.ajax({
            type: 'post',
            beforeSend: function() {
                return typeof getUserId() !== 'undefined'
            },
            url: base_url + '/expertmarkcontractcomplete',
            data: {contract_id: getContractId(), receiver_id: getUserId(),
                communications_id: communications_id},
            success: function (result) {
                if (result.success) {
                    $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                        result.data.msg + ' ' + result.data.buyer_link ));
                    var marked_by_expert = {type: 'marked_by_expert',
                        receiver: getUserId(),
                        communication_id: getCommunicationId(),
                        sender: sender_id,
                        sendername: sender_name,
                        text: result.data.msg,
                        expertlink: result.data.expert_link,
                        project_type:getProjectType(),
                        id:getProjectId()};
                    socket.emit('sendmessage', marked_by_expert);
                    $('#check-expert-project-compelted-' + sender_id).prop('checked', 'checked');
                    $('#expert-project-completed-' + sender_id).hide();
                    $('.close').trigger('click');
                    $("div.modal-backdrop").remove();
                    $('.expert-mark-as-complete').css('pointer-events', 'auto');
                    scrolldown();
                }

            }
        });
    });
    $(document).on('click', '.remove_discount_coupon', function (e) {
        var contract_id = $(this).attr("data-contract_id");
        var rate = $(this).attr('data-rate').trim();
        if (contract_id == 'preview') {
            $('.preview_discount_hide').show();
            $('.preview_discount_show').hide();
            $("#preview_mm_fee").text("-$" + (parseFloat(Math.round(parseInt(rate) * 0.15) * 100) / 100).toFixed(2));
            $("#preview_what_you_will_get").text("$" + (parseFloat(Math.round(parseInt(rate)))).toFixed(2));
            $('#coupon_code_applied').val("");
            $('#preview_discount_coupon_button').show();
        } else {
            $.ajax({
                type: 'post',
                url: base_url + '/removeBuyerPromoCode',
                data: {'contract_id': contract_id},
                async: false,
                success: function (response) {
                    if (response == 1) {
                        $('.coupon_applied_daily_rate_parent').hide();
                        $('#mm_fee-' + contract_id).text("-$" + (parseFloat(Math.round(parseInt(rate) * 0.15) * 100) / 100).toFixed(2));
                        $('#what_you_will_get-' + contract_id).text("$" + (parseFloat(Math.round(parseInt(rate)))).toFixed(2));
                        $('#update_coupon_code').val("0 ");
                        $('.redeemPoints').html('<a data-toggle="modal" id="apply_coupon" contract_id="' +
                            contract_id + '" class="apply-coupon-btn white-btn new_blue_btn" \n\
                                title="Apply discount code" href="javascript:void(0)">Apply discount code</a>');
                        return false;
                    } else {
                        window.location.href = base_url + '/buyer/messages';
                        return false;
                    }
                }
            });
        }
    });

    $(document).on('click', '#refer_experts', function (e) {
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
                        var form_data = new FormData($('#refer_expert')[0]);
                        fixForFormdataNotSuportsEmptyFileSafariBug(form_data);
                        $.ajax({
                            type: 'post',
                            url: base_url + '/referSingleExpert ',
                            data: form_data,
                            processData: false, //Add this
                            contentType: false, //Add this
                            success: function (result) {
                                $('.referral_success_message').html('Your request has been sent. Thankyou.').show().fadeOut(6000);
                                $('#referral_name').val("");
                                $('#referral_email').val("");
                                $('.close').trigger('click');
                            }
                        });
                    } else {
                        $(".validate_expert_error").html("Email already exist.");
                        return false;
                    }
                }
            });
        }
    });
    $(document).on('click', '.all-contracts', function(){
        $.each($('#accept_contract_stage_popups .contract_extensions'), function (key, val) {
            val.click();
        });
    });

    $(document).on('click', '.all-contracts-buyer', function(){
        $.each($('#view_contract_preview .contract_extensions'), function (key, val) {
            val.click();
        });
    });

    $(document).on('click', '#referExperts', function (e) {
        $('.validate_expert_error').html('');
        $('#refer_expert')[0].reset();
    });

    $(document).on('click', '.editcontract.contract-label', function (e) {
        if (is_admin_panel_view) {
            e.stopPropagation();
            return false;
        }
        if (getUserType() == 1) {
            viewOffer(getContractId());
        } else {
            viewProjectByExpert(getContractId());
        }
    });
    $(document).on('click', '#preview_before_make_offer .close', function (e) {
        $('#preview_before_make_offer').modal("hide");
    });
    $(document).on('click', '#finish-cancel-retainer, .buyer-finish-service-contract', function (e) {
        showBuyerPopupFinishServiceProjectContract();
    });
    $(document).on('click', '#expert-finish-cancel-retainer, .expert-finish-service-contract', function (e) {
        showExpertPopupFinishServiceProjectContract();
    });
    $(document).on('click','#cancel-finish-service-package',function(e){
        $('.modal').modal('hide');
    });
    $(document).on('click','#confirm-finish-service-package',function(e){
        finishServiceProjectContract(getContractId());
    });
    $(document).on('click','#expert-confirm-finish-service-package',function(e){
        expertFinishServiceProjectContract(getContractId());
    });
    $(document).on('click', '.discuss_with_buyer', function(){
        $('#one-to-one-chatbox').focus();
    });
    $(document).on('click', '.panel-heading a', function(e){
        $('.panel-heading a').css('pointer-events','auto');
        if(!$(this).hasClass('collapsed')){
            $(this).css('pointer-events','none');
        }
    });
    $('.attach-file-icon').on('click', function(){
        var contract_id = $(document).find('#contract_id').val();
        if(contract_id != '' || !$(document).find('#make-offer').is(':visible')){
            $('#message-attachment-file').trigger('click');
        } else {
            $('.add-proposal-file-pop-up').show();
            setTimeout(function () {
                $('body').addClass('add-proposal-prompt-open');
            }, 0);
        }
        
    });
    $(document).on('click', '.add-proposal-prompt-open', function(event){
        if(event.target.class == "add-proposal-file-pop-up")
            return;
        if($(event.target).parents('.add-proposal-file-pop-up').length==0){
            $('.add-proposal-file-pop-up').hide();
            $('body').removeClass('add-proposal-prompt-open');
        }
    });
    $(document).on('click','.open-send-proposal-popup',function(event){
        event.preventDefault();
        window.location.href = base_url + '/' + getCommunicationId() + '/proposal/1';
    });
    $(document).on('click', '.discuss-with-expert', function(){
        $('#one-to-one-chatbox').focus();
    });
    $(document).on('click', '.buyer-accepted-proposal', function(){
        viewProjectByExpert();
    });
    $(document).on('click', '.reivew-proposal-link', function(){
       $('#one-to-one-chatbox').focus();
    });
    $(document).on('click', '.feedback-by-buyer', function(){
       feedbackGivenByBuyer();
    });
});




function showContractViewPopUp(contractid) {
    var view_edit_popup = base_url + '/getcontractviewpopup/' + contractid + '?source=messages';
    $.ajax({
        type: 'get',
        url: view_edit_popup,
        async:false,
        success: function (response) {
            if (response != 0) {
                $("#view_contract_preview").html("");
                if (response.hasOwnProperty('name') && response.name == 'stay_safe_popup'){
                    $('#buyer_empty_popup_container').html(response.content);
                    $('#staysafecontract').modal('show');
                } else if (response.hasOwnProperty('name') && response.name == 'business_address_pop_up'){
                    $("#make_offer_stage_popups").html("");
                    $("#make_offer_stage_popups").html(response.content);
                    $("#business_address_popup").modal("show");
                } else {
                    $('#view_contract_preview').html(response).modal('show');
                }
                $('[data-toggle="popover"]').popover({ trigger: "hover" });
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}

function showBuyerPopupFinishServiceProjectContract() {
    var contract_id = getContractId();
    $.ajax({
        type: 'get',
        url: base_url + '/showpopupfinishmonthlyspcontract/' + contract_id,
        data: {contract_id: contract_id},
        async:false,
        success: function (response) {
            $('#view_contract_preview').html(response).modal('show');
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}

function showExpertPopupFinishServiceProjectContract() {
    var contract_id = getContractId();
    $.ajax({
        type: 'get',
        url: base_url + '/showpopupfinishmonthlyspcontract/' + contract_id,
        data: {contract_id: contract_id},
        async:false,
        success: function (response) {
            $(".modal-backdrop").remove();
            $("#accept_contract_stage_popups").html("");
            $("#accept_contract_stage_popups").html(response);
            $('#expert-cancel-confirm-pop-up').modal("show");
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}
function finishServiceProjectContract(contractid) {
    $('#confirm-finish-service-package').css('pointer-events', 'none');
    $.ajax({
        type: 'get',
        url: base_url + '/finishservicepackagecontract/' + contractid,
        async:false,
        success: function (result) {
            if (result.success) {
                $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                    result.data.msg + ' ' + result.data.buyer_link ));
                $('.existing-chat').append(senderMessageTextPanel(receiverName()  ,
                    result.message_to_buyer.msg + ' ' + result.message_to_buyer.buyer_link ));
                var monthly_retainer_finished_by_buyer = {type: 'monthly_retainer_finished_by_buyer',
                    receiver: getUserId(),
                    communication_id: getCommunicationId(),
                    sender: sender_id,
                    sendername: sender_name,
                    text: result.data.msg,
                    expertlink: result.data.expert_link,
                    project_type:getProjectType(),
                    id:getProjectId()};
                socket.emit('sendmessage', monthly_retainer_finished_by_buyer);
                var monthly_retainer_finished_by_buyer_feedback_request = {
                    type: 'monthly_retainer_buyer_feedback_request',
                    receiver: getUserId(),
                    communication_id: getCommunicationId(),
                    sender: sender_id,
                    sendername: receiverName(),
                    text: result.message_to_buyer.msg,
                    project_type:getProjectType(),
                    id:getProjectId()};
                socket.emit('sendmessage', monthly_retainer_finished_by_buyer_feedback_request);
                $('#check-buyer-project-compelted-' + sender_id).attr('checked', 'checked');
                $('#buyer-project-compelted-' + sender_id).hide();
                $('.close').trigger('click');
                $("div.modal-backdrop").remove();
                $('#confirm-finish-service-package').css('pointer-events', 'auto');
                feedbackGivenByBuyer();
                scrolldown();
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}
function expertFinishServiceProjectContract(contractid) {
    $('#expert-confirm-finish-service-package').css('pointer-events', 'none');
    $.ajax({
        type: 'post',
        url: base_url + '/expertfinishservicepackagecontract',
        data: {contract_id: getContractId()},
        success: function (result) {
            if (result.success) {
                $('.existing-chat').append(senderMessageTextPanel(sender_name ,
                    result.data.msg ));
                var monthly_retainer_finished_by_expert = {type: 'monthly_retainer_finished_by_expert',
                    receiver: getUserId(),
                    communication_id: getCommunicationId(),
                    sender: sender_id,
                    sendername: sender_name,
                    text: result.data.msg,
                    buyerlink: result.data.buyer_link,
                    project_type:getProjectType(),
                    id:getProjectId()};
                socket.emit('sendmessage', monthly_retainer_finished_by_expert);
                $('#check-expert-project-compelted-' + sender_id).attr('checked', 'checked');
                $('#expert-project-completed-' + sender_id).hide();
                $('.close').trigger('click');
                $("div.modal-backdrop").remove();
                $('#expert-confirm-finish-service-package').css('pointer-events', 'auto');
                scrolldown();
            }

        }
    });
}
function emptyValidationErrors() {
    $(".validation_posted_project_id").text("");
    $(".validation_start_time").text("");
    $(".validation_end_time").text("");
    $(".validation_project_price").text("");
    $(".validation_project_deliverable").text("");
}
$(document).on('click', '.update-contract-by-expert', function(){
    editContract();
});
function editContract() {
    $('#contract_preview_update').attr("disabled", "disabled");
    var contract_data = new FormData($('#send_proposal_form')[0]);
    fixForFormdataNotSuportsEmptyFileSafariBug(contract_data);
    if(contract_ajax_flag){
        $.ajax({
            type: 'post',
            url: $('#send_proposal_form').attr('action'),
            data: contract_data,
            processData: false,
            contentType: false,
            beforeSend : function()    {
                contract_ajax_flag = false;
            },
            success: function (result) {
                if (result.success) {
                    var contract_updated = {
                    type: 'contract_updated',
                    receiver: result.data.receiver_id,
                    communication_id: result.data.communications_id,
                    sender: sender_id,
                    sendername: sender_name,
                    text: result.data.msg,
                    buyer_link: result.data.buyer_link,
                    project_type:getProjectType(),
                    id:getProjectId()
                };
                socket.emit('sendmessage', contract_updated);
                    window.location.href = base_url + '/expert/messages';
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    }
}

function isCheckedInitiateConversation() {
    $('#one-to-one-chatbox').removeAttr('disabled');
    $('#message-attachment-file').removeAttr('disabled');
    $("#accept-Eoi-message-box").hide();
    $("#message-box-text-box").show();
    $("#send-message-box").show();
}
function notCheckedInitiateConversation() {
    $('#one-to-one-chatbox').prop('disabled', true);
    $('#message-attachment-file').prop('disabled', true);
    if (current_user_type == user_type_buyer) {
        if (getProjectType() == 'project') {
            var button_text="Initiate Discovery & Negotiation Conversation";
            if($( window ).width() < 769){ var button_text="Initiate Conversation";}
            $('.existing-chat').append('<div class="message-sender-outer-container"><div class="message-sender-container chat-container"><div class="message-sender pull-right"><div class="check-box-design" onclick="initiateConversation(this);" id="initiate_chat-message"><a href="javascript:void(0)" id="action-initiate-conversation" userid=' + sender_id + ' usertype=' + current_user_type + ' communicationid="" class="begin-btn standard-btn">'+button_text+'</a></div></div></div></div>');
        }
    } else if (current_user_type == user_type_expert) {
        if (getProjectType() == 'service_package') {
            $("#accept-Eoi-message-box, #choices").show();
            $("#message-box-text-box, #send-message-panel-status").hide();
        }else{
            $("#send-message-box").hide();
            $("#accept-Eoi-message-box").hide();
        }
    }
}

function getNotifications(communication_id) {
    if (communication_id == "" || typeof communication_id == 'undefined') {
        return false;
    } else {
        $.ajax({
            type: 'get',
            url: base_url + '/chatnotificationcount?communication_id=' + communication_id,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response)
                {
                    if (response.all_unread_messages != 0 && typeof response.all_unread_messages !== 'undefined') {
                        $('.project_notifications').html('<span class="unread-count">'+response.all_unread_messages+'</span>');
                    }
                    if (response.new_message_notification != 0 && typeof response.new_message_notification !== 'undefined') {
                        $(".list-group a[communication-id*='" + communication_id + "']").find('#communication_id_'+communication_id).html('<span class="unread-message-count">'+response.new_message_notification+'</span>');
                    }
                    if (response.new_message_notification != 0) {
                        $(".list-group a[communication-id*='" + communication_id + "']").find('.time').html(response.latest_message_date);
                    }
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            },
        });

        var height_limit = 242;
        if (!is_admin_panel_view) {
            document.querySelector('#one-to-one-chatbox').addEventListener('keyup', autoSizeTextArea);
        }
        function autoSizeTextArea() {
            var element = this;
            if (element.scrollHeight < height_limit) {
                setTimeout(function () {
                    element.style.cssText = 'height:auto;';
                    element.style.cssText = 'height:' + element.scrollHeight + 'px; overflow:auto;';
                }, 0);
            } else {
                setTimeout(function () {
                    element.style.cssText = 'height:' + (height_limit - 2) + 'px; overflow:auto;';
                }, 0);
            }
        }
    }
}


function getParameterByName(name, url) {
    if (!url)
        url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(document).on('click', '#accept_availity_req', function (e) {
    $('#one-to-one-chatbox').prop('disabled', false);
    $('#message-attachment-file').prop('disabled', false);
    $("#available_status").text("You’re Available- Send a message");
    $("#available_status_value").val("available");
    showMessageTextArea();
});
$(document).on('click', '#back_to_choices', function (e) {
    $("#available_status_value").val("");
    $("#one-to-one-chatbox").val("");
    hideMessageTextArea();
});

$(document).on('click', '#decline_availity_req', function (e) {
    $('#one-to-one-chatbox').prop('disabled', false);
    $('#message-attachment-file').prop('disabled', false);
    $("#available_status").text("I am not Available");
    $("#available_status_value").val("not_available");
    showMessageTextArea();
});
function sendExpertAvailibility(sender_id) {
    $("#expert-action-availability-accepted-" + sender_id).attr('checked', 'checked');
    $("#availability-accepted-" + sender_id).attr('checked', 'checked');
    $("#send-message-panel-status").hide();
    $("#message-box-text-box").show();
    $("#make-offer").show();
    $(".list-group a.active").attr("start-conversation","yes");
}
function showMessageTextArea() {
    $("#message-box-text-box,#send-message-panel-status ").show();
    $("#one-to-one-chatbox").show().focus();
    $("#choices").hide();
}
function hideMessageTextArea() {
    $("#message-box-text-box,#send-message-panel-status ").hide();
    $("#choices").show();
}

$(".hide-conversation").click(function(){
    $(".express-off-interest-block, .hide-conversation").addClass('hide');
    $(".show-conversation-block").removeClass('hide');
});
$(".show-conversation").click(function(){
    $(".express-off-interest-block, .hide-conversation").removeClass('hide');
    $(".show-conversation-block").addClass('hide');
});

$(".hide-expres-conversation").click(function(){
    $(".express-view-off-interest-block, .hide-expres-conversation").addClass('hide');
    $(".show-express-conversation-block-link").removeClass('hide');
});
$(".show-express-conversation-block-link").click(function(){
    $(".express-view-off-interest-block, .hide-expres-conversation").removeClass('hide');
    $(".show-express-conversation-block-link").addClass('hide');
});
