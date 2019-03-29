@forelse ($projects['data'] as $jobs)
    <a href="{{ url('/project_view?sellerid='.$jobs['id'])}}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 match-list-view">
            <div class="match-result-title buyer-job-list-post">
                <h6>{{ $jobs['job_title'] }}</h6>
                <p class="job-price-lbl expert-price">
                    <span class="job-rate"> 
                        @if(!empty($jobs['rate']) && $jobs['rate'] != 0) 
                         Rate: {{ convertToCurrencySymbol($jobs['currency']).((is_numeric($jobs['rate'])) ? number_format($jobs['rate']) : $jobs['rate']) }}
                                @if($jobs['rate_variable']=='daily_rate'){{'/day'}}
                                @endif 
                            @else
                          {{'Negotiable'}}
                          @endif
                    </span>
                </p>
                @if(isset($jobs['job_end_date']) && !empty($jobs['job_end_date']))
                    <span class="search-list-expire-date">
                                                  <img src="../images/ic_event_note.svg" alt="ic_event_note" class="ic_event_note" />
                        <span class="expire-date-lbl">
                            {{projectExpiryDateStatus($jobs['job_end_date'])}}
                                                    </span></span>
                @endif
                <p>
                    @if(
                    !empty($jobs['buyer_profile']['company_name']) &&
                    ( $jobs['hide_company_name'] != config('constants.TRUE'))
                    )
                        <span class="search_res_company_name">{{ucfirst($jobs['buyer_profile']['company_name'])}} </span>
                    @else
                        @if ($jobs['buyer_profile']['type_of_organisation'])
                            <span class="search_res_company_name">{{ ucfirst($jobs['buyer_profile']['type_of_organisation']['name']) }} Company</span>
                        @endif
                    @endif
                    @php
                        $office_location = '';
                        if(!empty($jobs['office_location'])){
                            $office_location = $jobs['office_location'];
                        }elseif(!empty($jobs['buyer_profile']['office_location'])){
                            $office_location = rtrim(trim(str_replace('<br/>', ', ', $jobs['buyer_profile']['office_location'])), ',');
                        }
                    @endphp
                    @if(!empty($office_location))
                        <span class="posted-office-location">{{$office_location}}</span>
                    @endif
                    <span class="job-view-feedback">{{$jobs['job_viewer_count']}} views</span>

                    @php $expert_job_interest = $jobs['count_project_eoi_count']; @endphp
                    @if($expert_job_interest)
                        <span class="job-view-intrest-shown">{{$expert_job_interest}} expressed interest</span>
                    @endif
                </p>
                @if(!empty($jobs['publish_date']))
                    <span class="posted-date-post">Posted {{ timeElapsedString($jobs['publish_date']) }}</span>
                @endif
                <div class="match-result-description">
                    @if(isset($jobs['description']) && !empty($jobs['description']))
                        <p>{{ getTruncatedContent(strip_tags($jobs['description']), 230) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </a>
@empty
    <h6 class="noresult">There are no projects on the platform that match your profile just yet. To maximize your project opportunities, please return to your profile and populate it with all of the possible details describing your marketing tech, marketing data, analytics, and/or research skills and experience. Please also include any coding skills you have and vendors' systems, too e.g. Salesforce, Lotame, Marketo, Google Analytics, Tealium, Adobe Analytics, etc.</h6>
@endforelse
@if ($projects['current_page'] < $projects['last_page'])
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 view-more-matches">
        <input type="button" value="View more" class="green-border-btn view_more_btn standard-btn" data-next-page-url="{{$projects['next_page_url']}}" id="view_more" />
    </div>
@endif
