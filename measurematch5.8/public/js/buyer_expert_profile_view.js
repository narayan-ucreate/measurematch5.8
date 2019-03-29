$(document).ready(function (e) {
    if ($(window).width() < 1024) {
        $('body').addClass('buyer-mobile-display');
    }
    $(document).on("click","#submit_invite_expert", function(e){
       e.preventDefault();
        var buyer_id = $('#buyer_id').val();
        var expert_name = $('#expert_name').val();
        if ($('#business_detail_id').val().length == 0) {
            showVatDetailsPopupToBuyer(buyer_id, expert_name);
        }else{
           $("#invite_expert_to_discuss_project").submit(); 
        }
        
     });
     $(document).on('submit', '#invite_expert_to_discuss_project', function (event) {
        event.preventDefault();
        
        var user_id = $('#user_id').val();
       
        var invite_expert_to_discuss_project = new FormData($(this)[0]);
        var project_id = $('#job_title').val();
      
        var msg_txt = $('#invite_message').val().replace(/<script\b[^>]*>([\s\S]*?)<\/script>/gm,"");
        if (typeof project_id === "undefined" || project_id == '') {
            $('#error_upload').text('Please select the project.');
            return false;
        } else if (msg_txt == '') {
            $('#error_upload').text('Please add message.');
            return false;
        } else {
            $(".send-message-profile").prop("disabled", true);
            $.ajax({
                type: 'post',
                url: base_url + '/invitenewexpertforconversation',
                data: invite_expert_to_discuss_project,
                success: function (result) {
                    if (result.success == 1) {
                        $(".send-message-profile").prop("disabled", false);
                        var invite_expert_for_conversation = {type: 'invite_expert_for_conversation', receiver: user_id, communication_id: result.data.communications_id, sender: buyer_id, sendername: "", text: result.data.msg, expertlink: result.data.expert_link};
                        socket.emit('sendmessage', invite_expert_for_conversation);
                        window.location.href = base_url + '/buyer/messages/project/'+project_id;

                    }

                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

    });
    $('body').on('click', '.create-project', function(e) {
        e.preventDefault();
        window.location.href = $(this).attr('href');
    })
    $('#view_project_overview,.invite-expert-link').on('click', function(e) {
        e.preventDefault();
        $('#job_title').change();
        setTimeout(function() {
            var element = $('.modal-body').find('ul.dropdown-menu');
            if (!element.find('.create-project').length) {
                var total_item = element.find('li').length
                element.append('<li data-original-index="'+total_item+'"><a class="create-project" href="'+$('#create_project_url').val()+'" tabindex="'+total_item+'" class="" style="" data-tokens="null"><span class="text gilroyregular-semibold">Submit a new Project brief</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>')
                $('#invite_message').focus();
            }
        }, 1000)
    })
    $('.select-project').on('change', function(e) {
        e.preventDefault();
        var project_status = $(".select-project option:selected").attr('data-status')
        $('.common-section').addClass('hide');
        if (typeof project_status !== 'undefined') {
            var project_id = $(".select-project option:selected").val()
            var project_url = $('#project_url').val()+'/'+project_id;
            if (project_status == $('#pending_status').val()) {
                $('#under_review_project').removeClass('hide');
                $('#learn_more_project').attr('href', project_url);
            } else if (project_status == $('#publish_status').val()) {
                var is_in_conversation = $(".select-project option:selected").attr('data-in-conversation')
                var communication_id = $(".select-project option:selected").attr('data-communication-id')
                $('.view-conversation').attr('href', project_url+'?communication_id='+communication_id);
                if (is_in_conversation == 1) {
                    $('.in-covnersation-section').removeClass('hide');
                } else {
                    $('.new-message-section').removeClass('hide')
                    $('#invite_message').focus();
                }
            }
        }

    });

    if($('.show_rating').attr('expert_rating')!=''){
        $('.show_rating').each(function () {
            var rating_by_buyer = $(this).attr('expert_rating');
            var id = $(this).attr('id');
            udpateRating(id,rating_by_buyer);
        });
    }

    //for showing star rating dynamically after rating is given
    function udpateRating(selector,rating_by_buyer){
        $("#"+selector).rateYo({
            rating: rating_by_buyer,
            numStars: 5,
            precision: 2,
            readOnly: true,
            ratedFill: "#29235c",
            starWidth: "20px"
        });
    }
});

    