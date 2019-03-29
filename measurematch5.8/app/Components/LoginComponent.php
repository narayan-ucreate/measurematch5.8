<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components;

class LoginComponent {



    public static function getDataFromCookieForPostAProjectFromHome($cookie=null,$form_data=null) {
        $project_from_home = '';
        if (isset($cookie) && !empty($cookie) && array_key_exists('project_from_home', $cookie)) {
            $project_from_home = json_decode($cookie['project_from_home'],TRUE);
             
        }
        return $project_from_home;
    }
    public static function setCookieForLogedInUser() {
        if(getenv('APP_ENV')=='production'){
            $config = config('session');
            setcookie ("logged_in_mm",true, time()+86400,'/', $config['domain'], $config['secure']);  
        }
    }
    public static function removeCookie($cookie_to_be_removed='',$domain=Null){
        if(!empty($cookie_to_be_removed)){ 
        setcookie($cookie_to_be_removed, "", time() - 100000, '/',$domain);
        }
        return true;
    }
    public static function clearAllCookiesAfterlogout() {
        if(getenv('APP_ENV')=='production'){
            self::removeCookie('logged_in_mm','.measurematch.com');
        }
    }
}
