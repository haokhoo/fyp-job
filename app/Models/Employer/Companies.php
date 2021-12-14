<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'website',
        'email',
        'phone',
        'address1',
        'address2',
        'city',
        'state',
        'postal',
        'country',
        'overview'
    ];
}
