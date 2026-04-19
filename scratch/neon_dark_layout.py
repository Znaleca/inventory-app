import re

with open('resources/views/layouts/app.blade.php', 'r') as f:
    content = f.read()

# Base Colors
content = content.replace('bg-slate-50', 'bg-[#13151b]')
content = content.replace('text-slate-800', 'text-white')
content = content.replace('bg-slate-900', 'bg-[#1f212a]')
content = content.replace('bg-white', 'bg-[#1f212a]')
content = content.replace('border-slate-200', 'border-[#2a2c36]')

# Sidebar Active/Hover states
content = content.replace("'bg-slate-800 text-white'", "'bg-fuchsia-500/10 text-fuchsia-400 border-l-[3px] border-fuchsia-500 font-bold shadow-[inset_4px_0_10px_rgba(217,70,239,0.1)]'")
content = content.replace("'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200'", "'text-slate-400 hover:bg-[#2a2c36] border-l-[3px] border-transparent hover:text-white hover:border-[#3b3d4a]'")
content = content.replace("'text-blue-400 w-5 h-5'", "'text-fuchsia-400 w-5 h-5 drop-shadow-[0_0_8px_rgba(217,70,239,0.8)]'")
content = content.replace("group-hover:text-blue-400/70", "group-hover:text-fuchsia-400/70")

# Sidebar Admin Links Active
content = content.replace("'bg-blue-600/20 text-blue-400'", "'bg-cyan-500/10 text-cyan-400 border-l-[3px] border-cyan-500 font-bold shadow-[inset_4px_0_10px_rgba(6,182,212,0.1)]'")

# Fix up the header and header profile dropdown
def replace_header_avatar(match):
    return match.group(0).replace('text-slate-700', 'text-white').replace('bg-[#1f212a]', 'bg-[#2a2c36]').replace('border border-[#2a2c36]', 'border border-fuchsia-500/20')
content = re.sub(r'<button @click="open = !open".*?</button>', replace_header_avatar, content, flags=re.DOTALL)

# Header Title styling 
content = content.replace('text-xl font-extrabold text-white', 'text-xl font-extrabold text-white drop-shadow-sm')
content = content.replace('bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]', 'bg-fuchsia-500 shadow-[0_0_8px_rgba(217,70,239,0.8)]')

# Dropdown links
content = content.replace('text-slate-700 hover:bg-[#13151b] hover:text-blue-600 border-b border-slate-100', 'text-slate-300 hover:bg-[#2a2c36] hover:text-fuchsia-400 border-b border-[#2a2c36]')
content = content.replace('text-slate-700 hover:bg-[#13151b] hover:text-rose-600', 'text-slate-300 hover:bg-[#2a2c36] hover:text-rose-500')

with open('resources/views/layouts/app.blade.php', 'w') as f:
    f.write(content)
