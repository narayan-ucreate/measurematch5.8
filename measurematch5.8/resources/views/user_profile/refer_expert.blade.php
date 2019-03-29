@extends('layouts.expert_layout')
@section('content')
<div class="vertical_align_middle refer-expert-bg">
<header class="profile-page-header">
    <nav class="navbar navbar-default">
        <div class="container"> <a href="{{url('/',[],$ssl)}}" class="pull-left logo" title="MeasureMatch">
                            <img class="img-responsive logo-lg" src="{{ url('images/logo.svg',[],$ssl) }}" width="172" alt="MeasureMatch"  />
                <img class="img-responsive logo-md" src="{{ url('images/mm-logo-stealth.svg',[],$ssl) }}" width="44" alt="MeasureMatch"  />
        </a><?php echo getUserType(); ?>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav pull-right">
                    <li><a class="message-mm-support" href="javascript:void(0)" title="Support">Support</a>@include('htmlpanels.mm_support_panel')</li>
                    <li><a href="{{url('expert/projects-search',[],$ssl)}}" title="Browse Projects" class="@if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE) @if(Auth::user()->admin_approval_status!=1)expert_profile_admin_unapproved @endif @else expert_profile_incomplete @endif">Browse Projects</a></li>
                    <li><a href="{{ url('expert/messages',[],$ssl) }}" title="Messages">Messages ({{ allUnreadMessages() }})</a></li>
                    <li class="active username_li"><span class="dropdown"> <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('expert/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</a> <span class="caret"></span> </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
                              
                                <li><a href="{{ url('expert/settings',[],$ssl) }}">Settings</a></li>
                                <li><a href="{{url('expert/dashboard',[],$ssl)}}">Dashboard</a></li>
                                <?php if (isset(Auth::user()->id)) { ?><li><a id="signout" title="Sign out" href="{{url('/logout',[],$ssl)}}">Sign out</a></li><?php } ?>
                            </ul> </span></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>
<section class="content refer-expert-page-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 refer-expert-white-block">
                @php 
                $userId = Auth::user()->id;
                $userInfo = expertInformation($userId);              
                @endphp 
                @if(!empty($userInfo[0]->profile_picture))
                    <img src="{{$userInfo[0]->profile_picture}}" alt="user-img" class="refer-user-img" />
                @else
                    <img src="{{url(config('constants.DEFAULT_PROFILE_IMAGE'), [],  $ssl)}}" alt="user-img" class="refer-user-img" />
                @endif
                <h3>Refer an expert, get a $20 discount</h3>
                <p>Refer an expert and should they successfully sign up, you will receive a $20 discount code to use against a future Measure Match project</p>
            <div class="refer-expert-block">
                    <form id="refer_expert" role="form" method="POST" action="javascript:void(0)">
                        {{ csrf_field() }}
                        <div class="msgReferral"></div>
                        <div class="form-group{{ $errors->has('error') ? ' has-error' : '' }}">                       <label for="email" class="control-label">Referral email address <span class="notification_star">*</span></label>
                            <div class="field_wrapper">
                             <input tabindex="1" id="email_1" class="referal_email" type="text" name="email[]" value=""/>
                               <input tabindex="2" type="button" id="refer_experts" value="Send invite(s)" class="blue-btn-bg standard-btn">
                                <div class="removeErrMsg validate_error_1"></div>        
                                </div>           
                            <a href="javascript:void(0);" class="add_button white-btn" title="Add field">Add another email </a> 
                        </div> 
                        <input type="hidden" name="email_count" id="email_count" value="">                          </form>
            </div>   
</div></div>
</div>
</section>
</div>
@include('include.basic_javascript_liberaries')
<script src="{{ url('js/referral.js?js='.$random_number,[],$ssl) }}"></script>
@include('include.footer')
@if(calculateProfileCompletePercentageStatus()['basic_profile_completness'] == TRUE)
        @if(Auth::user()->admin_approval_status!=1)
            @include('expert_profile_admin_unapproved_modal')
        @endif
    @else
        @include('expert_profile_incomplete_modal')
    @endif
@endsection