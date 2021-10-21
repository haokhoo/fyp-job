<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_epy_id',
        'job_jsk_id',
        'company_id',
        'jsk_id',
        'question',
        'status'
    ];
}
