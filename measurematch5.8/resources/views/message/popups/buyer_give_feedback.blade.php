
@if(!empty($contract_detail) && !empty($project_detail))
 <link href="{{ url('css/jquery.rateyo.min.css?css='.$random_number,[],$ssl) }}" rel='stylesheet' type='text/css'>
 <div id="Buyer-give-feedback-{{ $contract_detail['id'] }}" class="modal fade got-match-popup seller-contract-popup buyer-contract-popup invite-seller-popup"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div  class="modal-dialog modal-lg final-accepted-offer feedback-popup lightbox-design lightbox-design-small">
         <div class="modal-innner-content">
             <div class="modal-content">
                 <div class="modal-header">
                     <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><img alt="cross" src="{{ url('images/cross-black.svg',[],$ssl) }}"></span></button>
                 </div>
                 @if($type == 'project')
                 <div class="modal-body">
                     <h3>Give Feedback</h3>
                     <h4 class="text-center">Honesty is the best policy</h4>
                     <form name="buyer_feedback" id="buyer_feedback" method="post" enctype="multipart/form-data" >
                         {{ csrf_field() }}
                         <div class="send_contract_form">
                             <label>Project</label>
                             <div class="input-bx deliverable_bx">
                                 <input type="text"  name="job_name" value="{{ $project_detail['job_title'] }}"  disabled>
                                 <div class="validation_error3 validation-alert-message"></div>
                             </div>
                             <label>Star rating</label>
                             <div id="rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx"></div>
                             <div class="validation_error7 validation-alert-message"></div>

                             <div class="input-bx deliverable_bx">
                                 <label>Explain why you've given this star rating</label>
                                 <textarea class="deliverable_offer" id="feedback_comment" name="feedback_comment" maxlength="255" placeholder="Start typing here" >{{ old('feedback_comment') }}</textarea>
                                 <div class="validation_error8 validation-alert-message"></div>

                             </div>
                             <div class="input-bx send_contract_btn_section">

                                 <input type="submit" class="send-contract-btn standard-btn" value="Give feedback"/>
                                 <input type="hidden" id="sender_id" name="sender_id" value="{{Auth::user()->id}}" />

                                 <input type="hidden" id="receiver_id" name="receiver_id" value="" />
                                 <input type="hidden" id="communication_id" name="communication_id" value="">
                             </div>
                         </div>
                     </form>
                 </div>
                 @else
                 @if($contract_detail['buyer_feedback_status']==config('constants.COMPLETED'))
                 <div class="modal-body">
                     <h3>The Package has finished</h3>
                     <h4 class="text-align-center">Thank you for working with a MeasureMatch Expert!</h4>
                     <div class="send_contract_form">
                         <label>Your rating</label>
                         <div id="show_rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx"></div>
                         <input type='hidden' value='{{$contract_detail['expert_rating']}}' id="rating"/>
                     </div>
                     <div class="deliverable_bx">
                            <label>How did {{ucfirst(getUserDetail($contract_detail['user_id'])['name'])}} do?</label>
                            <p>{{ucfirst($contract_detail['feedback_comment'])}}</p>
                            <div class="validation_error8 validation-alert-message"></div>
                     </div>
                 </div>
                 @else
                 <div class="modal-body">
                     <h3>Please leave {{ucfirst(getUserDetail($contract_detail['user_id'])['name'])}} a review</h3>
                     <h4 class="text-align-center">In order to finish the Package, please leave {{ucfirst(getUserDetail($contract_detail['user_id'])['name'])}} a review</h4>
                     <form name="buyer_feedback" id="buyer_feedback" method="post" enctype="multipart/form-data" >
                         {{ csrf_field() }}
                         <div class="send_contract_form leave-feedback-popup">
                             <label>Give a star rating</label>
                             <div id="rating" name="expert_rating" class="rateyo-readonly-widg input-bx deliverable_bx"></div>
                             <div class="validation_error7 validation-alert-message"></div>

                             <div class="deliverable_bx">
                                 <label>How did {{ucfirst(getUserDetail($contract_detail['user_id'])['name'])}} do?</label>
                                 <textarea class="deliverable_offer" id="feedback_comment" name="feedback_comment" maxlength="255" placeholder="Start typing here..." >{{ old('feedback_comment') }}</textarea>
                                 <p>{{ucfirst($contract_detail['feedback_comment'])}}</p>
                                 <div class="validation_error8 validation-alert-message"></div>
                             </div>

                             <div class="text-align-center col-lg-12">
                                 <input type="submit" class="standard-btn" value="Leave Review"/>
                                 <input type="hidden" id="sender_id" name="sender_id" value="{{Auth::user()->id}}" />
                                 <input type="hidden" id="receiver_id" name="receiver_id" value="" />
                                 <input type="hidden" id="communication_id" name="communication_id" value="">
                             </div>
                         </div>
                     </form>
                 </div>
                 @endif
                 @endif
             </div>
         </div>
     </div>
 </div> 
@endif
<script type="text/javascript" type="text/javascript" src="{{ url('js/jquery.rateyo.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        if($('#show_rating').length){
            var rating_by_buyer = $('#rating').val();
            $("#show_rating").rateYo({
                rating: rating_by_buyer,
                numStars: 5,
                precision: 2,
                readOnly: true,
                ratedFill: "#1e70b7",
                starWidth: "15px"
            });
        } else {
            autosize(document.querySelectorAll('textarea.deliverable_offer'));
            var rating = 0;
            $('#rating').val(rating);
            $(".rateyo-readonly-widg").rateYo({
                rating: rating,
                numStars: 5,
                precision: 2,
                minValue: 1,
                maxValue: 5,
                ratedFill: "#1e70b7",
            }).on("rateyo.change", function (e, data) {
                var buyer_rating = data.rating;
                $('#rating').val(buyer_rating);
            });
        }
    });
</script>
