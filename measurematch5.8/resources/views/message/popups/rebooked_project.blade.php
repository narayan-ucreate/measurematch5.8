<div id="project_detail_popup" class="proect-deatil-pop rebooked-popup modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close"  data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="proect-deatil-pop-left">
                                <h2> {{$job_info->job_title}} </h2> 
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
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="projectright-detail">
                                <div class="block">
                                    <b>@if(isBuyer()){{'Your'}}@else{{userName($buyer_id, 0, 1)."'s"}}@endif company name</b>
                                    <p>
                                        @if($job_info->hide_company_name)
                                        Hidden from project brief
                                        @else
                                        {{$buyer_information->company_name}}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
