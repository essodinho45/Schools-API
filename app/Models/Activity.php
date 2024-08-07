<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'school-code',
        'title',
        'remark',
        'class',
        'classroom',
        'points',
        'max',
        'count',
        'end_date',
    ];
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school-code', 'code');
    }
}
