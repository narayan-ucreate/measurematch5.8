@extends('layouts.buyer_layout')
@section('content')
    <div id="wrapper" class="vendor-section">
        <div id="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="send-proposal-wrap">
                            <div class="send-proposal-header">
                                <div class="row">
                                    <div class="send-proposal-header-top">
                                        <div class="col-md-12 text-center">
                                            <h4>
                                                @if (isset($hub_info->publish) && $hub_info->publish)
                                                    Edit
                                                @else
                                                    Create
                                                @endif

                                                Your Service Hub</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="proposal-step-1  @if(isset($hub_info) && $hub_info) complete @endif @if($step == config('constants.VENDOR_HUB_STEP_1') || $step == null){{'active'}}@endif">
                                                <a href="{{route('service-hubs-create', [config('constants.VENDOR_HUB_STEP_1')])}}">
                                                    <span>Step 1</span>
                                                    @if (isset($hub_info->publish) && $hub_info->publish)
                                                        Edit
                                                    @else
                                                        Add
                                                    @endif
                                                     Hub Details
                                                </a>
                                            </li>

                                            <li role="presentation" class="proposal-step-2 proposal-action-tabs @if(_count($invited_experts) || $step == config('constants.VENDOR_HUB_STEP_3')) complete @endif @if($step == config('constants.VENDOR_HUB_STEP_2')){{'active'}}@endif">
                                                <a href="{{route('service-hubs-create', [config('constants.VENDOR_HUB_STEP_2')])}}">
                                                    <span>Step 2</span>Invite Existing Partners
                                                </a>
                                            </li>
                                            
                                            <li role="presentation" class="proposal-step-3 proposal-action-tabs @if(isset($hub_info->publish) && $hub_info->publish) complete @endif @if($step == config('constants.VENDOR_HUB_STEP_3')){{'active '}}@endif">
                                                <a href="{{route('service-hubs-create', [config('constants.VENDOR_HUB_STEP_3')])}}">
                                                    <span>Step 3</span>Review & Submit
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="vendor-step-1 add-hub-block @if($step != config('constants.VENDOR_HUB_STEP_1') && $step != null){{'hide'}}@endif">
                                    <form method="post" id="create-hub-first-step" enctype="multipart/form-data"  action="{{route('service-hubs-store')}}">
                                        <div class="block block-1">
                                            <div class="from-center">
                                                <div class="input-bx">
                                                    <label class="font-20">Add Your Service Hub Details</label>
                                                    <p>The fields in the form below will populate your Service Hub, which you will be able to preview before submitting. </p> <br />
                                                    <p>At the moment, only one Service Hub can be created per Technology Vendor account.</p>
                                                </div>
                                                
                                                <div class="input-bx">
                                                    <label>Your Service Hub name <a class="info-icon info-icon-left-arrow">? <span> Your Service Hub name will likely be your company name, but you can choose for it to be product-specific or product range-specific. It's your call.</span></a></label>
                                                    <input type="text" id="name" name="name" value="{{$hub_info->name ?? $vendor_data->company_name}}" placeholder="Tealium">
                                                </div>

                                                <div class="input-bx hub-logo-upload">
                                                    <label>Add your Service Hub Logo</label>
                                                    <input type="text" id="logo_placeholder" name="logo_placeholder" value="{{(isset($hub_info->logo)) ? basename($hub_info->logo) : ''}}"  readonly placeholder="Only .jpg, .jpeg or .png allowed. Max 1MB size." />
                                                    <input class="choose-file" id="logo" type="file" name="logo" value="Choose file" />
                                                    <button class="btn standard-btn">Choose file</button>
                                                    @if ($hub_info)
                                                        <p><a href="{{$hub_info->logo}}" target="_blank"> Click here to download</a></p>
                                                    @endif
                                                </div>

                                                <div class="input-bx">
                                                    <label>Your website address</label>
                                                    <input type="text" id="service_website" name="service_website" value="{{$hub_info->service_website ?? $vendor_data->company_url}}" placeholder="Add Website Address">
                                                </div>
             
                                                <div class="input-bx">
                                                    <label>Write an "About Us"</label>
                                                    <textarea name="description" value="" class="adding-text" id="description" data-updated="0">{{$hub_info->description ?? ''}}</textarea>
                                                    <span><span class="total_character_pending">300</span> character(s) left</span>
                                                </div>

                                                <div class="input-bx">
                                                    <label>Add a "sales" email address</label>
                                                    <p class="margin-bottom-10">Each Service Hub includes a high-visibility link for clients to strike up sales conversations with technology vendors.</p>
                                                    <input type="text" id="sales_email" name="sales_email" value="{{$hub_info->sales_email ?? ''}}" placeholder="e.g. sales@measurematch.com">
                                                </div>                 
                                            </div>
                                        </div>
                                        <div class="block">
                                            <div class="from-center">
                                                <div class="input-bx">
                                                    <label class="font-20">Add your most commonly requested services</label>
                                                    <p>These services are high-frequency use cases i.e. the kinds of services your clients are asking for regularly. These will be used to bucket your service provider partners in your Service Hub.</p>
                                                </div>
                                                <div class="input-bx">
                                                    <label>Services</label>
                                                    <div class="service-category-section">

                                                        @if (isset($hub_info->serviceCategories))
                                                            @foreach($hub_info->serviceCategories as $category)
                                                            <div class="service-cat">
                                                                <input type="text" name="service_category_name[]" class="service-category-name" value="{{$category->name}}" placeholder="e.g. Implementation & Setup" />
                                                                <a class="delete-btn delete-categories @if (_count($hub_info->serviceCategories) == 1) hide @endif" href="#"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                            @endforeach
                                                        @else
                                                            <input type="hidden" name="action" value="create">                                                            
                                                            <div class="service-cat">
                                                                <input type="text" name="service_category_name[]" class="service-category-name" value="" placeholder="e.g. Implementation & Setup" />
                                                                <a class="delete-btn hide delete-categories" href="#"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                      @endif
                                                    </div>

                                                    <a class="gilroyregular-semibold font-14 add-more" href="#">
                                                        <img class="add-more-icon" alt="cross" src="{{ url('images/plus.svg',[],$ssl) }}"/> 
                                                        Add another service
                                                    </a>
                                                </div>

                                                <div class="input-bx">
                                                    <label>MeasureMatch Terms</label>
                                                    <div class="check-box-design">
                                                        <input tabindex="8" id="terms_and_condition" name="terms_and_condition" class="terms-and-conditions" type="checkbox"
                                                        {{$hub_info ? 'checked' : ''}}
                                                        >
                                                        <label for="terms_and_condition">
                                                            <span><span></span></span>I have read &amp; I consent to the <a id="tnc_link" href="https://web.measurematch.com/terms-of-service" target="_blank">MeasureMatch Terms of Service</a>
                                                        </label>
                                                        <span class="terms-and-conditions-error validation_error"></span>
                                                    </div>

                                                    <div class="check-box-design">
                                                        <input tabindex="8" name="code_of_conduct" id="code_of_conduct" class="terms-and-conditions"
                                                               {{$hub_info ? 'checked' : ''}}
                                                               type="checkbox">
                                                        <label for="code_of_conduct">                                                        
                                                            <span><span></span></span>I have read &amp; I consent to the  <a id="tnc_link" href="https://web.measurematch.com/code-of-conduct" target="_blank">MeasureMatch Code of Conduct</a>
                                                        </label>
                                                        <span class="code-of-conduct-error validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="input-bx">
                                                    <input id="submit_basic_information" tabindex="5" type="submit" class="font-16 btn standard-btn full-width-btn" value="Save & continue to next step" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="service-cat-hidden hide">
                                        <div class="service-cat">
                                            <input type="text" name="service_category_name[]" class="service-category-name" value="" placeholder="e.g. Implementation & Setup" />
                                            <a class="delete-btn delete-categories" href="#"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="vendor-step-2 invite-service-block @if($step != config('constants.VENDOR_HUB_STEP_2')){{'hide'}}@endif">
                                    <div class="block">
                                        <div class="from-center">
                                            <div class="input-bx">
                                                <label class="font-20">Invite at least 3 existing Service Partners</label>
                                                <p>These service partners will be automatically approved to be featured in your Service Hub.</p>
                                            </div>
                                            <div class="input-bx service-partners">
                                                <label>Invite 3 different service partners:</label>
                                                <form method="post" id="create-hub-second-step" enctype="multipart/form-data"  action="{{route('store-invited-experts')}}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="service_hub_id" value="{{($hub_info->id) ?? ''}}">
                                                    <input type="hidden" name="inviting_experts_mandatory" value="{{($inviting_experts_mandatory) ? '1' : '0'}}">
                                                    <input type="hidden" id='latest_service_partner_row_number' value="2">
                                                    @php $invited_experts_count = (_count($invited_experts) > 2) ? _count($invited_experts) : 3; @endphp
                                                    @for($i = 0; $i < $invited_experts_count; $i++)

                                                    <div class="row margin-top-20 vendor-invites-input service-partner-{{$i}}" service-partner-row = "{{$i}}">
                                                        <div class="col-md-3 right-spacing-0">
                                                            <input type="text" name="first_name[]" value="{{($invited_experts[$i]['first_name']) ?? ''}}" placeholder="First name" autofocus>
                                                        </div>
                                                        <div class="col-md-3 right-spacing-0">
                                                            <input type="text" name="last_name[]" value="{{($invited_experts[$i]['last_name']) ?? ''}}" placeholder="Last name">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="email[]" value="{{($invited_experts[$i]['email']) ?? ''}}" placeholder="e.g. tom@measurematch.com">
                                                        </div>
                                                        <a class="delete-btn delete_partner hide" href="javscript:void(0);" service-partner-row = "{{$i}}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>

                                                    @endfor
                                                    <div class="row add-more-categories-block">
                                                        <div class="col-md-12  margin-top-20">
                                                            <a class="gilroyregular-semibold font-14 add-another-partner" href="javascript:void(0)">
                                                                <img class="add-more-icon" alt="cross" src="{{ url('images/plus.svg',[],$ssl) }}"/> Invite another Service Partner
                                                            </a>
                                                        </div>
                                                    </div>                                                
                                                </div>
                                                <div class="input-bx">
                                                    <div class="row">                                                    
                                                        <div class="@if($inviting_experts_mandatory){{'col-md-12 second-step-btn'}}@else{{'col-md-9'}}@endif">
                                                            <input id="submit_basic_information" tabindex="5" type="submit" class="btn standard-btn font-16 full-width-btn" value="Save & continue to next step" />
                                                        </div>

                                                        @if(!$inviting_experts_mandatory)
                                                        <div class="col-md-3">
                                                            <a href="{{route('service-hubs-create', [config('constants.VENDOR_HUB_STEP_3')])}}"
                                                               class="btn blue-border-btn full-width-btn skip-btn"/>Skip</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="vendor-step-3 invite-service-block @if($step != config('constants.VENDOR_HUB_STEP_3')){{'hide'}}@endif">
                                    <div class="vendor-review-step-bg">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex">
                                                    <div class="vendor-dis-block">
                                                        <h4 class="gilroyregular-semibold font-24">{{$hub_info->name ?? ''}}</h4>
                                                        @php $class = ''; @endphp
                                                        @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT'))
                                                            @php $class = 'hide'; @endphp
                                                            <div id="truncated_description" >
                                                                <p>
                                                                {!! nl2br(e( closeTags(substr(trim($hub_info->description), 0, config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT') ).'...') ))!!}
                                                                <a href="javascript:void(0)" id="show_more" class="read-more gilroyregular-semibold">Read more</a></p>
                                                            </div>
                                                        @endif

                                                        <div id="full_description" class="{{$class}}">
                                                            <p>
                                                            {!! nl2br(e($hub_info->description ?? '')) !!}
                                                                @if(isset($hub_info->description) && strlen($hub_info->description) > config('constants.VENDOR_SERVICE_HUB_DESCRIPTION_LIMIT'))
                                                                    <a href="javascript:void(0)" id="show_less" class="read-more  gilroyregular-semibold"> Read less</a>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="vendor-logo">
                                                        <img src="{{$hub_info->logo ?? ''}}" alt="" width="100"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="vendor-verified-block">
                                                    <div class="vendor-verified-expert">
                                                        <h5 class="font-14 gilroyregular-semibold">{{$hub_info->name ?? ''}} verified Experts:</h5>
                                                        <p class="font-12"><span class="gilroyregular-semibold">Please note:</span> The below boxes are placeholders. Clients will see <br />the Experts which you have approved here</p>
                                                    </div>
                                                    <div class="vendor-expert-blocks">
                                                        @for($j = 0; $j < 2; $j++)
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="vendor-expert expert-empty-state">
                                                                        <span class="ver-expert-img"></span>
                                                                        <h4 class="font-14 gilroyregular-semibold expert-empty-name-state"></h4>
                                                                        @for($i = 0; $i < 3; $i++)
                                                                            <p class="expert-empty-desc-state"></p>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="vendor-expert expert-empty-state">
                                                                        <span class="ver-expert-img"></span>
                                                                        <h4 class="font-14 gilroyregular-semibold expert-empty-name-state"></h4>
                                                                        @for($i = 0; $i < 3; $i++)
                                                                            <p class="expert-empty-desc-state"></p>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="vendor-expert expert-empty-state">
                                                                        <span class="ver-expert-img"></span>
                                                                        <h4 class="font-14 gilroyregular-semibold expert-empty-name-state"></h4>
                                                                        @for($i = 0; $i < 3; $i++)
                                                                            <p class="expert-empty-desc-state"></p>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <div class="vendor-contact">
                                                    <a href="{{createExternalUrl($hub_info->service_website ?? '')}}" target="_blank"><img alt="cross" src="{{ url('images/vendor-website.svg',[],$ssl) }}"/> Website</a>
                                                    <a href="mailto:{{$hub_info->sales_email ?? ''}}"><img alt="cross" src="{{ url('images/vendor-send.svg',[],$ssl) }}"/> Contact Sales</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vendor-review-footer block">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <form method="post" action="{{route('service-hubs-store')}}">
                                                <a class="btn blue-border-btn" href="{{route('service-hubs-create', ['step-1'])}}"/>Go Back & Edit Hub</a>
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="steps" value="3">
                                                    <input id="submit_basic_information" tabindex="5" type="submit" class="btn font-16 standard-btn" value="Save & continue to next step" />
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('include.basic_javascript_liberaries')
    <script type="text/javascript" src="{{ url('js/autosize.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap-select.js?js='.$random_number,[],$ssl)}}"></script>
    <script type="text/javascript" src="{{ url('js/service_hub.js?js='.$random_number,[],$ssl)}}"></script>
    <script> var base_url = "{{ url('/',[],$ssl) }}"; </script>
    @include('include.footer')
@endsection
