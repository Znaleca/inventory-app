import re
import sys

with open('resources/views/layouts/app.blade.php', 'r') as f:
    content = f.read()

# Replace sidebar main comment and class
content = content.replace('SIDEBAR (Light Tech Theme)', 'SIDEBAR (Dark Tech Theme)')
content = re.sub(r'<aside\s+class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200 transition-all duration-300 ease-in-out lg:static lg:translate-x-0 h-full shadow-\[4px_0_24px_rgba\(0,0,0,0\.02\)\]"',
                 '<aside\n            class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800 transition-all duration-300 ease-in-out lg:static lg:translate-x-0 h-full shadow-[4px_0_24px_rgba(0,0,0,0.2)] overflow-hidden"', content)

# Add blueprint grid overlay inside <aside> just before <div class="relative flex
content = re.sub(r'(\}" x-cloak>)\s*<div class="relative flex flex-col h-full w-full">',
                 r'\1\n\n            {{-- Blueprint grid overlay on dark background --}}\n            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: linear-gradient(rgba(99,102,241,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.3) 1px, transparent 1px); background-size: 30px 30px;"></div>\n\n            <div class="relative flex flex-col h-full w-full z-10">', content)

# Replace branding border and bg
content = content.replace('border-b border-slate-200 bg-gradient-to-br from-slate-50 to-white', 'border-b border-slate-800 bg-slate-900/50')

# Top accent bar gradient
content = content.replace('from-blue-500 via-indigo-500 to-violet-500', 'from-blue-600 to-indigo-500 z-10')

# Scan line
content = content.replace('via-blue-100/40', 'via-blue-500/30')

# Accent nodes
content = content.replace('border-blue-300/60', 'border-blue-500/40')
content = content.replace('border-indigo-300/60', 'border-indigo-500/40')

# Logo brightness
content = content.replace('class="h-8 w-8 object-contain logo-img"', 'class="h-8 w-8 object-contain logo-img brightness-150"')

# Terminal text colors
content = content.replace('text-blue-500 uppercase tracking', 'text-blue-400 uppercase tracking')
content = content.replace('bg-blue-400 inline-block rounded-full', 'bg-emerald-400 inline-block rounded-full')
content = content.replace('tracking-tight text-slate-800 flex', 'tracking-tight text-white flex')
content = content.replace('text-blue-400 font-mono', 'text-blue-500 font-mono')

# Vertical line
content = content.replace('w-px bg-slate-100 -z-10', 'w-px bg-slate-800 -z-10')

# Nav links and hover state replacements using regex to catch weird whitespace
content = re.sub(r'\{\{\s*\$isActive\s*\?\s*\'bg-blue-50/80 text-blue-700\'\s*:\s*\'text-slate-500 hover:bg-slate-50 hover:text-slate-900\'\s*\}\}', "{{ $isActive ? 'bg-slate-800/80 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}", content)

# Accent line on active link
content = content.replace('w-[3px] bg-blue-500', 'w-[2px] bg-blue-500')
content = content.replace('w-[1px] bg-blue-200', 'w-[1px] bg-blue-500/20')
content = content.replace('h-[1px] bg-blue-200', 'h-[1px] bg-blue-500/20')
content = re.sub(r'bg-white border border-blue-400', 'bg-slate-900 border border-blue-500/50', content)

# Icon container classes
content = re.sub(r'bg-white p-1 rounded-sm shadow-\[0_1px_2px_rgba\(0,0,0,0\.05\)\] border border-slate-100\s+\{\{\s*\$isActive\s*\?\s*\'border-blue-200 shadow-blue-100\'\s*:\s*\'group-hover:border-slate-300\'\s*\}\}', "bg-slate-900 p-1 rounded-sm shadow-[0_1px_2px_rgba(0,0,0,0.2)] border {{ $isActive ? 'border-blue-500/30' : 'border-slate-800 group-hover:border-slate-700' }}", content)

# Icon color
content = content.replace('text-blue-600 w-[18px]', 'text-blue-400 w-[18px]')
content = content.replace('text-slate-400 group-hover:text-slate-600 w-[18px]', 'text-slate-500 group-hover:text-slate-300 w-[18px]')

# Admin separator
content = content.replace('text-slate-400 uppercase tracking', 'text-slate-500 uppercase tracking')
content = content.replace('bg-slate-200 flex-1 border-t border-dashed border-slate-300', 'bg-slate-800 flex-1 border-t border-dashed border-slate-700')
content = content.replace('bg-slate-200 border-t border-dashed border-slate-300', 'bg-slate-800 border-t border-dashed border-slate-700')

# Toggle button
content = re.sub(r'bg-white border border-slate-300 text-slate-400 rounded-sm hover:text-blue-600 hover:border-blue-400', 'bg-slate-900 border border-slate-700 text-slate-400 rounded-sm hover:text-blue-400 hover:border-blue-500', content)

# Write back
with open('resources/views/layouts/app.blade.php', 'w') as f:
    f.write(content)

print("Done updating app.blade.php")

