<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestStudentSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_student_id',
        'subject_id',
        'mark',
        'full_mark',
    ];
}
