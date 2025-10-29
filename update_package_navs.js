const fs = require('fs');
const path = require('path');

// Define the base directory
const baseDir = 'C:\\Users\\USER\\Desktop\\Jetwide-web\\pages\\packages';

// List of all package files
const files = [
    'amboseli-naivasha-mara-safari.html',
    'amboseli-olpejeta-nakuru-mara-safari.html',
    'amboseli-school-trip.html',
    'bali-school-trip.html',
    'classic-big-five-loop-safari.html',
    'diani-budget-getaway.html',
    'discover-kuala-lumpur-ipoh-penang.html',
    'dubai-experience.html',
    'dubai-honeymoon.html',
    'dubai-luxury-experience.html',
    'egypt-cairo-sharm-school-trip.html',
    'egypt-nile-cruise-school-trip.html',
    'egypt-nile-cruise.html',
    'kuala-lumpur-escape.html',
    'kuala-lumpur-shopping-tour.html',
    'maasai-mara-school-trip.html',
    'malaysia-honeymoon.html',
    'maldives-honeymoon.html',
    'maldives-resort-experience.html',
    'mombasa-school-trip.html',
    'rift-valley-school-trip.html',
    'samburu-safari.html',
    'seychelles-mahe-wonders.html',
    'seychelles-praslin-mahe.html',
    'south-africa-honeymoon.html',
    'south-africa-school-trip.html',
    'tsavo-east-west-safari.html'
];

files.forEach(filename => {
    const filePath = path.join(baseDir, filename);
    
    try {
        // Read the file
        let content = fs.readFileSync(filePath, 'utf-8');
        
        // Full replacement of the entire nav structure
        const oldNav = `                <nav class="nav-menu">
                    <a href="../../index.html">Home</a>
                    <a href="../visa-services.html">Visa Services</a>
                    <div class="dropdown">
                        <a href="../../index.html#tours" class="dropdown-toggle">Tours & Safaris <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">Safari Packages</h4>
                                <a href="../themed-packages.html" class="dropdown-item">Themed Packages</a>
                                <a href="../beach-getaways.html" class="dropdown-item">Beach Getaways</a>
                                <a href="../kenyan-safaris.html" class="dropdown-item">Exclusive Kenyan Safaris</a>
                            </div>
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">International Destinations</h4>
                                <a href="../international-destinations.html" class="dropdown-item">International Destinations</a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../job-placement.html" class="dropdown-item">Job Placements</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking & Airport Transfer Services</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="../about-us.html" class="dropdown-toggle">About Jetwide <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../about-us.html" class="dropdown-item">About Us</a>
                            <a href="../blogs.html" class="dropdown-item">Blogs</a>
                            <a href="../contact.html" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                </nav>`;
        
        const newNav = `                <nav class="nav-menu">
                    <a href="../../index.html">Home</a>
                    <div class="dropdown">
                        <a href="../../index.html#tours" class="dropdown-toggle">Tours & Safaris <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">Safari Packages</h4>
                                <a href="../themed-packages.html" class="dropdown-item">Themed Packages</a>
                                <a href="../beach-getaways.html" class="dropdown-item">Beach Getaways</a>
                                <a href="../kenyan-safaris.html" class="dropdown-item">Exclusive Kenyan Safaris</a>
                                <a href="../international-destinations.html" class="dropdown-item">International Destinations</a>
                            </div>
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">Last Minute Deals</h4>
                                <a href="../../index.html#special-offers" class="dropdown-item">Special Offers</a>
                            </div>
                        </div>
                    </div>
                    <a href="../job-placement.html">Job Placements</a>
                    <div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../visa-services.html" class="dropdown-item">Visa Services</a>
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="../about-us.html" class="dropdown-toggle">About Jetwide <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../about-us.html" class="dropdown-item">About Us</a>
                            <a href="../blogs.html" class="dropdown-item">Blogs</a>
                            <a href="../contact.html" class="dropdown-item">Contact Us</a>
                            <a href="../../index.html#gallery" class="dropdown-item">Gallery</a>
                        </div>
                    </div>
                </nav>`;
        
        // Replace the navigation
        if (content.includes(oldNav)) {
            content = content.replace(oldNav, newNav);
            fs.writeFileSync(filePath, content, 'utf-8');
            console.log(`✓ Updated: ${filename}`);
        } else {
            console.log(`⚠ Skipped: ${filename} (navigation already updated or different format)`);
        }
        
    } catch (error) {
        console.log(`✗ Error updating ${filename}: ${error.message}`);
    }
});

console.log('\nAll package files have been processed!');

