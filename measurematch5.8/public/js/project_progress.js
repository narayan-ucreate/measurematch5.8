if ($('#is_welcome').val() == 'true' && ($(window).width() >= 1024)) {
    $('#welcome_measurematch').modal('show');
}
$(function(){
    $(document).on('click','#cancel-finish-service-package',function(e){
        $('.modal').modal('hide');
    });
    if($('#show_rating').attr('expert_rating')!=''){
        var rating_by_buyer = $('#show_rating').attr('expert_rating');
        udpateRating(rating_by_buyer);
        
        $('#edit_rating').val(rating_by_buyer);
        $("#edit_rating").rateYo({  
            rating: rating_by_buyer,
            numStars: 5,
            precision: 2,
            minValue: 1,
            maxValue: 5,
            starWidth: "20px",
            ratedFill: "#1e70b7",
        }).on("rateyo.change", function (e, data) {
            var new_rating = data.rating;
            $('#edit_rating').val(new_rating);
        });
    }
    
    $(document).on('click', '.save_remove_expert', function(){
        var active_tab = $('.sub-nav-tabs li.active a').text();
        var current_element = $(this);
        var expert_id = current_element.attr('expert_id');
        $('#error_'+expert_id).text('');
        $.ajax({
                type: 'get',
                url: base_url + '/saveexpert/' + expert_id,
                success: function (response) {
                    if(response.result == 'saved'){
                        //user has been saved
                        current_element.addClass('selected-expert');
                    } else if(response.result == 'unsaved') {
                        //user has been unsaved
                        current_element.removeClass('selected-expert');
                        if(active_tab=='Saved Experts'){
                            //for reloading the saved experts page
                            $('#saved_experts').trigger('click');
                        }
                    } else if(response.result == 'error') {
                        //error occured, fetch error message
                        $('#error_'+expert_id).text(response.error_message);
                    }
                },
                error: function (jqXHR, exception) {
                    displayErrorMessage(jqXHR, exception);
                }
            });
    });
    
    $('#saved_experts').click(function(){
        $.ajax({
            type: 'get',
            url: base_url + '/savedexpertlisting',
            success: function (response) {
                $('.saved_experts_div').html(response);
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $(document).on("click", "ul.pagination li a", function(e) {
        e.stopPropagation();
        e.preventDefault();
        var action_url=$(this).attr("href");
        $.ajax({
              type: 'get',
              url: action_url,      
              success: function(response){
                  if(response!=0){
                      if(action_url.indexOf("savedexpertlisting") >= 0){
                          $('.saved_experts_div').html(response);
                      }else{
                          $('.past_matches_div').html(response);
                      }
                  }
              },
              error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
              },
         });
    });
    
    $('#past_matches').click(function(){
        $.ajax({
            type: 'get',
            url: base_url + '/pastmatchingexpertlisting',
            success: function (response) {
                if(response!=0){
                    $('.past_matches_div').html(response);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $('#recommended_experts').click(function(){
        $.ajax({
            type: 'get',
            url: base_url + '/randomexpertslisting/' + $('#post_id').val(),
            success: function (response) {
                if(response!=0){
                    $('.recommended_experts_div').html(response);
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $('#open_delete_box').click(function(){
        $("#project_delete_button").attr("project_id", $(this).attr('data-project_id'));
    });
    
    $('.delete_project').click(function(){
        var project_id = $(this).attr('project_id');
        $('#general_error').removeClass('alert alert-danger validation_error');
        $('#general_error').text('');
        $.ajax({
            type: 'get',
            url: base_url + '/deleteproject/' + project_id,
            success: function (response) {
                if(response.result=='error'){
                    $('#general_error').addClass('alert alert-danger validation_error');
                    $('#general_error').text(response.error_msg);
                }else if(response.result=='deleted'){
                    window.location.href = base_url + '/myprojects';
                }
                $('.cancel-btn').trigger('click');
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    
    //review expert tab
    var rating = 0;
    $('#rating').val(rating);
    $(".rateyo-readonly-widg").rateYo({

        rating: rating,
        numStars: 5,
        precision: 2,
        minValue: 1,
        maxValue: 5,
        starWidth: "20px",
        ratedFill: "#1e70b7",
    }).on("rateyo.change", function (e, data) {
        var expert_rating = data.rating;
        $('#rating').val(expert_rating);
    });
    
    $(document).on('click', '#buyer_feedback', function (e) {
        e.preventDefault();
        var button = $(this);
        var sender_id = button.attr('sender_id');
        var receiver_id = button.attr('receiver_id');
        var comm_id = button.attr('communications_id');
        var contract_id = button.attr('contract_id');
        var rating = $('#rating').val();
        var feedback_comment = $('#feedback_comment').val();
        if (rating == 0)
        {
            $(".rating_validation_error").text("Please select rating");
            $('.rating_validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (feedback_comment == "")
        {
            $(".feedback_validation_error").text("Please add your feedback");
            $('.feedback_validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (rating != '' && feedback_comment != '') {
            $.ajax({
                type: 'post',
                url: base_url + '/buyerfeedback',
                data: {contract_id: contract_id, receiver_id: receiver_id, communications_id: comm_id, sender_id: sender_id, rating: rating, feedback_comment: feedback_comment},
                success: function (result) {
                    //get view with ajax
                    if (result == 1) {
                        window.location.reload();
                    }
                }
            });
        }
    });
    
    $(document).on('click', '#edit_buyer_feedback', function (e) {
        e.preventDefault();
        var button = $(this);
        var sender_id = button.attr('sender_id');
        var receiver_id = button.attr('receiver_id');
        var comm_id = button.attr('communications_id');
        var contract_id = button.attr('contract_id');
        var rating = $('#edit_rating').val();
        var feedback_comment = $('#edit_feedback_comment').val();
        if (rating == 0)
        {
            $(".edit_rating_validation_error").text("Please select rating");
            $('.edit_rating_validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (feedback_comment == "")
        {
            $(".edit_feedback_validation_error").text("Please add your feedback");
            $('.edit_feedback_validation_error').fadeIn('fast').delay(2000).fadeOut();
            return false;
        } else if (rating != '' && feedback_comment != '') {
            $.ajax({
                type: 'post',
                url: base_url + '/editbuyerfeedback',
                data: {contract_id: contract_id, receiver_id: receiver_id, communications_id: comm_id, sender_id: sender_id, rating: rating, feedback_comment: feedback_comment},
                success: function (result) {
                    //get view with ajax
                    if (result == 1) {
                        window.location.reload();
                    }
                }
            });
        }
    });
 
    //on changeing upload button value show name preview of attachement
    if (document.getElementById("upload")) {
        document.getElementById("upload").onchange = function () {
            var initial_value = $(this).val();
            var remaining_value = initial_value.replace("C:\\fakepath\\", "");
            var pass = document.getElementById('preview_attachment');
            pass.innerHTML = remaining_value;
        };
    }
   
    autosize(document.querySelectorAll('textarea.deliverable_offer'));
    $(document).on('click','#contract_preview_update',function(e){
       e.preventDefault();
       var numbers = /^[0-9]+$/;
       var update_coupon_code=$('#update_coupon_code').val();
       var job_post = $('#edit_contract select#job_post').val();
       var project_start_date = $('#edit_contract #start_time').val();
       var project_end_date = $('#edit_contract #end_time').val();
       var project_price = $('#edit_contract #project_price').val();     
       var project_deliverable = $('#edit_contract #project_deliverable').val();       
       var contract_id = $('#contract_id').val();
       var threshold_price_for_coupon = 1000;
       var payment_mode = $("input[name='payment_mode']:radio:checked");
       emptyValidationErrors();
       if (job_post == 0 || job_post == "" || job_post == "undefined"){
            $(".validation_posted_project_id").text("Please select project");
            return false;
       }
       if (project_start_date == ""){
            $(".validation_start_time").text("Please enter project start date");
            return false;
       }
       if (project_end_date == ""){
            $(".validation_end_time").text("Please enter project end date");
            return false;
       }
       if (project_price == ""){
                $(".validation_project_price").text("Please enter rate");
                return false;
        } else if (!numbers.test(project_price)){      
                $(".validation_project_price").text("Please enter only digits in rate");
                return false;
        } else if (project_price < 200){
                $(".validation_project_price").text("Contract cannot be made if it is under the value of $200");
                return false;
        } else if (project_price < threshold_price_for_coupon && update_coupon_code == '1'){
               removeBuyerPromoCode(contract_id, project_price)
        } 
        if (project_deliverable == "")
        {
            $(".validation_project_deliverable").text("Please enter deliverable");
            return false;
        }
        if (project_start_date != '' && project_end_date != '' && project_price != '' && job_post != '' && project_deliverable != '') {
              editContract(job_post);
           
        }
    });
    
    function removeBuyerPromoCode(contract_id, project_price){
        var remove_coupon = confirm("Coupon code will not work on contracts below $1000");
        if (remove_coupon == true) {
            $.ajax({
                type:'post',
                url: base_url+'/removeBuyerPromoCode',
                data:{'contract_id': contract_id},
                async: false,
                success:function(response){
                    if(response==1){
                        $(".validation_error7").text("Coupon code removed successfully!!");
                        return false;
                    }else{
                        $(".validation_error7").text("Coupon code could not be removed!!");
                        return false;
                    }
                }
             }); 
        } else {
               $("#project_price").val(project_price);
               return false;
        }
    }
    
      $('#start_time').datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            minDate: new Date()
        });
        $('#end_time').datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            minDate: new Date(),
            useCurrent: true //Important! See issue #1075
        });
        $("#start_time").on("dp.change", function (e) {

            $('#end_time').data("DateTimePicker").minDate(e.date);
        });
        
        $('#edit_feedback').click(function(){
            $('#edit_feedback_form').show();
            $('#feedback_view').hide();
        });
        
        $('#new_project').click(function(){
            window.location.href = base_url + '/project/create';
        });
        
    $('.editcontract').click(function(){
        var contract_id = $(this).attr('contract_id');
        showContractViewPopUp(contract_id);
    });
    
    $(document).on('click', '#contract_preview_edit', function (e) {
        var contract_id = $(this).attr('contract_id');
        $.ajax({
            type: 'get',
            url: base_url + '/getcontracteditpopup/' + contract_id,
            success: function (response) {
                if(response!=0){
                    $('#view_contract').modal('hide');
                    $('#edit_contract').html(response).modal('show');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
   
    $(document).on('click', '#apply_coupon', function(){
        var contract_id = $(this).attr('contract_id');
        $.ajax({
            type: 'get',
            url: base_url + '/getapplycouponpopup/' + contract_id,
            success: function (response) {
                if(response!=0){
                    $('#view_contract').modal('hide');
                    $('#apply_coupon_pop_up').html(response).modal('show');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
    
    $(document).on('click', '.coupon_popup_back', function(){
        var contract_id = $(this).attr('contract_id');
        showContractViewPopUp(contract_id);
    });
    
    $(document).on('click', '#mark_as_complete_button', function(){
        var contract_id = $(this).attr('contract_id');
        $.ajax({
            type: 'get',
            url: base_url + '/getmarkascompleteconfirmpopup/' + contract_id,
            success: function (response) {
                if(response!=0){
                    $('#view_contract').modal('hide');
                    $('#mark_as_complete_confirm').html(response).modal('show');
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            }
        });
    });
});

function editContract(job_post){
    var updated = 1;
    var empData = new FormData($('#edit_contract_information')[0]);
       $.ajax({
               type: 'post',
               url: base_url + '/editcontractoffer',
               data: empData,
               success: function (response) {
                    if (response.success == updated) {
                        window.location.href = base_url+'/buyer/project-progress/'+job_post;
                        return false;
                    } else {
                        return false;
                    }
               },
               cache: false,
               contentType: false,
               processData: false
              });
}

function emptyValidationErrors(){
  $(".validation_posted_project_id").text("");
  $(".validation_start_time").text("");
  $(".validation_end_time").text("");
  $(".validation_project_price").text("");
  $(".validation_project_deliverable").text("");
}

function showContractViewPopUp(contract_id){
    $.ajax({
        type: 'get',
        url: base_url + '/getcontractviewpopup/' + contract_id,
        success: function (response) {
            if(response!=0){
                $('#view_contract').html(response).modal('show');
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}


 /*preview discount coupon*/
$(document).on('click', '.redeemSubmit', function (e) {
    var rate = $(this).attr('data-rate').trim();
    var contract_id = $(this).data('contract_id');
    var redeem_coupon_value = $("#redeemCouponValue-" + contract_id).val().trim();

    if (redeem_coupon_value == '') {
        $('.validate_expert_error').html('Please add coupon code.').fadeIn('slow').delay(4000).fadeOut();
        return false;
    } else if (redeem_coupon_value) {
        var check_unique_coupon_url = base_url + '/checkcouponuniqueness?coupon=' + redeem_coupon_value;
        var coupon_can_be_applied = false;
        $.ajax({
            type: 'get',
            url: check_unique_coupon_url,
            success: function (response) {
                if (response.status == 3) {
                    var txt_message;
                    var remove_coupon = confirm(response.message);
                    if (remove_coupon == true) {
                        $.ajax({
                            type: 'post',
                            url: base_url + '/removeBuyerPromoCode',
                            data: {'contract_id': response.already_applied_contract_id},
                            async: false,
                            success: function (response) {
                                if (response == 1) {
                                    applyPromotionalCoupon(redeem_coupon_value, contract_id, rate);
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
                    applyPromotionalCoupon(redeem_coupon_value, contract_id, rate);
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
$(document).on('click', '.remove_discount_coupon', function (e) {
    var contract_id = $(this).attr("data-contract_id");
    var rate = $(this).attr('data-rate').trim();
    var project_id = $(this).attr('project_id');
    if (contract_id == 'preview') {
        $('.preview_discount_hide').show();
        $('.preview_discount_show').hide();
        $("#preview_mm_fee").text("-$" + (parseFloat(Math.round(parseInt(rate) * 0.15) * 100) / 100).toFixed(2));
        $("#preview_what_you_will_get").text("$" + (parseFloat(Math.round(parseInt(rate)))).toFixed(2));
        $('#coupon_code_applied').val("");
    } else {
        $.ajax({
            type: 'post',
            url: base_url + '/removeBuyerPromoCode',
            data: {'contract_id': contract_id},
            async: false,
            success: function (response) {
                console.log(response)
                if (response == 1) {
                    $('.coupon_applied_daily_rate_parent').hide();
                    $('#mm_fee-' + contract_id).text("-$" + (parseFloat(Math.round(parseInt(rate) * 0.15) * 100) / 100).toFixed(2));
                    $('#what_you_will_get-' + contract_id).text("$" + (parseFloat(Math.round(parseInt(rate)))).toFixed(2));
                    $('#update_coupon_code').val("0 ");
                    $(".coupon-code-block, .apply-coupon-btn").show();
                    return false;
                } else {
                    window.location.href = base_url + '/buyer/project-progress/'+project_id;
                    return false;
                }
            }
        });

    }
});
$(document).on('click', '.mark-as-complete', function (e) {
    var comm_id = $(this).attr('id'); 
    var project_id = $(this).attr('project_id');
    var receiver_id = $(this).attr('user_id');
    var sender_id = $(this).attr('sender_id');
    var payment_mode = $(this).attr('payment_mode');
    var communications_id = $(this).attr('communications_id');
    var contract_type = "project";
    $('.mark-as-complete').css('pointer-events', 'none');
    $.ajax({
        type: 'post',
        url: base_url + '/markcontractascomplete',
        data: {contract_id: comm_id, receiver_id: receiver_id, communications_id: communications_id, payment_mode: payment_mode,contract_type:contract_type},
        success: function (result) {
            if (result.success == 1) {
                window.location.href = base_url + '/buyer/project-progress/'+project_id;
            }
        },
        error: function (jqXHR, exception) {        
            displayErrorMessage(jqXHR, exception);    
        }
    });
});

//for showing star rating dynamically after rating is given
function udpateRating(rating_by_buyer){
    $("#show_rating").rateYo({
        rating: rating_by_buyer,
        numStars: 5,
        precision: 2,
        readOnly: true,
        ratedFill: "#29235c",
        starWidth: "15px"
    });
}
function applyPromotionalCoupon(redeemCouponValue, contract_id, rate) {
        $.ajax({
            type: 'post',
            url: base_url + '/previewBuyerPromoCode',
            data: {'coupon': redeemCouponValue, 'contract_id': contract_id, 'rate': rate},
            async: false,
            success: function (response) {
                console.log(response)
                if (response.status == 0) {
                    $('.validate_expert_error').html(response.message).fadeIn('slow').delay(4000).fadeOut();
                } else if (response.status == 1) {
                    $('.validate_expert_error').html(response.message).fadeIn('slow').delay(4000).fadeOut();
                    $('#previewCouponContract').modal('hide');
                    $('#view_contract').modal('show');
                    $("#preview_what_you_will_get").text("$" + (parseFloat(Math.round(parseInt(rate)) - response.amount)).toFixed(2));
                    $('.coupon_applied_daily_rate_parent').show();
                    $('#coupon_code_applied').val(redeemCouponValue);

                } else if (response.status == 2) {
                    /*work needs to be done for update contract*/
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
                    $('#view_contract').modal('show');
                     return false;
                }

            }
        });
    }
    
function redirectToDetailPage(service_package_id) {
    $.ajax({
        type: 'get',
        url: base_url + '/addtosession',
        async: false,
        data: {service_package_id: service_package_id},
        success: function (response) {
            window.location.href = base_url + '/servicepackage/' + service_package_id;
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
}