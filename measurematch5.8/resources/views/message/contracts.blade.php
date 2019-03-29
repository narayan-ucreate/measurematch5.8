@php  $user_id = (Auth::user()->user_type_id != config('constants.ADMIN')) ? Auth::user()->id : $project_detail['user_id']; @endphp
@if(!empty($user_profile) && !empty($project_detail))
@if(isset($user_type))
<input type="hidden" id="contract_id" name="contract_id" value="@if(isset($contract_detail)){{$contract_detail['id']}}@endif">
<input type="hidden" id="latest_contract_id" value="@if(isset($latest_contract->id)) {{ $latest_contract->id }} @endif">
<input type="hidden" id="latest_contract_accepted" value="@if(isset($latest_contract->status)) {{ $latest_contract->status }} @endif">
<input type="hidden" id="contract_start_date" value="@if(isset($latest_contract->job_start_date)) {{ date('j M, Y', strtotime($latest_contract->job_start_date)) }} @endif">
 @php
 $job_end_date='';
 if( isset( $latest_contract->subscription_type) && $latest_contract->subscription_type == 'monthly_retainer') {
 $job_end_date='Monthly Retainer (cancel anytime)';
 }else{
 $job_end_date= isset($latest_contract->job_end_date) ? date('j M, Y', strtotime($latest_contract->job_end_date)) :'';
 } 
 $communication_id = isset($communication_id) ? $communication_id : null;
 @endphp
<input type="hidden" id="contract_end_date" value="{{$job_end_date}}">
<input type="hidden" id="contract_rate" value="@if(isset($latest_contract->rate)) {{ number_format($latest_contract->rate) }} @endif">
<input type="hidden" id="project_type"  value="@if(isset($project_detail['type'])){{$project_detail['type']}}@endif">
<input type="hidden" id="project_id"  value="@if(isset($project_detail['id'])){{$project_detail['id']}}@endif">
<input type="hidden" id="contract_subscription"  value="@if(isset($contract_detail['subscription_type'])){{$contract_detail['subscription_type']}}@endif">
@if($user_type==1)


