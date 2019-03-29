@extends('layouts.layout')
@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 expert-find-project">
        <div class="find-match-content margin-b-32">
            <div class="find-match-title-section">
                <h2>Create Proposal</h2>
            </div>
            <div class="post-job-form-section find-match-form bottom-margin-0 no-border">
                <div class="clearfix"></div>
            </div>
            <div>
                <table class="table-responsive table">
                    <thead>
                    <th>Deliverable</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>VAT</th>
                    <th>Amount</th>
                    </thead>
                    <tbody>
                        @forelse($deliverables as $deliverable)
                            <td>{{$deliverable->title}}<br>
                                {{$deliverable->description}}
                            </td>
                            <td>{{config('constants.RATE_TYPE.'.$deliverable->rate_type)}}</td>
                            <td>{{$deliverable->quantity}}</td>
                            <td>{{$deliverable->price}}</td>
                            <td>{{$deliverable->vat}}</td>
                            <td>{{$deliverable->total_amount}}</td>
                            @empty
                            @include('proposal.create_deliverable_form')
                        @endforelse
                    </tbody>
                </table>
                @if(_count($deliverables))

                    @include('proposal.create_deliverable_form')
                   @endif

                <button class="btn btn-success"> Add Deliverable</button>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    <script src="{{ url('js/proposal.js?js='.$random_number,[],$ssl) }}"></script>
@endsection