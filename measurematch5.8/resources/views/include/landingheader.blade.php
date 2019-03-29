<?php
$user = Auth::user();
$current_method = last(explode('/', url()->current()));
?>
<nav class="navbar navbar-default @if($current_method!='postproject') navbar-fixed-top @endif topnav header-menu">
    <div class="container">
        <a href="{{homeUrlWebflow()}}" class="navbar-brand" title="MeasureMatch"><img class="img-responsive" src="{{ url('images/logo.svg',[],$ssl) }}" alt="MeasureMatch"></a><?php echo getUserType(); ?>
      <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            @if(empty($user) && $current_method != 'postproject')
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span> 
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
            @endif
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        @if(empty($user) && $current_method != 'postproject')
            <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav pull-left">
                 <li id="our_expert_link"><a  href="{{ url('our-experts',[],$ssl) }}" title="Our Experts">Our Experts</a></li>
                 <li id="aboutus_link"><a href="{{ url('about-us',[],$ssl) }}" title="About us">About us</a></li>
                 <li id="blog_link"><a target="_blank" href="http://blog.measurematch.com" title="Blog">Blog</a></li>
                 <li id="faq_link"><a href="{{ url('faq',[],$ssl) }}" title="FAQs">FAQs</a></li>
                 <li id="contactus_link"><a href="{{ url('contact-us',[],$ssl) }}" title="Contact Us">Contact us</a></li>
              </ul>

              <ul class="nav navbar-nav pull-right header-login-btn">
                 <li>
                    <a href="{{url('login',[],$ssl)}}" title="Login">Login</a>
                 </li>
                 <li class="post-project-li"><a href="{{url('homepage/postproject',[],$ssl)}}" title="Post a Project/Task">Post a Project/Task</a></li>
              </ul>
            </div>
        @endif
    </div>
    <!-- /.container-fluid -->
  </nav>
