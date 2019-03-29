var skill_array = [];

function isInputKeyNumber(evt) {
    evt = (evt) ? evt : window.event;
    var char_code = (evt.which) ? evt.which : evt.keyCode;
    if (char_code > 31 && (char_code < 48 || char_code > 57)) {
        return false;
    }
    return true;
}

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

function readURL(input) {

    if (input.files && input.files[0]) {
        var fileinput = document.getElementById('img_show');
        if (!fileinput)
            return "";
        var filename = fileinput.value;
        if (filename.length == 0)
            return "";
        var dot = filename.lastIndexOf(".");
        if (dot == -1)
            return "";
        var extension = filename.substr(dot, filename.length);
        var file_ext = extension.toLowerCase();
        var allowed_extensions = [".jpg", ".png", ".gif", ".jpeg"];
        var a = allowed_extensions.indexOf(file_ext);
        if (a < 0)
        {
            $('#imageerrormsg').text('Images are allowed only of .jpg, .png or .gif format');
            $('#imageerrormsg').fadeIn('fast').delay(24000).fadeOut();
            return false;
        } else
        {
            function _base64ToArrayBuffer(base64) {
                var binary_string = window.atob(base64.split(",")[1]);
                var len = binary_string.length;
                var bytes = new Uint8Array(len);
                for (var i = 0; i < len; i++) {
                    bytes[i] = binary_string.charCodeAt(i);
                }
                return bytes.buffer;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah1').css('background-image', 'url("' + e.target.result + '")');
            };

            reader.readAsDataURL(input.files[0]);
            $("#show_image_pop").hide();
            $("#show_change_image_pop").show();

        }
    }
}

function capitalize(textboxid, str) {
    // string with alteast one character
    if (str && str.length >= 1)
    {
        var firstChar = str.charAt(0);
        var remainingStr = str.slice(1);
        str = firstChar.toUpperCase() + remainingStr;
    }
    document.getElementById(textboxid).value = str;
}

$(window).resize(function () {
    tysize();
});

function addStopEditingClass(){
    $('.edit_icon').addClass('stop_editing');
    $('.editprofilebio').addClass('stop_editing');
    $('.edit-profile-section').addClass('stop_editing');
    $('#addclgbuttom').addClass('stop_editing');
    $('.add-expert-position').addClass('stop_editing');
    $('#addskill').addClass('stop_editing');
    $('#addtool').addClass('stop_editing');
    $('#myTab li').not('.active').find('a').removeAttr("data-toggle");
    $('#skill_a').addClass('stop_editing');
    $('#workhistory_a').addClass('stop_editing');
    $('#education_a').addClass('stop_editing');
    $('a[href="#experience-tab"]').addClass('stop_editing');
    $('.removeskill').addClass('stop_editing');
    $('#add_lang_button').addClass('stop_editing');
    $('.edit_lang_button').addClass('stop_editing');
    $('.addCourse').addClass('stop_editing');
    $('.edit_work_history').addClass('stop_editing');
    $('.edit_college').addClass('stop_editing');
    $('.edit_course').addClass('stop_editing');
    $('.edit_icon').addClass('edit_icon_removed');
    $('.edit_icon').removeClass('edit_icon');
}

function buyerRating() {
    $('.rating-list').each(function(ele) {
        $(this).rateYo({
            rating: $(this).attr('data-rating'),
            numStars: 5,
            precision: 2,
            minValue: 1,
            maxValue: 5,
            readOnly: true,
            ratedFill: "#1e70b7",
        });
    })
}
function totalToolsAndSkills(){
    var total_tools_and_skills = 0;
    if($('#skill_from_db').val() != ''){
        total_tools_and_skills = total_tools_and_skills + parseInt($('#skill_from_db').val().split(',').length);
    }
    if($('#tools_from_db').val() != ''){
        total_tools_and_skills = total_tools_and_skills + parseInt($('#tools_from_db').val().split(',').length);
    }
    return total_tools_and_skills;
}


