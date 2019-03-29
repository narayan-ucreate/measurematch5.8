@extends('layouts.adminlayout')
@section('content')
@php
$expert_id = $published_service_package['user_id'];
@endphp
<section class="content admin-section">
    <div class="row">
        <div class="container">
            <!-- left column -->
            
            @if(!empty($approved) || !empty($all_draft))
            <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                @include('include.adminsidemenu')
            </div>
            @else
            <div class="col-lg-2 col-sm-2 col-xs-12 admin-sidebar">
                <ul>
                    <li class="admin-logo-sidebar">
                        <a href="{{url('admin/buyerListing',[],$ssl)}}"><img src="{{url('images/logo-sm.svg',[],$ssl) }}" alt="logo icon" />
                            <span>Admin</span>
                        </a>
                    </li>
                    <li class="@if($published_service_package['publish'] == config('constants.PUBLISHED'))active @endif">
                        <a href="{{ url('admin/expert/servicepackages',[$expert_id],$ssl)}}" title="Clients">
                            Published Service Packages
                        </a>
                    </li>
                    <li class="@if($published_service_package['publish'] != config('constants.PUBLISHED'))active @endif">
                        <a href="{{ url('admin/expert/draftedservicepackages',[$expert_id],$ssl) }}" title="Experts">
                            Drafted Service Packages
                        </a>
                    </li>
                </ul>
            </div>
            @endif
            
            <div class="col-md-9 admin-right-side admin-edit-project-section">
                <!-- general form elements -->
                <div class="box">
                    <!-- form start -->
                    <div class="box-header"> 
                        <h1 class="box-heading">Service Package • {{$bread_crumb}}</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                    </div>
                    <div class="box-body">
                        <ol class="breadcrumb">
                            <li><a class="back-buttton" href="{{ url($back_link,[],$ssl) }}">{{$bread_crumb}}</a></li>
                            <li>{{$published_service_package['name']}}</li>
                        </ol>
                        <form method="post" id="update_service_package_form" action="{{ url('servicepackage/update/'.$published_service_package['id'],[],$ssl)}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
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
                            <input type="hidden" name="publish" value="@if($published_service_package['publish'] == 1) TRUE @else FALSE @endif">
                            <input type="hidden" name="expert_id" value="{{$published_service_package['user_id']}}">
                            @if(!empty($approved)) <input type="hidden" name="approved" value="{{$approved}}" id="approved"> @endif
                            @if(!empty($draft)) <input type="hidden" name="draft" value="{{$draft}}" id="draft"> @endif
                            @if(!empty($all_draft)) <input type="hidden" name="all_draft" value="{{$all_draft}}" id="all_draft"> @endif
                         <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4 admin-form-lable">
                                    <label>Name your service package <span class="notification-star-buyer">*</span></label>          
                                  </div>
                                  <div class="col-md-8 admin-form-input-bx">
                                      <input type="text" name="name" value="{{$published_service_package['name']}}" id="name"> 
                                        <div class="validation_project_name admin-validation-message"></div>
                                  </div>
                              </div>
                          </div>
                              

                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4 admin-form-lable">
                                        <label>Category <span class="notification-star-buyer">*</span></label>
                                  </div>
                                  <div class="col-md-8 admin-form-input-bx">
                                      <select id="category" class="selectpicker" name="service_package_category" >                                            
                                          <option value="">Choose</option>
                                          @foreach($categories as $category)
                                          <option value="{{$category['id']}}" title="{{$category['name']}}"  data-content="{{$category['name']}}" @if($published_service_package['service_packages_category_id']==$category['id']) selected='selected' @else '' @endif>{{$category['name']}}</option> 
                                          @endforeach
                                      </select>
                                        <div class="validation_category admin-validation-message"></div>
                                  </div>
                              </div>
                          </div>
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 admin-form-lable">
                                        <label>Service package type <span class="notification-star-buyer">*</span></label>          
                                    </div>
                                    <div class="col-md-8 admin-form-input-bx">
                                            <select id="service_package_type_featured" class="selectpicker margin-bottom-10" name="service_package_type" >                                            
                                                <option value="">Choose</option>
                                                @foreach($featured_listing as $service_package_type)
                                                <option value="{{$service_package_type}}" title="{{$service_package_type}}"  data-content="{{$service_package_type}}" @if($service_package_type==$published_service_package['service_package_type']['name']) selected='selected' @endif>{{$service_package_type}}</option> 
                                                @endforeach
                                                <option value="Other" title="Other"  data-content="Other" @if(!in_array($published_service_package['service_package_type']['name'], $featured_listing)) selected='selected' @endif>Other</option> 
                                            </select>
                                            <input  tabindex="3" type="text" id="service_package_type" class="skill-input" autocomplete="off" name="service_package_type_other" placeholder="Add your own Service Package Type here..." value="@if(!in_array($published_service_package['service_package_type']['name'], $featured_listing)){{$published_service_package['service_package_type']['name']}}@endif" style="@if(in_array($published_service_package['service_package_type']['name'], $featured_listing)) display: none; @endif"/>
                                        <div class="validation_type admin-validation-message"></div>
                                    </div>
                                </div>
                            </div>
                             
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4 admin-form-lable">
                                        <label>Description <span class="notification-star-buyer">*</span></label>          
                                  </div>
                                  
                                  <div class="col-md-8 admin-form-input-bx">
                                    <textarea name="description" class="edit_description" id ="description" value="{!! $published_service_package['description'] !!} " maxlength="4000" placeholder="Copy and paste or write your brief here" >{!! $published_service_package['description'] !!}</textarea>
                                        @if($errors->has('description')) <span class="help-block airport-error" style="color:red;"> {{ $errors->first('description') }} </span> @endif
                                        <div class="admin-validation-message validation_description {{ $errors->has('description') ? ' has-error' : '' }}"></div>          
                                  </div>
                              </div>
                          </div>
                           
                            <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4 admin-form-lable">
                                        <label>Deliverables <span class="notification-star-buyer">*</span></label>          
                                  </div>
                                  
                                  <div class="col-md-8 admin-form-input-bx deliverable-panel">
                                      @if(_count($published_service_package['deliverables']))
                                      @foreach($published_service_package['deliverables'] as $deliverable)
                                      <textarea name="deliverables[]" class="deliverables" id ="description" value="{!! $deliverable['deliverable'] !!} " maxlength="4000" placeholder="Copy and paste or write your brief here" >{!! $deliverable['deliverable'] !!}</textarea>
                                      @endforeach
                                      @endif
                                        @if($errors->has('description')) <span class="help-block airport-error" style="color:red;"> {{ $errors->first('description') }} </span> @endif
                                        <div class="admin-validation-message validation_deliverables {{ $errors->has('description') ? ' has-error' : '' }}"></div>          
                                  </div>
                                  <div class="input-bx-panel top-margin-0">
                                    <a href="javascript:void(0);" class="add-deliverable-link">Add another deliverable</a>
                                  </div>
                              </div>
                          </div>
                            
                            <div class="input-bx-panel multi-selectpicer select-dropdown-style tag-style">
                                <label>Service Package Tags (minimum 3)<span class="notification-star-buyer">*</span></label>
                                <div id="Tags_list" class="add-skill-button-block" > 
                                    @php
                                    if (!empty($tags)) {
                                    foreach ($tags as $skill) {
                                    echo '<span class="skill-button">' . $skill . '<a class="black_cross_link" href="javascript:void(0)"><img src=' . url("images/black_cross.png", [], $ssl) . ' alt="black_cross" class="black_cross" /></a></span>';
                                    }
                                    }
                                    @endphp                                                <div class="addskill"></div>
                                    <input  tabindex="3" type="text"  id="addskill_manually" class="skill-input"  autocomplete="off" value=""  name="addskill_manually" placeholder="JavaScript, Python, Enterprise Web Analytics"/>
                                    <input type="hidden"  id="manual_skills"  value=""  name="tags" />
                                    
                                    
                                </div>
                                
                                <div class="error-message pull-right col-md-8 validation_error_manual_skills{{ $errors->has('tags') ? ' has-error' : '' }}"></div>
                            </div>
                           
                           <div class="form-group">
                               <div class="row">
                                   <div class="col-md-4 admin-form-lable">
                                        <label>Information required from client: <span class="notification-star-buyer">*</span></label>
                                   </div>
                                   
                                   <div class="col-md-8 admin-form-input-bx">
                                       <input type="text" name="buyer_remarks" value="{{$published_service_package['buyer_remarks']}}" id="buyer_remarks"> 
                                        @if($errors->has('addskill')) <span class="help-block airport-error" style="color:red;"> {{ $errors->first('addskill') }} </span> @endif
                                    <div class="admin-validation-message validation_buyer_info {{ $errors->has('addskill') ? ' has-error' : '' }}"></div>                                       
                                   </div>
                               </div>
                          </div>
                             <div class="form-group">
                                 <div class="row">
                                     <div class="col-md-4 admin-form-lable">
                                         <label>Price <span class="notification-star-buyer">*</span></label>          
                                     </div>
                                     <div class="col-md-8 admin-form-input-bx">
                                         <input type="text" name="price" value="{{$published_service_package['price']}}" id="price"> 
                                         <div class="validation_price admin-validation-message"></div>
                                     </div>
                                 </div>
                             </div>
                          <div class="admin-contract-payment-block">
                              <div class="form-group project-lenth-section">
                                  <div class="row">
                                      <div class="col-md-4 admin-form-lable">
                                          <label>Service package duration <span class="notification-star-buyer">*</span> </label>
                                      </div>
                                      
                                      <div class="col-md-8 admin-form-input-bx">
                                          <input type="text" name="duration" value="{{$published_service_package['duration']}}" id="package_duration"> 
                                          <div class="validation_package_duration admin-validation-message"></div>
                                      </div>
                                  </div>
                              </div>

                            <div class="form-group">
                               <div class="row">
                                   <div class="col-md-4 admin-form-lable"><label>Subscription type <span class="notification-star-buyer">*</span></label></div>
                                   
                                   <div class="col-md-8 admin-form-input-bx">
                                       <select class="selectpicker full-width-dropdown" name="subscription_type" id="type">
                                           <option value="">Choose</option>
                                           <option value="one_time_package" @if($published_service_package['subscription_type'] == 'one_time_package') selected="selected" @endif>One-Time Package</option>
                                           <option value="monthly_retainer" @if($published_service_package['subscription_type'] == 'monthly_retainer') selected="selected" @endif>Monthly Retainer</option>
                                        </select>
                                       <div class="admin-validation-message subscription_type {{ $errors->has('remote_work') ? ' has-error' : '' }}"></div>
                                   </div>
                               </div> 
                          </div>
                            
                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-md-4"></div>
                                      
                                      <div class="col-md-8">
                                        <input type="button" id="update_service_package" class="update-button" value="Update">
                                      </div>
                                  </div>
                              </div>
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
