<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\Jobseeker\Education;
use App\Models\Jobseeker\Profiles;
use App\Models\Jobseeker\Experiences;
use App\Models\Jobseeker\Skills;
use App\Models\Jobseeker\Resume;
use App\Models\Jobseeker\Review;
use App\Models\Jobseeker\Questions;
use App\Models\Jobseeker\Favourite_companies;
use App\Models\Jobseeker\Applicants;
use App\Models\Jobseeker\Favourite_jobs;
use App\Models\Jobseeker\Jobs_students;
use App\Models\Jobseeker\Question_jobs;

use App\Models\Employer\Companies;
use App\Models\Employer\Answers;
use App\Models\Employer\Jobs_employer;
use App\Models\Employer\Answer_jobs;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    //Chat Message----------------------------------------------------------------
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    //Jobseeker----------------------------------------------------------------------
    public function profiles()
    {
        return $this->hasOne(Profiles::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experiences::class);
    }

    public function skills()
    {
        return $this->hasMany(Skills::class);
    }

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function question()
    {
        return $this->hasMany(Questions::class);
    }

    public function fcompany()
    {
        return $this->hasMany(Favourite_companies::class);
    }

    public function applicant()
    {
        return $this->hasMany(Applicants::class);
    }

    public function job_j()
    {
        return $this->hasMany(Jobs_students::class);
    }

    public function fjob()
    {
        return $this->hasMany(Favourite_jobs::class);
    }

    public function question_job()
    {
        return $this->hasMany(Question_jobs::class);
    }

    //Employer-----------------------------------------------------------------------
    public function companies()
    {
        return $this->hasOne(Companies::class);
    }

    public function answer()
    {
        return $this->hasMany(Answers::class);
    }

    public function job_e()
    {
        return $this->hasMany(Jobs_employer::class);
    }

    public function answer_job()
    {
        return $this->hasMany(Answer_jobs::class);
    }
}