$(document).ready(function () {
    if (document.referrer.indexOf('buyer/experts/search') > -1 || document.referrer.indexOf('servicepackages/type') > -1){
        $('.expert-breadcrumb').show();
        $('#back_to_search a:first').attr('href', document.referrer);
        $('#back_to_search').show();
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

     tysize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Configure/customize these variables.

    $('.expertRefeeralMessage').delay(2000).fadeOut(6000);
    var image_length_count;
    $("#show_image_pop").on("click", function () {
        $("#img_show").trigger("click");
    });
    $("#show_change_image_pop").on("click", function () {
        $("#img_show").trigger("click");
    });
    $("#show_image_pop_on_cover").on("click", function () {
        $("#cover_image_file").trigger("click");
    });
    $("#change_image_pop_on_cover").on("click", function () {
        $("#cover_image_file").trigger("click");
    });

    if (typeof bas64url != "undefined") {
        var favourite_url = bas64url + "?url=" + $('.profile-image .fileinput-new .profilepicture').css('background-image').replace('url(', '').replace(')', '').replace(/\"/gi, "");

        $("#cancelpopup").on("click", function () {
            $("#cropit-preview-image").attr("src", '');
        });

        var imgindex;
        $(document).off('click','.user_pic');
        $(document).on('click','.user_pic', function () {
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

        $('.savecropimage').on('click',function () {
            var image_data = $('#image-cropper').cropit('export');
            $('#base64image').val(image_data);
            var zoom_value = $('#image-cropper').cropit('zoom');
            $('.profilepicture').css('background-image', 'url(' + image_data + ')');
            var upload_image_data = $('.cropit-preview-image').attr('src');
            $('#cropitimage').val(upload_image_data);
            $('#output').attr('src', image_data);
            $('#image_url').val(image_data);
            $('#dealer_photo').val(image_data);
            $('#zoomlevel').val(zoom_value);
            $('.loading').show();
            $('body').addClass('bodyloader');
            $('#uploadpic').submit();
        });

        $('.rotate-cw-btn').on('click',function () {
            $('#image-cropper').cropit('rotateCW');
        });
    }

   /*Skills js start*/
    $('.closeskill').on('click', function (e) {
        var attr = $(this).attr('id');
        var detail = attr.split('_');
        var hidden_id = detail[1].replace(/ /g, "_");
        var value = $('#' + hidden_id).val();
        $(this).parent().removeClass('selected');
        if ($(this).parent().hasClass('a_wraper') == true) {
            $(this).parent().remove();
        }

        var skill_set_val = $('#skill_set').val();
        var list = skill_set_val;
        var new_value = list.replace(new RegExp(",?" + value + ",?"), function (match) {
            var first_comma = match.charAt(0) === ',',
                    second_comma;
            if (first_comma && (second_comma = match.charAt(match.length - 1) === ','))
            {
                return ',';
            }
            return '';
        });

        $("#skill_set").val(new_value);
    });

    $(document).on('click','#addskill', function (e) {
        $('#add_skills_display').focus();
        $("#add_skill_form").trigger('reset');
        $('.btn_anchor_wrap').removeClass('selected');
        $('.a_wraper').remove();
        $('#skill_set').val('');
    });

    $(document).on('click', '.overlay', function (e) {
        var html_string = $('#skills-tab .skill_list_section').html();
        $('#skills-tab .skill_list_section').html(html_string);
        $('#skills-tab .skill_list_section .btn_anchor_wrap').addClass('selected');
        $('#addskillmodal').modal('hide');
    });
    var source;
    $("#add_skills_display").autocomplete();
    var manually_added_text = $('#addskill_manually').val();
    $.ajax({
        type: 'get',
        url: base_url + '/skillsautocomplete',
        data: {'textType': manually_added_text},
        success: function (data) {
            source = data;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
 
    $("#add_skills_display").on('keyup', function () {
        var srcs = source;

        var index;
        var existing_array = $('#addskills').val().split(',');
        for (i = 0; i < existing_array.length; i++) {
            index = srcs.indexOf(existing_array[i]);
            if (index > -1) {
                srcs.splice(index, 1);
            }
        }

        $(this).autocomplete({
            open: function (event, ui) {
                if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                    $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
                }
            },
            source: function (request, response) {
                var results = $.ui.autocomplete.filter(srcs, request.term);
                response(results);
            },
            autoFocus: true
        });
    });



    $(document).on('click','#addskillforms', function (e) {
        //case: when user directly clicks on submit with mouse without using enter or comma
        //***starts here
        var input_display_value = $('#add_skills_display').val().trim().toLowerCase();
        var regex_to_remove_script_tags = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
        var match;
        while (match = regex_to_remove_script_tags.exec(input_display_value)) {
            $("#skills_validation_error").text("Script tags are not allowed");
            $('#skills_validation_error').css('display', 'block');
            $('#add_skills_display').val("");
            return false;
        }
        var skills_from_db = $('#skill_from_db').val().trim();
        var final_array = [];
        var array = skills_from_db.split(',');
        var sports = [];
        $.each(array, function (key, value) {
            final_array.push(value.trim().toLowerCase());
        });
      
        if (final_array != '' && input_display_value != '') {
            if (jQuery.inArray(input_display_value, final_array) != -1) {
                $("#skills_validation_error").text("Skill is already added");
                $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                return false;
            }
        }

        var skill_set = $('#addskills').val();
        if (input_display_value != '') {
            $('#addskills').val(skill_set + '' + input_display_value + ',');
        }

        skill_set = $('#addskills').val();
        var currently_display_skills = $('#add_skills_display').val();

        if (skill_set == '') {
            skill_set = currently_display_skills;
        }
        skill_array = skill_set.split(',');
        

        var length = skill_array.length;
        if (skill_set == '') {
            $("#skills_validation_error").text("Please enter a skill");
            $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        }
        skill_array.pop();//for removing the last blank entry
        if (skill_array.length > 50) {
            $(window).scrollTop(500);
            $("#skills_validation_error").text("You can select maximum 50 skills");
            $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else {
            for (i = 0; i < length; i++) {
                if (typeof skill_array[i] != "undefined" && skill_array[i].length > 60)
                {
                    skill_array.splice(i, 1);
                    $('#addskills').val(skill_array.join());
                    $("#skills_validation_error").text("Skill can't be more than 60 characters");
                    $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                   return false;
                }
            }
        }
        $("#skills_validation_error").text('');
        document.getElementById("add_skill_form").submit();
       

    });

    $(document).on('click','.removeskill', function (e) {
        if (!$(this).hasClass('stop_editing')) {
            if (totalToolsAndSkills() === 3) {
                $("#skill_error").text("You must have at least 3 skills and Tools/Technologies.");
                $('#skill_error').css('display', 'block');
                $('#skill_error').fadeIn('fast').delay(2000).fadeOut('fast');
                $('#skills_flag').removeClass("profile-completion-completed-element");
                $('.skills-details').removeClass('hidden');
            }
            var skill_name = $(this).prev('.btnskills').text();
            var id = $(this).attr('id');
            $.ajax({
                type: "GET",
                url: base_url + '/deleteskill',
                data: {id: id},
                success: function (response) {
                    $('#allskills' + id).remove();
                    var newskillarray = $('#skill_from_db').val().split(',');
                    //removing this value from the hidden skill_from_db
                    newskillarray.splice(newskillarray.indexOf(skill_name), 1);
                    $('#skill_from_db').val(newskillarray.join());
                    var totalskills = $('.removeskill').length;
                    if (totalToolsAndSkills() < 3) {
                        $(".total-skills").text(totalToolsAndSkills());
                        $(".total-skills-left").text(3 - totalToolsAndSkills());
                    }
                    if (totalskills == 0) {
                        window.location.href = base_url + '/expert/profile-skills';
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
            e.preventDefault();
        }
    });
    
    $(document).on('click', '.removenewskill', function (e) {

        var id = $(this).attr('id');
        $('#newskills' + id).remove();
        var demo_input1 = $('#addskills').val();
        var split_skills = demo_input1.split(',');
        split_skills.splice(id, 1);
        $('#addskills').val(split_skills.join());

    });
   
    /*Skills js end and Tools js start*/
    $(document).on('click','#addtool', function (e) { 
        $('#add_tools_display').focus();
        $('.nodatatext-tools').hide();
        $("#add_tool_form").trigger('reset');
        $('.btn_anchor_wrap').removeClass('selected');
        $('.a_wraper').remove();
        $('#tool_set').val('');
    });
    $(document).on('click', '.overlay', function (e) {
        var html_string = $('#skills-tab .tool_list_section').html();
        $('#skills-tab .tool_list_section').html(html_string);
        $('#skills-tab .tool_list_section .btn_anchor_wrap').addClass('selected');
        $('#addtoolmodal').modal('hide');
    });
    $('.closetool').on('click', function (e) {
        var attr = $(this).attr('id');
        var detail = attr.split('_');
        var hidden_id = detail[1].replace(/ /g, "_");
        var value = $('#' + hidden_id).val();
        $(this).parent().removeClass('selected');
        if ($(this).parent().hasClass('a_wraper') == true) {
            $(this).parent().remove();
        }

        var skill_set_val = $('#tool_set').val();
        var list = skill_set_val;
        var new_value = list.replace(new RegExp(",?" + value + ",?"), function (match) {
            var first_comma = match.charAt(0) === ',',
                    second_comma;
            if (first_comma && (second_comma = match.charAt(match.length - 1) === ','))
            {
                return ',';
            }
            return '';
        });

        $("#tool_set").val(new_value);
    });
    
    var tools_source;

    var manually_added_text = $('#addskill_manually').val();
    $.ajax({
        type: 'get',
        url: base_url + '/toolsautocomplete',
        data: {'textType': manually_added_text},
        success: function (data) {
            tools_source = data;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });

    $('#add_skills_display').on('autocompleteclose', function (event, ui) {
        var skill_exist_in_source_array = $('#add_skills_display').val();
        if (jQuery.inArray(skill_exist_in_source_array, source) != -1) {
            $('#add_skills_display').val('');
        }
    });
    
      $("#add_tools_display").on('keyup', function () {
        var srcs = tools_source;
        var index;
        var existing_array = $('#addtools').val().split(',');
        for (i = 0; i < existing_array.length; i++) {
            index = srcs.indexOf(existing_array[i]);
            if (index > -1) {
                srcs.splice(index, 1);
            }
        }

        $(this).autocomplete({
            open: function (event, ui) {
                if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                    $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
                }
            },
            source: function (request, response) {
                var results = $.ui.autocomplete.filter(srcs, request.term);
                response(results);
            },
            autoFocus: true
        });
    });
    var add_tools;
    var input_display_value;
    $("#add_tools_display").on('touchstart keyup', function (e) {
        if (e.which == 188 || e.which == 13) {
            var input_display_value = $('#add_tools_display').val().trim().toLowerCase();
            var regex_to_remove_script_tags = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
            var match;
            while (match = regex_to_remove_script_tags.exec(input_display_value)) {
                $(".tools_validation_error").text("Script tags are not allowed");
                $('.tools_validation_error').css('display', 'block');
                $(this).val("");
                return false;
            }
            var skills_from_db = $('#tools_from_db').val();
            var final_array = [];
            if(skills_from_db){
            var array = skills_from_db.split(',');
            $.each(array, function (key, value) {
                final_array.push(value.trim().toLowerCase());
            });  
            }
             var add_tools = $('#addtools').val();
            if (this.value.trim() !== '') {
                if (e.which == 13) {
                    if (this.value.trim().length > 60) {
                        $(".tools_validation_error").text("Tool/Technology can't be more than 60 characters.");
                        $('.tools_validation_error').css('display', 'block');
                        return false;
                    } else {
                        $(".tools_validation_error").text("");
                        $('.tools_validation_error').css('display', 'none');
                    }
                    if (jQuery.inArray(input_display_value, final_array) != -1) {
                        $(".tools_validation_error").text("Tool/Technology is already added.");
                        $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                        return false;
                    }
                    $('#addtools').val(add_tools + '' + input_display_value + ',');
                } else {
                    if (this.value.trim().length > 61) {
                        $(".tools_validation_error").text("Tool/Technology can't be more than 60 characters.");
                        $('.tools_validation_error').css('display', 'block');
                        return false;
                    } else {
                        $(".tools_validation_error").text("");
                        $('.tools_validation_error').css('display', 'none');
                    }
                    var input_display_info = input_display_value.split(',');
                    if (jQuery.inArray(input_display_info[0], final_array) != -1) {
                        $(".tools_validation_error").text("Tool/Technology is already added");
                        $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                        return false;
                    }
                    $('#addtools').val(add_tools + '' + input_display_value);
                }
            }
            addSkillToolSpan('addtools', 'newtools', 'removenewtool');
        }
    });
    
    $('#add_tools_display').on('autocompleteselect', function (event, ui) {
        var list_item = ui.item.value;
        if (list_item.trim().length != 0) {

            input_display_value = list_item.trim();
            var skills_from_db = $('#tools_from_db').val().trim();
            var final_array = [];
            var array = skills_from_db.split(',');
            $.each(array, function (key, value) {
                final_array.push(value);
            });

            add_tools = $('#addtools').val();

            if (input_display_value !== '') {
                if (jQuery.inArray(input_display_value, final_array) != -1) {
                    $(".tools_validation_error").text("Tool/Technology is already added");
                    $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                    return false;
                }
                $('#addtools').val(add_tools + '' + input_display_value + ',');
            }
            addSkillToolSpan('addtools', 'newtools', 'removenewtool');
        }
    });

    $('#add_tools_display').on('autocompleteclose', function (event, ui) {
        var skill_exist_in_source_array = $('#add_tools_display').val();
        if (jQuery.inArray(skill_exist_in_source_array, tools_source) != -1) {
        $('#add_tools_display').val("");
        }
    });
    $(document).on('click','#addtoolsubmit', function (e) {
        //case: when user directly clicks on submit with mouse without using enter or comma
        //***starts here
        var input_display_value = $('#add_tools_display').val().trim().toLowerCase();
        var regex_to_remove_script_tags = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
        var match;
        while (match = regex_to_remove_script_tags.exec(input_display_value)) {
            $(".tools_validation_error").text("Script tags are not allowed");
            $('.tools_validation_error').css('display', 'block');
            $('#add_tools_display').val("");
            return false;
        }
        var skills_from_db = $('#tools_from_db').val().trim();
        var final_array = [];
        var array = skills_from_db.split(',');
        var sports = [];
        $.each(array, function (key, value) {
            final_array.push(value.trim().toLowerCase());
        });
      
        if (final_array != '' && input_display_value != '') {
            if (jQuery.inArray(input_display_value, final_array) != -1) {
                $(".tools_validation_error").text("Tool/Technology is already added");
                $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                return false;
            }
        }

        var skill_set = $('#addtools').val();
        if (input_display_value != '') {
            $('#addtools').val(skill_set + '' + input_display_value + ',');
        }

        skill_set = $('#addtools').val();
        var currently_display_skills = $('#add_tools_display').val();
       
        if (skill_set == '') {
            skill_set = currently_display_skills;
        }
        skill_array = skill_set.split(',');
    
        var length = skill_array.length;
        if (skill_set == '') {
            $(".tools_validation_error").text("Please enter Tools & Technologies");
            $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } 
        skill_array.pop();//for removing the last blank entry
        if (skill_array.length > 50) {
            $(window).scrollTop(500);
            $(".tools_validation_error").text("You can select maximum 50 Tool & Technologies");
            $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
           return false;
        } else {
            for (i = 0; i < length; i++) {
                if (typeof skill_array[i] != "undefined" && skill_array[i].length > 60)
                {
                    skill_array.splice(i, 1);
                    $('#addtools').val(skill_array.join());
                    $(".tools_validation_error").text("Tool/Technology can't be more than 60 characters");
                    $('.tools_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                    return false;  
                }
            }
        }
         $(".tools_validation_error").text('');
         document.getElementById("add_tool_form").submit();
       

    });
    $(document).on('click','.removetool', function (e) {
            if (totalToolsAndSkills() === 3) {
                $("#tools_error").text("You must have at least 3 skills and Tools/Technologies.");
                $('#tools_error').css('display', 'block');
                $('#tools_error').fadeIn('fast').delay(2000).fadeOut('fast');
                e.preventDefault();
                $('#skills_flag').removeClass("profile-completion-completed-element");
                $('.skills-details').removeClass('hidden');
            }
            var skill_name = $(this).prev('.btntools').text();
            var id = $(this).attr('id');
            $.ajax({
                type: "GET",
                url: base_url + '/deleteskill',
                data: {id: id},
                success: function (response) {
                    $('#allskills' + id).remove();
                    var newskillarray = $('#tools_from_db').val().split(',');
                    //removing this value from the hidden skill_from_db
                    newskillarray.splice(newskillarray.indexOf(skill_name), 1);
                    $('#tools_from_db').val(newskillarray.join());
                    var totalskills = $('.removetool').length;
                    if (totalToolsAndSkills() < 3) {
                        $(".total-skills").text(totalToolsAndSkills());
                        $(".total-skills-left").text(3 - totalToolsAndSkills());
                    }
                    if (totalskills == 0) {
                        window.location.href = base_url + '/expert/profile-skills';
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
            e.preventDefault();
       
    });
     $(document).on('click', '.removenewtool', function (e) { 
        var id = $(this).attr('id');
        $('#newtools'+ id).remove();
        var demo_input1 = $('#addtools').val();
        var split_skills = demo_input1.split(',');
        split_skills.splice(id, 1);
        $('#addtools').val(split_skills.join());

    });
   
    
    /*Add Tools js end*/
    if (document.getElementById("uploadBtn") != null) {
        document.getElementById("uploadBtn").onchange = function () {
            var str = this.value;
            var filename = str.replace(/^.*\\/, "");
            document.getElementById('uploadFile').innerHTML = filename;
        };

    }
    var rate_variable = $("#rate_variable").val();
    if (rate_variable != '') {
        $("#rate_variable").addClass('button-black');
    }

    //----------------URL rewriting for different tab of Expert profile----------------
    if (window.location.href.indexOf("expert/profile-summary") > -1) {
        $('#summary_a').click();
    }
    if (window.location.href.indexOf("expert/profile-skills") > -1) {
        $('#skill_a').click();
    }
    if (window.location.href.indexOf("expert/work-history") > -1) {
        $('#workhistory_a').click();
    }
    if (window.location.href.indexOf("expert/profile-education") > -1) {
        $('#education_a').click();
    }

    $('.expert-profile-view #summary_a').on('click',function () {
        var new_url = base_url + '/expert/profile-summary';
        history.pushState({}, null, new_url);
        $('[data-id=summary-right]').click();
    });
    $('.expert-profile-view #skill_a').on('click',function () {
        var new_url = base_url + '/expert/profile-skills';
        history.pushState({}, null, new_url);
        $('[data-id=skill-right]').click();
    });
    $('.expert-profile-view #workhistory_a').on('click',function () {
        var new_url = base_url + '/expert/work-history';
        history.pushState({}, null, new_url);
        $('[data-id=workhistory-right]').click();

    });
    $('.expert-profile-view #education_a').on('click',function () {
        var new_url = base_url + '/expert/profile-education';
        history.pushState({}, null, new_url);
        $('[data-id=education-right]').click();
    });
    $(document).on('click','.stop_editing',function () {
        $('#confirm_cancellation').modal('show');
        return false;
    });
    $(document).on('click','#yesediting', function () {
        $(document).find('#myTab li.active').trigger('click');
        $(document).find('#editsummary').trigger('click');
        if ( $(document).find('#addskills').val() != "") {
            $(document).find('#addskillforms').trigger('click');
        } else {
            $(document).find('div.active div:visible input[value="Save"]:visible').trigger('click');
        }
        $(document).find('div.active div:visible button[name="addcollege"]:visible').trigger('click');
    });
    

    $(document).on('click','.edit-profile-section', function () {
        if ($(this).hasClass('profileUnComplete')) {

            $('#profile_not_completed').show();
            $('#profile_not_completed').fadeOut(5000);
        } else {
            $('#expert_profile_city').val($('.view_location').html().trim())
            $(document).find('#remote_work').val($('#view_location_preference').attr('data-remote-id'))
            $(document).find('#remote_work').selectpicker('refresh');
            $('#profile_not_completed').hide();
            $('.edittextarea').addClass('stop_editing');
            addStopEditingClass();
        }
    });
    $(document).on('click','#addskill', function () {
        addStopEditingClass();
    });
    $(document).on('click','#addtool', function () {
        addStopEditingClass();
    });
    $(document).on('click','#add_lang_button', function () {
        $('#userlanguage').focus();
        if ($('.edit-profile-section').hasClass('profileUnComplete')) {
            $('#profile_not_completed').show();
            $('#profile_not_completed').fadeOut(5000);
        } else {
            addStopEditingClass();
        }
    });
    $(document).on('click','.edit_lang_button', function () {
        addStopEditingClass();
    });
    $(document).on('click','.edit_work_history', function () {
        addStopEditingClass();
    });
    $(document).on('click','.add-expert-position', function () {
        addStopEditingClass();
    });
    $(document).on('click','.edit_college', function () {
        addStopEditingClass();
    });
    $(document).on('click','#addclgbuttom', function () {
        addStopEditingClass();
    });

    $(document).on('click','.edit_course', function () {
        addStopEditingClass();
    });

    $(document).on('click','.addCourse', function () {
        addStopEditingClass();
    });
    $(document).on('click','.transparent-bg-btn', function () {
        $('.edit-profile-section').removeClass('stop_editing');
        $('.editprofilebio').removeClass('stop_editing');
        $('#addclgbuttom').removeClass('stop_editing');
        $('.add-expert-position').removeClass('stop_editing');
        $('#addskill').removeClass('stop_editing');
        $('#addtool').removeClass('stop_editing');
        $('#add_lang_button').removeClass('stop_editing');
        $('.edit_lang_button').removeClass('stop_editing');
        $('#myTab li').not('.active').find('a').attr("data-toggle", "tab");
        $('.removeskill').removeClass('stop_editing');
        $('#skill_a').removeClass('stop_editing');
        $('#workhistory_a').removeClass('stop_editing');
        $('#education_a').removeClass('stop_editing');
        $('a[href="#experience-tab"]').removeClass('stop_editing');
        $('.addCourse').removeClass('stop_editing');
        $('.edit_work_history').removeClass('stop_editing');
        $('.edit_college').removeClass('stop_editing');
        $('.edit_course').removeClass('stop_editing');
        $('.stop_editing').addClass('edit_icon');
        $('.stop_editing').removeClass('stop_editing');
    });
    $(document).on('click','#noediting', function () {
        window.location.href = base_url + '/expert/profile-summary';
        $('.edit-profile-section').removeClass('stop_editing');
        $('.editprofilebio').removeClass('stop_editing');
        $('#addclgbuttom').removeClass('stop_editing');
        $('.add-expert-position').removeClass('stop_editing');
        $('#addskill').removeClass('stop_editing');
        $('#addtool').removeClass('stop_editing');
        $('#add_lang_button').removeClass('stop_editing');
        $('.edit_lang_button').removeClass('stop_editing');
        $('#myTab li').not('.active').find('a').attr("data-toggle", "tab");
        $('.removeskill').removeClass('stop_editing');
        $('#skill_a').removeClass('stop_editing');
        $('#workhistory_a').removeClass('stop_editing');
        $('#education_a').removeClass('stop_editing');
        $('a[href="#experience-tab"]').removeClass('stop_editing');
        $('.addCourse').removeClass('stop_editing');
        $('.edit_work_history').removeClass('stop_editing');
        $('.edit_college').removeClass('stop_editing');
        $('.edit_course').removeClass('stop_editing');
        $('.stop_editing').addClass('edit_icon');
        $('.stop_editing').removeClass('stop_editing');
        $('.transparent-bg-btn').trigger('click');
    });
    $("#edutotime").datepicker({
        maxDate: new Date()
    });
    $(document).on('click touchstart', "#addskill", function () {
        autosize(document.querySelectorAll('textarea.addskillinput'));
        $(document).find('#addskills').height(20);
    });

    $("textarea#bio").on('focus',function () {
        var element_height = this.scrollHeight;
        $(this).css('height', element_height + "px");
    });
    $(".edit-work-description").on('click',function () {
        var data_id = $(this).attr('data-id');
        setTimeout(function () {
            $("textarea#empdescription").trigger('focus');
            $('#employee_title' + data_id).trigger('focus');
        });
    });
    $(".cross-work-description").off().on('click',function () {

        if (confirm('Do you want to delete this work experience?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/deleteWorkHistory',
                data: {id: id},
                success: function (response) {
                    window.location.reload();
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
            return false;
        } else {
            return false;
        }
    });
        $(".cross-college-university").on('click',function () {

        if (confirm('Do you want to delete this College/University information?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/deleteCollegeUniversity',
                data: {id: id},
                success: function (response) {                    
                    window.location.reload();
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
            return false;
        } else {
            return false;
        }
    });
      $(".crossCourseCertificates").off().on('click',function () {

        if (confirm('Do you want to delete this Certificates/Courses information?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/deleteCertificateAndCourses',
                data: {id: id},
                success: function (response) {                    
                    window.location.reload();
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
            return false;
        } else {
            return false;
        }
    });
    $("textarea#empdescription").on('focus',function () {
        var element_height = this.scrollHeight;
        $(this).css('height', element_height + "px");
    });
    $(".add-expert-position").on('click',function () {
        setTimeout(function () {
            $("textarea#eempdescription").trigger('focus');
            $("#employee_title").trigger('focus');
            $("html, body").animate({ scrollTop: 100 }, "fast");
        });
    });
    $("textarea#eempdescription").on('focus',function () {
        var element_height = this.scrollHeight;
        $(this).css('height', element_height + "px");
    });
    $('#is_current').on('click',function () {
        if ($(this).prop("checked") == true) {
            var employee_start_month = $("#employee_start_month").val().trim();
            var employee_start_year = $("#employee_start_year").val().trim();
            if (employee_start_month == "" && employee_start_year == "") {
                $("#start_month_error").text("Please enter start date");
                $('#start_month_error').fadeIn('fast').delay(2000).fadeOut();
                return false;
            }
            $("#empmonth").hide();
            $("#empyear").hide();
            document.getElementById("someplace").innerHTML = "Present";
        } else if ($(this).prop("checked") == false) {
            $("#empmonth").show();
            $("#empyear").show();
            document.getElementById("someplace").innerHTML = "";
        }
    });
    $('.edit_work_history').on('click',function () {
        var form_id = $(this).attr('data-id');
        var employee_current_form = $("#empcurrent" + form_id).val();
        if (employee_current_form == 1) {
            $(".editempmonth").hide();
            $(".editempyear").hide();
            $("#hidden_eis_current" + form_id).val('1');
            document.getElementById("someplace1" + form_id).innerHTML = "Present";
        }
    });
    $('.edit_current').on('click',function () {
        var form_id = $(this).attr('id');
        if ($(this).prop("checked") == true) {
            var e_employee_start_month = $("#edit_employee_start_month-" + form_id).val().trim();
            var e_employee_start_year = $("#edit_employee_start_year-" + form_id).val().trim();
            if (e_employee_start_month == "" && e_employee_start_year == "") {
                $("#start_month_error-" + form_id + "").text("Please enter start date");
                $("#start_month_error-" + form_id + "").fadeIn('fast').delay(2000).fadeOut();
                return false;
            }
            $('#hidden_eis_current' + form_id).val(1);
            $(".editempmonth").hide();
            $(".editempyear").hide();
            document.getElementById("someplace1" + form_id).innerHTML = "Present";
        } else {
            $('#hidden_eis_current' + form_id).val(0);
            $(".editempmonth").show();
            $(".editempyear").show();
            document.getElementById("someplace1" + form_id).innerHTML = "";
        }
    });
    $(document).on('click', "#addskill",function () {
        var added_skill_value = $("#addtool").val();
        if (added_skill_value = "Add skill") {
            $(".nodatatext-skill").hide();
        }
    });
    $("#addtool").on('click',function () {
        var added_skill_value = $("#addtool").val();
        if (added_skill_value = "Add Tools & Tech") {
            $(".nodatatext-tool").hide();
        }
    });
    $(".add-expert-position").on('click',function () {
        var added_work_value = $(".add-expert-position").val();
        if (added_work_value = "Add position") {
            $(".nodatatext").hide();
        }
    });
    $("#addclgbuttom").on('click',function () {
        var added_college_value = $("#addclgbuttom").val();
        if (added_college_value = "Add college/university") {
            $(".nodatatext").hide();
        }
    });
    $(".addCourse").on('click',function () {
        var added_course_value = $(".addCourse").val();
        if (added_course_value = "Add certificate/course") {
            $(".nodatatext").hide();
        }
    });
    $(".cancel-skill").on('click',function () {
        var added_skill_value = $("#addskill").val();
        if (added_skill_value = "Add skill") {
            $(document).find(".nodatatext").show();
        }
    });
    $(".cancel-work-history").on('click',function () {
        var added_work_value = $(".add-expert-position").val();
        if (added_work_value = "Add position") {
            $(".nodatatext").show();
        }
    });
    $(".cancel-edit-college").on('click',function () {
        var added_college_value = $("#addclgbuttom").val();
        if (added_college_value = "Add college/university") {
            $(".nodatatext").show();
        }
    });
    $(".cancel-edit-course").on('click',function () {
        var added_course_value = $(".addCourse").val();
        if (added_course_value = "Add certificate/course") {
            $(".nodatatext").show();
        }
    });
   
        $('.select-job').on('click', function (e) {
            $("div.select-job-title").removeClass("select-job-title");
            $(this).addClass('select-job-title');
        });
     
        /*share profile comment*/
        $(document).on('click','#share-profile-btn',function (e) {
            var user_id = $('#share-profile-btn').attr('data-user');
            var buyer_id = $('#share-profile-btn').attr('data-buyer');
            var url = base_url + '/buyer/messages';
            var final_url = url + '?sender_id=' + buyer_id + '&receiver_id=' + user_id;
            $.ajax({
                type: 'get',
                url: base_url + '/checkDefaultUser',
                data: {user_id: user_id, buyer_id: buyer_id},
                contentType: false,
                success: function (result) {
                    var final_location = final_url + '&comm_id=' + result;
                    window.location.href = final_location;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
        });   
        
    $('select.selectpicker').on('change', function () {
        var selected = $('.selectpicker option:selected').val();
        $(this).parent().find(".dropdown-toggle").addClass('textcolorchange')
    });
    $('#get_started').on('click', function () {
        var id = $(this).attr('data-status');
        $.ajax({
            'type': 'post',
            'url': base_url + '/updateLoggedInStatus',
            'data': {'id': id},
            success: function (response) {
                if (response == 1) {
                    window.location.href = base_url + '/';
                }
            }
        });

    });



});

