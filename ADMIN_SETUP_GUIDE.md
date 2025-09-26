# 🔧 Admin Panel Setup Guide for Jetwide Website

## 🎯 Option 1: WordPress Headless CMS (Recommended)

### What This Does:
- Keep your beautiful static website at `jetwide.org/new/`
- Add WordPress at `jetwide.org/new/wp-admin/` for content management
- Pull data dynamically from WordPress to update your site

### Step 1: Install WordPress
1. **Create subfolder:** `jetwide.org/new/wp/`
2. **Download WordPress** from wordpress.org
3. **Upload WordPress files** to the `wp/` folder
4. **Run WordPress installer:** `jetwide.org/new/wp/wp-admin/install.php`
5. **Complete setup** with database details

### Step 2: Configure Custom Post Types
Add this to your WordPress `functions.php`:

```php
// Custom Post Types for Jetwide
function jetwide_custom_post_types() {
    // Tour Packages
    register_post_type('tour_packages', array(
        'labels' => array(
            'name' => 'Tour Packages',
            'singular_name' => 'Tour Package'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true, // Enable REST API
    ));
    
    // Special Events
    register_post_type('special_events', array(
        'labels' => array(
            'name' => 'Special Events',
            'singular_name' => 'Special Event'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
    
    // Destinations
    register_post_type('destinations', array(
        'labels' => array(
            'name' => 'Destinations',
            'singular_name' => 'Destination'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'jetwide_custom_post_types');

// Enable CORS for API access
function jetwide_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}
add_action('rest_api_init', 'jetwide_cors_headers');
```

### Step 3: Add JavaScript to Fetch Content
Add this to your `index-ready.html`:

```javascript
// Fetch Tour Packages from WordPress
async function loadTourPackages() {
    try {
        const response = await fetch('wp/wp-json/wp/v2/tour_packages?_embed');
        const packages = await response.json();
        
        const container = document.querySelector('.destinations-grid');
        container.innerHTML = ''; // Clear existing content
        
        packages.forEach(package => {
            const packageHTML = `
                <div class="destination-card">
                    <img src="${package._embedded['wp:featuredmedia'][0].source_url}" 
                         alt="${package.title.rendered}" class="destination-image" />
                    <div class="destination-info">
                        <div class="flex-row justify-between">
                            <span class="destination-location">${package.title.rendered}</span>
                            <span class="destination-price">$${package.acf?.price || '199'}</span>
                        </div>
                        <span class="price-per-person">Per Person</span>
                        <p class="destination-description">${package.excerpt.rendered}</p>
                    </div>
                </div>
            `;
            container.innerHTML += packageHTML;
        });
    } catch (error) {
        console.error('Error loading tour packages:', error);
    }
}

// Load content when page loads
document.addEventListener('DOMContentLoaded', loadTourPackages);
```

---

## 🎯 Option 2: Simple Custom Admin Panel

Create a lightweight admin panel without WordPress.

### Step 1: Create Admin Files
**File:** `jetwide.org/new/admin/index.php`

```php
<?php
session_start();

// Simple authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'jetwide2025') {
        $_SESSION['admin_logged_in'] = true;
    } else {
        // Show login form
        include 'login.php';
        exit;
    }
}

// Admin panel content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jetwide Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Jetwide Content Management</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>Tour Packages</h3>
                <form action="update_content.php" method="POST">
                    <div class="mb-3">
                        <label>Package Name:</label>
                        <input type="text" name="package_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Price:</label>
                        <input type="text" name="package_price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description:</label>
                        <textarea name="package_description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Package</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <h3>Special Events</h3>
                <form action="update_events.php" method="POST">
                    <div class="mb-3">
                        <label>Event Title:</label>
                        <input type="text" name="event_title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Event Date:</label>
                        <input type="date" name="event_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Description:</label>
                        <textarea name="event_description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
```

### Step 2: Create Data Storage
**File:** `jetwide.org/new/data/content.json`

```json
{
    "tour_packages": [
        {
            "id": 1,
            "name": "Maasai Mara Safari",
            "price": "$199",
            "description": "Experience the Great Migration",
            "image": "assets/images/Angama-Mara.png"
        }
    ],
    "special_events": [
        {
            "id": 1,
            "title": "Romantic Holidays",
            "date": "2025-12-25",
            "description": "Perfect getaway for couples",
            "price": "120k"
        }
    ]
}
```

---

## 🎯 Option 3: Firebase Integration (Modern Approach)

Use Google Firebase for real-time content management.

### Setup Steps:
1. **Create Firebase project**
2. **Add Firestore database**
3. **Create admin interface**
4. **Connect to your website**

---

## 📋 Recommendation

**For your needs, I recommend Option 1 (WordPress Headless CMS)** because:

✅ **Familiar Interface** - WordPress admin you already know
✅ **Powerful Features** - Image uploads, SEO, users management  
✅ **Keep Your Design** - Your beautiful site stays unchanged
✅ **Easy Updates** - Content updates automatically
✅ **wp-admin Access** - Exactly what you requested

## 🚀 Next Steps

1. **Choose your preferred option**
2. **I'll help you set up the specific solution**
3. **Create the admin interface**
4. **Connect it to your beautiful website**

**Which option interests you most? I'll provide detailed setup instructions for your chosen approach!**