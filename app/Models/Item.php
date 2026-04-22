<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'category_id', 'item_type', 'brand', 'model',
        'name', 'condition', 'serial_number',
        'description', 'unit', 'unit_price', 'stock_used',
        'is_one_time_use', 'is_expirable', 'storage_location', 'storage_section',
        'reorder_level',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'      => 'decimal:2',
            'is_one_time_use' => 'boolean',
            'is_expirable'    => 'boolean',
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

        // For devices, only the new_quantity borrow out counts against new stock
        // For consumables, the full quantity_borrowed - quantity_returned counts
        if ($this->item_type === 'device') {
            $borrowImpact = $this->borrows()->with('borrowEntries')->get()->sum(function($borrow) {
                if ($borrow->new_quantity > 0) {
                    $returnedNew = $borrow->borrowEntries->where('disposition', 'returned_new')->count();
                    $net = $borrow->new_quantity - $returnedNew;
                } else {
                    $net = ($borrow->used_quantity > 0 ? 0 : $borrow->quantity_borrowed - $borrow->quantity_returned); // legacy borrows
                }
                return $borrow->type === 'in' ? -$net : $net;
            });
        } else {
            $borrowImpact = $this->borrows()->get()->sum(function($borrow) {
                $net = $borrow->quantity_borrowed - $borrow->quantity_returned;
                return $borrow->type === 'in' ? -$net : $net;
            });
        }

        return (int) max(0, $received + $transferredIn - $usedLogs - $transferredOut - $disposedNew - $borrowImpact);
    }

    /**
     * Get effective used stock (db column adjusted by used-stock transfers).
     */
    public function getEffectiveStockUsedAttribute(): int
    {
        $usedOut = $this->transfers()->where('type', 'out')->sum('used_quantity');
        $usedIn  = $this->transfers()->where('type', 'in')->sum('used_quantity');

        // Subtract used devices that are currently lent out (pending return)
        $usedBorrowedOut = $this->borrows()->where('type', 'out')->sum('used_quantity');
        $usedBorrowedIn  = $this->borrows()->where('type', 'in')->sum('used_quantity');

        return (int) max(0, $this->stock_used - $usedOut + $usedIn - $usedBorrowedOut + $usedBorrowedIn);
    }

    /**
     * Check if the item is out of stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= 0;
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
        // 1. Get all NEW batches sorted by expiry (earliest first)
        // A batch is NEW if it has no returned_used log AND it doesn't contain [USED] explicitly
        $batches = $this->stockEntries()
            ->whereDoesntHave('borrowEntries', function($sub) {
                $sub->where('disposition', 'returned_used');
            })
            ->whereDoesntHave('usageLogs', function($sub) {
                $sub->where('quantity_used', '>', 0);
            })
            ->where(function($q) {
                $q->where('serial_number', 'NOT LIKE', '%[USED]%')
                  ->orWhereNull('serial_number');
            })
            ->orderByRaw('expiry_date IS NULL, expiry_date ASC')
            ->orderBy('received_date', 'ASC')
            ->get();

        // 2. Calculate net unlinked deductions (Transfer Out + Disposed − Transfer In).
        // Transfer In adds stock, so we subtract it from deductions.
        $transferredOut = $this->transfers()->where('type', 'out')->sum('new_quantity');
        $transferredIn  = $this->transfers()->where('type', 'in')->sum('new_quantity');
        $disposedNew    = $this->disposals()->where('type', 'new')->sum('quantity');
        
        $borrowImpact = 0;
        if ($this->item_type !== 'device') {
            $borrowImpact = $this->borrows()->get()->sum(function($borrow) {
                $net = $borrow->quantity_borrowed - $borrow->quantity_returned;
                return $borrow->type === 'in' ? -$net : $net;
            });
        }

        $unlinkedDeductions = max(0, $transferredOut + $borrowImpact + $disposedNew - $transferredIn);
        $breakdown = [];

        foreach ($batches as $batch) {
            $received = $batch->quantity;
            // Usage logs ARE linked to specific batches
            $usedInLogs = $batch->usageLogs()->sum('quantity_used');
            $available = (int) ($received - $usedInLogs);

            if ($available <= 0) continue;

            if ($this->item_type === 'device') {
                $borrowedCount = \App\Models\BorrowEntry::where('stock_entry_id', $batch->id)
                    ->whereHas('borrow', function($q) {
                        $q->where('status', 'active');
                    })
                    ->whereNull('disposition') // actively held by borrower
                    ->count();
                $available -= $borrowedCount;

                // Make sure it hasn't been returned to the USED pool
                $returnedUsedCount = \App\Models\BorrowEntry::where('stock_entry_id', $batch->id)
                    ->where('disposition', 'returned_used')
                    ->count();
                if ($returnedUsedCount > 0) {
                    $available = 0; // Device belongs to Used pool now
                }
            }

            // Deduct unlinked items from this batch (FIFO)
            $toDeduct = min($available, $unlinkedDeductions);
            $remaining = $available - $toDeduct;
            $unlinkedDeductions -= $toDeduct;

            if ($remaining > 0) {
                $breakdown[] = [
                    'id' => $batch->id,
                    'lot_number' => $batch->lot_number,
                    'serial_number' => $batch->serial_number,
                    'expiry_date' => $batch->expiry_date,
                    'received_date' => $batch->received_date,
                    'remaining' => $remaining,
                    'is_used' => false,
                ];
            }
        }

        return $breakdown;
    }

    /**
     * Get a breakdown of specific USED devices still available.
     */
    public function getUsedDevicesBreakdownAttribute(): array
    {
        if ($this->item_type !== 'device') {
            return [];
        }

        // Get all devices that have been returned used from a previous borrow OR imported as used manually
        $usedBatches = $this->stockEntries()
            ->where(function($q) {
                $q->whereHas('borrowEntries', function($sub) {
                    $sub->where('disposition', 'returned_used');
                })
                ->orWhereHas('usageLogs', function($sub) {
                    $sub->where('quantity_used', '>', 0);
                })
                ->orWhere('serial_number', 'LIKE', '%[USED]%');
            })
            ->orderBy('received_date', 'ASC')
            ->get();

        $transferredOut = $this->transfers()->where('type', 'out')->sum('used_quantity');
        $transferredIn  = $this->transfers()->where('type', 'in')->sum('used_quantity');
        $disposedUsed   = $this->disposals()->where('type', 'used')->sum('quantity');

        $usedUnlinkedDeductions = max(0, $transferredOut + $disposedUsed - $transferredIn);
        
        $breakdown = [];

        foreach ($usedBatches as $batch) {
            // Respect the actual raw stock entry quantity
            $usedAmount = $batch->quantity;

            $borrowedCount = \App\Models\BorrowEntry::where('stock_entry_id', $batch->id)
                ->whereHas('borrow', function($q) {
                    $q->where('status', 'active');
                })
                ->whereNull('disposition') // actively held by borrower
                ->count();
                
            $usedInLogs = $batch->usageLogs()->where('stock_type', 'used')->sum('quantity_used');
            
            $usedAmount -= $borrowedCount;
            $usedAmount -= $usedInLogs;
            
            if ($usedAmount <= 0) continue;

            // FIFO deduction for used items leaving the system through transfers or disposal
            $toDeduct = min($usedAmount, $usedUnlinkedDeductions);
            $remaining = $usedAmount - $toDeduct;
            $usedUnlinkedDeductions -= $toDeduct;

            if ($remaining > 0) {
                $breakdown[] = [
                    'id' => $batch->id,
                    'lot_number' => $batch->lot_number,
                    'serial_number' => $batch->serial_number,
                    'expiry_date' => $batch->expiry_date,
                    'received_date' => $batch->received_date,
                    'remaining' => $remaining,
                    'is_used' => true,
                ];
            }
        }

        return $breakdown;
    }

    /**
     * Get the quantity of items currently lent out.
     */
    public function getActiveLentOutAttribute(): int
    {
        return (int) $this->borrows()
            ->where('type', 'out')
            ->whereIn('status', ['active', 'partial'])
            ->get()
            ->sum(function($borrow) {
                return max(0, $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used);
            });
    }

    /**
     * Get the quantity of items currently borrowed from other departments.
     */
    public function getActiveBorrowedInAttribute(): int
    {
        return (int) $this->borrows()
            ->where('type', 'in')
            ->whereIn('status', ['active', 'partial'])
            ->get()
            ->sum(function($borrow) {
                return max(0, $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used);
            });
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
