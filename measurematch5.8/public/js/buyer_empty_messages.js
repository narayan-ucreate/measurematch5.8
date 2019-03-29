$(document).ready(function () {
    if ($(window).width() < 1024) {
        $('body').addClass('mobile-messaging-view');
    }
});
    
$('body').on('click', '#show_more_deliverable', function(e) {
   $('body') .find('#deliverable_details_less').addClass('hide');
   $('body') .find('#deliverable_details').removeClass('hide');
   
});
$('body').on('click', '#show_less_deliverable', function(e) {
   $('body') .find('#deliverable_details_less').removeClass('hide');
   $('body') .find('#deliverable_details').addClass('hide');
   
});

$('body').on('click', '#show_more', function(e) {
   $('body') .find('#truncated_description').addClass('hide');
   $('body') .find('#full_description').removeClass('hide');
});
$('body').on('click', '#show_less', function(e) {
   $('body') .find('#truncated_description').removeClass('hide');
   $('body') .find('#full_description').addClass('hide');
});
$('body').on('click', '#show_more_skills', function (e) {
    var buttons = $("#make_offer_stage_popups").find(".skill-button");
    buttons.each(function (index) {
         if (index > 5) {
            $(this).removeClass('hide');
        }
    });
    $("#make_offer_stage_popups").find("#show_less_skills").removeClass('hide');
    $("#make_offer_stage_popups").find("#show_more_skills").addClass('hide');
});
$('body').on('click', '#show_less_skills', function(e) {
  var buttons = $("#make_offer_stage_popups").find(".skill-button");
        buttons.each(function (index) {
         if (index > 5) {
            $(this).addClass('hide');
        }    
         $("#make_offer_stage_popups").find("#show_more_skills").removeClass('hide');    
         $("#make_offer_stage_popups").find("#show_less_skills").addClass('hide');
        });
});

function projectDetailBack(project_id, buyer_id){
    $('.project-details-popup-button').attr("disabled", true);
    $.ajax({
        url: base_url + '/buyer/overlay/project/'+project_id,
        data : {'buyer_id' : buyer_id, post_job_id: project_id},
        type: 'GET',
        success: function (response) {
            if (response.success == 1) {
                $("#make_offer_stage_popups").html("");
                $(".modal-backdrop").remove();
                $('body').find("#make_offer_stage_popups").html(response.content);
                $("#project_detail_popup").modal("show");
                setTimeout(function(){
                   var buttons = $("#make_offer_stage_popups").find(".skill-button");
                    buttons.each(function (index) {
                     if(index > 5){   
                     $(this).addClass('hide');
                     }
                    }); 
                });
            }
        $('.project-details-popup-button').attr("disabled", false);
        },
        error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
        $('.project-details-popup-button').attr("disabled", false);
        }
    });
}
$('body').on("click",".project-details-popup-button",function(){
   projectDetailBack($('#project_id').val(), $(this).attr('data-buyer-id'));
});

var project_url = $("#project_url").val();
$('body').on("click",'a[href$="'+project_url+'"]',function(e){
    e.preventDefault();
    projectDetailBack($('#project_id').val());
});

$('body').on("click",".project-details-popup-button-myprojectpage",function(){
    var this_element = $(this);
    projectDetailBack(this_element.attr("project_id"), this_element.attr("data-buyer-id"));
});