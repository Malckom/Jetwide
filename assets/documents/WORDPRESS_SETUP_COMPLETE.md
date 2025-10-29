# 🚀 Complete Setup Guide: WordPress Admin for Jetwide Website

## 🎯 What You'll Achieve
- Keep your beautiful static website at `jetwide.org/new/`
- Add WordPress admin panel at `jetwide.org/new/wp-admin/`
- Easily update tour packages, destinations, and special events
- Content automatically appears on your live website

---

## 📋 Step 1: Install WordPress

### 1.1 Create WordPress Folder
1. **cPanel** → **File Manager**
2. Navigate to `jetwide.org/new/`
3. **Create New Folder:** `wp`

### 1.2 Download & Upload WordPress
1. **Download WordPress** from [wordpress.org/download](https://wordpress.org/download)
2. **Extract** the zip file on your computer
3. **Upload all WordPress files** to `jetwide.org/new/wp/` folder

### 1.3 Run WordPress Installation
1. **Visit:** `jetwide.org/new/wp/wp-admin/install.php`
2. **Follow installation wizard:**
   - Database Name: `your_database_name`
   - Database User: `your_db_user` 
   - Database Password: `your_db_password`
   - Database Host: `localhost` (usually)
3. **Create admin account:**
   - Username: `admin`
   - Password: `strong_password_here`
   - Email: `your_email@domain.com`

---

## 📋 Step 2: Configure WordPress for Jetwide

### 2.1 Add Custom Post Types
1. **Login to WordPress:** `jetwide.org/new/wp/wp-admin/`
2. **Appearance** → **Theme Editor**
3. **Select:** `functions.php`
4. **Copy and paste** the entire content from `wordpress-functions.php` file
5. **Click:** Update File

### 2.2 Install Required Plugin (Optional)
For easier management, install **Custom Fields Suite** plugin:
1. **Plugins** → **Add New**
2. **Search:** "Custom Fields Suite"
3. **Install & Activate**

---

## 📋 Step 3: Add Dynamic Content to Your Website

### 3.1 Update Your index-ready.html
Add this before the closing `</body>` tag:

```html
<!-- Dynamic Content Manager -->
<script src="dynamic-content.js"></script>
<script>
// Initialize WordPress content loading
document.addEventListener('DOMContentLoaded', function() {
    const contentManager = new JetwideContentManager();
    
    // Auto-refresh content every 5 minutes
    setInterval(() => {
        contentManager.refreshContent();
    }, 300000);
});
</script>
```

### 3.2 Upload dynamic-content.js
1. **Upload** `dynamic-content.js` to `jetwide.org/new/`
2. **Update** your `index.html` to include the script

---

## 📋 Step 4: Create Your First Content

### 4.1 Add a Destination
1. **WordPress Admin** → **Destinations** → **Add New**
2. **Title:** "Maasai Mara Safari"
3. **Content:** Detailed description
4. **Featured Image:** Upload destination photo
5. **Destination Details:**
   - Price: $299
   - Duration: 3 Days
   - Difficulty: Easy
6. **Publish**

### 4.2 Add a Special Event
1. **WordPress Admin** → **Special Events** → **Add New**
2. **Title:** "Christmas Holiday Special"
3. **Content:** Event description
4. **Featured Image:** Upload event photo
5. **Event Details:**
   - Price: 150k
   - Duration: DECEMBER
   - Group Size: 5-20 PP
   - Event Date: 2025-12-25
6. **Publish**

### 4.3 Add a Tour Package
1. **WordPress Admin** → **Tour Packages** → **Add New**
2. **Title:** "Kenya Highlights Tour"
3. **Content:** Package description
4. **Featured Image:** Upload package photo
5. **Package Details:**
   - Price: $799
   - Duration: 7 Days 6 Nights
   - Includes: Accommodation, meals, transport, guide
   - Excludes: International flights, personal expenses
6. **Publish**

---

## 📋 Step 5: Test Everything

### 5.1 Check WordPress Admin
- **Visit:** `jetwide.org/new/wp/wp-admin/`
- **Verify:** You can create/edit content
- **Check:** All custom fields appear

### 5.2 Check Live Website
- **Visit:** `jetwide.org/new/`
- **Verify:** New content appears automatically
- **Test:** Content updates in real-time

### 5.3 Test Content Management
1. **Edit** a destination in WordPress
2. **Change** the price
3. **Update** the post
4. **Refresh** your live website
5. **Verify:** Price updated automatically

---

## 🎯 Admin Panel Features

### What You Can Manage:
✅ **Destinations:** Popular safari locations
✅ **Special Events:** Seasonal offers and themed holidays
✅ **Tour Packages:** Complete travel packages
✅ **Images:** Upload and manage all photos
✅ **Content:** Rich text descriptions
✅ **Pricing:** Easy price updates
✅ **Scheduling:** Event dates and durations

### WordPress Admin Interface:
- **Dashboard:** Overview of all content
- **Destinations:** Manage safari locations
- **Special Events:** Manage holiday offers  
- **Tour Packages:** Manage complete packages
- **Media Library:** Manage all images
- **Users:** Manage admin access

---

## 🔧 Troubleshooting

### Content Not Appearing?
1. **Check:** WordPress is installed correctly
2. **Verify:** Custom post types are active
3. **Test:** API endpoint: `jetwide.org/new/wp/wp-json/wp/v2/destinations`
4. **Check:** Browser console for errors

### Images Not Loading?
1. **Upload** images via WordPress Media Library
2. **Set** as Featured Image for posts
3. **Check** image URLs in WordPress

### Admin Access Issues?
1. **Reset password** via email
2. **Check** database connection
3. **Verify** correct admin URL

---

## 📞 Support

### File Structure After Setup:
```
jetwide.org/new/
├── index.html (your main website)
├── assets/ (CSS and images)
├── dynamic-content.js (content loader)
└── wp/ (WordPress installation)
    ├── wp-admin/ (admin access)
    ├── wp-content/
    └── (other WordPress files)
```

### Admin URLs:
- **Main Website:** `jetwide.org/new/`
- **WordPress Admin:** `jetwide.org/new/wp/wp-admin/`
- **API Endpoint:** `jetwide.org/new/wp/wp-json/wp/v2/`

---

## 🎉 Success!

Once set up, you'll have:
- ✅ **Beautiful static website** (fast loading)
- ✅ **WordPress admin panel** (easy content management)
- ✅ **Real-time updates** (content syncs automatically)
- ✅ **Professional CMS** (manage everything easily)
- ✅ **SEO ready** (WordPress handles metadata)
- ✅ **Scalable solution** (add more content types easily)

**You can now manage all your tour content through the familiar WordPress interface while keeping your beautiful custom design!**