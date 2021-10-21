<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Jobseeker\ProfilesController;
use App\Http\Controllers\Jobseeker\EducationController;
use App\Http\Controllers\Jobseeker\ExperiencesController;
use App\Http\Controllers\Jobseeker\SkillsController;
use App\Http\Controllers\Jobseeker\ResumeController;
use App\Http\Controllers\Jobseeker\ReviewController;
use App\Http\Controllers\Jobseeker\QuestionsController;
use App\Http\Controllers\Jobseeker\FavouriteCompaniesController;
use App\Http\Controllers\Jobseeker\ApplicantsController;
use App\Http\Controllers\Jobseeker\JobsStudentsController;
use App\Http\Controllers\Jobseeker\FavouriteJobsController;
use App\Http\Controllers\Jobseeker\QuestionJobsController;


use App\Http\Controllers\Employer\CompaniesController;
use App\Http\Controllers\Employer\AnswersController;
use App\Http\Controllers\Employer\JobsEmployerController;
use App\Http\Controllers\Employer\AnswerJobsController;

Route::post('login-jobseeker', [ApiController::class, 'loginJobseeker']);//
Route::post('login-employer', [ApiController::class, 'loginEmployer']);//

Route::post('register-jobseeker', [ApiController::class, 'registerJobseeker']);//
Route::post('register-employer', [ApiController::class, 'registerEmployer']);//
Route::get('jobs', [JobsEmployerController::class, 'index']);//
Route::get('getOneJob/{job_e}', [JobsEmployerController::class, 'showE']);//
Route::get('allcompanies', [CompaniesController::class, 'getAllCompany']);//
Route::get('allcompanies/{companies}', [CompaniesController::class, 'show']);//
Route::get('jobs/{job_e}', [JobsEmployerController::class, 'show']);//
Route::get('company-jobs/{companies}', [JobsEmployerController::class, 'showCompanyJob']);//
Route::get('review/{companies}', [ReviewController::class, 'index']);//
Route::get('question-company/{companies}', [QuestionsController::class, 'index']);//
Route::get('answer-company/{companies}', [AnswersController::class, 'index']);//
Route::get('answer-company/{companies}/{questions}', [AnswersController::class, 'show']);//
Route::get('question-jobs/{ejob}', [QuestionJobsController::class, 'index']);//
Route::get('answer-job/{questions}', [AnswerJobsController::class, 'showE']); //display//

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [ApiController::class, 'logout']);//
    Route::get('get-user', [ApiController::class, 'get_user']);//
    //Jobseeker----------------------------------------------------------------------------------------
    //Profiles
    Route::get('profile', [ProfilesController::class, 'index']);//
    Route::post('profile', [ProfilesController::class, 'store']);//
    Route::put('profile',  [ProfilesController::class, 'update']);//
    Route::delete('profile',  [ProfilesController::class, 'destroy']);
    //Education
    Route::get('education', [EducationController::class, 'index']);
    Route::post('education', [EducationController::class, 'store']);//
    Route::put('education/{education}',  [EducationController::class, 'update']);//
    Route::get('education/{education}', [EducationController::class, 'show']);//
    Route::delete('education/{education}',  [EducationController::class, 'destroy']);//
    //Experiences
    Route::get('experiences', [ExperiencesController::class, 'index']);
    Route::get('experiences/{experiences}', [ExperiencesController::class, 'show']);//
    Route::post('experiences', [ExperiencesController::class, 'store']);//
    Route::put('experiences/{experiences}',  [ExperiencesController::class, 'update']);//
    Route::delete('experiences/{experiences}',  [ExperiencesController::class, 'destroy']);//
    //Skills
    Route::get('skills', [SkillsController::class, 'index']);
    Route::get('skills/{skills}', [SkillsController::class, 'show']);//
    Route::post('skills', [SkillsController::class, 'store']);//
    Route::put('skills/{skills}',  [SkillsController::class, 'update']);//
    Route::delete('skills/{skills}',  [SkillsController::class, 'destroy']);//
    //Resume
    Route::get('resume', [ResumeController::class, 'index']);//
    Route::post('resume', [ResumeController::class, 'store']);//
    //Review
    Route::post('review/{companies}', [ReviewController::class, 'store']);//
    Route::put('review/{review}', [ReviewController::class, 'report']);
    //Question-campany
    Route::post('question-company/{companies}', [QuestionsController::class, 'store']);//
    //Chat
    Route::get('message', [ChatController::class, 'index']);
    Route::get('message-employer/{companies}', [ChatController::class, 'showCompany']);
    Route::post('message-employer/{companies}', [ChatController::class, 'sendCompany']);
    //Favourite Company
    Route::get('favourite-company', [FavouriteCompaniesController::class, 'index']);//
    Route::post('favourite-company/{companies}', [FavouriteCompaniesController::class, 'store']);//
    Route::delete('favourite-company/{fcompany}',  [FavouriteCompaniesController::class, 'destroy']);//
    //Applicant apply
    Route::get('applicant-display', [ApplicantsController::class, 'index']);
    Route::get('applicant-pending', [ApplicantsController::class, 'showPending']);//
    Route::get('applicant-approve', [ApplicantsController::class, 'showApproval']);//
    Route::get('applicant-reject', [ApplicantsController::class, 'showRejected']);
    Route::post('applicants-apply/{job_e}', [ApplicantsController::class, 'applyE']);//
    Route::post('applicant-apply/{job_s}', [ApplicantsController::class, 'applyS']);
    //Job
    Route::get('job', [JobsStudentsController::class, 'index']);
    Route::get('job-pending', [JobsStudentsController::class, 'showPending']);
    Route::get('job-approve', [JobsStudentsController::class, 'showApproval']);
    Route::get('job-remove', [JobsStudentsController::class, 'showRemoved']);
    Route::get('job/{job_j}', [JobsStudentsController::class, 'show']);
    Route::post('job', [JobsStudentsController::class, 'store']);
    Route::put('job-approve/{job_j}',  [JobsStudentsController::class, 'approval']); //admin
    Route::put('job-remove/{job_j}',  [JobsStudentsController::class, 'remove']);
    //Favourite Job
    Route::get('favourite-jobs', [FavouriteJobsController::class, 'index']);//
    Route::post('favourite-job/{fjob_s}', [FavouriteJobsController::class, 'addS']);
    Route::delete('favourite-job/{fjob_s}',  [FavouriteJobsController::class, 'removeS']);
    Route::post('favourite-jobs/{fjob_e}', [FavouriteJobsController::class, 'addE']);//
    Route::delete('favourite-jobs/{fjob_e}',  [FavouriteJobsController::class, 'removeE']);//
    //Question-job
    Route::get('question-job/{id}', [QuestionJobsController::class, 'showS']); //display for student who post the job
    Route::post('question-job/{job_s}', [QuestionJobsController::class, 'questionS']);
    Route::post('question-jobs/{job_e}', [QuestionJobsController::class, 'questionE']);//
    //Answer-job
    Route::get('answer-job', [AnswerJobsController::class, 'index']);
    Route::get('answer-job{id}', [AnswerJobsController::class, 'showS']); //display for student who post the job
    Route::post('answer-job/{question_s}', [AnswerJobsController::class, 'answerS']);

    //Employer----------------------------------------------------------------------------------------
    //Company Information
    Route::get('companies', [CompaniesController::class, 'index']);//
    Route::post('companies', [CompaniesController::class, 'store']);//
    Route::put('companies',  [CompaniesController::class, 'update']);//
    //Get All Review
    Route::get('review-company/{companies}', [ReviewController::class, 'show']);
    //Question
    Route::get('question-dialog/{questions}', [QuestionsController::class, 'show']);//
    //Question job
    Route::get('question-dialogs/{ejob}', [QuestionJobsController::class, 'showE']); //display question
    //Answer
    Route::post('answer-company/{questions}', [AnswersController::class, 'store']);//
    //Chat
    Route::get('message', [ChatController::class, 'index']);
    Route::get('message-jobseeker/{user}', [ChatController::class, 'showJobseeker']);
    Route::post('message-jobseeker/{user}', [ChatController::class, 'sendJobseeker']);
    //Job
    // Route::get('jobs', [JobsEmployerController::class, 'index']);
    Route::get('jobs-pending', [JobsEmployerController::class, 'showPending']);//
    Route::get('jobs-approve', [JobsEmployerController::class, 'showApproval']);//
    Route::get('jobs-remove', [JobsEmployerController::class, 'showRemoved']);//
    Route::post('jobs/{companies}', [JobsEmployerController::class, 'store']);//
    Route::put('jobs-approve/{job_e}',  [JobsEmployerController::class, 'approval']); //admin
    Route::put('jobs-remove/{job_e}',  [JobsEmployerController::class, 'remove']);//
    Route::put('jobs-recover/{job_e}',  [JobsEmployerController::class, 'recover']);//

    //Applicant apply
    Route::get('applicants-display/{job_e}/{companies}', [ApplicantsController::class, 'display']);//
    Route::get('applicants-pending/{companies}', [ApplicantsController::class, 'displayPending']);
    Route::get('applicants-approve/{job_e}/{companies}', [ApplicantsController::class, 'displayApproval']);//
    Route::get('applicants-reject/{job_e}/{companies}', [ApplicantsController::class, 'displayRejected']);//
    Route::put('applicants-approve/{applicants}',  [ApplicantsController::class, 'approve']); //
    Route::put('applicants-reject/{applicants}',  [ApplicantsController::class, 'reject']);//
    //Answer-job
    Route::post('answer-jobs/{question_e}', [AnswerJobsController::class, 'answerE']);
});
