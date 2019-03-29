<div class="measurematch-history content-block add-bio-content">
    <h4>Your Story</h4>

    <div class="edit_view edittextarea"> @if(isset($user_profile['user_profile']['summary']))
            @php $bio_data = $user_profile['user_profile']['summary'];
            @endphp
            <div class="remove_bio">
                <p class="formated-user-bio">{!! nl2br(e(  $bio_data)) !!}
                    <a href="javascript:void(0)" class="edit_bio edit_icon">
                        <img width="15" alt="pen" src="{{ url('images/pen.png',[],$ssl) }}">
                    </a>
                </p>
            </div>
        @else
            <div class="remove_bio normal-view">
                <p class="nodatatext">What would you like a prospective client to read about you?
                    <a class="edit_bio" href="javascript:void(0)" data-toggle="modal">
                        Add your story
                    </a>
                </p>
            </div>
        @endif
        <div class="edit_bio_expert" style="display:none">
            <form method="post" name="editbioform" id="editbioform"
                  action="{{url('editsellerbio',[],$ssl)}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="bioid" id="bioid" value="{{$user_profile['user_profile']['id']}}" >
                <div class="input-bx textarea-bx">
                    @if(isset($user_profile['user_profile']['summary']))
                        <textarea tabindex="5" class="textarea-section" id="bio"  maxlength="2000" name="bio"
                          placeholder="What would you like a prospective client to read about you?">{!! str_replace(('<br />'), '',$bio_data) !!}</textarea>
                    @else
                        <textarea tabindex="5" class="textarea-section" id="bio"
                                  placeholder="What would you like a prospective client to read about you?"
                                  maxlength="2000" name="bio"></textarea>
                    @endif
                    <span class="validation_error"></span>
                </div>
                @if ($errors->has('bio')) <span class="help-block">
                                             <strong>{{ $errors->first('bio') }}</strong></span>
                @endif
                <input tabindex="6" type="submit" id="editbiosavebutton" class="blue-bg-btn standard-btn"
                       value="Save" name="editbio">
                <a href="javascript:void(0)" class="add-grey-btn cancel-bio pull-left gilroyregular-bold-font cancel-btn-text">
                    Cancel
                </a>
            </form>
        </div>
    </div>
    <div class="language-section content-block" id="language_list">
        <h4>Client Feedback</h4>
        <div class="lang-listing-user">
                @forelse($user_profile['contract_feedbacks'] as $contract)
                    @if($contract['buyer_feedback_status']==1)
                        <div class="feedback-block" data-toggle="modal" id="{{$contract['id']}}">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 feedback-rating">
                                    <input type="hidden" id="rating-{{$contract['id']}}" value="{{$contract['expert_rating']}}"/>
                                    <span data-rating="{{$contract['expert_rating']}}" name="expert_rating" class="rating-list rateyo-readonly-widg-{{$contract['id']}} input-bx deliverable_bx"></span>
                                    @if($contract['type']=='project')
                                       @php $job_title = $contract['post_jobs']['job_title'] @endphp
                                    @else
                                        @php $job_title = $contract['service_package']['name'] @endphp
                                    @endif
                                    <span class="feedback-title">{{ucwords($job_title)}}</span>
                                    @php
                                    $feedback_time = date('M Y', strtotime($contract['feedback_time']));
                                    if ($feedback_time == 'Jan 1970') {
                                        $feedback_time = "";
                                    }
                                    @endphp
                                    <span class="feedback-time">{{$feedback_time}}
                                        {{$contract['buyer']['office_location']}}
                                                        </span>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 buyer-feedback read-more-section">

                                    <b><span class="feedback-company-name">{{ucwords($contract['buyer']['first_name'])}},
                                            {{ucwords($contract['buyer']['company_name'])}}
                                    </span>
                                    </b>
                                    <p>
                                    @php    $class = ''; @endphp
                                    @if (strlen($contract['feedback_comment']) > config('constants.EXPERT_PROFILE_FEEDBACK_LIMIT'))
                                        @php  $class = 'hide' @endphp
                                        <span class="short-description">
                                            <span class="more">  {{substr($contract['feedback_comment'], 0, config('constants.EXPERT_PROFILE_FEEDBACK_LIMIT'))}}</span>
                                            ... <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-more">Read more</a>
                                        </span>
                                    @endif
                                    <span class="full-description {{$class}}">
                                        <span class="more">{!! nl2br(e( $contract['feedback_comment'] )) !!}</span>
                                        @if ($class !== '')
                                            <a href="javascript:void(0)" class="readmore-lin gilroyregular-semibold read-less">Read less</a>
                                        @endif
                                    </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    @endif
                    @empty
                        <span class="no-client-feedback-message nodatatext">No client feedback at present </span>
                @endforelse

                @if($user_profile['user_service_hubs'])
                    <div class="content-block">
                        <h4>Verified by Vendors</h4>
                            @foreach($user_profile['user_service_hubs'] as $service_hub)
                                <div class="verified-by-vendor-section">
                                    <span class="service-hub-logo-img" style="background-image: url({{$service_hub['service_hub']['logo']}})"></span>
                                    <div class="view-more-hub-section">
                                        <span class="vendor-title">{{$service_hub['service_hub']['name']}}</span>
                                        <a target="_blank" href="{{route('vendor-service-hubs-details', $service_hub['service_hub']['id'])}}">View Hub</a>
                                    </div>                                    
                                </div>
                            @endforeach
                    </div>
                @endif
        </div>
    </div>
</div>

<script src="{{ url('js/expertEdit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/sellerprofile.js?js='.$random_number,[],$ssl)}}"></script>