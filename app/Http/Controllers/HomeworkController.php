<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeworkController extends Controller
{
    public function postHomeworkFileApi(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'bmp'];
            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $request_file = $request->file;
                $path = $request_file->storeAs('public/files/homeworks-files' . (microtime(true) * 1000) . $name);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'invalid file'
                ], 422);
            }
        }
    }
    public function postHomeworkApi(Request $request)
    {
        if ($request->has('student_code')) {
            $student_codes = explode(";", $request->input('student_code'));
        }

        if ($request->has('school_code')) {
            $school_code = $request->input('school_code');
        }

        if ($request->has('description')) {
            $description = $request->input('description');
        }

        if ($request->has('responses')) {
            $responses = explode(";", $request->input('student_code'));
        }
        if ($request->has('can_response')) {
            $can_response = (bool) $request->input('can_response');
        }
        if ($request->has('kh_guid')) {
            $kh_guid = $request->input('kh_guid');
        }
        if ($request->has('subject')) {
            $subject = $request->input('subject');
        }
        $path = NULL;
        $is_image = false;
        if ($request->has('file_path')) {
            $path = $request->input('file_path');
            $is_image = !Str::endsWith($path, '.pdf');
        }

        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $allowedfileExtension = ['pdf', 'jpg', 'png', 'bmp'];
        //     $extension = $file->getClientOriginalExtension();
        //     $name = $file->getClientOriginalName();
        //     $check = in_array($extension, $allowedfileExtension);
        //     if ($check) {
        //         $is_image = 1;
        //         if ($extension == 'pdf')
        //             $is_image = 0;
        //         $request_file = $request->file;
        //         $path = $request_file->storeAs('public/files/homeworks-files', $school_code . '-' . $name);
        //         $url = url('/') . str_replace('public/files/homeworks-files', '/homeworks', $path);
        //         return response($url, 200);
        //     } else {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'invalid file'
        //         ], 422);
        //     }
        // }

        foreach ($student_codes as $student_code) {
            try {
                $student_id = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->id;
                $student_user = Student::where('code', $student_code)->where('school-code', $school_code)->get()[0]->user_id;
                $date = Carbon::now();

                if ($path)
                    $homework = Homework::firstOrCreate(
                        [
                            'student_id' => $student_id,
                            'file-path' => $path,
                        ],
                        [
                            'date' => $date,
                            'kh_guid' => $kh_guid,
                            'school-code' => $school_code,
                            'student-code' => $student_code,
                            'subject' => $subject,
                            'description' => $description,
                            'responses' => json_encode($responses, JSON_UNESCAPED_UNICODE),
                            'can_response' => $can_response,
                            'is-image' => $is_image,
                            'is-sent' => false,
                            'is-read' => false,
                            'is-sent-firebase' => false,
                        ]
                    );
                else
                    $homework = Homework::create(
                        [
                            'student_id' => $student_id,
                            'date' => $date,
                            'kh_guid' => $kh_guid,
                            'school-code' => $school_code,
                            'student-code' => $student_code,
                            'subject' => $subject,
                            'description' => $description,
                            'responses' => json_encode($responses, JSON_UNESCAPED_UNICODE),
                            'can_response' => $can_response,
                            'is-image' => $is_image,
                            'is-sent' => false,
                            'is-read' => false,
                            'is-sent-firebase' => false,
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
    public function getHomeworksApi(Request $request)
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
            if ($request->has('kh_guid')) {
                $kh_guid = $request->input('kh_guid');
                array_push($condition, ['kh_guid', '=', (string) $kh_guid]);
            }
            //dd($condition);
            $homeworks = Homework::whereIn('student_id', $students)
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
            return (\json_encode($homeworks, JSON_UNESCAPED_UNICODE));
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
            if ($request->has('kh_guid')) {
                $kh_guid = $request->input('kh_guid');
                array_push($condition, ['kh_guid', '=', (string) $kh_guid]);
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
            $updatedHomeworks = Homework::whereIn('student_id', $students)->where($condition)
                ->where('date', '<=', $date)
                // ->whereTime('date','<=', $date->toTimeString())
                ->pluck('id');

            // \Log::info($updatedRemarks);

            //mark remarks as sent
            $homeworks = Homework::whereIn('id', $updatedHomeworks)->update(['is-sent' => true]);

            //mark remarks as read on condition
            if ($read != null) {
                $homeworks = Homework::whereIn('id', $updatedHomeworks)->update(['is-read' => true]);
            }

            //return updated remarks
            $homeworks = Homework::whereIn('id', $updatedHomeworks)->get();
            return (\json_encode($homeworks, JSON_UNESCAPED_UNICODE));
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
            if ($request->has('kh_guid')) {
                $kh_guid = $request->input('kh_guid');
                array_push($condition, ['kh_guid', '=', (string) $kh_guid]);
            }
            if ($request->input('date')) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date'));
                \Log::info($request->input('date'));
                \Log::info($date->toTimeString());
                \Log::info($date->toDateString());
            }

            $updatedHomeworks = Homework::whereIn('student_id', $students)->where($condition)
                ->where('date', '<=', $date)
                // ->whereDate('date','<=', $date->toDateString())
                // ->whereTime('date','<=', $date->toTimeString())
                ->pluck('id');

            $homeworks = Homework::whereIn('id', $updatedHomeworks)->update(['is-read' => true]);
            $homeworks = Homework::whereIn('id', $updatedHomeworks)->get();
            return (\json_encode($homeworks, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
