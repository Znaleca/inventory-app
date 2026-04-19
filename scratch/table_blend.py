import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# 1. Remove the solid dark backplate from the main table containers
old_container = 'class="bg-[#1f212a] border border-[#2a2c36] shadow-[0_10px_40px_rgba(0,0,0,0.2)] rounded-3xl flex flex-col pt-0 overflow-hidden"'
new_container = 'class="bg-transparent flex flex-col pt-0 overflow-hidden mb-4"'
content = content.replace(old_container, new_container)

# 2. For the headers of the Expiring & Disposal cards, remove the heavy bg and border
# They were: bg-[#2a2c36] border-b border-[#3b3d4a] px-5 py-4 shrink-0 shadow-md relative z-10
header_pattern = r'bg-\[#2a2c36\] border-b border-\[#3b3d4a\] px-5 py-4 shrink-0 shadow-md relative z-10'
header_replace = 'bg-transparent border-b border-[#2a2c36] px-2 py-4 shrink-0 relative z-10'
content = re.sub(header_pattern, header_replace, content)

# 3. For the gradient header (Low Stock), let's keep the gradient but remove the sharp edges or change to robust blend?
# Wait, "border design per content" might imply they don't want the gradient box either. 
# They just want the header text and the icon! 
gradient_pattern = r'bg-gradient-to-r from-\[#00f2fe\] to-\[#4facfe\] px-5 py-4 shrink-0 shadow-md relative z-10'
gradient_replace = 'bg-transparent border-b border-[#2a2c36] px-2 py-4 shrink-0 relative z-10'
content = re.sub(gradient_pattern, gradient_replace, content)

with open('resources/views/dashboard.blade.php', 'w') as f:
    f.write(content)
