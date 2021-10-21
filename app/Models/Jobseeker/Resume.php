<?php

namespace App\Models\Jobseeker;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume',
        'auto_resume',
        'user_id'
    ];
}
