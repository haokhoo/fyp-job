<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiences extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title',
        'employer',
        'start_date',
        'end_date'
    ];
}
