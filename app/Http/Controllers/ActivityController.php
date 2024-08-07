<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Student;
use App\Models\StudentPoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use DB;

class ActivityController extends Controller
{
    public function getActivities(Request $request)
    {
        try {
            $activities_q = Activity::whereDate('end_date', '>=', date('Y-m-d'));
            if (!$request->has('school_code'))
                throw new \Exception('school code is required.');
            if (!$request->has('student_code'))
                throw new \Exception('student code is required.');
            $activities_q->where('school-code', $request->input('school_code'));
            $student = Student::where('school-code', $request->input('school_code'))->where('code', $request->input('student_code'))->first();
            $class = $student->class;
            $classroom = $student->classroom;
            $activities_q
                ->where(function ($q) use ($class) {
                    $q->where('class', $class)->orWhere('class', null);
                })
                ->where(function ($q) use ($classroom) {
                    $q->where('classroom', $classroom)->orWhere('classroom', null);
                });
            $activities = $activities_q->get();
            $total_points = $student->points()->sum('points') ?? 0;
            $activity_ids = $student->points()->pluck('student_points.activity_id')->all();
            foreach ($activities as $activity) {
                if (in_array($activity->id, $activity_ids)) {
                    $activity->status = 'joined';
                    continue;
                }
                if ($activity->max > 0 && $activity->count >= $activity->max) {
                    $activity->status = 'full';
                    continue;
                }
                if ($total_points < $activity->points) {
                    $activity->status = 'no_points';
                    continue;
                }
                $activity->status = 'available';
            }
            return (\json_encode($activities, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function joinActivity(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->has('school_code')) {
                $school_code = $request->input('school_code');
            }
            if ($request->has('student_code')) {
                $student_code = $request->input('student_code');
            }
            if ($request->has('activity_id')) {
                $activity_id = $request->input('activity_id');
            }
            $activity = Activity::where('id', $activity_id)->first();
            $student = Student::where('school-code', $school_code)->where('code', $student_code)->first();
            if ($student->points()->where('activity_id', $activity_id)->exists())
                throw new \Exception('لقد تم الاشتراك بهذا النشاط مسبقاً.');
            if ($activity->max > 0 && $activity->count >= $activity->max)
                throw new \Exception('اكتمل عدد الطلاب المسجلين بالنشاط.');
            if ($student->points()->sum('points') < $activity->points)
                throw new \Exception('ليس لديك العدد الكافي من النقاط للاشتراك.');
            $points = StudentPoints::create([
                'kh_guid' => Str::uuid(),
                'student_id' => $student->id,
                'student-code' => $student_code,
                'school-code' => $school_code,
                'remark' => $activity->title . ' - ' . $activity->remark,
                'points' => (-1) * $activity->points,
                'd1' => false,
                'd2' => false,
                'd3' => false,
                'activity_id' => $activity_id,
            ]);
            $activity->count++;
            $activity->save();
            DB::commit();
            return (\json_encode($points, JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info(\json_encode($e));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
