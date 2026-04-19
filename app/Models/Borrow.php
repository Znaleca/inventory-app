<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrow extends Model
{
    protected $fillable = [
        'item_id', 'staff_id', 'bio_id', 'type', 'source_department', 'department', 'approved_by', 'quantity_borrowed', 'new_quantity', 'used_quantity',
        'quantity_returned', 'quantity_used', 'status', 'borrowed_at', 'return_date', 'returned_at', 'notes', 'borrower_name', 'serial_number',
    ];


    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'return_date' => 'date',
            'returned_at' => 'datetime',
            'quantity_borrowed' => 'integer',
            'new_quantity'      => 'integer',
            'used_quantity'     => 'integer',
            'quantity_returned' => 'integer',
            'quantity_used'     => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function borrowEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BorrowEntry::class);
    }
}
