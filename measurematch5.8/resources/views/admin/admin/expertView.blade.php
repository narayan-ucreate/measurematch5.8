@extends('layouts.adminlayout')
@section('content')
<section class="content admin-section">
    <div class="row">
        <div class="container">
            <!-- left column -->
            <div class="col-lg-2 col-sm-2 col-xs-12 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-lg-10 col-sm-10 col-xs-2 admin-right-side">
                <!-- general form elements -->
                <div class="box">
                    <!-- form start -->

                    <div class="box-header"> 
                        <h1 class="box-heading">Expert • {{$page_label}}</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                        
                    </div>
                    <div class="box-body">
                        <ol class="breadcrumb">
                            <li><a href="{{ url($back_url,[],$ssl) }}">{{$page_label}}</a></li>
                            <li>{{ucwords($result[0]->name)}} {{ucwords($result[0]->last_name)}}</li>
                        </ol>
                        <div class="pull-right message-section">
                            <p class="success">
                                @if(Session::has('success'))
                                {{Session::get('success')}}
                                @endif
                            </p>
                        </div>

                        <div class="expert-profile-image pull-right">
                            
                            <span style="background-image:url({{getImage($result[0]['user_profile']['profile_picture'],$ssl)}})"></span> 
                        </div>

                        <div class="form-group">
                            <label>  First Name :  </label>
                            <span class="admin-view-brief">  {{ isset($result[0]->name) ? ucwords($result[0]->name) :'' }}</span>
                        </div>

                        <div class="form-group">
                            <label> Last Name :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]->last_name) ? ucwords($result[0]->last_name) :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label> Profile Link :</label>
                            <span id="webflow_url" class="gilroyregular-bold-font link-color">{{ $webflow_url ?? '' }}</span>
                            <button class="copy-link-btn"  onclick="copyToClipboard('#webflow_url')">Copy URL</button>
                            <span class="success-message-of-copy-url gilroyregular-bold-font" id="link_copied_message"></span>
                        </div>
                        <div class="form-group">
                            <label>  Type :  </label>
                            <span class="admin-view-brief">  {{ $result[0]['user_profile']->expert_type }}</span>
                        </div>
                        <div class="form-group">
                            <label>MM ID :</label>
                            <span class="admin-view-brief">    {{ isset($result[0]->mm_unique_num) ? $result[0]->mm_unique_num :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Date Registered :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]->created_at) ? date('d-m-Y',strtotime($result[0]->created_at)) :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Email :</label>
                            <span class="admin-view-brief">@if($result[0]->email) <a href="mailto:{{ $result[0]->email }}">{{ $result[0]->email }}</a> @else '' @endif</span>
                        </div>
                        <div class="form-group">
                            <label>Phone number :</label>
                            <span class="admin-view-brief">{{ $result[0]->phone_num }}</span>
                        </div>
                        <div class="form-group">
                            <label>VAT number :</label>
                            <span class="admin-view-brief">
                                @if ($result[0]->vat_country_code)
                                    {{ $result[0]->vat_country_code }}{{ $result[0]->vat_number }}
                                @else
                                -
                                @endif
                            </span>
                        </div>

                        <div class="form-group">
                            <label>Description :</label>
                            <span class="admin-view-brief">
                                @if(isset($result[0]['user_profile']->describe) && !empty($result[0]['user_profile']->describe))
                                {{ $result[0]['user_profile']->describe }}
                                @else
                                {{ '' }}
                                @endif
                            </span>
                        </div>

                        <div class="form-group">
                            <label>Daily Rate :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]['user_profile']->currency) ? $result[0]['user_profile']->currency :'' }}
                                {{ isset($result[0]['user_profile']->daily_rate) ?  ((is_numeric($result[0]['user_profile']->daily_rate)) ? number_format($result[0]['user_profile']->daily_rate) : $result[0]['user_profile']->daily_rate) :'' }}
                                {{ isset($result[0]['user_profile']->rate_variable) ?  $result[0]['user_profile']->rate_variable :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Location :</label>
                            <span class="admin-view-brief">    {{ isset($result[0]['user_profile']->current_city) ? $result[0]['user_profile']->current_city :'' }} </span>
                        </div>

                        <div class="form-group">
                            <label>Work Preferences:</label>
                            <span class="admin-view-brief">   {{ isset($result[0]['user_profile']['remote_work']->name) ? $result[0]['user_profile']['remote_work']->name :'' }} </span>

                        </div>

                        <div class="form-group">
                            <label>Bio :</label>
                            <span class="admin-view-brief">{!! isset($result[0]['user_profile']->summary) ? $result[0]['user_profile']->summary :'' !!} </span>
                        </div>

                        <div class="form-group">
                            <label>Skills :</label>
                            <span class="admin-view-brief">  @if(isset($result[0]['user_skills']) && !empty($result[0]['user_skills']))
                                @foreach($result[0]['user_skills'] as $s=>$skill)
                                <?php
                                $skill_name[$s] = $skill['skill']['name'];

                                $skills = implode(', ', $skill_name);
                                $skill_names = rtrim($skills, ", \t\n");
                                ?>
                                @endforeach
                                @if(!empty($skill_names))
                                <p>{{$skill_names}}</p>
                                @endif
                                @endif </span>
                        </div>
                        @if($page_label == 'Archived')
                            <div class="form-group">
                                <input type="button" class="update-button reinstate" data-id="{{$result[0]->id}}" value="REINSTATE">
                            </div>
                        @endif
                        @if($page_label == 'To Interview')
                            <div class="form-group">
                                <input type="button" id="expertApprove" data-id="{{$result[0]->id}}" class="update-button" value="Approve">
                                <input type="button" id="expertDecline" data-id="{{$result[0]->id}}" class="decline-button" value="Decline">
                            </div>
                        @endif
                        @if($page_label == 'Approved' && $webflow_url == '-')
                            <div class="form-group">
                                <input type="button" id="expertApproveWebflow" data-id="{{$result[0]->id}}" class="update-button" value="Send to Webflow">
                            </div>
                        @endif
                        @if($page_label == 'Unverified')

                            <form method="post" id="delete_user" action="{{ route('deleteExpert') }}">
                                {{csrf_field()}}
                                <input type="hidden" name="status" value="{{ config('constants.UNVERIFIED_LABEL') }}">
                                <input type="hidden" name="id" value="{{ $result[0]->id }}">
                                <div class="form-group">
                                    <input type="submit" class="decline-button" value="Permanently delete this user">
                                </div>
                            </form>
                        @endif
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
