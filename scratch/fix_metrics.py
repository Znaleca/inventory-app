import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Top 4 Metrics Fix text-slate-800 -> text-white
# They span lines ~20 to ~140. 
# We'll just replace 'text-slate-800' with 'text-white' explicitly for the big numbers and badges inside the gradient divs.
content = re.sub(r'class="text-[4|3]xl font-black [^"]*?text-slate-800 drop-shadow-sm', lambda m: m.group(0).replace('text-slate-800', 'text-white'), content)
content = re.sub(r'class="inline-flex items-center gap-1.5 rounded-full bg-white/20[^"]*?text-slate-800 shadow-sm ring-1 ring-white/40 backdrop-blur-md', lambda m: m.group(0).replace('text-slate-800', 'text-white'), content)


# 2. Secondary Metrics (New Items, etc.)
# Currently they are: bg-transparent border-transparent p-5 shadow-md shadow-black/20
# We need to give them gradients so they look good and the white text becomes readable!
grads = [
    'bg-gradient-to-br from-cyan-400 to-blue-500',
    'bg-gradient-to-br from-indigo-400 to-purple-600',
    'bg-gradient-to-br from-sky-400 to-blue-600',
    'bg-gradient-to-br from-pink-400 to-rose-500'
]

# We'll iteratively replace the transparent wrappers with the gradients
count = 0
def gradient_injector(match):
    global count
    grad = grads[count % len(grads)]
    count += 1
    return match.group(0).replace('bg-transparent border-transparent shadow-black/20', f'{grad} shadow-{grad.split("-")[2]}/30')

content = re.sub(r'bg-transparent border-transparent p-5 shadow-md shadow-black/20', gradient_injector, content)

# 3. Chart Blocks
# "and the graph and pie like gradient also"
# Currently they are:
# <div class="relative overflow-hidden bg-transparent border-transparent  rounded-3xl p-6">
# We want to give them subtle glowing modern gradients that fit Light Mode but provide that "card gradient" aesthetic.
# Let's use ultra-modern Light Mode premium gradients:
# Chart 1 (Doughnut): 'bg-gradient-to-br from-blue-50 to-indigo-100 border border-white shadow-xl shadow-blue-500/10'
# Chart 2 (Bar): 'bg-gradient-to-br from-emerald-50 to-teal-100 border border-white shadow-xl shadow-emerald-500/10'
chart_grads = [
    'bg-gradient-to-br from-slate-100 to-slate-200 border border-white shadow-xl shadow-slate-300 w-full',
    'bg-gradient-to-br from-slate-100 to-slate-200 border border-white shadow-xl shadow-slate-300 w-full'
]

c_count = 0
def chart_grad_injector(match):
    global c_count
    grad = chart_grads[c_count % len(chart_grads)]
    c_count += 1
    return f'class="relative overflow-hidden rounded-3xl p-6 {grad}"'

content = re.sub(r'class="relative overflow-hidden bg-transparent border-transparent[^"]*rounded-3xl p-6"', chart_grad_injector, content)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)
