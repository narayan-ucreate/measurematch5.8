<!DOCTYPE html>
<html>
@include('include.landing_head_script')
<body class="white-bg fixednavbody">
@include('include.googleTagsScriptsBody')
@include('include.landingheader')
<link rel="stylesheet" href="{{ url('css/measurematch-hero.webflow.css?css='.$random_number,[],$ssl) }}">
<link rel="stylesheet" href="{{ url('css/webflow.css?css='.$random_number,[],$ssl) }}">
@if(!isset($_COOKIE['cookieIds']) && empty($_COOKIE['cookieIds']))
<div class="topbluebar">
    <div class="container">
        <p>By using this site and service you agree to the use of cookies for analytics, personalized content and services</p>
        <div class="pull-right"><span id="cookiesPopup" class="popup-close-btn pull-right">x</span><a class="pull-right" target="_blank" href="{{ url('cookie-policy',[],$ssl) }}" title="Learn More">Learn More</a></div>
    </div>
</div>
@endif
@if(isset($_REQUEST['referal_email']))
<header class="homepagebanner">
    <div class="homebannertxt">
        <div class="wrapper">
            <div class="txtblock">
                <div class="container">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6 homepagebanner-content">
                      <h1 class="refer-heading">
                        A one stop shop for Marketing projects
                        </h1>
                         <p>
                          Quickly and intuitively find specialist marketing projects and build relationships with potential clients
                        </p>
                         <div class="bannerlinks custom-button">
                            <a href="{{url('expert/signup-step1',[],$ssl)}}" class="join_expert-btn">I Am A Consultant <span>Sign Me Up</span></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6 mm-logo-lg">
                        <img src="images/logo_mm_large.svg" class="icon" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
