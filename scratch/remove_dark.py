import os
import re

directory = '/Users/acelanz/Documents/inventory-app/resources/views'

def remove_dark_classes(content):
    # Match various dark mode classes: dark:bg-something, dark:text-something, dark:hover:..., etc.
    # We will replace all patterns matching 'dark:[\w\-\/\[\]#%]+' with empty string.
    # Example: dark:bg-[#0E1116] or dark:text-gray-300
    new_content = re.sub(r'\bdark:[a-zA-Z0-9\-\/\[\]#%:]+\b', '', content)
    # Removing any double spaces left behind between html class attributes
    new_content = re.sub(r' +', ' ', new_content)
    # clean up `class=" "` to `class=""`
    new_content = re.sub(r'class=" "', 'class=""', new_content)
    return new_content

def process_dir(dir_path):
    for root, dirs, files in os.walk(dir_path):
        for file in files:
            if file.endswith('.blade.php'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r') as f:
                    content = f.read()
                
                new_content = remove_dark_classes(content)
                
                if content != new_content:
                    with open(filepath, 'w') as f:
                        f.write(new_content)
                    print(f"Updated {filepath}")

process_dir(directory)
print("Finished processing all blade templates.")
