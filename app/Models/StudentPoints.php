<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPoints extends Model
{
    use HasFactory;
    protected $fillable = [
        'kh_guid',
        'student_id',
        'student-code',
        'school-code',
        'remark',
        'points',
        'date',
        'd1',
        'd2',
        'd3',
        'is_sent',
        'activity_id',
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
    public function getCreatedAtColumn()
    {
        return date('Y-m-d H:i:s', $this->created_at);
    }
}
