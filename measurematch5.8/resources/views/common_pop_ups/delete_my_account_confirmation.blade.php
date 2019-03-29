<div class="modal fade profile-page-popup delete-account-popup change-psw-popup  lightbox-design lightbox-design-small" id="request_to_delete_account_pop_up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
        <div class="modal-innner-content">
            <div class="modal-content">
                <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">
                            <img  alt="cross" src="{{url('images/cross-black.svg',[],$ssl)}}">
                    </span>
                </div>

                <div class="modal-body text-align-left">
                    <h3 class="gilroyregular-bold-font font-24 text-align-center">Request to delete my account</h3>
                    <p class="margin-bottom-10 text-align-center margin-bottom-20 font-14">Are you sure you'd like to request your account to be deleted?</p>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-align-center">
                        <button  class="blue-bg-btn green-gradient  standard-btn" data-dismiss="modal">Cancel</button>
                        <button id="confirm_account_deletion" class="blue-bg-btn green-gradient standard-btn" data-dismiss="modal">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
