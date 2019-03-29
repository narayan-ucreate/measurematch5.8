@extends('layouts.adminlayout')
@section('content')
<section class="content admin-section">
    <div class="row">
        <div class="container">
            <!-- left column -->
            <div class="col-lg-2 col-sm-2 col-xs-12 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            <div class="col-md-9 admin-right-side">
                <!-- general form elements -->
                <div class="box">
                    <!-- form start -->
                    <div class="box-header"> 
                        <h1 class="box-heading">Expert • Approved</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    <ol class="breadcrumb">
                        <li><a class="back-buttton" href="{{ url('admin/expertListing',[],$ssl) }}">Approved</a></li>
                        <li>{{ucwords($result[0]->name)}} {{ucwords($result[0]->last_name)}}</li>
                    </ol>
                    <form method="post" id="expertUpdate" action="{{ url('admin/expertUpdate',[],$ssl)}}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="pull-right message-section">
                                <p class="success" style="color:green">
                                    @if(session()->has('success'))
                                    {!! session()->get('success') !!}
                                    @endif

                                </p>
                                <p class="success error-message">
                                    @if(session()->has('warning'))
                                    {!! session()->get('warning') !!}
                                    @endif
                                </p>
                            </div>
                            <input type="hidden" name="user_id" value="{{$result[0]->id}}">
                            <div class="form-group">
                                <label>  First Name :  </label>
                                <input type="text" name="first_name" value="{{ isset($result[0]->name) ? ucwords($result[0]->name) :'' }} " id="first_name">
                                <div class="first_name_validation_error error-message"></div>

                            </div>

                            <div class="form-group">
                                <label> Last Name :</label>
                                <input type="text" name="last_name" value="{{ isset($result[0]->last_name) ? ucwords($result[0]->last_name) :'' }}" id="last_name">
                                <div class="last_name_validation_error error-message"></div>

                            </div>
                            <div class="form-group">
                                <label> Type :</label>
                                <select placeholder="Choose" class="selectpicker" name="expert_type" id="expert_type_selector">
                                    <option value="">Choose</option>
                                    <option value="Independent" @if($result[0]['user_profile']->expert_type =='Independent'){{'selected'}}@endif>Independent</option>
                                    <option value="Consultancy" @if($result[0]['user_profile']->expert_type =='Consultancy'){{'selected'}}@endif>Consultancy</option>
                                </select>
                                <div class="expert_type_validation_error error-message"></div>
                            </div>
                            <div class="form-group" style="@if($result[0]['user_profile']->expert_type =='Independent') display: none; @endif" id="number_of_experts_div">
                                <label>How many Experts work for your consultancy?</label>
                                <select  class="selectpicker" name="experts_count_lower_range" id="experts_count">
                                    <option value="">Choose</option>
                                    <option value="2" @if($result[0]['user_profile']->experts_count_lower_range == 2) selected="selected" @endif>2-10 Experts</option>
                                    <option value="11" @if($result[0]['user_profile']->experts_count_lower_range == 11) selected="selected" @endif>11-30 Experts</option>
                                    <option value="31" @if($result[0]['user_profile']->experts_count_lower_range == 31) selected="selected" @endif>31-99 Experts</option>
                                    <option value="101" @if($result[0]['user_profile']->experts_count_lower_range == 101) selected="selected" @endif>100+ Experts</option>
                                </select>
                                <div class="validation_error_experts_count error-message"></div>
                            </div>
                            <div class="form-group">
                                <label>Email :</label>
                                <span>@if($result[0]->email) <a href="mailto:{{ $result[0]->email }}">{{ $result[0]->email }}</a> @else '' @endif</span>
                            </div>
                            <div class="form-group">
                                <label> Phone Number :</label>
                                <input type="text" name="phone_number" value="{{ isset($result[0]->phone_num) ? $result[0]->phone_num :'' }}" id="phone_number">
                                <div class="phone_number_validation_error error-message"></div>
                            </div>
                            <div class="form-group">
                                <label>Description :</label>
                                <textarea name="describe" id="describe">@if(isset($result[0]['user_profile']->describe) && !empty($result[0]['user_profile']->describe)){{ trim($result[0]['user_profile']->describe) }}@else{{ '' }}@endif</textarea>
                                <div class="description_validation_error error-message"></div>

                            </div>

                            <div class="form-group select-box averge_daily">
                                <label>Daily Rate :</label>
                                 <div class="post-rate-input-bx">
                                    <div class="input-group post-rate">
                                <div class="input-group-addon">$</div><input type="text" name="daily_rate" onkeypress="return isNumber(event)" value="{{ isset($result[0]['user_profile']->daily_rate) ?  ((is_numeric($result[0]['user_profile']->daily_rate)) ? number_format($result[0]['user_profile']->daily_rate) : $result[0]['user_profile']->daily_rate) :'' }}" id="daily_rate">

                                    </div>

                                 </div>
                                 <div class="rate_validation_error clearfix error-message"></div>
                            </div>
                            <div class="form-group">
                                <label>Location :</label>
                                <input type="text" name="current_city" value="{{ isset($result[0]['user_profile']->current_city) ?  $result[0]['user_profile']->current_city :'' }}" id="current_city">
                                <div class="city_validation_error error-message"></div>

                            </div>
                            <div class="form-group select-box work_preference">
                                <label>Work Preferences:</label>
                                <select placeholder="Choose" class="selectpicker" name="remote_work" id="remote_work">
                                    <option value="">Choose</option>
                                    <option @if($result[0]['user_profile']->remote_id =='1'){{'selected'}}@endif value="1">Only work remotely</option>
                                    <option @if($result[0]['user_profile']->remote_id =='2'){{'selected'}}@endif value="2">Only work on site</option>
                                    <option @if($result[0]['user_profile']->remote_id =='3'){{'selected'}}@endif value="3">Can work remotely and on site</option>
                                </select>
                                <div class="remote_work_validation_error error-message"></div>

                            </div>

                            <div class="form-group">
                                <label>Skills :</label>
                                @if(isset($result[0]['user_skills']) && !empty($result[0]['user_skills']))
                                @foreach($result[0]['user_skills'] as $s=>$skill)
                                <?php
                                $skill_name[$s] = $skill['skill']['name'];

                                $skills = implode(', ', $skill_name);
                                $skill_names = rtrim($skills, ", \t\n");

                                ?>
                                @endforeach

                                @endif
                                <input type="text" name="addskill" value="{{ isset($skill_names) ? $skill_names :'' }}" id="addskill">
                                <div class="skills_validation_error error-message"></div>
                            </div>

                            <div class="form-group">
                                <label>Bio :</label>
                                <?php $summery=isset($result[0]['user_profile']->summary) ? $result[0]['user_profile']->summary :'';?>
                                <textarea name="summary" id="summary">{!! str_replace(('<br />'), '',$summery) !!}</textarea>
                                <div class="bio_validation_error error-message"></div>

                            </div>
                            <div class="form-group"> <label></label>
                                <input type="button" class="update-button" id="update_expert" value="Update">
                            </div>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
