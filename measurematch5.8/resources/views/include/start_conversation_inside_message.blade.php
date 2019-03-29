<div id="popup_start_conversation" class="start-conversation">
    <form id="send_initial_message" class="send_initial_message">

        <button type="button" class="close" data-dismiss="modal"></button>
        <span class="expert-profile-pic" style="background-image:url({{getImage($image_url,$ssl)}});"></span>
        <h2 class="gilroyregular-semibold">Start a conversation with {{ucfirst($user_name)}}</h2>
        <h3>Begin your conversation with {{ucfirst($user_name)}} by sending a message. Here is some guidance on what to write:</h3>
        <ol>
            <li>  Suggest one or more dates/times to schedule a call or send direct access to your calendar (highly recommended)</li>
            <li> Provide any necessary extra context about the Project</li>
        </ol>
        <div class="msg-bg margin-bottom-20">
            <div class="col-lg-12 col-md-12 col-sm-12 right-panel read-more-section">
                @php
                    $empty_class = '';
                    if (isset($last_message->latestMessage->msg) && $last_message->latestMessage->msg !='') {
                     $message = $last_message->latestMessage->msg;
                    } else {
                     $message = ucfirst($user_name) .' did not write a message with the Expression of Interest';
                      $empty_class = 'no-message';
                    }
                    $class = '';
                @endphp
                <b class="font-16">{{ ucfirst($user_name) }}'s Message: </b>
                @if (strlen($message) > config('constants.EXPERT_PROFILE_MESSAGE_LIMIT'))
                    @php  $class = 'hide' @endphp
                    <div class="short-description font-14">
                        "{{substr($message, 0, config('constants.EXPERT_PROFILE_MESSAGE_LIMIT'))}}..."
                        <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-more">Read more</a>
                    </div>
                @endif
                <div class="full-description font-14 {{$class}} {{$empty_class}}">
                    {!! $empty_class === 'no-message' ? nl2br(e( $message )) : '"' . nl2br(e( $message )) . '"' !!}
                    @if ($class !== '')
                        <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-less">Read less</a>
                    @endif
                </div>
            </div>
        </div>
        <textarea class="form-control" name="message" id="initital_message" placeholder="Start typing here..." ></textarea>
        <div  class="error-message  message_validation_error hide has_error">
            Please add a message
        </div>
        <div class="start-con-btn-group">
            <button id="submit_and_start_conversation" buyer_id="{{Auth::user()->id}}" expert_name="{{ucfirst($user_name)}}" type="submit" class="btn standard-btn">Send Message</button>
            <a href="" class="reivew-proposal-link" data-dismiss="modal">Cancel</a>
            
        </div>
    </form>
</div>
<input type="hidden" id="vat_country_confirmation_pop_up" value="{{$vat_country_confirmation_pop_up}}">
<input type="hidden" id='start_conversation_popup' value="1">