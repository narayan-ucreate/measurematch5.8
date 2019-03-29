<?php
$user_id =  Auth::user()->id;
?>
<div class="modal lightbox-design lg-modal post-project-modal" id="account_review_pop_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
                <div class="modal-body text-align-center">
                    <h3 class="font-28 margin-top-0 text-align-center">Welcome to MeasureMatch!</h3>
                    <h4 class="font-18 text-align-center margin-bottom-30 gilroyregular-font">Get started by selecting one of the options below.</h4>
                    <div class="col-md-4 col-xs-12 browse-experts-panel margin-bottom-20">
                        <img src="{{url('images/submit-a-project.svg','',$ssl)}}" alt="submit-a-project" />
                        <h3 class="gilroyregular-bold-font font-20 margin-top-32">Submit a Project brief</h3>
                        <p class="font-16">Create a clear and concise statement of your objectives with our easy to use form. This will enable relevant Experts to easily understand what you are looking to achieve and respond to you.</p>
                        <a class="expert-mark-as-complete margin-top-10 gilroyregular-semibold standard-btn font-16" data-dismiss="modal" aria-label="Close" href="javascript:void()" />Submit a Project</a>
                    </div>
                    <div class="col-md-4 col-xs-12 browse-service-packages-panel margin-bottom-20">
                        <img src="{{url('images/browse-service-packages.svg','',$ssl)}}" alt="browse-service-packages" />
                        <h3 class="gilroyregular-bold-font font-20 margin-top-32">Browse Service Packages</h3>
                        <p class="font-16">Unsure of your exact needs?  Look through the many services available, select a ready made package to help you achieve success and connect with the Expert offering it immediately.</p>
                        <a class="continue-btn margin-top-10 gilroyregular-semibold font-16 green_gradient standard-btn" href="{{url('servicepackage/types'), '', $ssl}}">Browse Service Packages</a>
                    </div>
                    <div class="col-md-4 col-xs-12 submit-a-project-panel margin-bottom-20">
                      <img src="{{url('images/browse-experts.svg','',$ssl)}}" alt="browse-experts" />
                      <h3 class="gilroyregular-bold-font font-20 margin-top-32">Browse Experts</h3>
                      <p class="font-16">Understand more about the awesome Experts on our platform; their experience, locations, services they specialise in and brands and clients they have already helped succeed.</p>
                      <a class="continue-btn font-16 margin-top-10 green_gradient gilroyregular-semibold standard-btn" href="{{url('buyer/experts/search'), '', $ssl}}">Browse Experts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
