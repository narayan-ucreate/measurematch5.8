<div class="modal-dialog modal-lg view-project-modal buyer-setting-view-contract-popup">
    <div class="modal-content">
        <div class="modal-body">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                <span aria-hidden="true">
                    <img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}">
                </span>
            </button>
            <div class="modal-container">
                @if (isExpert())
                <h3 class="margin-bottom-25"> Proposal to {{ ucfirst($buyer_profile->company_name) ?? ''}}</h3>
                @else
                <div class="user-profile perposalview-blocks">
                    <img src="{{$contract_detail['expert']['user_profile']['profile_picture']}}">
                    <h3>{{$contract_detail['expert']['name']}}’s Proposal</h3>
                </div>
                @endif
                <div class="row perposalview-blocks">
                    <div class="col-md-6">
                        <h5>{{$contract_detail['service_package_id'] ? 'Package' : 'Project'}} Start Date</h5>
                        <p>{{date('d M Y', strtotime($contract_detail['job_start_date']))}}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h5>Estimated Completion Date</h5>
                        @php
                        $job_end_date = isset($contract_detail['job_end_date']) ? date('d M Y', strtotime( $contract_detail['job_end_date'])) :'';
                        if (isset( $contract_detail['subscription_type']) &&  $contract_detail['subscription_type'] == config('constants.MONTHLY_RETAINER')) {
                        $job_end_date = 'Monthly Retainer (cancel anytime)';
                        }
                        @endphp

                        <p>{{$job_end_date}}</p>
                    </div>
                </div>
                <div class="perposalview-blocks">
                    <h5>Total value of proposal</h5>
                    @php $is_monthly=(isset( $contract_detail['subscription_type']) &&  $contract_detail['subscription_type'] == 'monthly_retainer') ?"/month":"";  @endphp
                    <p>{{ convertToCurrencySymbol($contract_detail['rate_variable']) }} {{ number_format($contract_detail['rate']).$is_monthly}}
                    </p>
                </div>
                <div class="job-post-deliverable  perposalview-blocks">
                    <h5>Deliverables for the {{$contract_detail['service_package_id'] ? 'package' : 'project'}}</h5>
                    @if($contract_detail['type']=='project')
                    @if(isset($contract_detail['project_deliverables']) && !empty($contract_detail['project_deliverables']) && ($contract_detail['project_deliverables'] !=' '))
                    <p>{!! $contract_detail['project_deliverables'] !!} </p>
                    @endif
                    @else
                    <ul class="contact-deliverable-list" >
                        @foreach ($deliverables as $deliverable)
                        <li>{!! $deliverable->deliverable !!} </li>
                        @endforeach
                        @endif
                </div>
                <div class="perposalview-blocks">
                    <h5>Attachments</h5>
                    @if(isset( $contract_detail['upload_document'])
                    && !empty( $contract_detail['upload_document'])
                    && ( $contract_detail['upload_document'] !=' '))
                    @php
                    $splitted_image = explode('/', $contract_detail['upload_document']);
                    $original_image_name = explode('_', end($splitted_image));
                    unset($image_explode);
                    foreach ($original_image_name as $key => $image) {
                    if ($key != 0) {
                    $image_explode[] = $image;
                    }
                    }
                    $image_names = implode($image_explode);
                    @endphp
                    <a class="attached-files-link" target="_blank" 
                       title="Attach file(s)" 
                       href="@if(isset( $contract_detail['upload_document']) && !empty( $contract_detail['upload_document']) && ( $contract_detail['upload_document'] !=' ')) {{  $contract_detail['upload_document'] }} @else {{ 'javascript:void(0);' }} @endif">
                        {{ $image_names }}
                    </a> @else
                    <span class="no_attachment_block font-14">No documents attached</span>
                    @endif
                </div>

                <div class="contract-popup-actions">
                    @if(!$contract_accepted && isExpert())
                    <input class="btn-standard-left standard-btn"
                           type="button" id="contract_preview_edit"
                           value="Edit Proposal"
                           contract_id="{{ $contract_detail['id'] }}"
                           project_id="{{$contract_detail['job_post_id']}}"
                           project_name="{{$contract_detail['post_jobs']['job_title']}}"
                           project_rate="{{$contract_detail['rate']}}"
                           project_start_date="{{ date('d-m-Y',strtotime($contract_detail['job_start_date'])) }}"
                           project_end_date= "{{date('d-m-Y',strtotime($contract_detail['job_end_date']))}}"
                           deliverables="{{$contract_detail['project_deliverables']}}"
                           supporting_docs="@if(!empty($contract_detail['upload_document']))<a target='_blank' href='{{$contract_detail["upload_document"]}}'>Attachment</a>@else NA @endif"
                           expert_name='{{ucfirst($contract_detail['expert']['name'])}}'>
                    <a href="javascript:void(0);" class="cancel-btn" data-dismiss="modal">Cancel</a>
                    @elseif(isBuyer())
                    @if( $contract_detail['status'] ==0)
                    @php
                    $user_id = Auth::user()->id;
                    $contract_exists = "0";
                    @endphp
                    @if(!$contract_accepted)
                    <a title="Accept Proposal" data-contract-enddate="{{  $contract_detail['job_end_date'] }}" contract_confirm="0"
                       class="accept-contract-btn standard-btn new_blue_btn margin-0" data-commId ="{{  $contract_detail['communications_id'] }}"
                       project_id="{{   $contract_detail['job_post_id'] }}" data-sender="{{  $contract_detail['buyer_id'] }}"
                       data-receiver="{{  $contract_detail['user_id'] }}" id="{{  $contract_detail['id'] }}"
                       data-contract_type="{{  $contract_detail['type'] }}" href="javascript:void(0);">Accept Proposal</a>
                    @endif
                    @else
                    @if($contract_detail['subscription_type']!="monthly_retainer" && null !== app('request')->input('source') && app('request')->input('source')=='messages')
                    <a title="Download Contract" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 message-download-contract-btn white-btn-middle" href="{{ url("contract/".$contract_detail['id']."/download",[],$ssl) }}" target="_blank">Download Contract</a>
                    @endif
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>