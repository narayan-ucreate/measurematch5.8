<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */



Route::get('download', 'Controller@download');
Route::post('contactEmail', 'UsersController@contactEmail');
// Route::post('login', 'Auth\AuthController@login');
Route::get('home', 'Auth\AuthController@login');
Route::get('reset', function () {
    return view('pages.reset');
});
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

Route::get('password_redirect', function() {
    $admin_id = Auth::user()->admin_approval_status;

    if ($admin_id == 0) {
        Auth::logout();
        return Redirect::To('login')->with('success', 'Your profile is in the queue for approval. Please check again later.');
    }

    return Redirect::To('/');
});
Route::get('password/reset{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::get('password/sent-mail', 'Auth\ForgotPasswordController@sentMail')->name('sentMail');
Route::get('password/success', 'Auth\ResetPasswordController@successReset')->name('successReset');
Route::get('expert/account-frozen', 'UserProfileController@accountFrozen');

Route::get('/', ['as' => 'home', 'uses' => 'UsersController@home']);

Route::get('/provider', 'UsersController@provider');
Route::get('referralLink', 'UsersController@referralLink');

Route::group(['middleware' => 'adminAuth'], function(){
   Route::prefix('admin')->group(function () {
        Route::get('/exportArchivedExpert', 'admin\AdminController@exportArchivedExpert');
        Route::match(['get', 'post'], '/expert-skills', 'admin\AdminController@exportSkills');
        Route::get('/exportArchivedBuyer', 'admin\AdminController@exportArchivedBuyer');
        Route::get('/exportNotVerifiedBuyer', 'admin\AdminController@exportNotVerifiedBuyer');
        Route::post('/approveUser', 'admin\AdminController@approveUser');
        Route::post('/declineUser', 'admin\AdminController@declineUser');
        Route::post('/expertApproveWebflow', 'admin\AdminController@expertApproveWebflow');
        Route::get('/buyerListing', ['as' => 'buyerListing', 'uses' => 'admin\AdminController@buyerListing']);
        Route::get('/expertListing', 'admin\AdminController@expertListing');
        Route::get('/vendorListing', 'admin\AdminController@vendorListing');
        Route::get('/pendingHubs', 'admin\AdminController@pendingHubs');
        Route::get('/archivedHubs', 'admin\AdminController@archivedHubs');
        Route::get('/liveHubs', 'admin\AdminController@liveHubs');
        Route::get('/pendingVendors', 'admin\AdminController@pendingVendors');
        Route::get('/unverifiedVendors', 'admin\AdminController@unverifiedVendors');
        Route::get('/liveProjects', 'admin\AdminController@liveProjects');
        Route::get('/completedProjects', 'admin\AdminController@completedProjects');
        Route::get('/archivedBuyersListing', ['as' => 'archivedBuyersListing', 'uses' => 'admin\AdminController@archivedBuyersListing']);
        Route::get('/archivedExpertsListing', 'admin\AdminController@archivedExpertsListing');
        Route::get('/archivedVendorsListing', 'admin\AdminController@archivedVendorsListing');
        Route::post('/blockUser', 'admin\AdminController@blockUser');
        Route::get('/pendingBuyers', ['as' => 'pendingBuyers', 'uses' => 'admin\AdminController@pendingBuyers']);
        Route::get('/pendingExperts', 'admin\AdminController@pendingExperts');
        Route::get('/incompleteProfileExperts', 'admin\AdminController@expertWithIncompleteProfile');
        Route::get('/expertWithIncompleteProfileView/{id}', 'admin\AdminController@expertWithIncompleteProfileView');
        Route::get('/exportNotVerifiedExperts', 'admin\AdminController@exportNotVerifiedExperts');
        Route::get('/pendingProjects', 'admin\AdminController@pendingProjects');
        Route::get('/inContractProjects', 'admin\AdminController@inContractProjectsListing');
        Route::get('/pendingservicepackages', 'admin\AdminController@pendingServicePackages');
        Route::get('/alldraftedservicepackages', 'admin\AdminController@allDraftedServicePackages');
        Route::get('/exportExpertWithIncompleteProfile', 'admin\AdminController@exportExpertWithIncompleteProfile');
        Route::get('/exportSideHustlersExperts', 'admin\AdminController@exportSideHustlersExperts');
        Route::get('/exportProjectsInformation/{id}', 'admin\AdminController@exportProjectsInformation');
        Route::get('/exportProjects', 'admin\AdminController@exportProjects');
        Route::get('/exportInContractProjects', 'admin\AdminController@exportInContractProjects');
        Route::get('/exportCompletedProjects', 'admin\AdminController@exportCompletedProjects');
        Route::get('/exportBuyerProjects/{id}/{buyer_id}', 'admin\AdminController@exportBuyerProjects');
        Route::get('/exportArchivedProjects', 'admin\AdminController@exportArchivedProjects');
        Route::get('/archivedProjects', 'admin\AdminController@archivedProjectsListing');
        Route::get('/expiredProjects', 'admin\AdminController@expiredProjectsListing');
        Route::get('/rebookingProjects', 'admin\AdminController@rebookingProjectsListing');
        Route::get('/exportexperteservicepackages/{id}', 'admin\AdminController@exportExpertServicePackages');
        Route::get('/exportdraftedservicepackages/{id}', 'admin\AdminController@exportDraftedServicePackages');
        Route::get('/exportalldraftedservicepackages', 'admin\AdminController@exportAllDraftedServicePackages');
        Route::get('/exportpendingeservicepackages', 'admin\AdminController@exportPendingServicePackages');
        Route::get('/exportapprovedeservicepackages', 'admin\AdminController@exportApprovedServicePackages');
        Route::get('/exportpendingeservicepackages', 'admin\AdminController@exportPendingServicePackages');
        Route::get('/exportrejectedservicepackages', 'admin\AdminController@exportRejectedServicePackages');
        Route::get('/vendorExportListing', 'admin\AdminController@vendorExportListing');
        Route::post('/unblockUser', 'admin\AdminController@unblockUser');
        Route::post('/reinstateProject', 'admin\AdminController@reinstateProject');
        Route::post('/buyerUpdate', 'admin\AdminController@buyerUpdate');
        Route::post('/vendorUpdate', 'admin\AdminController@vendorUpdate');
        Route::post('/expertUpdate', 'admin\AdminController@expertUpdate');
        Route::get('/buyerExportListing', 'admin\AdminController@buyerExportListing');
        Route::get('/expertExportListing', 'admin\AdminController@expertExportListing');
        Route::get('/getProjects/{id}', 'admin\AdminController@getProjects');
        Route::get('/rejectedservicepackages', 'admin\AdminController@rejectedServicePackages');
        Route::get('/servicepackages', 'admin\AdminController@approvedServicePackages');
        Route::get('/expert/servicepackages/{id}', 'admin\AdminController@expertServicePackages');
        Route::get('/expert/draftedservicepackages/{id}', 'admin\AdminController@getDraftedServicePackages');
        Route::get('/editservicepackage/{id}', 'admin\AdminController@editServicePackage');
        Route::get('/editpendingservicepackage/{id}', 'admin\AdminController@editServicePackage');
        Route::get('/viewPublishedProject/{id}', 'admin\AdminController@viewPublishedProject');
        Route::get('/publishedservicepackage/{id}', 'admin\AdminController@viewServicePackage');
        Route::get('/approvedservicepackage/{id}', 'admin\AdminController@viewServicePackage');
        Route::get('/rejectedservicepackage/{id}', 'admin\AdminController@viewServicePackage');
        Route::get('/draftedservicepackage/{id}', 'admin\AdminController@viewServicePackage');
        Route::get('/viewPendingProject/{id}', 'admin\AdminController@viewPendingProject');
        Route::get('/pendingservicepackage/{id}', 'admin\AdminController@viewPendingServicePackage');
        Route::post('/updateLiveProject', 'admin\AdminController@updateLiveProject');
        Route::get('/buyerView/{id}', 'admin\AdminController@buyerView');
        Route::get('/vendorView/{id}', 'admin\AdminController@vendorView');
        Route::get('/buyerPendingView/{id}', 'admin\AdminController@buyerPendingView');
        Route::get('/vendorPendingView/{id}', 'admin\AdminController@vendorPendingView');
        Route::get('/buyerEdit/{id}', 'admin\AdminController@buyerEdit');
        Route::get('/vendorEdit/{id}', 'admin\AdminController@vendorEdit');
        Route::get('/expertView/{id}', 'admin\AdminController@expertView');
        Route::get('/expertPendingView/{id}', 'admin\AdminController@expertPendingView');
        Route::get('/buyerArchievedView/{id}', 'admin\AdminController@buyerArchievedView');
        Route::get('/expertArchievedView/{id}', 'admin\AdminController@expertArchievedView');
        Route::get('/vendorArchivedView/{id}', 'admin\AdminController@vendorArchivedView');
        Route::get('/expertEdit/{id}', 'admin\AdminController@expertEdit');
        Route::get('/project/{id}', 'admin\AdminController@viewProject');
        Route::get('/hub/{id}', 'admin\AdminController@viewHub');
        Route::get('/project/edit/{id}', 'admin\AdminController@editLiveProject');
        Route::get('/unverifiedBuyers', ['as' => 'unverifiedBuyers', 'uses' => 'admin\AdminController@unverifiedBuyers']);
        Route::get('/notverifiedexperts', 'admin\AdminController@notverifiedexperts');
        Route::get('/sideHustlersExperts', 'admin\AdminController@sideHustlersExperts');
        Route::get('/viewUnverifiedBuyer/{id}', 'admin\AdminController@viewUnverifiedBuyer');
        Route::get('/viewUnverifiedVendor/{id}', 'admin\AdminController@viewUnverifiedVendor');
        Route::get('/notverifiedexpertView/{id}', 'admin\AdminController@notverifiedexpertView');
        Route::get('/sideHustlerView/{id}', 'admin\AdminController@sideHustlerView');
        Route::post('/resendemail', 'admin\AdminController@resendemail');
        Route::get('/getContractDetailsPopup/{id}', 'admin\AdminController@getContractDetailsPopup');
        Route::post('/updateContractDetails/{id}', 'admin\AdminController@updateContractDetails');
        Route::post('/switchVendorInviteSetting', 'admin\AdminController@switchVendorInviteSetting');
        Route::post('/deleteexpert', 'admin\AdminController@hardDeleteExpert')->name('deleteExpert');
        Route::post('/approveRejectServiceHub', 'ServiceHubController@approveRejectServiceHub');
    });
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', function () {
        return view('pages.profile');
    });
    Route::get('/technographic-search-results', 'TechnographicController@createTechnographicBuyerUrl');
    Route::post('/update-basic-information','UsersController@updateBasicInformation');
    Route::get('get-vat-details-popup/{id}', 'UsersController@getVatDetailsPopup');
    Route::post('user/store-vat-detais/{user_id}', 'UsersController@storeVatDetails');
    Route::post('buyer/save-business-address/{user_id}', 'BuyerAccountController@saveBusinessAddress');
});
Route::group(['middleware' => 'expertMiddleware'], function() {
    Route::post('proposal/store-terms/{communication_id}', 'ProposalController@storeTerms')->name('store-terms');
    Route::get('expert/profile-summary', 'UserProfileController@userProfile');
    Route::get('expert/profile-skills', 'UserProfileController@userProfile');
    Route::get('expert/work-history', 'UserProfileController@userProfile');
    Route::get('expert/profile-education', 'UserProfileController@userProfile');
    Route::any('expert/projects-search', ['as' => 'projects-search', 'uses' => 'SellerSearchController@searchProject']);
    Route::get('expert/dashboard', 'DashboardController@dashboard');
    Route::get('expert/settings', 'UserProfileController@editSellerAccount');
    Route::get('expert/messages', ['as' => 'expertMessage', 'uses' => 'MessagesController@expertMessageView']);
    Route::get('expert/projects/expired', 'PostController@expiredProjectsView');
    Route::get('{communication_id}/proposal/vat-details', 'ProposalController@vatPreview')->name('vat-details-page');
    Route::get('{communication_id}/proposal/{step}', 'ProposalController@index')->name('send-proposal');
    Route::post('proposal/store-deliverable/{communication_id}', 'ProposalController@manageDeliverable')->name('manage-deliverable');
    Route::post('apply-to-service-hub', 'ServiceHubController@appyToServiceHub')->name('apply-to-service-hub');
    Route::get('servicepackage/create', 'ServicePackageController@createServicePackage');
});

