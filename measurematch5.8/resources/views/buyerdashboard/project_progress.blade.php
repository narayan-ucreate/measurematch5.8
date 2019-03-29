@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
  <div id="page-content-wrapper">
    <div class="page-content inset">
      <div class="col-md-3 leftSidebar">
        <div class="theiaStickySidebar">
          @include('buyer.sidemenu')
        </div>
      </div>
       <div class="col-md-9 rightcontent-panel">
         <div class="theiaStickySidebar">
            <div class="breadcrumb-bg project-details-breadcrumb">
            <input type="hidden" id="post_id" value='{{$job_details['id']}}'>
              <ul>
                  <li><a href="{{url('myprojects',[],$ssl)}}" alt='My Projects'>My Projects</a></li>
                  <li>@if(strlen($job_details['job_title'])>=17){{ucfirst(substr($job_details['job_title'],0,17))}}...@else{{ucfirst($job_details['job_title'])}}@endif</li>
              </ul>
          </div>
          <div class="clearfix"></div>
          <div class="row">
             <div class="col-md-12">
               @if(Session::has('status'))
                <div class="col-md-12">
                   <div class="alert alert-info fade in alert-dismissable">
                      <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                      {{Session::get('status')}}
                  </div>
                </div>
               @endif
               <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 my-projects-list-view my-project-details arrow-tab-theme">
          <div class="white-box">
            <div class="white-box-header">
                <div id="general_error"></div>
              <h3>{{ucfirst($job_details['job_title'])}}</h3>
                @if($job_details['publish']==3)
                    <h5 class="awaiting-approval"><img src="{{ url('images/awaiting-approval.svg',[],$ssl)}}" alt="awaiting-approval" /> Awaiting approval</h5>
                @else
                <h5 class="posted-time"> Posted on {{date('d/m/Y', strtotime($job_details['publish_date']))}}</h5>
                @endif
              <a href="javascript:void(0)" class="nav-tabs-dropdown btn btn-block btn-primary">Tabs</a>
              <ul id="nav-tabs-wrapper" class="nav nav-tabs">
                <li @if($job_details['publish']==3) class="active" @endif><a href="#viewprojectpost" data-toggle="tab">View Project Post</a></li>

                <li @if($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts'])==0) class="active" @endif><a href="#inviteexperts" data-toggle="tab">Invite Experts</a></li>
                 <!--&& count($job_details['contract'])==0-->
                <li @if(($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts']) && $job_details['accepted_contract_id']==NULL) || !empty($job_details['accepted_contract_id']) && $job_details['accepted_contract_complete_status'] == FALSE) class="active" @endif><a href="#contractoffer" data-toggle="tab">Contract Offer</a></li>
                <li @if($job_details['publish']==1 && _count($job_details['contracts']) && $job_details['accepted_contract_complete_status'] == TRUE) class="active" @endif><a href="#finishproject" data-toggle="tab">Finish Project</a></li>
              </ul>
            </div>
            <div class="white-box-content expert-tabs-section">
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade @if($job_details['publish']==3) in active @endif" id="viewprojectpost">
                  <div class="view-project-post">
                    <div class="required-skills-section">
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 project-budget-widget">
                            <h5 class="margin-top-0">Estimated Project Budget</h5>
                            <span class="job-price">
                                @if($job_details['rate']>0)
                                {{$job_details['currency']}}{{((is_numeric($job_details['rate']))?number_format($job_details['rate']):$job_details['rate'])}}@if($job_details['rate_variable']=='daily_rate'){{'/day'}}@endif
                                 @else
                                Negotiable
                                @endif
                            </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 location-preference-widget">
                          <h5 class="margin-top-0">Location preference</h5>
                          <p>{{$location_preference_array[$job_details['remote_id']]}}
                              @if($job_details['remote_id'] != 1)
                              (
                                @if(!empty($job_details['office_location']))
                                    {{$job_details['office_location']}}
                                @else
                                    {{$job_details['buyer']['office_location']}}
                                @endif
                                )
                              @endif
                          </p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="porject-brief">
                          <h4 class="margin-top-0">Description of Project</h4>
                          <p>{!!nl2br(e( $job_details['description'])) !!}</p>
                        </div>
                        <div class="clearfix"></div>
                        @if(_count($job_details['deliverables']))
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 location-preference-widget deliverables-points">
                          <h5>Deliverables</h5>
                          <div class="deliverables-list-style">
                            <ul>
                              @foreach($job_details['deliverables'] as $deliverable)
                              <li> {!! ucfirst($deliverable['deliverable']) !!}</li>
                              @endforeach
                            </ul>
                          </div>
                        </div>
                        @endif
                      <div class="jobskill-panel">
                        @if(_count($tools))
                        <div class="required-skills col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <h4>Required Tools & Tech Expertise</h4>
                          @foreach($tools as $key=>$tool)
                            <span class="skill-button">{{$tool}}</span>
                          @endforeach
                        </div>
                        @endif
                        @if(_count($skills))
                        <div class="required-skills col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <h4>Other Required Skills</h4>
                          @foreach($skills as $key=>$skill)
                            <span class="skill-button">{{$skill}}</span>
                          @endforeach
                        </div>
                        @endif
                       </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 project-estimation-widget margin-bottom-20">
                          <h5>Project Duration</h5>
                          <div class="no-project-duration">{{convertDaysToWeeks($job_details['project_duration'])['time_frame']}}</div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 project-expire-widget">
                          <h5>Project Start</h5>
                          <div class="job-preview-date"> <span>{{date('d M y', strtotime($job_details['job_end_date']))}}</span></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 project-number-widget">
                          <h5>Project number</h5>
                          <p> {{$job_details['job_num']}} </p>
                        </div>
                         @if(_count($images))
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 location-preference-widget margin-bottom-20">
                          <h5>Attachments</h5>
                          <div class="attachment-list-style">
                            <ul>
                              @foreach($images as $image)
                              <li><a target='_blank' href="{{$image}}">{{getFileName($image)}}</a></li>
                              @endforeach
                            </ul>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                    <div role="tabpanel" class="tab-pane fade invite-expert-tab @if($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts'])==0) active in @endif" id="inviteexperts">

                      <!-- Nav tabs -->
                      <ul class="nav sub-nav-tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#Recommended" aria-controls="home" role="tab" data-toggle="tab" id="recommended_experts">Recommended Matches</a></li>
                        <li role="presentation"><a href="#Saved" aria-controls="profile" role="tab" data-toggle="tab" id="saved_experts">Saved Experts</a></li>
                        <li role="presentation"><a href="#Matches" aria-controls="messages" role="tab" data-toggle="tab" id="past_matches">Past Matches</a></li>
                      </ul>


                      <!-- Tab panes -->
                      <div class="tab-content">
                          <div role="tabpanel" class="tab-pane active recommended_experts_div" id="Recommended">
                              @php $count=0;@endphp
                              @foreach($random_experts as $key=>$expert)
                                @php $count++;
                                    if(strlen($expert['active_expert']['name'])>17){
                                        $full_name=ucfirst(substr($expert['active_expert']['name'],0,17)).'...';
                                    }else{
                                        $full_name=ucfirst($expert['active_expert']['name']).' '.ucfirst(substr($expert['active_expert']['last_name'],0,1));
                                    }
                                @endphp
                                    <div class="col-lg-4 col-md-4 col-sm-4 expert-widget">
                                        <div class="row widget-white-box">

                                            <span style="background-image:url({{ getImage($expert['active_expert']['user_profile']['profile_picture'],$ssl)}});" class="expert-profile-pic"></span>

                                            <div class="expert-info-block">
                                                <span id="error_{{$expert['user_id']}}" class="validation_error"></span>
                                                <a href="javascript:void(0)" class="saved-expert saved-icon save_remove_expert @if(checkIfExpertSavedByBuyer($expert['user_id'], Auth::user()->id, 'project_progress')>0) selected-expert @endif" expert_id='{{$expert['user_id']}}'></a>
                                                <h4>{{$full_name}}</h4>
                                                <span class="expert-name">@if(strlen($expert['active_expert']['user_profile']['describe'])>57){{substr($expert['active_expert']['user_profile']['describe'],0,57).'...'}}@else{{$expert['active_expert']['user_profile']['describe']}}@endif</span>
                                                <span class="expert-location">@if(strlen($expert['active_expert']['user_profile']['current_city'])>27){{substr($expert['active_expert']['user_profile']['current_city'],0,27).'...'}}@else{{$expert['active_expert']['user_profile']['current_city']}}@endif</span>
                                            </div>
                                            <a href="{{url('buyer/expert-profile/' . $expert['user_id'], [], $ssl)}}" class="view-profile-btn">View Profile</a>
                                        </div>
                                    </div>
                                @if($count==3)
                                  <div class="clearfix"></div>
                                @endif
                              @endforeach
                          </div>

                          <div role="tabpanel" class="tab-pane saved_experts_div pagination-container" id="Saved"></div>

                          <div role="tabpanel" class="tab-pane past_matches_div pagination-container" id="Matches"></div>
                      </div>


                </div>

                <div role="tabpanel" class="tab-pane fade contract-offer-tab @if(($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts']) && $job_details['accepted_contract_id'] == NULL) || !empty($job_details['accepted_contract_id']) && $job_details['accepted_contract_complete_status'] == FALSE) active in @endif" id="contractoffer">
                  <div class="row">
                  <div class="headingpanel">Offers (@if(isset($job_details['contracts'])){!!_count($contracts_count)!!}@else{!!0!!}@endif)</div>
                  @if(($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts'])==0) || $job_details['publish']!=config('constants.APPROVED'))
                  <div class="col-lg-12">
                    <div class="finish-project-empty-state">
                    <img src="{{ url('images/contract_offers.svg',[],$ssl)}}"  class="shape-icon" />
                    <h6>This is where you can see your contract offers that <br /> you’ve made to Experts.</h6>
                    <a href="{{url('buyer/experts/search',[],$ssl)}}" class="btn btn-primary browse-expert-btn">Browse Experts</a>
                  </div>
                  </div>
                  @elseif($job_details['publish']==1 && _count($job_details['contracts']))
                    @foreach($job_details['contracts'] as $key => $contract)
                        @if(!$contract['parent_contract_id'])
                        <div class="offerscontent">
                            <div class="row">
                                <div class="offerrow">
                                    <a href="{{url('/buyer/expert-profile/'.$contract['expert']['id'],[],$ssl)}}">
                                        <span class="imgblock" style="background-image:url({{ url($contract['expert']['user_profile']['profile_picture'])}}"></span>
                                    </a>
                                    <div class="offerdetail">
                                        <h3><a href="{{url('/buyer/expert-profile/'.$contract['expert']['id'],[],$ssl)}}">{{ucfirst($contract['expert']['name'])}} {{ucfirst(substr($contract['expert']['last_name'],0,1))}}</a></h3>
                                        <p>{{ucfirst($contract['expert']['user_profile']['describe'])}}</p>
                                    </div>
                                    <a href="{{url('buyer/messages/'.$contract['type'].'/'.$contract['job_post_id'].'?view_communication_contract='.$contract['communications_id'], [], $ssl)}}" target="_blank" title="View offer" class="view-message-btn editcontract standard-btn pull-right">View offer</a>
                                    @if(isset($contract['status']) && ($contract['status'] ==1) || $contract['complete_status']='')
<!--                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#view_contract-{{ $contract['id'] }}" contract_id='{{ $contract['id'] }}' data-dismiss="modal" class="editcontract standard-btn pull-right" title="View offer">View offer</a>-->
                                    @else
<!--                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#view_contract-{{ $contract['id'] }}" contract_id='{{ $contract['id'] }}' data-dismiss="modal" class="editcontract standard-btn pull-right" title="@if(!empty($job_details['accepted_contract_id'])) View Offer @else View/edit offer @endif">@if(!empty($job_details['accepted_contract_id'])) View offer @else View/edit offer @endif</a>-->
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                  @endif
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade finish-project-tab @if($job_details['publish']==1 && _count($job_details['contracts']) && $job_details['accepted_contract_complete_status'] == TRUE) active in @endif" id="finishproject">
                @if(($job_details['publish']==config('constants.APPROVED') && _count($job_details['contracts'])==0) || _count($job_details['contracts']) && $job_details['accepted_contract_complete_status'] == FALSE || $job_details['publish']!=config('constants.APPROVED'))
                    <div class="col-lg-12">
                      <div class="finish-project-empty-state">
                      <img src="{{ url('images/finish_project.svg',[],$ssl)}}"  class="shape-icon" />
                      <h6>When your project is finished, you will be able to mark<br /> it as complete and review the expert.</h6>
                      <a href="{{url('buyer/experts/search',[],$ssl)}}" class="btn btn-primary browse-expert-btn">Browse Experts</a>
                      </div>
                    </div>
                @else
                    <div class="row">
                       @if($job_details['accepted_contract']['buyer_feedback_status']!=1)
                       <div class="finish-project-tab" id='feedback_form'>
                            <div class="col-lg-12">
                                <span class="feedback-buyer-image" style="background-image:url('{{$job_details['accepted_contract']['expert']['user_profile']['profile_picture']}}');"></span>
                                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 buyer-comment-box">
                                    <div class="row">
                                        <form>
                                            <h4>How was {{ucfirst($job_details['accepted_contract']['expert']['name'])}} on this project?</h4>

                                            <div class="star-rating-widget">
                                                <span class="star-rating">Star rating</span>
                                                <div id="rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx"></div>
                                                <div class="rating_validation_error validation_error validation-message-error clearfix"></div>
                                            </div>

                                            <label>Explain why you’ve given this star rating</label>
                                            <textarea rows="5" cols="25" maxlength="254" placeholder="Start typing here" id="feedback_comment"></textarea>
                                            <div class="feedback_validation_error validation_error validation-message-error clearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-cta review-btn" id="buyer_feedback" sender_id='{{Auth::user()->id}}' receiver_id='{{$job_details['accepted_contract']['expert']['id']}}' communications_id='{{$job_details['accepted_contract']['communications_id']}}' contract_id='{{$job_details['accepted_contract']['id']}}'>Review {{ucfirst($job_details['accepted_contract']['expert']['name'])}} & finish project</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="posted-feedback-widget" id='feedback_view'>
                            <div class="col-lg-8 col-md-10 col-xs-12 buyer-comment-box">
                                    <h3>All done!</h3>

                                    <h6>Your review for {{ucfirst($job_details['accepted_contract']['expert']['name'])}} was sent.</h6>

                                <div class="commented-widget">
                                    <div class="expert-feedback-img-widget">
                                            <a href="javascript:void(0)" class="expert-feedback-img" style="background-image:url('{{$job_details['accepted_contract']['expert']['user_profile']['profile_picture']}}');"></a>
                                    </div>

                                    <div class="expert-comment-widget">
                                        <h4>{{ucfirst($job_details['accepted_contract']['expert']['name'])}}</h4> <span class="expert-star-rating"><div id="show_rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx" expert_rating="{{$job_details['accepted_contract']['expert_rating']}}"></div><strong><span id="rating_value">{{number_format($job_details['accepted_contract']['expert_rating'],1)}}</span></strong></span>
                                            <p id="feedback_para">@if($job_details['accepted_contract']['buyer_feedback_status']==1)"{!!$job_details['accepted_contract']['feedback_comment']!!}"@endif</p>
                                            <a href="javascript:void(0)" title="Edit" class="edit-comment-widget" id="edit_feedback">Edit</a>
                                    </div>

                                </div>
                                    <button class="btn btn-primary btn-cta review-btn" id="new_project">New project</button>
                            </div>
                        </div>
                        @endif

                        <div class="finish-project-tab" id='edit_feedback_form' style="display: none;">
                            <div class="col-lg-12">
                                <span class="feedback-buyer-image" style="background-image:url('{{$job_details['accepted_contract']['expert']['user_profile']['profile_picture']}}');"></span>
                                <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 buyer-comment-box">
                                    <div class="row">
                                        <form>
                                            <h4>How was {{ucfirst($job_details['accepted_contract']['expert']['name'])}} on this project?</h4>

                                            <div class="star-rating-widget">
                                                <span class="star-rating">Star rating</span>
                                                <div id="edit_rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx"></div>
                                                <div class="edit_rating_validation_error validation_error validation-message-error clearfix"></div>
                                            </div>

                                            <label>Explain why you’ve given this star rating</label>
                                            <textarea rows="5" cols="25" maxlength="254" placeholder="Start typing here" id="edit_feedback_comment">{!!$job_details['accepted_contract']['feedback_comment']!!}</textarea>
                                            <div class="edit_feedback_validation_error validation_error validation-message-error clearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-cta review-btn" id="edit_buyer_feedback" sender_id='{{Auth::user()->id}}' receiver_id='{{$job_details['accepted_contract']['expert']['id']}}' communications_id='{{$job_details['accepted_contract']['communications_id']}}' contract_id='{{$job_details['accepted_contract']['id']}}'>Update review for {{ucfirst($job_details['accepted_contract']['expert']['name'])}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
              </div>
            </div>
          </div>
        </div>
               <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 edit-delete-widget">
          <div class="white-box">
            <div class="white-box-content">
               <ul>
                  <li class="edit-icon">
                      <a  @if(!empty($job_details['accepted_contract_id'])) style="pointer-events: none; cursor: default;" @endif href="{{ url('project/edit/'.$job_details['id'],[],$ssl)}}"><img src="{{ url('images/edit.svg',[],$ssl)}}" alt="edit" />Edit Project</a></li>
                  @if($job_details['publish']==config('constants.PROJECT_PENDING'))
                  <li class="delete-project-icon">
                      <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">
                          <img  src="{{ url('images/delete.svg',[],$ssl)}}" alt="delete" />Delete Project
                      </a>
                  </li>
                  @endif
               </ul>
            </div>
          </div>
        </div>
             </div></div></div>
       </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade delete-project-popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <h3>Delete this project?</h3>
        <p>This project will be permanently deleted and cannot be recovered.
        </p>

        <button class="btn btn-primary btn-xs btn-danger delete_project" project_id="{{$job_details['id']}}">Delete Project</button>
        <a href="javascript:void(0)" class="cancel-btn btn" data-dismiss="modal" aria-label="Close">Cancel</a>
      </div>
    </div>
  </div>
</div>

<!-- Start of Edit/update contract popup -->
<div class="modal invite-seller-popup send_contract_popup lightbox-design lightbox-design-small fade" id="edit_contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<!-- Start of view contract popup -->
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_contract" class="modal mark_completed_project got-match-popup seller-contract-popup invite-seller-popup lightbox-design-small lightbox-design fade in"></div>
<!--coupon code  preview starts-->
<div aria-labelledby="myModalLabel" data-dismiss="modal" role="dialog"  id="apply_coupon_pop_up" class="modal mark_completed_project got-match-popup lightbox-design-small lightbox-design coupon-code-popup invite-seller-popup fade in" style=""></div>
<!--mark project as complete preview starts-->
<div class="modal fade mark_completed_project lightbox-design lightbox-design-small profile-page-popup profile-picture-popup profile-picture-popup-seller" id="mark_as_complete_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div id="billing_detail_pop_up"></div>
<link href="{{ url('css/buyer-dashboard.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type="text/css"/>
<link href="{{ url('css/buyer-dashboard-tabs.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type="text/css"/>
<link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
@include('include.buyer_mobile_body')
@include('include.basic_javascript_liberaries')
<script type="text/javascript" src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/project_progress.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap-datetimepicker.min.js?js='.$random_number,[],$ssl)}}"></script>
@include('include.footer')
@endsection
