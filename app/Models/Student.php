<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use \Remark;
// use \School;
// use \User;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'code',
        'school-code',
        'class',
        'classroom',
        'bus-line',
        'freezed',
    ];

    protected $appends = [
        'total_count',
        'not_read_count',
        'percentage',
    ];

    public function remarks(): HasMany
    {
        return $this->hasMany(Remark::class, 'student_id', 'id');
    }
    public function points(): HasMany
    {
        return $this->hasMany(StudentPoints::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTotalCountAttribute()
    {
        return $this->remarks->count();
    }

    public function getNotReadCountAttribute()
    {
        return $this->remarks->where('is-read', false)->count();
    }

    public function getPercentageAttribute()
    {
        if ($this->total_count == 0)
            return 0;
        return $this->not_read_count / $this->total_count;
    }
}
