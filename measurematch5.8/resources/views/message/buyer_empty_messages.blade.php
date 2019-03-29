@extends('layouts.userscommonlayout')
@section('content')
<div id="wrapper" class="active @if($user_type == config('constants.BUYER')) buyerdesktop_buyer @endif">
    <script src="{{url('js/side-menu.js?js='.$random_number,[],$ssl)}}"></script>
     @include('message.start_conversation')
</div>
@include('include.footer')
<script src="{{url('js/socketio.js',[],$ssl)}}"></script>
<script type="text/javascript">
var index = true;
var sender_id = "{{Auth::user()->id}}";
var current_user_type = "{{Auth::user()->user_type_id}}";
var sender_name = "@php echo ucfirst(Auth::user()->name) . ' ' . ucfirst(substr(Auth::user()->last_name, 0, 1)); @endphp"
    var socket = io.connect('@php echo config('constants.MM_SOCKET_APP'); @endphp', {query: "id=" + sender_id
    }
    );
</script>
<script src="{{ url('js/buyer_empty_messages.js?js='.$random_number,[],$ssl) }}"></script>
<script src="{{url('js/jquery.validate.min.js',[],$ssl)}}"></script>
@endsection

