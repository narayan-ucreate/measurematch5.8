<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackCommand extends Command {

    protected $signature = 'send:back {callback} {data} {to_back=false} {debug=false}';
    protected $description = 'Command description';
    private $back = true;
    private $debug = false;

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        
        $callback = $this->argument('callback');
        $data = $this->argument('data');
        $to_back = $this->argument('to_back');
        if ($to_back == false) {
            $this->back = false;
        }
        
        
        $debug = $this->argument('debug') === 'true' || $this->argument('debug') === true;
     
        if ($debug == true) {
            $this->debug = true;
        }
        if (!empty($data)) {
            $args_string = "";
            foreach ($data as $key => $val) {
                $args_string .= "--$key=$val ";
            }
        }
        if ($this->back == true && php_sapi_name() != 'cli') {
            $command = "nohup php " . base_path() . "/artisan " . $callback . " $args_string >/dev/null &";
        } else {
            $command = "php " . base_path() . "/artisan " . $callback . " $args_string";
        }
        if ($this->debug) {
            echo $command;
            return false;
            //exit;
        }

        exec($command, $output);
        return $output;
    }

}
