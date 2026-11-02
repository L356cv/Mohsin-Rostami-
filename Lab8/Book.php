<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'pages',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
