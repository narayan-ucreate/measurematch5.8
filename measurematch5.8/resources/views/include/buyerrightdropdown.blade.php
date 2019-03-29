<div class="navbar-header">
    @php
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $word = substr($actual_link, strrpos($actual_link, '/') + 1);
    @endphp
    <ul class="nav navbar-nav navbar-right hide-small-screen">
        <li class="username_li @if($word=='profile-summary' || $word=='account' || $word=='dashboard' || $word=='myprojects') <?php echo "active"; ?> @endif">
            <span class="dropdown">
                <button class="dropdown-toggle" type="button" id="dropdownMenuDivider" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <a href="{{ url('buyer/profile-summary',[],$ssl) }}">{{ucwords(Auth::user()->name .' '.Auth::user()->last_name)}}</a>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuDivider">
                    @if(Auth::user()->admin_approval_status == config('constants.APPROVED'))
                    <li class="profile-icon @if($word=='profile-summary') <?php echo 'active'; ?>@endif">
                        <a href="{{url('buyer/profile-summary',[],$ssl)}}" title="Profile">
                            Profile
                        </a>
                    </li>
                    @endif
                    <li @if($word=='settings') class=<?php echo 'active'; ?>@endif>
                         <a href="{{ url('buyer/settings',[],$ssl) }}">
                            Settings
                        </a>
                    </li>
                    @if(isset(Auth::user()->id))
                    <li>
                        <a id="signout" title="Sign out" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{url('/logout',[],$ssl)}}">Sign out</a>
                    </li>
                      <form id="logout-form" action="{{ url('/logout',[],$ssl) }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                    @endif
                </ul> </span>
        </li>
    </ul>
</div>