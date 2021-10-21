<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Applicants;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use App\Models\Employer\Jobs_employer;
use App\Models\Jobseeker\Jobs_students;
use App\Models\Jobseeker\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicantsController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->applicant()
            ->get();
    }

    public function showPending()
    {
        return $this->user
            ->applicant()
            ->join('companies', 'applicants.company_id', '=', 'companies.id')
            ->join('jobs_employers', 'applicants.job_epy_job', '=', 'jobs_employers.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->where('applicants.status', 0)
            ->get();
    }

    public function showApproval()
    {
        return $this->user
            ->applicant()
            ->join('companies', 'applicants.company_id', '=', 'companies.id')
            ->join('jobs_employers', 'applicants.job_epy_job', '=', 'jobs_employers.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->where('applicants.status', 1)
            ->get();
    }

    public function showRejected()
    {
        return $this->user
            ->applicant()
            ->where('status', 2)
            ->get();
    }

    //Student apply job from company
    public function applyE(Request $request)
    {
        $data = $request->only('id', 'company_id');
        $validator = Validator::make($data, [
            'id' => 'required',
            'company_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $apply = $this->user->applicant()->updateOrCreate([
            'job_epy_job' => $request->id,
            'company_id' => $request->company_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job applied successfully.',
            'data' => $apply
        ], Response::HTTP_OK);
    }

    //Student apply job from student
    public function applyS(Jobs_students $job_s)
    {
        $apply = $this->user->applicant()->create([
            'job_jsk_job' => $job_s->id,
            'jsk_id' => $job_s->user_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job applied successfully.',
            'data' => $apply
        ], Response::HTTP_OK);
    }

    //Employer--------------------------------------------------------------------------
    public function display(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('company_id', $companies->id)
            ->where('job_epy_job', $job_e->id)
            ->where('status', 0)
            ->select(
                'applicants.id',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume')
            )
            ->get();
    }

    public function displayApproval(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('company_id', $companies->id)
            ->where('job_epy_job', $job_e->id)
            ->where('status', 1)
            ->select(
                'applicants.id',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume')
            )
            ->get();
    }

    public function displayRejected(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('company_id', $companies->id)
            ->where('job_epy_job', $job_e->id)
            ->where('status', 2)
            ->select(
                'applicants.id',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume')
            )
            ->get();
    }

    // public function displayPending(Companies $companies)
    // {
    //     return $this->user
    //         ->applicant()
    //         ->where('status', 0)
    //         ->where('company_id', $companies->id)
    //         ->get();
    // }

    // public function displayApproval(Companies $companies)
    // {
    //     return $this->user
    //         ->applicant()
    //         ->where('status', 1)
    //         ->where('company_id', $companies->id)
    //         ->get();
    // }

    // public function displayRejected(Companies $companies)
    // {
    //     return $this->user
    //         ->applicant()
    //         ->where('status', 2)
    //         ->where('company_id', $companies->id)
    //         ->get();
    // }

    public function approve(Applicants $applicants)
    {
        $approve = $applicants->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Applicant approved successfully',
            'data' => $approve
        ], Response::HTTP_OK);
    }

    public function reject(Applicants $applicants)
    {
        $job_e = $applicants->update([
            'status' => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Applicant rejected successfully',
            'data' => $job_e
        ], Response::HTTP_OK);
    }
}
