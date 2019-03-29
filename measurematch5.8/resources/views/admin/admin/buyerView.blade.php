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
                        <h1 class="box-heading">Client • {{$page_label}}</h1>
                        <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                        
                    </div>
                    @if(_count($result))
                    <div class="box-body">
                        <ol class="breadcrumb">
                            <li><a href="{{ url($back_url,[],$ssl) }}">{{$page_label}}</a></li>
                            <li>{{ isset($result[0]['buyer_profile']->company_name) ? $result[0]['buyer_profile']->company_name.', ' :'' }} 
                                {{ucwords($result[0]->name)}} {{ucwords($result[0]->last_name)}}</li>
                        </ol>
                        <div class="pull-right message-section">
                            <p class="success">
                                @if(Session::has('success'))
                                {{Session::get('success')}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label>  First Name :  </label>
                            <span class="admin-view-brief">  {{ isset($result[0]->name) ? ucwords($result[0]->name) :'' }} </span>

                        </div>

                        <div class="form-group">
                            <label> Last Name :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]->last_name) ? ucwords($result[0]->last_name) :'' }} </span>


                        </div>
                        <div class="form-group">
                            <label>MM Number :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]->mm_unique_num) ? $result[0]->mm_unique_num :'' }} </span>
                        </div>
                         <div class="form-group">
                            <label>Date Registered :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]->created_at) ? date('d-m-Y',strtotime($result[0]->created_at)) :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Email :</label>
                            <span class="admin-view-brief"> {{ isset($result[0]->email) ? $result[0]->email :'' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Phone :</label>
                            <span class="admin-view-brief"> {{ isset($result[0]->phone_num) ? $result[0]->phone_num :'' }} </span>
                        </div>

                        <div class="form-group">
                            <label>Company Name :</label>
                            <span class="admin-view-brief">  {{ isset($result[0]['buyer_profile']->company_name) ? $result[0]['buyer_profile']->company_name :'' }} </span>
                        </div>

                        <div class="form-group">
                            <label>Company URL :</label>
                            <span class="admin-view-brief">{{ isset($result[0]['buyer_profile']->company_url) ? $result[0]['buyer_profile']->company_url :'' }} </span>
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
                            <label> How soon do you need to get a project done? :</label>
                            <span class="admin-view-brief"> {{ isset($result[0]['buyer_profile']->expected_project_post_time) ? config('constants.EXPECTED_PROJECT_POST_TIME.'.$result[0]['buyer_profile']->expected_project_post_time) :'N/A' }} </span>
                        </div>
                        <div class="form-group">
                            <label>Office Location(s) :</label>
                            <span class="admin-view-brief">  {!! isset($result[0]['buyer_profile']->office_location) ? $result[0]['buyer_profile']->office_location :'-' !!} </span>
                        </div>
                        <div class="form-group">
                            <label>Parent Company:</label>
                            <span class="admin-view-brief">
                                @if(isset($result[0]['buyer_profile']->parent_company))
                                    @if($result[0]['buyer_profile']->parent_company == '-1')
                                    {{'-'}}
                                    @else
                                    {{$result[0]['buyer_profile']->parent_company}}
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Bio :</label>
                            <span class="admin-view-brief">{{ isset($result[0]['buyer_profile']->bio) ? $result[0]['buyer_profile']->bio :'' }} </span>
                        </div>
                       
                          <div class="form-group">
                            <label>Type of organization :</label>
                            <span class="admin-view-brief">
                                @php 
                                $type_of_org = getTypeOfOrganization();
                                @endphp
                                @foreach($type_of_org as $type) 
                                   @if($result[0]['buyer_profile']->type_of_organization_id==$type->id)                                                        {{ $type->name }}                                           
                                    @endif
                                @endforeach
                               
                                 </span>
                        </div>
                         
                        <div class="form-group">
                            <?php
                            $post = getPostJobs($result[0]->id);
                            if (isset($post) && !empty($post)) {
                                end($post);
                                $key = key($post);
                            }
                            ?>
                            <label>Projects (titles) :</label>
                            <span class="admin-view-brief">
                                @if(!empty($post) && _count($post))
                                    <ul>
                                    @foreach($post as $project)
                                    <li><a href="{{ url('admin/project',[$project->id],$ssl)."?source=".ucfirst($result[0]->name)}}">{{$project->job_title}}</a></li>
                                    @endforeach
                                    </ul>
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        @if($page_label == config('constants.ARCHIVED_LABEL'))
                            <div class="form-group">
                                <input type="button" class="update-button reinstate" data-id="{{$result[0]->id}}" value="REINSTATE">
                            </div>
                        @endif
                        @if($page_label == config('constants.PENDING_LABEL'))
                            <div class="form-group">
                                <input type="button" id="approve" data-id="{{$result[0]->id}}" class="update-button" value="Approve">
                                <input type="button" id="decline" data-id="{{$result[0]->id}}" class="decline-button" value="Decline">
                            </div>
                        @endif
                    </div>
                    @else
                    <p>No result found</p>
                    @endif
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
    </div>   <!-- /.row -->
</section>
<script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection
