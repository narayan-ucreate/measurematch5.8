@if(Auth::check()) <script src="{{url('js/socketio.js',[],$ssl)}}"></script>
@if(Auth::user()->user_type_id==2)
<div class="modal fade alert-message-popup welcome-popup-design lightbox-design lightbox-design-small" id="waiting_approval" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <span class="no-post-found text-align-center"> Your first project is still in queue for review by a MeasureMatch team member. You can start posting more Projects to the platform after the approval of your first project. Many thanks for your patience. </span>

            </div>
        </div>
    </div>
</div> 
<script src="{{ url('js/buyer-dashboard-tabs.js?js='.$random_number,[],$ssl) }}" type="text/javascript"></script>
@endif
@endif
<script type="text/javascript">
var logged_in = 0;
var environment = "{{getenv('APP_ENV')}}";
<?php if(Auth::check() && Auth::user()->user_type_id != config('constants.ADMIN')){ ?>
var logged_in = 1;
var email = "{{Auth::user()->email}}";
var name = "{{Auth::user()->name.' '.Auth::user()->last_name}}";
var user_id = "{{Auth::user()->id}}";
var user_type = "{{Auth::user()->user_type_id}}";
var user_state = "{{Auth::user()->admin_approval_status}}";
var first_name = "{{Auth::user()->name}}";
var last_name = "{{Auth::user()->last_name}}";
var socket_sender_id = "{{Auth::user()->id}}";
var socket = io.connect('@php echo config('constants.MM_SOCKET_APP'); @endphp', {query: "id=" + socket_sender_id});

if(user_type == "@php echo config('constants.BUYER'); @endphp"){ 
    var projectCreatedCount = {{ buyerProjectCreatedCount(Auth::user()->id) }};
    var projectApprovedCount = {{ buyerProjectApprovedCount(Auth::user()->id)}};
}

<?php } ?>
 var base_url = "{{ url('/',[],$ssl) }}";
 
 
 
 </script>
<script src="{{url('js/mm_global.js?js='.$random_number,[],$ssl)}}"></script>