<div class="expert-contract-options">
    <div class="contract-checklist-design-panel margin-bottom-10 pull-left">
        <h3 class="gilroyregular-semibold">How MeasureMatch works:</h3>
    <div class="check-box-design initiate-message">
        @if($project_detail['type']=='project')
        <input disabled=""  type="checkbox" name="" value="1" id="conversation-{{$user_id}}">
        <label for="conversation-{{$user_id}}"><span><span></span></span>1. Conversation initiated</label>
        @elseif( $project_detail['type'] == "service_package")
         <input disabled=""  type="checkbox" name="" value="1" id="expert-action-availability-accepted-{{$user_id}}">
        <label for="expert-action-availability-accepted-{{$user_id}}"><span><span></span></span>1. Expert sends availability</label>
        @endif
    </div>
    <div class="check-box-design">
        <input class="buyer-contracts" disabled="" id="create-offer" type="checkbox" name="" value="1">
        <label for="create-offer"><span><span></span></span>2. Proposal sent</label>
    </div>
    <div class="check-box-design">
        <input disabled="" id="expert-action-accepted-offer-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="expert-action-accepted-offer-{{$user_id}}"><span><span></span></span>3. Contract confirmed</label>
    </div>
    <div class="check-box-design">
        <input disabled="" id="check-expert-project-compelted-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="check-expert-project-compelted-{{$user_id}}"><span><span></span></span>4. @if($project_detail['type']=='project'){{'Project completed'}}@else @if($project_detail['subscription_type']=='monthly_retainer'){{'Package ended'}}@else{{'Package completed'}}@endif @endif</label>
    </div>
    <div class="check-box-design">
        <input disabled="" id="check-expert-feedback-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="check-expert-feedback-{{$user_id}}"><span><span></span></span>5. Feedback received</label>
    </div>
    @if($project_detail['type']=='service_package' && $project_detail['subscription_type']=='monthly_retainer')
    <div class="check-box-design">
        <input disabled="" id="check-expert-payment-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="check-expert-feedback-{{$user_id}}"><span><span></span></span>6. Final Payment</label>
    </div>
    @endif
    </div>
    <div  class="contract-process-btn-panel"  style="display:none;" id="make-offer">
        <a id="action-make-offer" href='{{route('send-proposal', [$communication_id, 1])}}' data-user_id="{{$user_id}}" 
           class="send-contract full-width-btn standard-btn margin-bottom-10" title="Send a Proposal" >Send a Proposal</a>
    </div>
    <div class="contract-process-btn-panel view-proposal-{{$user_id}} view-contract-by-expert" id="view-offer-{{$user_id}}" @if(empty($contract_detail)) style="display: none;" @endif>
        @if(!isset($latest_contract->status) 
            || (isset($latest_contract->status) 
            && !$latest_contract->status 
            && empty($latest_contract->parent_contract_id)))
            <a href="javascript:void(0)" userid="{{$user_id}}" usertype="{{config('constants.EXPERT')}}" class="send-contract font-14  btn standard-btn  margin-bottom-10 full-width-btn" title="View/Edit Proposal" data-toggle="modal" data-target="#expert-contract-preview">
                View/Edit Proposal
            </a>
        @else
            <a href="javascript:void(0)" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 full-width-btn" title="View Contract">View Contract</a>
        @endif
    </div>
    <div class="contract-process-btn-panel" id="expert-project-completed-{{$user_id}}" style="display:none">
        @if($project_detail['type']=='project')
        <a  href="javascript:void(0)" userid="{{$user_id}}" usertype="1" communicationid="" class="send-contract standard-btn mark-project-completed-btn full-width-btn" title="Mark project as complete" id="mark_contract_complete_confirmation_button" @if(isset($latest_contract->id)) contract_id="{{  $latest_contract->id }}" @endif>
        {{'Mark project as complete'}}
        @else
        <a @if($project_detail['subscription_type']!='monthly_retainer') id="mark_contract_complete_confirmation_button" @if(isset($latest_contract->id)) contract_id="{{  $latest_contract->id }}" @endif @endif href="javascript:void(0)" userid="{{$user_id}}" usertype="1" communicationid="" class="send-contract standard-btn full-width-btn finiesh/ @if($project_detail['subscription_type']=='monthly_retainer') expert-finish-service-contract @endif" title="@if($project_detail['subscription_type']=='monthly_retainer'){{'Finish/Cancel Retainer'}}@else{{'Mark package as complete'}}@endif">
            @if($project_detail['subscription_type']=='monthly_retainer')
            {{'Finish/Cancel Retainer'}}
            @else
            {{'Mark package as complete'}}
            @endif
        @endif
        </a>
    </div>

</div>
@include('message.popups.expert_view_popups')
@else
<div class="buyer-contract-options contract-checklist-design-panel pull-left">
    <h3 class="gilroyregular-semibold">How MeasureMatch works:</h3>
    <div class="check-box-design initiate-message">
        @if($project_detail['type']=='project')
        <input class="buyer-contracts"  ischeck="" disabled="" id="initiated-conversation" type="checkbox" name="" value="1">
        <label for="initiated-conversation"><span><span></span></span>1. Conversation initiated</label>
        @elseif( $project_detail['type'] == "service_package")
         <input disabled=""  type="checkbox" name="" value="1" id="availability-accepted-{{$user_id}}">
        <label for="availability-accepted-{{$user_id}}"><span><span></span></span>1. Expert sends availibility</label>
        @endif
    </div>
    <div class="check-box-design">
        <input disabled="" id="offer-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="offer-{{$user_id}}"><span><span></span></span>2. Proposal received</label>
    </div>
    <div class="check-box-design">
        <input class="buyer-contracts" disabled="" id="buyer-accepted-offer-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="buyer-accepted-offer-{{$user_id}}"><span><span></span></span>3. Contract confirmed</label>
    </div>
    <div class="check-box-design">
        <input class="buyer-contracts" disabled="" id="check-buyer-project-compelted-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="check-buyer-project-compelted-{{$user_id}}"><span><span></span></span>4. @if($project_detail['type']=='project'){{'Project completed'}}@else @if($project_detail['subscription_type']=='monthly_retainer'){{'Package ended'}}@else{{'Package completed'}}@endif @endif</label>
    </div>
    <div class="check-box-design">
        <input class="buyer-contracts" disabled="" id="buyer-feedback-{{$user_id}}" type="checkbox" name="" value="1">
        <label for="buyer-feedback-{{$user_id}}"><span><span></span></span>5. Feedback received</label>
    </div>
