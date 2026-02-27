@echo off
echo Starting PHP Development Server...
echo Access your project at http://localhost:8000
php -d upload_max_filesize=50M -d post_max_size=55M -d memory_limit=512M -S localhost:8000 index.php
pause
