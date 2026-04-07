<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'disposed_by',
        'disposed_at',
        'reason',
    ];

    protected $casts = [
        'disposed_at' => 'datetime',
        'quantity' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
