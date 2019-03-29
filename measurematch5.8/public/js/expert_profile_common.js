$("#add_tools_display").autocomplete();
$('#add_skills_display').on('autocompleteselect', function (event, ui) {
    var list_item = ui.item.value;
    if (list_item.trim().length != 0) {
        input_display_value = list_item.trim();
        var skills_from_db = $('#skill_from_db').val().trim();
        var final_array = [];
        var array = skills_from_db.split(',');
        var sports = [];
        $.each(array, function (key, value) {
            final_array.push(value);
        });

        add_skills = $('#addskills').val();

        if (input_display_value !== '') {
            if (jQuery.inArray(input_display_value, final_array) != -1) {
                $("#skills_validation_error").text("Skill is already added.");
                $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                return false;
            }
            $('#addskills').val(add_skills + '' + input_display_value + ',');
        }
        addSkillToolSpan('addskills', 'newskills', 'removenewskill');
    }
});



var add_skills;
var input_display_value;
$("#add_skills_display").on('touchstart keyup', function (e) {

    if (e.which == 188 || e.which == 13) {
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
        var add_skills = $('#addskills').val();
        if (this.value.trim() !== '') {
            if (e.which == 13) {
                if (this.value.trim().length > 60) {
                    $("#skills_validation_error").text("Skill can't be more than 60 characters");
                    $('#skills_validation_error').css('display', 'block');
                    return false;
                } else {
                    $("#skills_validation_error").text("");
                    $('#skills_validation_error').css('display', 'none');
                }
                if (jQuery.inArray(input_display_value, final_array) != -1) {
                    $("#skills_validation_error").text("Skill is already added.");
                    $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                    return false;
                }
                $('#addskills').val(add_skills + '' + input_display_value + ',');
            } else {
                if (this.value.trim().length > 61) {
                    $("#skills_validation_error").text("Skill can't be more than 60 characters");
                    $('#skills_validation_error').css('display', 'block');
                    return false;
                } else {
                    $("#skills_validation_error").text("");
                    $('#skills_validation_error').css('display', 'none');
                }
                var input_display_info = input_display_value.split(',');
                if (jQuery.inArray(input_display_info[0], final_array) != -1) {
                    $("#skills_validation_error").text("Skill is already added");
                    $('#skills_validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
                    return false;
                }
                $('#addskills').val(add_skills + '' + input_display_value);
            }
        }
        addSkillToolSpan('addskills', 'newskills', 'removenewskill');
    }
});


function addSkillToolSpan(all_skill_toool, primary_id, remove_class){
    var total_tools_added = $('#' + all_skill_toool).val();
    var split_skills = total_tools_added.split(',');

    var newArray = split_skills.filter(function (v) {
        return v !== ''
    });
    var html = '';
    $.each(newArray, function (key, value) {
        html += '<span class="all-skills-tools" id="' + primary_id + key + '"><div class="btn_anchor_wrap tool-technologies-tags"><button class="btnskills capital">' + value + '</button><a href="javascript:void(0)" class="btnclose ' + remove_class + '" id="' + key + '" attr=""> <img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross"></a></div></span>';
    });
    if(all_skill_toool == 'addskills') {
        $('#new_added_skills').html(html);
        $('#add_skills_display').val("");
    } else {
        $('#new_added_tools').html(html);
        $('#add_tools_display').val("");
    }

}