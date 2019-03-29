var save_hit = [];
function isValidUrl(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
function find_locations(item_number, location) {
    $.ajax({
        type: 'get',
        url: base_url + '/getlocationdetails',
        data: {'location': location},
        success: function (data) {
            save_hit[location] = data;
            var obj = data;
            $('#tags-' + item_number).html('');
            $.each(obj, function (key, value) {
                $('#tags-' + item_number).append('<span>' + value.description + '<input type="hidden" id="city" value="'+value.city+'"><input type="hidden" id="country" value="'+value.country+'"></span>');
                $('#cmpy_location_value-' + item_number).val('1');
            });
            $('.dropdown-' + item_number).show();
            if (!$('#tags-' + item_number).html()) {
                $('#cmpy_dtl .dropdown-' + item_number).hide();
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    $(document).on('click', '.dropdown-' + item_number + ' span', function () {
        var getvalue = $(this).text();
        $('#cmpy_location-' + item_number).val(getvalue);
        $('#cmpy_dtl  .dropdown-' + item_number).hide();
    });
}
function readURL(input) {

    if (input.files && input.files[0]) {
        var fileinput = document.getElementById('img_show');
        if (!fileinput)
            return "";
        var filename = fileinput.value;
        if (filename.length === 0)
            return "";
        var dot = filename.lastIndexOf(".");
        if (dot === -1)
            return "";
        var extension = filename.substr(dot, filename.length);
        //alert(extension);
        var file_ext = extension.toLowerCase();
        var allowed_extensions = [".jpg", ".png", ".bmp"];
        var a = allowed_extensions.indexOf(file_ext);
        if (a < 0)
        {
            $('#imageerrormsg').text('Images are allowed only of .jpg, .png or .gif format');
            $('#imageerrormsg').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah1').css('background-image', 'url("' + e.target.result + '")');
            };

            reader.readAsDataURL(input.files[0]);
            $("#show_image_pop_profile").hide();
            $("#show_change_image_pop").show();

        }
    }
}
$(document).ready(function () {
    $("#show_image_pop_profile").on("click", function () {
        $("#img_show").trigger("click");
    });

    $("#show_image_pop_on_cover").on("click", function () {
        $("#cover_image_file").trigger("click");
    });
    $("#change_image_pop_on_cover").on("click", function () {
        $("#cover_image_file").trigger("click");
    });

    /*!
     * Edit bio information
     */

    if (typeof bas64url != "undefined") {
        if ($('.profile-image .fileinput-new').hasClass("blank_buyer_img")) {
            $('.profilezoompopup .change_car #change_photo_label').text('Add photo');
        }

        $("#cancelpopup").on("click", function () {
            $("#cropit-preview-image").attr("src", '');
        });

        var imgindex;
        $(document).on('click', '.user_pic', function () {
            $('#cropimageadd').trigger('click');
        });

        $('#cropimageadd').on('change', function () {
            if (!$('#zoompopup').is(":visible") && $(this).val().length > 0) {
                if (this.files && this.files[0]) {
                    var FR = new FileReader();
                    FR.addEventListener("load", function (e) {
                        $('#image-cropper').cropit('destroy');
                        $('#image-cropper').cropit({exportZoom: 1,
                            maxZoom: 1.5,
                            imageBackground: true,
                            imageBackgroundBorderWidth: 250,
                            imageBackgroundBorderSize: 100,
                            imageState: {src: {imgsrc: e.target.result}}});
                    });

                    FR.readAsDataURL(this.files[0]);
                    $('#zoompopup').modal('show');
                }
            }
        });

        $('.savecropimage').on('click', function () {
            var image_data = $('#image-cropper').cropit('export');
            var new_img_div = "<div class='profilepicture user_pic' style='background-image:url(" + image_data + ");' alt=''></div>";
            $('.fileinput-new').html(new_img_div);
            $('.fileinput-new').addClass('uploaded-profile-pic');
            $('.fileinput-new').removeClass('blank_buyer_img');
            $('#show_change_image_pop').show();
            $('#base64image').val(image_data);
            var zoom_val = $('#image-cropper').cropit('zoom');
            var upload_image_data = $('.cropit-preview-image').attr('src');
            $('#cropitimage').val(upload_image_data);
            $('#image_url').val(image_data);
            $('#zoomlevel').val(zoom_val);
            $('#logo_form').submit();
        });

        $('.rotate-cw-btn').on('click', function () {
            $('#image-cropper').cropit('rotateCW');
        });
    }


    $('#summary-tab-link').on('click', function () {
        var new_url = base_url + '/buyer/profile-summary';
        history.pushState({}, null, new_url);
    });

    $('#save_bio_btn').on('click', function (e) {
        var biotext = $('#bio_text').val();
        if (biotext == '') {
            $(".validation_error").text('Please enter your bio');
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else {
            e.preventDefault();
            var biodata = $('#bio_form').serialize();
            $.ajax({
                type: 'post',
                url: base_url + '/savebio',
                data: biodata,
                success: function (response) {
                    if (response == 1) {
                        window.location.href = base_url + '/buyer/profile-summary';
                    } else if (response == 2) {
                        $('.validation_error').text('Bio could not be saved!')
                        $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                    } else if (response == 3) {
                        $('.validation_error').text('Please add description to save bio!')
                        $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
            });
        }
    });
    $('#parent_company_exists').on('change', function () {
        var optn = $(this).val();

        if (optn == 'Yes') {
            $('#parent_company_url').css('display', 'block');
        } else {
            $('#parent_company_url').css('display', 'none');
        }
    });

//showing the original value in bio pop up on click of edit pen
    $('.bio_edit_pen').on('click', function () {
        if ($(this).hasClass('stop_editing')) {
            $('.remove_bio').show();
            $('.edit_bio_expert').hide();
        } else {
            autosize(document.querySelectorAll('textarea#bio_text'));
            $('.remove_bio').hide();
            $('.edit_bio_expert').show();
        }
    });
    $('#save_company_details').on('click', function (e) {
        $(document).find('.no_matching_location').remove();
        var company_url = $('#company_url').val();
        var company_location = $.trim($('#office_location').val());
        var type_of_organization = $.trim($('#type_of_organization option:selected').val());

        var company_option_value = $('#parent_company_exists').val();

        var current_url = $('#hidden_parent_company_url').val();
        if (company_option_value != '') {
            var company_option = company_option_value;
        } else {
            if (current_url != '' && current_url != '-1') {
                var company_option = '1';
            } else if (current_url != '-1') {
                var company_option = '2';
            } else {
                var company_option = '0';
            }
        }

        var company_permanent_url = $('#parent_company_url').val();
        var urlregex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        var company_url_information = urlregex.test(company_url);
        var length = $.trim($("#office_location").val()).length;
        var parent_company_character_length = $.trim($("#parent_company_url").val()).length;

        if (company_url == '') {

            $("#company_url_validation").text('Please enter company website.');
            $('#company_url_validation').fadeIn('fast').delay(2000).fadeOut();

        } else if(type_of_organization==''){
           $("#type_of_org_error").text('Please select type of organization.');
            $('#type_of_org_error').fadeIn('fast').delay(2000).fadeOut(); 
        }else if (company_url_information == false) {
            $("#company_website_url").text('Company website should be valid.');
            $('#company_website_url').fadeIn('fast').delay(2000).fadeOut();
        } else if (company_option == '' || company_option == '0') {
            $("#parent_company_existance_error").text('Please select company option.');
            $('#parent_company_existance_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (company_location == '') {
            $("#location_error").text('Please enter company location');
            $('#location_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (location_description.length !== 0 && location_description.indexOf($("#office_location").val()) <= -1) {
                $("#location_error").text('Please choose a location from the dropdown list of choices');
                $('#office_location').val('');
                $('#location_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (company_location != '' && length == 0) {
            $("#location_error").text('Blank space(s) are not allowed.');
            $('#location_error').fadeIn('fast').delay(2000).fadeOut();
        } else if (company_option == '1' && parent_company_character_length == 0) {
            $("#parent_company_url_error").text('Please enter parent company name.');
            $('#parent_company_url_error').fadeIn('fast').delay(2000).fadeOut();
        } else {
            e.preventDefault();
            var bioData = $('#cmpy_dtl').serialize();
            $.ajax({
                type: 'post',
                dataType: 'text',
                url: base_url + '/editcompany',
                async: false,
                data: bioData,
                success: function (response) {
                   if(response) {
                        window.location.href = base_url + '/buyer/profile-summary';
                    } else if (response.success == 0) {
                        $('.validation_error').text('Company Detail could not be saved!')
                        $('.validation_error').fadeIn('fast').delay(2000).fadeOut();
                    }
                },
                error: function (response)
                {
                    alert('Error: Please refresh the page');
                },
            });
        }
    });

    
    $(".bio_edit_pen").on('click', function () {
        $("textarea#bio_text").trigger('focus');
    });
    $("textarea#bio_text").on('focus', function () {
        var ele = this.scrollHeight;
        $(this).css('height', ele + "px");
    });

    $('#cancel_edit_company').on('click' ,function () {
        $('#confirm_cancellation').modal('show');
    });


    $('.edit-profile-section').on('click', function (e) {
        if ($(this).hasClass('stop_editing')) {

        } else {
            $('.remove_view').hide();
            $('#edit_profile').show();
            var hidden_url = $('#hidUrl').val();

            $('#company_url').val(hidden_url);
            var present_url = $('#hidden_parent_company_url').val();
            $('#parent_company_url').val(present_url);

            if (present_url != '' && present_url != '-1') {
                var finalText = 'Yes';
                var addtext = $('#No');
                var addtext1 = $('#noVal');
                var innerTxt = '1';
            } else if (present_url != '-1') {
                var finalText = 'No';
                var addtext = $('#Yes');
                var addtext1 = $('#noVal');
                var innerTxt = '2';
            } else {
                var finalText = 'Choose the option';
                var addtext = $('#No');
                var addtext1 = $('#Yes');
                var innerTxt = '0';
            }
            addtext.removeAttr("selected");
            addtext1.removeAttr("selected");
            $('#comany_url.dropdown-toggle').attr('title', finalText);
            $('#comany_url .dropdown-toggle span.pull-left').html(finalText);
            $("#comany_url ul.dropdown-menu.inner").find("li").removeClass("selected");
            $("#comany_url ul.dropdown-menu.inner").find("li").eq(innerTxt).addClass("selected");
        }
    });
    $(document).on('click', '.stop_editing', function () {
        $('#confirm_cancellation').modal('show');
        return false;
    });
    $(document).on('click', '#save_edited_changes', function () {
         $('#confirm_cancellation').modal('hide');
         $('#save_company_details').trigger('click'); 
        if ($('#bio_text').val() != "") {
            $(".edit_view").removeClass("stop_editing");
            $('#save_bio_btn').trigger('click');
        }else{
            window.location.href = base_url + '/buyer/profile-summary';
        }
    });
    $(document).on('click', '.bio_edit_pen, .edit_bio', function () {
        $('.edit-profile-section').addClass('stop_editing');
       
    });
    $(document).on('click', '.edit-profile-section', function () {
        $('.editbiotext').addClass('stop_editing');
        $('.edit_icon').addClass('stop_editing');
        $('.edit-profile-section').addClass('stop_editing');
        $('a[href="#freelancer-tab"]').addClass('stop_editing');
    });
    $(document).on('click', '#discard_edited_changes', function () {
        window.location.href = base_url + '/buyer/profile-summary';
    });
});
$("#office_location").on('keyup', function (e) { 
    var location = document.getElementById('office_location').value;
    if (e.keyCode === 13 || location == '') {
        $("#office_location_tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#office_location_tags"));
            return false;
        }
    }
    findLocation(location, $("#office_location_tags"));
});
$(document).on('click', '.dropdown span', function () { 
     $('#office_location').val($(this).text()); 
     $('#office_location_tags').hide();
     $(document).find('.no_matching_location').remove();
});

