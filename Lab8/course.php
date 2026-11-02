<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = [
        'user_id',
        'course_code',
        'course_name',
        'credit_hours',
        'semester',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
