var expert =1;
var buyer =2;
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var char_code = (evt.which) ? evt.which : evt.keyCode;
    if (char_code > 31 && (char_code < 48 || char_code > 57)) {
        return false;
    }
    return true;
}
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $("#link_copied_message").text("URL copied").show().fadeOut(4000);;
  $temp.remove();
}
$(function () {
    if ($('#budget1').is(':checked')) {
        $('#rate').attr('disabled', 'disabled');
    }

    $('.make-clickable').on('click', function(e) {
       window.location = $(this).attr('data-url')
    });
    
    if (getParameterByName("communication_tab"))
    {
        var communication_status = (getParameterByName("communication_tab"));
        if (communication_status == 'active') {
            $('.box-inner-tabing').find('.active').removeClass('active')
            $("#communication_tab_link").addClass('active');
            $('#communication_tab_content').removeClass('hide');
            $('#project_details_tab_content').addClass('hide');
            var project_id = $('#project_id').val();
            window.history.pushState(null, null, base_url
                    + '/admin/project/' + project_id);
        }
    }else if (getParameterByName("contracts_tab_link")){
        var communication_status = (getParameterByName("contracts_tab_link"));
        if (communication_status == 'active') {
            $('.box-inner-tabing').find('.active').removeClass('active')
            $("#contracts_tab_link").addClass('active');
            $('.box-inner-content ').addClass('hide');
            $('#contracts_tab_content').removeClass('hide');
            var project_id = $('#project_id').val();
            window.history.pushState(null, null, base_url
                    + '/admin/project/' + project_id);
        }
    } else {
        $('#project_details_tab_content').removeClass('hide');
        $('#communication_tab_content').addClass('hide');
    }   
    $('.project-detail-tab').on('click', function (e) {
        e.preventDefault();
        $(this).parents('.box-inner-tabing').eq(0).find('.active').removeClass('active')
        $(this).addClass('active')
        $('.box-inner-content ').addClass('hide');
        
        var data_tab = $(this).attr('data-tab');
        if (data_tab == 'communication') {
            $('#communication_tab_content').removeClass('hide');
        } else if (data_tab == 'contracts') {
            $('#contracts_tab_content').removeClass('hide');
        } else {
            $('#project_details_tab_content').removeClass('hide');
        }
    });
    
    /*!
     * Ajax call to block user
     */
    $('.blockUser').on('click', function (e) {
        if (confirm('Do you want to block this user ?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/blockUser',
                data: {id: id},
                success: function (response) {
                    window.location.reload();
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
    
    $('#service_package_type_featured').on('change', function () {
        var current_element = $(this);
        if (current_element.val() == 'Other'){
            $('#service_package_type').show();
        } else {
            $('#service_package_type').val('');
            $('#service_package_type').hide();
        }
    });

    $('.expertblockUser').on('click', function (e) {
        if (confirm('Do you want to block this user ?')) {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'post',
                url: base_url + '/admin/blockUser',
                data: {id: id},
                success: function (response) {
                    window.location.href = base_url + '/admin/expertListing';
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },

            });
            return false;
        } else {
            return false;
        }
    });

    /*!
     * Reinstate User
     */
    $('.reinstate').on('click', function (e) {
        if (confirm('Do you want to reinstate this user ?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/unblockUser',
                data: {id: id},
                success: function (response) {
                    if (response.result === "success") {
                        if (response.user_type === expert) {
                            window.location.href = base_url + '/admin/pendingExperts';
                        } else {
                            window.location.href = base_url + '/admin/pendingBuyers';
                        }
                    } else {
                        if (response.user_type === expert) {
                            window.location.href = base_url + '/admin/archivedExpertsListing';
                        } else {
                            window.location.href = base_url + '/admin/archivedBuyersListing';
                        }
                  }
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
    /*!
     * Reinstate archived project
     */
    $('#reinstate_project').on('click', function (e) {
        if (confirm('Do you want to reinstate this project ?')) {
            var project_id = $(this).attr('data-project_id');

            $.ajax({
                type: 'post',
                url: base_url + '/admin/reinstateProject',
                data: {project_id: project_id},
                success: function (response) {
                    if (response.result === "success") {
                        window.location.href = base_url + '/admin/archivedProjects';
                    } else {
                        console.log('Error: Project could not be reinstiated');
                    }
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

    $('#reinstate_package').on('click', function (e) {
        if (confirm('Do you want to reinstate this service package?')) {
            var id = $(this).attr('data-package_id');
            var user_id = $(this).attr('data-user');

            $.ajax({
                type: 'put',
                url: base_url + '/servicepackage/approve',
                data: {service_package_id: id, userId: user_id},
                success: function (response) {
                    if (response.success == 1) {
                        window.location.href = base_url + '/admin/rejectedservicepackages';
                    } else {
                        console.log('Error: Package could not be reinstiated');
                    }
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

    /* Resend email */
    $('.Resendemail').on('click', function (e) {
        if (confirm('Do you want to re-send email?')) {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'post',
                url: base_url + '/admin/resendemail',
                data: {id: id},
                success: function (response) {
                    if (response == 1) {
                        $('.success').text('Email sent successfully!');
                        $('.message-section').fadeIn('fast').delay(300).fadeOut(2000);
                    } else {
                        $('.success').text('Email not sent !');
                        $('.message-section').fadeIn('fast').delay(300).fadeOut(2000);
                    }
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

    /*!
     * Buyer registeration validation
     */
    $('#updateByr').on('click', function (e) {
        var user_type = $(this).attr('data-id');
        var ch = 0;
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var company_name = $("#company_name").val();
        var company_url = $("#company_url").val();
        var phone_number = $("#phone_number").val();
        var office_location = $("#office_location").val();
        var type_of_organization = $("#type_of_organization").val();
        var bio = $("#bio").val();
        var phone_number_regex = /^(?=.*[0-9])[- +()0-9]+$/;
        var error_count = 0;
        if (user_type == 3){
            var expected_project_post_time = $("#expected_project_post_time").val().trim();
        }
        var url_regx = /^(http(s)?:\/\/)?(Http(s)?:\/\/)?(HTTP(s)?:\/\/)?([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})([\/\w \.-]*)*\/?$/;
        if (first_name === "")
        {
            $(".first_name_error").text("Please add First Name.");
            error_count++;
        } else {
            $(".first_name_error").text("");
        }
        if (last_name === "")
        {
            $(".last_name_error").text("Please add Last Name.");
            error_count++;
        } else {
            $(".last_name_error").text("");
        }
        if (company_name === "")
        {
            $(".company_name_error").text("Please add Company Name.");
            error_count++;
        } else {
            $(".company_name_error").text("");
        }
        if (company_url === '') {
            $(".company_url_error").text('Please enter a valid company URL.');
            error_count++;
        } else if (!url_regx.test(company_url)) {
            $(".company_name_error").text('Please enter a valid company URL.');
            error_count++;
        } else {
            $(".company_name_error").text('');
        }

        if (company_name === "")
        {
            $(".company_name_error").text("Please add Company Name.");
            error_count++;
        } else {
            $(".company_name_error").text("");
        }

        if (expected_project_post_time == ""){
            $("#expected_project_post_time_error").text("Please select one option from list").addClass('has_error');
            error_count++;
        } else {
            $("#expected_project_post_time_error").html('').removeClass('has_error');
        }


        if (phone_number == ""){
            $(".phone_number_error").html('Please enter your phone number');
            error_count++;
        } else if (!phone_number_regex.test(phone_number)) {
            $(".phone_number_error").html('Please enter a valid phone number');
            error_count++;
        } else {
            $(".phone_number_error").html('').removeClass('has_error');
        }
        
        if (office_location === '') {
            $(".office_location_error").text('Please select office location.');
            error_count++;
        } else {
            $(".office_location_error").text('');
        }
        if (bio === '') {
            $(".bio_error").text('Please add bio.');
            error_count++;
        } else {
            $(".bio_error").text('');
        }        
        if (type_of_organization === '') {
            $(".type_of_organization_error").text('Please select type of organization.');
            error_count++;
        } else {
            $(".type_of_organization_error").text('');
        }

        if (error_count==0) {
            $('#update_buyer').submit();
        }
    });

    /*!
     * User approval 
     */
    $('#approve').on('click', function (e) {
        if (confirm('Do you want to approve this user ?')) {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'post',
                url: base_url + '/admin/approveUser',
                data: {id: id},
                success: function (response) {
                    window.location.href = base_url + '/admin/pendingBuyers';
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

    /*!
     *  User Decline
     */
    $('#decline').on('click', function (e) {
        if (confirm('Do you want to decline this user ?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/declineUser',
                data: {id: id},
                success: function (response) {
                    if (response.user_type_id == 4){
                        window.location.href = base_url + '/admin/pendingVendors';
                    } else {
                        window.location.href = base_url + '/admin/pendingBuyers';
                    }
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

    /*!
     *Update expert
     */
    $('#update_expert').on('click', function (e) {
        var first_name = $("#first_name").val().trim();
        var last_name = $("#last_name").val().trim();
        var describe = $("#describe").val().trim();
        var daily_rate = $("#daily_rate").val();
        var phone_number = $("#phone_number").val();
        var current_city = $("#current_city").val();
        var remote_work = $("#remote_work").val();
        var expert_type = $("#expert_type_selector").val();
        var skills = $("#addskill").val();
        var bio = $("#summary").val();
        var phone_number_regex = /^(?=.*[0-9])[- +()0-9]+$/;
        var error_count = 0;

        if (first_name == "")
        {
            $(".first_name_validation_error").text("Please add First Name.");
            error_count++;
        } else {
            $(".first_name_validation_error").text("");
        }
        if (last_name == "")
        {
            $(".last_name_validation_error").text("Please add Last Name.");
            error_count++;
        } else {
            $(".last_name_validation_error").text("");
        }
        if (expert_type == "")
        {
            $(".expert_type_validation_error").text("Please choose expert type.");
            error_count++;
        } else {
            $(".expert_type_validation_error").text("");
        }
        if (expert_type=='Consultancy'){
            if($('#experts_count').val()==''){
                $(".validation_error_experts_count").text("Please select number of experts");
                error_count++;
            }
        } else {
            $(".validation_error_experts_count").text("");
        }
        if (phone_number == ""){
            $(".phone_number_validation_error").html('Please enter your phone number');
            error_count++;
        } else if (!phone_number_regex.test(phone_number)) {
            $(".phone_number_validation_error").html('Please enter a valid phone number');
            error_count++;
        } else {
            $(".phone_number_validation_error").html('').removeClass('has_error');
        }
        if (describe === "")
        {
            $(".description_validation_error").text("Please add Description.");
            error_count++;
        } else {
            $(".description_validation_error").text("");
        }

        if (current_city === '') {
            $(".city_validation_error").text('Please select Current location.');
            error_count++;
        } else {
            $(".city_validation_error").text('');
        }
        if (daily_rate === '') {
            $(".rate_validation_error").text('Please add Daily Rate.');
            error_count++;
        } else {
            $(".rate_validation_error").text('');
        }
        if (remote_work === '') {
            $(".remote_work_validation_error").text('Please select Work Preferences.');
            error_count++;
        } else {
            $(".remote_work_validation_error").text('');
        }
        if (skills === '') {
            $(".skills_validation_error").text('Please add skills.');
            error_count++;
        } else {
            $(".skills_validation_error").text('');
        }
        if (bio === '') {
            $(".bio_validation_error").text('Please add bio.');
            error_count++;
        } else {
            $(".bio_validation_error").text('');
        }
        if (error_count==0) {
            $('#expertUpdate').submit();
        }
    });
    
    var max_field = 10;
    var add_button = $('.add-deliverable-link');
    var wrapper = $('.deliverable-panel');
    var field_html = '<div>\n\
                        <textarea name="deliverables[]" value="" class="deliverables add_description" \n\
                        placeholder="Describe a deliverable here" style="min-height:80px;"></textarea><a href="javascript:void(0);" \n\
                        class="remove_button" title="Remove">Remove</a>\n\
                    </div>';
    var x = 1;
    $(add_button).click(function () {
        if (x < max_field) {
            x++;
            $(wrapper).append(field_html);
        }
    });
    $(wrapper).on('click', '.remove_button', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
    $('#expert_type_selector').change(function(){
        var chosen = $(this);
        if(chosen.val()=='Consultancy'){
            $('#experts_count').val('').selectpicker('refresh');
            $('#number_of_experts_div').show();
        } else {
            $('#number_of_experts_div').hide();
        }
    });
    $("body").on('click', function () {
        if (!$("#addskill_manually").is(":focus")) {
            $(".add-skill-button-block").removeClass("service-tag-top");
        }
    });
    $("body #addskill_manually").on('click focus', function () {
        $(".add-skill-button-block").addClass("service-tag-top");
    });
    
    $('#update_service_package').on('click', function (e) {
        var numbers = /^[0-9]+$/;
        var name = $("#name").val().trim();
        var category = $("#category").val().trim();
        var description = $("#description").val().trim();
        var type = $('#type').val();
        var service_package_type = $("#service_package_type_featured option:selected").val();
        var other_skill = $('#service_package_type').val().trim();
        var deliverable_value = [];
        $('.deliverables').each(function () {
            if ($(this).val().trim() != '') {
                deliverable_value.push($(this).val());
            }
        });

        var buyer_remarks = $("#buyer_remarks").val();
        var price = $("#price").val().trim();
        var package_duration = $("#package_duration").val().trim();
        if (name == "")
        {
            $(".validation_project_name").text("Please enter service package title");
            return false;
        } else {
            $(".validation_project_name").text("");
        }

        if (category == "")
        {
            $(".validation_category").text("Please enter category.");
            return false;
        } else {
            $(".validation_category").text("");
        }
        if (service_package_type == "")
        {
            $(".validation_type").text("Please enter service package type.");
            return false;
        } else if (service_package_type == 'Other' && other_skill == ''){
            $(".validation_type").text('Hold your horses! As you chose "Other", please define the Service Package Type here.');
            return false;
        } else {
            $(".validation_type").text("");
        }
        if (description == "")
        {
            $(".validation_description").text("Please enter description.");
            return false;
        } else {
            $(".validation_description").text("");
        }
        if (deliverable_value.length == 0) {
            $(".validation_deliverables").text("Please add deliverables.");
            return false;
        } else {
            $(".validation_deliverables").text("");
        }

        var value_replaced_manually = $('#addskill_manually').val().replace(/,+$/, '');

        if (value_replaced_manually.trim().length != 0) {
            $(".addskill").append('<span class="skill-button">' + value_replaced_manually.trim() + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
        }

        $("#addskill_manually").val("");
        $('#Tags_list .skill-button .black_cross_link').on('click', function () {
            $(this).parents('.skill-button').remove();
        });
        var manual_skills = $('.add-skill-button-block').find('span');

        var all_manual_skills = [];
        $(manual_skills).each(function (i, e) {
            all_manual_skills.push($(this).text().trim());
        });
        $('#manual_skills').val(all_manual_skills);

        if ((manual_skills.length) < 3)
        {
            $(".validation_error_manual_skills").text("Please enter atleast 3 tag");
            return false;
        }

        if (buyer_remarks == "")
        {
            $(".validation_buyer_info").text("Please add information required from buyer.");
            return false;
        } else {
            $(".validation_buyer_info").text("");

        }
        if (!priceValidations(price)) {
            return false;
        }
        if (package_duration == "" || package_duration == 0)
        {
            $(".validation_package_duration").text("Please enter package duration.");
            return false;
        } else if (!numbers.test(package_duration))
        {
            $(".validation_package_duration").text("Please enter only digits in project duration.");

            return false;
        } else if (package_duration > 30)
        {
            $(".validation_package_duration").text("Service package duration cannot be more than 30 days.");

            return false;
        } else {
            $(".validation_package_duration").text("");

        }
        if (!type)
        {
            $(".subscription_type").text("Please choose subscription type.");
            return false;
        } else {
            $(".subscription_type").text("");

        }
        $('#update_service_package_form').submit();
    });

    $('#updatePublishedPost').on('click', function (e) {
        var numbers = /^[0-9]+$/;
        var job_title = $("#title").val().trim();
            alert(job_title); return false;
        var description = $("#description").val().trim();
        var end_time = $("#project_end_date").val();
        var rate_variable = $("#rate_variable").val();
        var rate = $("#rate").val().trim();
        var project_duration = $("#project_duration").val().trim();
        var remote_work = $("#remote_work").val();
        var budget = $("input[name='budget']:checked").val();
        return false;
        var msg = true;
        if (job_title == "")
        {
            $(".validation_project_name").text("Please enter job title");
            return false;
        } else {
            $(".validation_project_name").text("");
        }

        
        if (description == "")
        {
            $(".validation_error3").text("Please enter description.");
            return false;
        } else {
            $(".validation_error3").text("");
        }
        if (remote_work == "")
        {
            $(".validation_error9").text("Please select place of work.");
            return false;
        } else {
            $(".validation_error9").text("");

        }
        if (end_time == "")
        {
            $(".validation_error5").text("Please enter job end date.");
            return false;
        } else {
            $(".validation_error5").text("");
        }

        if (rate == "" && budget == 'yes')
        {
            $(".validation_error7").text("Please enter rate.");
            return false;
        } else if (!numbers.test(rate) && budget == 'yes')
        {
            $(".validation_error7").text("Please enter only digits in rate.");
            return false;
        } else if (rate < 200 && budget == 'yes')
        {
            $(".validation_error7").text("Please enter rate more than $200.");
            return false;
        } else {
            $(".validation_error7").text("");
        }
        if (budget == 'no') {
            $("#rate").val("0");
        }
        if (project_duration == "")
        {
            $(".validation_project_duration").text("Please enter project duration.");
            return false;
        } else if (!numbers.test(project_duration))
        {
            $(".validation_project_duration").text("Please enter only digits in project duration.");

            return false;
        } else if (project_duration > 365)
        {
            $(".validation_project_duration").text("Project duration cannot be more than 365 days.");

            return false;
        } else {
            $(".validation_project_duration").text("");

        }
        if (job_title != ''  && description != '' && end_time != '' && rate_variable != '') {
            return false;
        }
    });

    $('#category_id').multiselect({
        nonSelectedText: 'Choose'
    });

    if (document.getElementById("uploadBtn")) {
        document.getElementById("uploadBtn").onchange = function () {
            var initial_value = $(this).val();
            var remaining_value = initial_value.replace("C:\\fakepath\\", "");
            var pass = document.getElementById('uploadFile');
            pass.innerHTML = remaining_value;
        };
    }

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

    $(window).load(function () {
        $('.message-section').fadeIn('fast').delay(300).fadeOut(2000);
        if (window.location.href.indexOf("preview") > -1) {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });
        } else {
            return false;
        }
    });
    
    /****Approve service package***/
    $('#approve_service_project').on('click', function (e) {
        if (confirm('Do you want to approve this service package?')) {
            var id = $(this).attr('data-id');
            var user_id = $(this).attr('data-user');
            var redirect_to = $(this).attr('redirect-to');
            
            $.ajax({
                type: 'put',
                url: base_url + '/servicepackage/approve',
                data: {service_package_id: id, userId: user_id},
                success: function (response) {
                    if (response.success) {
                        if (typeof redirect_to !== typeof undefined && redirect_to !== false) {
                            window.location.href = base_url + '/admin/alldraftedservicepackages';
                        } else {
                            window.location.href = base_url + '/admin/pendingservicepackages';
                        }
                    } else {
                        $('.warning').html('Due to some reason project has not been updated.Please try again.');
                        return false;
                    }
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

    /****Reject Pending package***/
    $('#decline_service_project').on('click', function (e) {
        if (confirm('Do you want to reject this service package?')) {
            var id = $(this).attr('data-id');
            var redirect_to = $(this).attr('redirect-to');

            $.ajax({
                type: 'post',
                url: base_url + '/servicepackage/unapprove',
                data: {service_package_id: id},
                success: function (response) {
                    if (response == '1') {
                        if (typeof redirect_to !== typeof undefined && redirect_to !== false) {
                            window.location.href = base_url + '/admin/alldraftedservicepackages';
                        } else {
                            window.location.href = base_url + '/admin/pendingservicepackages';
                        }
                    } else {
                        $('.warning').html('Due to some reason project has not been updated.Please try again.');
                        return false;
                    }
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

    $('#servicePackageApproveWebflow').on('click', function (e) {
        if (confirm('Do you want to create a Webflow page for this service package?')) {
            var id = $(this).attr('data-id');
            var user_id = $(this).attr('data-user');

            $.ajax({
                type: 'post',
                url: base_url + '/servicepackage/approveWebflow',
                data: {service_package_id: id, userId: user_id},
                success: function (response) {
                    if (response.success) {
                        window.location.href = base_url + '/admin/servicepackages';
                        return false;
                    } else {
                        $('.warning').html('Due to some reason the page was not created. Please try again.');
                        return false;
                    }
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

    /****Approve Pending Project***/
    $('#approve_pending_project').on('click', function (e) {
        var this_object = $(this);
        var approval_status = $('#is_approved').val();
        var project_start_date_in_past = this_object.attr('data-project_start_date');
        if(project_start_date_in_past){
           alert("The service provider start date is in the past. You need to edit this date before you can publish the project live."); 
           return false;
        }
        var confirmation_message = 'Do you want to approve this project?';
        if(approval_status == 0) {
            confirmation_message = 'By approving this Project, you are also approving the MeasureMatch Buyer account. Would you like to continue?';
        }
        if (confirm(confirmation_message)) {
            this_object.attr("disabled", "disabled");
            $('#decline_pending_project').attr("disabled", "disabled");
            var id = this_object.attr('data-id');
            var user_id = this_object.attr('data-user');
            $.ajax({
                type: 'post',
                url: base_url + '/updateProjectStatus',
                data: {id: id, user_id: user_id},
                success: function (response) {
                    if (response == '1') {
                        window.location.href = base_url + '/admin/pendingProjects';
                    } else {
                        $('.warning').html('Due to some reason project has not been updated.Please try again.');
                        return false;
                    }
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

    /****Reject Pending Project***/
    $('#decline_pending_project').on('click', function (e) {
        if (confirm('Do you want to reject this project ?')) {
            var id = $(this).attr('data-id');
            $(this).attr("disabled", "disabled");
            $('#approve_pending_project').attr("disabled", "disabled");
            $.ajax({
                type: 'post',
                url: base_url + '/rejectProject',
                data: {id: id},
                success: function (response) {
                    if (response == '1') {
                        window.location.href = base_url + '/admin/pendingProjects';
                    } else {
                        $('.warning').html('Due to some reason project has not been updated.Please try again.');
                        return false;
                    }
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

    $('#budget').on('click', function () {
        $("#rate").removeAttr("disabled");
    });

    $('#budget1').on('click', function () {
        $('#rate').val('');
        $(".validation_error7").text("");
        $("#rate").attr("disabled", "disabled");
    });

    $('#expertApprove').on('click', function (e) {
        if (confirm('Do you want to approve this user ?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/approveUser',
                data: {id: id},
                success: function (response) {
                    window.location.href = base_url + '/admin/pendingExperts';
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
            });
            return false;
        } else {
            return false;
        }
    });



    $('#expertDecline').on('click', function (e) {
        if (confirm('Do you want to decline this user ?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/declineUser',
                data: {id: id},
                success: function (response) {
                    window.location.href = base_url + '/admin/pendingExperts';
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },

            });
            return false;
        } else {
            return false;
        }
    });

    $('#expertApproveWebflow').on('click', function (e) {
        if (confirm('Do you want to create a Webflow page for this user?')) {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: base_url + '/admin/expertApproveWebflow',
                data: {id: id},
                success: function (response) {
                    window.location.href = base_url + '/admin/expertListing';
                    return false;
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                },
            });
            return false;
        } else {
            return false;
        }
    });

    $('.notVerifiedblockExpert').on('click', function (e) {
        if (confirm('Do you want to block this user ?')) {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'post',
                url: base_url + '/admin/blockUser',
                data: {id: id},
                success: function (response) {
                    window.location.reload();
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

    $("#addskill_manually").on('keyup', function (event) {
        if (event.keyCode === 13 || event.keyCode === 188) {
            var val = $(this).val().replace(/,+$/, '');
            if (val.trim().length != 0) {
                if (val.trim().length < 61) {
                    $(".addskill").append('<span class="skill-button">' + val.trim() + '<a class="black_cross_link" href="javascript:void(0)"><img src="' + base_url + '/images/black_cross.png" alt="black_cross" class="black_cross" /></a></span>');
                    $("#addskill_manually").val("");
                    $(this).blur();
                    $(this).focus();
                } else {
                    $(".validation_error_manual_skills").text("Skill can't be more than 60 characters.").addClass('has_error');
                    $('.validation_error_manual_skills').fadeIn('fast').delay(TWO_THOUSAND).fadeOut('fast');
                    return false;
                }
            }
        }

        $('#Tags_list .skill-button .black_cross_link').on('click', function () {
            $(this).parents('.skill-button').remove();
        });

        if (event.keyCode === $.ui.keyCode.TAB) {
            event.preventDefault();
        }
    });
    $('#service_package_type').autocomplete();
    var textType = $('#service_package_type').val();
    $.ajax({
        type: 'get',
        url: base_url + '/servicepackagetypes?exclude_featured=true',
        data: {'textType': textType},
        success: function (data) {
            source = data;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
    
if ($('#project_id').val()) {
    updateDate('default_end_date', 'project_end_date');
    updateDate('default_visibility_date', 'project_visibility_date');
}   
var project_url = $("#project_url").val();
$('body').on("click",'a[href$="'+project_url+'"]',function(e){
    e.preventDefault(); return false;
});
});
$(document).on('keyup', '#service_package_type', function () {
    var srcs = source;

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
        appendTo: '#package-type-autocomplete'
    });
});
$(document).on('click', '#Tags_list .skill-button .black_cross_link', function () {
    $(this).parents('.skill-button').remove();
});
function priceValidations(rate) {
    var numbers = /^[0-9]+$/;
    if (rate == "")
    {
        $(".validation_price").text("Please add service package price.");
        $('.paid_exp').text('-');
        $('.paid_mm').text('-');
        return false;
    } else if (!numbers.test(rate))
    {
        $(".validation_price").text("Please enter only digits in price.");
        $('.paid_exp').text('-');
        $('.paid_mm').text('-');
        return false;
    } else {
        if (rate < 200 || rate > 100000) {
            $(".validation_price").text("Please enter the price between 200 and 100,000.");
            $('.paid_exp').text('-');
            $('.paid_mm').text('-');
            return false;
        } else {
            $(".validation_price").text("");
            return true;
        }
    }
}
$(document).on('click', '.stop-link-propagation, #chat-box a, .editcontract.contract-label', function(event){
    event.stopPropagation();
    return false;
});

$('body').on('change', '#select_all', function(e) {
    e.preventDefault();
    if ($(this).is(':checked')) {
        $('.upload-skill-logo').prop('checked', true)
        $('.file-upload-bulk').removeClass('hide');

    } else {
        $('.upload-skill-logo').prop('checked', false)
        $('.file-upload-bulk').addClass('hide');
    }

})
$('.file-upload').on('click', function(e) {
    e.preventDefault();
    $('input[type="file"][id="upload_logo"]').attr('data-skill-id', $(this).attr('data-skill-id'))
    $('.upload-skill-logo').prop('checked', false);
    $('#upload_logo').trigger('click')
})

$('.upload-skill-logo').on('click', function(e) {
    var is_selected = false;
    $(".upload-skill-logo").each(function(){
        if ($(this).is(':checked')) {
            is_selected = true;
        }
    });
    if (is_selected) {
        $('.file-upload-bulk').removeClass('hide');
    } else {
        $('.file-upload-bulk').addClass('hide');
    }
})
$('.file-upload-bulk').on('click', function(e) {
    e.preventDefault();
    var selected = [];
    $(".upload-skill-logo").each(function(){
        if ($(this).is(':checked')) {
            selected.push($(this).attr('value'));
        }
    });
    $('input[type="file"][id="upload_logo"]').attr('data-skill-id', selected.join(','))

    $('#upload_logo').trigger('click')
})
var already_selected = [];
$('input[type="file"][id="upload_logo"]').change(function(e){

    var formData = new FormData();
    formData.append("file", $(this)[0].files[0]);
    formData.append("upload_file", true);
    formData.append('skill_id', $(this).attr('data-skill-id'));

    $.ajax({
        type: "POST",
        url: "expert-skills",

        success: function (data) {
            location.reload();
        },
        error: function (error) {
            alert("Please select valid png file");
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });



});

//

function getParameterByName(name, url) {
    if (!url)
        url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
    var results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$('.contract-details-popup-admin').on('click', function (e) {
    var contract_id = $(this).attr('data-id');
    $.ajax({
        type: 'get',
        url: base_url + '/admin/getContractDetailsPopup/' + contract_id,
        success: function (response) {
            $('#contract_popups').html(response.content);
            $('#viewcontract').modal('show');
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        },
    });

});

$('.project-details-popup-admin').on('click', function (e) {
    $('#view_webflow_project').modal('show');
});

$('#projectApproveWebflow').on('click', function (e) {
    if (confirm('Do you want to create a Webflow page for this project?')) {
        var id = $(this).attr('data-id');
        var title = $('#project_title').val();
        var description = $('#view_webflow_project .edit_description').val();

        $.ajax({
            type: 'post',
            url: base_url + '/project/approvewebflow',
            data: {
                project_id: id,
                project_title: title,
                project_description: description
            },
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                    return false;
                } else {
                    $('.warning').html('Due to some reason the page was not created. Please try again.');
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    }
});

$('.view-business-details-popup').on('click', function (e) {
    var user_type = $(this).attr('data-id');
    var expert_type = $("input[name=expert_type]").val();
    if (user_type == '1'){
        $('#view_business_details .buyer-detail').addClass('hide');
        $('#view_business_details .expert-detail').removeClass('hide');
        if (expert_type == 2){
            $('#view_business_details .hide-sole-trader').addClass('hide');
        }
    } else {
        $('#view_business_details .buyer-detail').removeClass('hide');
        $('#view_business_details .expert-detail').addClass('hide');
        $('#view_business_details .hide-sole-trader').removeClass('hide');
    }
    $('#view_business_details').modal('show');
});

$(".view-expert-contract").on('click', function (e) {
    var communication_id= $(this).attr("communication-id");
    var contract_id= $(this).attr("contract-id");
    var user_id= $(this).attr("user-id");
    var buyer_id= $(this).attr("buyer-id");
    var data = {communication_id: communication_id, buyer_id: buyer_id, expert_id: user_id};
    $.ajax({
        url: base_url + '/expertcontractviewpopup/' + contract_id + '?source=admin',
        type: 'POST',
        data: data,
        async: false,
        success: function (response) {
            if (response.success == 1) {
                $("#view_contract_popup").html(response.content);
                $('#gotmatchpopup-' + contract_id).modal("show");
            }
        }
    });
});

$('.view-approve-vendor-popup').on('click', function (e) {
   $('#approve-vendor-popup').modal('show');
});

$('#approve_with_invite').on('click', function (e) {
    var id = $(this).attr('data-id');
    ajax_approve_vendor(id, true);
});

$('#approve_without_invite').on('click', function (e) {
    var id = $(this).attr('data-id');
    ajax_approve_vendor(id, false);
});

function ajax_approve_vendor(user_id, invite_mandatory) {
    $.ajax({
        type: 'post',
        url: base_url + '/admin/approveUser',
        data: {
            id: user_id,
            invite_mandatory: invite_mandatory
        },
        success: function (response) {
            window.location.href = base_url + '/admin/pendingVendors';
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}

$('.switch-invite-preference').on('click', function(e) {
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'post',
        url: base_url + '/admin/switchVendorInviteSetting',
        data: { id: id },
        success: function (response) {
            window.location.href = base_url + '/admin/vendorView/'+id;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});

$('body').on('click', '#show_more', function(e) {
    $('body') .find('#truncated_description').addClass('hide');
    $('body') .find('#full_description').removeClass('hide');
});
$('body').on('click', '#show_less', function(e) {
    $('body') .find('#truncated_description').removeClass('hide');
    $('body') .find('#full_description').addClass('hide');
});

$('#delete_user').on('submit', function(e) {
    if (!confirm('This will permanently delete this user. Do you wish to continue?')){
        return false;
    }
});

$('.approve-reject-hub').on('click', function (e) {
    var action = $(this).val().toLowerCase();
    if (confirm('Do you want to ' + action +' this hub ?')) {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        $.ajax({
            type: 'post',
            url: base_url + '/admin/approveRejectServiceHub',
            data: {id: id, status: status},
            success: function (response) {
                window.location.href = base_url + '/admin/pendingHubs';
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }

        });
        return false;
    }
    return false;
});