<?php

namespace App\Components;

use Auth;
use Exception;
use App\Model\ReferralExpert;
use App\Model\ReferralCouponCode;
use App\Model\CouponAppliedByExpert;
use App\Model\PromotionalCoupon;
use App\Model\PromotionalCouponUsageDetail;
use App\Model\Communication;

Class Common {

    public static function getAuthorizeUser() {
        return Auth::user();
    }

    public static function saveMessage($data) {
        $message = new \App\Model\Message($data);
        if ($message->save()) {
            return $message;
        }
        return false;
    }

    public static function sendContractOfferMessageByBuyer($sender_id, $receiver_id, $communication_id, $contract_id) {
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "<div class='proposal-$contract_id'></div>",
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_type' => 'proposal_sent_by_expert'
            
        ];
        return self::saveMessage($data);
    }

    public static function filterMessageDate($data) {
        $temp_date = 0;
        foreach ($data as $key => $value) {
            $message_date = date('Y-m-d', strtotime($value['created_at']));
            $data[$key]['show_date'] = addTimeZone('j M, Y', $value['created_at']);
            $data[$key]['message_time'] = addTimeZone('h:i a', $value['created_at']);
        }

        return $data;
    }

    public static function checkIfPromoCouponIsValidForBuyer($buyer_id, $coupon_code, $rate) {
        $accepted_contract_exists = \App\Model\Contract::getBuyercontractsCount($buyer_id);
        $promotional_coupon = PromotionalCoupon::getFirstPromotionalCoupon($coupon_code);
        if (_count($promotional_coupon) < 1) {
            $response['status'] = 0;
            $response['message'] = "No such coupon found.";
        } elseif ($accepted_contract_exists > 0) {
            $response['status'] = 0;
            $response['message'] = "This code is no longer valid.";
        } else if ($rate < 1000) {
            $response['status'] = 0;
            $response['message'] = "This code isn't valid for this project. The value of the project must be at least $1,000 ";
        } else if (!empty($promotional_coupon)) {
            $expiry_date = $promotional_coupon->expiry_date;
            $current_date = date('Y-m-d H:i:s');
            $promotional_coupon_usage_details = PromotionalCouponUsageDetail::getPromotionCouponCodeCount($promotional_coupon->id);
            if ($promotional_coupon_usage_details >= config('constants.HUNDRED') || $current_date > $expiry_date || $promotional_coupon->is_active === FALSE) {
                $response['status'] = 0;
                $response['message'] = "Coupon code has been expired!";
            } else {
                $response['status'] = 1;
                $response['message'] = "Coupon code is valid";
                $response['amount'] = $promotional_coupon->amount;
            }
        } else {
            $response['status'] = 0;
            $response['message'] = "Copoun code is not valid";
        }
        return $response;
    }

    public static function saveAcceptOfferMessageToBuyer($expert_id, $buyer_id, $communication_id) {
        $expert_first_name = ucfirst(getUserDetail($expert_id)['name']);
        $data = [
            'sender_id' => $buyer_id,
            'receiver_id' => $expert_id,
            'msg' => "You accepted $expert_first_name's proposal",
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 0,
            'message_sender_role' => 'user',
            'message_type' => 'buyer_accepting_proposal'
        ];
        return self::saveMessage($data);
    }
    public static function saveProposalAcceptanceOfferMessageToExpert($expert_id, $buyer_id, $communication_id) {
        $buyer_first_name = ucfirst(getUserDetail($buyer_id)['name']);
        $data = [
            'sender_id' => $buyer_id,
            'receiver_id' => $expert_id,
            'msg' => "$buyer_first_name accepted your proposal",
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 0,
            'message_sender_role' => 'user',
            'message_type' => 'buyer_accepting_proposal_to_expert'
        ];
        return self::saveMessage($data);
    }
    
    public static function saveAcceptOfferMessageByAdminToBuyer($sender_id, $receiver_id, $communication_id,$type) {
        $project = ($type == config('constants.SERVICE_PACKAGE')) ? 'package' : 'project';
        $expert_first_name = ucfirst(getUserDetail($sender_id)['name']);
        $message = "Nice job! You just accepted $expert_first_name's proposal."
            . "<br><br> Once $expert_first_name has completed the deliverables and the "
            . "$project is finished, simply “Mark the $project as complete“ by hitting the"
            . " blue button on the right-hand-side.";
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $message,
            'buyer_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class='editcontract contract-label'><span></span></a>",
            'expert_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class='editcontract contract-label'><span></span></a>",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'expert',
            'message_type' => 'buyer_accepting_contract'
        ];
        return self::saveMessage($data);
    }
    
    public static function saveAcceptOfferMessageByAdminToExpert($sender_id, $receiver_id, $communication_id) {
        $message = "Congratulations! This contract is now locked. Now, go and do great things together with your new client ;-) "
            . "<br><br> Please get in touch at any time with questions, ideas or concerns.";
            
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $message,
            'buyer_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class='editcontract contract-label'><span></span></a>",
            'expert_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class='editcontract contract-label'><span></span></a>",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'buyer',
            'message_type' => 'buyer_accepting_contract_to_expert'
        ];
        return self::saveMessage($data);
    }

    public static function saveBuyerMarkProjectAsComplete($sender_id, $receiver_id, $communication_id,$type) {
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "I have marked this $type as complete. Payment will now be processed by MeasureMatch within the next 31 days",
            'buyer_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class=' editcontract contract-label'><span></span></a>",
            'expert_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class=' editcontract contract-label'></a>",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }

    public static function saveBuyerFeedbackMessageAferProjectCompletion($sender_id, $receiver_id, $communication_id, $contract_id, $package_type) {
        if ($package_type == config('constants.SERVICE_PACKAGE')) {
            $package_type = config('constants.PACKAGE');
        }
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "Please give me some feedback based on the $package_type",
            'buyer_link' => "<a class='feedback-by-buyer' title='Give Feedback' onclick='feedbackGivenByBuyer(this);' data-target='#feedbackpopup-".$contract_id."' data-toggle='modal' href='javascript:void(0)'><span> here.</span></a>",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }

    public static function saveBuyerInviteNewExpertForConversation($sender_id, $receiver_id, $message, $communication) {
        $buyer_link = url('re-route/project/' . $communication->job_post_id, [], getenv('APP_SSL'));
        $expert_link = url('re-route/project-view/' . $communication->job_post_id, [], getenv('APP_SSL'));
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => stripScriptingTagsInline($message),
            'buyer_link' => "<br/>Click <a href=" . $buyer_link . " data-toggle='modal' class='editcontract contract-label'><span> here</span></a> to view the project",
            'expert_link' => "<br/>Click <a target='_blank' href=" . $expert_link . " data-toggle='modal' class='editcontract contract-label'><span> here</span></a> to view the project",
            'communications_id' => $communication->id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }

    public static function saveExpertMessageShowInterestInProjectToExpert($sender_id, $receiver_id, $job_title, $communication) {
        try {
            $expert_detail = getUserDetail($sender_id);
            $expert_name = $expert_detail['name'];
            $job_title = getJob($communication->job_post_id, 0);

            $project_expert_link = url('re-route/project-view/' . $communication->job_post_id, [], getenv('APP_SSL'));

            $expert_data = [
                'sender_id' => $receiver_id,
                'receiver_id' => $sender_id,
                'msg' => "Hello " . $expert_name . ",<br><br>Your Expression of Interest (EOI) in <a target='_blank' href='" . $project_expert_link . "'  class='view-full-btn'>$job_title</a> has been sent to the Client.<br><br>"
                        . "You are not able to communicate with the Client about the Project until they have reviewed your MeasureMatch profile and decided to initiate a conversation with you.<br><br>"
                        . "In bocca al lupo! - that’s Good luck in Italian ;-)<br><br>Thank you from the Team at MeasureMatch",
                'buyer_link' => "",
                'expert_link' => "",
                'communications_id' => $communication->id,
                'attachment' => "",
                'read' => 0,
                'automated_message' => 1,
                'message_type' => 'expert_expression_of_interest',
                'message_sender_role' => 'buyer'
            ];

            return self::saveMessage($expert_data);
        } catch (Exception $ex) {
            throw new \Exception(__('global.internal_server_error'));
        }
    }

    public static function saveExpertMessageShowInterestInProjectToBuyer($sender_id, $receiver_id, $job_title, $communication)
    {
        try {
            $expert_link = url('re-route/expert-profile/'.$sender_id, [], getenv('APP_SSL'));
            $expert_detail = getUserDetail($sender_id);
            $buyer_detail = getUserDetail($receiver_id);
            $expert_name = ucfirst($expert_detail['name']);
            $buyer_name = ucfirst($buyer_detail['name']);
            $job_title = getJob($communication->job_post_id, 0);
            $project_buyer_link = url('re-route/project/' . $communication->job_post_id, [], getenv('APP_SSL'));

            $buyer_data = [
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'msg' => "Hi " . $buyer_name . ",<br><br>" . $expert_name . " has submitted an Expression of Interest (EOI) in your project: <a href='" 
                            . $project_buyer_link . "'  class='view-full-btn'>$job_title</a>.<br><br>We encourage you to quickly take a look at the MeasureMatch <a href='" 
                            . $expert_link . "' class='contract-label profile links'><span></span> Expert's profile </a> and, if relevant, start a discovery and negotiation "
                            . "conversation by pressing the blue button directly below this message.<br><br>In bocca al lupo! (that’s Good luck in Italian ;-)<br>"
                . "         <br>Thank you from the MeasureMatch Team",
                'buyer_link' => "",
                'expert_link' => "",
                'communications_id' => $communication->id,
                'attachment' => "",
                'read' => 0,
                'automated_message' => 1,
                'message_sender_role' => 'expert',
                'message_type' => 'expression_of_interest'
            ];
            return self::saveMessage($buyer_data);
        } catch (Exception $ex) {
            throw new \Exception(__('global.internal_server_error'));
        }
    }

    public static function saveCoverLetterMessage($sender_id, $receiver_id, $message, $communication_id) {
        try {
            $data = [
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'msg' => $message,
                'buyer_link' => "",
                'expert_link' => "",
                'communications_id' => $communication_id,
                'attachment' => "",
                'read' => 0,
                'automated_message' => 0
            ];

            return self::saveMessage($data);
        } catch (Exception $ex) {
            throw new \Exception(__('global.internal_server_error'));
        }
    }

    public static function saveWelcomeMessageToExpertShowInterestInProjectByAdmin($sender_id, $receiver_id, $communication, $project_title, $project_id) {
        $buyer_first_name = userName($sender_id,0,1);
        $route = route('projects_view')."?sellerid=$project_id";
        $message = "<div class='first-automated-message'>
            <p>Nice job! The Client, $buyer_first_name, has invited you to discuss their project <a href=$route target='_blank'>$project_title</a> and you’re now in an open conversation.  </p><br/>
            <p>We recommend you organise a call with $buyer_first_name ASAP to discuss the project in more detail.</p><br/>
            <p>  Please try to keep as much of your communication via the platform as possible so the MeasureMatch team can be of full support where needed.</p>
            </div>";
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $message,
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication->id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'admin',
            'message_type' => 'expert_welcome_message'
        ];

        return self::saveMessage($data);
    }

    public static function saveExpertMarkProjectComplete($sender_id, $receiver_id, $communication_id, $type) {
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "I have marked this $type as complete. Before payment can be processed, you must mark this $type as complete too.",
            'buyer_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class=' editcontract contract-label'><span></span></a>",
            'expert_link' => "<a href='javascript:void(0)' data-toggle='modal' data-target='' class=' editcontract contract-label'></a>",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }
    
    public static function saveExpertFinishPackageContractMessage($contract_id, $sender_id, $receiver_id, $communication_id, $days) {
        $next_billing_date = trim(nextBillingDateForMonthlyRetainer($contract_id));
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "<span class='finshed_contract_message'>I’ve cancelled/finished the Package</span> Before the next payment date of $next_billing_date, I am responsible to complete my agreed time commitment of $days days/month. Please rate my package ",
            'buyer_link' => "<a href='javascript:void(0)' onclick='feedbackGivenByBuyer(this);' data-toggle='modal' data-target='' class='contract-label'><span>here</span></a>",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }
    public static function saveBuyerFinishPackageContractMessage($contract_id, $sender_id, $receiver_id, $communication_id, $days) {
        $next_billing_date = trim(nextBillingDateForMonthlyRetainer($contract_id));
        $next_billing_date_timestamp = strtotime($next_billing_date);
        $today_timestamp = strtotime(date('y-m-d'));
        if ($next_billing_date_timestamp == $today_timestamp) {
            $message = "This monthly retainer package has now finished. The Expert should no longer supply $days days/month of services.";
        } else {
            $message = "<span class='finshed_contract_message'>I’ve cancelled/finished the Package</span>Before the next payment date of $next_billing_date, you are responsible to complete the agreed time commitment of $days days/month.";
        }
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $message,
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1
        ];
        return self::saveMessage($data);
    }

    public static function initateConversationBuyerMessage($buyer_id, $expert_id, $communication_id) {
        $expert_first_name = userName($expert_id,0,1);
        $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'communications_id' => $communication_id,
            'msg' => 'Nice job! You’re now in a direct conversation with ' . $expert_first_name . 
                    ' to discuss your project.  <br/><br/>'
                    . 'We recommend you organize a call with ' . $expert_first_name . 
                    ' ASAP to discuss your needs in more detail.',
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'expert',
            'attachment' => '',
            'message_type' => 'initiation'
        ];
        return self::saveMessage($data);
    }
    
    public static function initateConversationExpertMessage($buyer_id, $expert_id, $communication_id) {
        $buyer_first_name = userName($buyer_id,0,1);
        $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'communications_id' => $communication_id,
            'msg' => 'Nice job! You’re now in a direct conversation with ' . $buyer_first_name . 
                    ' to discuss your project.  <br/><br/>'
                    . 'We recommend you organize a call with ' . $buyer_first_name . 
                    ' ASAP to discuss your needs in more detail. <br/><br/>'
                    . 'Please try to keep as much of your communication via the platform as '
                    . 'possible so the MeasureMatch team can be of full support where needed.',
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'buyer',
            'attachment' => '',
            'message_type' => 'initiation'
        ];
        return self::saveMessage($data);
    }

    public function initateConversationBuyerForReBooking($buyer_id, $expert_id, $communication_id, $project_title) {
        $expert_first_name = userName($expert_id,0,1);
        $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'communications_id' => $communication_id,
            'msg' => 'Nice job! You just started a new conversation with '. $expert_first_name.' to discuss a new project: '.$project_title.'.',
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => config('constants.USER_ROLE.EXPERT'),
            'attachment' => '',
            'message_type' => config('constants.MESSAGE_TYPE.1')
        ];
        return self::saveMessage($data);
    }

    public function initateConversationExpertForReBooking($buyer_id, $expert_id, $communication_id, $project_title) {
        $buyer_first_name = userName($buyer_id,0,1);
        $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'communications_id' => $communication_id,
            'msg' => 'Nice job! '.$buyer_first_name.' has invited you to discuss a new project, '.$project_title.'.',
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => config('constants.USER_ROLE.BUYER'),
            'attachment' => '',
            'message_type' => config('constants.MESSAGE_TYPE.1')
        ];
        return self::saveMessage($data);
    }
    
    public function sendRebookedProjectMessage($buyer_id, $expert_id, $communication_id) {
        $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'msg' => '',
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'buyer',
            'message_type' => 'buyer_rebooking_project'
        ];
        return self::saveMessage($data);
    }

    public static function getPopUpStatus() {
        $user_setting = json_decode(Auth::user()->settings, 1);
        $service_package_welcome_email = isset($user_setting['service_package_welcome_email']) ? TRUE : FALSE;
        $service_package_welcome_popup_count = isset($user_setting['service_package_welcome_popup_count']) ? $user_setting['service_package_welcome_popup_count'] : 1;
        $service_package_listing_show_popup = isset($user_setting['service_package_listing_show_popup']) ? TRUE : FALSE;
        $service_package_listing_welcome_popup_count = isset($user_setting['service_package_listing_welcome_popup_count']) ? $user_setting['service_package_listing_welcome_popup_count'] : 1;
        $buyer_search_service_package_welcome_pop_up = isset($user_setting['search_service_package_tool_tip']) ? TRUE : FALSE;

        return ['service_package_welcome_email' => $service_package_welcome_email,
            'service_package_welcome_popup_count' => $service_package_welcome_popup_count,
            'service_package_listing_welcome_popup_count' => $service_package_listing_welcome_popup_count,
            'service_package_listing_show_popup' => $service_package_listing_show_popup,
            'buyer_search_service_package_welcome_pop_up' => $buyer_search_service_package_welcome_pop_up];
    }

    public static function expertAvailbalityMessageToBuyer($available_status_value, $message_text, $communication_id) {
        if ($available_status_value == 'available') {
            $message = "<span class='expert_availablity_message gilroyregular-bold-font'>I'm available</span><br>" . $message_text;
            $communication = \App\Model\Communication::updateCommunication($communication_id, ['status' => 1]);
        } else if ($available_status_value == 'not_available') {
            $message = "<span class='expert_availablity_message gilroyregular-bold-font'>I'm not available</span><br>" . $message_text;
            $communication = \App\Model\Communication::updateCommunication($communication_id, ['status' => 1]);
        } else {
            $message = $message_text;
        }
        return ['message_type' => 'normal', 'message' => $message];
    }

    public static function editContractAutoMessage($buyer_id, $expert_id, $communication_id, $contract_id) {
         $message = __('general_status_messages.messaging_module.edit_contract');
         $data = [
            'sender_id' => trim($buyer_id),
            'receiver_id' => trim($expert_id),
            'communications_id' => $communication_id,
            'msg' => $message,
            'buyer_link' => "<a href='javascript:void(0)' data-toggle='modal' onclick='viewOffer(" . $contract_id . ");' data-target='#editgotmatchpopup-" . $contract_id ."' ><span> here</span></a>",
            'expert_link' => "<a href='javascript:void(0)' data-toggle='modal' onclick='viewProjectByExpert(" . $contract_id . ");' data-target='#gotmatchpopup-" . $contract_id ."'><span> here</span></a>",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'user',
            'attachment' => '',
        ];
        return self::saveMessage($data);
    }

    public static function autoMessageShowInterestInServicePackageToExpert($sender_id, $receiver_id, $service_package_id, $communication_id) {
        $package_buyer_link = "<a href='" . url('re-route/service-package/' . $service_package_id, [], getenv('APP_SSL')) . "'  class='view-full-btn'>" . getServicePackageName($service_package_id, 0) . "</a>";
        $package_expert_link = "<a href='" . url('re-route/service-package-detail/' . $service_package_id, [], getenv('APP_SSL')) . "'  class='view-full-btn'>" . getServicePackageName($service_package_id, 0) . "</a>";
        $automessage_data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => "I expressed interest in your Package-",
            'buyer_link' => $package_buyer_link,
            'expert_link' => $package_expert_link,
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'automated_message' => 1,
            'message_sender_role' => 'user'
        ];
        return self::saveMessage($automessage_data);
    }

    public static function SaveMessageShowInterestInServicePackageToExpert($sender_id, $receiver_id, $message, $communication_id) {
        $message_data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $message,
            'buyer_link' => "",
            'expert_link' => "",
            'communications_id' => $communication_id,
            'attachment' => "",
            'read' => 0,
            'message_sender_role' => 'user'
        ];
        return self::saveMessage($message_data);
    }
}
