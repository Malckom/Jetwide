const fs = require('fs');
const path = require('path');

const baseDir = 'C:\\Users\\USER\\Desktop\\Jetwide-web\\pages\\packages';
const files = fs.readdirSync(baseDir).filter(f => f.endsWith('.html'));

let updated = 0;
let skipped = 0;

const oldNavPattern = `<a href="../../index.html">Home</a>
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

const newNavPattern = `<a href="../../index.html">Home</a>
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

files.forEach(filename => {
    try {
        const filePath = path.join(baseDir, filename);
        let content = fs.readFileSync(filePath, 'utf-8');
        
        if (content.includes(oldNavPattern)) {
            content = content.replace(oldNavPattern, newNavPattern);
            fs.writeFileSync(filePath, content, 'utf-8');
            console.log(`✓ Updated: ${filename}`);
            updated++;
        } else {
            console.log(`⊗ Skipped: ${filename}`);
            skipped++;
        }
    } catch (error) {
        console.log(`✗ Error: ${filename} - ${error.message}`);
    }
});

console.log(`\n✓ Updated: ${updated} files`);
console.log(`⊗ Skipped: ${skipped} files`);
console.log(`Total: ${files.length} files`);
