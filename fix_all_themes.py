import os
import re

files_to_update = [
    'resources/views/categories/index.blade.php',
    'resources/views/categories/create.blade.php',
    'resources/views/categories/edit.blade.php',
    'resources/views/units/index.blade.php',
    'resources/views/units/create.blade.php',
    'resources/views/units/edit.blade.php',
    'resources/views/locations/index.blade.php',
    'resources/views/locations/create.blade.php',
    'resources/views/locations/edit.blade.php',
]

def replace_colors(content):
    replacements = {
        'slate-200': 'sky-100',
        'slate-50': 'sky-50',
        'blue-50': 'sky-50',
        'indigo-500': 'sky-500',
        'indigo-600': 'sky-600',
        'indigo-700': 'sky-700',
        'indigo-50': 'sky-50',
        'blue-600': 'sky-500',
        'blue-500': 'sky-500',
        'border-blue-500': 'border-sky-500',
        'text-blue-500': 'text-sky-500',
        'text-blue-600': 'text-sky-600',
        'bg-blue-600': 'bg-sky-500',
        'hover:bg-blue-700': 'hover:bg-sky-600',
        'text-slate-800': 'text-[#0f172a]',
        'text-slate-900': 'text-[#0f172a]',
        'bg-slate-100': 'bg-sky-50/50',
    }
    
    for old, new in replacements.items():
        # Avoid double replacing things like sky-500 -> sky-500
        content = content.replace(old, new)
        
    # Standardize main wrappers
    content = re.sub(r'<div class="[^"]*bg-white[^"]*shadow[^"]*rounded-[^"]*">', '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">', content)
    content = re.sub(r'<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">', '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">', content)
    
    # Standardize table headers
    content = content.replace('bg-slate-50 border-b border-slate-200', 'bg-sky-50/80 border-b border-sky-100')
    content = content.replace('bg-sky-50 border-b border-sky-100', 'bg-sky-50/80 border-b border-sky-100') # if replaced above
    content = content.replace('text-xs font-medium text-slate-500 uppercase tracking-wider', 'text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400')
    
    # Standardize form headers
    content = re.sub(r'<div class="p-6 border-b border-sky-100">\s*<h2 class="text-lg font-bold text-\[\#0f172a\]">([^<]+)</h2>', 
                    r'<div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0 mb-6">\n    <div>\n        <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Settings</p>\n        <h3 class="text-xl font-black text-[#0f172a] tracking-tight">\1</h3>\n    </div>', content)
                    
    content = re.sub(r'<div class="p-6 border-b border-slate-200">\s*<h2 class="text-lg font-bold text-slate-800">([^<]+)</h2>', 
                    r'<div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0 mb-6">\n    <div>\n        <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Settings</p>\n        <h3 class="text-xl font-black text-[#0f172a] tracking-tight">\1</h3>\n    </div>', content)
    
    # Remove items-theme if any
    content = re.sub(r'<style>[\s\S]*?\.items-theme[\s\S]*?</style>', '', content)
    
    return content

for filepath in files_to_update:
    if os.path.exists(filepath):
        with open(filepath, 'r') as f:
            data = f.read()
        
        new_data = replace_colors(data)
        
        with open(filepath, 'w') as f:
            f.write(new_data)
        print(f"Updated: {filepath}")
    else:
        print(f"Not found: {filepath}")