Route::group(['middleware' => 'BuyerOrExpert'], function() {
    Route::get('vendor-service-hubs', 'ServiceHubController@vendorServiceHubs')->name('vendor-service-hubs');
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('service-hubs/create/{step?}', 'ServiceHubController@index')->name('service-hubs-create');
    Route::get('service-hubs', 'ServiceHubController@vendorHubs')->name('service-hubs');
    Route::get('mark-applicant-as-seen/{id}', 'ServiceHubController@markApplicantAsSeen');
    Route::post('service-hubs', 'ServiceHubController@store')->name('service-hubs-store');
    Route::post('store-invited-experts', 'VendorInvitedExpertController@store')->name('store-invited-experts');
});
Route::group(['middleware' => 'vendorMiddleware' OR 'adminAuth'], function (){
    Route::put('expert/approve/{id}', 'ServiceHubController@approveExpert')->name('approve-service-hub-expert');
    Route::post('expert/decline', 'ServiceHubController@declineExpert')->name('decline-service-hub-expert');
    Route::get('service-hubs-experts/{id?}', 'ServiceHubController@allExpertsList');
    Route::get('service-hubs-right-hand-section/{id?}', 'ServiceHubController@serviceHubRightHandBlock');
    Route::get('service-hub/approved-experts/{id}', 'ServiceHubController@viewHub');
});
Route::group(['middleware' => 'vendorBuyerMiddleware'], function() {
    Route::get('project/create', 'PostController@createProject')->name('create-project');
    Route::get('myprojects', 'BuyerDashboardController@integratedMyProjects')->name('buyer-my-projects');
    Route::get('servicepackage/types', ['as' => 'find-service-package', 'uses' => 'ServicePackageController@types']);
    Route::any('buyer/experts/search', ['as' => 'buyer-search', 'uses' => 'BuyerSearchController@searchResult']);
    Route::get('buyer/settings', 'BuyerAccountController@basicinfo');
    Route::post('updatebuyerbasic', 'BuyerAccountController@updateBasicInfo');
    Route::get('servicepackage/{id}', ['as' => 'view-service-package', 'uses' => 'ServicePackageController@detailsViewedByBuyer']);
    Route::get('buyer/expert-profile/{id}', ['as' => 'expert-profile', 'uses' => 'UserProfileController@buyerExpertProfileView']);
    Route::get('servicepackages/type/{id}', ['as' => 'service-package-types', 'uses' => 'ServicePackageController@servicePackageTypeWiseList']);
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('vendor-service-hubs/{id}', 'ServiceHubController@vendorServiceHubsDetails')->name('vendor-service-hubs-details');
});

Route::any('/admin/admin_login', 'admin\AdminController@adminLogin');
Route::get('/technographic-info', 'TechnographicController@loadingPage');
Route::get('/technographic-results', 'TechnographicController@search');
Route::get('/get-technographic-logo', 'TechnographicController@getClearbitData');
Route::get('/get-buyer-details', 'UsersController@getBuyerDetailsFromClearbit');

Route::get('admin_logout', 'admin\AdminController@logout');
Route::get('getlocationdetails', 'UsersController@getLocationDetails');
Route::get('checkUniqueMME', 'UsersController@checkUniqueMME');
Route::get('referExpertLink', ['as' => 'referExpertLink', 'uses' => 'UsersController@referExpertLink']);
Route::get('buyer/expert-profile-detail', ['as' => 'expert-profile-detail', 'uses' => 'UserProfileController@sellerprofiledetail']);
Route::post('searchrecord', 'BuyerSearchController@searchrecord');
Route::post('updateLoggedInStatus', 'UserProfileController@updateLoggedInStatus');
Route::post('deleteWorkHistory', 'UserProfileController@deleteWorkHistory');
Route::post('deleteCollegeUniversity', 'UserProfileController@deleteCollegeUniversity');
Route::post('deleteCertificateAndCourses', 'UserProfileController@deleteCertificateAndCourses');

