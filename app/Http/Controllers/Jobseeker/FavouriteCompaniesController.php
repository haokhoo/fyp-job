<?php

namespace App\Http\Controllers\Jobseeker;

use App\Models\Jobseeker\Favourite_companies;
use App\Http\Controllers\Controller;
use App\Models\Employer\Companies;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class FavouriteCompaniesController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->fcompany()
            ->join('companies', 'favourite_companies.company_id', '=', 'companies.id')
            ->join('companylogos', 'companies.user_id', '=', 'companylogos.user_id')
            ->select('companylogos.logo', 'companies.company_name', 'favourite_companies.company_id', 'favourite_companies.id')
            ->get();
    }

    public function store(Companies $companies)
    {
        $fcompany = $this->user->fcompany()->updateOrCreate([
            'company_id' => $companies->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company add to favourite successfully',
            'data' => $fcompany
        ], Response::HTTP_OK);
    }

    public function destroy(Favourite_companies $fcompany)
    {
        $fcompany->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Company has been removed from favourite.'
        ], Response::HTTP_OK);
    }
}
