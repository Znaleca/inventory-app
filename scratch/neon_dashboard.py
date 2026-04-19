import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Update the Main Card Gradients (Top 4 Metrics)
content = content.replace('from-emerald-400 to-teal-500', 'from-[#b026ff] to-[#ff2d88]') # Purple to Pink
content = content.replace('from-orange-400 to-red-500', 'from-[#00f2fe] to-[#4facfe]')   # Cyan to Blue
content = content.replace('from-amber-400 to-yellow-500', 'from-emerald-400 to-teal-500') # Green/Teal (for Health/Expiring)
content = content.replace('from-rose-400 to-pink-600', 'from-rose-500 to-red-600')     # Deep Red

# Darken Top Metric Cards drop shadow
content = content.replace('shadow-emerald-500/30', 'shadow-[#b026ff]/30')
content = content.replace('shadow-orange-500/30', 'shadow-[#00f2fe]/30')

# 2. Update the Smaller "Additional Metrics" to be generic dark cards with Neon Icons
def additional_metrics_replacer(match):
    # Changes absolute gradient bg to dark #1f212a bg
    block = match.group(0)
    block = re.sub(r'bg-gradient-to-br from-\w+-400 to-\w+-500|bg-gradient-to-br from-\w+-400 to-\w+-600', 'bg-[#1f212a] border border-[#2a2c36]', block)
    block = block.replace('shadow-lg', 'shadow-md')
    block = re.sub(r'shadow-\w+-500/30', 'shadow-black/20', block)
    # The text "{{ $totalNewStock }}" is text-white, the subtext is light blue -> change to slate-400
    block = re.sub(r'text-[a-z]+-50', 'text-slate-400', block)
    return block
content = re.sub(r'\{\{-- Additional Metrics --\}\}.*?(?=\{\{-- Charts Section --\}\})', additional_metrics_replacer, content, flags=re.DOTALL)

# 3. Chart wrappers
content = content.replace('bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700', 'bg-[#1f212a] border border-[#2a2c36]')
# Disable the giant blurs behind charts so they look clean like the CRM ref
content = re.sub(r'<div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-[^"]+ blur-3xl"></div>', '', content)
content = re.sub(r'<div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[^"]+ blur-3xl"></div>', '', content)

# Chart labels
content = content.replace('text-[#94a3b8]', "text-[#64748b]")
content = content.replace("color: 'rgba(255,255,255,0.1)'", "color: 'rgba(255,255,255,0.03)'")

# Doughnut Chart Neon colors
doughnut_colors = """backgroundColor: [
                                '#ff2d88', // neon pink
                                '#00f2fe', // cyan
                                '#b026ff', // purple
                                '#f43f5e', // rose
                            ],"""
content = re.sub(r"backgroundColor:\s*\[\s*'#[\da-f]{6}',.*?\]\s*,", doughnut_colors, content, count=1, flags=re.DOTALL)

# Bar Chart Neon colors
bar_colors = """backgroundColor: [
                                '#00f2fe', // cyan
                                '#b026ff', // purple
                                '#ff2d88', // neon pink
                                '#f43f5e'  // rose
                            ],"""
content = re.sub(r"backgroundColor:\s*\[\s*'#[\da-f]{6}',.*?\]\s*,", bar_colors, content, count=1, flags=re.DOTALL)


# 4. Tables styling
# Base Table container
content = content.replace('bg-white border border-slate-200 shadow-md', 'bg-[#1f212a] border border-[#2a2c36] shadow-[0_10px_40px_rgba(0,0,0,0.2)]')
content = content.replace('bg-white border border-slate-300', 'bg-[#1f212a] border border-[#3b3d4a]')
# Table header
content = content.replace('tr class="bg-slate-100"', 'tr class="bg-[#2a2c36]"')
content = content.replace('border border-slate-300 px-3 py-2 text-left font-bold text-slate-700', 'border border-[#3b3d4a] px-3 py-2 text-left font-bold text-slate-300')
content = content.replace('border border-slate-300 px-3 py-2 text-center font-bold text-slate-700', 'border border-[#3b3d4a] px-3 py-2 text-center font-bold text-slate-300')
content = content.replace('border border-slate-300 px-3 py-2 text-right font-bold text-slate-700', 'border border-[#3b3d4a] px-3 py-2 text-right font-bold text-slate-300')

# Table body cells
content = content.replace('border border-slate-300', 'border border-[#3b3d4a]')
content = content.replace('hover:bg-slate-50', 'hover:bg-[#2a2c36]')
content = content.replace('text-slate-800', 'text-white')
content = content.replace('text-slate-600', 'text-slate-400')
content = content.replace('text-slate-700', 'text-slate-300')

# Colored badges inside tables to match neon
content = content.replace('bg-rose-50', 'bg-rose-500/10')
content = content.replace('bg-amber-50', 'bg-amber-500/10')
content = content.replace('bg-emerald-50', 'bg-emerald-500/10')
content = content.replace('bg-slate-50', 'bg-slate-500/10')
content = content.replace('bg-stone-50', 'bg-stone-500/10')

# Card headers gradients -> make them dark like CRM
content = re.sub(r'bg-gradient-to-r from-\w+-400 to-\w+-\d+', 'bg-[#2a2c36] border-b border-[#3b3d4a]', content)
content = re.sub(r'bg-gradient-to-r from-\w+-500 to-\w+-\d+', 'bg-[#2a2c36] border-b border-[#3b3d4a]', content)
content = re.sub(r'bg-gradient-to-r from-slate-700 to-slate-900', 'bg-[#2a2c36] border-b border-[#3b3d4a]', content)


# Save
with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)
