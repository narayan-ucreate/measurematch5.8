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
                        <h1 class="box-heading">Client • Approved</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    <div class="box-body">
                        <ol class="breadcrumb">
                            <li><a class="back-buttton" href="{{ url('admin/buyerListing',[],$ssl) }}">Approved</a></li>
                            <li>{{ucwords($result[0]->name)}} {{ucwords($result[0]->last_name)}}</li>
                        </ol>
                        <form method="post" id="update_buyer" action="{{ url('admin/buyerUpdate',[],$ssl)}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_type" id="user_type" value="{{ config('constants.BUYER') }}">
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
                            <div class="form-group">
                                <label>  First Name :  </label>
                                <input type="text" name="first_name" value="{{ isset($result[0]->name) ? ucwords($result[0]->name) :'' }}" id="first_name">
                                <div class="first_name_error"  style="color:red;"></div>
                            </div>
                            <input type="hidden" name="user_id" value="{{$result[0]->id}}">
                            <div class="form-group">
                                <label> Last Name :</label>
                                <input type="text" name="last_name" value="{{ isset($result[0]->last_name) ? ucwords($result[0]->last_name) :'' }}" id="last_name">
                                <div class="last_name_error"  style="color:red;"></div>
                            </div>
                            <div class="form-group">
                                <label>Email :</label>
                                <span> {{ isset($result[0]->email) ? $result[0]->email :'' }} </span>
                            </div>
                            <div class="form-group">
                                <label>Company Name :</label>
                                <input type="text" name="company_name" value="{{ isset($result[0]['buyer_profile']->company_name) ? $result[0]['buyer_profile']->company_name :'' }}" id="company_name">
                                <div class="company_name_error"  style="color:red;"></div>
                            </div>
                            <div class="form-group">
                                <label>Company URL :</label>
                                <input type="text" name="company_url" value="{{ isset($result[0]['buyer_profile']->company_url) ? $result[0]['buyer_profile']->company_url :'' }}" id="company_url">
                                <div class="company_url_error"  style="color:red;"></div>
                            </div>

                            <div class="form-group">
                                <label>How soon do you need</br> to get a project done?</label>
                                <select class="selectpicker" name="expected_project_post_time" id="expected_project_post_time">
                                    <option value="">
                                        Choose
                                    </option>
                                    @foreach(config('constants.EXPECTED_PROJECT_POST_TIME') as $key => $time)
                                        <option value="{{$key}}" @if ($result[0]['buyer_profile']->expected_project_post_time == $key) selected @endif>{{$time}}</option>
                                    @endforeach
                                </select>
                                <div class="clearfix">&nbsp;</div>
                                <span id="expected_project_post_time_error"></span>
                            </div>

                            <div class="form-group">
                                <label>Phone number :</label>
                                <input type="text" name="phone_number" value="{{ isset($result[0]->phone_num) ? $result[0]->phone_num :'' }}" id="phone_number">
                                <div class="phone_number_error"  style="color:red;"></div>
                            </div>
                            <div class="form-group">
                                <label>Office Location(s) :</label>
                                <textarea name="office_location" id="office_location">{!! isset($result[0]['buyer_profile']->office_location) ? trim(str_replace("<br/>","\n",$result[0]['buyer_profile']->office_location)) :'' !!}</textarea>
                                <div class="office_location_error"  style="color:red;"></div>
                            </div>    
                            <div class="form-group">
                                <label>Bio :</label>
                                <textarea name="bio" id="bio">{{ isset($result[0]['buyer_profile']->bio) ? $result[0]['buyer_profile']->bio :'' }}</textarea>
                                <div class="bio_error"  style="color:red;"></div>
                            </div>
                           
                           <div class="form-group custom-dropdown">
                            <label>Type of organization :</label>
                            @php 
                                $type_of_org = getTypeOfOrganization();                               
                                @endphp
                            @if ($errors->has('type_of_organization')) <span class="help-block"> <strong>{{ $errors->first('type_of_organization') }}</strong> </span> @endif
                            <select placeholder="Choose" class="selectpicker" name="type_of_organization" id="type_of_organization">
                              <option value="">Choose</option>
                               
                                @foreach($type_of_org as $type) 
                                 <option @if(isset($result[0]['buyer_profile']->type_of_organization_id) && trim($result[0]['buyer_profile']->type_of_organization_id)==$type->id){{ 'selected="selected"' }}@endif value="{{$type->id}}"> {{ $type->name }}  </option>                                
                                @endforeach
                              
                              <option @if(isset($result[0]['buyer_profile']->type_of_organization_id) && trim($result[0]['buyer_profile']->type_of_organization_id)=='2'){{ 'selected="selected"' }}@endif value="2">Services</option>        
                            </select>
                            <div class="type_of_organization_error"  style="color:red;"></div>
                          </div>

                          
                            <div class="form-group">
                                <label></label>
                                <input type="button" id="updateByr" data-id="{{ config('constants.BUYER') }}" class="update-button" value="Update">
                            </div>
                        </form>
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