Route::get('viewsellerprofile', 'UserProfileController@viewSellerProfile');
//admin
Route::auth();
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('signin', 'Auth\AuthController@getLogin');
//registration
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('emailCheck', 'UsersController@emailCheck');
//Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('register', function() {
    return redirect('/');
});
//signup
Route::post('register', 'UsersController@create');
//signup process view
Route::any('expertbasicinformation', 'UserProfileController@expertBasicInformation');
Route::any('editsellerrate', 'UserProfileController@editsellerrate');
Route::post('savelanguage', 'UsersLanguageController@savelanguage');
Route::post('editlanguage', 'UsersLanguageController@editlanguage');
Route::post('editeducation', 'UsersEducationController@editEducation');
Route::post('addcourse', 'UsersCourseController@addcourse');
Route::post('editcourse', 'UsersCourseController@editcourse');
Route::post('addeducation', 'UsersEducationController@addEducation');
Route::post('editsummary', 'UserProfileController@editsummary');
Route::any('addemployment', 'UsersEmploymentController@addEmploymentDetail');
Route::any('editemployment', 'UsersEmploymentController@editEmploymentDetail');
Route::any('editsellerbio', 'UserProfileController@editsellerbio');

Route::get('buyer/profile-summary', 'BuyerController@buyerProfile');
Route::get('buyer/reviews', 'BuyerController@buyerProfile');
Route::post('editcompany', 'BuyerController@editcompany');
Route::get('viewbuyerprofile', ['as' => 'buyer-detail-page', 'uses' => 'BuyerController@viewBuyerProfile']);
Route::post('savebio', 'BuyerController@savebio');
Route::post('addlogo', 'BuyerController@addlogo');
Route::post('sellerlogo', 'UserProfileController@sellerlogo');
Route::post('saveskills', 'UsersSkillController@saveskills');
Route::get('deleteskill', 'UsersSkillController@deleteskill');

