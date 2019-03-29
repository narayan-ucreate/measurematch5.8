<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
     protected $table = 'password_resets';
     
        protected $fillable = [
            'email', 'token'
        ];
        
        public function getInfo($token) {
            return $this->whereToken($token)->first();
        } 
        
        public function deleteData($token) {
            $this->whereToken($token)->delete();
        }
        
        public function insertData($data) {
            return $this->insert($data);
        }
        
        
}
