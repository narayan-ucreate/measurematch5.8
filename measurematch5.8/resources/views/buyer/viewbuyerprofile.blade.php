@extends('layouts.expert_layout')
@section('content')
<div id="wrapper" class="active buyer-profile-content buer-profile-view expert-view-buyer-profile">
  <div id="page-content-wrapper">
    <div class="page-content inset">
      <div class="col-md-3 leftSidebar">
           @include('sellerdashboard.sidemenu')
      </div>
      <div class="col-md-9 rightcontent-panel">
         <div class="theiaStickySidebar">
            <div class="col-md-12">
               <div class="col-lg-12 expert-breadcrumb">
                  <div class="row">
                     <div class="breadcrumb-bg project-details-breadcrumb">
        @if(isset($_REQUEST['breadcrumb-page'])&& !empty($_REQUEST['breadcrumb-page']))
    {!! Breadcrumbs::render($_REQUEST['breadcrumb-page'],['name'=>$buyer_data->first_name,'id'=>$buyer_data->user_id]) !!}
    @endif
                     </div>
                  </div>
               </div>

            @if(Session::has('profilepicerror'))
            <h5 style="color:red">{{Session::get('profilepicerror')}}</h5>
            @endif
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-left-section buyer-profile-view">
                <div class="expert-left-side">
                <div class="profile-image buyer-view-profile">
                    <img src="{{ url(config('constants.DEFAULT_PROFILE_IMAGE'), [], $ssl) }}" alt="default-buyer-image" class="profile_pic_empty" />
                    @if(!empty($buyer_data->profile_picture))
                    <?php $imgSrc = $buyer_data->profile_picture; ?>
                    <span class="profile-pic-icon uploaded-profile-pic"><div style="background-image:url({{ $imgSrc }});" class="profilepicture" /></div>
                   <!-- <img class="camera_small" alt="Camera_small" src="images/Camera_small.png"> </span>-->
                @else
                <?php $imgSrc = "images/Camera_green.png"; ?>
                <span class="profile-pic-icon"><img src="{{ $imgSrc }}" alt="Camera" class="lg-Camera" /> </span>
                @endif
            </div>

            <div class="summary-section">
                <h3>{{ ucfirst($buyer_data->first_name) }} {{ ucfirst($buyer_data->last_name) }}</h3>
                <?php $ofcLocations = explode("<br/>", $buyer_data->office_location); ?>
                <!--will show edit icon only if location and parent company is available -->
                <?php
                if (!preg_match("~^(?:f|ht)tps?://~i", $buyer_data->company_url)) {
                    $url = "http://" . $buyer_data->company_url;
                } else {
                    $url = $buyer_data->company_url;
                }


                $strCnt = strlen($buyer_data->company_url);
                if ($strCnt > 15) {
                    $addDot = '...';
                } else {
                    $addDot = '';
                }
                if ($buyer_data->company_url != '') {
                    $companyUrl = substr($buyer_data->company_url, 0, 15);
                } else {
                    $companyUrl = '';
                }
                ?>
                @if(!empty($buyer_data->parent_company) && !empty($buyer_data->office_location))

                <ul>
                     @if(empty($buyer_data->hide_company_name) || ($buyer_data->hide_company_name==0))
                     <li><img src="images/ic_company.png" alt="company" class="company-icon" /><span>{{ ucfirst($buyer_data->company_name) }}</span></li>
                  @else
                                    @php
                                    $type_of_org = getTypeOfOrganization();
                                    @endphp
                                    @foreach($type_of_org as $type)
                                    @if($buyer_data->type_of_organization_id==$type->id)
                                     <li><img src="images/ic_company.png" alt="company" class="company-icon" /><span>{{ ucfirst($type->name) }}</span></li>
                                    @endif
                                    @endforeach

                                @endif


                    <li class="website-url"><img src="images/web.png" alt="web" class="web-icon" /><span><a href="{{ $url}}" title="{{ $buyer_data->company_url }}" target="_blank">{{ $companyUrl }}{{ $addDot }}</a></span></li>
                    @if(!empty($buyer_data->office_location))
                    <li class="office-locatioin-icon"><img src="images/ic_location.png" alt="location" class="location-icon" /><span>
