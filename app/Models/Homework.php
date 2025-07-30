<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Homework extends Model
{
    use HasFactory;
    protected $table = 'homeworks';
    protected $fillable = [
        'kh_guid',
        'date',
        'student_id',
        'school-code',
        'student-code',
        'subject',
        'description',
        'responses',
        'can_response',
        'file-path',
        'is-image',
        'is-sent',
        'is-read',
        'is-sent-firebase',
        'response',
        'response_date',
        'response_read_by_admin',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => date('Y-m-d H:i:s', strtotime($attributes['created_at'])),
        );
    }
    protected function responses(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => json_decode($attributes['responses']),
        );
    }
}
