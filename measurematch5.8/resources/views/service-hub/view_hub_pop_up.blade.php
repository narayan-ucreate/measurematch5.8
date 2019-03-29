<div class="vendor-review-step-bg">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex">
                <div class="vendor-dis-block">
                    <h4 class="gilroyregular-semibold font-24">{{$hub_info->name ?? ''}}</h4>
                    @php $class = ''; @endphp
                    @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.JOB_DESCRIPTION_LIMIT'))
                    @php $class = 'hide'; @endphp
                    <div id="truncated_description" >
                        <p>
                            {!! nl2br(e( closeTags(substr(trim($hub_info->description), 0, config('constants.JOB_DESCRIPTION_LIMIT') ).'...') ))!!}
                            <a href="javascript:void(0)" id="show_more" class="read-more float-none gilroyregular-semibold">Read more</a></p>
                    </div>
                    @endif

                    <div id="full_description" class="{{$class}}">
                        <p>
                            {!! nl2br(e($hub_info->description ?? '')) !!}
                            @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.JOB_DESCRIPTION_LIMIT'))
                            <a href="javascript:void(0)" id="show_less" class="read-more float-none gilroyregular-semibold"> Read less</a>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="vendor-logo">
                    <span class="modal-vandor-logo-img" style="background-image: url('{{$hub_info->logo}}')"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="block verified-experts vendor-verified-empty-block">
    <div class="row">
        <div class="col-md-12">
            <div class="vendor-verified-block">
                <div class="vendor-verified-expert">
                    <h5 class="font-14 gilroyregular-semibold">{{$hub_info->name ?? ''}} verified Experts:</h5>
                    @if(!$experts_listing->total())
                    <p class="font-12"><span class="gilroyregular-semibold">Please note:</span> The below boxes are placeholders. Clients will see <br /> the Experts which you have approved here</p>
                    @endif
                </div>
                <div class="vendor-expert-blocks">
                    <div class="row verified-experts-block">
                        <div class="v-align-box ver-expert-row">
                        @php $count = 0; @endphp
                        @for($i = 0; $i < 6; $i++)
                            @if($count != 0 && $count % 3 == 0)<div class="v-align-box ver-expert-row">@endif
                            @if($i <= ($experts_listing->total()-1))
                                @include('service-hub.approved_expert_block', ['approved_expert' => $experts_listing[$i]])
                            @else
                                <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 expert-detail-col">
                                    <div class="vendor-expert expert-empty-state">
                                        <span class="ver-expert-img"></span>
                                        <h4 class="font-14 gilroyregular-semibold expert-empty-name-state"></h4>
                                        <p class="expert-empty-desc-state"></p>
                                        <p class="expert-empty-desc-state"></p>
                                        <p class="expert-empty-desc-state"></p>
                                        <p class="expert-empty-desc-state"></p>
                                    </div>
                                </div>
                            @endif
                            @php $count++; @endphp
                            @if($count > 2 && $count % 3 == 0)</div>@endif                            
                        @endfor
                        </div>
                    </div>                    
                </div>
            
            <div class="vendor-contact">
                <a id="external_link" href="{{createExternalUrl($hub_info->service_website ?? '')}}" target="_blank"><img alt="cross" src="{{ url('images/vendor-website.svg',[],$ssl) }}"/> Website</a>
                <a href="mailto:{{$hub_info->sales_email ?? ''}}"><img alt="cross" src="{{ url('images/vendor-send.svg',[],$ssl) }}"/> Contact Sales</a>
            </div>
           </div>
            @if($experts_listing->total() && ($experts_listing->currentPage() < $experts_listing->lastPage()))
                <div id="view_more_experts_block" class="vendor-verified-block margin-top-10 margin-bottom-10 text-align-center">
                    <a class="loadmore-btn standard-btn"
                        id = "view_more_experts"
                        href = "javascript:void()"
                        page-number = '{{($experts_listing->currentPage()+1)}}'
                        service-hub-id = '{{$hub_info->id}}'>View more</a>
                </div>
            @endif
        </div>
    </div>
</div>