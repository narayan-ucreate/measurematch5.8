<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use Payment;
use Carbon\Carbon;
use DB;
use Postmark\PostmarkClient;
use App\Model\BuyerProfile;
use App\Model\PostJob;
use App\Model\User;
use App\Model\OutboundEmailLog;
use App\Model\Contract;
use App\Model\Communication;
use App\Components\Email;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\BackCommand::class,
        \App\Console\Commands\TriggerEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        $base_url = getenv('APP_URL');

        $schedule->call(function () {
            $day_of_the_week = 'Sun';
            try {
                $this->sendFeedbackEmailContractsFinishedYesterday();
                $this->servicePackageBillingDateForMonthlyPackages();
                $this->servicePackageFinalDayPaymentForMonthlyPackages();
                $this->oneDayPriorProjectStart();
                $this->oneWeekPriorProjectStart();
                $this->contractEndEmail();
                $this->expertUpcomingMessage();
                $this->pendingbuyeractivation();
                $this->expertUpcomingMessageForServicePackage();
                $this->upcomingServicePackageContractToAdmin();
                $this->servicePackageCheckProgressWithExpertAndBuyer();
                $day_of_the_week = date("D");
                if ($day_of_the_week == $sunday) {
                    $this->weeklyProjectEmail();
                }
            } catch (\Exception $e) {
                $message = 'error in cron' . $e;
                return $message;
            }
        })->cron('* 5 * * *');

        $schedule->call(function () {
            try {
                $this->servicePackageUpdateNextBillingDateForMonthlyPackages();
                $this->sendNewProjectFollowUpEmail();
            } catch (\Exception $e) {
                $message = 'error in cron' . $e;
                return $message;
            }
        })->cron('* 0 * * *');

        $schedule->call(function () {
            try {
                \DB::beginTransaction();
                $countries = getVatApiResponse('countries');
                $rates = getVatApiResponse('rates');
                if (_count($countries)) {
                    foreach ($countries as $key => $country){
                        $countries[$key]['value'] = getCountryVAT($country['country_code'], $rates);
                    }
                    \DB::table('country_vat_details')->truncate();
                    \DB::table('country_vat_details')->insert($countries);
                }
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                $message = 'error in cron' . $e;
                return $message;
            }
        })->cron('* 0 * * *');

    }

    public function oneWeekPriorProjectStart() { 
        $base_url = getenv('APP_URL');
        $current_date = Carbon::now();
        $next_week_date = date("Y-m-d", strtotime("+1 week"));
        $current_contracts = Contract::getContractInformationWithStartDate($next_week_date, config('constants.ACCEPTED'),config('constants.PROJECT'))->get();
        if (isset($current_contracts) && !empty($current_contracts)) {
            foreach ($current_contracts as $contract) {
                try {
                    if (hasSubscribed($contract->user_id)) {
                        Email::emailExpertWeekly(['expert_id' => $contract->user_id]);
                    }
                    if (hasSubscribed($contract->buyer_id)) {
                        Email::emailBuyerWeekly(['buyer_id' => $contract->buyer_id]);
                    }
                } catch (\Exception $e) {
                    $message = 'error in cron' . $e;
                    return $message;
                }
            }
        }
    }

    // Function to send mail to Expert Every week
    public function weeklyProjectEmail() {
        $base_url = getenv('APP_URL');
        $current_date = Carbon::now();
        $current_contracts = Contract::getCurrentlyOngoingContracts($current_date, $current_date);

        if (isset($current_contracts) && !empty($current_contracts)) {
            foreach ($current_contracts as $contract) {
                try {
                    if (hasSubscribed($contract->user_id)) {
                        Email::emailExpertWeekly(['expert_id' => $contract->user_id]);
                    }
                    if (hasSubscribed($contract->buyer_id)) {
                        Email::emailBuyerWeekly(['buyer_id' => $contract->buyer_id]);
                    }
                } catch (\Exception $e) {
                    $message = 'error in cron' . $e;
                    return $message;
                }
            }
        }
    }

    // Function to send mail to Expert(Seller) when in 5 days contract will end
    public function contractEndEmail() {
        $base_url = getenv('APP_URL');
        $current_date = Carbon::now();
        $next_week_date = date("Y-m-d", strtotime("+5 day"));
        $current_contracts = Contract::getContractWithLastDate($next_week_date, 1)->get();
        if (isset($current_contracts) && !empty($current_contracts)) {
            foreach ($current_contracts as $contract) {
                if ($contract->type == config('constants.SERVICE_PACKAGE') && $contract->subscription_type == config('constants.ONE_TIME_PACKAGE')) {
                    $params = [
                        'contract_id' => $contract->id,
                        'service_package_id' => $contract->service_package_id,
                        'expert_id' => $contract->user_id,
                        'buyer_id' => $contract->buyer_id,
                        'communication_id' => $contract->communications_id
                    ];
                    if (hasSubscribed($contract->user_id)) {
                        Email::expertEmailUpcomingContractEndDate($params);
                    }
                    if (hasSubscribed($contract->buyer_id)) {
                        Email::buyerEmailUpcomingContractEndDate($params);
                    }
                } elseif ($contract->type == "project") {
                    $params = [
                        'contract_id' => $contract->id,
                        'job_post_id' => $contract->job_post_id,
                        'expert_id' => $contract->user_id,
                        'buyer_id' => $contract->buyer_id
                    ];
                    if (hasSubscribed($contract->user_id)) {
                        Email::emailContractEnd($params);
                    }
                }
            }
        }
    }

    public function expertUpcomingMessage() {

        $base_url = getenv('APP_URL');
        $next_week_date = date("Y-m-d", strtotime("+5 day"));

        $expert_users = Contract::getContractInformationWithStartDate($next_week_date, config('constants.ACCEPTED'),config('constants.PROJECT'))->get();

        if (isset($expert_users) && !empty($expert_users)) {
            foreach ($expert_users as $user) {
                if (hasSubscribed($user->expert_id)) {
                    Email::emailUpcomingContractStartInKernel(['contract_id' => $user->id, 'job_post_id' => $user->job_post_id, 'expert_id' => $user->user_id, 'buyer_id' => $user->buyer_id]);
                }
                if (hasSubscribed($user->buyer_id)) {
                    Email::buyerEmailUpcomingContractStart(['contract_id' => $user->id, 'job_post_id' => $user->job_post_id, 'expert_id' => $user->user_id, 'buyer_id' => $user->buyer_id]);
                }
            }
        }
    }

    public function expertUpcomingMessageForServicePackage() {

        $base_url = getenv('APP_URL');
        $next_week_date = date("Y-m-d", strtotime("+5 day"));

        $expert_users = Contract::getContractInformationWithStartDate($next_week_date, config('constants.ACCEPTED'),config('constants.SERVICE_PACKAGE'))->get();
        if (isset($expert_users) && !empty($expert_users)) {
            foreach ($expert_users as $user) {
                if (hasSubscribed($user->user_id)) {
                    Email::emailUpcomingServicePackageStartInKernel([
                        'contract_id' => $user->id,
                        'service_package_id' => $user->service_package_id,
                        'expert_id' => $user->user_id,
                        'buyer_id' => $user->buyer_id,
                        'communication_id' => $user->communications_id
                    ]);
                }
                if (hasSubscribed($user->buyer_id)) {
                    Email::buyerEmailUpcomingServicePackageStart([
                        'contract_id' => $user->id,
                        'service_package_id' => $user->service_package_id,
                        'expert_id' => $user->user_id,
                        'buyer_id' => $user->buyer_id,
                        'communication_id' => $user->communications_id
                    ]);
                }
            }
        }
    }
    
    public function upcomingServicePackageContractToAdmin() {
        $base_url = getenv('APP_URL');
        $next_day = date("Y-m-d", strtotime("+1 day"));
        $contracts = Contract::getContractInformationWithStartDate($next_day, config('constants.ACCEPTED'),config('constants.SERVICE_PACKAGE'))->get();
        if (isset($contracts) && !empty($contracts)) {
            foreach ($contracts as $contract) {
                Email::upcomingServicePackageStartEmailToAdmin(['contract_id' => $contract->id, 'service_package_id' => $contract->service_package_id, 'expert_id' => $contract->user_id, 'buyer_id' => $contract->buyer_id,'communication_id' => $contract->communications_id]);
            }
        }
    }
    
    public function servicePackageCheckProgressWithExpertAndBuyer() {
        $base_url = getenv('APP_URL');
        $last_week = date("Y-m-d", strtotime("-7 day"));
        $contracts = Contract::getContractInformationWithStartDate($last_week, config('constants.ACCEPTED'),config('constants.SERVICE_PACKAGE'))->get();
        if (isset($contracts) && !empty($contracts)) {
            foreach ($contracts as $contract) {
                if (hasSubscribed($contract->buyer_id)) {
                    Email::servicePackagecheckProgressWithBuyer(['buyer_id' => $contract->buyer_id]);
                }
                if (hasSubscribed($contract->user_id)) {
                    Email::servicePackagecheckProgressWithExpert([
                        'expert_id' => $contract->user_id,
                        'buyer_id' => $contract->buyer_id
                    ]);
                }
            }
        }
    }

    public function pendingbuyeractivation() {
        $registered_users = User::getBuyerActivationLink()->get();
        $date = date('Y-m-d H:i:s', strtotime('-72 hours'));

        if (isset($registered_users) && !empty($registered_users)) {
            foreach ($registered_users as $user) {

                if ($user->admin_approval_time >= $date && $user->status == config('constants.PENDING')) {

                    if ($user->user_type_id == config('constants.BUYER') && hasSubscribed($user->id) ) {
                        Email::pendingActivationBuyerEmail(['email' => $user->email, 'name' => $user->name . ' ' . $user->last_name, 'token' => $user->access_token]);
                    }
                    if ($user->user_type_id == config('constants.EXPERT') && hasSubscribed($user->id)) {
                        Email::pendingActivationExpertEmail(['email' => $user->email, 'name' => $user->name . ' ' . $user->last_name, 'token' => $user->access_token]);
                    }
                    $user_detail = User::getUserById($user->id);
                    $user_detail->pending_activation_email_sent = TRUE;
                    $user_detail->save();
                }
            }
        }
    }

    public function oneDayPriorProjectStart() {
        $next_day_date = \date("Y-m-d", \strtotime("+2 day"));
        $contracts = Contract::getParentContracts(['job_start_date' => $next_day_date, 'status' => config('constants.ACCEPTED'), 'type' => config('constants.PROJECT')]);
        $post_id_array = [];
        if(_count($contracts)){  
        foreach ($contracts as $value) {
            $post_id_array[] = $value->job_post_id;
        }
       
        if (_count($post_id_array)) {
            $unique_post_ids = array_unique($post_id_array);
           $post_detail = PostJob::getPostJobInArray($unique_post_ids)->get()->toArray();
            if (isset($post_detail) && !empty($post_detail)) {
                foreach ($post_detail as $post) {
                    $buyer_information = userInfo($post['user_id']);
                    Email::emailAdminBeforeProjectStart(['buyer_id' => $buyer_information[0]->id, 'project_id' => $post['id'], 'currency' => $post['currency']]);
                }
            }
        }
        }
    }
  
    public function servicePackageBillingDateForMonthlyPackages() { 
        $contracts_to_billed = Contract::fetchContracts(['status'=>config('constants.ACCEPTED'),'subscription_type'=>config('constants.MONTHLY_RETAINER'),'finished_on'=>null,'monthly_billing_date'=>date("Y-m-d")]);
        if (isset($contracts_to_billed) && !empty($contracts_to_billed)) {
            foreach ($contracts_to_billed as $contract) {
                if (hasSubscribed($contract['user_id'])) {
                    Email::servicePackageMonthlyContractBillingDateReachedToExpert($contract['id']);
                }
                if (hasSubscribed($contract['buyer_id'])) {
                    Email::servicePackageMonthlyContractBillingDateReachedToBuyer($contract['id']);
                }
                Email::servicePackageMonthlyContractBillingDateReachedToAdmin($contract['id']);
            }
        }
    }
    public function servicePackageFinalDayPaymentForMonthlyPackages() { 
        $contracts = Contract::monthlyContractsNotFinishedToday(['status'=>config('constants.ACCEPTED'),'subscription_type'=>config('constants.MONTHLY_RETAINER'),'job_end_date'=>date("Y-m-d")]);
        if (isset($contracts) && !empty($contracts)) {
            foreach ($contracts as $contract) {
                if (hasSubscribed($contract['user_id'])) {
                    Email::servicePackageMonthlyContractFinalDayPaymentToExpert($contract['id']);
                }
                if (hasSubscribed($contract['buyer_id'])) {
                    Email::servicePackageMonthlyContractFinalDayPaymentToBuyer($contract['id']);
                }
                Email::servicePackageMonthlyContractFinalDayPaymentToAdmin($contract['id']);
            }
        }
    }
    public function servicePackageUpdateNextBillingDateForMonthlyPackages() {
        $last_day_date = date("Y-m-d", strtotime("-1 day"));
        $contracts = Contract::fetchContracts(['status' => config('constants.ACCEPTED'), 'subscription_type' => config('constants.MONTHLY_RETAINER'), 'monthly_billing_date' => $last_day_date]);
        if (_count($contracts)) {
            foreach ($contracts as $contract) {
                if (!empty($contract['finished_on'])) {
                    Communication::updateCommunication($contract['communications_id'], ['contract_action_date' => Carbon::now()]);
                    Contract::updateContractInformation($contract['id'], ['complete_status' => config('constants.COMPLETED')]);
                } else {
                    Contract::updateContractInformation($contract['id'], ['monthly_billing_date' => date('Y-m-d G:i:s', strtotime(nextBillingDateForMonthlyRetainer($contract['id'])))]);
                }
            }
        }
    }
    
    public function sendFeedbackEmailContractsFinishedYesterday() { 
        $last_day_date = date("Y-m-d", strtotime("-1 day"));
        $contracts = Contract::where(['complete_status' => config('constants.ACCEPTED'),'buyer_feedback_status'=>config('constants.PENDING')])
                ->whereDate('finished_on','=',$last_day_date)
                ->whereNull('subscription_type')->get();
        if (_count($contracts)) {
            foreach ($contracts as $contract) {
                if (hasSubscribed($contract->buyer_id)) {
                    Email::sendBuyerFeedbackNotification($contract->id);
                }
            }
        }
    }

    public function sendNewProjectFollowUpEmail() {
        $last_day_date = date("Y-m-d", strtotime("-1 day"));
        $new_project_emails = OutboundEmailLog::last24HourAndLessUnseenEmails($last_day_date);
        if(_count($new_project_emails)){
            foreach($new_project_emails as $project_email){
                if(hasSubscribed($project_email['user_id'])) {
                    Email::newProjectFollowUpEmailToExpert(['project_id' => $project_email['post_job_id'], 'user_id' => $project_email['user_id'], 'message_id' => $project_email['email_client_message_id']]);
                }
            }
        }
    }
}
