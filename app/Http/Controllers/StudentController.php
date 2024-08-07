<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Student  $student
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Student $student)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Student  $student
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Student $student)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Student  $student
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Student $student)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Student  $student
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Student $student)
    // {
    //     //
    // }

    public function addStudent(Request $request, Faker $faker)
    {
        try {
            // 'user_phone',
            // 'name',
            // 'code',
            // 'gender',
            // 'school-code',
            // 'class',
            // 'classroom',
            // 'bus-line',
            // 'freezed',
            if ($request->has('user_phone')) {
                $user_phone = $request->input('user_phone');
            }

            if ($request->has('name')) {
                $name = $request->input('name');
            }

            if ($request->has('code')) {
                $code = $request->input('code');
            }

            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
            }

            if ($request->has('class')) {
                $class = $request->input('class');
            }

            if ($request->has('classroom')) {
                $classroom = $request->input('classroom');
            }

            $bus_line = ' ';
            if ($request->has('bus_line')) {
                if ($request->input('bus_line') != NULL && $request->input('bus_line') != '')
                    $bus_line = $request->input('bus_line');
            }

            $freezed = false;
            if ($request->has('freezed')) {
                $freezed = $request->input('freezed');
            }

            $gender = NULL;
            if ($request->has('gender')) {
                if ($request->input('gender') != NULL && $request->input('gender') != '')
                    $gender = $request->input('gender');
            }

            $user = User::where('name', $user_phone)->first();

            if (!$user)
                $user = User::create(['name' => $user_phone, 'email' => $faker->unique()->safeEmail, 'password' => Hash::make('123456')]);

            // return response()->json([
            //     'status' => false,
            //     'message' => 'Phone number not found'
            // ], 500);

            $student = Student::where([
                ['code', '=', $code],
                ['school-code', '=', $school_code]
            ])
                ->first();
            if (!$student)
                $student = Student::create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'gender' => $gender,
                    'code' => $code,
                    'school-code' => $school_code,
                    'class' => $class,
                    'classroom' => $classroom,
                    'bus-line' => $bus_line,
                    'freezed' => $freezed,
                ]);
            else
                Student::where([
                    ['code', '=', $code],
                    ['school-code', '=', $school_code]
                ])
                    ->update([
                        'user_id' => $user->id,
                        'name' => $name,
                        'gender' => $gender,
                        'class' => $class,
                        'classroom' => $classroom,
                        'bus-line' => $bus_line,
                        'freezed' => $freezed,
                    ]);
            return response('success', 200);
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getUserStudents(Request $request)
    {
        try {
            $user = auth()->user();
            $students = Student::where('user_id', $user->id)->where('freezed', '<>', true)
                ->whereRelation('school', 'freezed', '<>', true)
                ->get();
            foreach ($students as $student) {
                $student->setAttribute('school-name', $student->school->name);
                $student->setAttribute('use_points', $student->school->use_points);
                unset($student->school);
            }
            return (\json_encode($students, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function freezeStudent(Request $request)
    {
        try {
            if ($request->has('code')) {
                $code = $request->input('code');
            }

            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
            }

            $student = Student::where('school-code', $school_code)->where('code', $code)->first();
            $student->freezed = true;

            if (!$student->save()) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occured. Student has not been freezed.',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'name' => $student->name,
                'message' => 'Student freezed.',
            ], 200);


        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
