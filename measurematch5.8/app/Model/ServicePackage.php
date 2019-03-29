<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ServicePackage extends Model {

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'service_packages';
    protected $fillable = ['user_id', 'name', 'description', 'service_packages_category_id', 'buyer_remarks',
        'price', 'duration', 'is_approved', 'is_hidden', 'subscription_type', 'publish', 'service_package_type_id'
    ];
    protected $dates = ['deleted_at'];

    public function userDetails() {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public function servicePackageType() {
        return $this->belongsTo('App\Model\ServicePackageType', 'service_package_type_id', 'id');
    }
    
    public function contract(){
        return $this->hasMany('App\Model\Contract', 'service_package_id', 'id');
    }
    
    public function contractFeedbacks() {
        return $this->hasmany('App\Model\Contract', 'service_package_id')->where('buyer_feedback_status', 1)->orderBy('feedback_time', 'DESC');
    }

    public function servicePackageCategory() {
        return $this->belongsTo('App\Model\Category', 'service_packages_category_id');
    }

    public function deliverables() {
        return $this->hasMany('App\Model\Deliverable', 'service_package_id', 'id')->where('type','service_package');
    }

    public function servicePackageTags() {
        return $this->hasMany('App\Model\ServicePackageTag', 'service_package_id', 'id');
    }

    public function communication() {
        return $this->hasMany('App\Model\Communication', 'service_package_id')->orderBy('communications.created_at', 'DESC');
    }

    public static function getServicePackageById($id) {
        return self::where('id', '=', $id)->get();
    }
    
    public static function getServicePackageByUserId($user_id) {
        return self::where('user_id', '=', $user_id)->get();
    }
    public static function getServicePackage($id) {
        return self::where('id', '=', $id)->first();
    }
    public static function getVisitorsCount($service_package_id) {
        return self::where('id', '=', $service_package_id)->pluck('visitors_count')->first();
    }
    public static function updateVisitorsCount($service_package_id) {
        return self::whereId($service_package_id)->increment('visitors_count');
    }
    public static function updateServicePackage($service_package_id, $update_data) {
        return self::where('id', $service_package_id)->update($update_data);
    }
    public static function updateServicePackageWithUserId($user_id, $update_data) {
       return self::where('user_id', $user_id)->update($update_data);
   }
    public static function scopeBasicConditions($query){
      return $query->where('is_approved', 'TRUE')
                    ->where('is_hidden', 'FALSE');
    }
    public static function checkExistance($conditions){
        return self::where($conditions)->exists();
    }
    public static function fetchServicePackages($conditions, $type = '', $related_models = []) {
        if ($type == 'count') {
            $result = self::where($conditions)->count();
        } elseif ($type == 'first') {
            $result = self::where($conditions)->first();
            if (_count($result)) {
                $result = $result->toArray();
            }
        } else {
            $query = self::where($conditions);
            if (_count($related_models)) {
                foreach ($related_models as $with) {
                    $query = $query->with($with);
                }
            }
            $result = $query->orderBy('created_at', 'desc')->get();
            if (_count($result)) {
                $result = $result->toArray();
            }
        }
        return $result;
    }

    public static function getServicePackages($conditions = [], $withs = [], $query_options = []) {
        if (is_array($conditions) && _count($conditions)) {
            $conitions_array = [];
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
            ->withCount('communication')
            ->where($conitions_array);
        if (isset($withs) && !empty($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }

        if (isset($query_options['whereIn'])) {
            foreach($query_options['whereIn'] as $field => $ids){
                $query->whereIn($field, $ids);
            }
        }
        if (isset($query_options['order_by']))
        {
            $query->orderBy($query_options['order_by'][0], $query_options['order_by'][1]);
        }
        else
        {
            $query->orderBy('created_at', 'desc');
        }
        if (isset($query_options['limit'])) {
            $query->limit($query_options['limit']);
        }
        if (isset($query_options['offset'])) {
            $query->offset($query_options['offset']);
        }
        if (isset($query_options['paginate'])) {
            $result = $query->paginate($query_options['paginate']);
        } elseif (isset($query_options['count'])) {
            $result = $query->count();
        } elseif (isset($query_options[0]) && $query_options[0] == 'first') {
            $result = $query->first();
            if (_count($result)) {
                $result = $result->toArray();
            }
        } else {
            $result = $query->get();
        }
        return $result;
    }

    public static function getPackageListingWithUserSorting($condition, $paginate, $order) {
        if (isset($order['orderBy']) && !empty($order['orderBy'])) {
            $order_by = $order['orderBy'];
        } else {
            $order_by = 'ASC';
        }
        $query = ServicePackage::join('users as u', 'u.id', '=', 'service_packages.user_id')
                ->select('*', 'service_packages.id as package_id', 'service_packages.name as package_name', 'service_packages.created_at as package_date', DB::raw("concat(u.name, ' ', u.last_name) AS myname"))
                ->with('userDetails')
                ->withCount('communication')
                ->where($condition);
        if (isset($order['data-sort']) && !empty($order['data-sort'])) {
            $sorting_field_in_array = explode(',', $order['data-sort']);
            foreach ($sorting_field_in_array as $field_value) {
                $query = $query->orderBy($field_value, $order_by);
            }
        } else {
            $query->orderBy('service_packages.created_at', $order_by);
        }
        $result = $query->paginate($paginate);
        return $result;
    }
    
    public static function getOther($search, $keys, $expert_ids, $query_options){
        if(!empty($search)){
            $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
            ->basicConditions()
            ->where(function($q) use ($search)
            {
                $q->where('name', 'iLike', '%'.$search.'%')
                   ->orWhere('description', 'iLike', '%'.$search.'%');
            })
            ->where(function($q) use ($keys) {
                $q->where('service_package_type_id', NULL)
                ->orWhereNotIN('service_package_type_id', $keys);
            });
            if(_count($expert_ids)){
                $query->whereIn('user_id', $expert_ids);
            }
        }else{
            $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
            ->basicConditions()
            ->where(function($q) use ($keys) {
                $q->where('service_package_type_id', NULL)
                ->orWhereNotIN('service_package_type_id', $keys);
            });
            if(_count($expert_ids)){
                $query->whereIn('user_id', $expert_ids);
            }
        }
        
        $query->with('userDetails.user_profile');
        $query->orderBy('created_at', 'desc');
        if (isset($query_options['limit'])) {
            $result = $query->limit($query_options['limit']);
        }
        if (isset($query_options['offset'])) {
            $result = $query->offset($query_options['offset']);
        }
        if (isset($query_options['paginate'])) {
            $result = $query->paginate($query_options['paginate']);
        }elseif (isset($query_options['count'])) {
            $result = $query->count();
        }elseif (isset($query_options['exists'])) {
            $result = $query->exists();
        }else{
            $result = $query->get();
        }
        return $result;
    }
    
    public static function searchNameDescription($search, $selected_featured_package, $type, $expert_ids = [], $related_models=[], $query_options=''){
        $result = [];
        if(!empty($search)){
            $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
                ->basicConditions()
                ->where(function($q) use ($search)
                {
                    $q->where('name', 'iLike', '%'.$search.'%')
                       ->orWhere('description', 'iLike', '%'.$search.'%');
                });
            if(!empty($selected_featured_package) && is_numeric($selected_featured_package)){
                $query->where('service_package_type_id', $selected_featured_package);
            }
            if(_count($expert_ids)){
                $query->whereIn('user_id', $expert_ids);
            }
        }else{
            if(!empty($selected_featured_package) && is_numeric($selected_featured_package)){
                $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
                    ->basicConditions()
                    ->where('service_package_type_id', $selected_featured_package);
                if(_count($expert_ids)){
                    $query->whereIn('user_id', $expert_ids);
                }
            }elseif(_count($expert_ids)){
                $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
                    ->basicConditions()
                    ->whereIn('user_id', $expert_ids);
            }else{
                $query = ServicePackage::selectRaw('*, count(*) OVER() as total')
                        ->basicConditions();
            }
        }
        if (isset($query) && isset($related_models) && !empty($related_models)) {
            foreach ($related_models as $related_model) {
                $query = $query->with($related_model);
            }
        }
        if (isset($query) && isset($query_options['limit'])) {
            $query->limit($query_options['limit']);
        }
        if (isset($query) && isset($query_options['offset'])) {
            $query->offset($query_options['offset']);
        }
        if(isset($query)){
            $query->orderBy('created_at', 'desc');
            $result = $query->$type();
        }
        return $result;
    }
    
    public static function savedPackages($buyer_id, $conditions = [], $withs = [], $query_options = ''){
        $conitions_array = [];
        if (is_array($conditions) && _count($conditions)) {
            foreach ($conditions as $key => $val) {
                $conitions_array[$key] = $val;
            }
        }
        $query = self::join('saved_service_packages', function($join) use ($buyer_id)
                {
                    $join->on('saved_service_packages.service_package_id', '=', 'service_packages.id');
                    $join->where('saved_service_packages.buyer_id', '=', $buyer_id);
                });
        if(_count($conitions_array)){
            $query->where($conitions_array);
        }
        if (_count($withs)) {
            foreach ($withs as $with) {
                $query = $query->with($with);
            }
        }
        if (isset($query_options['order_by'])) {
            $query->orderBy($query_options['order_by'][0], $query_options['order_by'][1]);
        }
        if (isset($query_options['limit'])) {
            $query->limit($query_options['limit']);
        }
        if (isset($query_options['offset'])) {
            $query->offset($query_options['offset']);
        }
        if (isset($query_options['paginate'])) {
            $result = $query->paginate($query_options['paginate']);
        } elseif (isset($query_options['count'])) {
            $result = $query->count();
        } elseif (isset($query_options[0]) && $query_options[0] == 'first') {
            $result = $query->first();
            if (_count($result)) {
                $result = $result->toArray();
            }
        } else {
            $result = $query->get();
            if (_count($result)) {
                $result = $result->toArray();
            }
        }
        return $result;
    }
   
}
