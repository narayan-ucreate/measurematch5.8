<label>Begin your conversation with {{ ucfirst($expert->name) }} by sending a message. Here is some guidance on what to write:</label>
<ol>
    <li>Suggest one or more dates/times to schedule a call or send direct access to your calendar (highly recommended)</li>
    <li>Provide any necessary extra context about the project</li>
</ol>

<textarea tabindex="2" class="margin-bottom-10" name="sendMessage" id="invite_message" placeholder="Start typing here"></textarea>
@else
    <span class="no-post-found buyer-alert-message">
                               <h3> Whoa Nelly!</h3>
        @if(isset($active_projects)&& !empty($active_projects))
            <p class="margin-bottom-10 text-align-center font-16">You are already discussing all your active projects with this Expert. If you'd like to discuss a new project, you'll need to post a new project.</p>
            <div class="text-align-center margin-bottom-10">
                                    <a class="add-post standard-btn " href="{{url('project/create',[],$ssl)}}">Submit a Project</a>
                                   </div>
            <span class="help-text text-align-center col-md-12 font-16">Or get in touch. We can <a class="gilroyregular-bold-font link-color" href="mailto:help@measurematch.com">help</a>.</span>
        @elseif(isFirstProject()==1)
            <p class="margin-bottom-10 font-18">The project you posted to the platform is currently under review by the MeasureMatch team, therefore you can't get in touch with any Experts yet. But hold tight, we'll get to it within 72 working hours.</p>
        @else
            <p class="margin-bottom-10 font-18">You need to post a project [i.e. to demonstrate that you're prepared to create value] before you can start a discussion with MeasureMatch Experts.</p>
            <div class="text-align-center margin-bottom-10">
                                    <a class="add-post standard-btn " href="{{url('project/create',[],$ssl)}}">Submit a Project</a>
                                   </div>
            <span class="help-text text-align-center col-md-12">Or get in touch. We can <a class="gilroyregular-bold-font link-color" href="mailto:help@measurematch.com">help</a>.</span>
        @endif
        @endif
        <span id="error_upload" class="margin-bottom-10 font-14 margin-0"></span>
                            </span>
    </div>
    <input type="hidden" id="user_id" name="user_id" value="{{ $expert['user_profile']->user_id }}">
    <input type="hidden" id="buyer_id" name="buyer_id" value="{{ Auth::user()->id }}">
    @if(isset($projects_list) && !empty($projects_list))
        <input tabindex="3" type="submit" class="invite-send-btn standard-btn" value="Send" name="send" />
    @endif