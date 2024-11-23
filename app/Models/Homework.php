<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Homework extends Model
{
    use HasFactory;
    protected $fillable = [
        'kh_guid',
        'date',
        'student_id',
        'school-code',
        'student-code',
        'description',
        'responses',
        'can_response',
        'file-path',
        'is-image',
        'is-sent',
        'is-read',
        'is-sent-firebase',
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }
}
