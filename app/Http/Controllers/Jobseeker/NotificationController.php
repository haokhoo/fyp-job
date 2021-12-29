<?php

namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use App\Models\Employer\Jobs_employer;
use App\Models\Jobseeker\Applicants;
use App\Models\Jobseeker\Notification;
use App\Models\Jobseeker\Profiles;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function unread(Profiles $profiles)
    {
        return Notification::where('to_user_id', $profiles->user_id)
            ->where('status', 0)
            ->get();
    }

    public function read(Profiles $profiles)
    {
        return Notification::where('to_user_id', $profiles->user_id)
            ->where('status', 1)
            ->get();
    }

    public function show(Notification $notification)
    {
        $notification->update([
            'status' => 1
        ]);

        return Notification::where('notifications.id', $notification->id)
            ->join('jobs_employers', 'notifications.job_epy_id', '=', 'jobs_employers.id')
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.title as job_title', 'companies.company_name', 'notifications.title', 'notifications.desc', 'notifications.shorttext', 'notifications.created_at' )
            ->get();
    }
}
