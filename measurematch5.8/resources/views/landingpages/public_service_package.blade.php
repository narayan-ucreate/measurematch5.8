<!DOCTYPE html>
<html lang="en">
    <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="icon" href="favicon.ico" type="image/gif" sizes="16x16">
     <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
     <meta http-equiv="pragma" content="no-cache">
     <meta http-equiv="Cache-control" content="public">
     <meta http-equiv="Cache-control" content="private">
     <meta http-equiv="Cache-control" content="no-cache">
     <meta http-equiv="Cache-control" content="no-store">
     <title>{{ucfirst($service_package_details[0]['name'])}}</title>
     <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     <meta name="description" content="{{$service_package_details[0]['description']}}">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
     <meta property="og:site_name" value="MeasureMatch" />
     <meta property="og:title" content="{{ucfirst($service_package_details[0]['name'])}}" />
     <meta property="og:description" content="{{$service_package_details[0]['description']}}" />
     <meta property="og:image" content="{{url('images/service-packages-image.png',[],$ssl) }}">
        @include('include.googleTagsScripts')
     <link rel="apple-touch-icon" sizes="57x57" href="{{ url('fav.ico/apple-icon-57x57.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="60x60" href="{{ url('fav.ico/apple-icon-60x60.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="72x72" href="{{ url('fav.ico/apple-icon-72x72.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="76x76" href="{{ url('fav.ico/apple-icon-76x76.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="114x114" href="{{ url('fav.ico/apple-icon-114x114.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="120x120" href="{{ url('fav.ico/apple-icon-120x120.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="144x144" href="{{ url('fav.ico/apple-icon-144x144.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="152x152" href="{{ url('fav.ico/apple-icon-152x152.png',[],$ssl) }}">
     <link rel="apple-touch-icon" sizes="180x180" href="{{ url('fav.ico/apple-icon-180x180.png',[],$ssl) }}">
     <link rel="icon" type="image/png" sizes="192x192"  href="{{ url('fav.ico/android-icon-192x192.png',[],$ssl) }}">
     <link rel="icon" type="image/png" sizes="32x32" href="{{ url('fav.ico/favicon-32x32.png',[],$ssl) }}">
     <link rel="icon" type="image/png" sizes="96x96" href="{{ url('fav.ico/favicon-96x96.png',[],$ssl) }}">
     <link rel="icon" type="image/png" sizes="16x16" href="{{ url('fav.ico/favicon-16x16.png',[],$ssl) }}">
     <link rel="manifest" href="{{ url('fav.ico/manifest.json',[],$ssl) }}">
     <meta name="msapplication-TileColor">
     <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
     <meta name="theme-color" content="#ffffff">

     <!-- Bootstrap -->
     <link href="{{ url('css/bootstrap.min.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
     <link href="{{ url('css/global_stylesheet.css?css='.$random_number,[],$ssl) }}" rel="stylesheet">
     <!-- <link href="{{ url('css/full-slider.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"> -->
     <link href="{{ url('css/homenavigation.css?css='.$random_number,[],$ssl) }}" rel="stylesheet" type="text/css">
     <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900italic,900,700italic,700,600italic,600,400italic,300italic,300,200italic,200' rel='stylesheet' type='text/css'>
</head>
    <link rel="stylesheet" href="{{ url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}">
    <link href="{{ url('css/signup-bg.css?css='.$random_number,[],$ssl) }}" rel="stylesheet"/>
    <body>
        @include('include.googleTagsScriptsBody')
        @if(!Auth::check())
        <body class="white-bg fixednavbody">
        @include('include.landingheader')
        @else
        <body class="white-bg fixednavbody">
        @include('include.loggedinnav')
        @endif
<div id="page-content-wrapper">

        <div class="container public-service-package-view @if(Auth::check()) public-service-package-logged_in_view @endif">
            <div class="row">
              <div class="create-package-panel my-service-package-details-panel buyer-services-nav">
                <div class="col-md-8">
                   <div class="white-box @if(!$average_rating) no-rating-white-box @endif">
                      <div class="white-box-content top-padding-0">
                         <div class="package-overview-panel buyer-servies-name-col package-raiting-panel">
                            <h6 class="gilroyregular-bold-font margin-bottom-10">MeasureMatch Service Package:</h6>
                            <h4 class="gilroyregular-bold-font no-boxshadow nopadding">{{ucfirst($service_package_details[0]['name'])}}</h4>
                            <div class="package-rating-section">
                                @if($average_rating)
                                <div id="show_rating" name="expert_rating" class="rateyo-readonly-widg" average_rating="{{$average_rating}}"></div>
                                <span class="package-review gilroyregular-bold-font font-16">({{(_count($service_package_details[0]['contract_feedbacks']))}} {{(_count($service_package_details[0]['contract_feedbacks'])==1)?'review':'reviews'}})</span>
                                @endif
                                <p>{!! nl2br(e( ucfirst($service_package_details[0]['description']) )) !!}</p>
                            </div>

                            <div class="service-package-view-without-login">
                                <h3>Want to see the rest of this Service Package?</h3>
                                <p>Signing up to MeasureMatch gives you full access to this Service Package and many others. It’s free to sign up, too!</p>
                                <a href="{{url('signup?buyer=&utm_source=public_service_package',[],$ssl)}}" class="service-package-signup-btn btn btn-primary express-interest-btn gilroyregular-bold-font" title="Sign up to MeasureMatch">Sign up to MeasureMatch</a>
                            </div>
                             @if(false)
                            <div class="expert-package-review-panel public-review-package-panel">
                               <h4 class="gilroyregular-bold-font font-16 margin-bottom-10">
                                  <div class="public-review-count">{{(_count($service_package_details[0]['contract_feedbacks']))}} {{(_count($service_package_details[0]['contract_feedbacks'])==1)?'Review':'Reviews'}}</div>
                                    <div id="show_rating_bottom" class="rateyo-readonly-widg"></div></h4>
                                      @if(_count($service_package_details[0]['contract_feedbacks']))
                                      @foreach ($service_package_details[0]['contract_feedbacks'] as $feedbacks)
                                        <div class="package-require-panel service-provider-profile">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="service-provider-profile-detail font-14">
                                                        <span class="service-provider-name gilroyregular-bold-font font-14">{{userName($feedbacks['buyer_id'], 1)}},</span> {{date('M Y', strtotime($feedbacks['feedback_time']))}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="{{$feedbacks['buyer_id'].'_'.$feedbacks['id']}}" class="rateyo-readonly-widg buyer_ratings" rating="{{$feedbacks['expert_rating']}}"></div>
                                            <p>{!! $feedbacks['feedback_comment'] !!}</p>
                                        </div>
                                        @endforeach
                                        @else
                                        <p>This package has not received any reviews yet.</p>
                                        @endif
                                      </div>
                                    @endif
                                  </div>
                               </div>
                            </div>
                         </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 edit-delete-widget buyer-guide-col leftSidebar">
                       <div class="theiaStickySidebar">
                           <div class="white-box express-interest-panel">
                              <div class="white-box-content paddingtop">
                                <div class="row">
                                   <div class="buyer-guide-col-head">
                                      <div class="buyer-guide-col-head-text">
                                         <p>Guide Package Price:</p>
                                         <span class="gilroyregular-bold-font">${{number_format($service_package_details[0]['price'])}}<span class="buyer-month">@if($service_package_details[0]['subscription_type']!="one_time_package"){{'/month'}} @endif</span></span>
                                      </div>
                                   </div>

                                   <ul>
                                      @if($looked_at_service_package)
                                        <li class="edit-icon looked-icon">
                                          <span class="looked-count gilroyregular-bold-font">@if($looked_at_service_package==1){{$looked_at_service_package.' View'}}@else{{number_format($looked_at_service_package).' Views'}}@endif</span>
<!--                                          <span class="looked-text">@php if($looked_at_service_package==1){echo 'Has';}else{echo 'Have';}@endphp looked at this package</span>-->
                                        </li>
                                      @endif
                                   </ul>

                                   <div class="buyer-review-panel typewiselist @if(!$looked_at_service_package) no-buyer-view @endif">
                                     <h6>Your MeasureMatch Expert for this Service Package:</h6>
                                     <div class="typewiselist_userinfo">
                                        <a href="#">
                                           <div class="userimg" style="background-image:url({{getImage($user_information['profile_picture'],$ssl)}})"></div>
                                           <span class="gilroyregular-bold-font expert-view-profile-img">{{userName($user_information['user_id'], 1)}}</span>
                                           <span class="looked-text">{{getTruncatedContent($user_information['describe'], 40)}}</span>
                                         </a>
                                      </div>
                                   </div>

                                @if(!Auth::check())
                                   <div class="guide-package-save-col">
                                      <a class="btn btn-primary express-interest-btn gilroyregular-bold-font" href="javascript:void(0)" data-toggle="modal" data-target="#expression-of-interest-pop-up" >Express Interest in this Service Package</a>
                                   </div>
                                 @endif
                              </div>
                           </div>
                        </div>

                        <div class="white-box about-mm-block">
                             <div class="white-box-content">
                              <img class="img-responsive" src="{{ url('images/logo.svg',[],$ssl) }}" width="150" alt="MeasureMatch"  />
                              <h5 class="gilroyregular-bold-font">What is MeasureMatch?</h5>
                              <p>The core value that we are creating in the MeasureMatch marketplace platform is the precise and productive matching of marketing, commerce, analytics and related business leaders to consultants and consultancies in our global network for implementation, configuration, systems integration, troubleshooting and other important enterprise [marketing & CX] technology systems and related data/analytics project work. MeasureMatch is the professional services marketplace platform helping every company to be more successful, more quickly and more cost-effectively in an increasingly digital world.</p>

                              <h5 class="gilroyregular-bold-font">What are Service Packages?</h5>
                              <p>Service Packages are essentially the productization of valuable services, created and serviced by MeasureMatch Experts. Please treat each Service Package as a prompt for a discovery conversation, a negotiation. Service agreements borne from these Service Packages often look very different from the original content. Go forth and get important work done today!
</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
             </div>
           </div>
        </div>
        <div id="expression-of-interest-pop-up" class="modal suggest-project-popup lightbox-design lightbox-design-small express-interest-lightbox">
           <div class="modal-dialog" role="document">
              <div class="modal-innner-content">
                 <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                    </div>

                    <div class="modal-body text-align-center">
                        <h3>Sign up to MeasureMatch</h3>
                        <h4 class="gilroyregular-font text-align-center font-16">To express interest in this Service Package, you'll need to sign up to a MeasureMatch Client account (It's free!).</h4>
                        <a href="{{ url('signup?buyer=&utm_source=public_service_package',[],$ssl) }}" class="standard-btn clearfix font-16 margin-bottom-10">Sign up to MeasureMatch</a>
                        <div class="clearfix"></div>
                        <span class="font-16">Already have an account? <a href="{{ url('login',[],$ssl) }}">Login</a></span>
                    </div>
                  </div>
              </div>
            </div>
         </div>


            @if(Auth::check())
            @include('include.footer')
            @else
            @include('include.footer')
            @endif
            <script src="{{url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{url('js/side-menu.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
            <script src="{{url('js/moment.js?js='.$random_number,[],$ssl)}}"></script>
            <script src="{{url('js/autosize.js?js='.$random_number,[],$ssl)}}"></script>
            <script src="{{url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
            <script src="{{url('js/buyer_service_packages.js?js='.$random_number,[],$ssl)}}"></script>
            <script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
            <link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
            @include('include.global_layout_parent')
    </body>
</html>
