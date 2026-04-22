<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockEntry;
use App\Models\Transfer;
use App\Models\UsageLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $items = Item::with(['category', 'stockEntries', 'usageLogs'])->get();

        $lowStockItems = $items->filter(fn ($item) => $item->is_low_stock);
        $lowStockCount = $lowStockItems->count();

        // Items expiring within 30 days
        $expiringItems = Item::whereHas('stockEntries', function ($q) {
            $q->whereNotNull('expiry_date')
                ->where('expiry_date', '>=', now())
                ->where('expiry_date', '<=', now()->addDays(30));
        })->with(['category', 'stockEntries' => function ($q) {
            $q->whereNotNull('expiry_date')
                ->where('expiry_date', '>=', now())
                ->where('expiry_date', '<=', now()->addDays(30))
                ->orderBy('expiry_date');
        }])->get()->filter(function ($item) {
            $breakdown = collect($item->batches_breakdown);
            return $breakdown->whereNotNull('expiry_date')
                ->where('expiry_date', '>=', now())
                ->where('expiry_date', '<=', now()->addDays(30))
                ->sum('remaining') > 0;
        });

        $recentUsage = UsageLog::with('item')
            ->orderByDesc('used_at')
            ->limit(10)
            ->get();


        // New Metrics
        $totalNewStock = $items->sum(fn ($item) => $item->total_stock);
        $totalUsedStock = $items->sum('stock_used');
        
        $totalStockValue = $items->sum(function ($item) {
            return $item->total_stock * $item->unit_price;
        });
        
        $activeBorrows = \App\Models\Borrow::whereIn('status', ['active', 'partial'])->get();
        $totalBorrowedCount = $activeBorrows->sum(function ($borrow) {
            return $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
        });
        $pendingReturnsCount = $activeBorrows->count();
        $pendingReturnsList = \App\Models\Borrow::with(['item', 'staff'])
            ->whereIn('status', ['active', 'partial'])
            ->orderBy('return_date', 'asc') // those due earliest first
            ->orderBy('borrowed_at', 'asc')
            ->limit(5)
            ->get();

        $expiredItems = Item::whereHas('stockEntries', function ($q) {
            $q->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay());
        })->with(['category', 'stockEntries' => function ($q) {
            $q->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->orderBy('expiry_date');
        }])->get()->filter(function ($item) {
            $breakdown = collect($item->batches_breakdown);
            return $breakdown->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->sum('remaining') > 0;
        });
        $expiredCount = $expiredItems->sum(function($item) {
            return collect($item->batches_breakdown)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->sum('remaining');
        });

        $recentReturns = \App\Models\Borrow::with(['item', 'staff'])
            ->whereNotNull('returned_at')
            ->orderByDesc('returned_at')
            ->limit(5)
            ->get();

        $recentDisposals = \App\Models\Disposal::with('item')
            ->orderByDesc('disposed_at')
            ->limit(5)
            ->get();

        $recentlyAdded = Item::with('category')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $recentTransfersFeed = \App\Models\Transfer::with('item')
            ->orderByDesc('transferred_at')
            ->limit(5)
            ->get();

        $totalTransfersCount = \App\Models\Transfer::count();

        $trendDates = collect(range(6, 0))->map(fn ($daysAgo) => now()->subDays($daysAgo)->startOfDay());
        $trendLabels = $trendDates->map(fn ($date) => $date->format('M d'))->values();

        $stockInByDate = StockEntry::query()
            ->selectRaw('DATE(received_date) as day, SUM(quantity) as qty')
            ->whereDate('received_date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('day')
            ->pluck('qty', 'day');

        $usageOutByDate = UsageLog::query()
            ->selectRaw('DATE(used_at) as day, SUM(quantity_used) as qty')
            ->whereDate('used_at', '>=', now()->subDays(6)->toDateString())
            ->groupBy('day')
            ->pluck('qty', 'day');

        $transfersByDate = Transfer::query()
            ->selectRaw("DATE(transferred_at) as day, SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as qty_in, SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as qty_out")
            ->whereDate('transferred_at', '>=', now()->subDays(6)->toDateString())
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $trendStockIn = $trendDates->map(function ($date) use ($stockInByDate, $transfersByDate) {
            $key = $date->toDateString();
            $transferIn = data_get($transfersByDate, "{$key}.qty_in", 0);
            return (int) (($stockInByDate[$key] ?? 0) + $transferIn);
        })->values();

        $trendStockOut = $trendDates->map(function ($date) use ($usageOutByDate, $transfersByDate) {
            $key = $date->toDateString();
            $transferOut = data_get($transfersByDate, "{$key}.qty_out", 0);
            return (int) (($usageOutByDate[$key] ?? 0) + $transferOut);
        })->values();

        return view('dashboard', compact(
            'totalItems', 'lowStockCount', 'lowStockItems',
            'expiringItems', 'expiredItems', 'expiredCount', 'recentUsage',
            'totalNewStock', 'totalUsedStock', 'totalBorrowedCount', 'pendingReturnsCount', 'pendingReturnsList',
            'recentReturns', 'recentDisposals', 'totalStockValue', 'recentlyAdded', 'recentTransfersFeed', 'totalTransfersCount',
            'trendLabels', 'trendStockIn', 'trendStockOut'
        ));
    }
}
