<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'subject', 'name');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
