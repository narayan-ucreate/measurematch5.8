<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 conversation-section cnvtn chat-block" style="height: 227px;">
    <div class="bhoechie-tab-content">
        <div class="chat-top-bar no-chat-section convstn expert_top_info_panel">
            <span class="seller-info"> <span class="seller-name" id="show-receiver-name"> <span class="glyphicon pull-right glyphicon-option-horizontal" aria-hidden="true"></span></span>
            </span>
        </div>
        <div class="conversation-block convstn" id="chat-box">

            <div class="one-to-one-message-block">
                <section class="inner-content">
                    <input type="hidden" name="message-count" value="" id="message-count">                    
                    <div class="existing-chat paddingtop one-to-one-chat" id="appendchat">
                     
                    </div>
                    <div class="last-height"></div>
                </section>

            </div>
        </div>
        <div class="type-message-section" id="send-message-box">
            <div  id="accept-Eoi-message-box" class="row" style="display:none;">
            <div class="availabilty-message-panel">
                <div id="choices" class="send-message-panel">
                <h4>What’s your availability for this Package?</h4>
                <a id="accept_availity_req" href="#" class="white-bg-btn white-bg">I am available</a>
                <a id="decline_availity_req" href="#" class="white-bg-btn white-bg">I am not available</a>
                </div>
                <div id="send-message-panel-status" class="send-message-panel back-to-btn-panel" style="display:none;">
                    <a id="back_to_choices" href="#" class="message-back-btn gilroyregular-bold-font">Back</a>
                    <strong class="gilroyregular-bold-font" id="available_status">You’re Available- Send a message</strong>
                </div>
            </div>
       </div>
       <div id="message-box-text-box">
        <form id="send-message" class="send-message" method="post" enctype="multipart/form-data">
                <div class="text-bx-bg ds custom-disable-messageing">
                    <textarea id="one-to-one-chatbox" placeholder="Start typing here..." name="message" class="messagebox" maxlength="10020" data-gramm="false" value="" onkeyup="enableSendButton(this);"></textarea>
                    <input type="hidden" id="{{Auth::user()->id}}" class="sender-id" name="sender_id" value="{{Auth::user()->id}}">
                    <input type="hidden"   class="receiver-id" name="receiver_id" value="" >
                    <input type="hidden"   class="communication-id" name="communication_id" value="" >
                    <input type="hidden" id="available_status_value"  name="available_status_value" value="">                    
                    <div class="message-attachment-icon">
                        <input disabled type="file" name="upload_file" class="glyphicon-paperclip" id="message-attachment-file" onchange="getFilePath(event);" 
                               @if(Auth::user()->user_type_id == config('constants.EXPERT')) style="display: none;" @endif>
                        <img class="paperclip @if(Auth::user()->user_type_id == config('constants.EXPERT')) attach-file-icon @endif" src="{{url('images/ic_attachment.svg',[],$ssl)}}">
                        @if(Auth::user()->user_type_id == config('constants.EXPERT'))
                            <div class="clip-popup add-proposal-file-pop-up" style="display: none;">
                                <div class="send-perposal-block clip-popup-block">
                                    <h3 class="font-16 gilroyregular-semibold">Sending a Proposal?</h3>
                                    <p class="font-14 gilroyregular">Please send this via our proposal feature here:</p>
                                    <button class="open-send-proposal-popup btn standard-btn gilroyregular-semibold font-14">Send a Proposal</button>
                                </div>
                                <div class="attached-doc-block clip-popup-block">
                                    <h3 class="font-16 gilroyregular-semibold">Attach a document</h3>
                                    <p class="font-14 gilroyregular">Easily attach any file<br>here.</p>
                                    <button type='button' class="btn standard-btn gilroyregular-semibold font-14">Attach file</button>
                                    <input type="file" name="upload_file_proposal" class="glyphicon-paperclip" id="proposal-attachment-file" onchange="getFilePath(event);">
                                </div>
                                <div class="arrow-down"></div>
                            </div>
                        @endif
                    </div> 
                    
                    
                    <div class="clearfix"></div>

                </div>
                <button href="javascript:void(0)" class="mobile-send-message-button hide" disabled="disabled"><img class="send-arrow" src="{{url('/images/arrow-white.svg')}}" alt="" /></button>
                <input type="submit" class="send-btn btn btn-primary disabled desktop-send-message-button" value="Send" disabled="disabled" >
                <p id="file-upload"></p>
                <div class="clearfix"></div>
                <span id="message_error" class="error-message full-w d-block"></span>
            </form>
            
        </div>
        </div>
        
    </div>
</div>

<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 expert-profile-section cnvtn chat-block" style="height: 227px;">

</div>

<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>


