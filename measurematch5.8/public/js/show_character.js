/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function forceLower(strInput)
{
    strInput.value = strInput.value.toLowerCase();
}

$(document).ready(function () {
    $('#selected_email').val() == '' || typeof $('#selected_email').val() ==='undefined' ?  $('#email').focus() : $('#password').focus();
    $('#show_characters').on('click',function () {
        if (document.getElementById('show_characters').checked == true) {

            document.getElementById('password').type = 'text';

        }
        if (document.getElementById('show_characters').checked == false) {
            document.getElementById('password').type = 'password';
        }
    });
    
    $("input#email").on({
        keydown: function (e) {
            if (e.which === 32)
                return false;
        }
    });
    $('input').on('keypress focus', function(e) {
        var parentEle = $(this).parents('div').eq(0);
         parentEle.find('.has-error').remove();
         parentEle.find('.help-block').remove();
            $(this).removeClass('error');
            $(this).removeClass('has-error');
    })
    
    
    $('#saveAndContinueInReset').on('click', function (e) {
        var email = $("#email").val().trim().toLowerCase();
        var valid_email = $("#valid_email").val().trim();
        var password = $("#password").val().trim();
        var confirm = $("#password-confirm").val().trim();
        var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        var passregex = /^(?=.*[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~])(?=.*\d)[A-Za-z!”"'.#@$!%*?&’()*\+-=,\/;:<>\[\\\]\^_`/{|}~\d]{6,}$/;
        if (email == "")
        {
            $(".validation_error").text("Please enter your email address");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (!emailformat.test(email))
        {
            $(".validation_error").text("Please enter the valid email");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if(email !=valid_email){
            $(".validation_error").text("Invalid email");
            $('.validation_error').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (password == "")
        {
            $(".validation_error1").text("Please enter the password");
            $('.validation_error1').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (password.length < 6)
        {
            $(".validation_error1").text("Please enter minimum 6 characters");
            $('.validation_error1').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (!passregex.test(password))
        {

            $(".validation_error1").text("Your password must contain at least one number");
            $('.validation_error1').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (confirm == "")
        {
            $(".validation_error2").text("The confirm password field is required.");
            $('.validation_error2').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else if (password != confirm) {

            $(".validation_error2").html("New password and confirm password must be same.");
            $('.validation_error2').fadeIn('fast').delay(2000).fadeOut('fast');
            return false;
        } else {
            return true;
        }
    });
});
