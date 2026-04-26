import os
import re

files_to_update = {
    'resources/views/categories/index.blade.php': {
        'url': 'Categories',
        'title': 'Category Registry',
        'var': '$categories',
        'count_append': 'records'
    },
    'resources/views/units/index.blade.php': {
        'url': 'Units',
        'title': 'Unit Master List',
        'var': '$units',
        'count_append': 'units'
    },
    'resources/views/locations/index.blade.php': {
        'url': 'Locations',
        'title': 'Storage Locations',
        'var': '$locations',
        'count_append': 'locations'
    }
}

for filepath, meta in files_to_update.items():
    if not os.path.exists(filepath): continue
    
    with open(filepath, 'r') as f:
        content = f.read()
        
    # Remove old Page Header
    content = re.sub(r'\{\{-- Page Header --\}\}\n<div class="mb-5 flex items-end justify-between">.*?</div>', '', content, flags=re.DOTALL)
    
    # Check if there is an outer <div class="bg-white rounded-2xl overflow-hidden border border-sky-100">
    # If not, we will inject it after @section('content')
    if '<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">' not in content:
        header_html = f"""<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

{{-- Page Header --}}
<div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0">
    <div>
        <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Inventory://{meta['url']}</p>
        <h3 class="text-xl font-black text-[#0f172a] tracking-tight">{meta['title']}</h3>
    </div>
    <span class="rounded-lg bg-sky-50 px-3 py-1.5 text-[11px] font-bold text-sky-600">{{{{ {meta['var']}->count() }}}} {meta['count_append']}</span>
</div>
"""
        content = content.replace("@section('content')", f"@section('content')\n\n{header_html}")
        
        # Add closing div before @endsection
        content = content.replace("@endsection", "</div>\n\n@endsection")

    # The main table also has a bg-white border border-sky-100, we should remove its border to avoid double borders.
    content = content.replace('<div class="bg-white border border-sky-100 relative overflow-hidden">', '<div class="bg-white relative overflow-hidden">')
    
    with open(filepath, 'w') as f:
        f.write(content)
    print(f"Updated {filepath}")
