@php
    $term_count = 0;
@endphp
<form method="post" id="create_term" action="{{route('store-terms', [$communication_id])}}" autocomplete="false">
    <div class="input-bx select-box">
        <div class="row">
            <div class="col-lg-12">
                <label>Your Terms <span class="optional">(Optional)</span>
                    <p>Here you can optionally add unique terms of your own.</p>
                </label>
                
                @if (isset($deliverables['terms']) && _count($deliverables['terms']))
                    @php
                    $term_count = _count($deliverables['terms']);
                    @endphp
                    @foreach($deliverables['terms'] as $key => $term)
                    <div class="terms-delete-block created-terms">
                        <p>{!! nl2br($term['term']) !!}</p>
                        <a href="javascript:void(0)" class="white-button edit-proposal-fields edit-term" 
                           data-id='{{$key}}' data-description='{{$term['term']}}'><i class="fa fa-pencil"></i> Edit term</a>
                        <a href="javascript:void(0)" class="white-button delete-proposal-field delete-term" data-id='{{$key}}'><i class="fa fa-trash"></i> Delete term</a>
                    </div>
                    @endforeach
                @endif
                <textarea placeholder="Start typing a term here..." type="text" id="term" name="term" class="expert-terms
                          @if (isset($deliverables['terms']) && _count($deliverables['terms'])) hide @endif"></textarea>
                <input type="hidden" name="update_index" id="term_index"/>
                <input type="hidden" id="manage-term-url" value="{{route('store-terms', [$communication_id])}}">
            </div>
        </div>
    </div>
    <div class="input-bx terms-input-block">
        <input type="submit" class="info-save-btn disable-btn expert-term-button @if (isset($deliverables['terms']) && _count($deliverables['terms'])) hide @endif" value="Save term" id="save_term">
        <input type="button" class="info-save-btn expert-term-button standard-btn @if (!isset($deliverables['terms']) || !_count($deliverables['terms'])) hide @endif" value="Add another term" id="add_another_term"/>
        <p class="deliver_text @if($term_count) hide @endif">You can add a 2nd term after saving your 1st one.</p>
    </div>
</form>