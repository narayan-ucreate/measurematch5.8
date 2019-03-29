<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WebflowPage extends Model
{
    protected $fillable = [
        'webflow_collection_id', 'webflow_item_id', 'internal_id', 'type', 'webflow_url', 'user_id',
    ];

    public static function getPageByInternalId($id, $type){
        return WebflowPage::where([
            ['internal_id', $id],
            ['type', $type],
        ])->get()->first();
    }

    public static function getPageByUserId($id){
        return WebflowPage::where('user_id', $id)->get()->first();
    }
}
