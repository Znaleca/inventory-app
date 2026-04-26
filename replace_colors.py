import os
import sys

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    # Apply replacements
    content = content.replace('border-slate-200', 'border-sky-100')
    content = content.replace('bg-slate-50', 'bg-sky-50')
    content = content.replace('bg-blue-600', 'bg-sky-500')
    content = content.replace('hover:bg-blue-700', 'hover:bg-sky-600')
    content = content.replace('border-blue-700', 'border-sky-600')
    content = content.replace('text-blue-600', 'text-sky-500')
    content = content.replace('text-slate-800', 'text-[#0f172a]')
    content = content.replace('bg-indigo-500', 'bg-sky-500')
    content = content.replace('bg-indigo-50 ', 'bg-sky-50 ')
    content = content.replace('border-indigo-200', 'border-sky-200')
    content = content.replace('text-indigo-600', 'text-sky-600')
    content = content.replace('hover:bg-indigo-600', 'hover:bg-sky-600')
    content = content.replace('hover:border-indigo-600', 'hover:border-sky-600')
    content = content.replace('border-slate-100', 'border-sky-100')
    
    # Header specific
    content = content.replace('<div class="items-theme bg-white rounded-2xl overflow-hidden border border-sky-100">', '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">')

    # Remove the style block if it exists
    style_block = """<style>
    .items-theme [class*="border-slate-200"] { border-color: #dbeafe !important; }
    .items-theme [class*="bg-slate-50"] { background-color: #f8fbff !important; }
    .items-theme [class*="text-blue-600"] { color: #0284c7 !important; }
</style>"""
    content = content.replace(style_block, '')

    with open(filepath, 'w') as f:
        f.write(content)

for filename in ['index.blade.php', 'create.blade.php', 'edit.blade.php', 'show.blade.php']:
    filepath = os.path.join('resources/views/items', filename)
    if os.path.exists(filepath):
        process_file(filepath)
        print(f"Processed {filepath}")
