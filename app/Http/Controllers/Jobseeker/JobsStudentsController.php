<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Jobs_students;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class JobsStudentsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->job_j()
            ->get();
    }

    public function show(Jobs_students $job_j)
    {
        return $this->user
            ->job_j()
            ->where('id',$job_j)
            ->get();
    }

    public function showPending()
    {
        return $this->user
            ->job_j()
            ->where('status',0)
            ->get();
    }

    public function showApproval()
    {
        return $this->user
            ->job_j()
            ->where('status',1)
            ->get();
    }

    public function showRemoved()
    {
        return $this->user
            ->job_j()
            ->where('status',2)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'desc' => 'required',
            'budget' => 'required',
            'category' => 'required',
            'position_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        
        $job_j = $this->user->job_j()->create([
            'title' => $request->title,
            'desc' => $request->desc,
            'budget' => $request->budget,
            'category' => $request->category,
            'position_type' => $request->position_type

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully, please wait for admin approval.',
            'data' => $job_j
        ], Response::HTTP_OK);
    }

    //Admin----------------------------------------------
    public function approval(Jobs_students $job_j)
    {
        $job_j = $job_j->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job approval successfully',
            'data' => $job_j
        ], Response::HTTP_OK);
    }

    public function remove(Jobs_students $job_j)
    {
        $job_j = $job_j->update([
            'status' => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job removed successfully',
            'data' => $job_j
        ], Response::HTTP_OK);
    }
}
