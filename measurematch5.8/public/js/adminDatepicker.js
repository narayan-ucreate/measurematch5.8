$(function () {
    var end_date = $('#default_end_date').val();
    var enddate_split = end_date.split("-");
    var end_default = moment(new Date((enddate_split[1]) + "/" + (enddate_split[0]) + "/" + (enddate_split[2])));

    $('#end_time').datetimepicker({
        ignoreReadonly: true,
        format: 'DD-MM-YYYY',
        minDate: end_default,
        defaultDate: end_default,
        useCurrent: false,
    });
});