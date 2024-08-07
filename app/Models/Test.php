<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'school_id',
        'min_mark',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'test_id', 'id');
    }
    public function students(): HasMany
    {
        return $this->hasMany(TestStudent::class, 'test_id', 'id');
    }
}
