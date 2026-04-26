import os
import re

files_to_update = [
    'resources/views/categories/create.blade.php',
    'resources/views/categories/edit.blade.php',
    'resources/views/units/create.blade.php',
    'resources/views/units/edit.blade.php',
    'resources/views/locations/create.blade.php',
    'resources/views/locations/edit.blade.php',
]

pattern = re.compile(
    r"@section\('content'\)\n\s*<div>\n*\s*\{\{-- Page Header --\}\}\n\s*<div class=\"mb-5\">\n\s*<p class=\"text-\[10px\] font-mono font-semibold text-sky-500 uppercase tracking-\[0\.25em\] mb-1\">(.*?)</p>\n\s*<h1 class=\"text-xl font-bold text-\[\#0f172a\] tracking-tight\">(.*?)</h1>\n\s*<p class=\"text-xs text-slate-400 font-mono mt-0\.5\">(.*?)</p>\n\s*</div>"
)

replacement = r"""@section('content')
    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

        {{-- Page Header --}}
        <div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0 mb-6">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">\1</p>
                <h3 class="text-xl font-black text-[#0f172a] tracking-tight">\2</h3>
                <p class="text-xs text-slate-400 font-mono mt-1">\3</p>
            </div>
        </div>

        <div class="p-6 pt-0">"""

for filepath in files_to_update:
    if not os.path.exists(filepath): continue
    with open(filepath, 'r') as f:
        content = f.read()

    new_content = pattern.sub(replacement, content)
    
    # Update the closing tag
    if new_content != content:
        # replace the last </div> before @endsection
        new_content = re.sub(r'</form>\n\s*</div>\n@endsection', r'</form>\n        </div>\n    </div>\n@endsection', new_content)
        
    with open(filepath, 'w') as f:
        f.write(new_content)
    
    print(f"Updated {filepath}")
