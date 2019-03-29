$(function(){
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
    $('#search_key').keyup(function(){
        $('#search_key_clear').show();
    });
    $('#location').keyup(function(){
        $('#location_clear').show();
    });
    $('#search_key_clear').click(function(){
        $('#search_key').val('');
        $(this).hide();
    });
    $('#location_clear').click(function(){
        $('#location').val('');
        $(this).hide();
    });
    $('#remote_option').change(function(){
        if($(this).val()!=0){
            $('#remote_option_clear').show();
        }else{
            $('#remote_option_clear').hide();
        }
    });
    $('#remote_option_clear').click(function(){
        $('#remote_option').val(0).trigger('change');
    });
    $('#search_service_package_modal').modal('show');
    $('#got_it_service_package').on('click', function(){
        $.ajax({
            type: 'put',
            url: base_url + '/updateusersetting',
            async: false,
            data: {search_service_package_tool_tip: true},
            success: function (response) {
                if(response.success == 1){
                    $('#search_service_package_modal').modal('hide');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $('#load_more_result').click(function(){
        var datastring = $("#service_package_search_form").serialize() + "&number_of_listed_packages=" + $(".typewiselist_userinfo:visible").length;
        var type = $('#type').val();
        if(type == ''){
            type = 'search';
        }
        $.ajax({
            type: 'get',
            url: base_url + '/servicepackages/type/' + type,
            data: datastring,
            async: false,
            success: function (response) {
                $('.main_section').append(response.view);
                if(response.show_load_more_button === false){
                    $('#main_section_load_more_div').hide();
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $('#featured_packages').on('change touch', function(){
        var selected_package_value = $(this).val();
        $('#selected_featured_package').val(selected_package_value);
        $('#service_package_search_form').submit();
    });
    
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
    });
    $('#saved_experts').click(function(){
        $.ajax({
            type: 'post',
            url: base_url + '/servicepackages/savedexperts',
            async: false,
            success: function (response) {
                $('.saved_packages_section').html(response.view);
                $('#saved_packages_count').text(response.saved_package_count);
                if(response.show_load_more_button === false){
                    $('#saved_packages_section_load_more_div').hide();
                }else{
                    $('#saved_packages_section_load_more_div').show();
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $('#saved_packages_section_load_more').click(function(){
        var number_of_listed_packages = $(".typewiselist_userinfo:visible").length;
        $.ajax({
            type: 'post',
            url: base_url + '/servicepackages/savedexperts',
            async: false,
            data: {number_of_listed_packages: number_of_listed_packages},
            success: function (response) {
                $('.saved_packages_section').append(response.view);
                $('#saved_packages_count').text(response.saved_package_count);
                if(response.show_load_more_button === false){
                    $('#saved_packages_section_load_more_div').hide();
                }else{
                    $('#saved_packages_section_load_more_div').show();
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $(document).on('click', '.save_the_package', function () {
        var current_element = $(this);
        var buyer_id = current_element.attr('buyer-id');
        var service_package_id = current_element.attr('service-package-id');
        $.ajax({
            type: 'post',
            url: base_url + '/service/saveservicepackage',
            async: false,
            data: {buyer_id: buyer_id, service_package_id: service_package_id},
            success: function (response) {
                if (response) {
                    current_element.attr('saved_id', response);
                    current_element.removeClass('save_the_package');
                    current_element.addClass('unsave_the_package');
                    current_element.addClass('save-expert-icon');
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
        var current_element = $(this);
        var service_package_id = current_element.attr('saved_id');
        $.ajax({
            type: 'post',
            url: base_url + '/deletesavedservicepackage',
            data: {saved_service_package_id: service_package_id},
            async: false,
            success: function (response) {
                if (response == 1) {
                    current_element.removeClass('unsave_the_package');
                    current_element.removeClass('save-expert-icon');
                    current_element.addClass('save_the_package');
                } else {
                    return false;
                }

            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $(document).on('click', '.unsave_from_saved_listing', function(){
        var current_element = $(this);
        var service_package_id = current_element.attr('saved_id');
        $.ajax({
            type: 'post',
            url: base_url + '/deletesavedservicepackage',
            data: {saved_service_package_id: service_package_id},
            async: false,
            success: function (response) {
                $('#saved_experts').trigger('click');
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    $('#match_results').click(function(){
        $('#main_section_load_more_div').show();
        var datastring = $("#service_package_search_form").serialize();
        var type = $('#type').val();
        $.ajax({
            type: 'get',
            url: base_url + '/servicepackages/type/' + type,
            data: datastring,
            async: false,
            success: function (response) {
                $('.main_section').html(response.view);
                if(response.show_load_more_button === false){
                    $('#main_section_load_more_div').hide();
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
});