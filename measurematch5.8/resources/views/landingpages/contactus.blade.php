<!DOCTYPE html>
<html lang="en">
     @include('include.landing_head_script')
    <body class="white-bg fixednavbody">
    @include('include.googleTagsScriptsBody')
        @include('include.landingheader')
        <header class="landingheader contactus_header">
            <div class="home-bx">
                <div class="home-section">
                    <div class="home-panel">
                        <div class="container">
                            <img src="images/header_logo.svg" class="header_logo">
                            <h2>Contact us</h2>
                            <h3>We are here to answer any questions you may have. Reach out to us and we’ll respond as soon as we can.</h3>

                        </div>
                    </div>
                </div>
            </div>
            <div class="learnmore"><a id="contactusbtn" href="javascript:void()">Contact us <img src="images/down-arrow.svg" alt="down-arrow" class="down-arrow" /> </a></div>
        </header>


        <section class="contactdetail" id="contactuspanel">
            <div class="container">
                <div class="col-sm-4">
                    <span class="conatct_icon"><img src="images/ic_email.svg"></span>
                    <h3>Get in Touch</h3>
                    <p>
                        <a href="mailto:%63%6f%6e%74%61%63%74%40%6d%65%61%73%75%72%65%6d%61%74%63%68%2e%63%6f%6d">contact@measurematch.com</a>
                    </p>
                </div>
                <div class="col-sm-4">
                    <span class="conatct_icon"><img src="images/ic_place copy.svg"></span>
                    <h3>Our Location</h3>
                    <p>
                        Studio 7, 270 Kingsland Road,<br/>London, E9 4DG
                    </p>
                </div>
                <div class="col-sm-4">
                    <span class="conatct_icon"><img src="images/ic_forum.svg"></span>
                    <h3>Just a quick question?</h3>
                    <p>
                        <a href="{{ url('faq',[],$ssl) }}" title="FAQ">Check out our FAQ</a>
                    </p>
                </div>
            </div>
        </section>
        <section id="contact_us_sec" class="getintouch">
            <div class="container">
                <div class="col-sm-6 map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2481.6604741842375!2d-0.0785047838598863!3d51.53778697964023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48761c95d88fa243%3A0xbba12bf1dc22ea6e!2sMeasureMatch+HQ!5e0!3m2!1sen!2sin!4v1482389478828" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>

                <form id="contact_us" class="col-sm-6 col-md-6 col-xs-12" method="post" action="{{ url('contactEmail',[],$ssl) }}">
                    @if(session()->has('response'))
                    <div class="error">
                        {!! session()->get('response') !!}
                    </div>
                    @endif
                     @if(session()->has('success'))
                    <div class="success">
                        {!! session()->get('success') !!}
                    </div>
                    @endif
                    {{ csrf_field() }}
                    <div class="contactform">
                        <label>Full name</label>
                        <input class="form-control" type="text" id="contctname" name="name" placeholder="Type your name here" />
                        <div class="validation_name_error error-message"></div>
                        @if($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                        <label>Email</label>
                        <input class="form-control" type="email" id="contctemail" name="email" placeholder="Type your email here" />
                        <div class="validation_email_error error-message"></div>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                        <label>Message</label>
                        <textarea class="form-control" name="msg" id="contctmsg" placeholder="Type your message here"></textarea>
                        <div class="validation_msg_error error-message"></div>
                        <input type="submit" class="btn btn-primary contact-us standard-btn" value="Submit" />
                    </div>
                </form>

        </section>
        @include('include.footer')
         <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
         <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
         <script src="{{ url('js/contact_us.js?js='.$random_number,[],$ssl) }}"></script>
        @include('include.global_layout_parent')
</body>
</html>
