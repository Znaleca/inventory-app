import re

# 1. Update app.blade.php for Light Mode
with open('resources/views/layouts/app.blade.php', 'r') as f:
    app_content = f.read()

app_content = app_content.replace('bg-[#13151b]', 'bg-slate-50')
app_content = app_content.replace('text-white', 'text-slate-800')
app_content = app_content.replace('bg-[#1f212a]', 'bg-slate-900') # Sidebar
app_content = app_content.replace('border-[#2a2c36]', 'border-slate-800') # Sidebar border

# Revert Header properties explicitly
app_content = app_content.replace('class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50"', 'class="flex-1 flex flex-col h-full overflow-hidden bg-white"')
app_content = re.sub(r'<header[^>]*>', '<header class="relative z-30 flex h-[4.5rem] items-center justify-between gap-4 px-8 bg-white border-b border-slate-200 shrink-0">', app_content)

# Dropdown / Auth Menu
app_content = app_content.replace('bg-[#2a2c36]', 'bg-white')
app_content = app_content.replace('border-[#3b3d4a]', 'border-slate-200')
app_content = app_content.replace('hover:bg-[#323441]', 'hover:bg-slate-50')
app_content = app_content.replace('text-slate-300', 'text-slate-700')

with open('resources/views/layouts/app.blade.php', 'w') as f:
    f.write(app_content)


# 2. Update dashboard.blade.php for Light Mode
with open('resources/views/dashboard.blade.php', 'r') as f:
    dash_content = f.read()

# Protect text inside the gradient top cards (Total, Low Stock, etc) from being turned dark
# The top cards span up to the Charts section. We will do a generic replacement BUT be careful!
# Actually, the quickest way is replace all, then fix the gradient specific texts:

dash_content = dash_content.replace('text-white', 'text-slate-800')
dash_content = dash_content.replace('text-slate-400', 'text-slate-600')
dash_content = dash_content.replace('text-slate-300', 'text-slate-500')

# Borders and Dividers
dash_content = dash_content.replace('border-[#3b3d4a]', 'border-slate-200')
dash_content = dash_content.replace('border-[#2a2c36]', 'border-slate-200')
dash_content = dash_content.replace('divide-[#2a2c36]', 'divide-slate-200')

# Backgrounds
dash_content = dash_content.replace('bg-[#2a2c36]', 'bg-white') # if any headers
dash_content = dash_content.replace('hover:bg-[#2a2c36]/50', 'hover:bg-slate-50')
dash_content = dash_content.replace('hover:bg-[#2a2c36]', 'hover:bg-slate-50')

# Restore Header Text in Top Metrics to White
def restore_header_text(match):
    return match.group(0).replace('text-slate-800', 'text-white')

# Both main gradient metric cards (Purple-Pink & Cyan-Blue) have drop-shadow-sm
dash_content = re.sub(r'<p class="text-3xl font-black text-slate-800 tracking-tight drop-shadow-sm">.*?</p>', restore_header_text, dash_content)
dash_content = re.sub(r'<p class="text-4xl font-black tracking-tighter text-slate-800 drop-shadow-sm truncate">.*?</p>', restore_header_text, dash_content, flags=re.DOTALL)

# Revert SVG icons inside bg-white/20 rings back to white
dash_content = dash_content.replace('bg-white/20 text-slate-800', 'bg-white/20 text-white')
dash_content = dash_content.replace('bg-white/10 text-slate-800', 'bg-white/10 text-white')

# Chart grid lines back to dark mode suitable for light backgrounds
dash_content = dash_content.replace("rgba(255,255,255,0.03)", "rgba(0,0,0,0.05)")

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(dash_content)
