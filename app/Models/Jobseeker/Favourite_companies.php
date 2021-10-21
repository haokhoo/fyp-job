<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite_companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id'
    ];
}
