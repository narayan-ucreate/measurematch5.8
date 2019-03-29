@extends('layouts.buyer_layout')
@section('content')
<div id="wrapper" class="active buyerdesktop_buyer">
<div id="page-content-wrapper">
   <div class="page-content inset">
      <div class="col-md-3 leftSidebar">
            @include('buyer.sidemenu')
      </div>
      <div class="col-md-9 rightcontent-panel">
      <div class="theiaStickySidebar">

         @if(Session::has('profilepicerror'))
         <h5 class="error-message">{{Session::get('profilepicerror')}}</h5>
         @endif
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-left-section">
            <div class="buyer-profile-left-side">
               <div class="modal-body">
                  <span id="imageerrormsg" class="error-message"></span>
                  <input type="hidden" value="{{url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl)}}" id="default_avatar">
                  <input type="hidden" value="" id="default_avatar_base64">
                  <form method="post" name="logo_form" id="logo_form" action="{{url('/addlogo',[],$ssl)}}" enctype="multipart/form-data">
                     <div class="profile-image upload_add user-profile-image user_pic">
                        <span id="blah1" class="profilespan"></span>
                        {{ csrf_field() }}
                        <input type="hidden" name="base64image" id="base64image" value=""/>
                        <img class="uploaded_img" id="blah" src="javascript:void(0)" alt="" style="display:none;" />
                        @if(!empty(trim($buyer_data->profile_picture)))
                        <span class="fileinput-new uploaded-profile-pic" id="show_image_pop_profile">
                           <div style="background-image:url({{ $buyer_data->profile_picture }});" alt="" class="profilepicture" >
                           </div>
                           <img class="camera_small" alt="Camera_small" src="{{ url('images/Camera_small_demo.png',[],$ssl) }}">
                           <span>upload photo</span>
                        </span>
                        @else
                        <span class="fileinput-new blank_buyer_img" id="show_image_pop_profile">
                           <div style="background-image:url({{url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl)}})" class="profilepicture no-user-image"></div>
                           <!--                                    <img  alt="Camera" class="user_pic"/> -->
                           <img src="{{ url('images/Camera_small_demo.png',[],$ssl) }}" alt="Camera" class="lg-Camera" />
                        </span>
                        @endif
                        <span class="fileinput-exists" id="show_change_image_pop" style="display:none" >
                        <img class="camera_small" alt="Camera_small" src="{{ url('images/Camera_small_demo.png',[],$ssl) }}">
                        </span>
                     </div>
                  </form>
                  <input type="hidden" id="image_url" name="image_url"/>
                  <input type="hidden" id="zoomlevel" name="zoomlevel"/>
                  <input type="hidden" id="cropitimage" name="cropitimage"/>
               </div>
               <div class="summary-section summary-info-buyer">
                  <span style="color: red;font-size: 13px;line-height: 0px;" id="image_error">@if(Session::has('image_error')){{Session::get('image_error')}}@endif</span>
                  @if(isset($buyer_data->first_name) || isset($buyer_data->last_name))
                  <h3>{{ ucfirst($buyer_data->first_name) }} {{ ucfirst($buyer_data->last_name) }}</h3>
                  <?php $ofcLocations = explode("<br/>", $buyer_data->office_location); ?>
                  <div class="edit-profile-section edit_view">
                     @else
                     @endif
                     <?php
                        if (!preg_match("~^(?:f|ht)tps?://~i", $buyer_data->company_url)) {
                            $url = "http://" . $buyer_data->company_url;
                        } else {
                            $url = $buyer_data->company_url;
                        }

                        $strCnt = strlen($buyer_data->company_url);
                        if ($strCnt > 25) {
                            $addDot = '...';
                        } else {
                            $addDot = '';
                        }
                        if ($buyer_data->company_url != '') {
                            $companyUrl = substr($buyer_data->company_url, 0, 25);
                        } else {
                            $companyUrl = '';
                        }
                        ?>
                     <div class="remove_view" >
                        <a id="cmpy_edit" class="edit_icon" href="javascript:void(0)" title="edit">
                          <img src="{{ url('images/pen.png',[],$ssl) }}" alt="pen">
                        </a>
                        <ul>
                           <li><img src="{{ url('images/ic_company.png',[],$ssl) }}" alt="company" class="company-icon" /><span>{{ ucfirst($buyer_data->company_name) }}</span></li>
                           @if(!empty($buyer_data->company_url))
                           <li class="website-url"><img src="{{ url('images/web.png',[],$ssl) }}" alt="web" class="web-icon" /><span>
                              <a href="{{ $url}}" title="{{ $buyer_data->company_url }}" target="_blank">{{ $companyUrl}}{{ $addDot }}</a></span>
                           </li>
                           @else
                           <li class="website-url"><img src="{{ url('images/web.png',[],$ssl) }}" alt="web" class="web-icon" /><span>
                              <a href="javascript:void(0)" title="">Add website url</a></span>
                           </li>
                           @endif
                           @if(!empty($buyer_data->type_of_organisation['name']))
                           <li class="buyer-org-name"><img src="{{ url('images/ic_company.png',[],$ssl) }}" alt="company" class="company-icon" />
                              <span><?php echo $buyer_data->type_of_organisation['name']; ?></span>
                           </li>
                           @else
                           <li class="buyer-org-name"><img src="{{ url('images/ic_company.png',[],$ssl) }}" alt="company" class="company-icon" />
                              <a href="javascript:void(0)" class="cmpy_edit" >Add type of organization</a>
                           </li>
                           @endif
                           <input type="hidden" id="type_o_org" value="{{$buyer_data->type_of_organisation['name']}}">
                           @if(!empty($buyer_data->office_location))
                           <li class="office-locatioin-icon">
                              <img src="{{ url('images/ic_location.png',[],$ssl) }}" alt="location" class="location-icon" /><span>
                              {!! $buyer_data->office_location; !!}
                              </span>
                           </li>
                           @else
                           <li class="add-office-location">
                              <img src="{{ url('images/ic_location.png',[],$ssl) }}" alt="location" class="location-icon" />
                              <a href="javascript:void(0)" class="cmpy_edit" data-toggle="modal" data-target="#myModal5">Add office location</a>
                           </li>
                           @endif
                           @if(!empty($buyer_data->parent_company))
                           <li class="add-parent-company-input"><img src="{{ url('images/ic_parent_company.png',[],$ssl) }}" alt="web" class="web-icon" /><span>@if($buyer_data->parent_company == '-1'){{ 'No parent company'}}
                              @else {{ ucfirst($buyer_data->parent_company) }} @endif</span>
                           </li>
                           @else
                           <li class="add-parent-company-input">
                              <img src="{{ url('images/ic_parent_company.png',[],$ssl) }}" alt="web" class="web-icon" />
                              <a href="javascript:void(0)" data-toggle="modal" class="cmpy_edit" data-target="#myModal5">Parent company?</a>
                           </li>
                           @endif
                        </ul>
                     </div>
                  </div>
                  <div style="display:none" id="edit_profile">
                     <div class="education-section content-block new-edit-section">
                        <div role="document">
                           <div class="modal-innner-content">
                              <div class="modal-body">
                                 <span class="successMsg" style="color:green"></span>
                                 <form id="cmpy_dtl"  method='post'>
                                    <input type="hidden" name="hidUrl" id="hidUrl" value="{{ $buyer_data->company_url }}">
                                    {{ csrf_field() }}
                                    <div class="input-bx">
                                       <label>Company website <span class="notification-star-buyer">*</span></label>
                                       <input tabindex="1" type="text" maxlength="40" name="company_url" id="company_url" value="" size="30" placeholder="e.g www.mycompany.com" />
                                       <span id="company_url_validation" class="validation_error"></span>
                                       <span id="company_website_url" class="validation_error"></span>
                                    </div>
                                    <div class="input-bx select-dropdown-style" id="type_o_org_div">
                                       <label>Type of organization <span class="notification-star-buyer">*</span></label>
                                       <?php
                                          if (!empty($buyer_data->type_of_organisation['name'])) {
                                             $type_of_organization = $buyer_data->type_of_organisation['name'];

                                          } else {
                                             $type_of_organization = '';
                                          }
                                          ?>
                                       <select  tabindex="2" placeholder="Choose" class="selectpicker" name="type_of_organization" id="type_of_organization">
                                          <option value="">Choose</option>
                                          @foreach($type_of_org_list as $type_of_org_id => $type_of_org_value)
                                          @if($type_of_org_value != 'Other Industry')
                                          <option value="{{$type_of_org_value}}" @if($type_of_organization==$type_of_org_value) selected @endif>{{$type_of_org_value}}</option>
                                          @endif
                                          @endforeach
                                          <option value="Other Industry">Other Industry</option>
                                       </select>
                                       <span id="type_of_org_error" class="validation_error"></span>
                                    </div>
                                    <div class="input-bx account_info new-custom-dropdown-style">
                                       <label>Office location<span class="notification-star-buyer">*</span></label>
                                       <input id="office_location"  name="office_location" tabindex="8"  placeholder="e.g. London" value="{{$buyer_data['office_location']}}" type="text" maxlength="40" class=" input-error-message" autocomplete="off">
                                       <div id="office_location_tags" class="dropdown"></div>
                                       <span id="location_error" class="validation_error"></span>
                                    </div>
                                    <div id="comany_url" class="input-bx select-box">
                                       <label>Parent company? <span class="notification-star-buyer">*</span></label>
                                       <select  tabindex="3" name="parent_company_option" id="parent_company_exists"  class="selectpicker">
                                          <option id="noVal"  value="" selected="selected">Choose</option>
                                          <option id="Yes" selected="selected" value="Yes">Yes</option>
                                          <option id="No" selected="selected" value="No">No</option>
                                       </select>
                                       <span id="parent_company_existance_error" class="validation_error"></span>
                                    </div>
                                    <div class="input-bx">
                                       <input type="hidden" id="hidden_parent_company_url" name="hidden_parent_company_url" value="@if(!empty($buyer_data['parent_company']) && ( $buyer_data['parent_company'] != '-1' )){{ $buyer_data['parent_company'] }}@endif">
                                       <input  tabindex="4" type="text" maxlength="40" size="30" style="@if(!empty($buyer_data['parent_company']) && $buyer_data['parent_company'] != '-1'){{ "display:block" }}@else {{ "display:none" }} @endif" id="parent_company_url" name="parent_company_url" value="" placeholder="e.g Google"/>
                                       <span id="parent_company_url_error" class="validation_error"></span>
                                    </div>
                                    <button  tabindex="5" type="button" id="save_company_details" class="blue-bg-btn standard-btn green-gradient">Save</button>
                                    <button  tabindex="6" type="button" class="blue-bg-btn standard-btn green-gradient" id="cancel_edit_company">Cancel</button>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 profile-content-main">
            <div class="profile-content-section">
               <!-- Nav tabs -->
               <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#summary-tab" aria-controls="home" role="tab" data-toggle="tab" id="summary-tab-link">Summary</a></li>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="summary-tab">
                     <div class="bio-section content-block bio-edit-view">
                        <h4>Bio  <a class="info-icon">? <span>What information would you like the expert to know</span></a></h4>
                        <div class="edit_view editbiotext bio_edit_pen" >
                           @if(!empty($buyer_data->bio))
                           <?php $bio = $buyer_data->bio; ?>
                           <div class="remove_bio">
                              <p><a title="edit"  href="javascript:void(0)" class="edit_icon bio_edit_pen"><img alt="pen" src="{{ url('images/pen.png',[],$ssl) }}"></a>
                                 <span id="bio_para">{!!nl2br(e($bio))!!}</span>
                              </p>
                           </div>
                           @else
                           <div class="remove_bio normal-view">
                              <?php $bio = "Please write a brief description about you"; ?>
                               <h6 class="nodatatext">{{ $bio }}</h6>
                              <a href="javascript:void(0)" class="bio_edit_pen">Add bio</a>
                           </div>
                           @endif
                           <div class="edit_bio_expert" style="display:none">
                              @if(!empty($buyer_data->bio))
                              <?php $bioValue = $buyer_data->bio; ?>
                              @else
                              <?php $bioValue = ""; ?>
                              @endif
                              <form id="bio_form">
                                 <div id="successmessage" style="color:green"></div>
                                 <div class="input-bx">
                                    {{ csrf_field() }}
                                    @if(isset($buyer_data->bio))
                                    <textarea  tabindex="7" class="textarea-section" id="bio_text" placeholder="A description of your company" maxlength="2000" name="bio">{!! str_replace(('<br />'), '',$bioValue) !!}</textarea>
                                    @else
                                    <textarea   tabindex="7" class="textarea-section" id="bio_text"  maxlength="2000" name="bio"></textarea>
                                    @endif
                                 </div>
                                 <span class="validation_error"></span>
                                 @if ($errors->has('bio')) <span class="help-block">
                                 <strong>{{ $errors->first('bio') }}</strong></span>
                                 @endif
                                 <input type="submit"  tabindex="8" id="save_bio_btn" class="clearfix blue-bg-btn standard-btn" value="Save" name="editbio">
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="freelancer-tab">
                     <div class="freelance-review-section content-block">
                        <h4>Reviews</h4>
                        <h6>You haven’t received any reviews yet</h6>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-right-section">
            <div class="profile-strength current-project-block">
               <h3>Current Projects</h3>
               <div class="current-project-list">
                  @if(_count($posted_projects))
                  @foreach($posted_projects as $posted_project)
                  <a href="{{url('buyer/messages/project/'.$posted_project->id,[],$ssl)}}">{{ucfirst($posted_project->job_title)}}</a>
                  @endforeach
                  @else
                  <p class="text-muted">No projects at the moment. Get one started by clicking the button below.</p>
                  @endif
               </div>
               <a href="{{url('project/create',[],$ssl)}}" class="begin-btn post-project-btn standard-btn" title="Submit a Project">Submit a Project</a>
            </div>
         </div>

      </div>
      </div>
   </div>