</div>
@if($project_detail['type']=='service_package' && $project_detail['subscription_type']=='monthly_retainer')
<div class="contract-checklist-design-panel final-payment-offer">
<div class="check-box-design">
    <input disabled="" id="check-buyer-payment-{{$user_id}}" type="checkbox" name="" value="1">
    <label for="check-expert-feedback-{{$user_id}}"><span><span></span></span>6. Final Payment</label>
</div>
    </div>
@endif
@if(Auth::user()->user_type_id == config('constants.ADMIN'))
<div onclick="showContractViewPopUp('@if(isset($contract_detail)){{$contract_detail['id']}}@endif');" class="contract-process-btn-panel" id="view-offer-{{$user_id}}" @if(empty($contract_detail)) style="display: none;" @endif>
    @if(!isset($latest_contract->status) || (isset($latest_contract->status) && !$latest_contract->status))

    <a  href="javascript:void(0)" userid="{{$user_id}}" usertype="1" communicationid="" class="send-contract standard-btn margin-top-10 full-width-btn" title="View Offer"  data-toggle="modal" data-target="#expert-contract-preview">
            View Proposal
    </a>
        @else
        <a href="javascript:void(0)" userid="{{$user_id}}" usertype="1" communicationid="" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 full-width-btn" title="View Contract">View Contract</a>
    @endif
</div>
@endif
@if(Auth::user()->user_type_id != config('constants.ADMIN'))
<div  onclick="viewOffer(this);" class="contract-process-btn-panel view-edit-offer-details-{{$user_id}}" @if(empty($contract_detail)) style="display: none;" @endif>
    @if(!isset($latest_contract->status) || (isset($latest_contract->status) && !$latest_contract->status)) 
      <a href="javascript:void(0)" class="send-contract font-14  btn standard-btn  margin-bottom-10 full-width-btn" title="View Proposal">View Proposal</a>
    @else
    <a href="javascript:void(0)" class="send-contract font-14 white-bg white-bg-btn margin-bottom-10 full-width-btn" title="View Contract">View Contract</a>
    @endif 
</div>
<div class="buyer-contract-actions">

   <div class="contract-process-btn-panel" id="buyer-project-compelted-{{$user_id}}" style="display:none;">
    @if($project_detail['type']=='project')
    <a href="javascript:void(0)" class="standard-btn mark-project-complete-btn full-width-btn" title="Mark project as complete" id="buyer_mark_contract_complete_confirmation_button" @if(isset($latest_contract->id)) current_contract_id="{{  $latest_contract->id }}" @endif>Mark project as complete</a>
    @else
     @if($project_detail['subscription_type']=='monthly_retainer')
     <a href="javascript:void(0)" class="editcontract mark-project-complete-btn standard-btn full-width-btn  buyer-finish-service-contract" title="Finish/Cancel Retainer">Finish/Cancel Retainer </a>
        @else
     <a href="javascript:void(0)" class="editcontract mark-project-complete-btn standard-btn full-width-btn" title="Mark package as complete"id="buyer_mark_contract_complete_confirmation_button" @if(isset($latest_contract->id)) current_contract_id="{{ $latest_contract->id }}" @endif >Mark package as complete</a>
     @endif
    @endif
    </div>
    @if($project_detail['type'] == "project")
    <div onclick="feedbackGivenByBuyer();" class="contract-process-btn-panel" id="buyer-feedback-to-expert-{{$user_id}}" style="display:none;">
        <a href="javascript:void(0)" class="editcontract standard-btn full-width-btn" title="Give Feedback">Give Feedback</a>
    </div>
    @endif
</div>
@endif

@include('message.popups.buyer_view_popups')
@endif
@endif
@endif
