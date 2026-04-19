<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockEntry extends Model
{
    protected $fillable = [
        'item_id', 'quantity', 'lot_number', 'serial_number',
        'expiry_date', 'received_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'received_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class);
    }

    public function borrowEntries(): HasMany
    {
        return $this->hasMany(BorrowEntry::class);
    }
}
