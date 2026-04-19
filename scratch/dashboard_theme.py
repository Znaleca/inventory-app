import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Additional Metrics Update
def replace_additional_metrics(match):
    return """
 {{-- Additional Metrics --}}
 <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
  {{-- New Items --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-400 to-blue-500 p-5 shadow-lg shadow-cyan-500/30 transition-all duration-300 hover:-translate-y-1 group">
      <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
      <div class="flex items-center gap-4 relative z-10">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white ring-1 ring-white/40 shadow-inner backdrop-blur-md">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6 drop-shadow-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
          </div>
          <div class="min-w-0 flex-1">
              <p class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $totalNewStock }}</p>
              <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-50 mt-1 drop-shadow-sm">New Items</p>
          </div>
      </div>
  </div>

  {{-- Used Items --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-400 to-purple-600 p-5 shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-1 group">
      <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
      <div class="flex items-center gap-4 relative z-10">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white ring-1 ring-white/40 shadow-inner backdrop-blur-md">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6 drop-shadow-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="min-w-0 flex-1">
              <p class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $totalUsedStock }}</p>
              <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-50 mt-1 drop-shadow-sm">Used Items</p>
          </div>
      </div>
  </div>

  {{-- Borrowed Items --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-400 to-blue-600 p-5 shadow-lg shadow-sky-500/30 transition-all duration-300 hover:-translate-y-1 group">
      <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
      <div class="flex items-center gap-4 relative z-10">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white ring-1 ring-white/40 shadow-inner backdrop-blur-md">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6 drop-shadow-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
          </div>
          <div class="min-w-0 flex-1">
              <p class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $totalBorrowedCount }}</p>
              <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-sky-50 mt-1 drop-shadow-sm">Borrowed</p>
          </div>
      </div>
  </div>

  {{-- Pending Returns --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-pink-400 to-rose-500 p-5 shadow-lg shadow-pink-500/30 transition-all duration-300 hover:-translate-y-1 group">
      <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
      <div class="flex items-center gap-4 relative z-10">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white ring-1 ring-white/40 shadow-inner backdrop-blur-md">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6 drop-shadow-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
          </div>
          <div class="min-w-0 flex-1">
              <p class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $pendingReturnsCount }}</p>
              <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-pink-50 mt-1 drop-shadow-sm">To Return</p>
          </div>
      </div>
  </div>
 </div>"""

content = re.sub(r"\{\{-- Additional Metrics --\}\}.*?</div>\s*</div>\s*</div>\s*</div>", replace_additional_metrics, content, flags=re.DOTALL)

# 2. Charts Section
def replace_charts_html(match):
    return """
    {{-- Charts Section --}}
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Chart 1: Inventory Distribution --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 shadow-2xl shadow-slate-900/40 rounded-3xl p-6">
            <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-blue-500/20 blur-3xl"></div>
            <h3 class="relative z-10 text-sm font-bold text-white mb-6 flex items-center gap-3 tracking-widest uppercase">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 ring-1 ring-blue-500/50">
                    <div class="w-2 h-2 rounded-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.8)]"></div>
                </span>
                Inventory Status Overview
            </h3>
            <div class="relative z-10 h-[300px] w-full flex items-center justify-center">
                <canvas id="inventoryStatusChart"></canvas>
            </div>
        </div>
        
        {{-- Chart 2: Health Metrics --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 shadow-2xl shadow-slate-900/40 rounded-3xl p-6">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <h3 class="relative z-10 text-sm font-bold text-white mb-6 flex items-center gap-3 tracking-widest uppercase">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 ring-1 ring-emerald-500/50">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                </span>
                Stock Health Distribution
            </h3>
            <div class="relative z-10 h-[300px] w-full flex items-center justify-center">
                <canvas id="inventoryHealthChart"></canvas>
            </div>
        </div>
    </div>"""

content = re.sub(r"\{\{-- Charts Section --\}\}.*?id=\"inventoryHealthChart\"></canvas>\s*</div>\s*</div>\s*</div>", replace_charts_html, content, flags=re.DOTALL)


# 3. Chart JS (Fonts and colors for dark UI)
js_replace = """
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    color: '#e2e8f0',
                                    font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600' }
                                }
                            }"""
content = re.sub(r"legend:\s*\{\s*position:\s*'bottom',\s*labels:\s*\{[^\}]+\}\s*\}", js_replace, content, flags=re.DOTALL)

js_replace_bar = """
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], display: true, color: 'rgba(255,255,255,0.1)' },
                                border: { display: false },
                                ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif" } }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#e2e8f0', font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600' } }
                            }
                        }"""
content = re.sub(r"scales:\s*\{.*?}", js_replace_bar, content, flags=re.DOTALL)