Route::get('redirectlogin', 'HomeController@redirectAfterLogin');

Route::get('buyer/projects/post', 'PostController@postJob');
Route::get('buyer/projects/post-preview', 'PostController@postJob');
Route::any('draft', 'PostController@updatedraft');
Route::post('shareProject', 'PostController@shareProject');
Route::post('checkShareProject', 'PostController@checkShareProject');


Route::get('project_view', ['as' => 'project_view', 'uses' => 'UserProfileController@job_view']);
Route::post('/buyerfeedback', 'MessagesController@buyerfeedback');
Route::post('/editbuyerfeedback', 'MessagesController@editBuyerfeedback');
Route::post('referExpert', ['as' => 'referExpert', 'uses' => 'MessagesController@referExpert']);
Route::post('referSingleExpert', ['as' => 'referSingleExpert', 'uses' => 'MessagesController@referSingleExpert']);
Route::post('showInterest', 'UserProfileController@showInterest');
Route::get('removeInterest', 'UserProfileController@removeInterest');
Route::get('showInterestStatus', 'UserProfileController@showInterestStatus');

Route::get('projects_view', ['as' => 'projects_view', 'uses' => 'DashboardController@jobsView']);
Route::get('buyerprojects_view', ['as' => 'buyerprojects_view', 'uses' => 'BuyerDashboardController@jobs_view']);
Route::post('updateselleraccount', 'UserProfileController@updSellerAccount');
Route::post('emailUpdateCheck', 'UsersController@emailUpdateCheck');
Route::get('getUserProfile', 'UsersController@getUserProfile');
Route::post('buyeremailCheck', 'BuyerAccountController@emailUpdateCheck');
Route::any('addresslist', 'BuyerAccountController@addresslist');
Route::post('updatebuyerbuisness', 'BuyerAccountController@updateBuisnessInfo');
Route::any('selleraddresslist', 'SellerAccountController@addresslist');
Route::post('updatesellerbuisness', 'SellerAccountController@updateBuisnessInfo');

Route::post('updateselleraccountinfo', 'SellerAccountController@updateSellerAccountInfo');
Route::post('updateExpertPassword', 'SellerAccountController@updateExpertPassword');
Route::post('checkUserPassword', 'SellerAccountController@checkUserPassword');
Route::post('updatebuyeraccountinfo', 'BuyerAccountController@updateBuyerAccountInfo');
Route::post('updateBuyerPassword', 'BuyerAccountController@updateBuyerPassword');
Route::get('sendmail', 'PostmarkMailController@sendMail');

Route::post('updatesellerCommunication', 'SellerAccountController@updatesellerCommunication');
Route::post('updateBuyerCommunication', 'BuyerAccountController@updateBuyerCommunication');
Route::any('updateStatus', 'UsersController@updateStatus');
Route::get('buyertypeoforganization', 'BuyerController@buyerTypeOfOrganization');
Route::get('buyertypeOrg', 'BuyerController@buyertypeOrg');
Route::get('pplink', 'UsersController@home');
Route::get('tnc', 'UsersController@home');

Route::post('userCheckExist', 'UsersController@userCheckExist');
Route::get('getSkills', 'UsersController@getSkills');

Route::get('faq', 'HomeController@faq');
Route::get('about-us', 'HomeController@aboutUs');
Route::get('contact-us', 'HomeController@contactUs');
Route::get('terms-of-service', 'HomeController@termsOfService');
Route::get('privacy-policy', 'HomeController@privacyPolicy');
Route::get('cookie-policy', 'HomeController@cookiePolicy');
Route::get('our-experts', 'HomeController@defaultExperts');

