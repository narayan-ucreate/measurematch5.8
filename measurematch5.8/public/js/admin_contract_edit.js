var start_date = $('#default_contract_start_date').val();
start_default= moment(start_date, 'YYYY-MM-DD');
$('#contract_start_date').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    defaultDate: start_default,
});

var end_date = $('#default_contract_end_date').val();
var end_default = moment(end_date, 'YYYY-MM-DD');

$('#contract_end_date').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY',
    defaultDate: end_default,
});

$("#contract_start_date").on("dp.change", function (e) {
    $('#contract_end_date').data("DateTimePicker").minDate(e.date);
});


$("#update_project_details").on("click", function () {
    var contract_start_date = $("#contract_start_date").val();
    var contract_end_date = $("#contract_end_date").val();
    var contract_id = $(this).attr("contract_id");
    
    var budget = $("#contract_budget").val().replace(/\D/g, "");
    var error_count = 0;

    if (contract_start_date == '')
    {
        $("#contract_start_date_error").text("Please add start date").addClass('has_error');
        error_count++;
    }
    if (contract_end_date == '')
    {
        $("#contract_end_date_error").text("Please add end date").addClass('has_error');
        error_count++;
    }
    if (budget == 0)
    {
        $("#contract_budget_error").text("Please add project value").addClass('has_error');
        error_count++;
    }

    if (error_count > 0) {
        return false;
    } else {
        $.ajax({
            type: 'post',
            url: base_url + '/admin/updateContractDetails/' + contract_id,
            data: {contract_id: contract_id, job_start_date: contract_start_date, job_end_date: contract_end_date, rate: budget},
            success: function (response) {
                if (response) {
                 window.location = window.location.href+'?contracts_tab_link=active'; 
                    return false;
                }
            },
            error: function (jqXHR, exception) {
                displayErrorMessage(jqXHR, exception);
            },
        });
    }

});
$(document).on('keyup', '#contract_budget', function (event) {
    var rate = $(this).val().trim();
    if (event.which >= 37 && event.which <= 40)
        return;
    $(this).val(function (index, value) {
        formated_rate = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return formated_rate;
    });
});