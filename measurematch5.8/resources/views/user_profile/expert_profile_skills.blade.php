<div class="skills-section content-block">
    <h4>Strategic & Advisory Skills</h4>
    @php
        $skills_got_from_database = [];
        $tools = [];
    @endphp
    <div class="skill_list_section">
        @forelse($user_profile['user_skills'] as $skill)
            @if (!$skill['skill']['is_tool'])
                @php $skills_got_from_database [] = $skill['skill']['id']  @endphp
            <span class="all-skills-tools" id="allskills{{ $skill['skill']['id']}}">
                <div class="btn_anchor_wrap selected">
                    <button class="btnskills capital">{{$skill['skill']['name']}}</button>
                    @if(sizeof($user_profile['user_skills']) >=1 )
                        <a href="javascript:void(0)" class="btnclose removeskill" id="{{$skill['skill']['id']}}" attr="">
                      <img src="{{url('images/black_cross.png',[],$ssl)}}" alt="black_cross" class="black_cross" />
                    </a> @endif
                </div>
            </span>
                @else
            @php
                $tools[] = ['id' => $skill['skill']['id'], 'name' => $skill['skill']['name']];
            @endphp
                @endif
                @empty
                <div class="nodatatext nodatatext-skill">Add any strategic and/or advisory skills you are Expert level in</div>

                @endforelse
   <div class="skill_list_section" id="new_added_skills"></div>
        <span id="skill_error" class="validation_error1"></span>
    </div>
    <?php
    if ($total_user_skills) {
        $value = "Add more skills";
    } else {
        $value = "Add Skills";
    }
    ?>
    <input type="hidden" name="skill_from_db" id="skill_from_db" value="{{implode(',',$skills_got_from_database)}}">
    <input class="add-grey-btn" type="button" id="addskill" value="{{$value}}">
</div>
<div class="add-skills-popup" style="display:none" id="addskillmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-body">
                <form name="addskillform" action="{{ url('/saveskills',[],$ssl)}}" method="post" id="add_skill_form">
                    {!! csrf_field() !!}
                    <div class="input-bx">
                        <label>Use a comma or enter for separate entries</label>                                                <input type="hidden" name="add_skill_hidden" id="add_skill_hidden" >
                        <input type="hidden" name="is_add_tool_form" id="is_add_tool_form" value="0" >
                        <input type="hidden" name="add_skill" value="" id="addskills">
                        <textarea tabindex="6" name="add_skills_display" id="add_skills_display" class="textarea-section addskillinput" maxlength="2000" size="30" placeholder="e.g Web Analytics, A/B Testing, Digital Strategy"></textarea>
                        <span id="skills_validation_error" class="validation_error"></span>
                    </div>
                    <input type="hidden" name="skills" id="skill_set">
                    <input tabindex="7" type="submit" id="addskillforms" name="skilladd" value="Save" class="blue-bg-btn standard-btn">
                    <a href="javascript:void(0)" class="add-grey-btn gilroyregular-bold-font cancel-btn-text cancel-skill">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="skills-section content-block">
    <h4>Tools & Technologies</h4>
    @php
        $tools_got_from_database = [];
    @endphp
    <div class="tool_list_section">
        @forelse($tools as $tool)
            @php $tools_got_from_database [] = $tool['id'] @endphp
            <span class="all-skills-tools" id="allskills{{$tool['id']}}">
                <div class="btn_anchor_wrap selected tool-technologies-tags">
                    <button class="btnskills capital">{{ $tool['name']}}</button>
                    @if(sizeof($user_profile['user_skills']) >=1 )
                        <a href="javascript:void(0)" class="btnclose removetool" id="{{$tool['id']}}" attr=""> <img src="{{url('images/black_cross.png',[],$ssl)}}" alt="black_cross" class="black_cross" />
                    </a>
                    @endif
                  </div>
            </span>
            @empty
            <div class="nodatatext nodatatext-tools">Add any tools and/or technology skills you are Expert level in</div>
            @endforelse
        <div class="tool_list_section" id="new_added_tools"></div>
        <span id="tools_error" class="validation_error1"></span>
    </div>
    <?php
    if ($total_user_tools) {
        $value = "Add more Tools & Tech";
    } else {
        $value = "Add Tools & Tech";
    }
    ?>
    <input type="hidden" name="tools_from_db" id="tools_from_db" value="{{implode(',',$tools_got_from_database)}}">
    <input class="add-grey-btn" type="button" id="addtool" value="{{$value}}">
