<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// use Student;
// use Remark;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'freezed',
        'use_points',
    ];
    protected $appends = ['remarks_count'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school-code', 'code');
    }

    public function remarks(): HasMany
    {
        return $this->hasMany(Remark::class, 'school-code', 'code');
    }
    public function points(): HasMany
    {
        return $this->hasMany(StudentPoints::class, 'school-code', 'code');
    }
    public function tests(): HasOne
    {
        return $this->hasOne(Test::class, 'school_id', 'id');
    }

    public function getRemarksCountAttribute()
    {
        $not_sent = $this->remarks()->where('is-sent', 0)->count();
        $sent = $this->remarks()->where('is-sent', 1)->count();
        return [
            'not_sent' => $not_sent,
            'sent' => $sent,
            'total' => $sent + $not_sent
        ];
    }
}
