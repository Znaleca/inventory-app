<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name',
        'title',
        'type',
        'specialization',
    ];

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->title ? $this->title.' ' : '').$this->name);
    }

    public function borrowLogs()
    {
        return $this->hasMany(Borrow::class);
    }
}
