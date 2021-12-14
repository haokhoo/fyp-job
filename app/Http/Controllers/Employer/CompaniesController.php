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
            ->join('companylogos', 'companies.user_id', '=', 'companylogos.user_id')
            ->select('companies.id', 'companylogos.logo', 'companies.company_name', 'companies.website', 'companies.email', 'companies.phone', 'companies.address1', 'companies.address2', 'companies.city', 'companies.state', 'companies.postal', 'companies.overview')
            ->get()
            ->last();
    }

    public function getAllCompany()
    {
        return DB::table('companies')
            ->join('companylogos', 'companies.user_id', '=', 'companylogos.user_id')
            ->select('companies.id', 'companylogos.logo', 'companies.company_name', 'companies.website', 'companies.email', 'companies.phone', 'companies.address1', 'companies.address2', 'companies.city', 'companies.state', 'companies.postal', 'companies.overview')
            ->groupBy('companies.id')
            ->get();
        
            // return DB::table('companylogos')
            // ->select('user_id','logo')
            // ->groupBy('user_id')
            // ->get();
    }

    public function show(Companies $companies)
    {
        return Companies::where('companies.id', $companies->id)
            ->join('companylogos', 'companies.user_id', '=', 'companylogos.user_id')
            ->select('companies.id', 'companylogos.logo', 'companies.company_name', 'companies.website', 'companies.email', 'companies.phone', 'companies.address1', 'companies.address2', 'companies.city', 'companies.state', 'companies.postal', 'companies.overview')
            ->get();
    }

    public function store(Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('company_name', 'website', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal', 'overview');
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
            'overview' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }



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
            'overview' => $request->overview
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company information inserted successfully',
            'data' => $companies
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->only('company_name', 'website', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal', 'overview');
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
            'overview' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }


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
            'overview' => $request->overview
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company information updated successfully',
            'data' => $companies
        ], Response::HTTP_OK);
    }
}
