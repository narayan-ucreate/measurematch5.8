<?php if(Auth::check()){ ?>
   <div id="message-mm-support_panel" class="feedback-panel" style="display: none">
       <span class="pull-right close-btn" id="close_support_panel"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span>
       <h3>Write your message here or send an email to <a href="mailto:support@measurematch.com">support@measurematch.com</a></h3>

    <span id="support_success_message" class="success-message"></span>
    <div class="clearfix"></div>
    <form id="support_message_to_admin" name="support_message_to_admin" >
    <textarea  id="user_message" name="user_message"  value="" maxlength="4000" class="input-bx-style texarea-minheight-75"></textarea>
    <span id="message_validation_error" class="error-message"> </span>
    <div class="clearfix"></div>
    <p>Need more help? Visit our <a target="_blank" href="{{url('/faq')}}">FAQ</a></p>
    <input id="submit_message-mm-support" type="button" value="Send" class="btn btn-primary next-step-btn " id="continue_project_post_btn">
   </form></div>
 <?php } ?>
