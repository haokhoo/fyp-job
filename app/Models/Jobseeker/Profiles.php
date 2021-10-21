<?php

namespace App\Models\Jobseeker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'phone',
        'email',
        'address1',
        'address2',
        'city',
        'state',
        'postal',
        'country',
        'summary'
    ];

    
}
