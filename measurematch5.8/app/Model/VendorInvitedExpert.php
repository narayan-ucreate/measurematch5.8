<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorInvitedExpert extends Model
{
    public function findWithConditions($conditions, $type = 'get')
    {
        $result = [];
        $result = self::where($conditions);
        $result = $result->$type();
        if(!empty($result) && $type != 'count')
            $result = $result->toArray();
        
        return $result;
    }
    
    public function deleteWithConditions($expers_to_delete)
    {
        return self::whereIn('id', $expers_to_delete)->delete();
    }
    
    public function updateWithConditions($condition, $data_to_update)
    {
        return self::where($condition)->update($data_to_update);
    }
        
    public function checkIfDomainIsAlreadyRegistered($email)
    {
        $domain = substr(strrchr($email, "@"), 1);
        $query = static::selectRaw("substring(email from '@(.*)$') as domain, service_hub_id")
            ->groupBy('domain', 'service_hub_id');
        $result = static::selectRaw('service_hub_id')
                ->from(\DB::raw(' ( ' . $query->toSql() . ' ) AS sub '))
                ->where('domain', $domain)
                ->get();
        if(!empty($result))
        {
            $result = $result->toArray();
        }
        return $result;
    }
}
