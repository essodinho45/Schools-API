<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Remark;

class RemarksCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    public function remarks(): HasMany
    {
        return $this->hasMany(Remark::class, 'remark_category_id', 'id');
    }
}
