<?php

namespace App\Components;

use Postmark\PostmarkClient;
use App\Components\BackgroundTasks;
use App\Model\BuyerProfile;

class Email {

    private static $client = null;
    private static $via_jobs = false;

    private static function getClient() {
        if (!self::$client) {
            self::$client = new PostmarkClient(getenv('POSTMARK_TOKEN'));
        }
        return self::$client;
    }

    private static function sendEmail($data,$settings = []) {

        if (self::$via_jobs == true) {
            $dispatcher = new \App\Jobs\Email(debug_backtrace()[1]['function'], $data);
            if(isset($settings['delay'])){
                $dispatcher->delay($settings['delay']);
            }
            $return = dispatch($dispatcher);
            self::$via_jobs = false;
            return $return;
        }
        return self::getClient()
                ->sendEmailWithTemplate($data['from'], $data['to'], $data['template_id'], $data['template_data']);
    }
    
        

    private static function getTemplateId($type) {
        $config = [
            'sendExpertCouponCodeMail' => getenv('ACTIVE_EXPERT_COUPON_TEMPLATE_ID')
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
            'headers' => isset($options['headers']) ? array("X-message-reply-data-Header" => $options['headers']) : '',
            'template_data' => $options['template_data'],
            'template_id' => self::getTemplateId(debug_backtrace()[1]['function'])
        ];
        if ($encode) {
            return "'" . json_encode($data) . "'";
        }
        return $data;
    }

