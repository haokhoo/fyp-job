<?php

namespace App\Http\Controllers\Employer;

use App\Models\Employer\Companylogo;
use App\Models\Employer\Companies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CompanylogoController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->logo()
            ->select('logo')
            ->get()
            ->last();
    }

    public function store(Request $request)
    {
        $this->user->logo()->delete();

        $data = $request->only('logo');
        $validator = Validator::make($data, [
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        if ($request->hasFile('logo')) {
            $file      = $request->file('logo');
            $filename  = $file->getClientOriginalName();
            $picture   = date('YmdHis') . '_' . $filename;
            $move = $file->move(public_path('logo'), $picture);

            if ($move) {
                Companylogo::create([
                    'logo' => 'logo/' . $picture,
                    'user_id' => $this->user->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Logo uploaded successfully',
                    'data' => $picture
                ], Response::HTTP_OK);
            }
        }
    }
}