# 4. Table Headers Replacements
header_patterns = [
    # Low Stock
    (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5 shrink-0">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100/50 text-orange-500">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Low Stock Alerts</h3>\s*</div>\s*<a href="([^"]+)"\s*class="[^"]+">\s*View All\s*</a>\s*</div>',
     r'''<div class="flex items-center justify-between bg-gradient-to-r from-orange-400 to-red-500 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm ring-1 ring-white/40 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Low Stock Alerts</h3></div><a href="\2" class="inline-flex items-center rounded-xl bg-white/20 px-4 py-2 text-[10px] font-bold text-white ring-1 ring-white/40 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:text-orange-600 uppercase tracking-widest">View All</a></div>'''),

    # Expiring
    (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5 shrink-0">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100/50 text-amber-500">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Expiring Soon \(30 Days\)</h3>\s*</div>\s*</div>',
     r'''<div class="flex items-center justify-between bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm ring-1 ring-white/40 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Expiring Soon (30 Days)</h3></div></div>'''),

    # Expired
    (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5 shrink-0">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100/50 text-rose-600">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Need Disposal \(Expired\)</h3>\s*</div>\s*<a href="([^"]+)"\s*class="[^"]+">\s*View All\s*</a>\s*</div>',
     r'''<div class="flex items-center justify-between bg-gradient-to-r from-rose-500 to-pink-600 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm ring-1 ring-white/40 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Need Disposal</h3></div><a href="\2" class="inline-flex items-center rounded-xl bg-white/20 px-4 py-2 text-[10px] font-bold text-white ring-1 ring-white/40 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:text-rose-600 uppercase tracking-widest">View All</a></div>'''),

     # Recent Usage
    (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100/80 text-slate-500">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Recent Usage Activity</h3>\s*</div>\s*<a href="([^"]+)"\s*class="[^"]+">\s*View Full Log\s*</a>\s*</div>',
     r'''<div class="flex items-center justify-between bg-gradient-to-r from-slate-700 to-slate-900 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white backdrop-blur-sm ring-1 ring-white/20 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Recent Usage Activity</h3></div><a href="\2" class="inline-flex items-center rounded-xl bg-white/10 px-4 py-2 text-[10px] font-bold text-white ring-1 ring-white/20 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:text-slate-800 uppercase tracking-widest">View Full Log</a></div>'''),

     # Recent Returns
     (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100/50 text-emerald-600">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Recent Returns</h3>\s*</div>\s*<a href="([^"]+)"\s*class="[^"]+">\s*View All\s*</a>\s*</div>',
      r'''<div class="flex items-center justify-between bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm ring-1 ring-white/40 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Recent Returns</h3></div><a href="\2" class="inline-flex items-center rounded-xl bg-white/20 px-4 py-2 text-[10px] font-bold text-white ring-1 ring-white/40 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:text-emerald-600 uppercase tracking-widest">View All</a></div>'''),

      # Recent Disposals
     (r'<div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2\.5">\s*<div class="flex items-center gap-3">\s*<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100/50 text-orange-600">(.*?)</div>\s*<h3 class="text-sm font-bold text-slate-800">Recent Disposals</h3>\s*</div>\s*<a href="([^"]+)"\s*class="[^"]+">\s*View All\s*</a>\s*</div>',
      r'''<div class="flex items-center justify-between bg-gradient-to-r from-red-500 to-rose-700 px-5 py-4 shrink-0 shadow-md relative z-10"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 text-white backdrop-blur-sm ring-1 ring-white/40 shadow-inner">\1</div><h3 class="text-sm font-bold text-white tracking-widest uppercase drop-shadow-sm">Recent Disposals</h3></div><a href="\2" class="inline-flex items-center rounded-xl bg-white/20 px-4 py-2 text-[10px] font-bold text-white ring-1 ring-white/40 shadow-sm backdrop-blur-md transition-all hover:bg-white hover:text-rose-600 uppercase tracking-widest">View All</a></div>''')
]

for pat, rep in header_patterns:
    content = re.sub(pat, rep, content, flags=re.DOTALL)

# Table rounded corners need to accommodate the rounded header
content = content.replace('class="bg-white border border-slate-200 shadow-sm rounded-3xl flex flex-col pt-1"', 'class="bg-white border border-slate-200 shadow-md rounded-3xl flex flex-col pt-0 overflow-hidden"')
content = content.replace('class="bg-white ] border border-slate-200 shadow-sm rounded-3xl flex flex-col pt-1"', 'class="bg-white border border-slate-200 shadow-md rounded-3xl flex flex-col pt-0 overflow-hidden"')
content = content.replace('class="bg-white ] border border-slate-200 shadow-sm rounded-3xl mt-6 flex flex-col pt-1"', 'class="bg-white border border-slate-200 shadow-md rounded-3xl mt-6 flex flex-col pt-0 overflow-hidden"')

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)

