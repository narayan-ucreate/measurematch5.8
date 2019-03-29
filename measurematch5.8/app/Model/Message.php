<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Message extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'messages';
    protected $fillable = ['sender_id', 'receiver_id', 'communications_id', 'msg', 'read', 'attachment', 'automated_message',
        'buyer_link', 'expert_link', 'message_sender_role', 'message_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function receiverDetails() {
        return $this->belongsTo('App\Model\User', 'receiver_id', 'id');
    }

    public function communicationDetail() {
        return $this->belongsTo('App\Model\Communication', 'communications_id', 'id');
    }

    public static function getMessagesByCommID($communications_id) {
        return Message::where('communications_id', '=', $communications_id)->get();
    }

    public static function messageData($communication_id, $options = []) {
        $query = Message::where(['communications_id' => $communication_id]);
        if (isset($options['type']) && $options['type'] == 'pagination') {
            return $query->orderBy('id', 'DESC')
                    ->offset($options['offset'])
                    ->limit(10)
                    ->get();
        } else {
            return $query->get()
                    ->count();
        }
    }

    public static function updateMessageReadStatus($communication_id, $logged_in_user) {
        return Message::where('communications_id', $communication_id)->where('sender_id', '!=', $logged_in_user)->update(['read' => TRUE]);
    }

    public static function getUnreadMessageCount($receiver_id) {
        return Message::select('id')->where('receiver_id', $receiver_id)->where('read', FALSE)->count();
    }

    public static function getCountUnreadMsgCount($communication_id, $logged_in_user, $options = []) {
        if (!isset($communication_id) || $communication_id == '' || !is_numeric($communication_id)) {
            return 0;
        } else {
            $query = Message::select('id', 'msg', 'created_at')->where('communications_id', $communication_id)->where('receiver_id', $logged_in_user);
            if (isset($options) && !empty($options) && (array_key_exists('type', $options))) {
                $result = $query->orderBy($options['type'], $options['value'])->first();
                if(_count($result)){
                    return $result->toArray();
                }
            } else {
                return $query = $query->where('read', FALSE)->count();
            }
        }
    }

    public static function getMessagesWithCreatedAt($today, $tomorrow) {
        return Message::select(DB::raw('count(receiver_id) as receiver_count, receiver_id'))->whereBetween('created_at', [$today, $tomorrow])->groupBy('receiver_id')->with('receiverDetails')->get()->toArray();
    }

    public static function fetchMessages($conditions) {
        return Message::where($conditions)->first()->toArray();
    }

}
