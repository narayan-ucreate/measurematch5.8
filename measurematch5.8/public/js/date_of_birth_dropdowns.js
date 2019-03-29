var month = 1;
var year = (new Date()).getFullYear();
var day, seq, days_in_month, previous_days_in_month = -1;

if (birth_month != '') {
    month = birth_month;
    daysInAMonth();
}

// Initializing the Year drop down from current year to current year - 100
var year_option = '<option value="">Year</option>';
for (i = year; i >= year - 100; i--) {
    year_option = year_option + '<option value=' + i + '>' + i + '</option>';
}
$('.dob-year').append(year_option);

// on change of Month/Year drop downs call the "daysInAMonth" function to calculate the number of days and repopulate days dropdown if needed
$('.dob-month').change(
    function () {
        month = $(this).val();
        daysInAMonth();
    }
);
$('.dob-year').change(
    function () {
        year = $(this).val();
        daysInAMonth();
    }
);

function daysInAMonth() {
    //calculate the number of days in a given month and year
    days_in_month = new Date(year, month, 1, -1).getDate();
    // if the number of days is not the same as previous dropdown (number of days) value then repopulate Days DropDown
    if (days_in_month != previous_days_in_month) {
        previous_days_in_month = days_in_month;
        day = '<option value="">Day</option>';
        for (i = 1; i <= days_in_month; i++) {
            day = day + '<option value=' + (i < 10 ? "0" + i : i) + '>' + (i < 10 ? "0" + i : i) + '</option>';
        }
        $('select.dob-day').html(day);
        $('.dob-day').selectpicker('refresh');
    }
};

function getFullDate(){
    var full_date = '';
    var selected_day = $('.dob-day :selected').text()
    var selected_month = $('.dob-month :selected').val()
    var selected_year = $('.dob-year :selected').text()
    if ((selected_day != 'Day')
        && (selected_month != 'Month')
        && (selected_year != 'Year')) {
        full_date = selected_day+'-'+selected_month+'-'+selected_year;
    }
    return full_date;
}