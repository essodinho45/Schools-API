<?php

use App\Models\Activity;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::get('/policy', function () {
    return view('success-privacy-policy');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::get('/config-clear', function () {
    $status = Artisan::call('config:clear');
    return '<h1>Configurations cleared</h1>';
});

//Clear cache:
Route::get('/cache-clear', function () {
    $status = Artisan::call('cache:clear');
    return '<h1>Cache cleared</h1>';
});

//Clear configuration cache:
Route::get('/config-cache', function () {
    $status = Artisan::call('config:cache');
    return '<h1>Configurations cache cleared</h1>';
});

//Generate Key:
Route::get('/gen-key', function () {
    $status = Artisan::call('key:generate');
    return '<h1>Key Generated</h1>';
});

//Generate Key:
Route::get('/migrate', function () {
    $status = Artisan::call('migrate');
    return '<h1>Migrated</h1>';
});

Route::get('/createlinks', function () {
    $status = Artisan::call('storage:link');
    return '<h1>Linked</h1>';
});

Route::get('/send-all', function () {
    Artisan::call('remarks:send');
    return Artisan::output();
});

Route::group(
    [
        'middleware' => [
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified'
        ]
    ],
    function () {
        Route::get('/lang/{locale}', function ($locale) {
            Session::put('applocale', $locale);
            return redirect()->back();
        })->name('lang');

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/students', function () {
            return view('admin.students');
        })->name('students');

        Route::get('/students-remark', function () {
            return view('admin.remark-for-students');
        })->name('remarkForStudents');

        Route::get('/activities', function () {
            return view('admin.activities');
        })->name('activities');
        Route::get('/points', function () {
            return view('admin.points');
        })->name('points');

        Route::get('/{id}/remarks', function ($id) {
            $student = Student::find($id);
            return view('admin.remarks', ['student' => $student]);
        })->name('studentRemarks');
        Route::get('/{id}/points', function ($id) {
            $student = Student::find($id);
            return view('admin.student-points', ['student' => $student]);
        })->name('studentPoints');

        Route::get('activity/{id}/students', function ($id) {
            $activity = Activity::find($id);
            return view('admin.activity-students', ['activity' => $activity]);
        })->name('activityStudents');

        Route::get('/all-remarks', function () {
            return view('admin.all-remarks');
        })->name('all-remarks');

        Route::get('/remarks-percent', function () {
            return view('admin.remarks-percent');
        })->name('remarksPercent');
    }
);

Route::group(
    [
        'middleware' => [
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
            'admin'
        ]
    ],
    function () {

        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');

        Route::get('/schools', function () {
            return view('admin.schools');
        })->name('schools');

        // Route::get('/all-remarks', function () {
        //     return view('admin.all-remarks');
        // })->name('all-remarks');
    }
);
