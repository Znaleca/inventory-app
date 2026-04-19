import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# Make the charts and small secondary metrics blend in as well
content = content.replace('bg-[#1f212a] border border-[#2a2c36]', 'bg-transparent border-transparent')

# And remove any remaining harsh drop-shadows that would look weird without a box
content = re.sub(r'shadow-\[.*?\]', '', content)
content = re.sub(r'shadow-2xl shadow-black/40', '', content)
content = re.sub(r'shadow-2xl shadow-slate-900/40', '', content)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)
