# !/bin/bash

# Change permissions for files of interest
# Make the script executable
#   chmod +x change_permissions.sh
# Run the script
#   ./change_permissions.sh

# Define the directories
pages_dir=../pages
css_dir=../css

# Change folder permissions to 711
chmod 711 "$pages_dir"
chmod 711 "$css_dir"

# Change file permissions inside pages folder to 711
find "$pages_dir" -type f -exec chmod 711 {} \;

# Change file permissions inside css folder to 755
find "$css_dir" -type f -exec chmod 755 {} \;

echo "Permissions updated successfully."