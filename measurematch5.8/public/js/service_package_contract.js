var date = new Date();
var duration= $("#duration").val();
var contract_type= $("#subscription_type").val();
var from = $("#start_time").val().split("-");
var day = date.getDate();
var month_index = date.getMonth();

var year = date.getFullYear();
var start_min_date = moment(new Date((month_index + 1) + "/" + (day) + "/" + (year)));
var min_date_to_show_in_pop_up = (day) + "-" + ('0' + (month_index + 1)).slice(-2) + "-" + (year);
var start_default = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));
var start_default_to_show_in_pop_up = (from[0]) + "-" + (from[1]) + "-" + (from[2]);
if (start_min_date >= start_default) {
    var start_default = start_min_date;
    start_default_to_show_in_pop_up = min_date_to_show_in_pop_up;
}
var end_min_date = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));
if (start_min_date >= end_min_date) {
    var end_min_date = start_min_date;
}
if (contract_type == "one_time_package" && ($("#sp_contract_update").val()=="0")) {
    var enddate_split = $("#start_time").val().split("-");
    var end_default = moment(new Date((enddate_split[1]) + "/" + (enddate_split[0]) + "/" + (enddate_split[2]))).add(duration, 'd');
}else if(contract_type == "monthly_retainer" && ($("#sp_contract_update").val()=="1")){
    var enddate_split = $("#start_time").val().split("-");
    var end_default = moment(new Date((enddate_split[1]) + "/" + (enddate_split[0]) + "/" + (enddate_split[2])));
} 
else { 
    var enddate_split = $("#start_time").val().split("-");
    var end_default = moment(new Date((enddate_split[1]) + "/" + (enddate_split[0]) + "/" + (enddate_split[2])));
}

var days_difference = parseInt((start_min_date - end_min_date) / (1000 * 60 * 60 * 24) + 1);
$('#start_time').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: start_min_date,
    defaultDate: start_default,
});
$('#edit_contract #start_time').val(start_default_to_show_in_pop_up);

if (contract_type == "one_time_package") {
$('#end_time').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: end_min_date,
    defaultDate: end_default,
    useCurrent: false,
});    
$("#start_time").on("dp.change", function (e) { 
    if ((new Date((from[1]) + "/" + (from[0]) + "/" + (from[2]))) > (new Date((month_index + 1) + "/" + (day) + "/" + (year)))) {
        $('#end_time').data("DateTimePicker").date(e.date);
    }
    $('#end_time').data("DateTimePicker").minDate(e.date);
    $("#update_service_package_contract").data("changed",true);  
}); 
$("#end_time").on("dp.change", function (e) {
      $("#update_service_package_contract").data("changed",true);  
});     
}else{ 
  $("#start_time").on("dp.change", function (e) { 
    $(".monthly-start-date").text($("#start_time").val());
    $(".monthly-end-date").text(spContractEndDate($(this).val()));
    $(".monthly-billing-day").text(spContractEndDate($(this).val(),1));
   });  
}
 $('.selectpicker').selectpicker();
 $(document).on('change', '#update_service_package_contract :input', function (e) {
    e.preventDefault();
    $("#update_service_package_contract").data("changed",true);
});
 
