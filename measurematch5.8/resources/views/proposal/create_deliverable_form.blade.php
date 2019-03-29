<form method="post" id="create_deliverable" action="{{route('manage-deliverable', [$communication_id])}}" autocomplete="false">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
            <input placeholder="Title of deliverable" type="text" class="common-deliverable-form"  id="title" name="title">
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
            <select  name="rate_type" id="rate_type" class="selectpicker select-box-dropdown common-deliverable-form">
                <option value="">Choose the "rate" type</option>
                @foreach(config('constants.RATE_TYPE') as $unit_key => $unit)
                    <option value="{{$unit_key}}">{{$unit}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
            <textarea  name="description" placeholder="Describe your deliverable" class="common-deliverable-form" id="description"></textarea>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
            <div class="currenacy-input-group">
                <div class="input-addon">{{$currency_name}} <span id="curr_symbol">{{$currency}}</span></div>
                <input type="text" id="price" name="price" class="common-deliverable-form price-format-validation" placeholder="Enter your amount here...">
            </div>
            <a href="javascript:void(0)" class="why-amount" data-toggle="popover" data-content="This is the currency the Client has opted to pay in. If you'd like to receive payment in a different currency, MeasureMatch will prompt you later in the process for your currency preference.">Why is the amount in {{$currency_name}} {{$currency}}?</a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group hide quantity-section">
            <select id="quantity" name="quantity" class="selectpicker select-box-dropdown common-deliverable-form">
                <option value="">Choose no. of days</option>
                @for($i=1; $i<50; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="col-lg-12 form-group button-group">
            <input tabindex="11" type="submit" class="info-save-btn standard-btn disable-btn" value="Save deliverable" id="submit_deliverable">
            @if (isset($deliverables['deliverables']) && _count($deliverables['deliverables']))

                <input type="button" class="standard-btn cancel-add-deliverable" value="Cancel">
            @endif
            @if (isset($deliverables['deliverables']) && _count($deliverables['deliverables']) < 1)

                    <p class="deliver_text">You can add a 2nd deliverable after saving your 1st one.</p>
            @endif
        </div>
    </div>

    {{csrf_field()}}
    <input type="hidden" value="" name="update_index" id="update_index">
</form>

