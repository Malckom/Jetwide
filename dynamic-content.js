// Dynamic Content Loader for Jetwide Website
// Add this script to your index-ready.html before closing </body> tag

class JetwideContentManager {
    constructor() {
        // Use absolute path to WordPress API
        this.wpApiUrl = '/New/wp/wp-json/wp/v2/';
        console.log('🔗 WordPress API URL:', this.wpApiUrl);
        this.init();
    }

    async init() {
        await this.loadDestinations();
        await this.loadSpecialEvents();
        await this.loadServices();
    }

    // Load destinations from WordPress
    async loadDestinations() {
        try {
            const response = await fetch(`${this.wpApiUrl}destinations?_embed&per_page=10`);
            const destinations = await response.json();
            
            const container = document.querySelector('.destinations-grid');
            if (container && destinations.length > 0) {
                container.innerHTML = '';
                
                destinations.forEach(dest => {
                    const destCard = this.createDestinationCard(dest);
                    container.appendChild(destCard);
                });
            }
        } catch (error) {
            console.log('Using static content - WordPress not connected:', error);
        }
    }

    // Load special events/themed holidays
    async loadSpecialEvents() {
        try {
            const response = await fetch(`${this.wpApiUrl}special_events?_embed&per_page=6`);
            const events = await response.json();
            
            const container = document.querySelector('.themed-grid');
            if (container && events.length > 0) {
                container.innerHTML = '';
                
                events.forEach(event => {
                    const eventCard = this.createEventCard(event);
                    container.appendChild(eventCard);
                });
            }
        } catch (error) {
            console.log('Using static events - WordPress not connected:', error);
        }
    }

    // Create destination card HTML
    createDestinationCard(destination) {
        const card = document.createElement('div');
        card.className = 'destination-card';
        
        const imageUrl = destination._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'assets/images/default-destination.jpg';
        const price = destination.acf?.price || '$199';
        
        card.innerHTML = `
            <img src="${imageUrl}" alt="${destination.title.rendered}" class="destination-image" />
            <div class="destination-info">
                <div class="flex-row justify-between">
                    <span class="destination-location">${destination.title.rendered}</span>
                    <span class="destination-price">${price}</span>
                </div>
                <span class="price-per-person">Per Person</span>
                <p class="destination-description">${this.stripHtml(destination.excerpt.rendered)}</p>
            </div>
        `;
        
        return card;
    }

    // Create special event card HTML
    createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'themed-card';
        
        const imageUrl = event._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'assets/images/default-event.jpg';
        const price = event.acf?.price || '120k';
        const duration = event.acf?.duration || 'EVERY DAY';
        const groupSize = event.acf?.group_size || '3-10 PP';
        
        card.innerHTML = `
            <img src="${imageUrl}" alt="${event.title.rendered}" class="themed-image" />
            <h3 class="themed-card-title">${event.title.rendered}</h3>
            <div class="themed-price-row">
                <span class="price-from">from</span>
                <span class="price-amount">${price}</span>
            </div>
            <div class="themed-details">
                <div class="detail-item">
                    <img src="assets/images/calender.png" alt="Calendar" class="detail-icon" />
                    <span class="detail-text">${duration}</span>
                </div>
                <div class="detail-item">
                    <img src="assets/images/group.png" alt="Group" class="detail-icon" />
                    <span class="detail-text">${groupSize}</span>
                </div>
            </div>
            <p class="themed-description">${this.stripHtml(event.excerpt.rendered)}</p>
        `;
        
        return card;
    }

    // Utility function to strip HTML tags
    stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    // Manual refresh function for admin use
    async refreshContent() {
        await this.init();
        console.log('Content refreshed from WordPress');
    }
}

// Initialize content manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.jetwideContent = new JetwideContentManager();
    
    // Add refresh button for admin (hidden by default)
    const refreshBtn = document.createElement('button');
    refreshBtn.innerHTML = '🔄 Refresh Content';
    refreshBtn.style.cssText = 'position:fixed;top:80px;right:80px;z-index:9999;background:#007cba;color:white;border:none;padding:10px;border-radius:5px;display:none;';
    refreshBtn.onclick = () => window.jetwideContent.refreshContent();
    document.body.appendChild(refreshBtn);
    
    // Show refresh button when user presses Ctrl+Shift+R
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'R') {
            e.preventDefault();
            refreshBtn.style.display = refreshBtn.style.display === 'none' ? 'block' : 'none';
        }
    });
});

// WordPress Custom Fields Configuration
// Add this to your WordPress theme's functions.php:
/*
function jetwide_add_custom_fields() {
    // For Destinations
    add_meta_box(
        'destination_details',
        'Destination Details',
        'destination_meta_callback',
        'destinations'
    );
    
    // For Special Events
    add_meta_box(
        'event_details',
        'Event Details', 
        'event_meta_callback',
        'special_events'
    );
}
add_action('add_meta_boxes', 'jetwide_add_custom_fields');

function destination_meta_callback($post) {
    $price = get_post_meta($post->ID, 'price', true);
    echo '<p><label>Price: <input type="text" name="price" value="' . $price . '" /></label></p>';
}

function event_meta_callback($post) {
    $price = get_post_meta($post->ID, 'price', true);
    $duration = get_post_meta($post->ID, 'duration', true);
    $group_size = get_post_meta($post->ID, 'group_size', true);
    
    echo '<p><label>Price: <input type="text" name="price" value="' . $price . '" /></label></p>';
    echo '<p><label>Duration: <input type="text" name="duration" value="' . $duration . '" /></label></p>';
    echo '<p><label>Group Size: <input type="text" name="group_size" value="' . $group_size . '" /></label></p>';
}

function save_custom_fields($post_id) {
    if (isset($_POST['price'])) update_post_meta($post_id, 'price', $_POST['price']);
    if (isset($_POST['duration'])) update_post_meta($post_id, 'duration', $_POST['duration']);
    if (isset($_POST['group_size'])) update_post_meta($post_id, 'group_size', $_POST['group_size']);
}
add_action('save_post', 'save_custom_fields');
*/