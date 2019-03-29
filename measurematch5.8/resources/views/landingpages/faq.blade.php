@extends('layouts.landingpageslayout')
@section('content')
<header class="landingheader faq-bg">
    <div class="home-bx">
        <div class="home-section">
            <div class="home-panel">
                <div class="container">
                    <img src="images/header_logo.svg" class="header_logo">
                    <h2>FAQ</h2>
                    <h3>Here are some of the most commonly asked questions on the platform.</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="learnmore"><a id="faqbtn" href="javascript:void(0)">Read our FAQ <img src="images/down-arrow.svg" alt="down-arrow" class="down-arrow" /> </a></div>
</header>
<section id="faqpanel">
    <div class="container clearfix">
        <div class="question_answer-block">
            <div class="panel-group" id="accordion"> <!-- accordion 1 -->
                <div class="panel">

                    <div class="panel-heading" id='abc'> <!-- panel-heading -->
                        <h4 class="panel-title"> <!-- title 1 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionOne" class="panel-anchor">
                                What is MeasureMatch?
                            </a>
                        </h4>
                    </div>
                    <!-- panel body -->
                    <div id="accordionOne" class="panel-collapse collapse">
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>MeasureMatch is a two-sided professional services marketplace that is designed to make it easier for companies to find and hire high-quality Experts who are especially skilled in the areas of marketing technology, marketing analytics and market research. Many of these Experts are independent practitioners, but also part of independent consultancies and other types of services organizations.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">  <!-- accordion 2 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 2 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionTwo" class="panel-anchor">
                                Which services are available via MeasureMatch?
                            </a>
                        </h4>
                    </div>
                    <!-- panel body -->
                    <div id="accordionTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Broadly, the services available from MeasureMatch Experts fall into three core areas of value: Technology, Analytics and Research. You could further segment these services down to advisory and execution services. Advisory services might encompass strategic guidance or solution design. Execution services might include systems implementation, configuration and/or integration. Using Marketing Technology as an example, because we are marketing and commerce focused, companies might choose to use MeasureMatch to find an Expert who can support or lead the deployment of a system like <a href="https://www.decibelinsight.com/" target="b
                                                                                                                                                                                                        ">Decibel Insight</a> for customer experience analytics or <a href="http://www.adobe.com/uk/solutions/digital-marketing/dynamic-tag-management.html" target="_blank">Adobe’s Dynamic Tag Management</a> system for enterprise-wide, multi-channel data collection or <a href="https://www.marketo.com/" target="_blank">Marketo</a> for marketing automation. You can imagine how many other marketing technology-related services might be sought after by any number of companies around the globe. The same goes for Analytics and Research services. Essentially, it is up to MeasureMatch Clients (i.e. the companies looking for and buying services) to let us or MeasureMatch Experts know what they need.</p>
                        </div>
                    </div>

                </div>

                <div class="panel">  <!-- accordion 3 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 3 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionThree" class="panel-anchor">
                                What is the MeasureMatch backstory?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionThree" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Depending on how you #measure it, MeasureMatch is the product James Sandoval's ~20 year digital marketing services career and 7+ years of entrepreneurial adventures focused on marketing technology sales and data services. Or, more specifically, MeasureMatch is the result of what he likes to call "entrepreneurial experiments" between November 2014 and March 2016 when, amongst other things, he attempted to merge several analytics consultancies across Europe. The merger didn't happen, but the experience was enlightening, inspiring and, importantly, it helped to shape what is now MeasureMatch.</p>
                        </div>

                    </div>

                </div>

                <div class="panel">  <!-- accordion 3 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 3 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionfour" class="panel-anchor">
                                Who are MeasureMatch Experts?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionfour" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>These are exceptional freelancers and organizations that sell and provide their services via MeasureMatch.</p>
                        </div>

                    </div>

                </div>

                <div class="panel">  <!-- accordion 3 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 3 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionfive" class="panel-anchor">
                                Who are MeasureMatch Clients?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionfive" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>These are the people in companies who buy services via MeasureMatch.</p>
                        </div>

                    </div>

                </div>

                <div class="panel">  <!-- accordion 3 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 3 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionsix" class="panel-anchor">
                                What is MeasureMatch trying to achieve?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionsix" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Nearly all of the people involved in the development of the MeasureMatch service have been freelancers, entrepreneurs or worked in small companies at some time. We know what it's like to hunt for work, do the work, try to hunt for more work at the same time and, invariably, scrape through income-free periods of time. We know how hard it is to be organised, remain motivated, chase for the payment of invoices. It's a lot like the loneliness of the long distance runner. Part of MeasureMatch’s mission is to help them to run their #measure, #martech and #mrx marathons, in comfortable shoes, cheered on by and supported every step of the way, and to cross the finish line with a better result, every single time.</p>
                        </div>

                    </div>

                </div>

                <div class="panel">  <!-- accordion 3 -->

                    <div class="panel-heading">
                        <h4 class="panel-title"> <!-- title 3 -->
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionseven" class="panel-anchor">
                                Who should sign up?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionseven" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>On one side, MeasureMatch is for contingent workers i.e. independent consultants and consultancies with outstanding technology, analytics and research skills and experience. And, of course, on the other side, it is for companies that need to solve related business problems and are open to hiring experts on-demand.</p>
                        </div>

                    </div>

                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordioneight" class="panel-anchor">
                                Why should Experts (i.e. freelancers) sign up?
                            </a>
                        </h4>
                    </div>

                    <div id="accordioneight" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Experts should sign up because it's free, it's potentially a great way to raise their profiles (to be found by Clients looking to hire) and signing up is required for freelancers to see and express interest in projects posted by MeasureMatch Clients.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordionnine" class="panel-anchor">
                                Why should Clients sign up?
                            </a>
                        </h4>
                    </div>

                    <div id="accordionnine" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Clients should sign up because it's free, it’s potentially a great way to find an awesome technology, analytics or insight professional, and signing up is required in order to start looking for MeasureMatch Experts. </p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordiontenth" class="panel-anchor">
                                What is MeasureMatch’s relationship with Clients & Experts?
                            </a>
                        </h4>
                    </div>

                    <div id="accordiontenth" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>
                                MeasureMatch is the provider of a marketplace and the infrastructure to enable Clients and Experts to conduct a dialogue about prospective project engagements and enter into direct agreements for the provision of relevant project work. The relationship between MeasureMatch and Clients is governed by the Terms of Service <a href="javascript:void();" data-toggle="modal" data-target="#termsservcies">here</a></p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#accordioneleventh" class="panel-anchor">
                                Why should consultancies or agencies (large or small) sign up to sell their services?
                            </a>
                        </h4>
                    </div>

                    <div id="accordioneleventh" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Well, why wouldn't they? MeasureMatch, for these organizations, is simply a gateway to potentially valuable work and revenue at a favourable cost.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#one" class="panel-anchor">
                                Does MeasureMatch vet new members?
                            </a>
                        </h4>
                    </div>

                    <div id="one" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Yes, MeasureMatch, over time, will introduce and evolve screening processes for new and existing Experts to ensure we are supplying the best possible services professional to Clients. We will also conduct identity verification and, when necessary, require the submission of one or more identity documents and/or profile photos. </p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#two" class="panel-anchor">
                                How much does it cost to be a MeasureMatch Expert?
                            </a>
                        </h4>
                    </div>

                    <div id="two" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Nothing. It doesn't cost a penny to register and create a MeasureMatch Expert profile. And it doesn't cost anything, except some time, for an Expert to enter into a conversation with a MeasureMatchclientabout a work opportunity. MeasureMatch currently captures 15% of each MeasureMatch work contract.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#three" class="panel-anchor">
                                How quickly will Experts get paid?
                            </a>
                        </h4>
                    </div>

                    <div id="three" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Our goal is to transfer payment funds to each MeasureMatch Expert within 15 business days following the completion and approval (by both Client and Expert) of agreed work milestones or following the completion of the entirety of each project (approved by both Client and Expert).</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#four" class="panel-anchor">
                                Does MeasureMatch guarantee payment for services fully rendered and approved by clients?
                            </a>
                        </h4>
                    </div>

                    <div id="four" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Yes, MeasureMatch guarantees payment for services that are fully rendered by Experts and approved by both Clients and Experts.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#five" class="panel-anchor">
                                Is MeasureMatch a member of any freelance organizations?
                            </a>
                        </h4>
                    </div>

                    <div id="five" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Not yet, but we are looking into a number of them globally. We will no doubt support those that best support the interest of MeasureMatch Experts and Clients.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#six" class="panel-anchor">
                                Who are the founders of MeasureMatch?
                            </a>
                        </h4>
                    </div>

                    <div id="six" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>James Sandoval started to develop MeasureMatch in early April 2016. Matt O’Neill and Shubu Mitra are both founding investors and advisors. See the About Us page for more details.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#seven" class="panel-anchor">
                                Which payment methods are supported for Clients?
                            </a>
                        </h4>
                    </div>

                    <div id="seven" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Clients can make payments via any major credit/debit card, bank account and via wire transfer. Additional methods will be introduced over time.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eigth" class="panel-anchor">
                                How can Experts receive payments?
                            </a>
                        </h4>
                    </div>

                    <div id="eigth" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Experts can receive payments directly into their chosen bank account. We’re working on adding other options soon.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#nine" class="panel-anchor">
                                What if I want to hire a MeasureMatch Expert full-time?
                            </a>
                        </h4>
                    </div>

                    <div id="nine" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>While it’s not ideal for us, because clearly s/he is amazing enough to want full-time, we think that’s awesome. As per our Terms of Service, we will need to structure an arrangement to formally release the Expert from the MeasureMatch service to you.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#tenth" class="panel-anchor">
                                Who owns the legal rights to the intellectual property created by a MeasureMatch Expert during a project engagement?
                            </a>
                        </h4>
                    </div>

                    <div id="tenth" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>All work created by MeasureMatch Experts becomes the property of MeasureMatch Clients as per our Terms of Service.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eleventh" class="panel-anchor">
                                How frequently will I be billed?
                            </a>
                        </h4>
                    </div>

                    <div id="eleventh" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>We bill MeasureMatch Clients at least once per month. When we start to support payment by milestones (in Q1 2017), billing will happen in accordance with the schedule defined between Experts and Clients.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eleventh01" class="panel-anchor">
                                How are the payment terms?
                            </a>
                        </h4>
                    </div>

                    <div id="eleventh01" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Credit/Debit cards are charged when payment is due. For those Clients who require invoicing, payment terms are net 10. When net 10 terms are not acceptable, Clients are required to pay 50% of the project value in advance, which will be held in escrow until payments to Experts are required. </p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eleventh02" class="panel-anchor">
                                How does MeasureMatch make money?
                            </a>
                        </h4>
                    </div>

                    <div id="eleventh02" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>We currently charge Experts 15% of the value of each contract. </p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eleventh03" class="panel-anchor">
                                [Client] What should I do after I sign-up to MeasureMatch?
                            </a>
                        </h4>
                    </div>

                    <div id="eleventh03" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Start searching for Experts, strike up a conversation or three. We designed the MeasureMatch experience for Clients to centre on these things and entering into project contracts. It’s worth noting that Experts can only start submitting Expressions of Interest in your project(s) after their profiles have reached a specific level of completion. In time, Experts will also need to successfully complete a skills, personality and identity screening process.</p>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#eleventh04" class="panel-anchor">
                                [Expert]  What should I do after I sign-up to MeasureMatch?
                            </a>
                        </h4>
                    </div>

                    <div id="eleventh04" class="panel-collapse collapse">
                        <!-- panel body -->
                        <div class="panel-body">
                            <a href="javascript:void(0)" class="faq-close"><img src="images/faq-close.svg" /></a>
                            <p>Start searching for projects and go back to your profile to ensure you’re positioning yourself and your skills as clearly and persuasively as possible. It is important to know that Experts can only submit an Expression of Interest in projects after their profiles are at a specific level of completion (see your profile for details). </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<script src="{{ url('js/faq.js?js='.$random_number,[],$ssl) }}"></script>
@endsection('content')