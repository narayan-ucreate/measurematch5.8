    <header>
       <nav class="navbar navbar-default">
              <div class="container">
              <div class="row">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>

                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav pull-right">
                     @if(isset(Auth::user()->id))
                    <li><a href="{{url('/logout',[],$ssl)}}" class="tp" title="Contact">Logout</a></li>
                    @else
                     <li><a href="{{url('/signin',[],$ssl)}}" class="tp" title="Contact">Login</a></li>
                    @endif
                  </ul>

                </div><!-- /.navbar-collapse -->
                </div>
              </div><!-- /.container-fluid -->
            </nav>
    </header>
<!-- HEADER ENDS HERE -->
