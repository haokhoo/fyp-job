<?php

namespace App\Http\Controllers\Employer;

use App\Models\Employer\Jobs_employer;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JobsEmployerController extends Controller
{
    protected $user;

    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return Jobs_employer::where('status', 1)
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            // ->select(
            //     'jobs_employers.*',
            //     DB::raw('(select company_name from companies where id  =   jobs_employers.company_id  order by id DESC LIMIT 1) as company_name'),
            //     DB::raw('(select state from companies where id  =   jobs_employers.company_id  order by id DESC) as state')
            // )
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function showCompanyJob(Companies $companies)
    {
        return Jobs_employer::where('company_id', $companies->id)
            ->where('status', 1)
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->get();
    }

    public function show(Jobs_employer $job_e)
    {
        return Jobs_employer::where('jobs_employers.id', $job_e->id)
            ->where('status', 1)
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->get();
    }

    public function showE(Jobs_employer $job_e)
    {
        return Jobs_employer::where('jobs_employers.id', $job_e->id)
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->get();
    }

    public function showPending()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return $this->user
            ->job_e()
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->where('status', 0)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function showApproval()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return $this->user
            ->job_e()
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function showRemoved()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        return $this->user
            ->job_e()
            ->join('companies', 'jobs_employers.company_id', '=', 'companies.id')
            ->select('jobs_employers.*', 'companies.company_name', 'companies.state')
            ->where('status', 2)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function store(Request $request, Companies $companies)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'desc' => 'required',
            'budget' => 'required',
            'category' => 'required',
            'position_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $job_e = $this->user->job_e()->create([
            'company_id' => $companies->id,
            'title' => $request->title,
            'desc' => $request->desc,
            'budget' => $request->budget,
            'category' => $request->category,
            'position_type' => $request->position_type

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully, please wait for admin approval.',
            'data' => $job_e
        ], Response::HTTP_OK);
    }

    //Admin----------------------------------------------
    public function approval(Jobs_employer $job_e)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $job_e = $job_e->update([
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job approval successfully',
            'data' => $job_e
        ], Response::HTTP_OK);
    }

    public function remove(Jobs_employer $job_e)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $job_e = $job_e->update([
            'status' => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job removed successfully',
            'data' => $job_e
        ], Response::HTTP_OK);
    }

    public function recover(Jobs_employer $job_e)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $job_e = $job_e->update([
            'status' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job recovered successfully',
            'data' => $job_e
        ], Response::HTTP_OK);
    }
}
