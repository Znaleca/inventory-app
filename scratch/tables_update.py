import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Low Stock Alerts
rep_low_stock = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Category</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Stock Left</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Reorder</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Action</th>
 </tr>
 </thead>
 <tbody>
  @foreach($lowStockItems->take(5) as $item)
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-600">{{ $item->category->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold {{ $item->total_stock <= 0 ? 'text-rose-600 bg-rose-50' : 'text-amber-600 bg-amber-50' }}">{{ $item->total_stock }} {{ $item->unit }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center text-slate-600">{{ $item->reorder_level }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center">
  <a href="{{ route('stock.create', $item) }}" class="inline-flex items-center justify-center rounded bg-slate-900 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-500">Restock</a>
  </td>
  </tr>
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*</tbody>\s*</table>', rep_low_stock, content, count=1, flags=re.DOTALL)

# 2. Expiring Soon
rep_expiring = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Qty Remaining</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Lot Number</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Expiration Date</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Status</th>
 </tr>
 </thead>
 <tbody>
  @foreach($expiringItems->take(5) as $item)
  @php
  $breakdownLookup = collect($item->batches_breakdown)->keyBy('id');
  @endphp
  @foreach($item->stockEntries as $entry)
  @php
  $batchData = $breakdownLookup->get($entry->id);
  if (!$batchData) continue;
  $daysLeft = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
  $isCritical = $daysLeft <= 7; 
  @endphp
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center text-slate-600">{{ $batchData['remaining'] }} {{ $item->unit }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-600 font-mono text-xs">{{ $entry->lot_number ?? 'N/A' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold {{ $isCritical ? 'text-rose-600 bg-rose-50' : 'text-amber-600 bg-amber-50' }}">{{ $entry->expiry_date->format('M d, Y') }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-semibold {{ $isCritical ? 'text-rose-600' : 'text-amber-600' }}">{{ $daysLeft < 0 ? 'Expired' : ($daysLeft == 0 ? 'Today' : $daysLeft . 'd left') }}</td>
  </tr>
  @endforeach
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*@endforeach\s*</tbody>\s*</table>', rep_expiring, content, count=1, flags=re.DOTALL)

# 3. Expired
rep_expired = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Qty Remaining</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Lot Number</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Expired On</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Days Past</th>
 </tr>
 </thead>
 <tbody>
  @foreach($expiredItems->take(5) as $item)
  @php
  $breakdownLookup = collect($item->batches_breakdown)->keyBy('id');
  @endphp
  @foreach($item->stockEntries as $entry)
  @php
  $batchData = $breakdownLookup->get($entry->id);
  if (!$batchData) continue;
  $daysSince = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
  if ($daysSince >= 0) continue; 
  @endphp
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center text-rose-600 font-bold bg-rose-50">{{ $batchData['remaining'] }} {{ $item->unit }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-600 font-mono text-xs">{{ $entry->lot_number ?? 'N/A' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">{{ $entry->expiry_date->format('M d, Y') }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-semibold text-rose-600">{{ abs($daysSince) }} days ago</td>
  </tr>
  @endforeach
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*@endforeach\s*</tbody>\s*</table>', rep_expired, content, count=1, flags=re.DOTALL)

# 4. Recent Usage Activity
rep_usage = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Qty</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Procedure</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Used By</th>
  <th class="border border-slate-300 px-3 py-2 text-right font-bold text-slate-700">Timestamp</th>
 </tr>
 </thead>
 <tbody>
  @foreach($recentUsage as $log)
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $log->item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-600 bg-slate-50">-{{ $log->quantity_used }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-600">{{ $log->procedure_type ?? '—' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-700 font-medium">{{ $log->used_by ?? '—' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-right text-slate-500 font-mono text-xs">{{ $log->used_at->format('M d, Y h:i A') }}</td>
  </tr>
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*</tbody>\s*</table>', rep_usage, content, count=1, flags=re.DOTALL)

# 5. Recent Returns
rep_returns = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Qty</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Returned By</th>
  <th class="border border-slate-300 px-3 py-2 text-right font-bold text-slate-700">Date</th>
 </tr>
 </thead>
 <tbody>
  @foreach($recentReturns as $return)
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $return->item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold text-emerald-600 bg-emerald-50">+{{ $return->quantity_returned }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-700 font-medium">{{ $return->borrower_name ?? $return->staff?->display_name ?? '—' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-right text-slate-500 font-mono text-xs">{{ $return->returned_at->format('M d, Y h:i A') }}</td>
  </tr>
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*</tbody>\s*</table>', rep_returns, content, count=1, flags=re.DOTALL)

# 6. Recent Disposals
rep_disposals = """ <table class="min-w-full text-sm border-collapse border border-slate-300 bg-white">
 <thead>
 <tr class="bg-slate-100">
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Item</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Qty</th>
  <th class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-700">Type</th>
  <th class="border border-slate-300 px-3 py-2 text-left font-bold text-slate-700">Reason</th>
  <th class="border border-slate-300 px-3 py-2 text-right font-bold text-slate-700">Date</th>
 </tr>
 </thead>
 <tbody>
  @foreach($recentDisposals as $disposal)
  <tr class="hover:bg-slate-50 transition-colors">
  <td class="border border-slate-300 px-3 py-2 font-semibold text-slate-800">{{ $disposal->item->name }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-bold text-stone-600 bg-stone-50">-{{ $disposal->quantity }}</td>
  <td class="border border-slate-300 px-3 py-2 text-center font-semibold {{ $disposal->type === 'used' ? 'text-amber-600' : 'text-rose-600' }}">{{ ucfirst($disposal->type) }}</td>
  <td class="border border-slate-300 px-3 py-2 text-slate-600">{{ Str::limit($disposal->reason, 25) ?? '—' }}</td>
  <td class="border border-slate-300 px-3 py-2 text-right text-slate-500 font-mono text-xs">{{ $disposal->disposed_at->format('M d, Y') }}</td>
  </tr>
  @endforeach
 </tbody>
 </table>"""

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">.*?@endforeach\s*</tbody>\s*</table>', rep_disposals, content, count=1, flags=re.DOTALL)

# Removing table padding p-2 wrapper from the gradient div blocks to ensure perfect edge-to-edge spreadsheet feel
content = re.sub(r'<div class="overflow-x-auto flex-1 p-2">', '<div class="overflow-x-auto flex-1">', content)
content = re.sub(r'<div class="overflow-x-auto p-2">', '<div class="overflow-x-auto">', content)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)

