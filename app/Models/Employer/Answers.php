<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'jobseeker_id',
        'question_id',
        'answer'
    ];
}
