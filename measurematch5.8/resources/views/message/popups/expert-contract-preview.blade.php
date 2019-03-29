@if(!empty($contract_detail))
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="gotmatchpopup-{{ $contract_detail['id'] }}" class="review-proposal modal lightbox-design fade in @if(!_count($contract_detail['deliverables'])) view-old-contract-popup new-theme-modal @endif" style="display: none;">
    @php $view_pop_pop = 'view_proposal_pop_up'; @endphp
    @if(!_count($contract_detail['deliverables']))
        @php $view_pop_pop = 'view_contract_pop_up'; @endphp
    @endif
@include("message.popups.$view_pop_pop")
</div>

@endif
