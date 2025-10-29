# 🚀 WordPress Deployment Guide for Jetwide Website

## 📋 Pre-Deployment Checklist
- ✅ Backup your current WordPress site
- ✅ Access to cPanel File Manager
- ✅ All files ready in Jetwide-web folder

---

## 🎯 Option 1: Complete WordPress Replacement (Recommended)

### Step 1: Backup Current Site
1. Login to **cPanel**
2. Open **File Manager**
3. Navigate to `public_html` folder
4. Select all files → **Compress** → Download backup

### Step 2: Clean Installation
1. **Delete all WordPress files** in `public_html` folder
   - wp-admin, wp-content, wp-includes folders
   - All .php files (wp-config.php, index.php, etc.)
   - .htaccess file
   - Keep ONLY: error_logs, cgi-bin (if present)

### Step 3: Upload Your Files
**Upload these files to `public_html`:**
```
public_html/
├── index.html (renamed from homepage.html)
├── assets/
│   ├── styles.css
│   └── images/
│       ├── logo_small.webp
│       ├── calender.png
│       ├── Girrafe.jpg
│       └── (all other images)
└── (any other files)
```

### Step 4: File Modifications Needed
**Before uploading, you need to fix image paths in homepage.html:**

1. **Open homepage.html in text editor**
2. **Find and Replace ALL instances of:**
   - `../assets/` → `assets/`
   - This fixes paths for web server deployment

3. **Rename homepage.html to index.html**

---

## 🎯 Option 2: Subdomain Approach (Keep WordPress)

### Create Subdomain
1. **cPanel** → **Subdomains** 
2. Create: `jetwide.yourdomain.com`
3. Upload files to the subdomain folder
4. Access via: `https://jetwide.yourdomain.com`

---

## 🎯 Option 3: Subfolder Approach (Keep WordPress)

### Create Subfolder
1. **cPanel** → **File Manager**
2. Create folder: `public_html/jetwide/`
3. Upload all files there
4. Access via: `https://yourdomain.com/jetwide/`

---

## 🛠️ Step-by-Step cPanel Upload Process

### 1. Access File Manager
- Login to **cPanel**
- Click **File Manager**
- Navigate to `public_html`

### 2. Upload Method 1: Zip Upload
1. **Compress your assets folder** into a zip file
2. **Upload index.html** directly
3. **Upload assets.zip** → **Extract**

### 3. Upload Method 2: Direct Upload
1. **Create assets folder** in public_html
2. **Upload styles.css** to assets/
3. **Create images folder** in assets/
4. **Upload all images** to assets/images/

---

## 🔧 Required File Modifications

### Fix CSS Path in HTML
**Find this line in homepage.html:**
```html
document.write('<link rel="stylesheet" href="../assets/styles.css?v=' + cssTimestamp + '&nocache=' + randomId + '&update=' + Date.now() + '">');
```

**Replace with:**
```html
document.write('<link rel="stylesheet" href="assets/styles.css?v=' + cssTimestamp + '&nocache=' + randomId + '&update=' + Date.now() + '">');
```

### Fix All Image Paths
**Replace ALL instances of `../assets/` with `assets/`**

---

## ✅ Post-Deployment Checklist

### 1. Test Website
- Visit your domain
- Check all images load
- Test responsive design (mobile/tablet)
- Verify all sections work

### 2. DNS Propagation (if new domain)
- May take 24-48 hours
- Use online DNS checker tools

### 3. SSL Certificate
- Most hosts auto-install SSL
- Check `https://` works

---

## 🆘 Troubleshooting

### Images Not Loading?
- Check file paths in HTML
- Verify case sensitivity (Linux servers)
- Ensure all images uploaded

### CSS Not Applied?
- Check styles.css path in HTML
- Verify file uploaded correctly

### Site Not Loading?
- Check index.html filename
- Verify proper file structure

---

## 📞 Need Help?

If you encounter issues:
1. **Check file permissions** (755 for folders, 644 for files)
2. **Review error logs** in cPanel
3. **Contact your hosting provider** for support

---

## 🎉 Final Result
Your beautiful Jetwide Travel & Safari website will be live at:
- **Main domain:** `https://yourdomain.com`
- **Subdomain:** `https://jetwide.yourdomain.com`
- **Subfolder:** `https://yourdomain.com/jetwide/`

The site will feature:
- ✅ Responsive design
- ✅ Auto-scrolling destinations
- ✅ Golden statistics section
- ✅ Modern footer with newsletter
- ✅ All your beautiful images and content