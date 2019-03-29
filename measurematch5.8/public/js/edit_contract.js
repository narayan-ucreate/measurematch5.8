var date = new Date();
var start_date = $('#start_date').val();
var enddate = $('#end_date').val()

var from = start_date.split("-");
var enddate_split = enddate.split("-");

var day = date.getDate();
var month_index = date.getMonth();

var year = date.getFullYear();
var start_min_date = moment(new Date((month_index + 1) + "/" + (day) + "/" + (year)));
var start_default = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));

if (start_min_date >= start_default) {
    var start_default = start_min_date;
}
var end_min_date = moment(new Date((from[1]) + "/" + (from[0]) + "/" + (from[2])));

 $('#project_price').on("focus touch", function (event) {
          $(this).parent().addClass("highlighted-price");
    });
    $('#project_price').on("blur", function (event) {
          $(this).parent().removeClass("highlighted-price");
    });
    

var end_default = moment(new Date((enddate_split[1]) + "/" + (enddate_split[0]) + "/" + (enddate_split[2])));
$('#start_time').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: start_min_date,
    defaultDate: start_default,

});
$('#end_time').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    minDate: end_min_date,
    defaultDate: end_default
});
$("#start_time").on("dp.change", function (e) {
    if ((new Date((from[1]) + "/" + (from[0]) + "/" + (from[2]))) > (new Date((month_index + 1) + "/" + (day) + "/" + (year)))) {
        $('#end_time').data("DateTimePicker").date(e.date);
    }
    $('#end_time').data("DateTimePicker").minDate(e.date);
    $("#edit_contract_information").data("changed",true);
});
$("#end_time").on("dp.change", function (e) {
    $("#edit_contract_information").data("changed",true);
});
$('.selectpicker').selectpicker('refresh');
$("#job_post").prop("disabled", true);
$('#edit_contract_information #start_time').val($('#job_start_date_from_db').val());
$('#edit_contract_information #end_time').val($('#job_end_date_from_db').val());
if (document.getElementById("upload") != null) {
    document.getElementById("upload").onchange = function () {
        var attachement = $(this).val();
        var result = attachement.replace("C:\\fakepath\\", "");
        $('.attached-files-link').hide();
        $('.no_attachment_block').hide();
        var path = document.getElementById('uploadFile');
        path.innerHTML = result;
    };
}