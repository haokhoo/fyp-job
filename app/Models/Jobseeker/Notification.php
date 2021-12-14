<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'status',
        'to_user_id',
        'job_epy_id',
        'shorttext',
        'id'
    ];
}
