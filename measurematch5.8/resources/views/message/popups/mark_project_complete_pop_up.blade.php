<div class="modal-dialog" role="document">
    <div class="modal-innner-content">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img  alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
            </div>
              <div class="modal-body">
                  @php $contract_acceptance_detail = isContractCompletionAllowed($contract_detail['communications_id']); @endphp
                  @if($contract_acceptance_detail['mark_complete_allowed'] == 'yes')
                    @if($contract_detail['type'] == 'project') 
                    <h3>{{ucfirst($contract_detail['post_jobs']['job_title'])}}</h3>
                    <h4 class="gilroyregular-bold-font text-align-center font-24">Mark project as complete</h4>
                    <p>By marking the project as complete, you are confirming the contract deliverables have been met. Once
                        confirmed, payment will be processed by MeasureMatch.</p>
                    <input sender_id="{{ Auth::user()->id }}" communications_id="{{ $contract_detail['communications_id'] }}" user_id="{{$contract_detail['user_id']}}" type="button" id="{{ $contract_detail['id'] }}" payment_mode="{{ $contract_detail['payment_mode'] }}" project_id="{{ $contract_detail['job_post_id'] }}" value="Confirm" class="mark-as-complete clearfix new_blue_btn standard-btn"  >
                    @else
                    <h4 class="gilroyregular-bold-font text-align-center font-24">Mark Package as complete</h4>
                    <p>By marking the Package as complete, you are confirming the contract deliverables have been met. Once
                        confirmed, payment will be processed by MeasureMatch.</p>
                    <input sender_id="{{ Auth::user()->id }}" communications_id="{{ $contract_detail['communications_id'] }}" user_id="{{$contract_detail['user_id']}}" type="button" id="{{ $contract_detail['id'] }}" payment_mode="{{ $contract_detail['payment_mode'] }}" project_id="{{ $contract_detail['job_post_id'] }}" value="Yes, mark Package as complete" class="mark-as-complete clearfix new_blue_btn standard-btn"  >
                    @endif
                  @elseif($contract_acceptance_detail['mark_complete_allowed'] == 'no' && array_key_exists('start_date_arrived', $contract_acceptance_detail))
                  @php $contact_us_email = getenv('CONTACT_US_EMAIL'); @endphp
                  <h4 class="gilroyregular-bold-font text-align-center font-24">
                      
                          Available to mark as complete from:<br /> {!! date('d M Y',strtotime($contract_detail['job_start_date'])) !!}<br/>
                          </h4>                          
                  <p class="margin-0">You have agreed a contract extension with the Expert, therefore you cannot mark this {{($contract_detail['type']=='service_package')?'package':'project'}} as complete until you have begun the extension, starting on: {!! date('d M Y',strtotime($contract_detail['job_start_date'])) !!}. <br />
                      If you'd like to finish the {{($contract_detail['type']=='service_package')?'package':'project'}} sooner than expected, please get in touch at <a class="gilroyregular-bold-font" href="mailto:{{$contact_us_email}}">{{$contact_us_email}}</a>.
                  </p>
                  
                  
                   <div class="text-align-center">
                        <input id="cancel-finish-service-package" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                    </div>
                  @else
                  <h4 class="gilroyregular-font text-align-center">This contract hasn't started yet. Therefore, you can't mark it as complete before the start date which is {!! date('d M Y',strtotime($contract_detail['job_start_date'])) !!}.</h4>
                  <div class="text-align-center">
                      <input id="cancel-finish-service-package" value="Got it" class="continue-btn green_gradient standard-btn" type="button">
                  </div>
                  @endif
              </div>
        </div>
    </div>
</div>
