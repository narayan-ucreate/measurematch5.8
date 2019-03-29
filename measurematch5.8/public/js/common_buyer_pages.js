function searchExpertDetails(id) {
    localStorage.clear();
    var new_url = base_url + '/buyer/expert-profile/' + id;
    var win = window.open(new_url, '_blank');
    win.focus();
}
$(document).ready(function () {
    htmlbodyHeightUpdate()
    $(window).resize(function () {
        htmlbodyHeightUpdate()
    });
    $(window).scroll(function () {
        htmlbodyHeightUpdate()
    });
    $('#show_more').click(function(){
        $('#truncated_description').hide();
        $('#full_description').show();
    });

    $('#show_less').click(function(){
        $('#truncated_description').show();
        $('#full_description').hide();
    });     
});