Route::get('getInfo', 'SellerAccountController@getInfo');
Route::post('delete_add_stripe_card', 'BuyerAccountController@delete_add_stripe_card');
Route::post('delete_add_stripe_card_from_msg', 'BuyerAccountController@delete_add_stripe_card_from_msg');

/* admin Routes */

Route::get('/paneladmin1', 'admin\AdminController@index');


//abc


/* admin routes end */


Route::get('unsubscribe', 'UsersController@unsubscribe');
Route::get('/unsubscribeEmail', 'UsersController@unsubscribeEmail');
Route::post('/deleteUserAccount', 'UsersController@deleteUserAccount');
Route::get('publish_projects_view', 'PostController@publish_jobs_view');
Route::get('expertUpcomingMessage', 'Controller@expertUpcomingMessage');

Route::post('newsletterSubscribe', 'UsersController@newsletterSubscribe');
Route::any('defaultlanguage', 'UsersController@defaultlanguage');
Route::post('updateProjectStatus', 'admin\AdminController@updateProjectStatus');
Route::post('rejectProject', 'admin\AdminController@rejectProject');

/* Home page post project */
Route::any('post-a-project-step1', 'PostController@postProjectFromHome');
Route::get('post-a-project-step2', 'PostController@postProjectFromHome');
Route::get('post-a-project-step3', 'PostController@postProjectFromHome');
Route::get('post-a-project-step4', 'PostController@postProjectFromHome');

Route::any('homepage/postproject', function() {
    $user = Auth::user();
    if ($user) {
        return Redirect::To('/redirectlogin');
    } else {
        return Redirect::To('homepage/postproject');
    }
});

Route::get('post-project-login-from-homepage', function() {
    $user = Auth::user();
    if ($user) {
        $userType = $user->user_type_id;
        return Redirect::To('/redirectlogin');
    } elseif (array_key_exists('project_from_home', $_COOKIE)) {
        return view('pages.login');
    } else {
        return view('auth.login');
    }
});
Route::post('removeSessionValues', 'UsersController@removeSessionValues');
Route::get('getbase64', 'UserProfileController@getbase64');

/* New buyer dashboard work start */
Route::get('buyernewdashboard', 'BuyerDashboardController@integrated_buyer_dashboard');
Route::post('add_project_in_session', 'BuyerDashboardController@addNameDetailInSession');

Route::get('buyer/project-progress/{id}', 'BuyerDashboardController@projectProgress');
Route::get('saveexpert/{id}', 'BuyerDashboardController@saveExpert');
Route::get('savedexpertlisting', 'BuyerDashboardController@savedExpertsListing');
Route::get('randomexpertslisting/{id}', 'BuyerDashboardController@recommendedExpertsAjax');
Route::get('pastmatchingexpertlisting', 'BuyerDashboardController@pastMatchingExpertsListing');
Route::get('deleteproject/{id}', 'BuyerDashboardController@deleteProject');
Route::get('getcontractviewpopup/{id}', 'MessagesController@contractViewPopUp');
Route::get('getcontracteditpopup/{id}', 'MessagesController@contractEditPopUp');
Route::get('getapplycouponpopup/{id}', 'MessagesController@applyCouponPopUp');
Route::get('getmarkascompleteconfirmpopup/{id}', 'MessagesController@markAsCompleteConfirmPopUp');
Route::get('getmarkascompleteconfirmexpertpopup/{id}', 'MessagesController@expertContractCompleteConfirmationPopUp');

//centralized sign up flow
Route::get('signup', 'UsersController@actionSignupView');
Route::post('saveUser', 'UsersController@saveUser');
Route::get('success', 'UsersController@successPage');
Route::get('checkUniqueEmail/{email}', 'UsersController@checkUniquEmail');
Route::get('checkbuyeremailtobebusinessemail/{email}', 'UsersController@validateBuyerEmailToBeBusinessEmail');
Route::post('postProjectBuyerSignUp', 'BuyerController@postProjectBuyerSignUp');

Route::get('insertPromotionalCouponCode/{coupon}/{date}', 'MessagesController@insertPromotionalCouponCode');
Route::post('previewBuyerPromoCode', 'MessagesController@previewBuyerPromoCode');
Route::post('removeBuyerPromoCode', 'MessagesController@removeBuyerPromoCode');
Route::get('checkcouponuniqueness', 'MessagesController@checkIfCouponAlreadyApplied');

Route::get('buyer/messages', ['as' => 'buyerMessage', 'uses' => 'MessagesController@redirectBuyerToProjectBasedMessaging']);

