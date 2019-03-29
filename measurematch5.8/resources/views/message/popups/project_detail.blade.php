<div id="project_detail_popup" class="proect-deatil-pop modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="proect-deatil-pop-left">
                                <h2> {{$job_info->job_title}} </h2> 
                                <div class="maxheight">    
                                <div class="block margin-0">  
                                    @php $class = ''; @endphp
                                    @if(strlen($job_info->description)> config('constants.JOB_DESCRIPTION_LIMIT')) 
                                        @php $class = 'hide'; @endphp
                                        <div id="truncated_description" >
                                           {!! nl2br(e( closeTags(substr(trim($job_info->description), 0, config('constants.JOB_DESCRIPTION_LIMIT') ).'...') ))!!}
                                            <a href="javascript:void(0)" id="show_more" class="read-more">Read more</a>
                                        </div>
                                    @endif

                                    <div id="full_description" class="{{$class}}">
                                        {!! nl2br(e($job_info->description)) !!}
                                        @if(strlen($job_info->description)> config('constants.JOB_DESCRIPTION_LIMIT')) <a href="javascript:void(0)" id="show_less" class="read-more"> Read less</a>@endif
                                    </div>
                                </div>  
                                <div class="block">
                                    @php $class = ''; @endphp
                                    <b>Deliverables</b>
                                    @if(!$all_deliverables) 
                                        <span class="no-text"> This brief has no deliverables</span>
                                    @endif
                                    @if(strlen($all_deliverables) > config('constants.JOB_DESCRIPTION_LIMIT')) 
                                        @php
                                        $class = 'hide';
                                        $string_after_strip = substr(strip_tags($all_deliverables), 0,  config('constants.JOB_DESCRIPTION_LIMIT'));
                                        $string_explode = explode('|', $string_after_strip);
                                        $all_strings_count=count($string_explode);
                                        $dots ='';
                                        @endphp
                                    <div id="deliverable_details_less" class="deliver-dot">
                                        @foreach($string_explode as $index=>$deliverable)
                                            @if($all_strings_count== $index+1)
                                                @php $dots='...'; @endphp
                                            @endif
                                           <p><span class="project-posted-date-dot">.</span> {{$deliverable.$dots}} </p>
                                         @endforeach
                                         <a href="javascript:void(0)" id="show_more_deliverable" class="read-more">Read more</a>
                                    </div>
                                   @endif
                                    @php $string_explode = array_filter(explode('|', $all_deliverables)); @endphp
                                    <div id="deliverable_details" class="{{$class}} deliver-dot">
                                        <br><br>
                                        @foreach($string_explode as $deliverable)
                                            <p><span class="project-posted-date-dot">.</span> {{ $deliverable }} </p>
                                        @endforeach
                                        @if(strlen($all_deliverables) > config('constants.JOB_DESCRIPTION_LIMIT'))
                                            <a href="javascript:void(0)" id="show_less_deliverable" class="read-more"> Read less</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="block">
                                    <b>Attachments</b>
                                    @php
                                    $attachments = ($job_info->upload_document != '' && $job_info->upload_document != 'no_image' ) ? json_decode($job_info->upload_document) : [];
                                    @endphp

                                    @forelse($attachments  as $index => $attachment)
                                    @php $filename = explode('/', $attachment)[4]; @endphp
                                    <a href="{{$attachment}}" target="_blank"> {{$filename}}</a>
                                    @empty
                                    <span class="no-text"> This brief has no attachments</span>     
                                    @endforelse
                                </div>    
                                <div class="skills-project-details-popup block">
                                    <b>Required Skills:</b>

                                    @php $skill_count = _count($skills); @endphp
                                    @forelse($skills  as $index => $skill)
                                    <span class="skill-button ">
                                        {{$skill->name}}
                                    </span>    
                                    @empty
                                    <span class="no-text"> This brief has no required skills</span>     
                                    @endforelse
                                    @if($skill_count > 6)<span class="skill-more"><a href="javascript:void(0)" id="show_more_skills" class="read-more">More skills</a>
                                        <a href="javascript:void(0)" class="hide read-more" id="show_less_skills">Less skills</a></span>
                                    @endif

                                </div>
                                </div>    
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="projectright-detail">
                            <div class="block">
                            <b>Your company name</b>
                            <p>
                                @if($job_info->hide_company_name)
                                Hidden from project brief
                                @else
                                {{$buyer_information->company_name}}
                                @endif
                            </p>
                            </div>
                                
                             
                            @if($job_info->remote_id !='1' && !empty($office_location))
                            <div class="block">
                            <b>Office location</b>
                            <p>{{$office_location}}</p>
                            </div>
                            @endif
                            <div class="block">
                            <b>Your location preference</b>
                            <p>
                             @if($job_info->remote_id == '1')
                            {{'Expert must work remotely'}}
                            @elseif($job_info->remote_id  == '2')
                            {{'Expert must work on-site'}}
                            @else
                            {{'Expert can work on-site or remotely'}}
                            @endif
                             </p>
                            </div>
                            <div class="block">
                            <b>Project start date</b>
                            <p>{{$job_info->job_end_date != '' ? date(config('constants.DATE_FORMAT'), strtotime($job_info->job_end_date)) : ''}}</p>
                            </div>
                            <div class="block">
                            <b>Estimated Project length</b>
                            <p>{{convertDaysToWeeks($job_info->project_duration)['time_frame']}}</p>
                            </div>
                            <div class="block">
                            <b>Budget approval status</b>
                            <p>{{getBudgetApprovalStatus($job_info->budget_approval_status)}}</p>
                            </div>
                            <div class="block">
                            <b>Project budget</b>
                            <p>@if($job_info->rate > 0 )</p>
                            </div>
                            {{convertToCurrencySymbol($job_info->currency)}}{{is_numeric($job_info->rate)?number_format($job_info->rate):$job_info->rate}}@if($job_info->rate_variable=='daily_rate'){{'/day'}}@endif
                            @else
                            I don't know my budget <span class="no-text">(Experts will see "Negotiable" in the Project brief)</span>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->user_type_id == config('constants.BUYER'))
                <div class="modal-footer">
                    @if(empty($job_info->accepted_contract_id)) <a href="{{route('editProject', $job_id)}}" class="btn standard-btn">Edit Project</a>@endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
