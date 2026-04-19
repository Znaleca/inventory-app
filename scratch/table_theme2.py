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

content = re.sub(r'<table class="min-w-full text-sm border-separate border-spacing-y-1">[\s\S]*?@endforeach\s*</tbody>\s*</table>', rep_low_stock, content, count=1)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)