Route::post('savemessage', 'MessagesController@saveMessage');
Route::get('users/{id}/profile', 'MessagesController@userProfileDetails');
Route::get('applicants/{id}/profile', 'ServiceHubController@applicantDetails');
Route::get('users/{communication_id}/messages', 'MessagesController@messageDetails');
Route::get('users/contractdetail', 'MessagesController@userContractDetails');
Route::post('users/{id}/initiateconversation', 'MessagesController@buyerInitiateConversation');
Route::post('showmakeofferpopup', 'MessagesController@makeOfferBuyerStatus');
Route::post('users/{buyer_id}/makeoffer/expertid/{expert_id}', 'MessagesController@sendContract')->name('send-contract');
Route::post('makeoffer/confirmterms', 'MessagesController@confirmMakeOfferTerms');
Route::post('users/{buyer_id}/getfeedbackbybuyerpopup/expertid/{expert_id}', 'MessagesController@getFeedbackByBuyerPopup');
Route::post('expertcontractviewpopup/{contract_id}', 'MessagesController@viewContractByExpertPopup');
Route::post('editcontractoffer', 'MessagesController@editContractOffer');
Route::post('buyerfeedbacktoexpert', 'MessagesController@buyerFeedbackToExpert');
Route::post('acceptcontractbybuyer', 'MessagesController@acceptContractByBuyer');
Route::post('acceptservicepackagebyexpert', 'MessagesController@acceptServicePackageByExpert');
Route::post('markcontractascomplete', 'MessagesController@markContractAsComplete');
Route::post('applycouponbyexpert', 'MessagesController@applyRefferalCouponByExpert');
Route::post('expertmarkcontractcomplete', 'MessagesController@expertMarkContractComplete');
Route::post('expertfinishservicepackagecontract', 'MessagesController@expertFinishServicePackageContract');
Route::get('updatepostjobsaccpetedcontractstatus/{offset}/{limit}', 'MessagesController@updatePostjobsAccpetedContractStatus');
Route::get('chatnotificationcount', 'MessagesController@chatNotificationCount');
Route::get('users/{communication_id}/markallmessagesread', 'MessagesController@markAllMessagesRead');
Route::get('users/{user_id}/getnewmessagenotifications', 'MessagesController@getNewMessageNotificationOnOtherPages');
Route::post('/invitenewexpertforconversation', 'MessagesController@inviteExpertForConversation');
Route::post('supportnotification', 'UsersController@supportNotification');
Route::any('inbound', 'MessagesController@postmarkInbound');
Route::any('email/seenbyuser', 'MessagesController@emailSeenByUser');

Route::get('buyer/activeprojectslisting', 'BuyerSearchController@activeProjectsListing');
Route::get('saveexperttoproject', 'BuyerSearchController@saveExpert');
Route::get('buyer/savedexpertslisting', 'BuyerSearchController@savedExpertsListing');
Route::get('buyer/unsaveexpert', 'BuyerSearchController@unsaveExpertForProject');
Route::any('updateusersetting', 'BuyerSearchController@updateUserSetting');

