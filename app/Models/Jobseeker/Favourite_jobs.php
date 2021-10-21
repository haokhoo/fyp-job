<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite_jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_epy_id',
        'job_jsk_id'
    ];
}
