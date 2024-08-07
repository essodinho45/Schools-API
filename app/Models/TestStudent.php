<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TestStudent extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_id',
        'full_name',
        'father_name',
        'mother_name',
        'previous_school',
        'phone',
        'whatsapp_mobile',
    ];
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'test_student_subjects', 'test_student_id', 'subject_id')->withPivot('mark', 'full_mark');
    }
}
