/* custom_search */
function custom_search() {
    var search = $("#search").val().trim();

    if (search == "")
    {
        $(".validation_error").text("Please enter keyword. ");
        return false;
    } else {
        $('#expertSearch').submit();
        return false;
    }
}

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#search').focus();
    var rates = $("#rates").val();
    var hourly_rate = $("#hourly_rate").val();
    var category_id = $("#category_id").val();
    var select_remote_option = $("#remote_work").val();
    var years_experience = $("#years_experience").val();
    if (rates != '' && rates != '0') {
        $("#rates").addClass('button-black');
    }

    if (hourly_rate != '' && hourly_rate != '0') {
        $("#hourly_rate").addClass('button-black');
    }
    if (category_id != '' && category_id != '0') {
        $("#category_id").addClass('button-black');
    }
    if (select_remote_option != '' && select_remote_option != '0') {
        $("#remote_work").addClass('button-black');
    }

    if (years_experience != '') {
        $("#years_experience").addClass('button-black');
    }

    $('select.selectpicker').on('change', function () {
        var selected = $('.selectpicker option:selected').val();
        if (selected == "") {
            $(this).parent().find(".dropdown-toggle").addClass('bs-placeholder');
            $(this).parent().find(".dropdown-toggle").removeClass('textcolorchange');
        } else {
            $(this).parent().find(".dropdown-toggle").addClass('textcolorchange');
            $(this).parent().find(".dropdown-toggle").removeClass('bs-placeholder');
        }
    });

    $('select.selectpicker').on('change', function () {
        var selected = $('.selectpicker option:selected').val();
        $(this).parent().find(".dropdown-toggle").addClass('textcolorchange')
    });

    $("#post-close-btn").on('click', function () {
        $(".successfully-posted").hide();
    });
    
    $("div").keypress(function () {
        $('.successfully-posted').fadeOut();
    });
    
    $("div").on('click',function () {
        $('.successfully-posted').fadeOut();
    });
    
    /**added to search parent div**/
    $('.advance-filter-link').on('click', function () {
        $('.find-match-form').toggleClass('search_panel_open');
    });
    var number_of_items = $('.paging').length;
    var cont = 1;
    $(".paging").each(function (i) {
        if (cont > 1) {
            $(this).css("display", "none");
        }
        cont++;
    });

    $(document).on('click','#view_more', function (e) {
       var url = $(this).attr('data-next-page-url');
       $.ajax( {
           url: url,
           success: function(data) {
               $('body').find('.view-more-matches').remove();
            $('#result_container').append(data);
           }
       })
    });

    $('select.selectpicker').on('change', function () {
        var selected = $('.selectpicker option:selected').val();
        if (selected == "") {
            $(this).parent().find(".dropdown-toggle").addClass('bs-placeholder');
            $(this).parent().find(".dropdown-toggle").removeClass('textcolorchange');
        } else {
            $(this).parent().find(".dropdown-toggle").addClass('textcolorchange');
            $(this).parent().find(".dropdown-toggle").removeClass('bs-placeholder');
        }
    });
    
    $('#searchsubmit').on('click', function (e) {
        var search = $("#search").val().trim();
        if (search == "")
        {
            $(".validation_error").text("Please enter keyword. ");
            return false;
        } else {
            $(".validation_error").text("");
        }
        if (search != '') {
            return true;
        }
    });
    
    /* custom_search start */
    $('.custom_search').on('change', function (e) {

        e.preventDefault();
        custom_search();
    });
    
    /* */
    $('#current_city').on('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            custom_search();
        }
    });
    
//clear filter
    $('#clear_filter').on('click', function (e) {
        $('#current_city').val('');
        $('#start_time_temp').val('');
        $('#end_time_temp').val('');
        var date = new Date();
        var day = date.getDate();
        var month_index = date.getMonth();
        var year = date.getFullYear();
        $('#start_time').datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            minDate: moment(new Date((month_index + 1) + "/" + (day) + "/" + (year))),
            useCurrent: false,
        });
        $('#end_time').datetimepicker({
            ignoreReadonly: true,
            format: 'DD-MM-YYYY',
            useCurrent: false,
        });
        $("#start_time").on("dp.change", function (e) {
            if ($('#end_time').val() != "") {
                if (($('#start_time').val()) > ($('#end_time').val())) {
                    $('#end_time').data("DateTimePicker").date(e.date);
                }
            }
        });
        $('#years_experience').val('');
        $('.custom_search').selectpicker('val', '');
        var url = window.location.href;
        url = url.split('?')[0];
        history.pushState('', 'Buyer Search', url);
    });
    
    $('select.selectpicker').on('change', function () {
        var selected = $('.selectpicker option:selected').val();
        $(this).parent().find(".dropdown-toggle").addClass('textcolorchange')
    });
});

$("#current_city").on('keyup', function (e) {  
    var location = document.getElementById('current_city').value;
    if (e.keyCode === 13 || location == '') {
         $("#current_city_tags").hide();
        return false;
    } else if (e.keyCode === 8) {
        if (location in save_hit) {
            saveApiHits(location, $("#current_city_tags"));
            return false;
        }
    }
    findLocation(location, $("#current_city_tags"));
});
$(document).on('click', '.dropdown span', function () { 
    if($('#current_city').is(":visible")) {
     $('#current_city').val($(this).text()); 
    }
    $('#current_city_tags').hide();
})

