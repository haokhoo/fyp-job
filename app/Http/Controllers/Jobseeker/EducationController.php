<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Education;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->education()
            ->get();
    }

    public function show(Education $education)
    {
        return $this->user
            ->education()
            ->where('id',$education->id)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'school' => 'required|string',
            'course' => 'required|string',
            'result' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $education = $this->user->education()->create([
            'school' => $request->school,
            'course' => $request->course,
            'result' => $request->result,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Education created successfully',
            'data' => $education
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Education $education)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'school' => 'required|string',
            'course' => 'required|string',
            'result' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $education = $education->update([
            'school' => $request->school,
            'course' => $request->course,
            'result' => $request->result,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Education updated successfully',
            'data' => $education
        ], Response::HTTP_OK);
    }

    public function destroy(Education $education)
    {
        $education->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Education deleted successfully',
            'data' => $education
        ], Response::HTTP_OK);
    }
}
