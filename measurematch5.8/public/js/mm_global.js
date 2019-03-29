function displayErrorMessage(jqXHR, exception) {
    var msg = '';
    if (jqXHR.status === 0) {
        msg = 'Not connect.\n Verify Network.';
    } else if (jqXHR.status == 404) {
        msg = 'Requested page not found. [404]';
    } else if (jqXHR.status == 500) {
        msg = 'Internal Server Error [500].';
    } else if (exception === 'parsererror') {
        msg = 'Requested JSON parse failed.';
    } else if (exception === 'timeout') {
        msg = 'Time out error.';
    } else if (exception === 'abort') {
        msg = 'Ajax request aborted.';
    } else {
        msg = 'Uncaught Error.\n' + jqXHR.responseText;
    }
    console.log(msg);
}

function getCountries() {
    var country_arr = new Array("Afghanistan", "Albania", "Algeria", "American Samoa", "Angola", "Anguilla", "Antartica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Ashmore and Cartier Island", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burma", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Clipperton Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo, Democratic Republic of the", "Congo, Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czeck Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Europa Island", "Falkland Islands (Islas Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern and Antarctic Lands", "Gabon", "Gambia, The", "Gaza Strip", "Georgia", "Germany", "Ghana", "Gibraltar", "Glorioso Islands", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See (Vatican City)", "Honduras", "Hong Kong", "Howland Island", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Ireland, Northern", "Israel", "Italy", "Jamaica", "Jan Mayen", "Japan", "Jarvis Island", "Jersey", "Johnston Atoll", "Jordan", "Juan de Nova Island", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Man, Isle of", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Midway Islands", "Moldova", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcaim Islands", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romainia", "Russia", "Rwanda", "Saint Helena", "Saint Kitts and Nevis", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Scotland", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and South Sandwich Islands", "Spain", "Spratly Islands", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Tobago", "Toga", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "USA", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands", "Wales", "Wallis and Futuna", "West Bank", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
    return country_arr;
}
$(function() {
    $("meta[name='viewport']").attr(
        'content',
        "user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"
    );
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.expert_profile_admin_unapproved').click(function(e) {
        e.preventDefault();
        $('#awaiting_admin_approval').modal('show');
    });
    $('.expert_profile_admin_unapproved').click(function(e) {
        e.preventDefault();
        $('#awaiting_admin_approval').modal('show');
    });

    $('.expert_profile_incomplete').click(function(e) {
        e.preventDefault();
        $('#profile_incomplete').modal('show');
    });
    var href = document.location.href;
    var last_path_segment = href.substr(href.lastIndexOf('/') + 1);
    if (logged_in && last_path_segment != 'messages') {
        socket.on('message', function(data) {
            getNewMessageNotifications(socket_sender_id, data);

        });
    }
    $('body').on('shown.bs.modal', function(e) {
        $('body').addClass('modal-open');
    })
    $('body').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
    });
    $('.message-mm-support').click(function(e) {
        if ($(window).width() <= 1024) {
            window.location.href = base_url + '/support'
            return false;
        }
        $('#message-mm-support_panel, #support_message_to_admin , #message-mm-support_panel h3').show();
        $("#support_success_message, #account_review_pop_up").hide();
        $('#user_message').focus();
    });
    $('#submit_message-mm-support').on('click', function() {
        if ($('#message-mm-support_panel').length) {
            var user_messages = $("#user_message").val().trim();
            if (user_messages.length < 1) {
                $("#message_validation_error").show().text("Please enter your message").fadeOut(3000);
            } else {
                $.ajax({
                    type: 'post',
                    url: base_url + '/supportnotification',
                    data: {
                        user_messages: user_messages
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#user_message").val("");
                            $('#support_message_to_admin , #message-mm-support_panel h3').hide();
                            $("#support_success_message").show().text("Thank you for contacting us. We will get back to you shortly.");
                        }
                    },
                    error: function(jqXHR, exception) {
                        displayErrorMessage(jqXHR, exception);
                    },
                });
            }
        }
    });
    $('#submit_support_request').on('click', function() {
        var user_messages = $("#support_message").val().trim();
        if (user_messages.length < 1) {
            $("#support_message_error").show().text("Please enter your message").fadeOut(3000);
        } else {
            $.ajax({
                type: 'post',
                url: base_url + '/supportnotification',
                data: {
                    user_messages: user_messages
                },
                success: function(response) {
                    $('#support_success_panel').show();
                    $('#feedback_panel').hide();
                },
                error: function(jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
            });
        }
    });
    $("#close_support_panel").on("click", function () {
        $('#message-mm-support_panel, #support_success_message').hide();
        if ($('body').hasClass("modal-open")) {
            $('#account_review_pop_up').show();
        }
    });

    if (logged_in) {
        if(user_state == '1'){
            user_state = 'Approved';
        } else if(user_state == '2'){
            user_state = 'Rejected';
        } else if(user_state == '0'){
            user_state = 'Pending Approved';
        }
        switch (user_type) {
            case '1':
                user_type_label = 'Expert';
                break;
            case '2':
                user_type_label = 'Buyer';
                break;
            case '4':
                user_type_label = 'Vendor';
                break;
            default:
                user_type_label = 'NA';
        }
        var identity_data = {
            userType: user_type_label,
            userState: user_state,
            name: name,
            firstName: first_name,
            lastName: last_name,
            email: email
        };
        if(user_type == 2){
            user_type_label = 'Buyer';
            identity_data.projectCreatedCount = projectCreatedCount ;
            identity_data.projectApprovedCount = projectApprovedCount ;
        }
        analytics.identify(user_id, identity_data);
        analytics.page('Application');
    }
});

