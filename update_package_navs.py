import os
import re

# Define the base directory
base_dir = r"C:\Users\USER\Desktop\Jetwide-web\pages\packages"

# List of all package files (excluding already updated ones)
files = [
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
    'south-africa-school-trip.html'
]

for filename in files:
    file_path = os.path.join(base_dir, filename)
    
    try:
        # Read the file
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Step 1: Replace "Visa Services-PLACEHOLDER" back to proper structure
        content = content.replace('Visa Services-PLACEHOLDER', 'Visa Services')
        
        # Step 2: Replace "Job Placements-PLACEHOLDER" back
        content = content.replace('Job Placements-PLACEHOLDER', 'Job Placements')
        
        # Step 3: Add "Last Minute Deals" section after Safari Packages section
        # Find the International Destinations header and add Last Minute Deals before it
        content = content.replace(
            '''                            <div class="dropdown-section">
                                <h4 class="dropdown-header">International Destinations</h4>''',
            '''                            <div class="dropdown-section">
                                <h4 class="dropdown-header">Last Minute Deals</h4>
                                <a href="../../index.html#special-offers" class="dropdown-item">Special Offers</a>
                            </div>
                            <div class="dropdown-section">
                                <h4 class="dropdown-header">International Destinations</h4>'''
        )
        
        # Step 4: Restructure navigation - remove standalone VISA Processing link
        content = content.replace(
            '''<a href="../../index.html">Home</a>
                    <a href="../visa-services.html">Visa Services-PLACEHOLDER</a>''',
            '''<a href="../../index.html">Home</a>'''
        )
        
        # Step 5: Restructure Other Services dropdown
        content = content.replace(
            '''<div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../job-placement.html" class="dropdown-item">Job Placements-PLACEHOLDER</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking & Airport Transfer Services</a>''',
            '''<a href="../job-placement.html">Job Placements</a>
                    <div class="dropdown">
                        <a href="../../index.html#services" class="dropdown-toggle">Other Services <span class="dropdown-arrow">▼</span></a>
                        <div class="dropdown-menu">
                            <a href="../visa-services.html" class="dropdown-item">Visa Services</a>
                            <a href="../car-hire.html" class="dropdown-item">Car Hire Services</a>
                            <a href="../airline-airport-services.html" class="dropdown-item">Airline Booking</a>'''
        )
        
        # Step 6: Add Gallery to About Jetwide dropdown
        content = content.replace(
            '''<a href="../contact.html" class="dropdown-item">Contact Us</a>
                        </div>
                    </div>
                </nav>''',
            '''<a href="../contact.html" class="dropdown-item">Contact Us</a>
                            <a href="../../index.html#gallery" class="dropdown-item">Gallery</a>
                        </div>
                    </div>
                </nav>'''
        )
        
        # Write the updated content back
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        print(f"✓ Updated: {filename}")
        
    except Exception as e:
        print(f"✗ Error updating {filename}: {str(e)}")

print("\nAll package files have been updated!")
