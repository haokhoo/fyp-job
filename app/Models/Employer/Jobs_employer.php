<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs_employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'desc',
        'budget',
        'category',
        'position_type',
        'status'
    ];
}
