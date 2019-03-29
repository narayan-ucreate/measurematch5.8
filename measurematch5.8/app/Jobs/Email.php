<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\BuyerProfile;
use App\Model\User;
use Postmark\PostmarkClient;
use App\Model\PostJob;
use App\Model\Message;
use App\Model\Communication;
use App\Model\CouponAppliedByExpert;
use App\Model\Contract;
use App\Model\RemoteWork;
use App\Model\JobsSkill;
use App\Model\Deliverable;
use App\Model\OutboundEmailLog;
use App\Components\CommonFunctionsComponent;

class Email extends Job implements ShouldQueue {

    use InteractsWithQueue,
        SerializesModels;

    public $method;
    public $data;

    public function __construct($method, $data) {
        $this->method = $method;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer  $mailer
     * @return void
     */
    private static $client = null;

    public function __sleep() {
        return [
            'method',
            'data',
            'connection',
            'queue',
            'delay',
            'job'
        ];
    }

    private static function getClient() {
        if (!self::$client) {
            self::$client = new PostmarkClient(getenv('POSTMARK_TOKEN'));
        }
        return self::$client;
    }

    private static function sendEmail($data) {
        return self::getClient()
                ->sendEmailWithTemplate($data['from'], $data['to'], $data['template_id'], $data['template_data'], $inlineCss = true, $tag = NULL, $trackOpens = true, $replyTo = $data['reply_to'], $cc = NULL, $bcc = NULL, $headers = NULL, $attachments = NULL, $trackLinks = NULL);
    }

    private static function getTemplateId($type) {
        $config = [
            'newProjectEmailNotification' => getenv('EXPERT_NEW_PROJECT_NOTIFICATION'),
            'adminChatNotification' => getenv('ADMIN_MESSAGE_NOTIFICATION'),
            'emailToExpertExpressionOfInterestAccepted' => getenv('ACTIVE_EXPERT_EOI_ACCEPTED_EMAIL_TEMPLATE_ID'),
            'emailToExpertOnInvite' => getenv('EMAIL_TO_EXPERT_ON_INVITE'),
            'emailToBuyerExpressionOfInterestAccepted' => getenv('ACTIVE_BUYER_EOI_ACCEPTED_EMAIL_TEMPLATE_ID'),
            'emailToAdminSendContractDetail' => getenv('ACTIVE_CONTRACT_START_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'sendContractEmailToExpert' => getenv('ACTIVE_EXPERT_CONTRACT_EMAIL_TEMPLATE_ID'),
            'sendContractEmailToBuyer' => getenv('ACTIVE_BUYER_CONTRACT_EMAIL_TEMPLATE_ID'),
            'sendContractEmailToAdmin' => getenv('ACTIVE_ADMIN_CONTRACT_EMAIL_TEMPLATE_ID'),
            'sendExpertProjectCompletionNotification' => getenv('ACTIVE_EXPERT_CONTRACT_FINAL_PAYMENT_EMAIL_TEMPLATE_ID'),
            'projectCompletionNotificationToAdmin' => getenv('PROJECT_COMPLETION_NOTIFICATION_EMAIL_TO_ADMIN'),
            'sendBuyerProjectCompletionNotification' => getenv('ACTIVE_BUYER_CONTRACT_FINAL_PAYMENT_EMAIL_TEMPLATE_ID'),
            'sendBuyerFeedbackNotification' => getenv('ACTIVE_BUYER_FEEDBACK_REQUEST_TEMPLATE_ID'),
            'sendExpertCouponCodeMail' => getenv('ACTIVE_EXPERT_COUPON_TEMPLATE_ID'),
            'sendExpertWelcomeMail' => getenv('ACTIVE_EXPERT_WELCOME_EMAIL_TEMPLATE_ID'),
            'sendBuyerWelcomeMail' => getenv('ACTIVE_Confirmation_BUYER_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'sendVendorWelcomeMail' => getenv('VENDOR_WELCOME_EMAIL'),
            'expertVerificationEmail' => getenv('EXPERT_APPROVAL_EMAIL_TEMPLATE_ID'),
            'buyerVerificationEmail' => getenv('ACTIVE_ADMIN_APPROVAL_BUYER_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'sendPostProjectMail' => getenv('ACTIVE_BUYER_POST_PROJECT_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'sendFirstPostProjectMail' => getenv('ACTIVE_BUYER_FIRST_POST_PROJECT_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'resetPassword' => getenv('ACTIVE_PASSWORD_RESET_EMAIL_TEMPLATE_ID'),
            'projectAdminReview' => getenv('ACTIVE_BUYER_POST_PROJECT_FROM_HOME_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'userVerficationEmail' => getenv('ACTIVE_ADMIN_APPROVAL_BUYER_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'waitingProjectApprovalEmail' => getenv('BUYER_PROJECT_PENDING_FOR_APPROVAL'),
            'shareEmailWithRegisteredUser' => getenv('ACTIVE_EXPERT_SHARE_EMAIL_WITH_REGISTERED_USER'),
            'shareEmailWithUnregisteredUser' => getenv('ACTIVE_EXPERT_SHARE_EMAIL_WITH_UNREGISTERED_USER'),
            'referredRegisteredExpertEmail' => getenv('ACTIVE_REFER_EXPERT_SHARE_EMAIL_WITH_REGISTERED_USER'),
            'referredUnregisteredExpertEmail' => getenv('ACTIVE_REFER_EXPERT_SHARE_EMAIL_WITH_UNREGISTERED_USER'),
            'expressionOfInterestEmailToExpert' => getenv('ACTIVE_EXPERT_EOI_EMAIL_TEMPLATE_ID'),
            'expressionOfInterestEmailToBuyer' => getenv('ACTIVE_BUYER_EOI_EMAIL_TEMPLATE_ID'),
            'sendEOIMailToAdmin' => getenv('ACTIVE_EOI_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'verificationToExpert' => getenv('ACTIVE_ADMIN_APPROVAL_EXPERT_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'expertApprovalEmail' => getenv('EXPERT_ACCOUNT_APPROVAL_BY_ADMIN'),
            'buyerApprovalEmail' => getenv('BUYER_ACCOUNT_APPROVAL_BY_ADMIN'),
            'vendorApprovalEmail' => getenv('VENDOR_ACCOUNT_APPROVAL_BY_ADMIN'),
            'buyerAccountRejectionEmail' => getenv('BUYER_ACCOUNT_REJECTION_BY_ADMIN'),
            'projectVerficationEmail' => getenv('ACTIVE_ADMIN_PROJECT_APPROVAL_BUYER_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'expertEmailForAdminReview' => getenv('ACTIVE_ADMIN_REVIEW_EXPERT_WELCOME_EMAIL_TEMPLATE_ID'),
            'buyerAccountForAdminReview' => getenv('BUYER_ACCOUNT_FOR_ADMIN_REVIEW'),
            'sendEmailToBuyerConfirmingPaymentModeInvoice' => getenv('BUYER_PAYMENT_CONFIRMATION_MESSAGE_INVOICE'),
            'sendMissedMessageEmailToExpert' => getenv('EXPERT_MISSED_MESSAGE_ALERT'),
            'sendMissedMessageEmailToBuyer' => getenv('BUYER_MISSED_MESSAGE_ALERT'),
            'expertProfileCompletionEmailToAdmin' => getenv('ACTIVE_ADMIN_EXPERT_EMAIL_TEMPLATE_ID'),
            'emailExpertWeekly' => getenv('ACTIVE_EXPERT_WEEK_PRIOR_EMAIL_TEMPLATE_ID'),
            'emailBuyerWeekly' => getenv('ACTIVE_BUYER_WEEK_PRIOR_EMAIL_TEMPLATE_ID'),
            'emailContractEnd' => getenv('ACTIVE_EXPERT_CONTRACT_END_EMAIL_TEMPLATE_ID'),
            'emailUpcomingContractStartInKernel' => getenv('ACTIVE_EXPERT_CONTRACT_START_EMAIL_TEMPLATE_ID'),
            'buyerEmailUpcomingContractStart' => getenv('ACTIVE_BUYER_CONTRACT_START_EMAIL_TEMPLATE_ID'),
            'pendingActivationBuyerEmail' => getenv('ACTIVE_REMINDER_BUYER_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'pendingActivationExpertEmail' => getenv('ACTIVE_REMINDER_EXPERT_VERIFICATION_EMAIL_TEMPLATE_ID'),
            'emailAdminBeforeProjectStart' => getenv('ACTIVE_PROJECT_STARTS_IN_24_HOURS_EMAIL_TO_ADMIN_TEMPLATE_ID'),
            'supportRequestMessageToAdminFromExpert' => getenv('EXPERT_SUPPORT_REQUEST_MESSAGE'),
            'supportRequestMessageToAdminFromBuyer' => getenv('BUYER_SUPPORT_REQUEST_MESSAGE'),
            'acknowledgementEmailFromMmSupport' => getenv('ACKNOWLEDGEMENT_TO_EXPERT_BUYER_SUPPORT_REQUEST_MESSAGE'),
            'sendExpertReferalEmail' => getenv('ACTIVE_REFERRAL_EXPERT_TEMPLATE_ID'),
            'sendBuyerReferalEmail' => getenv('ACTIVE_REFERRAL_EXPERT_THANKYOU_TEMPLATE_ID'),
            'sendBuyerSignupEmailToMeasureMatch' => getenv('ADMIN_BUYER_SIGN_UP_MESSAGE'),
            'sendExpertSignupEmailToMeasureMatch' => getenv('ADMIN_EXPERT_SIGN_UP_MESSAGE'),
            'servicePackageCreatedEmailToExpert' => getenv('SERVICE_PACKAGE_CREATED_EMAIL_TO_EXPERT'),
            'servicePackageCreatedEmailToAdmin' => getenv('SERVICE_PACKAGE_CREATED_EMAIL_TO_ADMIN'),
            'servicePackageDraftedEmailToAdmin' => getenv('SERVICE_PACKAGE_SAVED_TO_DRAFT_EMAIL_TO_ADMIN'),
            'servicePackageApprovedEmailToExpert' => getenv('SERVICE_PACKAGE_APPROVED_EMAIL_TO_EXPERT'),
            'servicePackageShowInterestEmailToExpert' => getenv('SERVICE_PACKAGE_SHOW_INTEREST_TO_EXPERT'),
            'servicePackageShowInterestEmailToBuyer' => getenv('SERVICE_PACKAGE_SHOW_INTEREST_TO_BUYER'),
            'servicePackageShowContractMonthlyOfferEmailToAdmin'=>getenv('SERVICE_PACKAGE_SHOW_CONTRACT_MONTHLY_OFFER_TO_ADMIN'),
            'servicePackageShowContractOneTimeOfferEmailToAdmin'=>getenv('SERVICE_PACKAGE_SHOW_CONTRACT_ONE_TIME_OFFER_TO_ADMIN'),
            'emailUpcomingServicePackageStartInKernel'=>getenv('ACTIVE_EXPERT_SERVICE_PACKAGE_START_EMAIL_TEMPLATE_ID'),
            'buyerEmailUpcomingServicePackageStart'=>getenv('ACTIVE_BUYER_SERVICE_PACKAGE_START_EMAIL_TEMPLATE_ID'),
            'expertEmailUpcomingContractEndDate'=>getenv('SERVICE_PACKAGE_UPCOMING_CONTRACT_END_DATE_EMAIL_TO_EXPERT'),
            'buyerEmailUpcomingContractEndDate'=>getenv('SERVICE_PACKAGE_UPCOMING_CONTRACT_END_DATE_EMAIL_TO_BUYER'),
            'servicePackageAvailableOnServicePackageMessageToBuyerEmail'=>getenv('AVAILABLE_ON_SERVICE_PACKAGE_EMAIL_TO_BUYER_TEMPLATE_ID'),
            'servicePackageContractOfferAcceptEmailToBuyer'=>getenv('SERVICE_PACKAGE_CONTRACT_OFFER_ACCEPTED_BY_EXPERT_EMAIL_TO_BUYER_TEMPLATE'),
            'servicePackageContractOfferAcceptEmailToExpert'=>getenv('SERVICE_PACKAGE_CONTRACT_OFFER_ACCEPTED_BY_EXPERT_EMAIL_TO_EXPERT'),
            'servicePackageContractOfferAcceptEmailToAdmin'=>getenv('SERVICE_PACKAGE_CONTRACT_OFFER_ACCEPTED_BY_EXPERT_EMAIL_TO_ADMIN_TEMPLATE'),
            'upcomingServicePackageStartEmailToAdmin'=>getenv('SERVICE_PACKAGE_UPCOMING_CONTRACT_START_EMAIL_TO_ADMIN'),
            'servicePackagecheckProgressWithBuyer'=>getenv('SERVICE_PACKAGE_CHECK_PROGRESS_AFTER_WEEK_TO_BUYER'),
            'servicePackagecheckProgressWithExpert'=>getenv('SERVICE_PACKAGE_CHECK_PROGRESS_AFTER_WEEK_TO_EXPERT'),
            'servicePackageOneTimeContractCompletedByExpertEmailToExpert'=>getenv('SERVICE_PACKAGE_ONE_TIME_CONTRACT_COMPLETION_BY_EXPERT_TO_EXPERT'),
            'servicePackageOneTimeContractCompletedByExpertEmailToBuyer'=>getenv('SERVICE_PACKAGE_ONE_TIME_CONTRACT_COMPLETION_BY_EXPERT_TO_BUYER'),
            'servicePackageOneTimeContractCompletionByBuyerToExpert'=>getenv('SERVICE_PACKAGE_ONE_TIME_CONTRACT_COMPLETION_BY_BUYER_TO_EXPERT'),
            'servicePackageOneTimeContractCompletionByBuyerToBuyer'=>getenv('SERVICE_PACKAGE_ONE_TIME_CONTRACT_COMPLETION_BY_BUYER_TO_BUYER'),
            'servicePackageOneTimeContractCompletionByBuyerToAdmin'=>getenv('SERVICE_PACKAGE_ONE_TIME_CONTRACT_COMPLETION_BY_BUYER_TO_ADMIN'),
            'servicePackageMonthlyContractFinishedByBuyerToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_BUYER_TO_EXPERT'),
            'servicePackageMonthlyContractFinishedByBuyerToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_BUYER_TO_BUYER'),
            'servicePackageMonthlyContractFinishedByExpertToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_EXPERT_TO_EXPERT'),
            'servicePackageMonthlyContractFinishedByExpertToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_EXPERT_TO_BUYER'),
            'servicePackageMonthlyContractFinishedEmailToAdmin'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_EMAIL_TO_ADMIN'),
            'servicePackageMonthlyContractFinalDayPaymentToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINAL_DAY_PAYMENT_TO_EXPERT'),
            'servicePackageMonthlyContractFinalDayPaymentToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINAL_DAY_PAYMENT_TO_BUYER'),
            'servicePackageMonthlyContractFinalDayPaymentToAdmin'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINAL_DAY_PAYMENT_TO_ADMIN'),
            'servicePackageMonthlyContractBillingDateReachedToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_BILLING_DATE_REACHED_TO_EXPERT'),
            'servicePackageMonthlyContractBillingDateReachedToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_BILLING_DATE_REACHED_TO_BUYER'),
            'servicePackageMonthlyContractBillingDateReachedToAdmin'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_BILLING_DATE_REACHED_TO_ADMIN'),
            'servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_BUYER_ON_FINAL_PAYMENT_DATE_TO_EXPERT'),
            'servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_BUYER_ON_FINAL_PAYMENT_DATE_TO_BUYER'),
            'servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToExpert'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_EXPERT_ON_FINAL_PAYMENT_DATE_TO_EXPERT'),
            'servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToBuyer'=>getenv('SERVICE_PACKAGE_MONTHLY_CONTRACT_FINISH_BY_EXPERT_ON_FINAL_PAYMENT_DATE_TO_BUYER'),
            'sendContactUsEmailToAdmin'=>getenv('CONTACT_US_EMAIL_TO_ADMIN'),
            'contractExtensionByBuyerEmailToExpert'=>getenv('CONTRACT_EXTENSION_BY_BUYER_EMAIL_TO_EXPERT'),
            'sendEmailsToOtherExpertsThatExpressedInterest'=>getenv('PROJECT_CONTRACT_ACCEPTED_BY_ANOTHER_EXPERT'),
            'contractExtensionByBuyerEmailToAdmin'=>getenv('CONTRACT_EXTENSION_BY_BUYER_EMAIL_TO_ADMIN'),
            'extendedContractUpdatedByBuyerEmailToExpert'=>getenv('EXTENDED_CONTRACT_UPDATED_BY_BUYER_EMAIL_TO_EXPERT'),
            'extendedContractUpdatedByBuyerEmailToAdmin'=>getenv('EXTENDED_CONTRACT_UPDATED_BY_BUYER_EMAIL_TO_ADMIN'),
            'extendedContractAcceptedByExpertEmailToExpert'=>getenv('EXTENDED_CONTRACT_ACCEPTED_BY_BUYER_EMAIL_TO_EXPERT'),
            'extendedContractAcceptedByExpertEmailToBuyer'=>getenv('EXTENDED_CONTRACT_ACCEPTED_BY_BUYER_EMAIL_TO_BUYER'),
            'extendedContractAcceptedByExpertEmailToAdmin'=>getenv('EXTENDED_CONTRACT_ACCEPTED_BY_BUYER_EMAIL_TO_ADMIN'),
            'extendedContractStartDateReachedToExpert'=>getenv('EXTENDED_CONTRACT_START_DATE_REACHED_TO_EXPERT'),
            'extendedContractStartDateReachedToBuyer'=>getenv('EXTENDED_CONTRACT_START_DATE_REACHED_TO_BUYER'),
            'extendedContractStartDateReachedToAdmin'=>getenv('EXTENDED_CONTRACT_START_DATE_REACHED_TO_ADMIN'),
            'extensionStartAndPreviousContractCompleteEmailToBuyer'=>getenv('EXTENSION_START_AND_PREVIOUS_CONTRACT_COMPLETE_EMAIL_TO_BUYER'),
            'extensionStartAndPreviousContractCompleteEmailToExpert'=>getenv('EXTENSION_START_AND_PREVIOUS_CONTRACT_COMPLETE_EMAIL_TO_EXPERT'),
            'extensionStartAndPreviousContractCompleteEmailToAdmin'=>getenv('EXTENSION_START_AND_PREVIOUS_CONTRACT_COMPLETE_EMAIL_TO_ADMIN'),
            'extensionContractAndMainContractCompleteEmailToBuyer'=>getenv('EXTENSION_CONTRACT_AND_MAIN_CONTRACT_COMPLETION_TO_BUYER'),
            'extensionContractAndMainContractCompleteEmailToExpert'=>getenv('EXTENSION_CONTRACT_AND_MAIN_CONTRACT_COMPLETION_TO_EXPERT'),
            'extensionContractAndMainContractCompleteEmailToAdmin'=>getenv('EXTENSION_CONTRACT_AND_MAIN_CONTRACT_COMPLETION_TO_ADMIN'),
            'projectContractCompletedByExpertEmailToExpert'=>getenv('PROJECT_CONTRACT_COMPLETED_BY_EXPERT_EMAIL_TO_EXPERT'),
            'projectContractCompletedByExpertEmailToBuyer'=>getenv('PROJECT_CONTRACT_COMPLETED_BY_EXPERT_EMAIL_TO_BUYER'),
            'projectContractCompletedByExpertEmailToAdmin'=>getenv('PROJECT_CONTRACT_COMPLETED_BY_EXPERT_EMAIL_TO_ADMIN'),
            'newProjectFollowUpEmailToExpert'=>getenv('NEW_PROJECT_FOLLOW_UP_EMAIL_TO_EXPERT'),
            'sendEmailToBuyerOnContractUpdateByBuyer'=>getenv('CONTRACT_UPDATE_BY_BUYER_EMAIL_TO_BUYER'),
            'sendEmailToExpertOnContractUpdateByBuyer'=>getenv('CONTRACT_UPDATE_BY_BUYER_EMAIL_TO_EXPERT'),
            'myDataRequestEmailToUser'=>getenv('MY_DATA_REQUEST_EMAIL_TO_USER'),
            'accountDeletionRequestEmailToUser'=>getenv('ACCOUNT_DELETION_REQUEST_EMAIL_TO_USER'),
            'newContractOfferedEmailToBuyer'=> getenv('NEW_CONTRACT_OFFERED_EMAIL_TO_BUYER'),
            'newContractCreatedByExpertToHimself' => getenv('NEW_CONTRACT_CREATED_BY_EXPERT_TO_HIMSELF'),
            'userRequestForDataEmailToAdmin'=>getenv('USER_REQUEST_FOR_DATA_EMAIL_TO_ADMIN'),
            'userRequestForAccountDeletionEmailToAdmin'=>getenv('USER_REQUEST_FOR_ACCOUNT_DELETION_EMAIL_TO_ADMIN'),
            'projectRebookEmailNotificationToExpert' => getenv('PROJECT_REBOOK_EMAIL_NOTIFICATION_TO_EXPERT'),
            'inviteExpertsByVendor' => getenv('INVITE_EXPERTS_BY_VENDOR'),
            'DeclineExpertByVendor' => getenv('DECLINE_EXPERT_BY_VENDOR'),
            'ApproveExpertByVendor' => getenv('APPROVE_EXPERT_BY_VENDOR'),
            'emailToVendorWhenApplyToServiceHub' => getenv('EMAIL_TO_VENDOR_WHEN_APPLY_TO_SERVICE_HUB')
            ];
        if (isset($config[$type])) {
            return $config[$type];
        }
        return null;
    }

    public static function prepareData($options = [], $encode = true) {
        $data = [
            'from' => isset($options['from']) ? $options['from'] : '',
            'to' => $options['to'],
            'reply_to' => isset($options['reply_to']) ? $options['reply_to'] : '',
            'template_data' => $options['template_data'],
            'template_id' => self::getTemplateId(debug_backtrace()[1]['function'])
        ];
        $data['template_data']['email_header'] = self::emailTemplateHeader();
             
        if ($encode) {
            return "'" . json_encode($data) . "'";
        }
        return $data;
    }
    
    private static function emailTemplateHeader() {
        $header_html = '<table width="100%" style="max-width:800px; background:#fff;" align="center">
    	<tr>
        	<td align="center" style="padding:20px 50px;">
            <a href="' . getHomeUrl() . '" title="MeasureMatch"><img src="' . getLogoUrl() . '" width="200" /></a>
            </td>
        </tr>
    </table>';
        return $header_html;
    }

    private static function emailTemplateFooter($email='', $show_unsubscribe_link = 1) {
        $footer_html = '<table width="100%" style="max-width:800px; background:#fff" align="center">
        <tr>
         <td align="center" style="border-top:#252161 solid 3px; margin-bottom:15px; padding-bottom:20px;">
            <p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Need more help? Visit our <a href="' . getFaqUrl() . '" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;
                text-decoration:none; color:#252161" title="click here">FAQ</a>.</p>
            <p style="padding:0px 5px; margin-top: 15px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Copyright © ' . date('Y') . ' MeasureMatch Ltd. All Rights Reserved.</p>
            <p style="padding:0px 5px; margin: 10px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
            Company Number: 10199524 | VAT Number: 253943881 | Address: 12-18 Hoxton Square, London N1 6NG, United Kingdom</p>';

        if ($show_unsubscribe_link && !empty($email)) {
            $footer_html .= '<p style="padding:0px 5px; margin-top: 15px; margin-bottom: 2px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                To unsubscribe <a href="' . getUnsubscribeUrl($email) . '" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; '
                . 'text-decoration:none; color:#252161" title="click here">click here</a>.</p>';
        }
        $footer_html .= '<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161;"'
            . ' href="' . getPrivacyPolicyLink() . '" title="Privacy Policy">Privacy Policy</a>|'
            . '<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161;"'
            . ' href="' . getTermConditionsLink() . '" title="Terms of Service">Terms of Service</a>|'
            . '<a style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-decoration:none; color:#252161;"'
            . ' href="' . getCodeOfConductLink() . '" title="Code of Conduct">Code of Conduct</a>
         </td>
      </tr>
    </table>';
        return $footer_html;
    }

    public function handle() {

        $method = $this->method;
        $data = $this->data;

        if (\method_exists($this, $method)) {
            return $this->{$method}($data);
        }
        return false;
    }

    public function emailAdminBeforeProjectStart($data) {
        $base_url = getenv('APP_URL');
        $email = getenv('ADMIN_EMAIL');
        $buyer_details = buyerInfo($data['buyer_id']);
        $project_details = getPostJobInformation($data['project_id']);
        $project_budget = $project_details['rate'];
        if ($project_budget == 0) {
            $project_budget = 'Negotiable';
        }else{
            $project_budget = convertToCurrencySymbol($data['currency']).number_format($project_details['rate'], 2);
        }
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email,
            'template_data' => [
                'admin' => getenv('CLIENT_NAME'),
                'buyer_name' => ucwords(trim($buyer_details[0]->name . ' ' . $buyer_details[0]->last_name)),
                'email' => $buyer_details[0]->email,
                'project_name' => $project_details['job_title'],
                'project_value' => $project_budget,
                'job_start_date' => date('d-m-Y', strtotime($project_details['job_start_date'])),
                'job_end_date' => date('d-m-Y', strtotime($project_details['job_end_date'])),
                'description' => $project_details['description'],
                'url' => $base_url,
                'userEmail' => $email,
                'email_footer' =>''
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function sendBuyerReferalEmail($data) {
        $base_url = getenv('APP_URL');
        $expert_information = userInfo($data['expert_id']);
        $expert_name = ucwords($expert_information[0]->name . ' ' . $expert_information[0]->last_name);
        $email = $expert_information[0]->email;
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email,
            'template_data' => [
                'expert_id' => $data['expert_id'],
                'emailTo' => $email,
                'expert_name' => $expert_name,
                'userEmail' => $email,
                'email_footer' => self::emailTemplateFooter($email)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function sendExpertReferalEmail($expert_information) {

        $expert_id = $expert_information['expert_id'];
        $email = $expert_information['referral_email'];
        $referral_expert_name = $expert_information['referral_name'];
        $ssl = getenv('APP_SSL');
        $base_url = getenv('APP_URL');
        $expert_information = userInfo($expert_id);
        if (isset($referral_expert_name) && !empty($referral_expert_name)) {
            $referral_expert_name = $referral_expert_name;
        } else {
            $referral_expert_name = '';
        }
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email,
            'template_data' => [
                'expert_id' => $expert_id,
                'emailTo' => $email,
                'expert_name' => ucwords($expert_information[0]->name . ' ' . $expert_information[0]->last_name),
                'userEmail' => $email,
                'referral_expert_name' => $referral_expert_name,
                'refer_link' => $base_url . '/referralLink?expert_id=' . urlencode(base64_encode($expert_id)) . '&email=' . urlencode(base64_encode($email)),
                'customLink' => $base_url . '/?referal_email=true',
                'email_footer' => self::emailTemplateFooter($email)
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function pendingActivationExpertEmail($expert_information) {

        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information['email'],
            'template_data' => [
                'first_name' => trim($expert_information['name']),
                'email' => $expert_information['email'],
                'last_name' => '',
                'token' => $expert_information['token'],
                'url' => $base_url,
                'userEmail' => $expert_information['email'],
                'activationLink' => $base_url . '/updateStatus?access_token=' . $expert_information['access_token'] . '&email=' . $expert_information['email'],
                'email_footer' => self::emailTemplateFooter($expert_information['email'])
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function pendingActivationBuyerEmail($buyer_detail) {

        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_detail['email'],
            'template_data' => [
                'first_name' => $buyer_detail['name'],
                'email' => $buyer_detail['email'],
                'last_name' => '',
                'token' => $buyer_detail['token'],
                'url' => $base_url,
                'userEmail' => $buyer_detail['email'],
                'activationLink' => $base_url . '/updateStatus?access_token=' . $buyer_detail['access_token'] . '&email=' . $buyer_detail['email'],
                'email_footer' => self::emailTemplateFooter($buyer_detail['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function buyerEmailUpcomingContractStart($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $job_post_title = getJob($contract_information['job_post_id'], 0);
        $contract = getContract($contract_information['contract_id']);
        $base_url = getenv('APP_URL');

        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'job_title' => $job_post_title,
                'company_name' => $buyer_information[0]['company_name'],
                'job_start_date' => date('d-m-Y', strtotime($contract[0]['job_start_date'])),
                'expert_name' => $user_information[0]['name'] . ' ' . $user_information[0]['last_name'],
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'userEmail' => $user_information[0]['email'],
                'activationLink' => getBuyerMessageLink()."/".config('constants.PROJECT')."/".$contract_information['job_post_id']."?communication_id=".$contract[0]['communications_id'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function emailUpcomingContractStartInKernel($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $job_post_title = getJob($contract_information['job_post_id'], 0);
        $contract = getContract($contract_information['contract_id']);
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'expert_name' => ucwords(trim($user_information[0]['name'] . ' ' . $user_information[0]['last_name'])),
                'job_title' => $job_post_title,
                'job_start_date' => date('d-m-Y', strtotime($contract[0]['job_start_date'])),
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'userEmail' => $user_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function emailContractEnd($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $job_post_title = getJob($contract_information['job_post_id'], 0);
        $contract = getContract($contract_information['contract_id']);
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'expert_name' => ucwords(trim($user_information[0]['name'] . ' ' . $user_information[0]['last_name'])),
                'job_title' => $job_post_title,
                'job_end_date' => date('d-m-Y', strtotime($contract[0]['job_end_date'])),
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'company_name' => $buyer_information[0]['company_name'],
                'userEmail' => $user_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function emailExpertWeekly($expert_id) {
        $base_url = getenv('APP_URL');
        $user_information = userInfo($expert_id['expert_id']);
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'expert_name' => ucwords(trim($user_information[0]['name'] . ' ' . $user_information[0]['last_name'])),
                'userEmail' => $user_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])            
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function emailBuyerWeekly($buyer_id) {
        $buyer_information = buyerInfo($buyer_id['buyer_id']);
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'userEmail' => $buyer_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])            
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function expertProfileCompletionEmailToAdmin($expert_id) {
        $email_to = getenv('ADMIN_EMAIL');

        $id = $expert_id['id'];
        $base_url = getenv('APP_URL');
        $user_information = User::findByCondition(['id' => $id], ['user_profile.remote_work', 'user_skills.skill'])->toArray();

        $skill_names = [];
        foreach ($user_information[0]['user_skills'] as $key => $skill_array) {
            $skill_names[] = $skill_array['skill']['name'];
        }
        $address=(empty($user_information[0]['user_profile']['country']))? $user_information[0]['user_profile']['current_city']:$user_information[0]['user_profile']['current_city'].", ".$user_information[0]['user_profile']['country'];
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email_to,
            'template_data' => [
                'url' => $base_url,
                'expert_email' => $user_information[0]['email'],
                'expert_name' => ucwords($user_information[0]['name'] . ' ' . $user_information[0]['last_name']),
                'description' => $user_information[0]['user_profile']['describe'],
                'daily_rate' => $user_information[0]['user_profile']['currency'] . number_format($user_information[0]['user_profile']['daily_rate']),
                'expert_type' => $user_information[0]['user_profile']['expert_type'],
                'city_country' => $address,
                'skill_names' => implode(', ', $skill_names),
                'bio' => strip_tags(html_entity_decode($user_information[0]['user_profile']['summary'], ENT_QUOTES)),
                'remote_skill' => $user_information[0]['user_profile']['remote_work']['name'],
                'email_footer' => ''            
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function expertEmailForAdminReview($expert_email) {
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_email['email'],
            'template_data' => [
                'userEmail' => $expert_email['email'],
                'email_footer' => self::emailTemplateFooter($expert_email['email'])      
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function verificationToExpert($expert_id) {
        $expert_information = userInfo($expert_id['id']);
        $ssl = getenv('APP_SSL');
        $base_url = getenv('APP_URL');
        $activationLink = $base_url . '/updateStatus?access_token=' . $expert_information[0]['access_token'] . '&email=' . $expert_information[0]['email'];
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'first_name' => $expert_information[0]['name'] . ' ' . $expert_information[0]['last_name'],
                'email' => $expert_information[0]['email'],
                'last_name' => '',
                'token' => $expert_information[0]['access_token'],
                'url' => $base_url,
                'userEmail' => $expert_information[0]['email'],
                'activationLink' => $activationLink,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'],0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function expertVerificationEmail($expert_id) {
        $ssl = getenv('APP_SSL');
        $expert_information = userInfo($expert_id['id']);
        $base_url = getenv('APP_URL');
        $activation_link = $base_url . '/updateStatus?access_token=' . $expert_information[0]['access_token'] . '&email=' . $expert_information[0]['email'];
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'first_name' => ucwords(trim($expert_information[0]['name'] . ' ' . $expert_information[0]['last_name'])),
                'email' => $expert_information[0]['email'],
                'last_name' => '',
                'token' => $expert_information[0]['access_token'],
                'url' => getHomeUrl(),
                'userEmail' => $expert_information[0]['email'],
                'activationLink' => $activation_link,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'], 0)     
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function buyerVerificationEmail($buyer_id)
    {
        $ssl = getenv('APP_SSL');
        $buyer_information = userInfo($buyer_id['id']);
        $base_url = getenv('APP_URL');
        $activation_link = $base_url . '/updateStatus?access_token=' . $buyer_information[0]['access_token'] . '&email=' . $buyer_information[0]['email'];

        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'first_name' => ucwords(trim($buyer_information[0]['name'])),
                'email' => $buyer_information[0]['email'],
                'last_name' => '',
                'token' => $buyer_information[0]['access_token'],
                'url' => getHomeUrl(),
                'userEmail' => $buyer_information[0]['email'],
                'activationLink' => $activation_link,
                'user_role' => ($buyer_information[0]['user_type_id'] == config('constants.VENDOR'))
                    ? ucfirst(config('constants.USER_ROLE.VENDOR'))
                    : ucfirst(config('constants.USER_ROLE.BUYER')),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'],0)  
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function expertApprovalEmail($expert_id) {
        $ssl = getenv('APP_SSL');
        $expert_information = userInfo($expert_id);
        $base_url = getenv('APP_URL');
        $activation_link = $base_url . '/updateStatus?access_token=' . $expert_information[0]['access_token'] . '&email=' . $expert_information[0]['email'];
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'first_name' => trim($expert_information[0]['name']),
                'email' => $expert_information[0]['email'],
                'last_name' => '',
                'token' => $expert_information[0]['access_token'],
                'url' => $base_url,
                'userEmail' => $expert_information[0]['email'],
                'activationLink' => $activation_link,
                'login' => $base_url . '/login',
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'],0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    
    public function buyerApprovalEmail($buyer_id) {
        $ssl = getenv('APP_SSL');
        $buyer_information = userInfo($buyer_id);

        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trim($buyer_information[0]['name']),
                'post_project_link' => $base_url . 'project/create',
                'user_type' => $buyer_information[0]['user_type_id'] === config('constants.BUYER') ? 'Buyer' : 'Vendor',
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'],0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    
    public function vendorApprovalEmail($vendor_id)
    {
        $vendor_information = userInfo($vendor_id);
        $base_url = getenv('APP_URL');
        $vendor_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $vendor_information[0]['email'],
            'template_data' => [
                'vendor_first_name' => trim($vendor_information[0]['name']),
                'login_link' => $base_url . 'login',
                'email_footer' => self::emailTemplateFooter($vendor_information[0]['email'],0)
            ]
        ];
        $data = self::prepareData($vendor_data, false);
        return self::sendEmail($data);
    }
    
    public function buyerAccountRejectionEmail($buyer_id) {
        $ssl = getenv('APP_SSL');
        $buyer_information = userInfo($buyer_id);
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => ucfirst(trim($buyer_information[0]['name'])),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'],0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function shareEmailWithRegisteredUser($expert_information) {

        $base_url = getenv('APP_URL');
        $project_link = $base_url . '/project_view?sellerid=' . $expert_information['post_job_id'];

        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information['expert_email'],
            'template_data' => [
                'admin' => 'James',
                'expertName' => trim($expert_information['expert_name']),
                'projectName' => getJob($expert_information['post_job_id'], 0),
                'referredExpertName' => $expert_information['referral_expert_name'],
                'projectLink' => $project_link,
                'url' => $base_url,
                'userEmail' => $expert_information['expert_email'],
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function shareEmailWithUnregisteredUser($expert_information) {

        $base_url = getenv('APP_URL');
        $project_link = $base_url . '/project_view?sellerid=' . $expert_information['post_job_id'];

        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information['expert_email'],
            'template_data' => [
                'admin' => 'James',
                'expertName' => trim($expert_information['expert_name']),
                'projectName' => getJob($expert_information['post_job_id'], 0),
                'referredExpertName' => $expert_information['referral_expert_name'],
                'projectLink' => $project_link,
                'url' => $base_url,
                'userEmail' => $expert_information['expert_email'],
                'email_footer' => self::emailTemplateFooter($expert_information['expert_email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function resetPassword($user) {

        $user_detail = userInfo($user['user_id']);
        $user_type = ucfirst(config('constants.USER_ROLE.BUYER'));
        if ($user_detail[0]['user_type_id'] == config('constants.EXPERT')) {
            $user_type =  ucfirst(config('constants.USER_ROLE.EXPERT'));
        } else if ($user_detail[0]['user_type_id'] == config('constants.VENDOR')) {
            $user_type = ucfirst(config('constants.USER_ROLE.VENDOR'));
        }
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => trim($user_detail[0]['email']),
            'template_data' => [
                'name' => ucwords(trim($user_detail[0]['name'] . ' ' . $user_detail[0]['last_name'])),
                'email' => $user_detail[0]['email'],
                'user_type' => $user_type,
                'link' => $user['link'],
                'mailFrom' => getenv('CLIENT_EMAIL'),
                'email_footer' => self::emailTemplateFooter($user_detail[0]['email'], 0)
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function referredRegisteredExpertEmail($expert_information) {
        $base_url = getenv('APP_URL');
        $project_link = $base_url . '/project_view?sellerid=' . $expert_information['post_job_id'];
        $custom_link = $base_url . '/referralLink?expert_id=' . urlencode(base64_encode($expert_information['expert_id'])) . '&email=' . urlencode(base64_encode($expert_information['expert_email']));
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information['expert_email'],
            'template_data' => [
                'admin' => getenv('CLIENT_NAME'),
                'expertName' => trim($expert_information['expert_name']),
                'projectName' => getJob($expert_information['post_job_id'], 0),
                'referredExpertName' => $expert_information['referral_expert_name'],
                'customLink' => $custom_link,
                'projectLink' => $project_link,
                'url' => $base_url,
                'userEmail' => $expert_information['expert_email'],
                'email_footer' => self::emailTemplateFooter($expert_information['expert_email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function referredUnregisteredExpertEmail($expert_information) {
        $base_url = getenv('APP_URL');
        $project_link = $base_url . '/project_view?sellerid=' . $expert_information['post_job_id'];
        $custom_link = $base_url . '/referralLink?expert_id=' . urlencode(base64_encode($expert_information['expert_id'])) . '&email=' . urlencode(base64_encode($expert_information['expert_email']));
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information['expert_email'],
            'template_data' => [
                'admin' => getenv('CLIENT_NAME'),
                'expertName' => trim($expert_information['expert_name']),
                'projectName' => getJob($expert_information['post_job_id'], 0),
                'referredExpertName' => $expert_information['referral_expert_name'],
                'customLink' => $custom_link,
                'projectLink' => $project_link,
                'url' => $base_url,
                'userEmail' => $expert_information['expert_email'],
                'email_footer' => self::emailTemplateFooter($expert_information['expert_email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function projectVerficationEmail($project_information) {
        $base_url = getenv('APP_URL');
        $user_detail = userInfo($project_information['user_id']); 
        $project_link = $base_url . 'buyer/messages/project/' . $project_information['project_id']; 
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_detail[0]['email'],
            'template_data' => [
                'first_name' => ucwords(trim($user_detail[0]['name'] . ' ' . $user_detail[0]['last_name'])),
                'project_title' => getJob($project_information['project_id'], 0),
                'project_link' => $project_link,
                'token' => $user_detail[0]['access_token'],
                'url' => $base_url,
                'user_email' => $user_detail[0]['email'],
                'activation_link' => getLoginUrl(),
                'email_footer' => self::emailTemplateFooter($user_detail[0]['email'],0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public function newProjectEmailNotification($project_information) {
        $base_url = getenv('APP_URL');
        $project_id = $project_information['project_id'];
        $project_link = $base_url . 'project_view?sellerid=' . $project_id;
        $active_expert = ['user_type_id' => 1,
            'status' => 1,
            'admin_approval_status' => 1,
            'is_deleted' => 0
        ];
        $experts = \App\Model\User::findByCondition($active_expert, [], [], ['user_profile'])->toArray();
        if (_count($experts)) {
            foreach ($experts as $expert) {
                if (hasSubscribed($expert['id'])) { 
                try {
                    $already_sent = (new OutboundEmailLog)->fetchNewProjectNotificationCount($expert['email'], $project_id);
                    if($already_sent)
                        continue;
                    $project_information = PostJob::getPostJobInformation($project_id)->get()->toArray();
                    $project_budget = $project_information[0]['rate'];
                    $project_price = convertToCurrencySymbol($project_information[0]['currency']) . number_format($project_budget);
                    if ($project_budget == 0) {
                        $project_price = 'Negotiable';
                    }
                    if ($project_information[0]['rate_variable'] == 'daily_rate') $project_price.= "/day";
                    $project_data['from'] = getenv('CLIENT_EMAIL');
                    $project_data['to'] = $expert['email'];
                    $project_data['template_data'] = [
                        'expert_first_name' => trim(ucfirst($expert['name'])),
                        'project_name' => ucfirst($project_information[0]['job_title']),
                        'project_budget' => $project_price,
                        'project_view_link' => $project_link,
                        'email_footer' => self::emailTemplateFooter($expert['email'])
                    ];
                    if (strlen($project_information[0]['description'])>150) {
                        $project_data['template_data']['trimmed_project_description'] = ['content' => ucfirst(substr($project_information[0]['description'],0,150)) ,'project_link'=>$project_link];
                    }else{
                        $project_data['template_data']['untrimmed_project_description'] = ['content' => ucfirst($project_information[0]['description'])];
                    }
                    $data = self::prepareData($project_data, false);
                    $result = self::sendEmail($data);
                    if($result->message == 'OK'){
                       $outbound_email_log = new OutboundEmailLog;
                       $outbound_email_log->email_client_message_id = $result->messageid;
                       $outbound_email_log->user_email = $expert['email'];
                       $outbound_email_log->user_id = $expert['id'];
                       $outbound_email_log->template_name = 'EXPERT_NEW_PROJECT_NOTIFICATION';
                       $outbound_email_log->post_job_id = $project_id;
                       $outbound_email_log->save();
                    }
                } catch (\Exception $e) {
                    echo "Email to " . $expert['email'] . " is not sent";
                }
                }
            }
            return true;
        }
    }
    
    private function waitingProjectApprovalEmail($buyer_project_data) {
        $base_url = getenv('APP_URL');
        $user_details = User::find($buyer_project_data['buyer_id']);
        $project_link = $base_url . '/publish_projects_view?projectid=' . $buyer_project_data['project_id'];
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_details->email,
            'template_data' => [
                'first_name' => ucwords($user_details->name),
                'project_title' => getJob($buyer_project_data['project_id'], 0),
                'project_link' => $project_link,
                'token' => $user_details->access_token,
                'email_footer' => self::emailTemplateFooter($user_details->email)
                
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }

    public static function expressionOfInterestEmailToExpert($project_data) {
        $expert_details = getUserDetails($project_data['expert_id']);
        $buyer_details = getUserDetails($project_data['buyer_id']);
        $buyer_company_name = BuyerProfile::getCompanyNameByBuyerId($project_data['buyer_id'])->company_name;
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $expert_details['email'],
            'template_data' => [
                'expert_first_name' => userName($project_data['expert_id'],0,1),
                'job_title' => trimFirstName(getJob($project_data['project_id'], 0)),
                'project_view_url' => getenv('APP_URL'). 'project_view?sellerid=' . $project_data['project_id'],
                'buyer_company_name' => trimFirstName($buyer_company_name),
                'email_footer' => self::emailTemplateFooter($expert_details['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function expressionOfInterestEmailToBuyer($project_data) {
        $base_url = getenv('APP_URL');
        $buyer_information = buyerInfo($project_data['buyer_id']);
        $expert_type = \App\Model\UserProfile::Profile($project_data['expert_id'])['expert_type'];
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => ucwords(trim($buyer_information[0]['name'])),
                'expert_name' => userName($project_data['expert_id'],1),
                'expert_type' => ($expert_type =='Consultancy')?'works in a consultancy':'is an independent consultant',
                'job_title' => getJob($project_data['project_id'], 0),
                'project_link' => $base_url . 'buyer/messages/project/' . $project_data['project_id'] . '?communication_id=' . $project_data['communication_id'],
                'expert_link' => $base_url . 'buyer/expert-profile/' . $project_data['expert_id'],
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function sendEOIMailToAdmin($project_data) {
        $base_url = getenv('APP_URL');
        $expert_details = getUserDetails($project_data['expert_id']);
        $buyer_details = getUserDetails($project_data['buyer_id']);
        $project_link = $base_url . '/admin/project/' . $project_data['project_id'];
        $project_details = getPostJobInformation($project_data['project_id']);
        $project_price = 'Negotiable';
        if (!empty($project_details['rate']) && ($project_details['rate'] != 0)) {
            $project_price = convertToCurrencySymbol($project_details['currency']) . number_format($project_details['rate'], 2);
        }
        $email_to = getenv('ADMIN_EMAIL');
        $project_email = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email_to,
            'template_data' => [
                'expert_name' => ucwords($expert_details['name']),
                'buyer_name' => ucwords($buyer_details['name']),
                'project_name' => getJob($project_data['project_id'], 0),
                'project_link' => $project_link,
                'project_price' => trim($project_price),
                'job_end_date' => date('d/m/Y', strtotime($project_details['job_end_date'])),
                'email_footer' => ''
            ]
        ];
        $data = self::prepareData($project_email, false);
        return self::sendEmail($data);
    }
    
    function sendBuyerWelcomeMail($buyer_id) {
        $buyer_information = buyerInfo($buyer_id);
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => ucwords(trim($buyer_information[0]['name'])),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    function sendVendorWelcomeMail($vendor_id) {
        $vendor_details = (new CommonFunctionsComponent)->getUserDetails($vendor_id);
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $vendor_details['email'],
            'template_data' => [
                'vendor_first_name' => trim($vendor_details['first_name']),
                'email_footer' => self::emailTemplateFooter($vendor_details['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    function sendExpertWelcomeMail($data) {
        $expert_information = userInfo($data['expert_id']);
        $ssl = getenv('APP_SSL');
        $base_url = getenv('APP_URL');
        $expert_link = $base_url . '/expert/profile-summary';
        $referral_link = $base_url . '/referExpertLink?user=' . urlencode(base64_encode($expert_information[0]['email']));
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'expertName' => ucwords(trim($expert_information[0]['name'] . ' ' . $expert_information[0]['last_name'])),
                'expertEmail' => trim($expert_information[0]['email']),
                'url' => getHomeUrl(),
                'mm_number' => $expert_information[0]['mm_unique_num'],
                'userEmail' => $expert_information[0]['email'],
                'referral_link' => $referral_link,
                'expertlink' => $expert_link,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    function userVerficationEmail($user_data) {
        $base_url = getenv('APP_URL');
        try {
            $activationLink = $base_url . '/updateStatus?access_token=' . $user_data['access_token'] . '&email=' . $user_data['user_email'];
            $email_data = [
                'from' => getenv('CLIENT_EMAIL'),
                'to' => $user_data['user_email'],
                'template_data' => [
                    'first_name' => ucfirst($user_data['first_name']),
                    'email' => $user_data['user_email'],
                    'token' => $user_data['access_token'],
                    'url' => getHomeUrl(),
                    'userEmail' => $user_data['user_email'],
                    'activationLink' => $activationLink,
                    'email_footer' => self::emailTemplateFooter($user_data['user_email'])
                ]
            ];
            $data = self::prepareData($email_data, false);
            return self::sendEmail($data);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function sendPostProjectMail($project_data) {
        $buyer_details = getUserDetails($project_data['buyer_id']);
        $post_detail = PostJob::find(trim($project_data['project_id']));
        $email_to = getenv('ADMIN_EMAIL');
        if (is_numeric($post_detail->rate) && $post_detail->rate) {
            $post_detail->rate = number_format($post_detail->rate);
        }
        $project_value = convertToCurrencySymbol($post_detail->currency) . $post_detail->rate;
        if ($post_detail->rate == 0) $project_value = "Negotiable";
        if ($post_detail->rate_variable == 'daily_rate') $project_value.= "/day";
        $skills = JobsSkill::getProjectSkillsByProjectId($post_detail->id);
        $tools = JobsSkill::getProjectToolsByProjectId($post_detail->id);
        $remote_work_details = RemoteWork::fetchRemoteInformation($post_detail->remote_id);
        $remote_skill = $remote_work_details['name'];
        $deliverables = Deliverable::findByCondtion(['post_job_id'=>$project_data['project_id'],'type'=>'project'])->toArray();
        $skill_names = $tool_names = [];
        $all_skills = $all_tools = 'No skills added';
        if(_count($skills)){
            foreach ($skills as $key => $skill) {
                $skill_names[] = $skill->name;
            }
            $all_skills = implode(', ', $skill_names);
        }
        if(_count($tools)){
            foreach ($tools as $key => $tool) {
                $tool_names[] = $tool->name;
            }
            $all_tools = implode(', ', $tool_names);
        }
        
        $company_name = BuyerProfile::getBuyerInformation($project_data['buyer_id']);
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $email_to,
            'template_data' => [
                'buyer_name' => $buyer_details['name'],
                'email' => $buyer_details['email'],
                'project_name' => $post_detail->job_title,
                'project_value' => $project_value,
                'job_end_date' => date('d M Y', strtotime($post_detail->job_end_date)),
                'description' => nl2br($post_detail->description),
                'skill_names' => $all_skills,
                'tool_names' => $all_tools,
                'company_name' => $company_name[0]['company_name'],
                'remote_skill' => $remote_skill,
                'office_location' => $post_detail->office_location,
                'deliverables' => $deliverables,
                'email_footer' =>''
                ]
            ];
        if ($post_detail->hide_company_name) {
            $email_data['template_data']['hide_company_name'] = TRUE;
        }
        if(!empty($post_detail->upload_document)){
            $all_images = [];
            $images = json_decode($post_detail->upload_document, TRUE);
            if(_count($images)){
                foreach($images as $image){
                    $all_images[] = [
                                    'href' => $image,
                                    'file_name' => getFileName($image)
                                    ];
                }
                $email_data['template_data']['uploaded_document'] = ['documents' => $all_images];
            }
        }
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public static function sendFirstPostProjectMail($project_data) {
        $buyer_details = getUserDetails($project_data['buyer_id']);
        $post_detail = PostJob::find(trim($project_data['project_id']));
        $email_to = getenv('ADMIN_EMAIL');
        if (is_numeric($post_detail->rate) && $post_detail->rate) {
            $post_detail->rate = number_format($post_detail->rate, 2);
        }
        $project_value = convertToCurrencySymbol($post_detail->currency) . $post_detail->rate;
        if ($post_detail->rate == 0) {
            $project_value = "Negotiable";
        }
        $skills = \App\Model\JobsSkill::getskillsWithJobPostId($post_detail->id);
        $remote_work_details = \App\Model\RemoteWork::fetchRemoteInformation($post_detail->remote_id);
        $remote_skill = $remote_work_details['name'];
        $skill_names = [];
        foreach ($skills as $key => $skill_array) {
            $skill_names[] = $skill_array['skill']['name'];
        }
        $buyer_info = BuyerProfile::getBuyerInformation($project_data['buyer_id']);
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $email_to,
            'template_data' => [
                'buyer_name' => trimFirstName($buyer_details['name']),
                'email' => $buyer_details['email'],
                'location' => $post_detail->office_location,
                'project_name' => $post_detail->job_title,
                'project_value' => $project_value,
                'job_end_date' => date('d M Y', strtotime($post_detail->job_end_date)),
                'description' => $post_detail->description,
                'skill_names' => implode(', ', $skill_names),
                'company_name' => trimFirstName($buyer_info[0]['company_name']),
                'remote_work' => $remote_skill,
                'email_footer' => ''
            ]
        ];

        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function projectAdminReview($project_data) {
        $buyer_information = buyerInfo($project_data['buyer_id']);
        $project_details = getPostJobInformation($project_data['project_id']);
        $project_value = convertToCurrencySymbol($project_details['currency']) . number_format($project_details['rate'],2);

        if ($project_details['rate'] == 0){$project_value = "Negotiable";}
        if ($project_details['rate_variable'] == 'daily_rate'){ $project_value.= "/day";}
        $remote_work_details = \App\Model\RemoteWork::fetchRemoteInformation($project_details['remote_id']);
        $remote_work = $remote_work_details['name'];
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'company_name' => ucwords($buyer_information[0]['company_name']),
                'company_url' => $buyer_information[0]['company_url'],
                'phone_number' => $buyer_information[0]['phone_num'],
                'email' => trim($buyer_information[0]['email']),
                'project_name' => ucwords($project_details['job_title']),
                'office_location' => strip_tags($project_details['office_location']),
                'project_duration' => convertDaysToWeeks($project_details['project_duration'])['time_frame'],
                'description' => $project_details['description'],
                'project_value' => $project_value,
                'job_end_date' => date('d M Y', strtotime($project_details['job_end_date'])),
                'remote_work' => $remote_work,
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function adminChatNotification($message_data) {
        $base_url = getenv('APP_URL');
        $message = \App\Model\Message::find($message_data['message_id']);
        $communication  = \App\Model\Communication::find($message->communications_id);
        if($communication->type == "project"){
        $project_link = $base_url . '/admin/project/' . $communication->job_post_id;    
        $project_title =  getJob($communication->job_post_id, 0);
        }else{
        $project_link = $base_url . '/admin/approvedservicepackage/' . $communication->service_package_id;    
        $project_title = getServicePackageName($communication->service_package_id,0);    
        }
       
        $sender = getUserDetails($message->sender_id);
        $receiver = getUserDetails($message->receiver_id);
        $email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_NOTIFICATION_EMAIL'),
            'template_data' => ['sender_name' => $sender['name'],
                'receiver_name' => $receiver['name'],
                'message_date' => date('d M Y', strtotime($message->created_at)),
                'message_time' => date('H:i', strtotime($message->created_at)),
                'project_name' => $project_title,
                'project_type' => ($communication->type == 'project')?"Project":"Package",
                'message' => ['content' => trim($message->msg)],
                'project_link' => $project_link,
                'email_footer' =>''
                ]
        ];
        if (!empty($message->attachment)) {
            $email_data['template_data']['attachment'] = ['link' => $message->attachment];
        }
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function emailToExpertExpressionOfInterestAccepted($project_data) {
        $user_information = userInfo($project_data['expert_id']);
        $buyer_information = buyerInfo($project_data['buyer_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information['0']->email,
            'template_data' => [
                'first_name' => ucwords(trim($user_information['0']->name)),
                'buyer_name' => userName($project_data['buyer_id'],1),
                'company_name' => trimFirstName($buyer_information[0]['company_name']),
                'message_link' => getExpertMessageLink()."?communication_id=".$project_data['communication_id'],
                'email_footer' => self::emailTemplateFooter( $user_information['0']->email)
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function emailToBuyerExpressionOfInterestAccepted($user_data) {
        $expert_details = getUserDetails($user_data['expert_id']);
        $buyer_details = getUserDetails($user_data['buyer_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_details['email'],
            'template_data' => ['expert_name' => ucwords(trim($expert_details['name'])),
                'buyerName' => ucwords(trim($buyer_details['name'])),
                'userEmail' => trim($buyer_details['email']),
                'email_footer' => self::emailTemplateFooter( $buyer_details['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function acknowledgementEmailFromMmSupport($user_id) {
        $user_information = userInfo($user_id);
        $support_email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $user_information['0']->email,
            'template_data' => [
                'user_first_name' => ucfirst(trim($user_information['0']->name)),
                'email_footer' => self::emailTemplateFooter($user_information['0']->email)
            ]
        ];
        $data = self::prepareData($support_email_data, false);
        return self::sendEmail($data);
    }

    public static function supportRequestMessageToAdminFromBuyer($message_data) {
        $user_details = getUserDetails($message_data['buyer_id']);
        $support_email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('MM_SUPPORT_EMAIL'),
            'template_data' => [
                'buyer_name' => $user_details['name'],
                'email' => $user_details['email'],
                'support_message_data' => nl2br($message_data['support_message_content']),
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($support_email_data, false);
        return self::sendEmail($data);
    }

    public static function supportRequestMessageToAdminFromExpert($message_data) {
        $user_details = getUserDetails($message_data['expert_id']);
        $support_email_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('MM_SUPPORT_EMAIL'),
            'template_data' => [
                'expert_name' => $user_details['name'],
                'email' => $user_details['email'],
                'support_message_data' => nl2br($message_data['support_message_content']),
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($support_email_data, false);
        return self::sendEmail($data);
    }

    public function sendMissedMessageEmail($message_data) {

        $message = Message::fetchMessages(['id' => $message_data['message_id']]);

        if ( $message['automated_message'] == FALSE) {
            $user_detail = getUserDetail($message['receiver_id']);
            $message_data = [
                'receiver_id' => $message['receiver_id'],
                'sender_id' => $message['sender_id'],
                'message_id' => $message_data['message_id']];
            if ($user_detail['user_type_id'] == config('constants.EXPERT') && hasSubscribed($message['receiver_id'])) {
                self::sendMissedMessageEmailToExpert($message_data);
            }
            if ($user_detail['user_type_id'] == config('constants.BUYER') && hasSubscribed($message['sender_id'])) {
                self::sendMissedMessageEmailToBuyer($message_data);
            }
        }
    }

    public static function sendMissedMessageEmailToExpert($message_data) {
        $receiver_id = $message_data['receiver_id'];
        $sender_id = $message_data['sender_id'];
        $base_url = getenv('APP_URL');
        $expert = getUserDetail($receiver_id);
        $buyer = getUserDetail($sender_id);
        $message = Message::fetchMessages(['id' => $message_data['message_id']]);
        if (strlen($message['msg']) >= 100) {
            $message_text = substr($message['msg'], 0, 100) . '...';
        } else {
            $message_text = $message['msg'];
        }
        $communication_id = ($message['communications_id']);
        $communication  = \App\Model\Communication::find($communication_id);
        $project_title = ($communication->type=='project')?getJob($communication->job_post_id, 0):getServicePackageName($communication->service_package_id,0);
        $email = $expert['email'];
        $inbound_url_key = getenv('POSTMARK_REPLY_TO_INBOUND_EMAIL_KEY');
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $email,
            'reply_to' => "$inbound_url_key+$sender_id+$receiver_id+$communication_id@inbound.postmarkapp.com",
            'template_data' => [
                'expert_first_name' => ucfirst($expert['name']),
                'buyer_name' => ucfirst($buyer['name']) . ' ' . ucfirst(substr($buyer['last_name'], 0, 1)),
                'project_name' => trimFirstName($project_title),
                'project_type' => ($communication->type == 'project')?"Project":"Package",
                'message' => ['content' => $message_text],
                'message_link' => $base_url . 'expert/messages?communication_id='.$communication_id,
                'email_footer' => self::emailTemplateFooter($email)
            ]
        ];

        if (!empty($message['attachment'])) {
            $message_content['template_data']['attachment'] = ['link' => $message['attachment']];
        }
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public static function sendMissedMessageEmailToBuyer($message_data) {
        $receiver_id = $message_data['receiver_id'];
        $sender_id = $message_data['sender_id'];
        $base_url = getenv('APP_URL');
        $buyer = getUserDetail($receiver_id);
        $expert = getUserDetail($sender_id);
        $message = Message::fetchMessages(['id' => $message_data['message_id']]);
        if (strlen($message['msg']) >= 100) {
            $message_text = substr($message['msg'], 0, 100) . '...';
        } else {
            $message_text = $message['msg'];
        }
        $communication_id = ($message['communications_id']);
        $communication  = \App\Model\Communication::find($communication_id);
        $project_title = ($communication->type == 'project') ? getJob($communication->job_post_id, 0) : getServicePackageName($communication->service_package_id,0);
        $project_id = ($communication->type == 'project') ? $communication->job_post_id : $communication->service_package_id ;
        $email = $buyer['email'];
        $inbound_url_key = getenv('POSTMARK_REPLY_TO_INBOUND_EMAIL_KEY');
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $email,
            'reply_to' => "$inbound_url_key+$sender_id+$receiver_id+$communication_id@inbound.postmarkapp.com",
            'template_data' => [
                'buyer_first_name' => ucfirst($buyer['name']),
                'expert_name' => ucfirst($expert['name']) . ' ' . ucfirst(substr($expert['last_name'], 0, 1)),
                'project_name' => trimFirstName($project_title),
                'project_type' => ($communication->type == 'project') ? "Project":"Package",
                'message' => ['content' => $message_text],
                'message_link' => getBuyerMessageLink()."/".$communication->type."/".$project_id."?communication_id=".$communication->id,
                'email_footer' => self::emailTemplateFooter($email)
            ]
        ];

        if (!empty($message['attachment'])) {
            $message_content['template_data']['attachment'] = ['link' => $message['attachment']];
        }

        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    
    public static function sendEmailToBuyerConfirmingPaymentModeInvoice($payment_data) {
        $contract = $payment_data['contract'];
        $buyer = getUserDetail($contract->buyer_id);
        $expert = getUserDetail($contract->user_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer['email'],
            'template_data' => [
                'buyer_first_name' => ucfirst($buyer['name']),
                'expert_first_name' => ucfirst($expert['name']),
                'project_name' => ucfirst($payment_data['project_detail']->job_title),
                'project_rate' => convertToCurrencySymbol($contract->rate_variable) . number_format($contract->rate),
                'start_date' => date('d-m-Y', strtotime($contract->job_start_date)),
                'end_date' => date('d-m-Y', strtotime($contract->job_end_date)),
                'deliverables' => nl2br($contract->project_deliverables),
                'email_footer' => self::emailTemplateFooter($buyer['email'])
            ]
        ];
        
        if(!empty($contract->upload_document)){
            $all_images[] = [
                            'href' => $contract->upload_document,
                            'file_name' => getFileName($contract->upload_document)
                            ];
            $email_data['template_data']['uploaded_document'] = ['documents' => $all_images];
        }
        
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function sendBuyerFeedbackNotification($contract_id) {
        $ssl = getenv('APP_SSL');
        $contract_information = getContract($contract_id);
        $buyer = userInfo($contract_information[0]->buyer_id);
        $expert = userInfo($contract_information[0]->user_id);
        $base_url = getenv('APP_URL');
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $buyer[0]->email,
            'template_data' => [
                'buyer_name' => trimFirstName(trim($buyer[0]->name . ' ' . $buyer[0]->last_name)),
                'job_title' => trimFirstName(getJob($contract_information[0]->job_post_id, 0)),
                'expert_name' => trimFirstName(trim($expert[0]->name . ' ' . $expert[0]->last_name)),
                'buyerMsgLink' => getBuyerMessageLink()."/".$contract_information[0]->type."/".$contract_information[0]->job_post_id,
                'userEmail' => $buyer[0]->email,
                'email_footer' => self::emailTemplateFooter($buyer[0]->email)
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public function sendBuyerProjectCompletionNotification($email_data) {
        $contract_information = getContract($email_data['contract_id']);
        $application_fee = $email_data['application_fee'];
        $communication = Communication::getCommunicationInformation($email_data['communication_id']);
        $buyer = userInfo($email_data['buyer_id']);
        $total_days = calculateTotalDays($contract_information[0]->job_start_date, $contract_information[0]->job_end_date);
        if (is_numeric($application_fee)) {
            $application_fee = number_format($application_fee, 2);
        }
        $commission_fee = convertToCurrencySymbol($contract_information[0]->rate_variable) . $application_fee;
        $amount_to_be_paid = $contract_information[0]->rate * (config('constants.EXPERT_SHARE') / config('constants.HUNDRED'));
        if (is_numeric($amount_to_be_paid)) {
            $amount_to_be_paid = number_format($amount_to_be_paid, 2);
        }
        $expert_fee = convertToCurrencySymbol($contract_information[0]->rate_variable) . $amount_to_be_paid;
        
        if ($contract_information[0]->is_promotional_coupon_applied == TRUE) {
            $promotional_rate = $contract_information[0]->rate - config('constants.HUNDRED');
            if (is_numeric($promotional_rate)) {
                $promotional_rate = number_format($promotional_rate, 2);
            }
            $fee_structure = convertToCurrencySymbol($contract_information[0]->rate_variable) . $promotional_rate;
            
        } else {
            $rate= (double) $contract_information[0]->rate;
            $commission_fee = convertToCurrencySymbol($contract_information[0]->rate_variable)."".number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $rate),2);
            if (is_numeric($contract_information[0]->rate)) {
                $contract_information[0]->rate = number_format($contract_information[0]->rate, 2);
            }
            $fee_structure = convertToCurrencySymbol($contract_information[0]->rate_variable) . $contract_information[0]->rate;
        }
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $buyer[0]->email,
            'template_data' => [
                'buyer_name' => ucwords(trim($buyer[0]->name . ' ' . $buyer[0]->last_name)),
                'job_title' => getJob($contract_information[0]->job_post_id, 0),
                'project_accept_date' => date('d M Y', strtotime($communication[0]->updated_at)),
                'job_start_date' => date('d M Y', strtotime($contract_information[0]->job_start_date)),
                'job_end_date' => date('d M Y', strtotime($contract_information[0]->job_end_date)),
                'total_days' => $total_days,
                'project_value' => $fee_structure,
                'expert_fee' => $expert_fee,
                'commission_fee' => $commission_fee,
                'userEmail' => $buyer[0]->email,
                'email_footer' => self::emailTemplateFooter($buyer[0]->email)
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public function sendExpertProjectCompletionNotification($email_data){
        $contract = getContract($email_data['contract_id']);
        $expert = userInfo($email_data['expert_id']);
        $rate = (float)$contract[0]->rate;
        if (is_numeric($rate)) {
            $rate = number_format($rate, 2);
        }
        $project_value = convertToCurrencySymbol($contract[0]->rate_variable) . $rate;
        $contract_infomation_rate = (double) $contract[0]->rate;
        $total_payable = number_format($contract_infomation_rate - (((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract_infomation_rate)), 2);
        $coupon_value = 0;
        $mm_commision = ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract_infomation_rate);
        $coupon_contract_information = CouponAppliedByExpert::isRefferalCouponAppliedByExpert($email_data['expert_id'], $contract[0]->id);
        if (!empty($coupon_contract_information)) {
            $mm_commision = ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract_infomation_rate) - config('constants.TWENTY');
            $total_payable = number_format(($contract_infomation_rate - ((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract_infomation_rate) + config('constants.TWENTY')), 2);
            $coupon_value = '$20';
        }
        if (is_numeric($mm_commision)) {
            $mm_commision = number_format($mm_commision, 2);
        }
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $expert[0]->email,
            'template_data' => [
                'expert_name' => ucwords(trim($expert[0]->name . ' ' . $expert[0]->last_name)),
                'job_title' => getJob($contract[0]->job_post_id, 0),
                'job_date' => date('d/m/Y', strtotime($contract[0]->job_start_date)),
                'project_value' => $project_value,
                'total_payable' => convertToCurrencySymbol($contract[0]->rate_variable) . '' . $total_payable,
                'headOfficeMapLink' => getOfficeMessageLink(),
                'userEmail' => $expert[0]->email,
                'mm_commision' => convertToCurrencySymbol($contract[0]->rate_variable).''.$mm_commision,
                'coupon_value' => $coupon_value,
                'email_footer' => self::emailTemplateFooter($expert[0]->email)
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    public function projectCompletionNotificationToAdmin($contract_id){
        $contract = getContract($contract_id);
        $buyer_details = getUserDetails($contract[0]['buyer_id']);
        $contract_payment_details = contractPaymentCalculation($contract[0]['rate'], $contract[0]['id'], $contract[0]['user_id']);
        $project= PostJob::find($contract[0]['job_post_id']);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' =>  getenv('ADMIN_EMAIL'),
            'template_data' => [
                "buyer_company_name" => BuyerProfile::getCompanyNameByBuyerId( $contract[0]['buyer_id'])->company_name,
                "buyer_email" => $buyer_details['email'],
                "buyer_name" => $buyer_details['name'],
                'project_title' => trimFirstName($project->job_title),
                'project_id' => $project->job_num,
                'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
                'promo_code' =>  $contract_payment_details['promo_code'],
                'email_footer' => ''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    
    public function sendContractEmailToAdmin($contract_details) {
        $sender = getUserDetails($contract_details['expert_id']);
        $receiver = getUserDetails($contract_details['buyer_id']);
        $buyer_profile = BuyerProfile::getBuyerInformation(trim($contract_details['buyer_id']));
        $company_name = $buyer_profile[0]['company_name'];
        $contract_information = Contract::getContractInformation($contract_details['contract_id']);
        $contract_rate = convertToCurrencySymbol($contract_information[0]['rate_variable']) . '' . number_format($contract_information[0]['rate'], 2);
        $job_start_date = date('d/m/Y', strtotime($contract_information[0]['job_start_date']));
        $job_end_date = date('d/m/Y', strtotime($contract_information[0]['job_end_date']));
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_name' => trim($sender['name']),
                'buyer_name' => $receiver['name'],
                'job_title' => $contract_details['job_title'],
                'company_name' => $company_name,
                'url' => getHomeUrl(),
                'userEmail' => getenv('ADMIN_EMAIL'),
                'contract_rate' => $contract_rate,
                'job_start_date' => $job_start_date,
                'job_end_date' => $job_end_date,
                'promo_code' => $contract_details['promo_code'],
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public function sendContractEmailToBuyer($contract_details) {
        $sender = getUserDetails($contract_details['expert_id']);
        $receiver = getUserDetails($contract_details['buyer_id']);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $receiver['email'],
            'template_data' => [
                'expert_name' => $sender['name'],
                'buyer_name' => $receiver['name'],
                'job_title' => $contract_details['job_title'],
                'url' => getHomeUrl(),
                'userEmail' => $receiver['email'],
                'email_footer' => self::emailTemplateFooter($receiver['email'], 0)
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public static function sendContractEmailToExpert($contract_details) {
        $sender = getUserDetails($contract_details['expert_id']);
        $receiver = getUserDetails($contract_details['buyer_id']);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => $sender['email'],
            'template_data' => [
                'expert_name' => $sender['name'],
                'buyer_name' => $receiver['name'],
                'job_title' => $contract_details['job_title'],
                'company_name' => $contract_details['company_name'],
                'userEmail' => $sender['email'],
                'email_footer' => self::emailTemplateFooter($sender['email'],0)
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }

    public static function emailToAdminSendContractDetail($contract_details) {

        $company_name = BuyerProfile::getCompanyNameByBuyerId($contract_details['receiver_id'])->company_name;
        $contract_information = Contract::getFirstContract($contract_details['contract_id']);
        $payment_mode = ucfirst($contract_information->payment_mode);
        $project_information = getPostJobInformation($contract_information->job_post_id);
        $contract_data = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_name' => ucwords(trim(getUserDetails($contract_details['sender_id'])['name'])),
                'buyer_name' => ucwords(trim(getUserDetails($contract_details['receiver_id'])['name'])),
                'company_name' => $company_name,
                'buyer_first_name' => ucfirst(getUserDetail($contract_details['receiver_id'])['name']),
                'project_name' => $project_information->job_title,
                'project_value' => convertToCurrencySymbol(trim($contract_information->rate_variable)) . '' . number_format($contract_information->rate, 2),
                'start_date' => date('d M Y', strtotime($contract_information->job_start_date)),
                'end_date' => date('d M Y', strtotime($contract_information->job_end_date)),
                'description' => $project_information->description,
                'payment_mode' => $payment_mode,
                'userEmail' => getenv('ADMIN_EMAIL'),
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($contract_data, false);
        return self::sendEmail($data);
    }

    public function emailToExpertOnInvite($email_data) {
        $project = PostJob::getPostJobInformation($email_data['post_job_id']);
        $project_detail = $project->first()->toArray();
        $expert_detail = getUserDetail($email_data['expert_id']);
        $buyer_detail = getUserDetail($email_data['buyer_id']);
        if ($project_detail['rate'] == 0) {
            $project_rate = 'Negotiable';
        } else {
            $project_rate = convertToCurrencySymbol($project_detail['currency']) . number_format($project_detail['rate'], 2);
        }
        $invite_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_detail['email'],
            'template_data' => ['expert_name' => ucwords(trim($expert_detail['name'])),
                'buyer_name' => ucwords(trim($buyer_detail['name'])),
                'userEmail' => $expert_detail['email'],
                'project_name' => $project_detail['job_title'],
                'project_value' => $project_rate,
                'description' => $project_detail['description'],
                'loginUrl' => getenv('APP_URL'). 'login',
                'email_footer' => self::emailTemplateFooter($expert_detail['email'])
            ]
        ];
        $data = self::prepareData($invite_data, false);
        return self::sendEmail($data);
    }

    public static function sendExpertCouponCodeMail($coupon_code_data) {
        $base_url = getenv('APP_URL');

        $referral_information = userInfo($coupon_code_data['referral_expert_id']);
        $expiry_date = '12 noon on ' . date('d M Y', strtotime('+90 days'));
        $email = $referral_information[0]->email;
        $expert_name = $referral_information[0]->name . ' ' . $referral_information[0]->last_name;
        $referral_link = $base_url . '/referExpertLink?user=' . urlencode(base64_encode($email));

        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $email,
            'template_data' => [
                'expertName' => trim($expert_name),
                'expertEmail' => $email,
                'coupon_code' => $coupon_code_data['coupon_code'],
                'expiry_date' => $expiry_date,
                'url' => getHomeUrl(),
                'referral_link' => $referral_link,
                'userEmail' => $email,
                'email_footer' => self::emailTemplateFooter($email)
            ]
        ];

        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function sendBuyerSignupEmailToMeasureMatch($user_id) {
        $buyer_details = getUserDetails($user_id);
        $company_details = BuyerProfile::getBuyerInformation($user_id);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'name' => trimFirstName($buyer_details['name']),
                'user_type' => $buyer_details['user_type_id'] === config('constants.BUYER') ? 'Client' : 'Vendor',
                'email' => $buyer_details['email'],
                'company_name' => trimFirstName($company_details[0]['company_name']),
                'company_url' => $company_details[0]['company_url'],
                'phone' => $buyer_details['phone'],
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    
    public function sendExpertSignupEmailToMeasureMatch($user_id) {
        $expert_details = getUserDetails($user_id);
                
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'name' => ucwords($expert_details['name']),
                'email' => $expert_details['email'],
                'phone' => $expert_details['phone'],
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    public function servicePackageCreatedEmailToExpert($email_data) {
        $expert_data = userInfo($email_data['expert_id']);
        $service_package_name = getServicePackageName($email_data['service_package_id'],0);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>   $expert_data[0]['email'],
            'template_data' => [
                'expert_first_name' => trim(ucwords($expert_data[0]['name'])),
                'email' => $expert_data[0]['email'],
                'service_package_title' => trim(ucwords($service_package_name)),
                'email_footer' => self::emailTemplateFooter($expert_data[0]['email'])
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    public function servicePackageCreatedEmailToAdmin($email_data) {
        $expert_details = getUserDetails($email_data['expert_id']);
        $service_package = getServicePackageDetails($email_data['service_package_id']);
        $subscription_type=($service_package['subscription_type']=="one_time_package")?"One Time":"Monthly Retainer";
        $price=($service_package['subscription_type']=="one_time_package")?"$".number_format($service_package['price'],2):"$".number_format($service_package['price'],2)." /month";
        $duration=($service_package['subscription_type']=="one_time_package")?$service_package['duration']." days":$service_package['duration']." days/month";
        $tag_list=[];
        if(_count($service_package['service_package_tags'])){
           foreach($service_package['service_package_tags'] as $key=>$tags){
              $tag_list[$key]['name']=$tags['tags']['name'];     
           } 
        }
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>  getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_name' => trimFirstName($expert_details['name']),
                'email' => $expert_details['email'],
                'name' => trimFirstName($service_package['name']),
                'description' => nl2br(ucfirst($service_package['description'])),
                'subscription_type' => $subscription_type,
                'category' => $service_package['service_package_category']['name'],
                'type' => $service_package['service_package_type']['name'],
                'tags' => $tag_list,
                'deliverables' => $service_package['deliverables'],
                'what_is_needed_from_buyer' => nl2br($service_package['buyer_remarks']),
                'price' => $price,
                'time_commitment' => $duration,
                'link_to_approve' => getApproveServicePackageLink($service_package['id']),
                'email_footer' => ''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    public function servicePackageDraftedEmailToAdmin($email_data) {
        $expert_details = getUserDetails($email_data['expert_id']);
        $service_package = getServicePackageDetails($email_data['service_package_id']);
        $subscription_type=($service_package['subscription_type']=="one_time_package")?"One Time":"Monthly Retainer";
        $price=($service_package['subscription_type']=="one_time_package")?"$".number_format($service_package['price'],2):"$".number_format($service_package['price'],2)." /month";
        $duration=($service_package['subscription_type']=="one_time_package")?$service_package['duration']." days":$service_package['duration']." days/month";
        $tag_list=[];
        if(_count($service_package['service_package_tags'])){
           foreach($service_package['service_package_tags'] as $key=>$tags){
              $tag_list[$key]['name']=$tags['tags']['name'];     
           } 
        }
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>  getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_name' => trimFirstName($expert_details['name']),
                'email' => $expert_details['email'],
                'name' => trimFirstName($service_package['name']),
                'description' => nl2br(ucfirst($service_package['description'])),
                'subscription_type' => $subscription_type,
                'category' => $service_package['service_package_category']['name'],
                'type' => $service_package['service_package_type']['name'],
                'tags' => $tag_list,
                'deliverables' => $service_package['deliverables'],
                'what_is_needed_from_buyer' => nl2br($service_package['buyer_remarks']),
                'price' => $price,
                'time_commitment' => $duration,
                'link_to_approve' => getApproveDraftServicePackageLink($service_package['id']),
                'email_footer' => ''
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }
    public function servicePackageApprovedEmailToExpert($service_package_id) {
        $service_package_name = getServicePackageName($service_package_id,0);
        $expert_email= getUserDetails(getServicePackageById($service_package_id)['user_id'])['email'];
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>  $expert_email,
            'template_data' => [
                'email' => $expert_email,
                'service_package_title' => trimFirstName($service_package_name),
                'login_url' => getenv('APP_URL').'servicepackages',
                'email_footer' => self::emailTemplateFooter($expert_email)
               
            ]
        ];
        $data = self::prepareData($message_content, false);
        return self::sendEmail($data);
    }   
    public function servicePackageShowInterestEmailToExpert($data) {
        $communication_information = Communication::getCommunicationInformation($data['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $message_link= getExpertMessageLink()."?communication_id=".$communication_information[0]->id;
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>  $expert_data[0]['email'],
            'template_data' => [
                'expert_first_name'=>trimFirstName($expert_data[0]['name']),
                'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
                'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
                'service_package_title' =>trimFirstName(getServicePackageName($data['service_package_id'],0)),
                'service_package_link'=>getExpertServicePackageLink($data['service_package_id']),
                'corresponding_message_link' => $message_link,
                'email_footer' => self::emailTemplateFooter($expert_data[0]['email'])
            ]
        ];
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
    public function servicePackageShowInterestEmailToBuyer($data) {
        $communication_information = Communication::getCommunicationInformation($data['communication_id']);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to'  =>  $buyer_data[0]['email'],
            'template_data' => [
                'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
                'service_package_title' =>trimFirstName(getServicePackageName($data['service_package_id'],0)),
                'service_package_link'=>getBuyerServicePackageLink($data['service_package_id']),
                'login_url' => getLoginUrl(),
                'email_footer' => self::emailTemplateFooter($buyer_data[0]['email'])
            ]
        ];
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
    public function servicePackageShowContractMonthlyOfferEmailToAdmin($data) {
        $communication_information = Communication::getCommunicationInformation($data['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $message_link= getExpertMessageLink()."?communication_id=".$communication_information[0]->id;
        if(!empty($buyer_data[0]['last_name'])){
          $buyer_name =  $buyer_data[0]['name']." ".$buyer_data[0]['last_name'];
        }else{
          $buyer_name =  $buyer_data[0]['name'];
        }
        if(!empty($expert_data[0]['last_name'])){
        $expert_name = $expert_data[0]['name']." ".$expert_data[0]['last_name'];
        }else{
        $expert_name = $expert_data[0]['name'];
        }
        if($data['monthly_days_commitment']==1){
          $day = "day";
        }else{
          $day = "days";
        } 
        
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_first_name'=>trimFirstName($expert_name),
                'buyer_full_name'=>trimFirstName($buyer_name),
                'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
                'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
                'payment_mode'=>ucfirst($data['payment_mode']),
                'amount'=>convertToCurrencySymbol($data['amount_type']).number_format($data['amount'],2),
                'start_date'=>date('d M Y', strtotime($data['start_date'])),
                'monthly_payment_date'=>nextBillingDateForMonthlyRetainer($data['contract_id']),
                'expected_time_commitment'=>$data['monthly_days_commitment']." ".$day,
                'service_package_title' =>trimFirstName(getServicePackageName($data['service_package_id'],0)),
                'service_package_link'=>getExpertServicePackageLink($data['service_package_id']),
                'corresponding_message_link' => $message_link,
                'url' => getHomeUrl(),
                'email_footer' =>''
            ]
        ];
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
    public function servicePackageShowContractOneTimeOfferEmailToAdmin($data) {
        $communication_information = Communication::getCommunicationInformation($data['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        if(!empty($buyer_data[0]['last_name'])){
          $buyer_name =  $buyer_data[0]['name']." ".$buyer_data[0]['last_name'];
        }else{
          $buyer_name =  $buyer_data[0]['name'];
        }
        if(!empty($expert_data[0]['last_name'])){
        $expert_name = $expert_data[0]['name']." ".$expert_data[0]['last_name'];
        }else{
        $expert_name = $expert_data[0]['name'];
        }
         
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_first_name'=>trimFirstName($expert_name),
                'buyer_full_name'=>trimFirstName($buyer_name),
                'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
                'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
                'payment_mode'=>ucfirst($data['payment_mode']),
                'amount'=>convertToCurrencySymbol($data['amount_type']).number_format($data['amount'],2),
                'start_date'=>date('d M Y', strtotime($data['start_date'])),
                'job_end_date'=>date('d M Y', strtotime($data['job_end_date'])),
                'service_package_title' =>trimFirstName(getServicePackageName($data['service_package_id'],0)),
                'url' => getHomeUrl(),
                'email_footer' =>''
            ]
        ];
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
    public function emailUpcomingServicePackageStartInKernel($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $service_package_title = trimFirstName(getServicePackageName($contract_information['service_package_id'],0));
        $contract = getContract($contract_information['contract_id']);
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'expert_name' => ucwords(trim($user_information[0]['name'])),
                'service_package_title' => $service_package_title,
                'job_start_date' => date('d-m-Y', strtotime($contract[0]['job_start_date'])),
                'buyer_name' => ucwords(trim($buyer_information[0]['name'])),
                'buyer_company_name'=>trimFirstName($buyer_information[0]['company_name']),
                'user_email' => $user_information[0]['email'],
                'login' => getExpertMessageLink()."?communication_id=".$contract_information['communication_id'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function upcomingServicePackageStartEmailToAdmin($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = userInfo($contract_information['buyer_id']);
        $service_package_title = trimFirstName(getServicePackageName($contract_information['service_package_id'],0));
        $contract = getContract($contract_information['contract_id']);
        $package_type = config('constants.ONE_TIME_PACKAGE_TEXT');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => env('ADMIN_EMAIL'),
            'template_data' => [
                'package_name' => $service_package_title,
                'package_type' => $package_type,
                'start_date' => date('d-m-Y', strtotime($contract[0]['job_start_date'])),
                'expert_name' => trimFirstName($user_information[0]['name']).' '.trimFirstName($user_information[0]['last_name']),
                'expert_email' => $user_information[0]['email'],
                'buyer_name' => trimFirstName($buyer_information[0]['name']).' '.trimFirstName($buyer_information[0]['last_name']),
                'buyer_email' => $buyer_information[0]['email'],
                'email_footer' =>''
            ]
        ];
        if($contract[0]['subscription_type']=='one_time_package'){
            $project_data['template_data']['end_date'] = ['contract_end_date' => date('d-m-Y', strtotime($contract[0]['job_end_date']))];
            $project_data['template_data']['contract_rate'] = convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2);
        }else{
            $project_data['template_data']['contract_rate'] = convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2).'/month';
        }

        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function buyerEmailUpcomingServicePackageStart($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $service_package_title = trimFirstName(getServicePackageName($contract_information['service_package_id'],0));
        $contract = getContract($contract_information['contract_id']);
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'service_package_title' => $service_package_title,
                'job_start_date' => date('d-m-Y', strtotime($contract[0]['job_start_date'])),
                'expert_name' => trimFirstName($user_information[0]['name']),
                'buyer_name' => trimFirstName($buyer_information[0]['name']),
                'user_email' => $user_information[0]['email'],
                'activation_link' => getBuyerMessageLink()."/".config('constants.SERVICE_PACKAGE')."/"
                                    .$contract_information['service_package_id']."?communication_id="
                                    .$contract_information['communication_id'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageAvailableOnServicePackageMessageToBuyerEmail($contract_information) {
        $communication_information = Communication::getCommunicationInformation($contract_information['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $message_link= getBuyerMessageLink()."/".config('constants.SERVICE_PACKAGE')."/"
                        .$communication_information[0]->service_package_id."?communication_id="
                        .$contract_information['communication_id'];
        $service_package_title = trimFirstName(getServicePackageName($communication_information[0]->service_package_id,0));
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_data[0]['email'],
            'template_data' => [
                'service_package_title' => $service_package_title,
                'company_name' => trimFirstName($buyer_data[0]['company_name']),
                'expert_name' => trimFirstName($expert_data[0]['name']),
                'buyer_name' => trimFirstName($buyer_data[0]['name']),
                'message_link'=>$message_link,
                'email_footer' => self::emailTemplateFooter($buyer_data[0]['email'])
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageContractOfferAcceptEmailToBuyer($contract_information) {
        $communication_information = Communication::getCommunicationInformation($contract_information['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $message_link= getBuyerMessageLink()."/".config('constants.SERVICE_PACKAGE')."/"
                        .$communication_information[0]->service_package_id."?communication_id="
                        .$contract_information['communication_id'];
        $service_package_title = trimFirstName(getServicePackageName($communication_information[0]->service_package_id,0));
        $base_url = getenv('APP_URL');
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_data[0]['email'],
            'template_data' => [
                'service_package_title' => trimFirstName($service_package_title),
                'expert_name' =>  trimFirstName($expert_data[0]['name']),
                'buyer_name' => trimFirstName($buyer_data[0]['name']),
                'message_link'=>$message_link,
                'email_footer' => self::emailTemplateFooter($buyer_data[0]['email'], 0)
            ]
        ];
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageContractOfferAcceptEmailToExpert($contract_information) {
        $communication_information = Communication::getCommunicationInformation($contract_information['communication_id']);
        $expert_data = expertInformation($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        
        $service_package_contract = getServicePackageContractDetails($contract_information['contract_id']);
        $subscription_type= config('constants.ONE_TIME');
        $contract_payment_details = contractPaymentCalculation($service_package_contract['rate'], $contract_information['contract_id'], $communication_information[0]->user_id);
        $work_location= expertWorkLocation($expert_data[0]['remote_id']);
        $amount = convertToCurrencySymbol($service_package_contract['rate_variable'])."".number_format($service_package_contract['rate'],2);
        $mm_commision = convertToCurrencySymbol($service_package_contract['rate_variable'])."".number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $service_package_contract['rate']),2);
        $project_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_data[0]['email'],
            'template_data' => [
                'expert_name' =>  trimFirstName($expert_data[0]['name']),
                'buyer_name' => trimFirstName($buyer_data[0]['name']),
                'service_package_title' => trimFirstName($service_package_contract['service_package']['name']),
                'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
                'subscription_type' => $subscription_type,
                'start_date'=>date('d M Y', strtotime($service_package_contract['job_start_date'])),
                'deliverables' => $service_package_contract['contract_deliverables'],
                'promo_code'=>$contract_payment_details['promo_code'],
                'work_location'=>$work_location,
                'email_footer' => self::emailTemplateFooter($expert_data[0]['email'], 0)
             ]
        ];
        if($service_package_contract['subscription_type']=='monthly_retainer'){
            $amount.= '/month';
            $mm_commision.= '/month';
            $payment_date = nextBillingDateForMonthlyRetainer($service_package_contract['id']);
            $project_data['template_data']['monthly_payment_date'] = ['payment_date' => $payment_date];
            $project_data['template_data']['billing_period'] = ['monthly_start_end_date' => date('d M Y', strtotime($service_package_contract['job_start_date'])).' to '.$payment_date];
            $project_data['template_data']['monthly_days_commitment'] = ['days_per_month' => $service_package_contract['monthly_days_commitment'].' days/month'];
        }else{
            $project_data['template_data']['amount_paid_to_expert'] = ['amount' => convertToCurrencySymbol($service_package_contract['rate_variable'])."".number_format($contract_payment_details['amount_to_be_paid'],2)];
        }
        $project_data['template_data']['total_payment_due'] = $amount;
        $project_data['template_data']['mm_commision'] = $mm_commision;
        $data = self::prepareData($project_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageContractOfferAcceptEmailToAdmin($data) {
        $communication_information = Communication::getCommunicationInformation($data['communication_id']);
        $expert_data = userInfo($communication_information[0]->user_id);
        $buyer_data  =  buyerInfo($communication_information[0]->buyer_id);
        $contract = Contract::getContractDetail($data['contract_id'])->first();
        
        $amount = convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'],2);
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'expert_first_name'=>trimFirstName(userName($communication_information[0]->user_id)),
                'buyer_full_name'=>trimFirstName(userName($communication_information[0]->buyer_id)),
                'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
                'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
                'payment_mode'=>trimFirstName($contract['payment_mode']),
                'start_date'=>date('d-m-Y', strtotime($contract['job_start_date'])),
                'service_package_title' =>trimFirstName(getServicePackageName($communication_information[0]->service_package_id,0)),
                'email_footer' => ''
            ]
        ];
        
        if($contract['subscription_type']=='monthly_retainer'){
            $amount.= '/month';
            $payment_date = nextBillingDateForMonthlyRetainer($contract['id']);
            $message_content['template_data']['monthly_payment_date'] = ['payment_date' => $payment_date];
            $message_content['template_data']['monthly_amount'] = ['amount_per_month' => $amount];
            $message_content['template_data']['monthly_days_commitment'] = ['days_per_month' => $contract['monthly_days_commitment'].' days/month'];
        }else{
            $message_content['template_data']['job_end_date'] = ['end_date' => date('d-m-Y', strtotime($contract['job_end_date']))];
            $message_content['template_data']['amount'] = ['one_time_amount' => $amount];
        }
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
     public function expertEmailUpcomingContractEndDate($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $service_package_title = trimFirstName(getServicePackageName($contract_information['service_package_id'],0));
        $contract = getContract($contract_information['contract_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'service_package_title' => $service_package_title,
                'company_name' => trimFirstName($buyer_information[0]['company_name']),
                'contract_end_date' => date('d-m-Y', strtotime($contract[0]['job_end_date'])),
                'expert_first_name' => trimFirstName($user_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'head_office_map_link' => getenv('HEAD_OFFICE_MAP_LINK'),
                'user_email' => $user_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];
        
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function buyerEmailUpcomingContractEndDate($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $service_package_title = trimFirstName(getServicePackageName($contract_information['service_package_id'],0));
        $contract = getContract($contract_information['contract_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'service_package_title' => $service_package_title,
                'contract_end_date' => date('d-m-Y', strtotime($contract[0]['job_end_date'])),
                'expert_first_name' => trimFirstName($user_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'head_office_map_link' => getenv('HEAD_OFFICE_MAP_LINK'),
                'user_email' => $user_information[0]['email'],
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackagecheckProgressWithBuyer($contract_information) {
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackagecheckProgressWithExpert($contract_information) {
        $expert_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'expert_first_name' => trimFirstName($expert_information[0]['name']),
                'buyer_company' => $buyer_information[0]['company_name'],
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageOneTimeContractCompletedByExpertEmailToExpert($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $communication_information = Communication::getCommunicationInformation($contract_information['communication_id']);
        $service_package_title = trimFirstName(getServicePackageName($communication_information[0]['service_package_id'],0));
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                'expert_first_name' => trimFirstName($user_information[0]['name']),
                'service_package_title' => $service_package_title,
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
      public function servicePackageOneTimeContractCompletedByExpertEmailToBuyer($contract_information) {
        $user_information = userInfo($contract_information['expert_id']);
        $buyer_information = buyerInfo($contract_information['buyer_id']);
        $communication_information = Communication::getCommunicationInformation($contract_information['communication_id']);
        $service_package_title = trimFirstName(getServicePackageName($communication_information[0]['service_package_id'],0));
        $message_link= getBuyerMessageLink()."/".config('constants.SERVICE_PACKAGE')."/"
                        .$communication_information[0]['service_package_id']."?communication_id="
                        .$contract_information['communication_id'];
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'expert_first_name' => trimFirstName($user_information[0]['name']),
                'service_package_title' => $service_package_title,
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'message_link' => $message_link,
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageOneTimeContractCompletionByBuyerToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'expert_amount' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'mm_commission' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
   
    public function servicePackageOneTimeContractCompletionByBuyerToBuyer($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'expert_amount' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'mm_commission' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
       ];
       
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
   }
   
    public function servicePackageOneTimeContractCompletionByBuyerToAdmin($contract_id){
       $contract = Contract::getContractDetail($contract_id)->first();
       $buyer_data  =  buyerInfo($contract['buyer_id']);
       $message_content = [
           'from' => getenv('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'expert_name'=>trimFirstName(userName($contract['user_id'])),
               'buyer_name'=>trimFirstName(userName($contract['buyer_id'])),
               'buyer_email'=> $buyer_data[0]['email'],
               'rate'=> convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'], 2),
               'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
               'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
               'payment_mode'=>trimFirstName($contract['payment_mode']),
               'start_date'=>date('d M Y', strtotime($contract['job_start_date'])),
               'end_date'=>date('d M Y', strtotime($contract['job_end_date'])),
               'service_package_name' =>trimFirstName(getServicePackageName($contract['service_package_id'],0)),
               'email_footer' => ''
           ]
       ];
       $email_data = self::prepareData($message_content, false);
       return self::sendEmail($email_data);
   }
    public function servicePackageMonthlyContractFinishedByBuyerToExpert($contract_id){
       $contract = Contract::getContractDetail($contract_id)->first();
       $buyer_data  =  buyerInfo($contract['buyer_id']);
       $expert_data= userInfo($contract['user_id']);
       $message_content = [
           'from' => getenv('CLIENT_EMAIL'),
           'to' => $expert_data[0]['email'],
           'template_data' => [
               'expert_first_name'=>trimFirstName($expert_data[0]['name']),
               'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
               'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
               'rate'=> convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'], 2).'/month',
               'final_payment_date'=> date('d M Y', strtotime($contract['job_end_date'])),
               'days_per_month'=> $contract['monthly_days_commitment'].' days/month',
               'service_package_name' =>trimFirstName(getServicePackageName($contract['service_package_id'],0)),
               'email_footer' => self::emailTemplateFooter($expert_data[0]['email'])
           ]
       ];
       $email_data = self::prepareData($message_content, false);
       return self::sendEmail($email_data);
   }
    public function servicePackageMonthlyContractFinishedByBuyerToBuyer($contract_id){
       $contract = Contract::getContractDetail($contract_id)->first();
       $buyer_data  =  buyerInfo($contract['buyer_id']);
       $expert_data= userInfo($contract['user_id']);
       $message_content = [
           'from' => getenv('CLIENT_EMAIL'),
           'to' => $buyer_data[0]['email'],
           'template_data' => [
               'expert_first_name'=>trimFirstName($expert_data[0]['name']),
               'buyer_first_name'=>trimFirstName($buyer_data[0]['name']),
               'buyer_company_name'=>trimFirstName($buyer_data[0]['company_name']),
               'rate'=> convertToCurrencySymbol($contract['rate_variable']).number_format($contract['rate'], 2).'/month',
               'final_payment_date'=> date('d M Y', strtotime($contract['job_end_date'])),
               'days_per_month'=> $contract['monthly_days_commitment'].' days/month',
               'service_package_name' =>trimFirstName(getServicePackageName($contract['service_package_id'],0)),
               'email_footer' => self::emailTemplateFooter($buyer_data[0]['email'])
           ]
       ];
       $email_data = self::prepareData($message_content, false);
       return self::sendEmail($email_data);
   }
    public function servicePackageMonthlyContractFinishedByExpertToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2).'/month',
               'home_url' => getHomeUrl(),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name'=>trimFirstName($buyer_information[0]['company_name']),
               'final_payment_date'=>date('d M Y', strtotime($contract[0]['job_end_date'])),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedByExpertToBuyer($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2).'/month',
               'home_url' => getHomeUrl(),
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'expert_name'=> trimFirstName($expert_information[0]['name']).' '.trimFirstName($expert_information[0]['last_name']),
               'final_payment_date'=>date('d M Y', strtotime($contract[0]['job_end_date'])),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedEmailToAdmin($contract_data){
       $contract = getContract($contract_data['contract_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_subject=($contract_data['finished_by'] === config('constants.EXPERT'))?"An Expert has cancelled a monthly retainer Service Package with a buyer":"A Client has cancelled a monthly retainer Service Package";
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'service_package_name' => $service_package_title,
               'email_subject' => $email_subject,
               'finished_by' =>($contract_data['finished_by'] === config('constants.EXPERT'))?"Expert":"Client",
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2).'/month',
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'home_url' => getHomeUrl(),
               'buyer_email' => $buyer_information[0]['email'],
               'expert_email' => $expert_information[0]['email'],
               'expert_name'=> trimFirstName($expert_information[0]['name']).' '.trimFirstName($expert_information[0]['last_name']),
               'buyer_name'=> trimFirstName($buyer_information[0]['name']).' '.trimFirstName($buyer_information[0]['last_name']),
               'final_payment_date'=>date('d M Y', strtotime($contract[0]['job_end_date'])),
               'start_date'=>date('d M Y', strtotime($contract[0]['job_start_date'])),
               'email_footer' =>''
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinalDayPaymentToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_date = nextBillingDateForMonthlyRetainer($contract_id);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'home_url' => getHomeUrl(),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'billing_period' => date('d M Y', strtotime($contract[0]['job_start_date'])).' to '.$payment_date,
               'mm_commission' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinalDayPaymentToBuyer($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = userInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_date = nextBillingDateForMonthlyRetainer($contract_id);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'home_url' => getHomeUrl(),
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'billing_period' => date('d M Y', strtotime($contract[0]['job_start_date'])).' to '.$payment_date,
               'mm_commission' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinalDayPaymentToAdmin($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'home_url' => getHomeUrl(),
               'buyer_name'=> trimFirstName($buyer_information[0]['name']).' '.trimFirstName($buyer_information[0]['last_name']),
               'expert_name'=> trimFirstName($expert_information[0]['name']).' '.trimFirstName($expert_information[0]['last_name']),
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'buyer_company_name'=>trimFirstName($buyer_information[0]['company_name']),
               'buyer_email' => $buyer_information[0]['email'],
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'final_payment_date'=>date('d M Y', strtotime($contract[0]['job_end_date'])),
               'payment_mode'=>trimFirstName($contract[0]['payment_mode']),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => ''
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractBillingDateReachedToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
               'buyer_company_name'=>trimFirstName($buyer_information[0]['company_name']),
               'service_package_name' => $service_package_title,
               'start_date'=>date('d M Y', strtotime($contract[0]['job_start_date'])),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'mm_commision'=> convertToCurrencySymbol($contract[0]['rate_variable'])."".number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract[0]['rate']),2),
               'billing_period'=>date('d M Y', strtotime($contract[0]['job_start_date']))." to ".date('d M Y'),
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractBillingDateReachedToBuyer($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'expert_first_name' => trimFirstName($expert_information[0]['name']),
                'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
                'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
                'service_package_name' => $service_package_title,
                'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
                'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
                'days_per_month' => $contract[0]['monthly_days_commitment'] . ' days/month',
                'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']) . "" . number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract[0]['rate']), 2),
                'billing_period'=>date('d M Y', strtotime($contract[0]['job_start_date']))." to ".date('d M Y'),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractBillingDateReachedToAdmin($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
               'buyer_email' => $buyer_information[0]['email'],
               'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'monthly_payment_date'=>date('d M Y'),
               'payment_mode'=>trimFirstName($contract[0]['payment_mode']),
               'start_date'=>date('d M Y', strtotime($contract[0]['job_start_date'])),
               'email_footer' =>''
            ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'expert_first_name' => trimFirstName($expert_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'service_package_name' => $service_package_title,
                'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
                'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
                'days_per_month' => $contract[0]['monthly_days_commitment'] . ' days/month',
                'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']) . "" . number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract[0]['rate']), 2),
                'billing_period'=>date('d M Y', strtotime($contract[0]['job_start_date']))." to ".date('d M Y'),
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToBuyer($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'expert_first_name' => trimFirstName($expert_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'service_package_name' => $service_package_title,
                'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
                'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
                'days_per_month' => $contract[0]['monthly_days_commitment'] . ' days/month',
                'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']) . "" . number_format(((config('constants.MM_SHARE') / config('constants.HUNDRED')) * $contract[0]['rate']), 2),
                'billing_period'=>date('d M Y', strtotime($contract[0]['job_start_date']))." to ".date('d M Y'),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToExpert($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_date = nextBillingDateForMonthlyRetainer($contract_id);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'home_url' => getHomeUrl(),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'billing_period' => date('d M Y', strtotime($contract[0]['job_start_date'])).' to '.$payment_date,
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToBuyer($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = userInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $service_package_title = trimFirstName(getServicePackageName($contract[0]['service_package_id'],0));
       $payment_date = nextBillingDateForMonthlyRetainer($contract_id);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'service_package_name' => $service_package_title,
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'home_url' => getHomeUrl(),
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'billing_period' => date('d M Y', strtotime($contract[0]['job_start_date'])).' to '.$payment_date,
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'days_per_month' => $contract[0]['monthly_days_commitment'].' days/month',
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }    
    
    public function sendContactUsEmailToAdmin($data) {
        $message_content = [
            'from' => getenv('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'first_name' => trimFirstName($data['first_name']),
                'email' => $data['email'],
                'message' => $data['message'],
                'email_footer' => ''
            ]
        ];
        $email_data = self::prepareData($message_content, false);
        return self::sendEmail($email_data);
    }
    public function contractExtensionByBuyerEmailToExpert($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_name = ($contract[0]['type']=='project')? getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0);
       $message_link= getExpertMessageLink()."?communication_id=".$contract[0]['communications_id'];
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName($project_name),
               'message_link' => $message_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    } 
    public function sendEmailsToOtherExpertsThatExpressedInterest($project_detail){
        $project_id = $project_detail['project_id'];
        $communications_detail = Communication::fetchCommunications(['job_post_id' => $project_id]);
        $project_name = getJob($project_id, 0);
        if(_count($communications_detail)){
            foreach ($communications_detail as $communication) {
                if (hasSubscribed($communication['user_id'])) {
                    if($communication['id'] != $project_detail['communication_id_of_accepted_contract']){
                        $expert_detail = getUserDetail($communication['user_id']);
                        try {
                            $email_data = [
                                'from' => env('CLIENT_EMAIL'),
                                'to' => $expert_detail['email'],
                                'template_data' => [
                                    'expert_first_name' => trimFirstName($expert_detail['name']),
                                    'date_of_expression' => date('d M Y', strtotime($communication['created_at'])),
                                    'project_name' => trimFirstName($project_name),
                                    'email_footer' => self::emailTemplateFooter($expert_detail['email'])
                                ]
                            ];
                            $data = self::prepareData($email_data, false);
                            self::sendEmail($data);
                        } catch (\Exception $e) {
                            echo "Email to " . $expert_detail['email'] . " is not sent";
                        }
                    }
                }
            }
        }
    } 
    public function contractExtensionByBuyerEmailToAdmin($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_name = ($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0);
       $rate = preg_replace('/\D/', '', $contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
               'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName($project_name),
               'payment_mode' => trimFirstName($contract[0]['payment_mode']),
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'end_date' => date('d M Y', strtotime($contract[0]['job_end_date'])),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($rate, 2),
               'email_footer' =>''
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    } 
     public function extendedContractUpdatedByBuyerEmailToExpert($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_name = ($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0);
       $message_link= getExpertMessageLink()."?communication_id=".$contract[0]['communications_id'];
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName($project_name),
               'message_link' => $message_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractUpdatedByBuyerEmailToAdmin($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_name = ($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
               'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName($project_name),
               'payment_mode' => trimFirstName($contract[0]['payment_mode']),
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'end_date' => date('d M Y', strtotime($contract[0]['job_end_date'])),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($contract[0]['rate'], 2),
               'email_footer' => ''
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractAcceptedByExpertEmailToExpert($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_link = ($contract[0]['type']=='project')? getenv('APP_URL'). '/project_view?sellerid='. $contract[0]['job_post_id'] :getenv('APP_URL'). '/servicepackage/detail/'.$contract[0]['service_package_id'];
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       if($contract[0]['type']=='project'){
       $project_deliverables = trimFirstName($contract[0]['project_deliverables']); 
       }else{
       $service_package_contract = getServicePackageContractDetails($contract_id);
       $deliverables = $service_package_contract['contract_deliverables'];
       }
       
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'project_link' => $project_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'], 0)
           ]
       ];
       if (isset($project_deliverables)) {
            $email_data['template_data']['project_deliverables'] = ['content' => $project_deliverables];
       }
       if (isset($deliverables)) {
            $email_data['template_data']['sp_deliverables'] = ['deliverables' => $deliverables];
       }
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractAcceptedByExpertEmailToBuyer($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_link = ($contract[0]['type']=='project')? getenv('APP_URL'). 'buyer/messages/project/'. $contract[0]['job_post_id'] : getenv('APP_URL'). '/servicepackage/'.$contract[0]['service_package_id'];
       $project_id = ($contract[0]['type']=='project')?  $contract[0]['job_post_id'] : $contract[0]['service_package_id'];
       $project_link =  getenv('APP_URL'). "buyer/messages/".$contract[0]['type']."/". $project_id."?communication_id=".$contract[0]['communications_id'];
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       if($contract[0]['type']=='project'){
       $project_deliverables = trimFirstName($contract[0]['project_deliverables']); 
       }else{
       $service_package_contract = getServicePackageContractDetails($contract_id);
       $deliverables = $service_package_contract['contract_deliverables'];
       }
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'project_link' => $project_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'], 0)
           ]
       ];
       if (isset($project_deliverables)) {
            $email_data['template_data']['project_deliverables'] = ['content' => $project_deliverables];
       }
       if (isset($deliverables)) {
            $email_data['template_data']['sp_deliverables'] = ['deliverables' => $deliverables];
       }
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractAcceptedByExpertEmailToAdmin($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       if($contract[0]['type']=='project'){
       $project_deliverables = trimFirstName($contract[0]['project_deliverables']); 
       }else{
       $service_package_contract = getServicePackageContractDetails($contract_id);
       $deliverables = $service_package_contract['contract_deliverables'];
       }
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_name' => trimFirstName(userName($contract[0]['buyer_id'])),
               'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'payment_mode' => trimFirstName($contract[0]['payment_mode']),
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => ''
           ]
       ];
       if (isset($project_deliverables)) {
            $email_data['template_data']['project_deliverables'] = ['content' => $project_deliverables];
       }
       if (isset($deliverables)) {
            $email_data['template_data']['sp_deliverables'] = ['deliverables' => $deliverables];
       }
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractStartDateReachedToExpert($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_link = ($contract[0]['type']=='project')? getenv('APP_URL'). '/project_view?sellerid='. $contract[0]['job_post_id'] :getenv('APP_URL'). '/servicepackage/detail/'.$contract[0]['service_package_id'];
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'contract_name' => trimFirstName($contract[0]['alias_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'project_link' => $project_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'end_date' => date('d M Y', strtotime($contract[0]['job_end_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractStartDateReachedToBuyer($contract_id){
       $contract = getContract($contract_id);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $expert_information = userInfo($contract[0]['user_id']);
       $project_link = ($contract[0]['type']=='project')? getenv('APP_URL'). '/project_view?sellerid='. $contract[0]['job_post_id'] :getenv('APP_URL'). '/servicepackage/detail/'.$contract[0]['service_package_id'];
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'expert_name' => trimFirstName(userName($contract[0]['user_id'])),
               'contract_name' => trimFirstName($contract[0]['alias_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'project_link' => $project_link,
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'end_date' => date('d M Y', strtotime($contract[0]['job_end_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
       ];
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extendedContractStartDateReachedToAdmin($contract_id){
       $contract = getContract($contract_id);
       $expert_information = userInfo($contract[0]['user_id']);
       $buyer_information = buyerInfo($contract[0]['buyer_id']);
       $payment_calculation = contractPaymentCalculationWithoutCoupon($contract[0]['rate']);
       if($contract[0]['type']=='project'){
       $project_unique_number = PostJob::find($contract[0]['job_post_id'],['job_num'])->job_num; 
       }
       $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' =>  getenv('ADMIN_EMAIL'),
           'template_data' => [
               'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
               'expert_first_name' => trimFirstName($expert_information[0]['name']),
               'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
               'project_name' => trimFirstName(($contract[0]['type']=='project')?getJob($contract[0]['job_post_id'], 0):getServicePackageName($contract[0]['service_package_id'],0)),
               'project_type' => ($contract[0]['type']=='project')?"Project":"Package",
               'start_date' => date('d M Y', strtotime($contract[0]['job_start_date'])),
               'end_date' => date('d M Y', strtotime($contract[0]['job_end_date'])),
               'mm_commision' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['mm_fee'], 2),
               'payable_to_expert' => convertToCurrencySymbol($contract[0]['rate_variable']).number_format($payment_calculation['amount_to_be_paid_to_expert'], 2),
               'rate' => convertToCurrencySymbol($contract[0]['rate_variable']) . number_format($contract[0]['rate'], 2),
               'email_footer' => ''
           ]
       ];
       if (isset($project_unique_number)) {
            $email_data['template_data']['project_id'] = ['project_number' => $project_unique_number];
       }
       $data = self::prepareData($email_data, false);
       return self::sendEmail($data);
    }
    public function extensionStartAndPreviousContractCompleteEmailToBuyer($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $expert_information = userInfo($current_contract->user_id);
        $total_contracts = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], [], 'count');
        $latest_contract = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], ['order_by' => ['created_at', 'desc']], 'first'); 
        $current_contract_payment_details = contractPaymentCalculationWithoutCoupon($current_contract->rate);
        $upcoming_contract_payment_details = contractPaymentCalculationWithoutCoupon($latest_contract->rate);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
                "buyer_first_name" => trimFirstName($buyer_information[0]['name']),
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                "count" => makeOrdinalNumber($total_contracts-1),
                "type" => ($current_contract->type=='service_package')?'service package':'project',
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "current_contract_alias_name" => ucfirst($current_contract->alias_name),
                "current_contract_start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "current_contract_end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "current_contract_rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                "current_contract_revenue" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "current_contract_mm_commision" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['mm_fee'], 2),
                "upcoming_contract_alias_name" => ucfirst($latest_contract->alias_name),
                "upcoming_contract_start_date" => date('d M Y', strtotime($latest_contract->job_start_date)),
                "upcoming_contract_end_date" => date('d M Y', strtotime($latest_contract->job_end_date)),
                "upcoming_contract_rate" => convertToCurrencySymbol($latest_contract->rate_variable) . number_format($latest_contract->rate, 2),
                "upcoming_contract_revenue" => convertToCurrencySymbol($latest_contract->rate_variable).number_format($upcoming_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "upcoming_contract_mm_commision" => convertToCurrencySymbol($latest_contract->rate_variable).number_format($upcoming_contract_payment_details['mm_fee'], 2),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function extensionStartAndPreviousContractCompleteEmailToExpert($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_information = userInfo($current_contract->user_id);
        $total_contracts = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], [], 'count');
        $latest_contract = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], ['order_by' => ['created_at', 'desc']], 'first'); 
        $current_contract_payment_details = contractPaymentCalculationWithoutCoupon($current_contract->rate);
        $upcoming_contract_payment_details = contractPaymentCalculationWithoutCoupon($latest_contract->rate);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                "count" => makeOrdinalNumber($total_contracts-1),
                "type" => ($current_contract->type=='service_package')?'service package':'project',
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "current_contract_alias_name" => ucfirst($current_contract->alias_name),
                "current_contract_start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "current_contract_end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "current_contract_rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                "current_contract_revenue" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "current_contract_mm_commision" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['mm_fee'], 2),
                "upcoming_contract_alias_name" => ucfirst($latest_contract->alias_name),
                "upcoming_contract_start_date" => date('d M Y', strtotime($latest_contract->job_start_date)),
                "upcoming_contract_end_date" => date('d M Y', strtotime($latest_contract->job_end_date)),
                "upcoming_contract_rate" => convertToCurrencySymbol($latest_contract->rate_variable) . number_format($latest_contract->rate, 2),
                "upcoming_contract_revenue" => convertToCurrencySymbol($latest_contract->rate_variable).number_format($upcoming_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "upcoming_contract_mm_commision" => convertToCurrencySymbol($latest_contract->rate_variable).number_format($upcoming_contract_payment_details['mm_fee'], 2),
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function extensionStartAndPreviousContractCompleteEmailToAdmin($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_details = getUserDetails($current_contract->user_id);
        $buyer_details = getUserDetails($current_contract->buyer_id);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
                'expert_name' => ucwords($expert_details['name']),
                'buyer_name' => ucwords($buyer_details['name']),
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "type" => ($current_contract->type=='service_package')?'Service Package':'Project',
                "contract_alias_name" => ucfirst($current_contract->alias_name),
                "buyer_company_name" => BuyerProfile::getCompanyNameByBuyerId($current_contract->buyer_id)->company_name,
                "buyer_email" => $buyer_details['email'],
                "contract_number" => explode(' ', $current_contract->alias_name)['1'],
                "start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                'email_footer' =>''
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function extensionContractAndMainContractCompleteEmailToBuyer($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $expert_information = userInfo($current_contract->user_id);
        $total_contracts = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], [], 'count');
        $current_contract_payment_details = contractPaymentCalculationWithoutCoupon($current_contract->rate);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $buyer_information[0]['email'],
           'template_data' => [
                "buyer_first_name" => trimFirstName($buyer_information[0]['name']),
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                "count" => makeOrdinalNumber($total_contracts),
                "type" => ($current_contract->type=='service_package')?'service package':'project',
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "current_contract_alias_name" => ucfirst($current_contract->alias_name),
                "current_contract_start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "current_contract_end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "current_contract_rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                "current_contract_revenue" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "current_contract_mm_commision" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['mm_fee'], 2),
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function extensionContractAndMainContractCompleteEmailToExpert($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_information = userInfo($current_contract->user_id);
        $current_contract_payment_details = contractPaymentCalculationWithoutCoupon($current_contract->rate);
        $all_contracts = Contract::findByCondition(['communications_id' => $current_contract->communications_id], [], ['order_by' => ['created_at', 'asc']]);
        $total_expert_revenue = 0;
        $count = 0;
        foreach($all_contracts as $contract){
            $count++;
            if($count==1){
                $first_contract_start_date = $contract->job_start_date;
            }
            if(_count($all_contracts) == $count){
                $last_contract_end_date = $contract->job_end_date;
            }
            $total_expert_revenue+= contractPaymentCalculationWithoutCoupon($contract->rate)['amount_to_be_paid_to_expert'];
        }
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => $expert_information[0]['email'],
           'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                "type" => ($current_contract->type=='service_package')?'service package':'project',
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "current_contract_alias_name" => ucfirst($current_contract->alias_name),
                "current_contract_start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "current_contract_end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "current_contract_rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                "current_contract_revenue" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['amount_to_be_paid_to_expert'], 2),
                "current_contract_mm_commision" => convertToCurrencySymbol($current_contract->rate_variable).number_format($current_contract_payment_details['mm_fee'], 2),
                "total_of_all_contracts" => convertToCurrencySymbol($current_contract->rate_variable).number_format($total_expert_revenue, 2),
                "first_contract_start_date" => date('d M Y', strtotime($first_contract_start_date)),
                "last_contract_end_date" => date('d M Y', strtotime($last_contract_end_date)),
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function extensionContractAndMainContractCompleteEmailToAdmin($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_details = getUserDetails($current_contract->user_id);
        $buyer_details = getUserDetails($current_contract->buyer_id);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('ADMIN_EMAIL'),
           'template_data' => [
                'expert_name' => ucwords($expert_details['name']),
                'buyer_name' => ucwords($buyer_details['name']),
                "job_title" => ($current_contract->type!='service_package')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id, 0),
                "type" => ($current_contract->type=='service_package')?'Service Package':'Project',
                "contract_alias_name" => ucfirst($current_contract->alias_name),
                "buyer_company_name" => BuyerProfile::getCompanyNameByBuyerId($current_contract->buyer_id)->company_name,
                "buyer_email" => $buyer_details['email'],
                "start_date" => date('d M Y', strtotime($current_contract->job_start_date)),
                "end_date" => date('d M Y', strtotime($current_contract->job_end_date)),
                "rate" => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                'email_footer' =>''
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function projectContractCompletedByExpertEmailToExpert($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_information = userInfo($current_contract->user_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'project_name' => trimFirstName(getJob($current_contract->job_post_id, 0)),
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function projectContractCompletedByExpertEmailToBuyer($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_information = userInfo($current_contract->user_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'project_name' => trimFirstName(getJob($current_contract->job_post_id, 0)),
                'message_link' => getBuyerMessageLink()."/".config('constants.PROJECT')."/"
                                .$current_contract->job_post_id."?communication_id="
                                .$current_contract->communications_id,
                'email_footer' => self::emailTemplateFooter($buyer_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function projectContractCompletedByExpertEmailToAdmin($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $expert_details = getUserDetails($current_contract->user_id);
        $buyer_details = getUserDetails($current_contract->buyer_id);
        $project= PostJob::find($current_contract->job_post_id);
        $email_data = [
           'from' => env('CLIENT_EMAIL'),
           'to' => getenv('EMAIL_CONTACT'),
           'template_data' => [
                'expert_name' => trimFirstName($expert_details['name']),
                'project_title' => ($current_contract->type=='project')?getJob($current_contract->job_post_id, 0):getServicePackageName($current_contract->service_package_id,0),
                'buyer_company' => BuyerProfile::getCompanyNameByBuyerId($current_contract->buyer_id)->company_name,
                'buyer_name' => trimFirstName($buyer_details['name']),
                'buyer_email' => $buyer_details['email'],
                'expert_email' => $expert_details['email'],
                'rate' => convertToCurrencySymbol($current_contract->rate_variable) . number_format($current_contract->rate, 2),
                'type' => ($current_contract->type == 'project')?"project":"package",
                'email_footer' => ''
           ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function buyerAccountForAdminReview($buyer_id) {
        $buyer_information = buyerInfo($buyer_id);
        $pending_buyer = getenv('APP_URL') . '/admin/buyerPendingView/' . $buyer_id;
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => getenv('ADMIN_EMAIL'),
            'template_data' => [
                'buyer_name' => ucwords(trim($buyer_information[0]['name'] . ' ' . $buyer_information[0]['last_name'])),
                'company_name' => ucwords($buyer_information[0]['company_name']),
                'company_url' => $buyer_information[0]['company_url'],
                'phone' => $buyer_information[0]['phone_num'],
                'email' => trim($buyer_information[0]['email']),
                'pending_buyer' => $pending_buyer,
                'login_url' => $buyer_information[0]['user_type_id'] == config('constants.BUYER') ? $pending_buyer : '',
                'user_type' => $buyer_information[0]['user_type_id'] == config('constants.BUYER') ? 'Client' : 'Vendor',
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function newContractOfferedEmailToBuyer($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $is_project = $current_contract->type == 'project' ? true : false;
        $link_prefix = $is_project ? 'buyer/messages/project/'.$current_contract->job_post_id : 'buyer/messages/service_package/'.$current_contract->service_package_id;
        $expert_information = userInfo($current_contract->user_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $project_name = $is_project ? ' your project, ' : ' the Service Package, ';
        $project_title = $is_project ? trimFirstName(getJob($current_contract->job_post_id, 0)) : trimFirstName(getServicePackage($current_contract->service_package_id)[0]->name);
        $project_name = $project_name.$project_title;
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $buyer_information[0]['email'],
            'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'project_name' => $project_name,
                'message_link' => getenv('APP_URL').$link_prefix."?communication_id=".$current_contract->communications_id,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public static function newContractCreatedByExpertToHimself($contract_id) {
        $current_contract = Contract::getFirstContract($contract_id);
        $is_project = $current_contract->type == 'project' ? true : false;
        $type = $current_contract->type == 'project' ? 'project' : 'package';
        $expert_information = userInfo($current_contract->user_id);
        $buyer_information = buyerInfo($current_contract->buyer_id);
        $project_title = $is_project ? trimFirstName(getJob($current_contract->job_post_id, 0)) : trimFirstName(getServicePackage($current_contract->service_package_id)[0]->name);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                "expert_first_name" => trimFirstName($expert_information[0]['name']),
                'buyer_first_name' => trimFirstName($buyer_information[0]['name']),
                'buyer_company_name' => trimFirstName($buyer_information[0]['company_name']),
                'project_name' => $project_title,
                'type' => $type,
                'message_link' => getExpertMessageLink()."?communication_id=".$current_contract->communications_id,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }


    public static function newProjectFollowUpEmailToExpert($project_detail) {
        $base_url = getenv('APP_URL');
        $project_id = $project_detail['project_id'];
        $project_link = $base_url . 'project_view?sellerid=' . $project_id;
        $expert_information = userInfo($project_detail['user_id']);
        try {
            $project_information = PostJob::getPostJobInformation($project_id)->get()->toArray();
            $project_budget = $project_information[0]['rate'];
            $project_price = convertToCurrencySymbol($project_information[0]['currency']) . number_format($project_budget);
            if ($project_budget == 0) {
                $project_price = 'Negotiable';
            }
            if ($project_information[0]['rate_variable'] == 'daily_rate') $project_price.= "/day";

            $project_data['from'] = getenv('CLIENT_EMAIL');
            $project_data['to'] = $expert_information[0]['email'];
            $project_data['template_data'] = [
                'expert_first_name' => trim(ucfirst($expert_information[0]['name'])),
                'project_name' => ucfirst($project_information[0]['job_title']),
                'project_budget' => $project_price,
                'project_view_link' => $project_link,
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'])
            ];
            if (strlen($project_information[0]['description'])>150) {
                $project_data['template_data']['trimmed_project_description'] = ['content' => ucfirst(substr($project_information[0]['description'],0,150)) ,'project_link'=>$project_link];
            }else{
                $project_data['template_data']['untrimmed_project_description'] = ['content' => ucfirst($project_information[0]['description'])];
            }
            $data = self::prepareData($project_data, false);
            if(self::sendEmail($data)){
                OutboundEmailLog::deleteRecord(['email_client_message_id' => $project_detail['message_id']]);
            }
        } catch (\Exception $e) {
            echo "Email to " . $expert_information[0]['email'] . " is not sent";
        }
        return true;
    }
    public static function myDataRequestEmailToUser($user_id) {
        $user_information = userInfo($user_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                "first_name" => ucfirst($user_information[0]['name']),
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'], 0)
            ]
        ];
        $data = self::prepareData($email_data, false);
            return self::sendEmail($data);
    }
    public static function accountDeletionRequestEmailToUser($user_id) {
        $user_information = userInfo($user_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $user_information[0]['email'],
            'template_data' => [
                "first_name" => ucfirst($user_information[0]['name']),
                'email_footer' => self::emailTemplateFooter($user_information[0]['email'], 0)
                ]
            ];
            $data = self::prepareData($email_data, false);
            return self::sendEmail($data);
    }
    public function userRequestForDataEmailToAdmin($user_id) {
        $user_information = userInfo($user_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => getenv('EMAIL_CONTACT'),
            'template_data' => [
                'user_type' => ($user_information[0]['user_type_id']==config('constants.BUYER')?'Client':'Expert'),
                'name' => ucwords(trim($user_information[0]['name'] . ' ' . $user_information[0]['last_name'])),
                'email' => trim($user_information[0]['email']),
                'email_footer' => ''
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    public function userRequestForAccountDeletionEmailToAdmin($user_id) {
        $user_information = userInfo($user_id);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => getenv('EMAIL_CONTACT'),
            'template_data' => [
                'user_type' => ($user_information[0]['user_type_id']==config('constants.BUYER')?'Client':'Expert'),
                'name' => ucwords(trim($user_information[0]['name'] . ' ' . $user_information[0]['last_name'])),
                'email' => trim($user_information[0]['email']),
                'email_footer' =>''
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }

    public function projectRebookEmailNotificationToExpert($data) {
        $expert_information = userInfo($data['expert_id']);
        $buyer_information = buyerInfo($data['buyer_id']);

        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_information[0]['email'],
            'template_data' => [
                'expert_name' => ucfirst(explode(' ', $expert_information[0]['name'])[0]),
                'buyer_first_name' => $data['buyer_first_name'],
                'buyer_company_name' => $buyer_information[0]['company_name'],
                'message_url' => $data['message_url'],
                'email_footer' => self::emailTemplateFooter($expert_information[0]['email'], 1)
            ]
        ];
        $data = self::prepareData($email_data, false);
        return self::sendEmail($data);
    }
    
    public function inviteExpertsByVendor($data)
    {
        $vendor_information = (new BuyerProfile)->buyerNameAndCompany($data['vendor_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $data['email'],
            'template_data' => [
                'expert_first_name' => ucfirst($data['first_name']),
                'vendor_full_name' => ucfirst($vendor_information->first_name).' '.ucfirst($vendor_information->last_name),
                'vendor_company_name' => $vendor_information->company_name,
                'sign_up_link' => getenv('APP_URL').'signup?expert',
                'email_footer' => self::emailTemplateFooter($data['email'], 1)
            ]
        ];
        $prepared_data = self::prepareData($email_data, false);
        return self::sendEmail($prepared_data);
    }
    
    public function DeclineExpertByVendor($data)
    {
        $expert_details = (new CommonFunctionsComponent)->getUserDetails($data['expert_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_details['email'],
            'template_data' => [
                'expert_first_name' => ucfirst($expert_details['first_name']),
                'vendor_company_name' => $data['vendor_company_name'],
                'message' => $data['message'],
                'email_footer' => self::emailTemplateFooter($expert_details['email'], 1)
            ]
        ];
        $prepared_data = self::prepareData($email_data, false);
        return self::sendEmail($prepared_data);
    }
    
    public function ApproveExpertByVendor($data)
    {
        $expert_details = (new CommonFunctionsComponent)->getUserDetails($data['expert_id']);
        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $expert_details['email'],
            'template_data' => [
                'expert_first_name' => ucfirst($expert_details['first_name']),
                'vendor_company_name' => $data['vendor_company_name'],
                'email_footer' => self::emailTemplateFooter($expert_details['email'], 1)
            ]
        ];
        $prepared_data = self::prepareData($email_data, false);
        return self::sendEmail($prepared_data);
    }

    public  function emailToVendorWhenApplyToServiceHub($data) {
        $vendor_information = userInfo($data['vendor_id'])[0];

        $email_data = [
            'from' => env('CLIENT_EMAIL'),
            'to' => $vendor_information['email'],
            'template_data' => [
                'vendor_first_name' => ucfirst($vendor_information->name),
                'redirect_url' => route('service-hubs'),
                'email_footer' => self::emailTemplateFooter($vendor_information['email'], 1)
            ]
        ];
        $prepared_data = self::prepareData($email_data, false);
        return self::sendEmail($prepared_data);
    }
}
