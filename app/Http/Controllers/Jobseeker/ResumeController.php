<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Resume;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function index()
    {
        return $this->user
            ->resume()
            ->get()
            ->last();

    }

    public function store(Request $request)
    {
        $data = $request->only('resume');
        $validator = Validator::make($data, [
            'resume' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        if ($request->hasFile('resume')) {
            $file      = $request->file('resume');
            $filename  = $file->getClientOriginalName();
            $picture   = date('YmdHis') . '_' . $filename;
            $move = $file->move(public_path('resumes'), $picture);

            if ($move) {
                Resume::create([
                    'resume' => 'resumes/' . $picture,
                    'user_id' => $this->user->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Resume uploaded successfully',
                    'data' => $picture
                ], Response::HTTP_OK);
            }
        }
    }
}
