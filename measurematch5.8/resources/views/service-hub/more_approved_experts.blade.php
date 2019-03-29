<div class="v-align-box ver-expert-row">
    @php $count = 0; @endphp
    @foreach($experts_listing as $expert_listing)
    @if($count != 0 && $count % 3 == 0)<div class="v-align-box">@endif
        @include('service-hub.approved_expert_block', ['approved_expert' => $expert_listing])
        @php $count++; @endphp
        @if($count > 2 && $count % 3 == 0)</div>@endif                            
    @endforeach
</div>