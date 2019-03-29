$('#request_data_download').click(function(){
    $('#request_data_download_pop_up').modal('show');
});

$(document).on('click', '#confirm_request_data', function () {
    $.ajax({
        'type': 'get',
        'url': base_url + '/request-my-data',
         success: function (response) {
            if (response == true) {
                $('#download_data_success_message').text("We've notified a MeasureMatch team member about your request to download your data as a csv file.").fadeIn('fast').delay(5000).fadeOut('fast');
            }
        }
    });
});
$('#request_to_delete_account').click(function(){ 
        $('#request_to_delete_account_pop_up').modal('show');
});
$(document).on('click', '#confirm_account_deletion', function () {
    $.ajax({
        'type': 'get',
        'url': base_url + '/delete-my-account',
         success: function (response) {
            if (response.success) {
                $('#delete_account_success_message').text("We've notified a MeasureMatch team member about your request to delete your account.").fadeIn('fast').delay(5000).fadeOut('fast');
            }
        }
    });
});
