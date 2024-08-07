<?php

namespace App\Http\Controllers;

use App\Models\Remark;
use App\Models\Student;
use App\Models\RemarksCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RemarkController extends Controller
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
    //  * @param  \App\Models\Remark  $remark
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Remark $remark)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\Remark  $remark
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(Remark $remark)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Remark  $remark
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Remark $remark)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Remark  $remark
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Remark $remark)
    // {
    //     //
    // }

    public function test(Request $request)
    {
        if ($request->has('phone')) {
            $phone = $request->input('phone');
        }

        if ($request->has('title')) {
            $title = $request->input('title');
        }

        if ($request->has('text')) {
            $text = $request->input('text');
        }

        $user = User::where('name', $phone)->first();
        $FcmToken = $user->device_key;
        $messaging = app('firebase.messaging');
        $message = CloudMessage::withTarget('token', $FcmToken)
            ->withNotification(Notification::create($title, $text));
        $messaging->send($message);
    }

    public function addRemarkApi(Request $request)
    {
        try {
            if ($request->has('student_code')) {
                $student_code = $request->input('student_code');
            }

            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
            }

            if ($request->has('category_code')) {
                $category_code = $request->input('category_code');
            }

            if ($request->has('title')) {
                $title = $request->input('title');
            }

            if ($request->has('text')) {
                $text = $request->input('text');
            }


            $student_id = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->id;
            $student_user = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->user_id;
            $cat_id = RemarksCategory::where('code', $category_code)->get()[0]->id;
            $date = Carbon::now();

            $remark = Remark::create([
                'date' => $date,
                'remark_category_id' => $cat_id,
                'student_id' => $student_id,
                'school-code' => $school_code,
                'student-code' => $student_code,
                'title' => $title,
                'text' => $text,
                'is-sent' => false,
                'is-read' => false,
                'is-sent-firebase' => false,
                'category-code' => $category_code,
            ]);

            $FcmToken = User::where('id', $student_user)->where('freezed', '<>', true)->get()[0]->device_key;
            if ($FcmToken == null)
                return response('success', 200);
            // return response()->json([
            //     'status' => true,
            //     'message' => 'No Device Token for User, Remark saved but not sent'
            // ], 200);
            try {
                $messaging = app('firebase.messaging');
                $message = CloudMessage::withTarget('token', $FcmToken)
                    ->withNotification(Notification::create($title, $text));
                // ->withData(['key' => 'value']);

                $messaging->send($message);
            } catch (\Throwable $e) {
                Log::info('FCM exception:');
                Log::info('======================================');
                Log::info($e->getMessage());
                throw new \Exception('firebase exception');
            }
            $remark->{'is-sent-firebase'} = true;
            $remark->save();

            return response('success', 200);
        } catch (\Throwable $e) {
            DB::disconnect('mysql');
            $message = $e->getMessage();
            if (Str::contains($message, 'firebase exception'))
                return response('success', 200);
            else {
                Log::info($e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => $message
                ], 500);
            }
        }
    }
    public function postRemarkApi(Request $request)
    {
        if ($request->has('student_code')) {
            $student_codes = explode(";", $request->input('student_code'));
        }

        if ($request->has('school_code')) {
            $school_code = $request->input('school_code');
        }

        if ($request->has('category_code')) {
            $category_code = $request->input('category_code');
        }

        if ($request->has('title')) {
            $title = $request->input('title');
        }

        if ($request->has('text')) {
            $text = $request->input('text');
        }

        $path = NULL;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'bmp'];
            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $is_image = 1;
                if ($extension == 'pdf')
                    $is_image = 0;
                $request_file = $request->file;
                $path = $request_file->storeAs('public/files/remarks-files', $school_code . '-' . $name);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'invalid file'
                ], 422);
            }
        }

        foreach ($student_codes as $student_code) {
            try {
                $student_id = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->id;
                $student_user = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->user_id;
                $cat_id = RemarksCategory::where('code', $category_code)->get()[0]->id;
                $date = Carbon::now();

                if ($path)
                    $remark = Remark::firstOrCreate(
                        [
                            'student_id' => $student_id,
                            'file-path' => url('/') . str_replace('public/files/remarks-files', '/remarks', $path),
                        ],
                        [
                            'date' => $date,
                            'remark_category_id' => $cat_id,
                            'school-code' => $school_code,
                            'student-code' => $student_code,
                            'title' => $title,
                            'text' => $text,
                            'is-sent' => false,
                            'is-read' => false,
                            'is-sent-firebase' => false,
                            'category-code' => $category_code,
                            'is-image' => $is_image,
                        ]
                    );
                else
                    $remark = Remark::create(
                        [
                            'student_id' => $student_id,
                            'date' => $date,
                            'remark_category_id' => $cat_id,
                            'school-code' => $school_code,
                            'student-code' => $student_code,
                            'title' => $title,
                            'text' => $text,
                            'is-sent' => false,
                            'is-read' => false,
                            'is-sent-firebase' => false,
                            'category-code' => $category_code,
                        ]
                    );
                $FcmToken = User::where('id', $student_user)->where('freezed', '<>', true)->get()[0]->device_key;
                if ($FcmToken == null)
                    continue;
                // return response()->json([
                //     'status' => true,
                //     'message' => 'No Device Token for User, Remark saved but not sent'
                // ], 200);

                try {
                    $messaging = app('firebase.messaging');
                    $message = CloudMessage::withTarget('token', $FcmToken)
                        ->withNotification(Notification::create($title, $text));
                    // ->withData(['key' => 'value']);

                    $messaging->send($message);
                } catch (\Throwable $e) {
                    Log::info('FCM exception:');
                    Log::info('======================================');
                    Log::info($e->getMessage());
                    throw new \Exception('firebase exception');
                }

                $remark->{'is-sent-firebase'} = true;
                $remark->save();
            } catch (\Throwable $e) {
                DB::disconnect('mysql');
                $message = $e->getMessage();
                if (Str::contains($message, 'firebase exception'))
                    ;
                else {
                    Log::info($e->getMessage());
                    return response()->json([
                        'status' => false,
                        'message' => $message
                    ], 500);
                }
            }
        }
        return response('success', 200);
    }

    public function getRemarksApi(Request $request)
    {
        try {
            $user = auth()->user();
            $students = Student::where('user_id', $user->id)->pluck('id');
            if ($request->has('sent')) {
                $is_sent = $request->input('sent');
            }
            // if ($request->has('read')) {
            //     $is_read = $request->input('read');
            // }
            if ($is_sent == 0)
                $condition = [['is-sent', '=', 0]];
            else
                $condition = [['is-sent', '<>', NULL]];

            if ($request->has('student')) {
                $student = $request->input('student');
                array_push($condition, ['student-code', '=', (string) $student]);
            }
            if ($request->has('school')) {
                $school = $request->input('school');
                array_push($condition, ['school-code', '=', (string) $school]);
            }
            if ($request->has('category')) {
                $category = $request->input('category');
                array_push($condition, ['category-code', '=', (string) $category]);
            }
            //dd($condition);
            $remarks = Remark::whereIn('student_id', $students)
                ->where($condition)
                ->whereRelation('student', 'freezed', '<>', true)
                ->orderBy('date', 'ASC')->get();
            //freezed to test
            // $remarks = Remark::where($condition)
            //                     ->whereHas(['school' => function($query) {
            //                         return $query->where('freezed', '<>' ,'true');
            //                     },
            //                     'student' => function($query) {
            //                         return $query->where('freezed', '<>' ,'true');
            //                     }])->orderBy('date', 'DESC')->get();
            return (\json_encode($remarks, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsSent(Request $request)
    {
        try {

            $user = auth()->user();
            $students = Student::where('user_id', $user->id)->pluck('id');
            $condition = [['is-sent', '=', false]];

            //check if we want to mark as read
            $read = null;
            if ($request->has('read')) {
                $read = $request->input('read');
                $condition = [['is-read', '=', false]];
            }

            if ($request->has('student')) {
                $student = $request->input('student');
                array_push($condition, ['student-code', '=', (string) $student]);
            }
            if ($request->has('school')) {
                $school = $request->input('school');
                array_push($condition, ['school-code', '=', (string) $school]);
            }
            if ($request->has('category')) {
                $category = $request->input('category');
                array_push($condition, ['category-code', '=', (string) $category]);
            }

            if ($request->input('date')) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date'));
                \Log::info('sent');
                \Log::info($request->input('date'));
                \Log::info($date->toTimeString());
                \Log::info($date->toDateString());
            }

            //check if we want to mark as read
            $read = false;
            if ($request->has('read')) {
                $read = $request->input('read');
            }

            //get ids of marked remarks
            $updatedRemarks = Remark::whereIn('student_id', $students)->where($condition)
                ->where('date', '<=', $date)
                // ->whereTime('date','<=', $date->toTimeString())
                ->pluck('id');

            // \Log::info($updatedRemarks);

            //mark remarks as sent
            $remarks = Remark::whereIn('id', $updatedRemarks)->update(['is-sent' => true]);

            //mark remarks as read on condition
            if ($read != null) {
                $remarks = Remark::whereIn('id', $updatedRemarks)->update(['is-read' => true]);
            }

            //return updated remarks
            $remarks = Remark::whereIn('id', $updatedRemarks)->get();
            return (\json_encode($remarks, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        try {
            $user = auth()->user();
            $students = Student::where('user_id', $user->id)->pluck('id');
            $condition = [['is-read', '=', 0], ['is-sent', '<>', 0]];

            if ($request->has('student')) {
                $student = $request->input('student');
                array_push($condition, ['student-code', '=', (string) $student]);
            }
            if ($request->has('school')) {
                $school = $request->input('school');
                array_push($condition, ['school-code', '=', (string) $school]);
            }
            if ($request->has('category')) {
                $category = $request->input('category');
                array_push($condition, ['category-code', '=', (string) $category]);
            }
            if ($request->input('date')) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date'));
                \Log::info($request->input('date'));
                \Log::info($date->toTimeString());
                \Log::info($date->toDateString());
            }

            $updatedRemarks = Remark::whereIn('student_id', $students)->where($condition)
                ->where('date', '<=', $date)
                // ->whereDate('date','<=', $date->toDateString())
                // ->whereTime('date','<=', $date->toTimeString())
                ->pluck('id');

            $remarks = Remark::whereIn('id', $updatedRemarks)->update(['is-read' => true]);
            $remarks = Remark::whereIn('id', $updatedRemarks)->get();
            return (\json_encode($remarks, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
