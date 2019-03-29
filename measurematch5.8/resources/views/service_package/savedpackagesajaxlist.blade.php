<div class="v-align-box">
@if(_count($service_packages))
@foreach($service_packages as $service_package)
<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 expert-detail-col servicepackage-panel">
    <div class="search-result-white-bx typewiselist">
        <div class="typewiselist_header">
            <h4>
                {{getTruncatedContent($service_package['name'], 120)}}
            <a href="javascript:void(0)" class="saved-expert saved-icon save-expert-icon unsave_from_saved_listing"
                buyer-id="{{Auth::user()->id}}" service-package-id="{{$service_package['service_package_id']}}" 
                saved_id='{{array_search($service_package["service_package_id"], $saved_packages_list)}}'>
            </a>
            </h4>
        </div>
        <div class="typewiselist_desc">
            <p class="gilroyregular-font">
                {{getTruncatedContent($service_package['description'], config('constants.SERVICE_PACKAGE_DESCRIPTION_LENGTH'))}}
            </p>
            <div class="typewiselist_userinfo">
                <a href="{{url('buyer/expert-profile/'.$service_package['user_details']['id'], [], $ssl)}}">
                <div class="userimg" style="background-image:url({{$service_package['user_details']['user_profile']['profile_picture']}});"></div>
                <div class="expert-package-details">
                    <strong class="gilroyregular-bold-font">{{ucfirst($service_package['user_details']['name'])}} {{ucfirst(substr($service_package['user_details']['last_name'], 0, 1))}}</strong>
                    <span class="gilroyregular-font">{{ucfirst($service_package['user_details']['user_profile']['describe'])}}</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="typewiselist_footer">
            <div class="total_budget">
                Guide Budget: <span class="gilroyregular-bold-font">${{number_format($service_package['price'])}}@if($service_package['subscription_type']=='monthly_retainer')/month @endif</span>
            </div>
            <a href="{{url('servicepackage/'.$service_package['service_package_id'],[],$ssl)}}" class="gilroyregular-bold-font">View Package</a>
        </div>
    </div>            
</div>
@endforeach
@endif</div>
