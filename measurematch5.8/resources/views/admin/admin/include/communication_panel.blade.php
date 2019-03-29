<div id="page-content-wrapper">
    <input type="hidden" id="timezone"/>
    <div class="expert_message_panel">
        <div class="expert_profile_inner_panel col-lg-12 col-md-12 ">
            <div class="expert-message-outer-panel">
                <div id="show-user-list">
                    @include('message.chatuserlist')
                </div>
                <div id="show-message-list">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 conversation-section cnvtn chat-block" style="height: 500px;">
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
                                <div class="type-message-section" id="send-message-box"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 expert-profile-section cnvtn chat-block" style="height: 227px;">
                    </div>
                </div>
            </div>
        </div>
        <div id="show-chat-user-profile" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 seller_message_profile_preview ">
        </div>
    </div>
</div>
<script type="text/javascript">
var index = true;
var sender_id = "{{$project->user_id}}";
var current_user_type = "{{$user_type}}";
var sender_name = " {{userName($project->user_id ,1)}}";
var is_admin_panel_view = true;</script>
<script src="{{ url('js/chat.js?js='.$random_number,[],$ssl) }}"></script>