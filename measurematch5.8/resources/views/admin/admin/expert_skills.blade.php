

@extends('layouts.adminlayout')
@section('content')
    <section class="content admin-section expert-skills">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-sm-2 col-xs-2 admin-sidebar">
                    @include('include.adminsidemenu')
                </div>
                <div class="col-xs-10 col-sm-10  col-lg-9">
                    <div class="box">
                        <div class="box-header">
                            <h1 class="box-heading">System Skills</h1>
                            <a class="signu_out_btn" href="{{ url('admin_logout',[],$ssl) }}">Signout <img src="{{url('images/signout-icon.svg',[],$ssl) }}" alt="signout" /></a>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="pull-right message-section">
                                <p class="success"> @if(Session::has('success'))
                                        {{Session::get('success')}}
                                    @endif </p>
                            </div>
                            <div class="admin-subtab">
                                <div class="row">
                                    <div class="col-md-7">
                                        <ul>
                                            <li>

                                                <a class="active">
                                                    Skills
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                    <div class="col-md-5 pull-right">
                                        <form method="get">
                                        <input type="text" name="name" value="{{$skill_name}}" class="expertskills-search form-control pull-right" placeholder="Search Skill">
                                        </form>
                                        <div class="file-upload-bulk hide btn standard-btn"><span>Upload Logo</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-hover dataTable" id="example1">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="check-box-design">
                                            <input tabindex="3" type="checkbox"  id="select_all" value="1">
                                            <label><span></span></label>
                                        </div>
                                    </th>
                                    <th width="65%">Skill Name</th>
                                    <th width="20%">Logo</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($results as $skill)
                                    <tr>
                                        <td>
                                            <div class="check-box-design">
                                                <input id="skill_id_{{$skill->id}}" type="checkbox" class="upload-skill-logo"  value="{{$skill->id}}">
                                                <label><span></span></label>
                                            </div>
                                        </td>

                                        <td>{{ $skill->name }}</td>
                                        <td>
                                        <img width="50" src="{{$skill->logo_url}}">
                                        </td>
                                        <td>
                                            <div class="file-upload upload-btn btn standard-btn" data-skill-id="{{$skill->id}}"><span>Upload Logo</span>

                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <td colspan="4">No Skills</td>
                                @endforelse

                                <input name="upload" id="upload_logo" class="upload hide" type="file">
                                </tbody>
                            </table>

                            <div class="pagination">
                                {!! $results->appends(['name' => $skill_name])->links() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="{{url('js/admin.js?js='.$random_number,[],$ssl)}}"></script>
@endsection