    public static function sendBuyerReferalEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function sendExpertReferalEmail($data) {

        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailAdminBeforeProjectStart($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function pendingActivationExpertEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function pendingActivationBuyerEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function buyerEmailUpcomingContractStart($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailUpcomingContractStartInKernel($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailContractEnd($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailExpertWeekly($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailBuyerWeekly($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expertProfileCompletionEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expertEmailForAdminReview($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function verificationToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expertVerificationEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function buyerVerificationEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expertApprovalEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function buyerApprovalEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function vendorApprovalEmail($data)
    {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public static function buyerAccountRejectionEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
   
    public static function shareEmailWithRegisteredUser($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function shareEmailWithUnregisteredUser($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function resetPassword($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function projectVerficationEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function referredRegisteredExpertEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function referredUnregisteredExpertEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function newProjectEmailNotification($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public function waitingProjectApprovalEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expressionOfInterestEmailToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function expressionOfInterestEmailToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function sendEOIMailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public static function userVerficationEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function projectAdminReview($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function sendFirstPostProjectMail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public static function sendPostProjectMail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public function sendExpertWelcomeMail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function sendExpertCouponCodeMail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public function sendBuyerWelcomeMail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function sendVendorWelcomeMail($data)
    {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public static function adminChatNotification($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function emailToExpertExpressionOfInterestAccepted($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function emailToBuyerExpressionOfInterestAccepted($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailToExpertOnInvite($email_data) {
        self::$via_jobs = true;
        return self::sendEmail($email_data);
    }
    public static function emailToAdminSendContractDetail($contract_details) {
        self::$via_jobs = true;
        return self::sendEmail($contract_details);
    }

    public static function newContractOfferedEmailToBuyer($contract_id) {
        self::$via_jobs = true;
        return self::sendEmail($contract_id);
    }

    public static function newContractCreatedByExpertToHimself($contract_id) {
        self::$via_jobs = true;
        return self::sendEmail($contract_id);
    }

    public static function sendContractEmailToExpert($contract_details) {
        self::$via_jobs = true;
        return self::sendEmail($contract_details);
    }

    public static function sendContractEmailToBuyer($contract_details) {
        self::$via_jobs = true;
        return self::sendEmail($contract_details);
    }

    public static function sendContractEmailToAdmin($contract_details) {
        self::$via_jobs = true;
        return self::sendEmail($contract_details);
    }

    public static function projectCompletionNotificationToAdmin($email_data) {
        self::$via_jobs = true;
        return self::sendEmail($email_data);
    }
    public static function sendExpertProjectCompletionNotification($email_data) {
        self::$via_jobs = true;
        return self::sendEmail($email_data);
    }

    public static function sendBuyerProjectCompletionNotification($email_data) {
        self::$via_jobs = true;
        return self::sendEmail($email_data);
    }

    public static function sendBuyerFeedbackNotification($feedback_data) {
        self::$via_jobs = true;
        return self::sendEmail($feedback_data);
    }

    public static function sendEmailToBuyerConfirmingPaymentModeInvoice($payment_data) {
        self::$via_jobs = true;
        return self::sendEmail($payment_data);
    }
    
    public static function sendMissedMessageEmail($message_data) {
        self::$via_jobs = true;
        return self::sendEmail($message_data);
    }


    public static function supportRequestMessageToAdminFromExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function supportRequestMessageToAdminFromBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function acknowledgementEmailFromMmSupport($user_id) {
        self::$via_jobs = true;
        return self::sendEmail($user_id);
    }
    
    public static function sendBuyerSignupEmailToMeasureMatch($user_id){
        self::$via_jobs = true;
        return self::sendEmail($user_id);
    }
    
    public static function sendExpertSignupEmailToMeasureMatch($user_id){
        self::$via_jobs = true;
        return self::sendEmail($user_id);
    }
    public static function servicePackageCreatedEmailToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageCreatedEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageDraftedEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageApprovedEmailToExpert($service_package_id) {
        self::$via_jobs = true;
        return self::sendEmail($service_package_id);
    }
    public static function servicePackageShowInterestEmailToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageShowInterestEmailToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageShowContractMonthlyOfferEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageShowContractOneTimeOfferEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function emailUpcomingServicePackageStartInKernel($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function buyerEmailUpcomingServicePackageStart($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function expertEmailUpcomingContractEndDate($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function buyerEmailUpcomingContractEndDate($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageAvailableOnServicePackageMessageToBuyerEmail($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageContractOfferAcceptEmailToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageContractOfferAcceptEmailToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageContractOfferAcceptEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function upcomingServicePackageStartEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackagecheckProgressWithBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackagecheckProgressWithExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageOneTimeContractCompletedByExpertEmailToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageOneTimeContractCompletedByExpertEmailToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
     public static function servicePackageOneTimeContractCompletionByBuyerToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageOneTimeContractCompletionByBuyerToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageOneTimeContractCompletionByBuyerToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByBuyerToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByBuyerToBuyer($data) {
           self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByExpertToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByExpertToBuyer($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractBillingDateReachedToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractBillingDateReachedToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractBillingDateReachedToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinalDayPaymentToExpert($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinalDayPaymentToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinalDayPaymentToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByBuyerOnFinalPaymentDateToBuyer($data) {
             self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function servicePackageMonthlyContractFinishedByExpertOnFinalPaymentDateToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function sendContactUsEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function contractExtensionByBuyerEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function contractExtensionByBuyerEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractUpdatedByBuyerEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractUpdatedByBuyerEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractAcceptedByExpertEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractAcceptedByExpertEmailToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractAcceptedByExpertEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractStartDateReachedToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractStartDateReachedToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extendedContractStartDateReachedToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionStartAndPreviousContractCompleteEmailToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionStartAndPreviousContractCompleteEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionStartAndPreviousContractCompleteEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionContractAndMainContractCompleteEmailToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionContractAndMainContractCompleteEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function extensionContractAndMainContractCompleteEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function projectContractCompletedByExpertEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function projectContractCompletedByExpertEmailToBuyer($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public function projectContractCompletedByExpertEmailToAdmin($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function sendEmailsToOtherExpertsThatExpressedInterest($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public function buyerAccountForAdminReview($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function newProjectFollowUpEmailToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function userRequestForDataEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function userRequestForAccountDeletionEmailToAdmin($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function myDataRequestEmailToUser($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    public static function accountDeletionRequestEmailToUser($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function projectRebookEmailNotificationToExpert($data) {
          self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function inviteExpertsByVendor($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function DeclineExpertByVendor($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
    
    public function ApproveExpertByVendor($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }

    public static function emailToVendorWhenApplyToServiceHub($data) {
        self::$via_jobs = true;
        return self::sendEmail($data);
    }
}
