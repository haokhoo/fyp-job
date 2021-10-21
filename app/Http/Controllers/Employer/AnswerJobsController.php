<?php

namespace App\Http\Controllers\Employer;

use App\Models\Employer\Answer_jobs;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use App\Models\Jobseeker\Question_jobs;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AnswerJobsController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->answer_job()
            ->get();
    }

    public function showS(User $id)
    {
        return Answer_jobs::where('jsk_id', $id->id)
            ->get();
    }
    //Answer for job-student
    public function answerS(Request $request, Question_jobs $question_s)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('answer');
        $validator = Validator::make($data, [
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $answer = $this->user->answer_job()->create([
            'answer' => $request->answer,
            'jsk_id' => $question_s->user_id,
            'question_id' => $question_e->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Answer sent successfully',
            'data' => $answer
        ], Response::HTTP_OK);
    }

    //Answer for job-employer
    public function answerE(Request $request, Question_jobs $question_e)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('answer');
        $validator = Validator::make($data, [
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $answer = $this->user->answer_job()->create([
            'answer' => $request->answer,
            'company_id' => $question_e->company_id,
            'question_id' => $question_e->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Answer sent successfully',
            'data' => $answer
        ], Response::HTTP_OK);
    }

    //For employer
    public function showE(Question_jobs $questions)
    {
        return Answer_jobs::where('question_id', $questions->id)
            ->select('answer', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