<?= $buyer_data->office_location; ?>
                        </span></li>
                    @else
                    <li class="buyer_add_location"><a href="javascript:void(0)" class="cmpy_edit" data-toggle="modal" data-target="#myModal5">Add office location(s)</a></li>
                    @endif
                    <li class="buyer-web-icon"><img src="images/ic_parent_company.png" alt="ic_parent_company" class="web-icon ic_parent_company" /><span>@if($buyer_data->parent_company == '-1'){{ 'No parent company'}}
                            @else {{ ucfirst($buyer_data->parent_company) }} @endif</span></li>
                </ul>

                @else
                <ul>
                    @if(empty($buyer_data->hide_company_name) || ($buyer_data->hide_company_name==0))
                     <li><img src="images/ic_company.png" alt="company" class="company-icon" /><span>{{ ucfirst($buyer_data->company_name) }}</span></li>
                     @else
                                    @php
                                    $type_of_org = getTypeOfOrganization();
                                    @endphp
                                    @foreach($type_of_org as $type)
                                    @if($buyer_data->type_of_organization_id==$type->id)
                                     <li><img src="images/ic_company.png" alt="company" class="company-icon" /><span>{{ ucfirst($type->name) }}</span></li>
                                    @endif
                                    @endforeach
                                @endif

                    <li class="buyer-web-icon"><img src="{{ url('images/web.png',[],$ssl) }}" alt="web" class="web-icon" /><span><a title="{{ $buyer_data->company_url }}"  href="{{ $url }}" target="_blank">{{ $companyUrl }}{{ $addDot }}</a></span></li>
                    @if(!empty($buyer_data->office_location))
                    <li class="buyer-location-icon"><img src="{{ url('images/ic_location.png',[],$ssl) }}" alt="location" class="location-icon" /><span>
<?= $buyer_data->office_location; ?>
                        </span></li>
                    @endif

                    @if(!empty($buyer_data->parent_company))
                    <li class="buyer-parent-icon"><img src="images/web.png" alt="web" class="web-icon" /><span>@if($buyer_data->parent_company == '-1'){{ 'No parent company'}}
                            @else {{ ucfirst($buyer_data->parent_company) }} @endif</span></li>
                    @endif
                </ul>
                @endif </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 profile-content-main">
            <div class="profile-content-section">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#summary-tab" aria-controls="home" role="tab" data-toggle="tab">Summary</a></li>

                </ul>
                <!-- Tab panes -->
                <!-- Area for bio - text-->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="summary-tab">
                        <div class="bio-section content-block buyer_view_bio">
                            <h4>Bio</h4>
                            @if(!empty($buyer_data->bio))
<?php $bio = $buyer_data->bio; ?>
                            <div class="buyer_view">
                                <p>{!!nl2br(e($bio))!!}</p>
                            </div>
                            @else
<?php $bio = "currently has no bio."; ?>
                            <h6 class="grey-text">{{ ucfirst($buyer_data->first_name.' '.$buyer_data->last_name) }} {{ $bio }}</h6>
                            @endif </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="freelancer-tab">
                        <div class="freelance-review-section content-block">
                            <h4>Reviews</h4>
                            <h6>
                                @if(empty($buyer_data->hide_company_name) || ($buyer_data->hide_company_name==0))
                                    {{ ucfirst($buyer_data->first_name.' '.$buyer_data->last_name) }}
                                     @else
                                    @php
                                    $type_of_org = getTypeOfOrganization();
                                    @endphp
                                    @foreach($type_of_org as $type)
                                    @if($buyer_data->type_of_organization_id==$type->id)
                                     <li><img src="images/ic_company.png" alt="company" class="company-icon" /><span>{{ ucfirst($type->name) }}</span></li>
                                     @endif
                                    @endforeach
                                @endif
                                  have received no reviews yet.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 profile-right-section">


        </div>
            </div>
      </div></div>
    </div>
</section>
@include('include.basic_javascript_liberaries')
@include('include.footer')
@endsection
