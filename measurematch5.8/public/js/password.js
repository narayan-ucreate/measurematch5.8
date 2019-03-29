/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('body').on('click', '#resent-email', function(e) {
    e.preventDefault();
    $.ajax({
        url : $('#url').val(),
        method : 'post',
        data : {email : $('#email').val(),_token : $('input[name=_token]').val()},
        success : function(url) {
            window.location = url;
        }
    })
})