</div>
</div>
<!-- /.content -->
<div class="modal fade profile-page-popup lightbox-design lightbox-design-small" id="confirm_cancellation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-innner-content">
         <div class="modal-content">
            <div class="modal-header">
               <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img  alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
            </div>
            <div class="modal-body">
               <h3>Do you want to save your changes?</h3>
               <span id="imageerrormsg" class="error-message"></span>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile-pic-inner">
                  <button id="save_edited_changes" class="blue-bg-btn green-gradient standard-btn" >Yes</button>
                  <button id="discard_edited_changes" class="blue-bg-btn green-gradient standard-btn" >No</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<a href="javascript:void(0)" data-toggle="modal" data-target="#zoompopup" id="zoompopupshow"></a>
<div class="modal fade add_current_role_popup lightbox-design lightbox-design-small car_edit_upload profilezoompopup" id="zoompopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close btn" data-dismiss="modal" aria-hidden="true"><img src="{{ url('/images/cross-black.svg',[],$ssl) }}" /></button>
         </div>
         <div class="modal-body">
            <div class="carupload_popup_head">
               <h3>Adjust Photo</h3>
               <p>Drag the image to adjust position.</p>
            </div>
            <div id="image-cropper" class="img-crop-section">
               <div class="cropwrapper">
                  <div class="preview-wrapper">
                     <div class="image-cropper">
                        <div class="cropit-preview"></div>
                     </div>
                  </div>
               </div>
               <div class="carupload_popup_head profile-update change-photo-section">
                  <div class="col-md-12 img-rotate-block">
                     <div class="rotation-btns">
                        <span class="rotate-img rotate-cw-btn"><img src="{{ url('/images/rotateIcon.svg',[],$ssl) }}" />
                        Rotate image
                        </span>
                     </div>
                  </div>
                  <div class="col-md-12 range_block">
                     <div class="range-slider user-img slider-wrapper">
                        <span aria-hidden="true" class="glyphicon glyphicon-user zoom-xs"></span>
                        <input tabindex="9" type="range" id="rangedupli" class="range-slider__range cropit-image-zoom-input" />
                        <span aria-hidden="true" class="glyphicon glyphicon-user zoom-lg"></span>
                     </div>
                  </div>
                  <div class="popup-btns">
                     <label class="change_car btn-file btn btn-light-white white-btn">
                     <input tabindex="10" type="file" id="cropimageadd" class="cropit-image-input" accept="image/*" alt=""/>
                     <span id="change_photo_label">Change photo </span>
                     </label>
                     <button tabindex="11" data-dismiss="modal" aria-hidden="true" class="savecropimage btn btn-primary">Save</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('js/jquery.cropit.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{ url('/js/cropit_exif_fix.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.footer')
<script src="{{ url('js/profile.js?js='.$random_number,[],$ssl) }}"></script>
<script >
   var bas64url = "{{ url('getbase64',[],$ssl) }}";
</script>
@endsection
