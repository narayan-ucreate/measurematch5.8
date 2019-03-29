<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Postmark\PostmarkClient;

class TriggerEmail extends Command {

    protected $signature = 'email:send {--data=}';
    protected $description = 'Command description';
    private static $client = null;

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $data = json_decode($this->option('data'), true);
        $sendResult = self::sendEmail($data);
        return (bool) ($sendResult['message'] == 'ok');
    }

    private static function getClient() {
        if (!self::$client) {
            self::$client = new PostmarkClient(env('POSTMARK_TOKEN'));
        }
        return self::$client;
    }

    public static function sendEmail($data) {
      
        return self::getClient()
                ->sendEmailWithTemplate($data['from'], $data['to'], $data['template_id'], $data['template_data']);
    }

    public static function sendRawEmail($data) {
        return self::getClient()
                ->sendEmail($data['from'], $data['to'], $data['subject'], $data['body']);
    }

}
