<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\Model\{Language,RemoteWork,PostJob,Category};

class SellerSearchController extends Controller {
 
    public function __construct() {
        $this->middleware('auth');
    }
   
    public function searchProject(Request $request) {
        if(!expertAuth()){
            return redirect('/');
        }
        $projects = PostJob::getCurrentPublishedJobs();
        $view = $request->ajax() ? 'include.browse_project_common' : 'user_profile.seller_search';
        return view($view, compact('projects'));
    }    
}