@else
<div class="section-hero">
  <div class="div-herocontent hero-contaner">
     <div class="hero-inner-container">
      <div class="container-herocontent">
        <div class="div-herocontent">
            <h1 class="heading gilroyregular-bold-font">Onboard <span  class="txt-rotate" data-period="2000" data-rotate='[ "Data", "Analytics", "MarTech", "CX", "Cloud", "#dataviz", "DMP", "CDP", "Adobe", "GA", "Salesforce", "Marketo" ]'></span> Experts On Demand.</h1>
            <p class="para-hero">MeasureMatch connects a range of data, analytics &amp; martech professionals with any organization to <strong class="gilroyregular-bold-font">measure</strong> and <strong  class="gilroyregular-bold-font">optimize</strong> for better customer experiences.</p>
          <a href="{{url('signup',[],$ssl)}}" class="join_buyer-btn standard-btn">Get Started</a>
        </div>
        <div class="div-heroexpert">
            <h4 class="gilroyregular-bold-font">Sadie S</h4>
          <div class="para">Analytics Expert</div>
          <div><em>Joined in May 2016</em></div>
        </div>
      </div>
    </div>
  </div>
    <div class="div-logos">
      <div class="container-logos">
        <div class="para-logos">MeasureMatch Experts are trusted by brands like these:</div>
        <div class="div-horizontallogos">
            <img src="images/images/tommy-hilfiger.png" height="9" alt="Tommy Hilfiger Logo" class="image-4">
            <img src="images/images/new-balance.png" alt="New Balance Logo" height="26" class="image-5">
            <img src="images/images/nivea.png" alt="Nivea Logo" height="15" class="image-7">
            <img src="images/images/samsung.png" alt="Samsung Logo" height="23">
            <img src="images/images/toyota.png" alt="Toyota Logo" height="33" class="image">
            <img src="images/images/Nike.png" alt="Nike Logo" height="20">
            <img src="images/images/dyson.png" alt="Dyson Logo" height="21" class="image-3">
            <img src="images/images/bmw.png" alt="BMW Logo" height="26" class="image-6">
            <img src="images/images/amex.png" alt="Amex Logo" height="22">
            <img src="images/images/barclays.png" alt="Barclays Logo" height="19">
        </div>
      </div>
    </div>
  </div>
  @endif
     <section class="how-it-work-section">
            @if(!isset($_REQUEST['referal_email']))
            <div class="container" id="whyshould">
                <a href="javascript:void()" id="home_arrow" class="backtotop-btn"></a>
                <h2>How it works</h2>
                <div class="home-widget">
                    <div class="ïmgicon">
                        <div class="ïmgblock">
                            <img src="images/join-icon.svg" class="icon" />
                        </div>
                    </div>
                    <h4>Join</h4>
                    <p>It’s fast and it’s free!</p>
                </div>
                <div class="home-widget">
                    <div class="ïmgicon">
                      <div class="ïmgblock">
                        <img src="images/match-icon.svg" class="icon" />
                      </div>
                    </div>
                    <h4>Match</h4>
                    <p>Clients – post a project and then browse and contact experts. <br/><br/>Experts –create a great profile, express your interest in projects and/or respond to clients.</p>
                </div>

                <div class="home-widget">
                    <div class="ïmgicon">
                      <div class="ïmgblock">
                        <img src="images/work-icon.svg" class="icon" />
                      </div>
                  </div>
                    <h4>Work</h4>
                    <p>Agree to terms and pricing, and get to work.</p>
                </div>

                <div class="home-widget">
                    <div class="ïmgicon"><div class="ïmgblock"> <img src="images/payment-icon.svg" class="icon" /> </div> </div>
                    <h4>Pay</h4>
                    <p>Payments are processed securely through MeasureMatch</p>
                </div>

                <div class="home-widget">
                    <div class="ïmgicon"><div class="ïmgblock"> <img src="images/measure-icon.svg" class="icon" /> </div> </div>
                    <h4>Measure</h4>
                    <p>Rate the expert orclientyou work with. Ratings build trust and quality.</p>
                </div>

            </div>
            @else
            <div class="container" id="whyshould">
                <a href="javascript:void()" id="home_arrow" class="backtotop-btn"></a>
                <h2>How it works</h2>
                <div class="home-widget">
                    <div class="ïmgicon">
                        <div class="ïmgblock">
                            <img src="images/join-icon.svg" class="icon" />
                        </div>
                    </div>
                    <h4>Join</h4>
                    <p>It’s fast and it’s free!</p>
                </div>

                <div class="home-widget">
                    <div class="ïmgicon">
                        <div class="ïmgblock">
                            <img src="images/match-icon.svg" class="icon" />
                        </div>
                    </div>
                    <h4>Match</h4>
                    <p> Experts –create a great profile, express your interest in projects and/or respond to clients.</p>
                </div>

              <div class="home-widget">
                 <div class="ïmgicon">
                    <div class="ïmgblock">
                       <img src="images/work-icon.svg" class="icon" />
                    </div>
                 </div>
                 <h4>Work</h4>
                 <p>Agree to terms and pricing, and get to work.</p>
              </div>

              <div class="home-widget">
                <div class="ïmgicon">
                  <div class="ïmgblock">
                    <img src="images/payment-icon.svg" class="icon" />
                  </div>
                </div>
                <h4>Payment</h4>
                <p>Payments are made through MeasureMatch, which keeps 15% of an experts's gross contract value.</p>
              </div>

              <div class="home-widget">
                <div class="ïmgicon">
                   <div class="ïmgblock">
                      <img src="images/measure-icon.svg" class="icon" />
                   </div>
                 </div>
                 <h4>Measure</h4>
                 <p>Rate the expert orclientyou work with. Ratings build trust and quality.</p>
              </div>
            </div>
          @endif
        </section>
        @if(!isset($_REQUEST['referal_email']))
        <section class="next-match-section">
           <div class="container">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hero-content-block">
                    <div class="nextmatch-txt">
                        <h2 class="gilroyregular-bold-font">Tap into highly specialized & valued experts</h2>
                        <p> It’s free to post a project and contact Experts</p>
                        <p> We iterate vetting to maximize quality and trust </p>
                        <p> You can get projects started in days instead of weeks</p>
                        <p> We’re here to help you get the most out of each MeasureMatch experience </p>
                        <a href="{{url('signup',[],$ssl)}}" class="post-a-project-btn white-btn">Get Started</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hero-img-block">
                    <img src="images/macbook_mm.png" class="icon" />
                </div>
            </div>
        </section>
        @endif
        @if(isset($_REQUEST['referal_email']))
        <section class="signup-content-block refer-next-match-section">
            <div class="container">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hero-content-block">
                    <div class="nextmatch-txt">
                        <h2>Why Should I sign up?</h2>
                        <p>Showcase your skills to a broad range of qualified clients looking for your services.</p>
                        <p>Enjoy quick, easy access to high-quality engagement opportunities.</p>
                        <p>Minimise gaps between projects via a pipline of MeasureMatch work.</p>
                     <a href="{{url('expert/signup-step1',[],$ssl)}}" class="post-a-project-btn">I Am A Consultant <span>Sign Me Up</span></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hero-img-block"><img src="images/macbook_mm.png" class="icon"></div>
            </div>
        </section>
        @else
        <section class="signup-content-block">
            <div class="content-block">
                <div class="container">
                    <h2>Why should I sign up?</h2>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 content-left-section">
                        <h3>Experts</h3>
                        <p>Showcase your skills to a broad range of rated clients looking for your services.</p>
                        <div class="white-border"></div>
                        <p>Enjoy quick, easy access to high-quality engagement opportunities.  </p>
                        <div class="white-border"></div>
                        <p>Minimise gaps between projects via a pipeline of MeasureMatch work.</p>
                        <div class="white-border"></div>
                        <p>The MeasurePay system ensures you get paid as quickly as possible.</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 content-right-section">
                        <h3 class="gilroyregular-bold-font">Clients</h3>
                        <p>Search for and hire trusted experts as and when you need them.</p>
                        <div class="white-border"></div>
                        <p>Post a projects directly for freelancers, independent consultants and consultancies.</p>
                        <div class="white-border"></div>
                        <p>Enjoy peace of mind that experts are vetted by MeasureMatch and rated by clients like you.</p>
                        <div class="white-border"></div>
                        <p>Benefit from flexible pricing and a speedy, transparent contracting process.</p>
                    </div>
                </div>
            </div>
            @endif
        </section>
        <section class="services-section">
            <div class="container">
                <h2 class="gilroyregular-bold-font">Deep & Broad Enterprise Value Services<br/>Creating Sustainable Competitive Advantage.</h2>
                <div class="col-lg-4 col-md-4 col-sm-4 services-widget">
                    <h3 class="gilroyregular-bold-font">Technology</h3>
                    <ul>
                        <li>Business Case Justification</li>
                        <li>Capability Roadmap</li>
                        <li>Customer Identity Management</li>
                        <li>Data Management Platforms</li>
                        <li>Data Pipeline Development</li>
                        <li>Digital Transformation Services</li>
                        <li>Marketing Automation</li>
                        <li>Organizational Design</li>
                        <li>Personalisation</li>
                        <li>Platform Integrations</li>
                        <li>Post-Implementation Diagnostics</li>
                        <li>Project Management</li>
                        <li>Solution Design</li>
                        <li>Strategy & Advisory</li>
                        <li>Systems Configuration</li>
                        <li>Systems Implementation</li>
                        <li>Systems Integration</li>
                        <li>Tagging</li>
                        <li>Technology Audit</li>
                        <li>Technology Evaluation & Selection</li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 services-widget">
                    <h3 class="gilroyregular-bold-font">Data</h3>
                    <ul>
                        <li> Analytics Strategy</li>
                        <li>Attribution Modelling</li>
                        <li>Conversion Rate Optimization</li>
                        <li>CRM Analytics</li>
                        <li>Custom Algorithms</li>
                        <li>Custom Reporting</li>
                        <li>Customer Journey Analysis</li>
                        <li>Data Cleansing/ETL</li>
                        <li>Data Collection Strategy</li>
                        <li>Data Integration</li>
                        <li>Data Layer Design</li>
                        <li>Data Platform Architecture</li>
                        <li>Data Science</li>
                        <li>Data Visualization</li>
                        <li>Digital Analytics</li>
                        <li>Forecasting</li>
                        <li>Location Analytics</li>
                        <li>Machine Learning</li>
                        <li>Merchandising & Sales Analytics</li>
                        <li>Multivariate Testing</li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 services-widget">
                    <h3 class="gilroyregular-bold-font">Insight</h3>
                    <ul>
                        <li>Advertising/Creative Effectiveness</li>
                        <li>Brand Equity Measurement</li>
                        <li>Campaign Effectiveness</li>
                        <li>Concept Testing</li>
                        <li>Copy Testing</li>
                        <li>Customer Loyalty</li>
                        <li>Customer Satisfaction</li>
                        <li>Data Analysis</li>
                        <li>Demand Forecasting</li>
                        <li>Desk Research</li>
                        <li>Econometric Modelling</li>
                        <li>Marketing Mix Modeling</li>
                        <li>Meta Analyses</li>
                        <li>Price Elasticity</li>
                        <li>Pricing</li>
                        <li>Project Management</li>
                        <li>Psychographics</li>
                        <li>Segmentation</li>
                        <li>User Experience/Usability Testing</li>
                        <li>Viral Marketing</li>
                    </ul>
                </div>
            </div>
        </section>
       @if(isset($_REQUEST['referal_email']))
    <section class="wealth-section">
        <div class="container">
            <h3>One step away from a wealth of<br /> opportunities</h3>
            <a class="post-a-project-btn" href="{{url('expert/signup-step1',[],$ssl)}}">I Am A Consultant <span>Sign Me Up</span></a>
        </div>
    </section>
    @endif
    @include('include.footer')
    <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
    @include('include.global_layout_parent')
    <script type="text/javascript" src="{{ url('js/index.js?js='.$random_number,[],$ssl) }}"></script>
   </body>
</html>