Route::get('servicepackages', 'ServicePackageController@expertServicePackageListing');
Route::get('myservicepackages', ['as' => 'my-service-package', 'uses' => 'ServicePackageController@buyerServicePackageListing']);
Route::get('servicepackagetypes', 'ServicePackageController@getTypes');
Route::post('servicepackage/save', 'ServicePackageController@saveServicePackage');
Route::get('servicepackage/edit/{id}', 'ServicePackageController@editServicePackage');
Route::get('servicepackage/detail/{id}', 'ServicePackageController@servicePackageDetails');
Route::any('servicepackage/update/{id}', 'ServicePackageController@updateServicePackage');
Route::any('servicepackage/approve', 'ServicePackageController@approveServicePackage');
Route::any('servicepackage/unapprove', 'ServicePackageController@disApproveServicePackage');
Route::any('servicepackage/approveWebflow', 'ServicePackageController@approveWebflow');
Route::post('servicepackage/hidepackage', 'ServicePackageController@hideServicePackage');
Route::post('servicepackage/unhidepackage', 'ServicePackageController@unHideServicePackage');
Route::get('servicepackage/gettags', 'ServicePackageController@getTags');
Route::post('servicepackage/eoiarchieve', 'ServicePackageController@archieveEOI');
Route::post('servicepackage/eoiunarchieve', 'ServicePackageController@unArchieveEOI');
Route::get('updatefeaturedservicepackage', 'ServicePackageController@updateFeaturedTypes');
Route::post('servicepackages/deleteservicepackage', 'ServicePackageController@delete');
Route::post('servicepackages/savedexperts', 'ServicePackageController@savedExperts');
Route::get('servicepackageeditpoup/{contract_id}', 'MessagesController@servicePackageEditContractPoup');
Route::post('service/saveservicepackage', 'ServicePackageController@saveServicePackageByBuyer');
Route::post('deletesavedservicepackage', 'ServicePackageController@deleteSavedServicePackage');
Route::get('finishservicepackagecontract/{contract_id}', 'MessagesController@finishMonthlyRetainerServicePackageContract');
Route::get('showpopupfinishmonthlyspcontract/{contract_id}', 'MessagesController@showPopupFinishMonthlyServicePackageContract');
Route::get('addtosession', 'ServicePackageController@addToSession');
Route::post('showinterestinservicepackage', 'MessagesController@showInterestInServicePackage');
Route::get('expertUpcomingMessageForServicePackage', 'Controller@expertUpcomingMessageForServicePackage');
Route::get('servicepackages/{name}/{id}', 'ServicePackageController@publicServicePackage');
Route::get('editextendedofferpopup/communication_id/{communication_id}', 'ServicePackageController@publicServicePackage');
Route::get('insertinvaliddomainstodb', 'BuyerController@insertInvalidEmailsToDatabase');
Route::get('resendverificationemail', 'UsersController@resendVerificationEmail');
Route::get('support', 'UsersController@supportRequestThroughMobile');
Route::get('addlocaltimezonetochat', 'MessagesController@addLocalTimeZoneToSession');
Route::get('contract/{id}/download', 'MessagesController@downloadPdf');
Route::get('skillsautocomplete', 'UsersSkillController@skillsAutocomplete');
Route::get('toolsautocomplete', 'UsersSkillController@toolsAutocomplete');
Route::get('homepage/postproject', 'PostController@createFromHomePage');
Route::post('homepage/postproject/save', 'PostController@saveProjectFromHome');
Route::get('postproject/finalstep', 'UsersController@signUpAfterPostingProject');
Route::post('project/save', 'PostController@saveProject')->name('saveProject');
Route::get('project/edit/{id}', 'PostController@editProject')->name('editProject');
Route::post('project/approvewebflow', 'PostController@approvewebflow');

//scripts
Route::get('scripttoupdateuniqueidsincontracts', 'MessagesController@scriptToUpdateUniqueIdInContracts');
Route::get('scripttoupdateskills', 'UsersSkillController@scriptToUpdateSkills');
Route::get('skillsnamescorrectionscript', 'UsersSkillController@skillsNamesCorrectionScript');
Route::get('skillsistoolstatusupdationscript', 'UsersSkillController@skillsIsToolStatusUpdationScript');
Route::get('scripttoupdatevisitorscount', 'ServicePackageController@scriptToUpdateVisitorsCount');
Route::get('scripttoupdatepublishdate', 'PostController@scriptToUpdatePublishDate');
Route::get('scripttoupdatevisibilitydate', 'PostController@scriptToUpdateVisibilityDate');
Route::get('scripttoexportexpertstowebflow', 'admin\AdminController@exportExpertsToWebflow');
Route::get('scripttoexportservicepackagestowebflow', 'admin\AdminController@exportServicePackagesToWebflow');
Route::get('scripttoupdateskillsaliases', 'TechnographicController@updateSkillsAliases');
Route::get('scripttoaddnewskills', 'UsersSkillController@scriptToAddNewSkills');

//below route is created to identify the users that have completed the basic required fields as expert
Route::get('updateuserprofilemandatefieldcheck', 'admin\AdminController@updateUserProfileMandateFieldsCheckScript');
Route::get('updatetypeoforganization', 'BuyerController@updateTypeOfOrganizationInBuyerProfileTableScript');
Route::get('expert-country/update', 'UserProfileController@updateExpertCountryScript');
Route::get('re-route/{type}/{id}', 'MessagesController@redirectFromCustomUrl');
Route::get('request-my-data', 'UsersController@requestMyData');
Route::get('delete-my-account', 'UsersController@accountDeletionRequest');
Route::get('buyer/mobile-view', 'BuyerController@buyerMobileView');
Route::get('buyer/messages/{type}/{id}', ['as' => 'buyerProjectMessages', 'uses' => 'MessagesController@buyerMessageView']);
Route::get('buyer/overlay/project/{id}','MessagesController@viewProjectDetail');

