<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_epy_job',
        'company_id',
        'job_jsk_job',
        'jsk_id',
        'status'
    ];
}
