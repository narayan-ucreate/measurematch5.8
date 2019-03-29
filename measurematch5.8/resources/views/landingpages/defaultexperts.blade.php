<!DOCTYPE html>
<html>
    @include('include.landing_head_script')
    <link rel="stylesheet" href="{{ url('css/bootstrap-select.css?css='.$random_number,[],$ssl)}}">
        <link href="{{ url('css/bootstrap-datetimepicker.css?css='.$random_number,[],$ssl)}}" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ url('css/jquery-ui.css?css='.$random_number,[],$ssl) }}">
        <link rel="stylesheet" href="{{ url('css/jasny-bootstrap.min.css?css='.$random_number,[],$ssl) }}">
    <body class="white-bg fixednavbody">
        @include('include.googleTagsScriptsBody')
        @include('include.landingheader')
       
        <?php
        header('Content-type:text/css');

        function isMobile() {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $response = (bool) preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet' .
                        '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]' .
                        '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);
                if ($response == true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        $serve_res = isMobile();
        if ($serve_res == true) {
            $bg = array('home-slide1.jpg', 'home-slide2.jpg'); // array of filenames
        } else {
            $bg = array('home-slide1.jpg', 'home-slide2.jpg', 'home-slide3.jpg'); // array of filenames
        }

        $i = rand(0, _count($bg) - 1); // generate random number size of the array
        $bannerImage = "$bg[$i]"; // set variable equal to which random filename was chosen
        ?>
        <section class="dark-blue-bg expertpage-title">
            <div class="container">
            <div class="row">
                <div class="col-lg-7 col-lg-offset-3 text-center">
                    <h1>Add pre-screened and tremendously valuable talent to your team. Fast.</h1>
                    
                </div>
            </div>
            </div>
        </section>
        <section class="findexpert-panel">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                        
                         <a href="javascript:void()"><span style="background-image:url({{url('images/marissa.jpg',[],$ssl)}})"></span></a>
                            </div>
                            <div class="expert_detail">
                                <h2>Marissa G</h2>
                                <h3>Springfield, VA, Unites States</h3>
                                <div class="expert-exprtise">
                                    <a href="javascript:void()" title="Optimizely">Optimizely</a>
                                    <a href="javascript:void()" title="Google Analytics">Google Analytics</a>
                                    <a href="javascript:void()" title="+13">+13</a>
                                </div>
                                <p>I am a Digital Analytics Consultant based in the Washington, D.C. Area. I work with an array of clients, but...</p>
                                <a class="hire-expert standard-version-btn" href="{{url('signup',[],$ssl)}}">Hire Marissa</a>
                                <span class="measure-match-member">Member Since: 
April 2017</span>
                            </div>
                       </div> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                        <a href="javascript:void()"><span style="background-image:url({{url('images/adam.jpg',[],$ssl)}})"></span></a>
                            </div>
                            <div class="expert_detail">
                                <h2>Adam G</h2>
                                <h3>Deerfield, MA, United States</h3>
                                <div class="expert-exprtise">
                                    <a href="javascript:void()" title="Web analytics">Web analytics</a>
                                    <a href="javascript:void()" title="Business Requirements">Business Requirements</a>
                                 
                                </div>
                                <p>Adam is a longstanding member of the web analytics community who has consulted...</p>
                                <a class="hire-expert standard-version-btn"  href="{{url('signup',[],$ssl)}}">Hire Adam</a>
                                <span class="measure-match-member">Member Since: June 2017</span>
                            </div>
                       </div> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                                <a href="javascript:void()"><span style="background-image:url({{url('images/zac.jpg',[],$ssl)}})"></span></a>
                            </div>
                            <div class="expert_detail">
                                <h2>Zachary V</h2>
                                <h3>San Francisco, United States</h3>
                                <div class="expert-exprtise">
                                    <a href="javascript:void()" title="Systems Architecture">Systems Architecture</a>
                                    <a href="javascript:void()" title="Systems Integration">Systems Integration</a>
<!--                                    <a href="javascript:void()" title="+24">+24</a>-->
                                </div>
                                <p>Helping Fortune 500 companies and start-ups develop and execute successful, data-driven online marketing...</p>
                                <a class="hire-expert standard-version-btn"  href="{{url('signup',[],$ssl)}}">Hire Zachary</a>
                                <span class="measure-match-member">Member Since: Feb 2017</span>
                            </div>
                       </div> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                                <a href="javascript:void()"><span style="background-image:url({{url('images/kristna.jpg',[],$ssl)}})"></span></a>                                
                            </div>
                            <div class="expert_detail">
                                <h2>Kristina P</h2>
                                <h3>Auckland, New Zealand</h3>
                                <div class="expert-exprtise">
                                    <a title="Google Analytics" href="javascript:void()">Google Analytics</a>
                                    <a href="javascript:void()" title="Ensighten">Ensighten</a>
                                    <a href="javascript:void()" title="+9">+9</a>
                                </div>
                                <p>Kristina works with the latest analytics techniques that enable and empower executives to interpret and...</p>
                                <a class="hire-expert standard-version-btn"  href="{{url('signup',[],$ssl)}}">Hire Kristina</a>
                                <span class="measure-match-member">Member Since: 
February 2017</span>
                            </div>
                       </div> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                                <a href="javascript:void()"><span style="background-image:url({{url('images/ben-s.jpg',[],$ssl)}})"></span></a>
                        
                            </div>
                            <div class="expert_detail">
                                <h2>Ben S</h2>
                                <h3>London, UK</h3>
                                <div class="expert-exprtise">
                                    <a href="javascript:void()" title="Data management platforms">Data management platforms</a>
                                    
                                    <a href="javascript:void()" title="+16">+16</a>
                                </div>
                                <p>I am a technology led digital marketer with exceptional technical and people skills. Specialising specifically in...</p>
                                <a class="hire-expert standard-version-btn"  href="{{url('signup',[],$ssl)}}">Hire Ben</a>
                                <span class="measure-match-member">Member Since: May 2017</span>
                            </div>
                       </div> 
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                       <div class="expertblock">
                            <div class="findexpert-header"></div>
                            <div class="expert-image">
                                <a href="javascript:void()"><span style="background-image:url({{url('images/Emily.jpg',[],$ssl)}})"></span></a>
                        
                            </div>
                            <div class="expert_detail">
                                <h2>Emily P</h2>
                                <h3>Charlottesville, Unites States</h3>
                                <div class="expert-exprtise">
                                    <a href="javascript:void()" title="Google Analytics">Google Analytics</a>
                                    <a href="javascript:void()" title="Tag Manager">Tag Manager</a>                                    
                                    <a href="javascript:void()" title="+2">+2</a>
                                </div>
                                <p>I am a Digital Analytics Consultant based in the Washington, D.C. Area. I work with an array of clients, but... </p>
                                <a class="hire-expert standard-version-btn"  href="{{url('signup',[],$ssl)}}">Hire Emily</a>
                                <span class="measure-match-member">Member Since: March 2017</span>
                            </div>
                       </div> 
                    </div>
                </div>
            </div>
        </section>
 
        <section class="compnay-logo">
            <div class="container">
                <h3>MeasureMatch experts are trusted by brands like these</h3>
                <img class="newBalance" alt="newBalance" src="{{url('images/newBalance.png',[],$ssl)}}" />
                <img class="samsung" alt="samsung" src="{{url('images/samsung.png',[],$ssl)}}" />
                <img class="nike" alt="nike" src="{{url('images/nike.png',[],$ssl)}}" />
                <img class="bmw" alt="bmw" src="{{url('images/bmw.png',[],$ssl)}}" />
                <img class="barclays" alt="barclays" src="{{url('images/barclays.png',[],$ssl)}}" />
                
                <img class="nivea" alt="nivea" src="{{url('images/nivea.png',[],$ssl)}}" />
                <img class="toyota" alt="toyota" src="{{url('images/toyota.png',[],$ssl)}}" />
                <img class="tommyHilfiger" alt="tommyHilfiger" src="{{url('images/tommyHilfiger.png',[],$ssl)}}" />
                <img class="dyson" alt="dyson" src="{{url('images/dyson.png',[],$ssl)}}" />
                <img class="amex" alt="amex" src="{{url('images/amex.png',[],$ssl)}}" />
            </div>
        </section>
        <section class="dark-blue-bg weready-expert">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 text-center post-project-section">
                        <h2>We're ready when you are!</h2>
                        <p>Get started by posting a project or task to the MeasureMatch platform and have access to the a vetted pool of Experts.</p>
                        <a class="standard-version-btn" href="{{url('homepage/postproject',[],$ssl)}}">Post a project/task to MeasureMatch</a>
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ url('js/jquery.min.js?js='.$random_number,[],$ssl) }}"></script>
        <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>       
        <script src="{{ url('js/jquery_cookie.js?js='.$random_number,[],$ssl) }}" type="text/javascript"></script>
        @include('include.footer')
        @include('include.global_layout_parent')

        <script>

            $(document).ready(function () {
                var buyerAddress = $('#buyerAddress').val();
                $("#home_arrow").click(function () {
                    $('html, body').animate({
                        scrollTop: $("#whyshould").offset().top -100
                    }, 1000);
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                if (window.location.href.indexOf("pplink") > -1) {
                    $('#privacypolicy_link').click();
                }
                if (window.location.href.indexOf("tnc") > -1) {
                    $('#tnc_link').click();
                }
                
          
        $('#our_expert_link').addClass('active');
          });

        </script>
</body>
</html>