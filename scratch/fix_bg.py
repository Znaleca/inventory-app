with open('resources/views/dashboard.blade.php', 'r') as f:
    c = f.read()

# 1. Update Secondary Metrics Backgrounds
# They all currently have the exact class block below
bad_class = "relative overflow-hidden rounded-3xl bg-transparent border-transparent p-5 shadow-md shadow-black/20 transition-all duration-300 hover:-translate-y-1 group"

c = c.replace(bad_class, "relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-400 to-purple-600 p-5 shadow-lg shadow-purple-500/20 transition-all duration-300 hover:-translate-y-1 group", 1)
c = c.replace(bad_class, "relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-400 to-blue-500 p-5 shadow-lg shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-1 group", 1)
c = c.replace(bad_class, "relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-400 to-teal-500 p-5 shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1 group", 1)
c = c.replace(bad_class, "relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-400 to-pink-500 p-5 shadow-lg shadow-rose-500/20 transition-all duration-300 hover:-translate-y-1 group", 1)

# 2. Fix the sub-label text color on those secondary metrics
# It is currently text-slate-600 inside the now-gradients cards
c = c.replace("text-slate-600 mt-1 drop-shadow-sm", "text-white/80 mt-1 drop-shadow-sm")

# 3. Fix Chart Containers
# Currently: class="relative overflow-hidden bg-transparent border-transparent  rounded-3xl p-6"
chart_bad = "relative overflow-hidden bg-transparent border-transparent  rounded-3xl p-6"
c = c.replace(chart_bad, "relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-300 shadow-xl shadow-slate-200  rounded-3xl p-6", 1)
c = c.replace(chart_bad, "relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-300 shadow-xl shadow-slate-200  rounded-3xl p-6", 1)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(c)
