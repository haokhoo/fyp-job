<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->chat()
            ->get();
    }

    //For Jobseeker-----------------------------------------------------------------------
    public function showCompany(Companies $companies)
    {
        return $this->user
            ->chat()
            // ->where('to_user_id',$companies->user_id,'user_id',$this->user->id)
            ->where([
                ['to_user_id', $companies->user_id],
                ['user_id', $this->user->id],
            ])
            ->orWhere([
                ['to_user_id', $this->user->id],
                ['user_id', $companies->user_id],
            ])
            ->get();
    }

    public function sendCompany(Request $request, Companies $companies)
    {
        $data = $request->only('message');
        $validator = Validator::make($data, [
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $message = $this->user->chat()->create([
            'message' => $request->message,
            'to_user_id' => $companies->user_id

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], Response::HTTP_OK);
    }

    //For Employer-------------------------------------------------------------
    public function showJobseeker(User $user)
    {
        return $this->user
            ->chat()
            ->where('to_user_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->get();
    }

    public function sendJobseeker(Request $request, User $user)
    {
        $data = $request->only('message');
        $validator = Validator::make($data, [
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $message = $this->user->chat()->create([
            'message' => $request->message,
            'to_user_id' => $user->id

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], Response::HTTP_OK);
    }
}
