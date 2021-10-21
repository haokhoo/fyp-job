<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Experiences;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class ExperiencesController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->experiences()
            ->get();
    }

    public function show(Experiences $experiences)
    {
        return $this->user
            ->experiences()
            ->where('id',$experiences->id)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'job_title' => 'required|string',
            'employer' => 'required|string',
            'start_date' => 'date',
            'end_date' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $experiences = $this->user->experiences()->create([
            'job_title' => $request->job_title,
            'employer' => $request->employer,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Experience created successfully',
            'data' => $experiences
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Experiences $experiences)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'job_title' => 'required|string',
            'employer' => 'required|string',
            'start_date' => 'date',
            'end_date' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $experiences = $experiences->update([
            'job_title' => $request->job_title,
            'employer' => $request->employer,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Education updated successfully',
            'data' => $experiences
        ], Response::HTTP_OK);
    }

    public function destroy(Experiences $experiences)
    {
        $experiences->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Experience deleted successfully',
            'data' => $experiences
        ], Response::HTTP_OK);
    }
}
