@php $current_user = Auth::user();@endphp
<div class="modal-dialog modal-lg">
    <div class="modal-innner-content">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">
                        <img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}">
                    </span>
                </button>
                <div class="@if(isExpert()) expert-listing-detail @endif listing-details project-detail-block">
                            <div class="row">
                                @if($current_user->user_type_id == config('constants.BUYER'))
                                    <div class="col-md-3">
                                        <div class=" user-profile perposalview-blocks">
                                            <img src="{{$contract_detail['expert']['user_profile']['profile_picture']}}" alt="Expert" alt="user-img"/>
                                        </div>
                                    </div>
                                @endif
                                <div class=" @if($current_user->user_type_id == config('constants.EXPERT')) col-md-12 @else col-md-9 @endif">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h3 class="font-24 gilroyregular-semibold margin-bottom-20 input-bx vat-section countryblock">
                                                @if(!$contract_accepted)
                                                    {{ucfirst($contract_detail['expert']['name'])}}'s Proposal
                                                @else
                                                    @if(isset($source) && $source=='admin')
                                                        Contract between {{ ucfirst($contract_detail['expert']['name']) . ' ' . ucfirst($contract_detail['expert']['last_name']) }}
                                                        and {{$buyer_information->buyer->company_name}}
                                                    @else
                                                        @if($current_user->user_type_id == config('constants.BUYER'))
                                                            Your Contract with {{ucfirst($contract_detail['expert']['name'])}}
                                                        @else
                                                            Your Contract with {{$buyer_information->buyer->company_name}}
                                                        @endif
                                                    @endif
                                                @endif
                                            </h3>
                                        </div>
                                        <div class="col-md-5">
                                            @php $project = ($project_type == config('constants.PROJECT'))? 'Project' : 'Package' @endphp
                                            <p class="margin-bottom-0 label-text">{{$project}} Start Date:</p>
                                            <p class="font-16 gilroyregular-semibold">{{isset($contract_detail['job_start_date']) ? date('D d M, Y', strtotime($contract_detail['job_start_date'])) : ''}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="margin-bottom-0 label-text">Estimated Completion Date:</p>
                                            <p class="font-16 gilroyregular-semibold">{{isset($contract_detail['job_end_date']) ? date('D d M, Y', strtotime($contract_detail['job_end_date'])) : ''}}</p>
                                        </div>
                                        @if($contract_detail['status'] == config('constants.ACCEPTED')
                                        && ($project_type == config('constants.PROJECT'))
                                        && ($current_user->user_type_id == config('constants.BUYER')))
                                            <div class="col-md-12">
                                                <a href="javascript:void(0)" class="standard-btn btn rebook-project"
                                                   data-expert-url="{{$contract_detail['expert']['user_profile']['profile_picture']}}"
                                                   data-expert-id="{{$contract_detail['expert']['user_profile']['user_id']}}"
                                                   data-expert-name="{{ucfirst($contract_detail['expert']['name'])}}"

                                                   title="Book {{ucfirst($contract_detail['expert']['name'])}} Again" id="">Book {{ucfirst($contract_detail['expert']['name'])}} Again</a>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!$contract_accepted && ($current_user->user_type_id == config('constants.BUYER')))
                                    <div class="row margin-top-10">
                                        <div class="col-lg-12">
                                            <a title="Accept Proposal" data-contract-enddate="{{  $contract_detail['job_end_date'] }}" contract_confirm="0"
                                               class="accept-contract-btn standard-btn new_blue_btn margin-0" data-commId ="{{  $contract_detail['communications_id'] }}"
                                               project_id="{{   $contract_detail['job_post_id'] }}" data-sender="{{  $contract_detail['buyer_id'] }}"
                                               data-receiver="{{  $contract_detail['user_id'] }}" id="{{  $contract_detail['id'] }}"
                                               data-contract_type="{{  $contract_detail['type'] }}" href="javascript:void(0);">
                                                Accept {{ucfirst($contract_detail['expert']['name'])}}'s Proposal
                                            </a>
                                            <a class="reivew-proposal-link"  href="javascript:void()" class="discuss-with-expert" data-dismiss="modal">Ask {{ucfirst($contract_detail['expert']['name'])}} a question</a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                        
                            </div>
                        </div>
            </div>
            <div class="modal-body">
                
                <div class="modal-container">
                    <div class="tab-pane active" id="review_submit_proposal">
                        @if($current_user->user_type_id == config('constants.BUYER') && !$contract_accepted)
                        <div class="listing-details margin-bottom-0 margin-top-20">
                            <div class="row margin-bottom-30">
                                    <div class="col-md-12">
                                        <h5  class="font-18 margin-top-5 gilroyregular-semibold">Why {{ucfirst($contract_detail['expert']['name'])}}</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="font-16 hover-bg">
                                            {!! ($contract_detail['introduction']) ? nl2br($contract_detail['introduction']) : '' !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5  class="font-18 margin-top-5 gilroyregular-semibold">{{ucfirst($project)}} goals</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="font-16 hover-bg">
                                            {!! ($contract_detail['summary']) ? nl2br($contract_detail['summary']) : '' !!}
                                        </p>
                                    </div>
                                </div>
                        </div>
                        @endif
                        <div class="add-deliverable-section">
                            <label>Deliverables &amp; Fees</label>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 project-deliverable-listing">
                                <div class="row deliverable-container">
                                    @include('proposal.list_deliverable', ['deliverables' => $contract_detail, 
                                                                            'expert_id' => $contract_detail['user_id'], 
                                                                            'expert_name' => $contract_detail['expert']['name'],
                                                                            'is_pop_up' => true])
                                </div>
                            </div>
                        </div>
                        @if (isset($contract_detail['terms']) && _count($contract_detail['terms']))
                        <div class="fullwidth-block">
                        <div class="listing-details terms-detail-block">
                            <div class="row margin-bottom-40">
                                <div class="col-md-12">
                                    <label class="gilroyregular-semibold">{{ucfirst($contract_detail['expert']['name'])}}'s Terms</label>
                                    <ul>
                                        @foreach($contract_detail['terms'] as $key => $term)
                                        <li>{!! nl2br($term['term']) !!} </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>    
                        @endif
                        
                    </div>
                </div>
            </div>
               @if(($current_user->user_type_id == config('constants.BUYER')))
            <div class="modal-footer">
             
                            @if(!$contract_accepted)
                                <a title="Accept Proposal" data-contract-enddate="{{  $contract_detail['job_end_date'] }}" contract_confirm="0"
                                   class="accept-contract-btn standard-btn new_blue_btn margin-0" data-commId ="{{  $contract_detail['communications_id'] }}"
                                   project_id="{{   $contract_detail['job_post_id'] }}" data-sender="{{  $contract_detail['buyer_id'] }}"
                                   data-receiver="{{  $contract_detail['user_id'] }}" id="{{  $contract_detail['id'] }}"
                                   data-contract_type="{{  $contract_detail['type'] }}" href="javascript:void(0);">Accept {{ucfirst($contract_detail['expert']['name'])}}'s Proposal</a>
                                <a class="reivew-proposal-link" href="javascript:void()" class="discuss-with-expert" data-dismiss="modal">Ask {{$contract_detail['expert']['name']}} a question</a>
                            @else
                                @if($contract_detail['subscription_type']!="monthly_retainer" && null !== app('request')->input('source') && app('request')->input('source')=='messages')
                                    <a title="Download Contract" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 message-download-contract-btn white-btn-middle" href="{{ url("contract/".$contract_detail['id']."/download",[],$ssl) }}" target="_blank">Download Contract</a>
                                @endif
                            @endif

                       
                        @if(($current_user->user_type_id == config('constants.EXPERT') && $contract_detail['status'] != 1))
                            <a href="{{route('send-proposal', [$contract_detail['communications_id'], 1])}}" class="send-contract btn standard-btn  margin-bottom-10">Edit Proposal</a>
                        @endif
                    </div>
               @endif
                
                        @if(($current_user->user_type_id == config('constants.EXPERT') && $contract_detail['status'] != 1))
                        <div class="modal-footer">
                            <a href="{{route('send-proposal', [$contract_detail['communications_id'], 1])}}" class="send-contract btn standard-btn  margin-bottom-10">Edit Proposal</a>
                        </div>
                            @endif
                        
                </div>
            </div>
        </div>
    </div>
</div>
