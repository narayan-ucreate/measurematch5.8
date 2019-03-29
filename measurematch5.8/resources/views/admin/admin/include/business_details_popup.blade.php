<div class="modal lightbox-design" id="view_business_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true"><img alt="cross" src="https://measurematch.herokuapp.com/images/cross-black.svg"></span>
                </button>
                <div class="modal-body">
                    <h2 class="expert-detail">{{ $user_information['name'] ?? 'Expert'  }}'s Business Details</h2>
                    <h2 class="buyer-detail">{{ $buyer_detail[0]->company_name }}'s Business Details</h2>
                    <input type="hidden" name="expert_type" value="{{ $expert_business_type }}">
                    <div class="box-style-body">
                        <div class="row expert-detail">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Business Type:</p>
                            </div>
                            <div class="col-lg-7">
                                <div> {{ $expert_business_type==config('constants.REGISTERD_COMPANY') ? 'Registered Company' : 'Sole Trader' }} </div>
                            </div>
                        </div>
                        <div class="row hide-sole-trader">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Registered Name: </p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_details->company_name ??  '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_details->company_name ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Country Registered In:</p>
                            </div>
                            <div class="col-lg-7">
                                @if($expert_business_type==config('constants.SOLE_TRADER'))
                                <div class="expert-detail"> {{ $expert_business_address->business_registered_country ?? '-' }} </div>
                                @endif
                                @if($expert_business_type==config('constants.REGISTERD_COMPANY'))
                                <div class="expert-detail"> {{ $expert_business_details->company_country ?? '-' }} </div>
                                @endif
                                <div class="buyer-detail"> {{ $buyer_business_details->company_country ?? '-' }} </div>
                            </div>
                        </div>
                        <div class="row hide-sole-trader">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Website Address: </p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_details->company_website ??  '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_details->company_website ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row hide-sole-trader">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Company Role: </p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_details->role ??  '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_details->role ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row hide-sole-trader">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">VAT Registered:</p>
                            </div>
                            <div class="col-lg-7">
                                @if($expert_business_type==config('constants.REGISTERD_COMPANY') && isset($expert_business_details->vat_status))
                                <div class="expert-detail">{{$expert_business_details->vat_status ? 'Yes' : 'No'}}</div>
                                @endif
                                @if(isset($buyer_business_details->vat_status))
                                <div class="buyer-detail">{{$buyer_business_details->vat_status ? 'Yes' : 'No'}}</div>
                                @endif
                            </div>
                        </div>
                        @if($expert_business_type==config('constants.REGISTERD_COMPANY'))
                        <div class="row expert-detail">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">VAT Number:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">
                                    {{$expert_business_details->vat_number ? $expert_business_details->vat_country . $expert_business_details->vat_number : '-'}}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Address Line 1:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->first_address ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->first_address ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Address Line 2:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->second_address ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->second_address ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">City / Town:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->city ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->city ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">State / Region / County:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->state ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->state ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Postal / ZIP Code:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->postal_code ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->postal_code ?? '-'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="gilroyregular-semibold">Country:</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="expert-detail">{{$expert_business_address->country ?? '-'}}</div>
                                <div class="buyer-detail">{{$buyer_business_address->country ?? '-'}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>