</div>
<div class="add-tools-popup" style="display:none" id="addtoolmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-body">
                <form name="addtoolform" action="{{ url('/saveskills',[],$ssl)}}" method="post" id="add_tool_form">
                    {!! csrf_field() !!}
                    <div class="input-bx">
                        <label>Use a comma or enter for separate entries</label>
                        <input type="hidden" name="is_add_tool_form" id="is_add_tool_form" value="1" >

                        <input type="hidden" name="add_tool" value="" id="addtools">
                        <textarea tabindex="6" name="add_tools_display" id="add_tools_display" class="textarea-section addskillinput" maxlength="2000" size="30" placeholder="e.g Google Analytics, Adobe Analytics, Optimizely..."></textarea>
                        <span  class="tools_validation_error validation_error"></span>
                    </div>
                    <input type="hidden" name="tools" id="tool_set">
                    <input tabindex="7" type="submit" id="addtoolsubmit" name="addtool" value="Save" class="blue-bg-btn standard-btn">
                    <a href="javascript:void(0)" class="add-grey-btn gilroyregular-bold-font cancel-btn-text cancel-tool">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="language-section content-block" id="language_list" style="display:block">
    <h4>Languages</h4>
    <div class="lang-listing-user">
        @if(isset($user_profile['user_languages']) && !empty($user_profile['user_languages']))
            @foreach($user_profile['user_languages'] as $languages)
                <div class="edit_view edit_lang_button" data-toggle="modal" id="{{$languages['language']['id']}}">
                    <h5>{{ucwords($languages['language']['name'])}} <a href="javascript:void(0)" class="edit_icon">
                            <img width="15" alt="pen" src="{{url('images/pen.png',[],$ssl)}}"></a> </h5>
                    <h5 class="grey-text">{{$languages['language_proficiency']}}</h5>
                </div>
                <div class="profile-page-popup edit-language-popup language-{{$languages['language']['id'] }}" style="display:none" id="" tabindex="-1" >
                    <div class="modal-dialog" role="document">
                        <div class="modal-innner-content">
                            <div class="">
                                <form method="post" name="editlanguage" id="editlanguage-{{$languages['language']['id']}}" action="{{url('editlanguage',[],$ssl)}}">
                                    <div class="modal-body"> {{ csrf_field() }}

                                        <input type="hidden" name="editlanguageid" value="{{$languages['language']['id']}}" id="edit_language_id">
                                        <div class="input-bx">
                                            <label>Language <span class="notification_star">*</span></label>
                                            @if($languages['language']['name']=='English')
                                                <input tabindex="8" type="text" placeholder="e.g Spanish" name="edituserlanguage"  class="input_pointer_disable" onKeyUp="javascript:capitalize(this.id, this.value);" value="@if(isset($languages['language']['name']) && !empty($languages['language']['name'])){{$languages['language']['name']}}@endif" id="edit_user_language" maxlength="20" readonly>
                                            @else
                                                <input tabindex="8" type="text" placeholder="e.g Spanish" name="edituserlanguage" onKeyUp="javascript:capitalize(this.id, this.value);" value="@if(isset($languages['language']['name']) && !empty($languages['language']['name'])){{$languages['language']['name']}}@endif" id="edit_user_language" maxlength="20" >
                                            @endif
                                        </div>
                                        <div class="input-bx select-box">
                                            <label>Proficiency</label>
                                            <select  tabindex="9" name="editlanguageproficiency" id="edit_language_proficiency_{{$languages['language']['id'] }}" class="selectpicker">
                                                <option value="">Choose </option>
                                                <option @if(isset($languages['language_proficiency']) && ($languages['language_proficiency']=='Native or bilingual proficiency')){{ 'selected=selected'}} @endif value="Native or bilingual proficiency">Native or bilingual proficiency</option>
                                                <option @if(isset($languages['language_proficiency']) && ($languages['language_proficiency']=='Professional working proficiency')){{ 'selected=selected'}} @endif value="Professional working proficiency">Professional working proficiency</option>
                                                <option @if(isset($languages['language_proficiency']) && ($languages['language_proficiency']=='Limited working proficiency')){{ 'selected=selected'}} @endif value="Limited working proficiency">Limited working proficiency</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                        <span class="validation_error"></span>
                                    </div>
                                    <input tabindex="10" type="submit" id="editlangsavebutton" class="editlang standard-btn blue-bg-btn" name="submit" value="Save" id="{{$languages['language']['id']}}">
                                    <a href="javascript:void(0)" class="add-grey-btn edit-cancel-language" data-id="{{$languages['language']['id']}}" language-proficiency="{{$languages['language_proficiency']}}">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <?php
        if (isset($user_profile['user_languages']) && !empty($user_profile['user_languages'])) {
            $value = "Add another language";
        } else {

            $value = "Add language";
        }
        ?>
        <input tabindex="11" class="add-grey-btn add_lang_button" type="button" value="{{$value}}" id="add_lang_button">
    </div>
</div>
<div class="add-language-popup" style="display:none" id="">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-body">
                <form method="post" name="addlanguage" id="addlanguage" action="{{url('savelanguage',[],$ssl)}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="languageid" value="" id="languageid">
                    <input type="hidden" id="clicked_button">
                    <div class="input-bx">
                        <label>Language <span class="notification_star">*</span></label>
                        <input tabindex="12" type="text" placeholder="e.g Spanish" name="userlanguage" onKeyUp="javascript:capitalize(this.id, this.value);" id="userlanguage" maxlength="20">
                        <div class="clearfix"></div>
                        <span class="validation_error"></span>
                    </div>
                    <div class="input-bx select-box">
                        <label>Proficiency</label>
                        <select tabindex="13" name="languageproficiency" id="language_proficiency" class="selectpicker">
                            <option value="">Choose </option>
                            <option value="Native or bilingual proficiency">Native or bilingual proficiency</option>
                            <option value="Professional working proficiency">Professional working proficiency</option>
                            <option value="Limited working proficiency">Limited working proficiency</option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <input tabindex="14" type="submit" class="blue-bg-btn standard-btn" name="submit" id="addlang" value="Save" >
                    <a href="javascript:void(0)" class="add-grey-btn edit-cancel-language cancel-btn-text gilroyregular-bold-font">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('js/expertEdit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/sellerprofile.js?js='.$random_number,[],$ssl)}}"></script>
<script src="{{url('js/expert_profile_common.js?js='.$random_number,[],$ssl)}}"></script>
