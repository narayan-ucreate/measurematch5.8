<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stripe extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'stripes';
    protected $fillable = [
    ];/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public static function updateVatNumber($id,$vat_number){
       return Stripe::where('user_id', $id)->update(['vat_number' => $vat_number]);
   }
   public static function getStripeInformation($stripe_id){
       return Stripe::where('id', $stripe_id)->pluck('strip_response');
   }
   public static function updateStripeInformation($stripe_id,$array_with_values){
       return Stripe::where('id', $stripe_id)->update($array_with_values);
   }

}
