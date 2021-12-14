<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employer\Answers;
use App\Models\Employer\Companies;
use App\Models\Jobseeker\Questions;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AnswersController extends Controller
{
    protected $user;

    public function __construct()
    {
        //this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Companies $companies)
    {
        return Answers::where('company_id', $companies->id)
            ->select('answer', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show(Companies $companies, Questions $questions)
    {
        return Answers::where('company_id', $companies->id)
            ->where('question_id', $questions->id)
            ->select('answer', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request, Questions $questions)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('answer');
        $validator = Validator::make($data, [
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $answer = $this->user->answer()->create([
            'answer' => $request->answer,
            'question_id' => $questions->id,
            'company_id' => $questions->company_id,
            'jobseeker_id' => $questions->user_id

        ]);

         $questions->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Answer sent successfully',
            'data' => $answer
        ], Response::HTTP_OK);
    }
}
