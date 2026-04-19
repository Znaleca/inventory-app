import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Strip the boxy table class
content = content.replace('table class="min-w-full text-sm border-collapse border border-[#3b3d4a] bg-white"', 'table class="min-w-full text-sm"')

# 2. Fix Header
content = content.replace('tr class="bg-[#2a2c36]"', 'tr class="border-b border-[#3b3d4a]"')

# 3. Strip borders from TH
content = content.replace('border border-[#3b3d4a] px-3 py-2 text-left font-bold text-slate-300', 'px-3 py-4 text-left font-bold text-slate-400 uppercase tracking-widest text-[10px]')
content = content.replace('border border-[#3b3d4a] px-3 py-2 text-center font-bold text-slate-300', 'px-3 py-4 text-center font-bold text-slate-400 uppercase tracking-widest text-[10px]')
content = content.replace('border border-[#3b3d4a] px-3 py-2 text-right font-bold text-slate-300', 'px-3 py-4 text-right font-bold text-slate-400 uppercase tracking-widest text-[10px]')

# 4. Add divide-y to TBODY
content = content.replace('<tbody>', '<tbody class="divide-y divide-[#2a2c36]">')

# 5. Strip borders from TD
content = content.replace('border border-[#3b3d4a] px-3 py-2', 'px-3 py-3')

# 6. Enhance TR hover
content = content.replace('tr class="hover:bg-[#2a2c36] transition-colors"', 'tr class="hover:bg-[#2a2c36]/50 transition-colors"')

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)
