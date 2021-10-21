<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Skills;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function index()
    {
        return $this->user
            ->skills()
            ->get();
    }

    public function show(Skills $skills)
    {
        return $this->user
            ->skills()
            ->where('id',$skills->id)
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $skills = $this->user->skills()->create([
            'name' => $request->name,
            'rating' => $request->rating
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully',
            'data' => $skills
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Skills $skills)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $skills = $skills->update([
            'name' => $request->name,
            'rating' => $request->rating
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully',
            'data' => $skills
        ], Response::HTTP_OK);
    }

    public function destroy(Skills $skills)
    {
        $skills->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully',
            'data' => $skills
        ], Response::HTTP_OK);
    }
}
