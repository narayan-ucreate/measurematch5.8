@extends('layouts.expert_layout')
@php
$job_id = $job_preview['id'];
$user_id = Auth::user()->id;
$buyer_id = $post_company->user_id;
jobViewUpdate($job_preview['id'], Auth::user()->id);
@endphp
@section('content')
<div id="wrapper" class="active find_next_opportunity_wrap">
  <div id="page-content-wrapper">
    <div class="page-content inset">
        <div class="col-md-3 leftSidebar custom-left-sidebar">
            @include('sellerdashboard.sidemenu')
        </div>
       <div class="col-md-9 rightcontent-panel">
         <div class="theiaStickySidebar">
            <div class="col-lg-12">
                <div class="col-lg-12 expert-breadcrumb">
                    <div class="row">
                        <div class="breadcrumb-bg project-details-breadcrumb">
                            @if(isset($_REQUEST['breadcrumb-page'])&& !empty($_REQUEST['breadcrumb-page']))
                            {!! Breadcrumbs::render($_REQUEST['breadcrumb-page'],['name'=>$job_preview['job_title'],'id'=>$job_preview['id']]) !!}
                            @else
                            {!! Breadcrumbs::render('projects_view',['name'=>$job_preview['job_title'],'id'=>$job_preview['id']]) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                  <div class="row">
                    <div class="post-job-view posted-project-view margin-b-32">
                        <div class="success share-project-message" style="color:green"></div>
                        <div class="job-view-top-section">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 job-preview-left-section">
                                <div class="data-analyst-section">
                                    <h4 id="job_title">{{$job_preview['job_title']}}</h4>
                                    <div class="clearfix"></div>
                                    <span class="job-view-office-location porject-location-panel org-name">
                                    @if ($job_preview['hide_company_name'] != config('constants.TRUE'))
                                        {{ ucfirst($post_company->company_name) }}
                                    @else
                                        {{ $post_company->type_of_organisation->name }} Company
                                    @endif
                                    </span>
                                    <span class="job-view-office-location porject-location-panel">
                                {{$office_locations = !empty($job_preview['office_location']) ? $job_preview['office_location'] : (' '.rtrim(trim(str_replace('<br/>',', ', $post_company->office_location)), ','))}}
                                </span>
                                    <div class="clearfix"></div>
                                    @if(isset($job_preview['publish_date']) && !empty($job_preview['publish_date']))
                                    <span class="job-view-posted-date">Posted <?php print_r(timeElapsedString($job_preview['publish_date'])); ?></span>
                                    @endif
                                    <span class="job-view-feedback">{{$job_preview['job_viewer_count']}} views</span>
                                    @if(isset($expert_job_interest) && !empty($expert_job_interest))
                                    <span class="job-view-intrest-shown"><span id="number_of_interests">{{$expert_job_interest}}</span> expressed interest</span>
                                    @endif
                                    @if(isset($job_preview['job_end_date']) && !empty($job_preview['job_end_date']))
                                    <span class="job-expire-date calendar-icon">
                                      <img src="{{url('images/project-start.svg',[],$ssl) }}" alt="calendar-icon" />
                                      <span class="expire-date-lbl">
                                        {{projectExpiryDateStatus($job_preview['job_end_date'])}}
                                    </span></span>
                                    @endif
                                    <p>
                                    <div id="show_interest" class="modal suggest-project-popup lightbox-design coverletter-popup lightbox-design-small">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-innner-content">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}" alt="" /></span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>Express Interest in {{$job_preview['job_title']}}</h3>
                                                        <h4 class="text-align-center">Please send a message to accompany your Expression of Interest.</h4>
                                                        <form>
                                                            {{csrf_field()}}
                                                            <div class="coverletter-box"><label class="gilroyregular-font"><span class="gilroyregular-bold-font">Write your message here:</span></label>
                                                                <textarea id="cover_letter_message" name="cover_letter_message" value=""></textarea>
                                                                <div id="cover_letter_error_message" class="error_message"></div>
                                                            </div>
                                                            <button type="button" id="show-interst-button" job-id="{{ $job_id }}" user-id="{{ $user_id }}" buyer-id="{{ $buyer_id }}" data-text-swap="Send Message & Express Interest" class="show-intrest-btn standard-btn" data-text-original="Send Message & Express Interest">Send Message & Express Interest</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="acknowledge_interest_shown" class="modal suggest-project-popup lightbox-design coverletter-popup lightbox-design-small">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-innner-content">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                                                    </div>
                                                    <div class="modal-body text-align-center">
                                                        <img class="margin-bottom-20 margin-top-10" src="{{ url('images/expressedInterest-success.svg',[],$ssl) }}" />
                                                        <div class="clearfix"></div>
                                                        <h3>You've just Expressed Interest in a project!</h3>
                                                        <h4 class="text-align-center">The Client decides if they'd like to engage in a conversation, so hold tight. To increase your chances of engaging in a project, express interest in more!</h4>
                                                        <div class="clearfix"></div>
                                                        <a href="{{ url('expert/projects-search',[],$ssl)}}" title="Browse more projects" class="browse-more-btn font-16 clearfix standard-btn"> Browse more projects</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="share_project" class="modal suggest-project-popup lightbox-design lightbox-design-small">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-innner-content">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{url('images/cross-black.svg',[],$ssl) }}"></span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>Do you know someone who is perfect for this project? Totally share it forward!</h3>
                                                        <h4 class="text-align-center">{{$job_preview['job_title']}}</h4>
                                                        <form id="refer_expert" action="{{ url('referExpert',[],$ssl)}}" method="post">
                                                            {{csrf_field()}}
                                                           <div class="input-bx">
                                                              <div class="row">
                                                               <div class="col-md-6 col-sm-6 col-xs-12 input-field">
                                                                  <label>First Name</label>
                                                                  <input id="referral_first_name" type="text" name="referral_first_name"/>
                                                               <div class="validate_expert_first_name_error error_message"></div>
                                                               </div>
                                                               <div class="col-md-6 col-sm-6 col-xs-12 input-field">
                                                                <label>Last Name</label> <input id="referral_last_name" type="text" name="referral_first_name"/>
                                                                <div class="validate_expert_last_name_error error_message"></div>
                                                                </div>
                                                               </div>
                                                            </div>
                                                            <input type="hidden"name="projectId" id="projectId" value="{{$job_preview['id']}}">
                                                            <input type="hidden"name="loggedInUserEmail" id="loggedInUserEmail" value="{{Auth::user()->email}}">
                                                            <div class="input-bx"><label>Email Address</label> <input id="referral_email" type="text" name="referral_email"/>
                                                                <div class="validate_expert_error error_message"></div>
                                                            </div>
                                                            <div class="clerfix"></div>
                                                            <input  type="button" id="projectSharedByExpert" value="Share" class="new_blue_btn standard-btn" />
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 id="shown-messages"></h6>
                                </div>
                            </div>
                        </div>
                        <div class="required-skills-section">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 job-preview-rate padding-left">
                                    <div class="data-analyst-section">
                                        <h5>Estimated Project Budget</h5>

                                         <span class="job-price">
                                         @if($job_preview['rate']>0)
                                            {{convertToCurrencySymbol($job_preview['currency'])}}{{((is_numeric($job_preview['rate']))?number_format($job_preview['rate']):$job_preview['rate'])}}@if($job_preview['rate_variable']=='daily_rate'){{'/day'}}@endif
                                         @else
                                          Negotiable
                                         @endif
                                          </span>
                                    </div>
                                </div>
                               @if(!empty($job_preview['remote']['name']))
                                <div class="job-sidebar-block col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-right">
                                    <h5>Location preference</h5>
                                    <p> @if($job_preview['remote']['id'] == 2)
                                        On Site
                                        @else
                                        {{$job_preview['remote']['name']}}
                                        @endif
                                        @if($job_preview['remote']['id'] != 1)
                                        ({{rtrim($office_locations, ',')}})
                                        @endif
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="description-of-project margin-0">
                            <h5>Description of Project</h5>
                            <div class="bottom-border"></div>
                            <p>{!! nl2br(e( $job_preview['description'])) !!}</p>
                        </div>
                        @if(_count($job_preview['deliverables']))
                           <div class="location-preference-widget deliverables-points">
                          <h5 class="margin-top-20">Deliverables</h5>
                          <div class="deliverables-list-style">
                            <ul>
                              @foreach($job_preview['deliverables'] as $deliverable)
                              <li>{!! $deliverable['deliverable'] !!}</li>
                              @endforeach
                            </ul>
                          </div>
                          </div>
                         @endif
                        @php $tools = getProjectTools($job_preview['id']) @endphp
                        @php $skills = getProjectSkills($job_preview['id']) @endphp
                        @if(_count($tools))
                        <div class="posted-project-skills">
                           <h5>Required Tools & Tech Expertise</h5>
                           <div class="bottom-border"></div>
                           <p>
                            @foreach($tools as $tool)
                               <span class="job_skill">{{ucfirst($tool->name)}}</span>
                               @endforeach
                            </p>
                         </div>
                         @endif
                        @if(_count($skills))
                        <div class="posted-project-skills">
                           <h5>Other Required skills</h5>
                           <div class="bottom-border"></div>
                           <p>
                             @foreach($skills as $skill)
                               <span class="job_skill">{{ucfirst($skill->name)}}</span>
                               @endforeach
                            </p>
                         </div>
                         @endif
                        <div class="required-skills-section">
                            <div class="row">
                                <div class="job-sidebar-block col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-left">
                                    <h5>Project duration</h5>

                                    @php $duration = convertDaysToWeeks($job_preview['project_duration']); @endphp
                                    <div class="job-preview-date">
                                        @if($duration['number_of_days'] !=0 )
                                        {{$duration['time_frame']}}@else{{'Unknown'}}@endif
                                    </div>
                                </div>
                                <div class="job-sidebar-block col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-right">
                                    <h5>Project number</h5>

                                    <p>@if(!empty($job_preview['job_num'])) {{$job_preview['job_num']}} @endif</p>
                                </div>
                                <div class="clearfix"></div>
                                @php $images= json_decode($job_preview['upload_document'],true); @endphp
                                @if(_count($images))
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 location-preference-widget attachment-list-style margin-bottom-30 padding-left">
                                 <h5>Attachments</h5>

                                 <ul>
                                    @foreach($images as $image)
                                    <li><a target='_blank' href="{{$image}}">{{getFileName($image)}}</a></li>
                                    @endforeach
                                 </ul>
                              </div>
                            @endif
                        </div>
                     </div>
                  </div>
               </div>
             </div>
              </div>
           </div>
        </div>
      </div>
   </div>
</div>
@include('include.basic_javascript_liberaries')
<script src="{{url('js/sellerJobView.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
