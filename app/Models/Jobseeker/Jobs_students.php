<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs_students extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'budget',
        'category',
        'position_type',
        'status'
    ];
}
