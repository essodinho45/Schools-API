<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use RemarksCategory;
// use Student;
// use School;

class Remark extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'remark_category_id',
        'student_id',
        'school-code',
        'student-code',
        'title',
        'text',
        'is-sent',
        'is-read',
        'is-sent-firebase',
        'category-code',
        'is-image',
        'file-path',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RemarksCategory::class, 'remark_category_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }
}
