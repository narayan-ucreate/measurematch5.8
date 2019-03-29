@extends('layouts.layout')
@section('content')

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 create-package-panel my-service-package-panel expert-my-service-package">
        <div class="white-box">
            <div class="white-box-header vendor-list-header">
                <h4>Vendor</h4>
            </div>
            <div class="white-box-content vendor-white-container margin-0">
                @forelse($service_hubs as $service_hub)
                    <a href="{{route('vendor-service-hubs-details', [$service_hub->id])}}">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="vendor-list-container">
                            <div class="verndor-logo-container">
                                <span class="vendor-listing-logo" style="background-image:url({{ url($service_hub->logo,[],$ssl)}});"></span>                                
                            </div>
                            @php
                                $truncated_content = getTruncatedContent($service_hub->name, config('constants.SERVICE_NAME_TRUNCATE_LIMIT'));
                            @endphp
                            <span class="vendor-hub-name vendor-total-count-container">{{$truncated_content}}
                                <span class="gilroyregular-font font-12 vendor-verified-experts-count">15+ Verified Experts</span>
                            </span>
                        </div>
                    </div>
                    </a>
                @empty
                    <div class="vendor-hub-empty-state col-lg-6 col-md-6 col-sm-7 col-xs-12">
                        <img alt="cross" src="{{ url('images/package-is-under-review.svg',[],$ssl) }}">
                        <h4>Service Hubs are coming soon!</h4>
                        <p>MeasureMatch Experts will soon be able to apply to technology vendor’s "Service Hub".</p>
                    </div>
               @endforelse
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    @include('include.footer')
    <script src="{{url('js/sellersearch.js?js='.$random_number,[],$ssl)}}"></script>
@endsection