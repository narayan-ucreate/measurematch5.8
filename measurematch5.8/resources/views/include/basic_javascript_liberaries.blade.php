<script type="text/javascript" src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/jquery-ui.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
<script type="text/javascript" src="{{ url('js/html5shiv.min.js?js='.$random_number,[],$ssl) }}"></script>
@if(Auth::check())<script type="text/javascript" src="{{url('js/side-menu.js',[],$ssl)}}"></script>@endif