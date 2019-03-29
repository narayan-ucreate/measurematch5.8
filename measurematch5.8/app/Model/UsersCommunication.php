<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersCommunication extends Model {

    protected $table = 'users_communications';
    public $timestamps = true;
    protected $fillable = [
        'email_subscription', 'user_id', 'created_at'
    ];

    /**
     * Users Communication Method
     * 
     * @return type
     */
    public function usersCommunication() {
        return $this->belongsTo('App\Model\Category', 'communication_type_id');
    }

    public static function getUserCommunications($id) {
        return UsersCommunication::where('user_id', '=', $id)->get()->toArray();
    }

    public static function getEmailSubscriptionStatus($userid) {
        $info = UsersCommunication::where(['user_id' => $userid])
            ->select('email_subscription')
            ->first();
        return $info ? $info->email_subscription : false;
    }

    public static function deleteUserCommunications($user_id) {
        return UsersCommunication::where('user_id', '=', $user_id)->delete();
    }
}
