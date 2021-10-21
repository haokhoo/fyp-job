<?php

namespace App\Http\Controllers\Employer;

use App\Models\Employer\Companies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CompaniesController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return $this->user
            ->companies()
            ->get()
            ->last();
    }

    public function getAllCompany()
    {
        return DB::table('companies')
            ->select('id', 'logo', 'company_name')
            ->get();
    }

    public function show(Companies $companies)
    {
        return Companies::where('id', $companies->id)
            ->select('id', 'logo', 'company_name', 'website', 'email', 'phone', 'address1', 'address2', 'city', 'state','postal', 'overview')
            ->get();
    }

    public function store(Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('company_name', 'website', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal', 'overview', 'logo');
        $validator = Validator::make($data, [
            'company_name' => 'required|string',
            'website' => 'required',
            'email' => 'required|email|unique:profiles',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal' => 'required|integer',
            'overview' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }


        if ($request->hasFile('logo')) {
            $file      = $request->file('logo');
            $filename  = $file->getClientOriginalName();
            $picture   = date('YmdHis') . '_' . $filename;
            $move = $file->move(public_path('company_logo'), $picture);

            if ($move) {
                $companies = $this->user->companies()->create([
                    'company_name' => $request->company_name,
                    'website' => $request->website,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal' => $request->postal,
                    'country' => "Malaysia",
                    'overview' => $request->overview,
                    'logo' => 'company_logo/' . $picture
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Company information inserted successfully',
                    'data' => $companies
                ], Response::HTTP_OK);
            }
        }
    }

    public function update(Request $request, Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('company_name', 'website', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal', 'overview', 'logo');
        $validator = Validator::make($data, [
            'company_name' => 'required|string',
            'website' => 'required',
            'email' => 'required|email|unique:profiles',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal' => 'required|integer',
            'overview' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        if ($request->hasFile('logo')) {
            $file      = $request->file('logo');
            $filename  = $file->getClientOriginalName();
            $picture   = date('YmdHis') . '_' . $filename;
            $move = $file->move(public_path('company_logo'), $picture);

            if ($move) {
                $companies = $this->user->companies()->update([
                    'company_name' => $request->company_name,
                    'website' => $request->website,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address1' => $request->address1,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal' => $request->postal,
                    'overview' => $request->overview,
                    'logo' => 'company_logo/' . $picture
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Company information updated successfully',
                    'data' => $companies
                ], Response::HTTP_OK);
            }
        }
    }
}
