# PowerShell script to update navigation in all package files

$oldNav = @'
        <nav class="nav-menu">
          <a href="../../index.html">Home</a>
          <a href="../visa-services.html">VISA Processing</a>
          <div class="dropdown">
            <a href="../../index.html#tours" class="dropdown-toggle">Tours & Safaris <span class="dropdown-arrow">▼</span></a>
            <div class="dropdown-menu">
              <div class="dropdown-section">
                <h4 class="dropdown-header">Local Tours</h4>
                <a href="../themed-packages.html" class="dropdown-item">Themed Packages</a>
                <a href="../beach-getaways.html" class="dropdown-item">Beach Getaways</a>
                <a href="../kenyan-safaris.html" class="dropdown-item
'@

$newNav = @'
        <nav class="nav-menu">
          <a href="../../index.html">Home</a>
          <div class="dropdown">
            <a href="../../index.html#tours" class="dropdown-toggle">Tours & Safaris <span class="dropdown-arrow">▼</span></a>
            <div class="dropdown-menu">
              <div class="dropdown-section">
                <h4 class="dropdown-header">Safari Packages</h4>
                <a href="../themed-packages.html" class="dropdown-item">Themed Packages</a>
                <a href="../beach-getaways.html" class="dropdown-item">Beach Getaways</a>
                <a href="../kenyan-safaris.html" class="dropdown-item
'@

$packagePath = "C:\Users\USER\Desktop\Jetwide-web\pages\packages\"
$files = Get-ChildItem -Path $packagePath -Filter "*.html"

foreach ($file in $files) {
    Write-Host "Processing: $($file.Name)" -ForegroundColor Cyan
    
    # Read file content
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    
    # Check if old navigation exists
    if ($content -match [regex]::Escape('<a href="../visa-services.html">VISA Processing</a>')) {
        # Perform multiple replacements
        $content = $content -replace [regex]::Escape('<a href="../visa-services.html">VISA Processing</a>'), ''
        $content = $content -replace [regex]::Escape('<h4 class="dropdown-header">Local Tours</h4>'), '<h4 class="dropdown-header">Safari Packages</h4>'
        $content = $content -replace [regex]::Escape('<h4 class="dropdown-header">International Destinations</h4>'), '<h4 class="dropdown-header">Last Minute Deals</h4>
                <a href="../../index.html#special-offers" class="dropdown-item">Special Offers</a>
              </div>
              <div class="dropdown-section">
                <h4 class="dropdown-header">International Destinations</h4>'
        $content = $content -replace [regex]::Escape('<a href="../job-placement.html" class="dropdown-item">Job Placement Services</a>'), ''
        $content = $content -replace [regex]::Escape('</div>
          <div class="dropdown">
            <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
            <div class="dropdown-menu">
              <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>'), '</div>
          <a href="../job-placement.html">Job Placements</a>
          <div class="dropdown">
            <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
            <div class="dropdown-menu">
              <a href="../visa-services.html" class="dropdown-item">Visa Services</a>
              <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>'
        $content = $content -replace [regex]::Escape('<a href="../airline-airport-services.html" class="dropdown-item">Airline Booking & Airport Transfer Services</a>'), '<a href="../airline-airport-services.html" class="dropdown-item">Airline Booking</a>'
        $content = $content -replace [regex]::Escape('              <a href="../contact.html" class="dropdown-item">Contact Us</a>
            </div>
          </div>
        </nav>'), '              <a href="../contact.html" class="dropdown-item">Contact Us</a>
              <a href="../../index.html#gallery" class="dropdown-item">Gallery</a>
            </div>
          </div>
        </nav>'
        
        # Write back to file
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
        Write-Host "✓ Updated: $($file.Name)" -ForegroundColor Green
    } else {
        Write-Host "⊗ Skipped: $($file.Name) (already updated or different format)" -ForegroundColor Yellow
    }
}

Write-Host "`nAll package files processed!" -ForegroundColor Cyan
