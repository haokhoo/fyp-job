<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Review;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Companies $companies)
    {
        return Review::where('company_id', $companies->id)
            ->join('profiles', 'reviews.user_id', '=', 'profiles.user_id')
            ->select('reviews.review', 'reviews.created_at', 'profiles.fullname')
            ->orderBy('reviews.created_at', 'desc')
            ->get();
    }

    //For employer
    public function show(Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return Review::where('company_id', $companies->id)
            ->get();
    }

    public function store(Request $request, Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('review');
        $validator = Validator::make($data, [
            'review' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $review = $this->user->review()->create([
            'review' => $request->review,
            'company_id' => $companies->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review sent successfully',
            'data' => $review
        ], Response::HTTP_OK);
    }

    public function report(Review $review)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $review = $review->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review reported successfully',
            'data' => $review
        ], Response::HTTP_OK);
    }
}
