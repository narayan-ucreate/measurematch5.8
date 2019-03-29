var location_selected = 0;
$(document).ready(function () {
    if ($(window).width() < 1024) {
        $('body').addClass('buyer-mobile-display');
    }
    var skill_array = [];
    var tool_array = [];
    var skills_source;
    var tools_source;
    var TWO_THOUSAND = 2000;
    var attached_files = [];
    var input_parent='';
    function removeByValueFromArray(array, element) {
        const index = array.indexOf(element.toLowerCase());
        if(index >=0){
         array.splice(index, 1);}
         return array;
    }
    function scroll_to_error(selector) {
        $('html, body').animate({
            scrollTop: ($(selector).offset().top - 60)
        }, 1000);
    }
    if (document.getElementById("attachments") != null) {
        document.getElementById("attachments").onchange = function () {
         var uploaded = document.getElementById('attachments');
             attached_files=uploaded.files;
             document.getElementById('uploaded_files').innerHTML = '';
            if (attached_files.length > 0) {
             for (var i = 0; i <= attached_files.length - 1; i++) {
                 var file_name = attached_files.item(i).name;
                 document.getElementById('uploaded_files').innerHTML =
                         document.getElementById('uploaded_files').innerHTML + '<br /><div class="remove-attachement">\n\
                        <a href="javascript:void(0);" class="remove_button" title="Remove">X</a>\n\
                        <span class="attached-file full-width"> ' + file_name+'</span</a></div>';
                 if (file_name.split('.').pop() == 'exe') {
                     document.getElementById('attachments').innerHTML = '';
                     document.getElementById('uploaded_files').innerHTML = '';
                     $('#upload_file_error').text('.exe files are not allowed').fadeIn('fast').delay('2000').fadeOut('fast');
                     return false;
                 }
             }

         }
     }
 }
    $(document).on('click', '.remove-attachement .remove_button', function () {
             $(this).parent().remove();
             $("#uploaded_files").html("");
             $("#attachments").val("");
             $("#attachments_from_db").val("");
    });
    $("#add_skills_manually,#add_tools_manually").autocomplete();
    var skills_text = $('#add_skills_manually').val();
    $.ajax({
        type: 'get',
        url: base_url + '/skillsautocomplete',
        data: {'textType': skills_text},
        success: function (data) {
            skills_source = data;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    var tools_text = $('#add_tools_manually').val();
    $.ajax({
        type: 'get',
        url: base_url + '/toolsautocomplete',
        data: {'textType': tools_text},
        success: function (data) {
            tools_source = data;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    if($('#manual_skills').val()){
    var skills_from_db = $('#manual_skills').val().split(",");
    $.each(skills_from_db,function(i){
       skill_array.push(skills_from_db[i].trim().toLowerCase());
    });
    }
    if($('#manual_tools').val()){
    var tools_from_db = $('#manual_tools').val().split(",");
    $.each(tools_from_db,function(i){
       tool_array.push(tools_from_db[i].trim().toLowerCase());
    });
    }
    $("#add_skills_manually, #add_tools_manually").on('keydown', function (event) {
        var selector_id=(this.id=="add_tools_manually")?"tools":"skills";
        var validation_text=(this.id=="add_tools_manually")?"Tool/Tech":"Skill";
        var source=(this.id=="add_tools_manually")?tools_source:skills_source;
        var keyCode = event.keyCode || event.which;
        $(this).autocomplete({
            open: function (event, ui) {
                if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                    $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
                }
            },
            source: function (request, response) {
                var results = $.ui.autocomplete.filter(source, request.term);
                response(results);
            },
            appendTo: '.add-'+selector_id+'-button-block',
            classes: {
                "ui-autocomplete": "post-project-autocomplete",
            }
        });
         var added_skill=(this.id=="add_tools_manually")?tool_array:skill_array;
         if (keyCode === 13 || keyCode === 188) {
            var value = $(this).val().replace(/,+$/, '');
            var regex_to_remove_script_tags = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
            var match;
            while (match = regex_to_remove_script_tags.exec(value)) {
                $(".validation_error_add_"+selector_id).text("Script tags are not allowed").addClass('has_error');
                $(".validation_error_add_"+selector_id).fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
                $(this).val("");
                return false;
            }
            if (jQuery.inArray(value.trim().toLowerCase(), added_skill) != -1) {
                $(".validation_error_add_"+selector_id).text(validation_text+" is already added.").addClass('has_error');
                $(".validation_error_add_"+selector_id).fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
                $(this).val("");
                return false;
            } else if (value.trim().length != 0 && value.trim().length < 61) {
                $(".add-more-"+selector_id).append('<span class="skill-button">' + value.trim() + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
                $(this).val("");
                $(this).blur();
                $(this).focus();
                added_skill.push(value.trim().toLowerCase());
            } else if (value.trim().length > 60) {
                $(".validation_error_add_"+selector_id).text(validation_text+" can't be more than 60 characters.").addClass('has_error');
                $(".validation_error_add_"+selector_id).fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
                $(this).val("");
                return false;
            }
            event.preventDefault();
        }
    });
   $('#add_skills_manually ,#add_tools_manually').on('autocompleteselect', function (event, ui) {
        var selector_id=(this.id=="add_tools_manually")?"tools":"skills";
        var validation_text=(this.id=="add_tools_manually")?"Tool/Tech":"Skill";
        var value = ui.item.value;
        var added_skill=(this.id=="add_tools_manually")?tool_array:skill_array;
        if (value.trim().length != 0) {
            if (jQuery.inArray(value.trim().toLowerCase(), added_skill) != -1) {
               $(".validation_error_add_"+selector_id).text(validation_text+" is already added.").addClass('has_error');
                $(".validation_error_add_"+selector_id).fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
                $(this.id).val("");
                return false;
            } else {
                $(".add-more-"+selector_id).append('<span class="skill-button">' + value + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
                var input_display_value = value.trim();
                var add_skills = $('#manual_'+selector_id).val();
                $('#manual_'+selector_id).val(add_skills + '' + input_display_value + ',');
                if(selector_id=='tools'){ tool_array.push(value.trim().toLowerCase()); }else{ skill_array.push(value.trim().toLowerCase());}
            }
        }
        if (event.keyCode === $.ui.keyCode.TAB) {
            event.preventDefault();
        }
        $('.add-'+selector_id+'-button-block').val("");
    });
    $('#add_skills_manually').on('autocompleteclose', function (event, ui) {
        if (jQuery.inArray($('#add_skills_manually').val(), skills_source) != -1) {
            $('#add_skills_manually').val('');
        }
    });
    $('#add_tools_manually').on('autocompleteclose', function (event, ui) {
        if (jQuery.inArray($('#add_tools_manually').val(), tools_source) != -1) {
            $('#add_tools_manually').val('');
        }
    });
    $(document).on('click', '.skill-button .black_cross_link', function (e) {
         if($(this).parents('.add-more-tools').length){
           tool_array = removeByValueFromArray(tool_array, $(this).parent().text());
          }else{
           skill_array = removeByValueFromArray(skill_array, $(this).parent().text());
          }
          $(this).parents('.skill-button').remove();
     });

    if(!$('#project_id').val()){
    $('#end_date').datetimepicker({
        ignoreReadonly: true,
        format: 'DD-MM-YYYY',
        minDate: new Date(),
        useCurrent: true
    });
    }else{
        var start_date = $('#default_end_date').val();
        var date = new Date();
        var from = start_date.split("-");
        var day = date.getDate();
        var month_index = date.getMonth();
        var year = date.getFullYear();
        var start_min_date = moment(new Date((month_index + 1) + "/" + (day) + "/" + (year)));
        var start_default = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));
        if (start_min_date >= start_default) {
            var start_default = start_min_date;
        }
        $('#end_date').datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            minDate: start_min_date,
            defaultDate: start_default,
        });
       
    updateDate('default_visibility_date', 'project_visibility_date');
    }
 $("#add_office_location").on('keyup', function (e) {
    var location = document.getElementById('add_office_location').value;
    if (e.keyCode === 13 || e.ctrlKey || location === '') {
      $("#office_location_tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#office_location_tags"));
            return false;
        }
    }
    if((e.keyCode >= 65 && e.keyCode <= 90 && !e.ctrlKey) 
            || e.keyCode === 8 
            || e.keyCode === 46){
        location_selected = 0;
        findLocation(location, $("#office_location_tags"));
    }   
});
$(document).on('click', '.dropdown span', function () {
    location_selected = 1;
     $('#add_office_location').val($(this).text());
     $('#office_location_tags').hide();
});
$('input[type=radio][name=rate_variable]').on('change', function() {
     switch($(this).val()) {
        case 'fixed':
        $("#project_budget_input .input-group").show();
        $("#daily_budget_input .input-group").hide();
           break;
        case 'daily_rate':
            $("#project_budget_input .input-group").hide();
            $("#daily_budget_input .input-group").show();
             break;
         case 'negotiable':
            $("#project_budget_input .input-group, #daily_budget_input .input-group").hide();
           break;
     }
});
    $('#currency').on('change', function () {
        var currency = $(this).val();
        var symbol = currency  == 'GBP' ? '£' : currency == 'EUR' ? '€' : '$';
        $('.currency-hint').text($(this).val()+' '+symbol);
    });
$('input[type=checkbox][name=hide_company_name]').on('change', function() {
       if ($(this).is(':checked')) {
           $("#type_of_org_block").show();
        }else{
        $("#type_of_org_block").hide();
        $("#type_of_organization").val($(this).attr('default_type'));
        $("#type_of_organization").selectpicker('refresh');
    }
});
var submit_project_validated_flag=0;
$('#submit_project').on('click', function (event) {
    if (!$(this).hasClass('disabled')) {
        $(this).addClass('disabled')
        var error_count = 0;
        var title = $("#title").val().trim();
        var description = $("#description").val().trim();
        var deliverable_value = [];
        var hide_company_name = $("#hide_company_name").val();
        var work_location = $("input[type=radio][name=work_location]:checked").length;
        var rate_variable = $("input[type=radio][name=rate_variable]:checked").val();
        var office_location = $("#add_office_location").val();
        var end_date = $("input[name=end_date]").val();
        var project_duration = $("#project_duration").val();
        var budget_approval_status = $("#budget_approval_status").val();
        var hide_company_name = $('input[type=checkbox][name=hide_company_name]').is(':checked');
        if($("#is_admin_edit").val()){ input_parent='.form-group'; }else{ input_parent='.input-box'; }
        if (title == "")
        {
            $("#title_validation_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        if (description == "")
        {
            $("#description_validation_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        $('.deliverables').each(function () {
            if ($(this).val().trim() != '') {
                deliverable_value.push($(this).val());
            }
        });
        if (deliverable_value.length == 0) {
            error_count++;
            $("#deliverables_validation_error").text("This field is required to submit your Project brief").addClass('has_error')
        }
        if(hide_company_name== 1){
            var type_of_organization = $("#type_of_organization").val();
            if (type_of_organization == "")
            {
                $("#type_of_org_validation_error").text("This field is required to submit your Project brief").addClass('has_error');
                error_count++;
            }
        }
        if (work_location==0)
        { $("#work_location_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        if (office_location == "")
        {
            $("#office_location_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        } else if (location_description.length !== 0 && location_description.indexOf($("#add_office_location").val()) <= -1) {
            $('#office_location_tags').hide();
            $("#office_location_error").text('Please choose a location from the dropdown list of choices').addClass('has_error');
            $('#add_office_location').val('');
            error_count++;
        }
        if (rate_variable == "")
        {  $("#rate_variable_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }else{
            if(rate_variable =='fixed' && ($('input[name=project_budget]').val() =="")){
                $("#rate_variable_error").text("This field is required to submit your Project brief").addClass('has_error');
                error_count++;
            }else if(rate_variable =='fixed' && ($('input[name=project_budget]').val().replace(/\D/g, "") < 1000)){
                $("#rate_variable_error").text("Projects cannot be posted if they are under the value of £1000").addClass('has_error');
                error_count++;
            }else if(rate_variable =='daily_rate' && $('#daily_project_budget').val() ==""){
                $("#rate_variable_error").text("This field is required to submit your Project brief").addClass('has_error');
                error_count++;
            }else if(rate_variable =='daily_rate' && ($('#daily_project_budget').val().replace(/\D/g, "") < 1)){
                $("#rate_variable_error").text("Projects cannot be posted if day rate is under the value of $1").addClass('has_error');
                error_count++;
            }
        }
        if (end_date == "")
        {
            $("#end_date_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        if (project_duration == "")
        {
            $("#project_duration_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        if (budget_approval_status == "")
        {
            $("#budget_approval_status_error").text("This field is required to submit your Project brief").addClass('has_error');
            error_count++;
        }
        var manual_skills = $('.add-more-skills').find('span');
        var all_skills = [];
        if (manual_skills) {
            $(manual_skills).each(function (i, e) {
                all_skills.push($(this).text().trim());
            });
            $('#manual_skills').val(all_skills);
        }
        var manual_tools = $('.add-more-tools').find('span');
        var all_tools = [];
        if (manual_tools) {
            $(manual_tools).each(function (i, e) {
                all_tools.push($(this).text().trim());
            });
            $('#manual_tools').val(all_tools);
        }
        if (error_count > 0) {
            $(this).removeClass('disabled')
            var scroll_to=$('.has_error:visible:first').parents(input_parent).find('label');
            scroll_to_error(scroll_to);
            var selector = $(".has_error").parents(input_parent);
            selector.find('textarea').css("border-color", "#EB1653");
            $('textarea.deliverables').not(':first').css("border-color", "#DBDBDB");
            selector.find('input').css("border-color", "#EB1653");
            selector.find('.select-dropdown-style .btn-default').css("border-color", "#EB1653");
            return false;
        } else {
            $(this).removeClass('disabled')
            if ($('#project_id').length) {
                $('#submit_project_form').submit();
            } else {
                $('#wrapper').removeClass('active');
                $("#submit_project_panel").hide();
                setTimeout(function(){
                    $("#code_of_conduct_panel").show();
                }, 100);
                submit_project_validated_flag=1;
                event.preventDefault();
                return false;
            }
        }
    } else {
        $(this).addClass('disabled')
    }
});

$('#code_of_conduct_submit_project').on('click', function (e) {
    if (!$(this).hasClass('disabled')) {
        $(this).addClass('disabled')
        if(submit_project_validated_flag){
            $('#submit_project_form').submit();
        } else {
            $("#submit_project_panel").show();
            $("#code_of_conduct_panel").hide();
        }
    } else {
        $(this).addClass('disabled')
    }

});
$('#go_back_to_submit_project').on('click', function (e) {
    submit_project_validated_flag=false;
    $('#wrapper').addClass('active');
    $("#submit_project_panel").show();
    $("#code_of_conduct_panel").hide();
    scroll_to_error($('body'));
});

$(document).on('click focus', '#submit_project_form input, #submit_project_form textarea, #submit_project_form select,.select-dropdown-style .btn-default', function (event) {
    $(this).parents(input_parent).find('.error-message').text("").removeClass("has_error");
    $(this).css("border-color","#DBDBDB")
});
$(document).on('keyup', '#project_budget input ,#daily_project_budget', function (event) {
       var rate = $(this).val().trim();
       if (event.which >= 37 && event.which <= 40)
           return;
       $(this).val(function (index, value) {
           formated_rate = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return formated_rate;
       });
   });
$(document).on('keyup', '#project_budget', function (event) {
       var rate = $(this).val().trim();
        if (event.which >= 37 && event.which <= 40)
           return;
        $(this).val(function (index, value) {
           formated_rate = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
           return formated_rate;
       });
   });
$('.help-info-icon span').each(function() {
        var height = $(this).height();
      $(this).css("margin-top",-(height/2));
});
});