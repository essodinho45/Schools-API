<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\PointsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RemarkController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//auth
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::get('/auth/logout', [AuthController::class, 'logout']);
Route::get('/freezeUser', [AuthController::class, 'freezeUser']);
Route::middleware('auth:sanctum')->post('/auth/password', [AuthController::class, 'changePassword']);
Route::middleware('auth:sanctum')->post('/auth/addkey', [AuthController::class, 'addDeviceKey']);
Route::middleware('auth:sanctum')->post('/auth/removekey', [AuthController::class, 'removeDeviceKey']);

//remarks
Route::get('/addRemark', [RemarkController::class, 'addRemarkApi'])->withoutMiddleware('throttle:api');
Route::post('/postRemark', [RemarkController::class, 'postRemarkApi'])->withoutMiddleware('throttle:api');
Route::get('/testFirebase', [RemarkController::class, 'test']);
Route::middleware('auth:sanctum')->get('/getRemarks', [RemarkController::class, 'getRemarksApi']);
Route::middleware('auth:sanctum')->get('/markAsRead', [RemarkController::class, 'markAsRead']);
Route::middleware('auth:sanctum')->get('/markAsSent', [RemarkController::class, 'markAsSent']);

//students
Route::get('/addStudent', [StudentController::class, 'addStudent'])->withoutMiddleware('throttle:api');
Route::get('/freezeStudent', [StudentController::class, 'freezeStudent']);
Route::middleware('auth:sanctum')->get('/getUserStudents', [StudentController::class, 'getUserStudents']);

//activities
Route::middleware('auth:sanctum')->get('/getActivities', [ActivityController::class, 'getActivities']);
Route::middleware('auth:sanctum')->post('/joinActivity', [ActivityController::class, 'joinActivity']);
Route::get('/addStudentPoints', [PointsController::class, 'addStudentPoints']);
Route::get('/getStudentPoints', [PointsController::class, 'getStudentPoints']);
Route::middleware('auth:sanctum')->get('/markPointsAsSent', [PointsController::class, 'markAsSent']);

//homeworks
Route::post('/postHomework', [HomeworkController::class, 'postHomeworkApi'])->withoutMiddleware('throttle:api');
Route::post('/postHomeworkFile', [HomeworkController::class, 'postHomeworkFileApi'])->withoutMiddleware('throttle:api');
Route::middleware('auth:sanctum')->get('/getHomeworks', [HomeworkController::class, 'getHomeworksApi']);
Route::middleware('auth:sanctum')->get('/markHomeworkAsRead', [HomeworkController::class, 'markAsRead']);
Route::middleware('auth:sanctum')->get('/markHomeworkAsSent', [HomeworkController::class, 'markAsSent']);
