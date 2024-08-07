<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_id',
        'title',
        'is_active',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(TestStudent::class, 'test_student_subjects', 'subject_id', 'test_student_id');
    }
}