function getNewMessageNotifications(user_id, data) {
    $.ajax({
        type: 'get',
        url: base_url + '/users/' + user_id + '/getnewmessagenotifications',
        data: {
            communication_id: user_id,
            project_type: data.project_type,
            id: data.id
        },
        success: function(response) {
            if (response) {

                if (response.all_unread_messages != 0) {
                    $('.project_notifications').html('<span class="unread-count">'+response.all_unread_messages+'</span>');
                }
                if (response.project_type == 'project') {
                    $('body').find('#unread-project-count-'+response.id).html('<span class="unread-count-specific">'+response.project_unread_message_count+'</span>');
                } else if (response.project_type == 'service_package') {
                    $('body').find('#unread-service-packages-count-'+response.id).html('<span class="unread-count-specific">'+response.project_unread_message_count+'</span>');
                }
            }
        },
        error: function(jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        },
    });
}

function budgetBreakdownCalculation(rate) {
    var rate = rate.replace(',', '');
    var paid_to_expert = parseInt(rate) * .85;
    var paid_to_mm = parseInt(rate) * .15;
    $('.paid_exp').text('-');
    $('.paid_mm').text('-');
    if (rate >= 200) {
        $('.paid_exp').text('$' + addCommas(Math.round(paid_to_expert * 100) / 100));
        $('.paid_mm').text('$' + addCommas(Math.round(paid_to_mm * 100) / 100));
    }
}


