<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    protected $fillable = [
        'item_id', 'quantity', 'new_quantity', 'used_quantity', 'type', 'destination',
        'transferred_by', 'transferred_to', 'department', 'bio_id', 'approved_by', 'transferred_at', 'notes', 'serial_number',
    ];

    protected function casts(): array
    {
        return [
            'transferred_at' => 'datetime',
            'quantity'       => 'integer',
            'new_quantity'   => 'integer',
            'used_quantity'  => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
