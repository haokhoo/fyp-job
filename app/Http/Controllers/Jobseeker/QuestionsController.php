<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Questions;
use App\Models\Employer\Companies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class QuestionsController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function index(Companies $companies)
    {
        return Questions::where('company_id', $companies->id)
            ->join('profiles', 'questions.user_id', '=', 'profiles.user_id')
            ->select('questions.id', 'questions.question', 'questions.created_at', 'profiles.fullname')
            ->orderBy('questions.created_at', 'desc')
            ->get();
    }

    //For employer
    public function shownewrecord(Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return Questions::where('company_id', $companies->id)
            ->join('profiles', 'questions.user_id', '=', 'profiles.user_id')
            ->select('questions.id', 'questions.question', 'questions.created_at','questions.status', 'profiles.fullname')
            ->orderBy('questions.created_at', 'desc')
            ->where('questions.status', 0)
            ->get();
    }

    public function show(Questions $questions)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return Questions::where('questions.id', $questions->id)
            ->join('profiles', 'questions.user_id', '=', 'profiles.user_id')
            ->select('questions.id', 'questions.question', 'questions.created_at' ,'profiles.fullname')
            ->get();
    }

    public function store(Request $request, Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('question');
        $validator = Validator::make($data, [
            'question' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $question = $this->user->question()->create([
            'question' => $request->question,
            'company_id' => $companies->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question sent successfully',
            'data' => $question
        ], Response::HTTP_OK);
    }
}
