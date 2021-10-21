<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer_jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'jsk_id',
        'question_id',
        'answer'
    ];
}
