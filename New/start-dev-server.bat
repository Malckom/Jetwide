@echo off
echo ================================
echo   JETWIDE NO-CACHE DEV SERVER
echo ================================
echo.
echo Starting development server...
echo This server prevents all caching issues!
echo.
echo Press Ctrl+C to stop the server
echo.

REM Try different Node.js commands
node server.js 2>nul
if %errorlevel% neq 0 (
    echo Node.js not found, trying npx serve...
    npx serve . --cors --no-clipboard --listen 3000
    if %errorlevel% neq 0 (
        echo.
        echo ❌ Neither Node.js nor npx found
        echo.
        echo Please install Node.js from: https://nodejs.org/
        echo Or use the browser method:
        echo 1. Open: http://localhost:3000/public/homepage.html
        echo 2. Press F9 to refresh images
        echo 3. Press Ctrl+R for hard refresh
        pause
    )
)