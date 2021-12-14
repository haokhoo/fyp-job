<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companylogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'user_id'
    ];
}
