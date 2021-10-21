<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Profiles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->profiles()
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->only('fullname','phone','email','address1','address2','city','state','postal','summary');
        $validator = Validator::make($data, [
            'fullname' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal' => 'required|integer',
            'summary' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $profiles = $this->user->profiles()->create([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'state' => $request->state,
            'postal' => $request->postal,
            'country' => "Malaysia",
            'summary' => $request->summary
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile created successfully',
            'data' => $profiles
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Profiles $profiles)
    {
        $data = $request->only('fullname','phone','email','address1','address2','city','state','postal','summary');
        $validator = Validator::make($data, [
            'fullname' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal' => 'required|integer',
            'summary' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $this->user->profiles()->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'state' => $request->state,
            'postal' => $request->postal,
            'summary' => $request->summary
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ], Response::HTTP_OK);
    }

    public function destroy(Profiles $profiles)
    {
        $this->user->profiles()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully'
        ], Response::HTTP_OK);
    }
}
