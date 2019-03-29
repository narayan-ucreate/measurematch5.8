<?php
namespace App\Components;
use App\Model\User;
use Segment;

Class SegmentComponent {
    
    private static function initializeSegment()
    {
        class_alias('Segment', config('constants.ANALYTICS'));
        Segment::init(getenv('SEGMENT_SERVER_KEY'));
    }
    
    private function trackSegment($data)
    {
        if(!class_exists(config('constants.ANALYTICS'))){
            self::initializeSegment();
        }
        Segment::track($data);
    }

    public function accountTracking($user_id, $user_type, $event_name, $created_at = '')
    {
        $user_type_string = getUserTypeString($user_type);
        $data = [
            "userId" => $user_id,
            "event" => $event_name,
            "properties" => [
                "userType" => $user_type_string
            ]
        ];
        if (!empty($created_at))
        {
            $data['properties']['accountCreatedAt'] = $created_at;
        }
        $this->trackSegment($data);
    }

    public function projectTracking($user_id, $project_id, $project_title, $event_name, $created_at = '')
    {
        $user_type = getUserTypeString(User::getUserType($user_id));
        $data = [
            "userId" => $user_id,
            "event" => $event_name,
            "properties" => [
                "projectId" => $project_id,
                "title" => $project_title,
                "userType" => $user_type
            ]
        ];
        if (!empty($created_at))
        {
            $data['properties']['projectCreatedAt'] = $created_at;
        }
        $this->trackSegment($data);
    }
    
    public function negotiationsTracking($user_id, $project_id, $communication_id, $project_title, $message_text, $event_name, $expert_id = '', $negotiation_started_category = '')
    {
        $data = [
            "userId" => $user_id,
            "event" => $event_name,
            "properties" => [
                "title" => $project_title,
                "projectId" => $project_id,
                "EOIId" => $communication_id,
                "text" => $message_text,
            ]
        ];
        if(!empty($expert_id))
            $data['properties']['expertId'] = $expert_id;
        
        if(!empty($negotiation_started_category))
            $data['properties']['negotiationStartedCategory'] = $negotiation_started_category;
        
        $this->trackSegment($data);
    }
    
    public function messagesTracking($user_id, $message_id, $recipient_id, $message_text, $attachment_link, $message_category, $event_name)
    {
        $data = [
            "userId" => $user_id,
            "event" => $event_name,
            "properties" => [
                "messageId" => $message_id,
                "recipientId" => $recipient_id,
                "text" => $message_text,
                'messageCategory' => $message_category
            ]
        ];
        if($message_category == config('constants.STANDARD_MESSAGE'))
            $data['properties']['attachmentLink'] = $attachment_link;
            
        $this->trackSegment($data);
    }
    
    public function feedbackTracking($user_id, $project_id, $expert_id, $feedback_text, $event_name)
    {
        $data = [
            "userId" => $user_id,
            "event" => $event_name,
            "properties" => [
                "projectId " => $project_id,
                "expertId" => $expert_id,
                "text" => $feedback_text
            ]
        ];
        $this->trackSegment($data);
    }
    
    public function technographicTracking($company_name, $company_url, $tech_list, $event_name)
    {
        $data = [
            "event" => $event_name,
            "anonymousId"=> rand (10,10000),
            "properties" => [
                "companyName" => $company_name,
                "companyURL" => $company_url,
                "techMatch" => $tech_list
            ]
        ];
        $this->trackSegment($data);
    }
    
    public function proposalAcceptedTracking($expert_id, $buyer_id, $project_id, $type, $contract_id, $proposals_accepted_count, $event_name)
    {
        $properties = [
            "expertId " => $expert_id,
            "buyerId " => $buyer_id,
            "contractId " => $contract_id,
            "proposalsAcceptedCount" => $proposals_accepted_count
        ];
        ($type == config('constants.PROJECT')) ? $properties['projectId'] = $project_id : $properties['packageId'] = $project_id;
        $data = [
            "userId" => $buyer_id,
            "event" => $event_name,
            "properties" => $properties
        ];
        $this->trackSegment($data);
    }
    
    public function proposalSubmittedTracking($expert_id, $buyer_id, $project_id, $type, $contract_id, $event_name)
    {
        $properties = [
            "expertId " => $expert_id,
            "buyerId " => $buyer_id,
            "contractId " => $contract_id,
        ];
        ($type == config('constants.PROJECT')) ? $properties['projectId'] = $project_id : $properties['packageId'] = $project_id;
        $data = [
            "userId" => $expert_id,
            "event" => $event_name,
            "properties" => $properties
        ];
        $this->trackSegment($data);
    }

    public function hubTracking($hub_id, $vendor_id, $hub_name, $event_name){

        $properties = [
            "userId" => $vendor_id,
            "hubName" => $hub_name,
        ];
        $data = [
            "userId" => $vendor_id,
            "hubId" => $hub_id,
            "event" => $event_name,
            "properties" => $properties
        ];
        $this->trackSegment($data);
    }

}