function spContractEndDate(start_date,day_only) {
    var from = start_date.split("-"); 
    var sp_end_date = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2]))).add(30, 'd');
    if(day_only){
    return moment(sp_end_date).format('D');    
    }else{
    return moment(sp_end_date).format('DD-MM-YYYY');
    }
} 
if (document.getElementById("upload") != null) {
       document.getElementById("upload").onchange = function () {
           var attachment = $(this).val();
            $('.attached-files-link').hide();
            $('.no_attachment_block').hide();
           var result = attachment.replace("C:\\fakepath\\", "");
           var uploaded_file = document.getElementById('upload_file');
           if (attachment.split('.').pop() == 'exe') {
                $('#upload').val('');
                document.getElementById('upload_file').innerHTML = '';
                var element = document.getElementById('error_upload');
                uploaded_file.innerHTML = ".exe files are not allowed";
            }else{
               uploaded_file.innerHTML = result;
            }
         };
}
if (document.getElementById("attachment") != null) {
 document.getElementById("attachment").onchange = function () {

     var attachment = $(this).val();
     var result = attachment.replace("C:\\fakepath\\", "");
     var element = document.getElementById('sendMsgName');
     element.innerHTML = "File Attached";
     if (attachment.split('.').pop() == 'exe') {
         $('#attachment').val('');
         document.getElementById('sendMsgName').innerHTML = '';
         var element = document.getElementById('error_upload');
         element.innerHTML = ".exe files are not allowed";
     }


 };
}
   if ($("#subscription_type").val() == 'monthly_retainer') {
    $('#price').on("keyup", function (event) {
        var rate = $(this).val().trim();

        if (event.which >= 37 && event.which <= 40)
            return;

        $(this).val(function (index, value) {
            formated_rate = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return formated_rate + '/month';

        });
    });
}
    
   
    
    $('#price').on("focus touch", function (event) {
          $(this).parent().addClass("highlighted-price");
    });
    $('#price').on("blur", function (event) {
          $(this).parent().removeClass("highlighted-price");
    });
    
   var fadeout_time_limit = 4000;
   function priceValidations(rate,error_count) {
    var numbers = /^[0-9]+$/;
    var initial_error_count=error_count;
    if (rate == "")
    {
        $("#contract_price_error").text("Please enter proposal value").addClass('has_error');
        ++error_count;
    } else {
        if (rate < 200) {
            $("#contract_price_error").text("Proposal must be a minimum of $200 in value").addClass('has_error');
            ++error_count;
        } else if (rate > 100000) {
            $("#contract_price_error").text("Proposal cannot be more than $100,000 in value").addClass('has_error');
          ++error_count;
        }
    }
    if(initial_error_count === error_count){$("#contract_price_error").text("").removeClass('has_error');}
    return error_count;
}   
  
    $(document).off('click', '#submit_service_package_contract');
    $(document).on('click', '#submit_service_package_contract', function (e) {
       $("#submit_service_package_contract").prop('disabled', true);
        var start_time = $("#start_time").val();
        var price = $("#price").val();
        var numbers = /^[0-9]+$/;
        var deliverable_value = [];

        var error_count = 0;
        $('.has_error').show();
        $('.deliverables').each(function () {
            if ($(this).val().trim() != '') {
                deliverable_value.push($(this).val());
            }
        });
        if (start_time == '') {
            ++error_count;
            $("#start_time_error").text("Please enter proposal start date").addClass('has_error');
        }else{
            $("#start_time_error").text("").removeClass('has_error');
            }
      
        if($("#subscription_type").val()=='one_time_package'){ 
        var end_time = $("#end_time").val().trim();
        if (end_time == '') {
            ++error_count;
            $("#end_time_error").text("Please enter proposal end date").addClass('has_error');
       } else {
            var from = $("#start_time").val().split("-");
            var to = $("#end_time").val().split("-");
            if(moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2]))) > moment(new Date((to[1]) + "/" + (to[0]) + "/" + (to[2])))) {
                ++error_count;
                $("#end_time_error").text("Proposal end date can not be less then start date").addClass('has_error');
            } else {
                $("#end_time_error").text("").removeClass('has_error');
            }
        }
           
        }else{
            if ($("#monthly_days_commitment").val() == '') {
               ++error_count;
               $("#monthly_days_commitment_error").text("Please select monthly time commitment").addClass('has_error');
           }else{
               $("#monthly_days_commitment_error").text("").removeClass('has_error');
               }
        }
      
        error_count = priceValidations(price.replace(/(?!-)[^0-9.]/g,""),error_count);  
        
        
        if (deliverable_value.length == 0) {
            ++error_count;
            $("#contract_deliverables_error").text("Please add deliverables").addClass('has_error');
        }else{
            $("#contract_deliverables_error").text("").removeClass('has_error');
            }
        
        if (error_count > 0) {
            scrollToErrorInPopup('.has_error:visible:first');
            $('.has_error').fadeIn('fast').delay(2000).fadeOut('fast');
            setTimeout(function(){ 
                $("#submit_service_package_contract").prop('disabled',false);
            },2000);
           return false;
        } else { $("#price").val(price.replace(/(?!-)[^0-9.]/g,""));
          if($("#sp_contract_update").val()==0){ submitPreview(); }else{editContract();}
             
        }
        
    });
    
    var selectIds = $('#Contract,#Extension');
    $(function ($) {
        selectIds.on('show.bs.collapse hidden.bs.collapse', function () {
            $(this).prev().find('.glyphicon').toggleClass('glyphicon-plus glyphicon-minus');
        })
    });