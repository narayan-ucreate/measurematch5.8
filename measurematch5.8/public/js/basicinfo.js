/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*open billing account details tab */
$(document).ready(function(){
    $(document).on('click', '.panel-heading a', function(e){
        $('.panel-heading a').css('pointer-events','auto');
        if(!$(this).hasClass('collapsed')){
            $(this).css('pointer-events','none');
        }
    });
});
if (window.location.href.indexOf("actDetail") > -1) {
    $('#binfo').click();
    var new_url = base_url + '/buyer/settings';
    history.pushState({}, null, new_url);
}
$('#have_vat').on('change', function() {
    if ($(this).is(':checked')) {
        $('.vat-section').removeClass('hide');
    } else {
        $('.vat-section').addClass('hide');
    }
})
$(document).on('click', '.dropdown span', function () { 
    if ($('#location').is(":visible")) {
        $('#location').val($(this).text());
        $("#city_name").val($(this).find("input#city").val());
        $("#country_name").val($(this).find("input#country").val());
    } else {
        $('#billing_address_city').val($(this).text());
        $("#billing_address_city_name").val($(this).find("input#city").val());
        $("#billing_address_country_name").val($(this).find("input#country").val());
    }
    $('#tags , #billing_address_tags').hide();
});

$("#location").on('keyup', function (e) {
    var location = document.getElementById('location').value;
    if (e.keyCode === 13 || location == '') {
        $("#tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#tags"));
            return false;
        }
    }
    findLocation(location, $("#tags"));
});
$("#billing_address_city").on('keyup', function (e) {
    var location = document.getElementById('billing_address_city').value;
    if (e.keyCode === 13 || location == '') {
        $('#billing_address_tags').hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#billing_address_tags"));
            return false;
        }
    }
    findLocation(location, $("#billing_address_tags"));
});
$(".view-buyer-contract").on('click', function (e) {
    var contract_id= $(this).attr("contract-id");
    var type= $(this).attr("type");
    var communication_id= $(this).attr("communication-id");
    var view_edit_popup = base_url + '/getcontractviewpopup/' + contract_id;
    if(type=='project'){
        var id=$(this).attr("project-id");
    } else {
        var id=$(this).attr("service-package-id");
    }
    $.ajax({
        type: 'get',
        url: view_edit_popup,
        async:false,
        success: function (response) {
            if (response != 0) {
                $("#view_contract_preview").html("");
                $('#view_contract_preview').html(response).modal('show');
                $('.finish-cancel-btn').hide();
                $("#view_contract_preview").find(".contract-popup-actions").html('<a href="'+base_url+'/buyer/messages/' + type + '/' + id + '?communication_id='+communication_id+'" class="btn standard-btn ">View Messages</a>');
            }
        },
        error: function (jqXHR, exception) {
            displayErrorMessage(jqXHR, exception);
        }
    });
});

$('.all-contracts').click(function(){
    $.each($(".contract_extensions-"+$(this).attr("parent_contract_id")), function (key, val) {
        val.click();
    });
});

var input = $("#buyer_phone");
input.intlTelInput({
   preferredCountries: ['gb', 'us'],
   separateDialCode: true,
   formatOnDisplay: false,
   utilsScript: './js/international-phone-codes-utils.js'
});
