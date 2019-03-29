<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Mail;

class PostmarkMailController extends Controller {

    /**
     * Send Mail Method
     */
    public function sendMail() {
        Mail::send('email', ['first_name' => 'Vikramjeet', 'last_name' => 'Singh', 'url' => 'google.com'], function ($m) {
            $m->from('vikramjeet@ucreate.co.in', 'measurematch');
            $m->to('rahulkumar@ucreate.co.in', 'Rahul Kumar')->subject('Please confirm your email address');
        });
    }

}
