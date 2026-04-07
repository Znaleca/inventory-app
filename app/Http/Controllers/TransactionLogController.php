<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockEntry;
use App\Models\UsageLog;
use Illuminate\Http\Request;

class TransactionLogController extends Controller
{
    public function index(Request $request)
    {
        $itemId = $request->input('item');
        $type = $request->input('type');        // 'in', 'out', or null for all
        $from = $request->input('from');
        $to = $request->input('to');

        // Fetch stock entries (IN)
        $stockQuery = StockEntry::with('item')
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('received_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('received_date', '<=', $to));

        // Fetch transfers (OUT)
        $transferQuery = \App\Models\Transfer::with('item')
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('transferred_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('transferred_at', '<=', $to));

        // Fetch usage logs (OUT)
        $usageQuery = UsageLog::with('item')
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('used_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('used_at', '<=', $to));

        // Fetch disposals (OUT)
        $disposalQuery = \App\Models\Disposal::with('item')
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('disposed_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('disposed_at', '<=', $to));

        // Fetch borrows (OUT)
        $borrowQuery = \App\Models\Borrow::with(['item', 'staff'])
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('borrowed_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('borrowed_at', '<=', $to));

        // Fetch returns (IN) - These are derived from Borrow records where quantity_returned > 0 or quantity_used > 0
        $returnQuery = \App\Models\Borrow::with(['item', 'staff'])
            ->where(function($q) {
                $q->where('quantity_returned', '>', 0)
                  ->orWhere('quantity_used', '>', 0);
            })
            ->when($itemId, fn ($q) => $q->where('item_id', $itemId))
            ->when($from, fn ($q) => $q->whereDate('returned_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('returned_at', '<=', $to));

        // Normalise collections into a unified format
        $stockEntries = ($type === 'out') ? collect() : $stockQuery->get()->map(fn ($s) => [
            'id' => 'stock-'.$s->id,
            'type' => 'in',
            'date' => $s->received_date instanceof \Carbon\Carbon ? $s->received_date : \Carbon\Carbon::parse($s->received_date),
            'item' => $s->item,
            'quantity' => $s->quantity,
            'lot_number' => $s->lot_number,
            'expiry_date' => $s->expiry_date,
            'notes' => $s->notes,
            // usage-specific (null for stock entries)
            'patient_id' => null,
            'procedure_type' => null,
            'used_by' => null,
        ]);

        $usageLogs = ($type === 'in') ? collect() : $usageQuery->get()->map(fn ($u) => [
            'id' => 'usage-'.$u->id,
            'type' => 'out',
            'date' => $u->used_at instanceof \Carbon\Carbon ? $u->used_at : \Carbon\Carbon::parse($u->used_at),
            'item' => $u->item,
            'quantity' => $u->quantity_used,
            'lot_number' => null,
            'expiry_date' => null,
            'notes' => $u->notes,
            'patient_id' => $u->patient_id,
            'procedure_type' => $u->procedure_type,
            'used_by' => $u->used_by,
        ]);

        $transfers = ($type === 'in') ? collect() : $transferQuery->get()->map(fn ($t) => [
            'id' => 'transfer-'.$t->id,
            'type' => 'out',
            'date' => $t->transferred_at instanceof \Carbon\Carbon ? $t->transferred_at : \Carbon\Carbon::parse($t->transferred_at),
            'item' => $t->item,
            'quantity' => $t->quantity,
            'lot_number' => null,
            'expiry_date' => null,
            'notes' => 'Transfer to '.$t->destination.'. '.$t->notes.($t->bio_id ? ' (Bio ID: '.$t->bio_id.')' : ''),
            'patient_id' => null,
            'procedure_type' => null,
            'used_by' => ($t->transferred_to ?? $t->transferred_by ? 'Transferred To: '.($t->transferred_to ?? $t->transferred_by) : 'Transferred').($t->approved_by ? ' (Processed by: '.$t->approved_by.')' : ''),
        ]);

        $disposals = ($type === 'in') ? collect() : $disposalQuery->get()->map(fn ($d) => [
            'id' => 'disposal-'.$d->id,
            'type' => 'out',
            'date' => $d->disposed_at instanceof \Carbon\Carbon ? $d->disposed_at : \Carbon\Carbon::parse($d->disposed_at),
            'item' => $d->item,
            'quantity' => $d->quantity,
            'lot_number' => null,
            'expiry_date' => null,
            'notes' => 'DISPOSAL: '.$d->reason,
            'patient_id' => null,
            'procedure_type' => null,
            'used_by' => $d->disposed_by ? ('Disposed By: '.$d->disposed_by) : null,
        ]);

        $borrows = ($type === 'in') ? collect() : $borrowQuery->get()->map(fn ($b) => [
            'id' => 'borrow-'.$b->id,
            'type' => 'out',
            'date' => $b->borrowed_at instanceof \Carbon\Carbon ? $b->borrowed_at : \Carbon\Carbon::parse($b->borrowed_at),
            'item' => $b->item,
            'quantity' => $b->quantity_borrowed,
            'lot_number' => null,
            'expiry_date' => null,
            'notes' => 'BORROWED BY: '.($b->borrower_name ?? $b->staff?->display_name ?? 'Unknown').
                       ($b->department ? ' Dept: '.$b->department : '').
                       ($b->bio_id ? ' BioID: '.$b->bio_id : '').'. '.$b->notes,
            'patient_id' => 'BORROWED',
            'procedure_type' => 'Clinical Borrow',
            'used_by' => ($b->borrower_name ?? $b->staff?->display_name ?? 'Unknown').($b->approved_by ? ' (Processed by: '.$b->approved_by.')' : ''),
        ]);

        $returns = ($type === 'out') ? collect() : $returnQuery->get()->map(fn ($r) => [
            'id' => 'return-'.$r->id,
            'type' => 'in',
            'date' => $r->returned_at ? ($r->returned_at instanceof \Carbon\Carbon ? $r->returned_at : \Carbon\Carbon::parse($r->returned_at)) : $r->updated_at,
            'item' => $r->item,
            'quantity' => $r->quantity_returned + ($r->item->is_one_time_use ? 0 : $r->quantity_used),
            'lot_number' => null,
            'expiry_date' => null,
            'notes' => 'RETURNED BY: '.($r->borrower_name ?? $r->staff?->display_name ?? 'Unknown').'. '.
                       ($r->quantity_used > 0 ? ($r->item->is_one_time_use ? '('.$r->quantity_used.' consumed) ' : '('.$r->quantity_used.' used) ') : '').
                       $r->notes,
            'patient_id' => 'RETURNED',
            'procedure_type' => 'Item Return/Usage',
            'used_by' => ($r->borrower_name ?? $r->staff?->display_name ?? 'Unknown').($r->approved_by ? ' (Processed by: '.$r->approved_by.')' : ''),
        ]);

        $transactions = $stockEntries->merge($usageLogs)->merge($transfers)->merge($disposals)->merge($borrows)->merge($returns)
            ->sortByDesc('date')
            ->values();

        $items = Item::orderBy('name')->get();

        return view('logs.index', compact('transactions', 'items'));
    }
}
