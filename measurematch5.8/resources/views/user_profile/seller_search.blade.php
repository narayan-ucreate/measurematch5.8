@extends('layouts.layout')
@section('content')
    @if(Session::has('success'))
        <section class="successfully-posted fade_error_message">
            <div class="container">
        <span class="message-content">
            {{Session::get('success')}}
        </span>
                <span id="post-close-btn" class="popup-close-btn">x</span>
            </div>
        </section>
    @endif
    @php
        $page = $_SERVER["REQUEST_URI"];
        if (isset($page)) {
            $_SESSION['page'] = $page;
        }
    @endphp

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 expert-find-project">
        <div class="find-match-content margin-b-32">
            <div class="find-match-title-section">
                <h2>Browse Projects</h2>
            </div>
            <div class="post-job-form-section find-match-form bottom-margin-0 no-border">
                <div class="clearfix"></div>
            </div>
            <div class="find-matches-grid">
                <div class="ajax-loading">
                        <div class="match-list-section no-completed-jobs" id="result_container">
                            @include('include.browse_project_common')
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('include.basic_javascript_liberaries')
    @include('include.footer')
    <script src="{{url('js/sellersearch.js?js='.$random_number,[],$ssl)}}"></script>
@endsection