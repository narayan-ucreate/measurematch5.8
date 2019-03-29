<?php

namespace App\Components;

use App\Model\ServiceHub;
use Auth;
use Exception;
use App\Model\{
        PostJob,
        User
    };

Class CommonFunctionsComponent {
    
    function projectsCount()
    {
        return [
            'live' => PostJob::getLiveProjects(date('Y-m-d H:i:s'))->count(),
            'pending' => PostJob::getPostListing(config('constants.PROJECT_PENDING'), null, [])->count(),
            'expired' => PostJob::getExpiredProjects(date('Y-m-d H:i:s'))->count(),
            'archived' => PostJob::getArchivedProjects()->count(),
            'in_contract' => (new PostJob)->getInContractProjects()->count(),
            'completed' => PostJob::getCompletedProjects()->count(),
            'rebookings' => PostJob::getRebookingProjects()->count(),
        ];
    }
    
    function userName($id, $with_last_initial = 0, $first_name_only=0) {
        $user = User::getUserById($id);
        if ($with_last_initial == 1) {
            return ucfirst($user['name']) . " " . ucfirst(substr($user['last_name'], 0, 1));
        }        
        if($first_name_only==1){
              return ucfirst($user['name']);
        }
        return ucfirst($user['name']) . " " . ucfirst($user['last_name']);
    }
    
    function getUserDetails($user_id) {
        $user = User::find($user_id);
        return [
            'first_name'=>ucfirst($user->name),
            'name' => ucfirst($user->name) . " " . ucfirst($user->last_name),
            'email' => $user->email,
            'user_type_id' => $user->user_type_id,
            'phone' => $user->phone_num
        ];
    }

    function vendorsCount()
    {
        $user = new User;
        return [
            'approved' => $user->approvedVendorsCount(),
            'pending' => $user->pendingVendorsCount(),
            'unverified' => $user->unverifiedVendorsCount(),
            'archived' => $user->archivedVendorsCount(),
        ];
    }

    function hubsCount()
    {
        $hub = new ServiceHub;
        return [
            'live' => $hub->liveHubsCount(),
            'pending' => $hub->pendingHubsCount(),
            'archived' => $hub->archivedHubsCount()
        ];
    }

    function expertsCount()
    {
        $user = new User;
        return [
            'approved' => $user->approvedExpertsCount(),
            'to_interview' => $user->expertsToInterviewCount(),
            'profile_incomplete' => $user->expertsWithIncompleteProfilecount(),
            'unverified' => $user->unverifiedExpertsCount(),
            'side_hustlers' => $user->sideHustlersExpertsCount(),
            'archived' => $user->archivedExpertscount(),
        ];
    }
}