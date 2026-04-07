<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'category_id', 'name', 'condition', 'sku',
        'description', 'unit', 'unit_price', 'reorder_level', 'stock_used',
        'is_one_time_use',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'reorder_level' => 'integer',
            'is_one_time_use' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class);
    }

    /**
     * Get total available NEW stock.
     */
    public function getTotalStockAttribute(): int
    {
        $received        = $this->stockEntries()->sum('quantity');
        $usedLogs        = $this->usageLogs()->where('stock_type', 'new')->sum('quantity_used');
        $transferredOut  = $this->transfers()->where('type', 'out')->sum('new_quantity');
        $transferredIn   = $this->transfers()->where('type', 'in')->sum('new_quantity');
        $disposedNew     = $this->disposals()->where('type', 'new')->sum('quantity');

        $borrowImpact = $this->borrows()->get()->sum(function($borrow) {
            $net = $this->is_one_time_use 
                ? ($borrow->quantity_borrowed - $borrow->quantity_returned) 
                : $borrow->quantity_borrowed;
            
            return $borrow->type === 'in' ? -$net : $net;
        });

        return (int) max(0, $received + $transferredIn - $usedLogs - $transferredOut - $disposedNew - $borrowImpact);
    }

    /**
     * Get effective used stock (db column adjusted by used-stock transfers).
     */
    public function getEffectiveStockUsedAttribute(): int
    {
        $usedOut = $this->transfers()->where('type', 'out')->sum('used_quantity');
        $usedIn  = $this->transfers()->where('type', 'in')->sum('used_quantity');
        return (int) max(0, $this->stock_used - $usedOut + $usedIn);
    }

    /**
     * Check if the item is below its reorder level.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->reorder_level;
    }

    /**
     * Get the nearest expiry date from active stock.
     */
    public function getNearestExpiryAttribute(): ?string
    {
        $entry = $this->stockEntries()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->orderBy('expiry_date')
            ->first();

        return $entry?->expiry_date;
    }

    /**
     * Get a breakdown of stock by batch (remaining quantity per StockEntry).
     */
    public function getBatchesBreakdownAttribute(): array
    {
        // 1. Get all batches sorted by expiry (earliest first)
        $batches = $this->stockEntries()
            ->orderByRaw('expiry_date IS NULL, expiry_date ASC')
            ->orderBy('received_date', 'ASC')
            ->get();

        // 2. Calculate net unlinked deductions (Transfer Out + Borrows − Transfer In).
        // Transfer In adds stock, so we subtract it from deductions.
        $transferredOut = $this->transfers()->where('type', 'out')->sum('new_quantity');
        $transferredIn  = $this->transfers()->where('type', 'in')->sum('new_quantity');
        $disposedNew    = $this->disposals()->where('type', 'new')->sum('quantity');
        $borrowImpact = $this->borrows()->get()->sum(function($borrow) {
            $net = $this->is_one_time_use 
                ? ($borrow->quantity_borrowed - $borrow->quantity_returned) 
                : $borrow->quantity_borrowed;
            
            return $borrow->type === 'in' ? -$net : $net;
        });

        $unlinkedDeductions = max(0, $transferredOut + $borrowImpact + $disposedNew - $transferredIn);
        $breakdown = [];

        foreach ($batches as $batch) {
            $received = $batch->quantity;
            // Usage logs ARE linked to specific batches
            $usedInLogs = $batch->usageLogs()->sum('quantity_used');
            $available = (int) ($received - $usedInLogs);

            if ($available <= 0) continue;

            // Deduct unlinked items from this batch (FIFO)
            $toDeduct = min($available, $unlinkedDeductions);
            $remaining = $available - $toDeduct;
            $unlinkedDeductions -= $toDeduct;

            if ($remaining > 0) {
                $breakdown[] = [
                    'id' => $batch->id,
                    'lot_number' => $batch->lot_number,
                    'expiry_date' => $batch->expiry_date,
                    'received_date' => $batch->received_date,
                    'remaining' => $remaining,
                ];
            }
        }

        return $breakdown;
    }

    /**
     * Check if the item can be safely deleted without losing transaction history.
     */
    public function getCanBeDeletedAttribute(): bool
    {
        return !($this->stockEntries()->exists() || 
                 $this->usageLogs()->exists() || 
                 $this->borrows()->exists() || 
                 $this->transfers()->exists() || 
                 $this->disposals()->exists());
    }
}
