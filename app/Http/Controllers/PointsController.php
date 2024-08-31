<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentPoints;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use Carbon\Carbon;
use Response;

class PointsController extends Controller
{
    public function addStudentPoints(Request $request)
    {
        try {
            if ($request->has('guid')) {
                $kh_guid = $request->input('guid');
            }
            if ($request->has('student_code')) {
                $student_code = $request->input('student_code');
            }
            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
            }
            if ($request->has('name')) {
                $remark = $request->input('name');
            }
            if ($request->has('pcount')) {
                $points = $request->input('pcount');
            }
            if ($request->has('d1')) {
                $d1 = $request->input('d1');
            }
            if ($request->has('d2')) {
                $d2 = $request->input('d2');
            }
            if ($request->has('date')) {
                $date = $request->input('date');
            }
            $student = Student::where('school-code', $school_code)->where('code', $student_code)->first();
            if (!$student)
                throw new \Exception('student not found');
            $points = StudentPoints::updateOrCreate(
                [
                    'kh_guid' => $kh_guid
                ],
                [
                    'student_id' => $student->id,
                    'student-code' => $student_code,
                    'school-code' => $school_code,
                    'remark' => $remark,
                    'points' => $points,
                    'date' => $date,
                    'd1' => $d1 == 'true' ? 1 : 0,
                    'd2' => $d2 == 'true' ? 1 : 0,
                    'is_sent' => false
                ]
            );
            $FcmToken = User::where('id', $student->user_id)->where('freezed', '<>', true)->get()[0]->device_key;
            if ($FcmToken == null)
                return response('success', 200);
            // return response()->json([
            //     'status' => true,
            //     'message' => 'No Device Token for User, Remark saved but not sent'
            // ], 200);
            $title = mb_convert_encoding($student->school->name, 'UTF-8', 'UTF-8');
            $body = mb_convert_encoding($remark, 'UTF-8', 'UTF-8');
            try {
                $messaging = app('firebase.messaging');
                $message = CloudMessage::withTarget('token', $FcmToken)
                    ->withNotification(Notification::create($title, $body));
                // ->withData(['key' => 'value']);

                $messaging->send($message);
                return response('success', 200);
            } catch (\Throwable $e) {
                Log::info('FCM exception:');
                Log::info('======================================');
                Log::info($e->getMessage());
                throw new \Exception('firebase exception');
            }
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getStudentPoints(Request $request)
    {
        try {
            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
                $points_q = StudentPoints::where('school-code', $school_code);
            } else {
                $user = auth('sanctum')->user();
                if (!$user)
                    return Response::json(['message' => 'Unauthenticated.'], 401);
                $students = Student::where('user_id', $user->id)->pluck('id');
                $points_q = StudentPoints::whereIn('student_id', $students)
                    ->whereRelation('student', 'freezed', '<>', true);
            }
            if ($request->has('sent')) {
                $is_sent = $request->input('sent');
                if (!$is_sent)
                    $points_q->where('is_sent', 0);
            }
            if ($request->has('student_code')) {
                $student_code = $request->input('student_code');
                $points_q->where('student-code', $student_code);
            }
            $data = false;
            if ($request->has('data')) {
                $data = $request->input('data');
                if ($data == 'mobile')
                    $data = 'is_sent';
                $points_q->where($data, 0);
            }
            $points = $points_q->orderBy('date', 'ASC')->get();
            if (!$points || empty($points))
                throw new \Exception('no points found');
            if ($data && $data != 'is_sent') {
                $update_points = $points;
                foreach ($update_points as $point) {
                    $point->update([$data => 1]);
                }
            }
            return (\json_encode($points, JSON_UNESCAPED_UNICODE));
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
            $condition = [['is_sent', '=', false]];

            if ($request->has('student')) {
                $student = $request->input('student');
                array_push($condition, ['student-code', '=', (string) $student]);
            }
            if ($request->has('school')) {
                $school = $request->input('school');
                array_push($condition, ['school-code', '=', (string) $school]);
            }

            if ($request->input('date')) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date'));
                \Log::info('activity');
                \Log::info($request->input('date'));
                \Log::info($date->toTimeString());
                \Log::info($date->toDateString());
            }

            //get ids of marked remarks
            $updatedPoints = StudentPoints::whereIn('student_id', $students)
                ->where($condition)
                ->where('created_at', '<=', $date)
                ->pluck('id');

            //mark remarks as sent
            $points = StudentPoints::whereIn('id', $updatedPoints)->update(['is_sent' => true]);

            //return updated remarks
            $points = StudentPoints::whereIn('id', $updatedPoints)->get();
            return (\json_encode($points, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
