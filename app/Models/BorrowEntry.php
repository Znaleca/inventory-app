<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowEntry extends Model
{
    protected $fillable = [
        'borrow_id',
        'stock_entry_id',
        'original_condition',
        'disposition',
    ];

    public function borrow(): BelongsTo
    {
        return $this->belongsTo(Borrow::class);
    }

    public function stockEntry(): BelongsTo
    {
        return $this->belongsTo(StockEntry::class);
    }

    public function isPending(): bool
    {
        return is_null($this->disposition);
    }
}
