<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceHubRejectedApplicantDetail extends Model
{
    protected $fillable = ['service_hub_applicant_id', 'message'];
}
