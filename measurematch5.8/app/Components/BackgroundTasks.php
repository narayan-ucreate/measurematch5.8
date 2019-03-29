<?php

namespace App\Components;

class BackgroundTasks {

    public static function trigger($callback, $callback_data = [], $options = []) {
       
        $default_options = [
            'callback' => $callback,
            'data' => $callback_data,
            'to_back' => true
        ];

        $final_options = array_merge($default_options, $options);
        return \Illuminate\Support\Facades\Artisan::call("send:back", $final_options);
    }

}