$(document).click(function (event) {
    event.stopPropagation();
    if (!$(event.target).hasClass('dropdown')) {
        $(".custom-dropdown-style .dropdown , .new-custom-dropdown-style .dropdown").hide();
    }
});
$(document).ready(function() {
    $('body').on('click', '.rebook-project', function(e) {
        e.preventDefault();
        $('#view_contract_preview').modal('hide');
        $('#rebook_project').modal('show');
        $('body').find('#expert_id').val($(this).attr('data-expert-id'));
        $('body').find('.rebook-expert-name').html($(this).attr('data-expert-name'));
        $('body').find('.rebook-pic').css('background-image', 'url('+$(this).attr('data-expert-url')+')');
    });
    var maxField = 10;
    var addButton = $('#add-deliverable-link');
    var wrapper = $('.deliverable-panel');
    var x = 1;
    $(addButton).click(function () {
        if (x < maxField) {
            x++;
            $(wrapper).append('<div class="remove-filed-btn">\n\
                        <a href="javascript:void(0);" class="remove_button"  title="Remove">X</a> <textarea name="deliverables[]" value="" tabindex="3.0'+x+'" class="deliverables input-field-design font-16 " \n\
                         maxlength="2500"     placeholder="Describe another deliverable here." cols="50"></textarea>\n\
                    </div>');
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
    $('#rebook_project_form').on('submit', function(e) {
        e.preventDefault();
        var deliverables = $('body').find("textarea[name='deliverables[]']")
            .map(function(){
                return $(this).val();})
            .get()
            .filter(function(deliverable) {
                return deliverable;
            });
        var deliverable_validation_message = ''
        if (deliverables.length == 0) {
            deliverable_validation_message = 'This field is required to submit your Project brief';
        }
        $('#deliverables_validation_error').html(deliverable_validation_message);
        $.ajax({
            method: 'post',
            url: $(this).attr('action'),
            statusCode: {
                422 : function(data) {
                    var response = data.responseJSON;
                    manupulateMessage(response);
                }
            },
            data : {
                _token: $('#token').val(),
                title : $('#job_title').val(),
                deliverables: deliverables,
                description: $('#description').val(),
                currency: $('#currency').val(),
                rebook_project: true,
                expert_id : $('#expert_id').val()
            },
            success: function(project_id) {
                var redirect_location = $('#redirect_url').val();
                redirect_location = redirect_location.split('/');
                redirect_location[redirect_location.length - 1] = project_id;
                redirect_location = redirect_location.join('/');
                window.location = redirect_location
            }
        });
    });
    function manupulateMessage(errors) {
        $('.validation_error').remove();
        for(var key in errors) {
            $("input[name='"+key+"']").after('<span class="validation_error">'+errors[key][0]+'</span>');
            $("textarea[name='"+key+"']").after('<span class="validation_error">'+errors[key][0]+'</span>');
            $("select[name='"+key+"']").after('<span class="validation_error">'+errors[key][0]+'</span>');
        }
    }

    $('body').on('keyup', '.form-field-rebook', function() {
        validateAllForm();
    })

    function validateAllForm() {
        var isValid = validateForm();
        if (isValid && validateCurrency()) {
            $('#submit-rebook').removeClass('disable-btn')
        } else {
            $('#submit-rebook').addClass('disable-btn')
        }
    }
    $('body').on('change', 'select#currency', function() {
        validateAllForm();
    })
    function validateCurrency() {
        var currency = $('select#currency option:selected').val();
        return currency != '' ? true : false;
    }


    function validateForm() {
        var isValid = true;
        $('.form-field-rebook').each(function() {
            if ( $(this).val() === '' )
                isValid = false;
        });
        return isValid;
    }

})


var save_hit = [];
var location_description = [];
function appendDropdownTags(value) {
    return '<span>' + value.description + '<input type="hidden" id="city" value="' + value.city + '"><input type="hidden" id="country" value="' + value.country + '"></span>';
}
function saveApiHits(location, tags) {
    $(document).find('.no_matching_location').remove();
    var obj = save_hit[location];
    tags.html('');
    $.each(obj, function (key, value) {
        tags.append(appendDropdownTags(value));
    });
    $(tags.selector).show();
}
function findLocation(location, tags) {
    $(document).find('.no_matching_location').remove();
    $.ajax({
        type: 'get',
        url: base_url + '/getlocationdetails',
        data: {'location': escape(location.trim())},
        success: function (data) {
            if (data.length === 0) {
                $("#office_location").after('<div class="validation_error no_matching_location">Please choose a location from the dropdown list of choices</div>');
                $('#office_location').val('');
            }
            if(typeof location_selected !='undefined' && location_selected === 1){
                return false;
            }
            save_hit[location] = data;
            var obj = data;
            tags.html('');
            $.each(obj, function (key, value) {
                tags.append(appendDropdownTags(value));
                location_description[key] = value.description;
            });
            $(tags.selector).show();
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}
function addCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
function goBackButton() {
    var referer = document.referrer;
    if(referer.indexOf('measurematch') !== -1){
        window.location=referer;
    } else{
        window.location=base_url;
    }
}
$(document).ready(function(){
    $("#menu-toggle").click(function(e) {
        $("#wrapper").toggleClass("active");
    });
    $("#menu_for_devices").click(function(e) {
        $(this).toggleClass("device-menu-open");
    });

    $('.expert_profile_unapproved_for_service_hub').click(function(e) {
        e.preventDefault();
        $('#awaiting_admin_approval_for_service_hub').modal('show');
    });


    $('.expert_profile_unapproved').click(function(e){
        e.preventDefault();

        $('#awaiting_admin_approval_for_service_package').modal('show');
    });
    $("#addskill_manually").on('focus', function(){
        $(this).parent().addClass("skills-focus");
    });
    $("#addskill_manually").on('blur', function(){
        $(this).parent().removeClass("skills-focus");
    });

    $(document).on('click', '.pending-account-approval', function (event) {
        $('#account_review_pop_up').modal({keyboard: false, show: true});
        $('.modal-backdrop').addClass('new-buyer-registration');
        event.preventDefault();
    });
    $(document).on('keyup', '.price-format-validation', function (event) {
        if (event.which >= 37 && event.which <= 40)
            return;
        $(this).val(function (index, value) {
            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });
});

function allowNumbersOnly(ev){
    var keyCode = window.event ? ev.keyCode : ev.which;
    //codes for 0-9
    if (keyCode < 48 || keyCode > 57) {
        //codes for backspace, delete, enter
        if (keyCode != 0 && keyCode != 8 && keyCode != 13 && !ev.ctrlKey) {
            ev.preventDefault();
        }
    }
}

function htmlbodyHeightUpdate() {
    var window_height = $(window).height()
    var navigation_height = $('.nav').height() + 50
    var container_height = $('.main').height()
    var decrement_height = 15;
    if ( $(".breadcrumb-bg").length ) {
        decrement_height += $('.breadcrumb-bg').outerHeight();
    }

    $('#page-content-wrapper, .find-match-content, .white-box, .min-height, .post-job-view').css('min-height', window_height - navigation_height - decrement_height);
    $('.info-right-side').css('min-height', window_height - navigation_height - 150);
    $('.project-brife-section').css('min-height', window_height - navigation_height - 107 - decrement_height);

    $('.global-scroll').css('max-height', window_height - 290);

    if (container_height > window_height) {
        $('html').height(Math.max(navigation_height, window_height, container_height) + 10);
        $('body').height(Math.max(navigation_height, window_height, container_height) + 10);
    } else
    {
        $('html').height(Math.max(navigation_height, window_height, container_height));
        $('body').height(Math.max(navigation_height, window_height, container_height));
    }

}
function fixForFormdataNotSuportsEmptyFileSafariBug(form_data) {
    if (form_data instanceof FormData && navigator.userAgent.match(/version\/11((\.[0-9]*)*)? .*safari/i)) {
        try {
            eval('for (var pair of form_data.entries()) {\
                                        if (pair[1] instanceof File && pair[1].name === \'\' && pair[1].size === 0) {\
                                            form_data.delete(pair[0]);\
                                        }\
                                    }');
            return form_data;
        } catch (e) {
        }
    }
}

function updateDate(date_from_db_selector, updated_date_selector) {
    var start_date = $('#' + date_from_db_selector + '').val();
    if (start_date) {
        var date = new Date();
        var from = start_date.split("-");
        var day = date.getDate();
        var month_index = date.getMonth();
        var year = date.getFullYear();
        var start_min_date = moment(new Date((month_index + 1) + "/" + (day) + "/" + (year)));
        var start_default = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));
        $('#' + updated_date_selector).datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            defaultDate: start_default,
        });
    }
}