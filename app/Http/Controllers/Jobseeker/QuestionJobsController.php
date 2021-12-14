<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Question_jobs;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use App\Models\Employer\Jobs_employer;
use App\Models\Jobseeker\Jobs_students;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class QuestionJobsController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Jobs_employer $ejob)
    {
        return Question_jobs::where('job_epy_id', $ejob->id)
            ->join('profiles', 'question_jobs.user_id', '=', 'profiles.user_id')
            ->select('question_jobs.id', 'question_jobs.question', 'question_jobs.created_at', 'profiles.fullname')
            ->orderBy('question_jobs.created_at', 'desc')
            ->get();
    }


    public function showS(User $id)
    {
        return Question_jobs::where('jsk_id', $id->id)
            ->get();
    }
    //question for job-student
    public function questionS(Request $request, Jobs_students $job_s)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('question');
        $validator = Validator::make($data, [
            'question' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $question = $this->user->question_job()->create([
            'question' => $request->question,
            'jsk_id' => $job_s->user_id,
            'job_jsk_id' => $job_s->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question sent successfully',
            'data' => $question
        ], Response::HTTP_OK);
    }

    //question for job-employer
    public function questionE(Request $request, Jobs_employer $job_e)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('question');
        $validator = Validator::make($data, [
            'question' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $question = $this->user->question_job()->create([
            'question' => $request->question,
            'company_id' => $job_e->company_id,
            'job_epy_id' => $job_e->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question sent successfully',
            'data' => $question
        ], Response::HTTP_OK);
    }

    //For employer
    public function showE(Question_jobs $ejob)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return Question_jobs::where('question_jobs.id', $ejob->id)
            ->join('profiles', 'question_jobs.user_id', '=', 'profiles.user_id')
            ->select('question_jobs.id', 'question_jobs.question', 'question_jobs.job_epy_id', 'question_jobs.created_at', 'profiles.fullname')
            ->orderBy('question_jobs.created_at', 'desc')
            ->get();
    }

    public function shownewrecord(Jobs_employer $ejob)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return Question_jobs::where('job_epy_id', $ejob->id)
            ->join('profiles', 'question_jobs.user_id', '=', 'profiles.user_id')
            ->select('question_jobs.id', 'question_jobs.question', 'question_jobs.created_at', 'question_jobs.status', 'profiles.fullname')
            ->where('question_jobs.status', 0)
            ->orderBy('question_jobs.created_at', 'desc')
            ->get();
    }
}
