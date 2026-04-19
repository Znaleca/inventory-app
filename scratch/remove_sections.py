import re

with open('resources/views/dashboard.blade.php', 'r') as f:
    content = f.read()

# Removing "Recent Usage", "Recent Returns", and "Recent Disposals" sections
# Since they are at the end of the "Main Tables Grid", we can truncate from the Recent Usage comment onwards
# and then append the closing divs.

# Let's find the start of {{-- Recent Usage --}}
split_token = '{{-- Recent Usage --}}'
if split_token in content:
    parts = content.split(split_token)
    
    # parts[0] is everything before Recent Usage
    # parts[1] is everything from Recent Usage to the end of the file.
    # We must ensure we put back `</div>\n@endsection` properly.
    
    new_content = parts[0].rstrip() + "\n </div>\n@endsection\n"
    
    with open('resources/views/dashboard.blade.php', 'w') as f:
        f.write(new_content)
