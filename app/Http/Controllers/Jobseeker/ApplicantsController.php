<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Applicants;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use App\Models\Employer\Jobs_employer;
use App\Models\Jobseeker\Education;
use App\Models\Jobseeker\Experiences;
use App\Models\Jobseeker\Jobs_students;
use App\Models\Jobseeker\Resume;
use App\Models\Jobseeker\Skills;
use App\Models\Jobseeker\Notification;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
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

        if ($this->user->profiles()->exists()) {
            $apply = $this->user->applicant()->updateOrCreate([
                'job_epy_job' => $request->id,
                'company_id' => $request->company_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job applied successfully.',
                'data' => $apply
            ], Response::HTTP_OK);
        } else {
            return response()->json(['error' => "Please fill in your profile information before your apply the job!"], 400);
        }
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
    public function displayfordashboard(Companies $companies)
    {
        return Applicants::where('company_id', $companies->id)
            ->where('applicants.status', 0)
            ->get();
    }

    public function display(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('applicants.company_id', $companies->id)
            ->join('profiles', 'applicants.user_id', '=', 'profiles.user_id')
            ->where('applicants.job_epy_job', $job_e->id)
            ->where('applicants.status', 0)
            ->select(
                'applicants.id',
                'applicants.created_at',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume'),
                'fullname',
                'email',
                'phone',
                'applicants.user_id',
                'address1',
                'address2',
                'city',
                'state',
                'postal',
                'country',
                'summary'
            )
            ->get();
    }

    public function displayApproval(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('applicants.company_id', $companies->id)
            ->join('profiles', 'applicants.user_id', '=', 'profiles.user_id')
            ->where('applicants.job_epy_job', $job_e->id)
            ->where('applicants.status', 1)
            ->select(
                'applicants.id',
                'applicants.created_at',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume'),
                'fullname',
                'email',
                'phone',
                'applicants.user_id',
                'address1',
                'address2',
                'city',
                'state',
                'postal',
                'country',
                'summary'
            )
            ->get();
    }

    public function displayRejected(Jobs_employer $job_e, Companies $companies)
    {
        return Applicants::where('applicants.company_id', $companies->id)
            ->join('profiles', 'applicants.user_id', '=', 'profiles.user_id')
            ->where('applicants.job_epy_job', $job_e->id)
            ->where('applicants.status', 2)
            ->select(
                'applicants.id',
                'applicants.created_at',
                DB::raw('(select resume from resumes where user_id  =   applicants.user_id  order by id DESC limit 1) as resume'),
                'fullname',
                'email',
                'phone',
                'applicants.user_id',
                'address1',
                'address2',
                'city',
                'state',
                'postal',
                'country',
                'summary'
            )
            ->get();
    }

    public function approve(Applicants $applicants)
    {
        $applicants->update([
            'status' => 1
        ]);

            DB::table('notifications')->insert([
            'to_user_id' => $applicants->user_id,
            'job_epy_id' => $applicants->job_epy_job,
            'title' => 'Your job application has been shortlisted!',
            'desc' => 'Good news! Your job application below has been shortlisted by an employer.',
            'shorttext' => 'You are being considered for this job. The employer will decide whether to select you for an interview',
            'status' => 0

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Applicant approved successfully'
        ], Response::HTTP_OK);
    }

    public function reject(Applicants $applicants)
    {
        $applicants->update([
            'status' => 2
        ]);

        $this->user->notification()->create([
            'to_user_id' => $applicants->user_id,
            'job_eyp_id' => $applicants->job_epy_job,
            'title' => 'Your job application unlikely to progress further.',
            'desc' => 'Your job application below is unlikely to progress further.',
            'shorttext' => 'Each employer or recruiter has their own process so you may or may not hear back from them.',
            'status' => 0

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Applicant rejected successfully'
        ], Response::HTTP_OK);
    }

    public function get_education(User $user)
    {
        return Education::where('education.user_id', $user->id)
            ->get();
    }

    public function get_skill(User $user)
    {
        return Skills::where('skills.user_id', $user->id)
            ->get();
    }

    public function get_experience(User $user)
    {
        return Experiences::where('experiences.user_id', $user->id)
            ->get();
    }
}
