<!DOCTYPE html>
<html lang="en">
    @include('include.landing_head_script')
    <body class="white-bg fixednavbody">
        @include('include.googleTagsScriptsBody')
        @include('include.landingheader')
        <header class="landingheader about-us-bg">
            <div class="home-bx">
                <div class="home-section">
                    <div class="home-panel">
                        <div class="container">
                            <img src="images/header_logo.svg" class="header_logo">
                            <h2>About us</h2>
                            <h3> We’re passionate freelancers and problem-solvers. And now we’re bringing readily available technology, data and insight specialists to companies the world over.</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="learnmore"><a id="learnmoreabout" href="javascript:void(0)">Learn more about us <img src="images/down-arrow.svg" alt="down-arrow" class="down-arrow" /> </a></div>
        </header>
        <section class="about-us-page" id="aboutus">
            <div class="container">
    
                <div class="col-md-offset-2 col-md-8 col-sm-12">
                    <p><strong>MeasureMatch</strong> is an on-demand marketplace where companies can post marketing technology, data management, analytics and insight projects for amazing independent consultants and consultancies to service. </p> <p>We founded the company to address a number of acute problems all too commonly experienced by companies trying to advance their digital marketing, measurement, insight and customer experience capabilities.  These problems include:</p>

                    <ul>
                        <strong>Technology vendors:</strong> <a href="http://www.gartner.com/it-glossary/software-as-a-service-saas/" target="_blank">SaaS</a> or otherwise, they provide powerfully valuable products, but many do not have, nor do they want to have, the headcount to service the companies that buy or subscribe to their software. If they do have the headcount, their primary goals are to sign more contracts and generate incremental revenue.

                    </ul>
                    <p class="left-space">MeasureMatch Experts on-demand help to fill this gap.</p>
                    <ul>
                        <strong>Supply & Demand: </strong>Independent technology, analytics and insight services providers, while in considerable demand, are, silly as it may seem, very hard for companies to find when they’re needed. This is partly due to a shortage of supply, but the real reason is disorganization on both sides.

                    </ul>
                    <p  class="left-space">MeasureMatch helps to address this problem.</p>
                    <ul>
                        <strong>Verification: </strong>There is little to no independent data for companies to verify the quality of the contingent workers or consultancies in advance of buying their services.
                    </ul>
                    <p  class="left-space">MeasureMatch screening processes and ratings system help here, too.</p>

                    <ul><strong>Feast or Famine: </strong>Independent consultants, freelancers and consultancies are hungry for work and growth, but they all-too-commonly don’t dedicate time or resources to sales and marketing, so they regularly experience feast-or-famine revenue cycles.</ul>
                    <p class="left-space">MeasureMatch is committed to attracting and retaining the best and most valuable companies, and their projects, for our community of Experts.</p>

                    <h4>Our Vision</h4>

                    <p>Optimal and personalized customer experiences are technology and data-driven, but most of the world’s businesses are nowhere close. <strong>MeasureMatch</strong> is determined to become the destination for those organizations to find and hire amazing independent technology, data and insight experts directly, on demand and from anywhere on planet earth to more quickly achieve and advance beyond their business goals.
</p>

                </div>
            </div>
        </section>
        <section class="home-newsletter">
    <div class="container">
<p>Subscribe to the MeasureMatch newsletter</p>
    <form id="subscription_newsletter" method="post" action="">
        {{ csrf_field() }}
        <input type="text" autocorrect="off" autocapitalize="none" placeholder="Your email" name="newsletters" id="newsletters" value="">
    <input type="button" id="subscribe_newsletter" value="Subscribe" class="standard-btn" />
    <div id="email_error"></div>
    </form>
    </div>
</section>
       <section class="team-section">
        <div class="container">


			<div class="col-sm-12 text-center">
            <h3>The Team</h3>
      </div>
            <div class="col-sm-4 team-widget">
            	<div class="team-pic"><img src="images/JameS.jpg"></div>
                <h4>James Sandoval<a target="_blank" class="linkedin-profile-btn"  href="https://www.linkedin.com/in/jamessandoval/"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>MeasureMatch Founder & CEO</span>
            </div>

           <div class="col-sm-4 team-widget">
            	<div class="team-pic"><img src="images/MattO.jpg"></div>
                <h4>Matt O’Neill<a target="_blank" class="linkedin-profile-btn" href="https://www.linkedin.com/in/tmattoneill"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Founding Advisor & Investor</span>
                <span class="team_designation">European GM for The Media Trust </span>
            </div>

            <div class="col-sm-4 team-widget">
            <div class="team-pic"><img src="images/ShubuM.jpg"></div>
                <h4>Shubu Mitra<a target="_blank" class="linkedin-profile-btn" href="https://www.linkedin.com/in/shubumitra"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Founding Advisor & Investor</span>
                <span class="team_designation">Director, Connection Planning Effectiveness & Productivity at The Coca-Cola Company</span>
            </div>
			         <div class="clearfix"></div>
            <div class="col-sm-4 team-widget">
            	<div class="team-pic"><img src="images/Maggie.jpg"></div>
                <h4>Maggie Finch<a target="_blank" class="linkedin-profile-btn" href="https://www.linkedin.com/in/maggie-boyer-finch-5367935"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Board of Advisors</span>
                <span class="team_designation">Entrepreneur & Advisor</span>
            </div>

           <div class="col-sm-4 team-widget">
            	<div class="team-pic"><img src="images/EmmaM.jpg"></div>
                <h4>Emma Marlow<a class="linkedin-profile-btn" target="_blank" href="https://www.linkedin.com/in/demonllama"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Board of Advisors</span>
                <span class="team_designation">Director, DemonLlama Limited</span>

            </div>

           <div class="col-sm-4 team-widget">
            <div class="team-pic"><img src="images/PeterO.jpg"></div>
                <h4>Peter O’Neill<a class="linkedin-profile-btn" target="_blank" href="https://www.linkedin.com/in/peteroneill"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Board of Advisors</span>
                <span class="team_designation">Founder, MeasureCamp, Founder & CEO, L3 Analytics</span>

            </div>
	<div class="clearfix"></div>
            <div class="col-sm-4 col-sm-offset-4 team-widget">
            <div class="team-pic"><img src="images/JamesD.jpg"></div>
                <h4>James Dutton<a class="linkedin-profile-btn" target="_blank" href="https://www.linkedin.com/in/jamesdutton"><img src="{{ url('images/linke-in.png',[],$ssl)}}"></a></h4>
                <span>Board of Advisors</span>
                <span class="team_designation">Managing Director, Asia Pacific at Resolution Media, Omnicom Media Group</span>

            </div>


        </div>
    </section>


        @include('include.footer')
         <script src="{{ url('js/bootstrap.min.js?js='.$random_number,[],$ssl) }}"></script>
         <script src="{{ url('js/aboutus.js?js='.$random_number,[],$ssl) }}"></script>
           @include('include.global_layout_parent')
       </body>
</html>
