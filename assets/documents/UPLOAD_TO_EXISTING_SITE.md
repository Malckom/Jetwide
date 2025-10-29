# 🚀 Upload Guide: Adding WordPress CMS to Your Existing Site

## 📍 Your Current Setup
- ✅ Static website already uploaded to `public_html/New/`
- ✅ Website accessible and working
- ✅ Need to add WordPress CMS for content management

---

## 📋 Files to Upload to `public_html/New/`

### **1. Updated index.html File**
- **File:** `C:\Users\USER\Desktop\Jetwide-web\New\index.html` 
- **Action:** Replace your current `index.html` with this updated version
- **What's New:** Added WordPress integration code at the bottom

### **2. Dynamic Content JavaScript**
- **File:** `C:\Users\USER\Desktop\Jetwide-web\dynamic-content.js`
- **Upload to:** `public_html/New/dynamic-content.js`
- **What it does:** Connects your site to WordPress

### **3. WordPress Functions Code**
- **File:** `C:\Users\USER\Desktop\Jetwide-web\wordpress-functions.php`
- **Use:** Copy this code into WordPress later

---

## 🔧 **Step-by-Step Upload Process**

### **Step 1: Update Your Current Site**
1. **cPanel** → **File Manager**
2. Navigate to `public_html/New/`
3. **Backup current index.html** (rename to `index-backup.html`)
4. **Upload new index.html** from `C:\Users\USER\Desktop\Jetwide-web\New\index.html`

### **Step 2: Upload JavaScript File**
1. Stay in `public_html/New/`
2. **Upload** `dynamic-content.js` to this folder
3. **Result:** File should be at `public_html/New/dynamic-content.js`

### **Step 3: Install WordPress in Subfolder**
1. **Create folder:** `public_html/New/wp/`
2. **Download WordPress** from wordpress.org
3. **Extract and upload** all WordPress files to the `wp` folder
4. **Visit:** `yourdomain.com/New/wp/wp-admin/install.php`
5. **Complete WordPress installation**

### **Step 4: Configure WordPress**
1. **Login to:** `yourdomain.com/New/wp/wp-admin/`
2. **Go to:** Appearance → Theme Editor
3. **Select:** `functions.php`
4. **Copy entire content** from `wordpress-functions.php` file
5. **Paste at bottom** of functions.php
6. **Update File**

### **Step 5: Test Everything**
1. **Visit your site:** `yourdomain.com/New/`
2. **Check browser console** for connection messages
3. **Login to WordPress:** `yourdomain.com/New/wp/wp-admin/`
4. **Test:** Create a destination or special event
5. **Refresh your site** to see if content appears

---

## 📁 **Final Folder Structure**

```
public_html/
├── (your existing WordPress site)
└── New/
    ├── index.html (updated with WordPress integration)
    ├── dynamic-content.js (new file)
    ├── assets/
    │   ├── styles.css
    │   └── images/
    └── wp/ (new WordPress installation)
        ├── wp-admin/ (your admin access)
        ├── wp-content/
        ├── wp-includes/
        └── (other WordPress files)
```

---

## 🌐 **Your URLs After Setup**

- **Main Website:** `yourdomain.com/New/`
- **WordPress Admin:** `yourdomain.com/New/wp/wp-admin/`
- **WordPress API:** `yourdomain.com/New/wp/wp-json/wp/v2/destinations`

---

## ✅ **Quick Checklist**

1. ☐ Upload updated `index.html`
2. ☐ Upload `dynamic-content.js`
3. ☐ Install WordPress in `wp/` folder
4. ☐ Add functions code to WordPress
5. ☐ Test content management
6. ☐ Verify dynamic content loading

---

## 🎯 **What You'll Be Able to Do**

### **Content Management:**
- **Login:** `yourdomain.com/New/wp/wp-admin/`
- **Manage Destinations:** Add safari locations with prices
- **Manage Special Events:** Update seasonal offers
- **Manage Tour Packages:** Create travel packages
- **Upload Images:** Manage all photos easily

### **Live Website Updates:**
- ✅ Content updates **automatically** appear on live site
- ✅ No need to edit HTML files manually
- ✅ Real-time price changes
- ✅ Easy image management

---

## 🆘 **Quick Test Commands**

After setup, test these:

### **Test 1: Check JavaScript Loading**
- Visit `yourdomain.com/New/`
- Press `F12` → Console tab
- Should see: `"ℹ️ Using static content"` (before WordPress setup)

### **Test 2: Check WordPress API**
- Visit: `yourdomain.com/New/wp/wp-json/wp/v2/destinations`
- Should see: JSON response (after WordPress setup)

### **Test 3: Manual Content Refresh**
- On your website, press `Ctrl+Shift+U`
- Should see: Alert about content refresh status

---

## 🎉 **Success Indicators**

### **✅ Everything Working When:**
- WordPress admin accessible at `/wp/wp-admin/`
- Can create destinations/events in WordPress  
- Content appears automatically on live site
- Browser console shows "WordPress CMS connected"
- Manual refresh with `Ctrl+Shift+U` works

**Ready to upload? Start with Step 1 and work through each step!**