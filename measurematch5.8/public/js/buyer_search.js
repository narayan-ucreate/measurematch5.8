$(window).load(function() {
    $('#loading_results_div').hide();
    $('.main_search_section_result').show();
    if($('#main_search_section_load_more_div_result').length){
        $('#main_search_section_load_more_div_result').show();
    }
});
$(document).ready(function(){
    if ($(window).width() < 1024) {
        $('body').addClass('buyer-mobile-display');
    }
    if($('#search_key').val() != ''){
        $('#search_key_clear').show();
    }
    if($('#location').val() != ''){
        $('#location_clear').show();
    }
    if($('#remote_option').val() != 0){
        $('#remote_option_clear').show();
    }
    $('#main_search_section_load_more_result').click(function(){
        var current_element = $(this);
        current_element.attr('disabled', true);
        var number_of_experts_listed = $(".expert-profile-pic:visible").length;
        var search_keyword = $("#search_key").val();
        var location = $("#location").val();
        var remote_option = $("#remote_option").val();
        var response = updateMatchExpertsDiv({listed_experts_number: number_of_experts_listed, search:search_keyword,
        location:location, selectremoteoption:remote_option});
        if(response.view !== ''){
            current_element.attr('disabled', false);
            $('.main_search_section_result').append(response.view);
        }
        if(response.show_load_more_button === false){
            $('#main_search_section_load_more_div_result').hide();
        }
    });
    
    $('#match_results').click(function(){
        var search_keyword = $("#search_key").val();
        var location = $("#location").val();
        var remote_option = $("#remote_option").val();
        var response = updateMatchExpertsDiv({search:search_keyword, location:location, selectremoteoption:remote_option});
        if(response !== ''){
            if (response.view !== '') {
                $('.main_search_section_result').html(response.view);
            }
            if($('#main_search_section_load_more_div_result').length){
                $('#main_search_section_load_more_div_result').show();
            }
            if (response.show_load_more_button === false) {
                $('#main_search_section_load_more_div_result').hide();
            }
        }
    });
    
    $('#saved_expert_section_load_more').click(function(){
        var current_element = $(this);
        current_element.attr('disabled', true);
        var number_of_experts_listed = $(".expert-profile-pic:visible").length;
        if($('#project_list:visible').length){
            var selected_project = $('#project_list').val();
        } else {
            var selected_project = -1;
        }
        
        var url;
        if(selected_project == -1){
            url = base_url + '/buyer/savedexpertslisting';
        } else {
            url = base_url + '/buyer/savedexpertslisting?post_job_id=' + selected_project;
        }
        $.ajax({
            type: 'get',
            url: url,
            data: {listed_experts_number: number_of_experts_listed},
            success: function (response) {
                if(response.view !== ''){
                    $('.saved_experts_listing_section').append(response.view);
                    current_element.attr('disabled', false);
                }
                if(response.show_load_more_button === false){
                    $('#saved_expert_section_load_more_div').hide();
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $(document).on('click', '.save_expert', function(){
        var current_element = $(this);
        var expert_id = $(this).attr('user_id');
        current_element.next('.save_expert_tooltip').hide();
        $.ajax({
            type: 'get',
            url: base_url + '/buyer/activeprojectslisting',
            data: {expert_id: expert_id},
            success: function (response) {
                if(response.saved){
                    $('#total_saved_experts').text(response.all_records);
                    current_element.addClass('save-expert-icon');
                }
                if(response.jobs_count>0){
                    $('.active_project_listing').html('');
                    $('#' + expert_id).append(response.view);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $(document).on('click', '.remove_expert', function(){
        var current_element = $(this);
        var expert_id = current_element.attr('user_id');
        var post_job_id = current_element.attr('post_job_id');
        var selected_project = $('#project_list').val();
        $.ajax({
            type: 'get',
            url: base_url + '/buyer/unsaveexpert',
            data: {expert_id: expert_id, post_job_id: post_job_id, selected_project: selected_project},
            success: function (response) {
                if(response.result=='unsaved'){
                    $('#total_saved_experts').text(response.all_records);
                    listSavedExperts(selected_project);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $(document).on('click', '.save_to_project', function(){
        var current_element = $(this);
        var expert_id = current_element.attr('expert_id');
        var job_id = current_element.val();
        $.ajax({
            type: 'get',
            url: base_url + '/saveexperttoproject',
            async: false,
            data: {expert_id: expert_id, job_id: job_id},
            success: function (response) {
                if(response.result == 'saved'){
                    current_element.attr('disabled', true);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $(document).on('click', '.tooltip-close-btn', function(){
        $('.active_project_listing').html('');
        
    });
        
    $(document).on('change', '#project_list', function(){
        var current_element = $(this);
        var post_job_id = current_element.val();
        listSavedExperts(post_job_id);
    });
    
    $('#saved_experts').click(function(){
        $('#project_list').val(-1).trigger('change');
        listSavedExperts();
    });
    $('#saved_experts').mouseover(function(){
        $('.save_expert_tooltip').hide();
        $('.saved_expert_tooltip').show();
    });    
    $(document).on('click', '.save_expert_tooltip_button', function(){
          var response = udpateUserSettings({save_expert_tool_tip: true});
        if(response.success == 1){ 
            $('span.save_expert_tooltip').remove();
        }
    });    
    $(document).on('click', '.saved_expert_tooltip_button', function(){
        var response = udpateUserSettings({saved_expert_tool_tip: true});
        if(response.success == 1){
            $('span.saved_expert_tooltip').remove();
        }
    });    
    $('#clear_search').click(function(){
        $('#search_key').val('');
        $('#location').val('');
        $('#remote_option').val('0').trigger('change');
    });    
    $('#search_key').keyup(function(){
        $('#search_key_clear').show();
    });
    
    $('#location').keyup(function(){
        $('#location_clear').show();
    });
    
    $('#search_key_clear').click(function(){
        $('#search_key').val('').focus();
        $(this).hide();
    });
    
    $('#location_clear').click(function(){
        $('#location').val('').focus();
        $(this).hide();
    });
    
    $('#remote_option').change(function(){
        if($(this).val()!=0){
            $('#remote_option_clear').show();
        }else{
            $('#remote_option_clear').hide();
        }
        $('#submit_form').focus();
    });    
    $('#remote_option_clear').click(function(){
        $('#remote_option').val(0).trigger('change');
        $('#submit_form').focus();
    });    
    $('#search_key').click(function(){
        removeActiveFilterClass();
        $(this).parent().closest('div').addClass('active-filter');
    });    
    $('#location').click(function(){
        removeActiveFilterClass();
        $(this).parent().closest('div').addClass('active-filter');
    });    
    $('.remote-options').click(function(){
        removeActiveFilterClass();
        $(this).addClass('active-filter');
    });
});

function removeActiveFilterClass(){
    $('.buyer-search-filter-section').find(".active-filter").removeClass('active-filter');
}

function updateMatchExpertsDiv(input_data){
    var result = '';
     $.ajax({
        type: 'get',
        url: base_url + '/buyer/experts/search',
        data: input_data,
        async: false,
        success: function (response) {
            result = response;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return result;
}

function udpateUserSettings(data_to_update){
    var result = '';
    $.ajax({
        type: 'put',
        url: base_url + '/updateusersetting',
        async: false,
        data: data_to_update,
        success: function (response) {
            result = response;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return result;
}

function listSavedExperts(post_job_id){
    var total_saved_experts = 0;
    var url;
    if(!post_job_id){
        url = base_url + '/buyer/savedexpertslisting';
    } else {
        url = base_url + '/buyer/savedexpertslisting?post_job_id=' + post_job_id;
    }
    $.ajax({
        type: 'get',
        url: url,
        async: false,
        success: function (response) {
            $('.saved_experts_listing_section').html(response.view);
            if(response.show_load_more_button === false){
                $('#saved_expert_section_load_more_div').hide();
            }else{
                $('#saved_expert_section_load_more_div').show();
            }
            total_saved_experts = response.total_experts;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    return total_saved_experts;
}

$("#location").on('keyup', function (e) { 
    var location = document.getElementById('location').value;
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
     $('#location').val($(this).text()); 
     $("#office_location_tags").hide();
     $('#location').focus();
});
