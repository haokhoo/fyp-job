<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Favourite_jobs;
use App\Http\Controllers\Controller;
use App\Models\Employer\Jobs_employer;
use App\Models\Jobseeker\Jobs_students;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class FavouriteJobsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->fjob()
            ->join('jobs_employers', 'favourite_jobs.job_epy_id', '=', 'jobs_employers.id')
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.title', 'jobs_employers.updated_at', 'companies.company_name',
            'companies.state', 'jobs_employers.budget',
            'jobs_employers.desc', 'jobs_employers.position_type', 'favourite_jobs.id', 'favourite_jobs.job_epy_id')
            ->get();
    }

    //Job Students
    public function addS(Jobs_students $fjob_s)
    {
        $fjob_s = $this->user->fjob()->updateOrCreate([
            'job_jsk_id' => $fjob_s->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job add to favourite successfully',
            'data' => $fjob_s
        ], Response::HTTP_OK);
    }

    public function removeS(Favourite_jobs $fjob_s)
    {
        $fjob_s->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job has been removed from favourite.'
        ], Response::HTTP_OK);
    }

    //Job Employer
    public function addE(Jobs_employer $fjob_e)
    {
        $fjob_e = $this->user->fjob()->updateOrCreate([
            'job_epy_id' => $fjob_e->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job add to favourite successfully',
            'data' => $fjob_e
        ], Response::HTTP_OK);
    }

    public function removeE(Favourite_jobs $fjob_e)
    {
        $fjob_e->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job has been removed from favourite.'
        ], Response::HTTP_OK);
    }
}
