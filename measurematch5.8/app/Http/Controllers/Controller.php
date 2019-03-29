<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mail;
use Response;

class Controller extends BaseController {

   use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

function download() {
        $file = $_GET['file'];
        $s3_bucket = getenv("S3_BUCKET_NAME");
        $bucket_url = getenv('BUCKET_URL');
        $file_path = $bucket_url. "/" . $s3_bucket . "/" . $file;
        $file_extension_all = explode('.', $file);
        $file_extension = end($file_extension_all);
        $image_mime = "application/octet-stream";
      
        if (!empty($file)) {
            $content = @file_get_contents($file_path);
        }
        
        if ($content) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $image_mime);
            header('Content-Disposition: attachment; filename=' . $file);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: public'); //for i.e.
            header('Pragma: public');
            echo $content;
        }
    }
    
   
}
