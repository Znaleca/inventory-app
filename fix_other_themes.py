import os
import re

files_to_update = [
    'resources/views/in-out/index.blade.php',
    'resources/views/logs/index.blade.php',
    'resources/views/staff/index.blade.php',
    'resources/views/staff/create.blade.php',
    'resources/views/staff/edit.blade.php',
]

def replace_colors(content):
    replacements = {
        'slate-200': 'sky-100',
        'slate-50/50': 'sky-50/80',
        'slate-50': 'sky-50',
        'slate-100': 'sky-100',
        'blue-50': 'sky-50',
        'blue-600': 'sky-500',
        'blue-500': 'sky-500',
        'blue-700': 'sky-600',
        'blue-800': 'sky-700',
        'border-blue-500': 'border-sky-500',
        'border-blue-600': 'border-sky-500',
        'text-blue-500': 'text-sky-500',
        'text-blue-600': 'text-sky-600',
        'bg-blue-600': 'bg-sky-500',
        'hover:bg-blue-700': 'hover:bg-sky-600',
        'text-slate-800': 'text-[#0f172a]',
        'text-slate-900': 'text-[#0f172a]',
        'bg-slate-900': 'bg-[#0f172a]',
        'border-slate-900': 'border-[#0f172a]',
        'hover:bg-slate-800': 'hover:bg-slate-800',
    }
    
    for old, new in replacements.items():
        content = content.replace(old, new)

    # Standardize table headers colors if they have them
    content = content.replace('bg-sky-50/50 border-b border-sky-100', 'bg-sky-50/80 border-b border-sky-100')
    
    # Change any `<div class="bg-white border border-sky-100 relative">` inside tabs to rounded
    content = content.replace('<div class="bg-white border border-sky-100 relative mb-8">', '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-8">')
    content = content.replace('<div class="bg-white border border-sky-100 relative">', '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative">')
    
    # And remove the `ml-1` that offset the vertical border, because we will change vertical borders to top gradients
    content = content.replace('ml-1', '')

    # Convert vertical left borders to top gradients
    borders_map = {
        '<div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>',
        '<div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600"></div>',
        '<div class="absolute top-0 left-0 w-1 h-full bg-slate-300"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-400 to-slate-600"></div>',
        '<div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600"></div>',
        '<div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>',
    }
    for old_b, new_b in borders_map.items():
        content = content.replace(old_b, new_b)

    # Standardize table headers
    content = content.replace('text-xs font-medium text-slate-500 uppercase tracking-wider', 'text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400')
    content = content.replace('text-slate-500', 'text-slate-400') # Some text-slate-500 are used for headers

    return content

for filepath in files_to_update:
    if os.path.exists(filepath):
        with open(filepath, 'r') as f:
            data = f.read()
        
        new_data = replace_colors(data)
        
        with open(filepath, 'w') as f:
            f.write(new_data)
        print(f"Updated: {filepath}")

