<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Communication extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'communications';
    protected $fillable = ['status','read'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    /**
     * Post Jobs
     *
     * @return type
     */
    public function post_jobs()
    {
        return $this->belongsTo('App\Model\PostJob', 'job_post_id', 'id');
    }
    
    public function buyerCompanyNameHidden()
    {
        return $this->belongsTo('App\Model\PostJob', 'job_post_id', 'id')->select('hide_company_name', 'id');
    }

    public function buyerBusinessInformation()
    {
        return $this->hasOne('App\Model\BusinessInformation', 'user_id', 'buyer_id');
    }


    /**
     * Buyer Method
     *
     * @return type
     */
    public function buyer()
    {
        return $this->belongsTo('App\Model\BuyerProfile', 'buyer_id', 'user_id');
    }

    public function expertProfilePicture()
    {
        return $this->belongsTo('App\Model\UserProfile', 'user_id', 'user_id')->select(['user_id', 'profile_picture']);
    }

    public function unreadProjectMessageCount()
    {
        $user_id = \Auth::user()->id;
        return $this->hasMany('App\Model\Message', 'communications_id', 'id')->where('read', false)->where('receiver_id', $user_id)->select('communications_id', 'id');
    }

    public function unreadServicePackagesMessageCount()
    {
        $user_id = \Auth::user()->id;
        return $this->hasMany('App\Model\Message', 'communications_id', 'id')->where('read', false)->where('receiver_id', $user_id)->select('communications_id', 'id');
    }




    /**
     * Contract Method
     *
     * @return type
     */
    public function contract()
    {
        return $this->belongsTo('App\Model\Contract', 'job_post_id', 'job_post_id');
    }

    public function relatedContract()
    {
        return $this->hasOne('App\Model\Contract', 'communications_id', 'id');
    }

    public function extensionContracts()
    {
        return $this->hasMany('App\Model\Contract', 'communications_id', 'id')->orderBy('alias_name', 'asc');
    }

    /**
     * User Method
     *
     * @return type
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User', 'buyer_id', 'id');
    }

    /**
     * User Method
     *
     * @return type
     */
    public function user_expert()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public static function getCommunicationInformation($communication_id)
    {
        return Communication::where('id', $communication_id)->get();
    }

    public static function updateCommunicationJobId($contract_id, $update_status)
    {
        return Communication::where('id', $contract_id)->update($update_status);
    }

    public function getCommunicationInfo($communication_id) {
        return $this->whereId($communication_id)->first();
    }

    public function latestMessage()
    {
        return $this->hasOne('App\Model\Message', 'communications_id', 'id')->orderBy('id', 'desc');
    }

    public static function expertList($buyer_id, $type, $id)
    {
        $query = Communication::select(
                'comm.id',
                'comm.id as communication_id',
                'comm.job_post_id',
                'comm.status',
                'comm.type',
                'comm.service_package_id',
                'comm.buyer_id as sender_id',
                'comm.user_id as receiver_id',
                'user.name',
                'user.last_name',
                'user_profile.describe',
                'user_profile.expert_type',
                'user_profile.profile_picture',
                'user_profile.current_city',
                'user_profile.country',
                'message.id as message_id',
                'message.msg',
                'message.created_at'
            )->with(['unreadProjectMessageCount' => function($query) {
                $query->select('communications_id');
                }]
            )->with(['relatedContract' => function($query){
                $query->select('id', 'communications_id');
                $query->where('status', config('constants.ACCEPTED'));
            }])
            ->from('communications as comm')
            ->join('users as user', 'user.id', '=', 'comm.user_id')
            ->join('user_profiles as user_profile', 'user_profile.user_id', '=', 'comm.user_id')
            ->join('messages as message', 'message.communications_id', '=', 'comm.id')
            ->where('comm.buyer_id', '=', $buyer_id);
        if ($type== config('constants.PROJECT')) {
            $query->where('comm.job_post_id', '=', $id);
        } else {
            $query->where('comm.service_package_id', '=', $id);
        }

        $query = $query->where('message.id', function ($q) {
            $q->select(DB::raw('Max(id) from messages where communications_id =comm.id'));
        })
            ->orderBy('message.id', 'desc')
            ->orderBy('comm.id', 'desc')
            ->get();

        return $query;
    }

    public static function BuyerList($expert_id)
    {
        $query = Communication::select(
                'comms.id',
                'comms.id as communication_id',
                'comms.job_post_id',
                'comms.status',
                'comms.type',
                'comms.service_package_id',
                'comms.buyer_id as receiver_id',
                'comms.user_id as sender_id',
                'user.name',
                'user.last_name',
                'buyer.company_name',
                'buyer.profile_picture',
                'message.id as message_id',
                'message.msg',
                'message.created_at'
            )
            ->with(['unreadProjectMessageCount' => function($query) {
                $query->select('communications_id');
            }])
            ->with('buyerCompanyNameHidden')
            ->from('communications as comms')
            ->join('users as user', 'user.id', '=', 'comms.buyer_id')
            ->join('buyer_profile as buyer', 'buyer.user_id', '=', 'comms.buyer_id')
            ->join('messages as message', 'message.communications_id', '=', 'comms.id')
            ->where('comms.user_id', '=', $expert_id)
            ->where('message.id', function ($q) {
                $q->select(DB::raw('Max(id) from messages where communications_id =comms.id'));
            })
            ->orderBy('message.id', 'desc')
            ->orderBy('comms.id', 'desc')
            ->get();
        return $query;
    }

    public function contractDetails()
    {
        return $this->hasOne('App\Model\Contract', 'communications_id', 'id')->orderBy('created_at', 'asc');
    }

    public function buyerCompany()
    {
        return $this->hasOne('App\Model\BuyerProfile', 'user_id', 'buyer_id');
    }

    public function projectDetail()
    {
        return $this->belongsTo('App\Model\PostJob', 'job_post_id', 'id');
    }
    public function servicePackageDetail()
    {
        return $this->belongsTo('App\Model\ServicePackage', 'service_package_id', 'id');
    }
    public static function updateCommunication($communication_id, $update_data)
    {
        return Communication::where('id', $communication_id)->update($update_data);
    }
    public static function CommunicationStaySafeFieldStatus($communication_id)
    {
        return Communication::where('id', $communication_id)->value('stay_safe_status');
    }
    public static function currentCommunicationCountOfUser($id)
    {
        return Communication::where('user_id', '=', $id)
            ->where('type', 'project')
            ->where(DB::raw('date(created_at)'), '=', \Carbon\Carbon::today()->format('Y-m-d'))
            ->count();
    }
    public static function getCommunicationInformationWithBuyerId($id, $auth_id)
    {
        return Communication::where('user_id', '=', $id)->where('buyer_id', '=', $auth_id)->where('type', config('constants.PROJECT_COMMUNICATION'))->pluck('job_post_id', 'id')->all();
    }
    public static function communicationCountByJobPostId($id, $job_post_id)
    {
        return Communication::where('user_id', '=', $id)
            ->where('job_post_id', $job_post_id)
            ->count();
    }

    public static function updateCommunicationReadStatus($communication_id)
    {
        return Communication::where('id', $communication_id)->update(['read'=>1]);
    }

    public static function getUnreadCommunicationCount($receiver_id)
    {
        return  Communication::select('id')->where('buyer_id', $receiver_id)->where('read', 0)->count();
    }

    public static function getCommunicationById($id, $project_type = 'project')
    {
        if ($project_type === config('constants.PROJECT_TYPE.PROJECT')) {
            return Communication::where('type', $project_type)->where('job_post_id', $id)->get()->toArray();
        } elseif ($project_type == config('constants.PROJECT_TYPE.SERVICE_PACKAGES')) {
            return Communication::where('type', $project_type)->where('service_package_id', $id)->get()->toArray();
        }
    }

    public static function getCommunicationCountById($id, $project_type = 'project')
    {
        if ($project_type === 'project') {
            return Communication::where('type', $project_type)->where('job_post_id', $id)->count();
        } elseif ($project_type == 'service_package') {
            return Communication::where('type', $project_type)->where('service_package_id', $id)->count();
        }
    }

    public static function updateStaySafeStatus($communications_id)
    {
        return Communication::where('id', $communications_id)->update(['stay_safe_status' => config('constants.APPROVED')]);
    }
    public static function getFirstCommunicationWithJobId($id, $job_post_id, $status)
    {
        return Communication::where('user_id', '=', $id)
            ->where('job_post_id', $job_post_id)
            ->where('status', $status)
            ->first();
    }
    public static function deleteCommunication($id, $job_post_id)
    {
        return Communication::where('user_id', '=', $id)
            ->where('job_post_id', $job_post_id)
            ->delete();
    }

    public static function fetchCommunications($conditions, $type = 'get', $query_options = [], $related_models = [])
    {
        $result = [];
        $query = self::where($conditions);
        if ($type == 'count')
        {
            $result = $query->count();
        }
        else
        {
            if (_count($related_models))
            {
                foreach ($related_models as $with)
                {
                    $query = $query->with($with);
                }
            }
            if (_count($query_options) && array_key_exists('orderBy',
                    $query_options))
            {
                $query->orderBy($query_options['orderBy'],
                    'desc');
            }
            if($type == 'first') 
                $result = $query->first();
            else
                $result = $query->get();
            if (_count($result))
            {
                $result = $result->toArray();
            }
        }
        return $result;
    }

    public function getUnreadMessageOfSpecificProject($inputs)
    {
        return $this
            ->join('messages', 'messages.communications_id', '=', 'communications.id')
            ->where('messages.read', false)
            ->when($inputs['project_type'] == 'project', function ($query) use ($inputs) {
                return $query->whereJobPostId($inputs['id']);
            })
            ->when($inputs['project_type'] != 'project', function ($query) use ($inputs) {
                return $query->whereServicePackageId($inputs['id']);
            })->count();
    }

    public function getLastMessageOfSpecificCommunication($communication_id) {
        return $this->with(['latestMessage' => function ($query) {
            $query->where('automated_message', false)->select('communications_id', 'msg');
        }])->whereId($communication_id)->first(['id']);
    }

    public function getProjectCommunicationInfoByExpertId($expert_id, $type, $id)
    {
        return $this
            ->where('user_id', '=', $expert_id)
            ->with(['buyerCompany' => function($query)
            {
                return $query->select('user_id', 'company_name');
            }])
            ->when($type == config('constants.PROJECT'), function($query) use ($id)
            {
                return $query->where('job_post_id', '=', $id);
            })
            ->when($type == config('constants.SERVICE_PACKAGE'), function($query) use ($id)
            {
                return $query->where('service_package_id', '=', $id);
            })
            ->first(['buyer_id']);
    }

    public function getCommunication($communication_id) {
        return $this->whereId($communication_id)->with('buyer', 'buyerBusinessInformation.businessDetails')->first();
    }

}
