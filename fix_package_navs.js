const fs = require('fs');
const path = require('path');

const baseDir = 'C:\\Users\\USER\\Desktop\\Jetwide-web\\pages\\packages';

const files = fs.readdirSync(baseDir).filter(f => f.endsWith('.html'));

let updatedCount = 0;

files.forEach(filename => {
    const filePath = path.join(baseDir, filename);
    
    try {
        let content = fs.readFileSync(filePath, 'utf-8');
        let updated = false;
        
        // Pattern 1: Remove standalone Visa Services link and restructure navigation
        if (content.includes('<a href="../visa-services.html">Visa Services</a>')) {
            // Remove the standalone Visa Services link
            content = content.replace(
                `<a href="../../index.html">Home</a>
                    <a href="../visa-services.html">Visa Services</a>
                    <div class="dropdown">`,
                `<a href="../../index.html">Home</a>
                    <div class="dropdown">`
            );
            
            // Add International Destinations to Safari Packages section
            content = content.replace(
                `<a href="../kenyan-safaris.html" class="dropdown-item">Exclusive Kenyan Safaris</a>
                            </div>
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">International Destinations</h4>
                                <a href="../international-destinations.html" class="dropdown-item">International Destinations</a>`,
                `<a href="../kenyan-safaris.html" class="dropdown-item">Exclusive Kenyan Safaris</a>
                                <a href="../international-destinations.html" class="dropdown-item">International Destinations</a>
                            </div>
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">Last Minute Deals</h4>
                                <a href="../../index.html#special-offers" class="dropdown-item">Special Offers</a>`
            );
            
            // Move Job Placements out and add Visa Services to Other Services
            content = content.replace(
                `</div>
                    </div>
                    <div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../job-placement.html" class="dropdown-item">Job Placements</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking & Airport Transfer Services</a>`,
                `</div>
                    </div>
                    <a href="../job-placement.html">Job Placements</a>
                    <div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../visa-services.html" class="dropdown-item">Visa Services</a>
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking</a>`
            );
            
            // Add Gallery to About Jetwide
            content = content.replace(
                `<a href="../contact.html" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                </nav>`,
                `<a href="../contact.html" class="dropdown-item">Contact Us</a>
                            <a href="../../index.html#gallery" class="dropdown-item">Gallery</a>
                        </div>
                    </div>
                </nav>`
            );
            
            updated = true;
        }
        
        if (updated) {
            fs.writeFileSync(filePath, content, 'utf-8');
            console.log(`✓ Updated: ${filename}`);
            updatedCount++;
        } else {
            console.log(`⊗ Skipped: ${filename}`);
        }
        
    } catch (error) {
        console.log(`✗ Error: ${filename} - ${error.message}`);
    }
});

console.log(`\n✓ Successfully updated ${updatedCount} files!`);
