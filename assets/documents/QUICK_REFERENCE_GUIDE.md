# 🚀 JETWIDE WEBSITE - QUICK REFERENCE GUIDE

## 📍 ESSENTIAL URLS

### Live Website
```
Homepage:       https://jetwide.org/
Admin Panel:    https://jetwide.org/admin/blog-upload.html
WordPress:      https://jetwide.org/wp/wp-admin/
Blog API:       https://jetwide.org/api/get-blogs.php
```

### Development Server
```bash
# Start Email Server
node server.js

# Or use the batch file (Windows)
start-dev-server.bat

# Access locally
http://localhost:3000
```

---

## 🔑 KEY FEATURES AT A GLANCE

### Homepage Features
✅ 5-slide hero carousel with auto-play  
✅ Auto-scrolling destinations  
✅ Special offers grid (4 packages)  
✅ Kenya safaris (4 destinations)  
✅ International carousel (6 destinations)  
✅ Themed holidays slider (3 packages)  
✅ Animated statistics section  
✅ Newsletter subscription  

### Navigation
- **Fixed header** - Auto-hides on scroll down
- **3 dropdown menus** - Tours, Services, About
- **Mobile hamburger** - Full responsive menu
- **WhatsApp button** - Direct messaging

### Pages Available
1. Home (`/index.html`)
2. VISA Services (`/pages/visa-services.html`)
3. Car Hire (`/pages/car-hire.html`)
4. Blogs (`/pages/blogs.html`)
5. About Us (`/pages/about-us.html`)
6. Contact (`/pages/contact.html`)
7. Kenyan Safaris (`/pages/kenyan-safaris.html`)
8. Beach Getaways (`/pages/beach-getaways.html`)
9. Themed Packages (`/pages/themed-packages.html`)
10. International Destinations (`/pages/international-destinations.html`)
11. Job Placement (`/pages/job-placement.html`)
12. Airline Services (`/pages/airline-airport-services.html`)

---

## 📞 CONTACT INFORMATION

### Business
```
Company:  Jetwide Travel & Safari Consortium
Location: Westlands Square, 2nd Floor, Nairobi
Phone:    +254 748 538 311 | +254 700 368 676 | +254 714 534 677
Email:    safaris@jetwide.org | info@jetwide.org
WhatsApp: +254 748 538 311
Hours:    Monday - Friday, 9 AM - 5 PM
```

### Social Media
- Facebook: @jetwideconsortium
- Instagram: @jetwidesafaris
- Twitter/X: @JetWideC
- LinkedIn: /company/jetwideconsortium
- YouTube: @JetwideConsortium
- Pinterest: @jetwide

---

## 🎨 COLOR PALETTE

```css
Primary Navy:    #14132A
Primary Gold:    #D4AF37 (212, 175, 55)
Accent Orange:   #FE9900 (254, 153, 0)
Accent Lime:     #ABFF35 (171, 255, 53)
Dark Text:       #333333
Light Text:      #666666
Background:      #F8F9FA
White:           #FFFFFF
```

---

## 📁 CRITICAL FILE LOCATIONS

### Core Files
```
Main Homepage:    /index.html
Master CSS:       /assets/styles.css (12,084 lines)
Dynamic JS:       /dynamic-content.js
Email Server:     /server.js
Environment:      /.env (create this!)
```

### Images
```
Hero Slides:      /assets/images/Hero-Section/
Special Offers:   /assets/images/Special-offers/
Themed Holidays:  /assets/images/Themed-Holidays_&_Corporate-Packages/
International:    /assets/images/International-Packages/
VISA Page:        /assets/images/Visa-Page/
Car Hire:         /assets/images/Car-Hire-Page/
Blog Images:      /assets/images/blog/
```

### Backend
```
Admin Panel:      /admin/blog-upload.html
Blog Upload PHP:  /admin/upload-blog.php
Blog API:         /api/get-blogs.php
Blog Data:        /data/blog-posts.json
Contact Handler:  /send-contact-simple.php
Car Enquiry:      /send-car-hire-enquiry.php
VISA Enquiry:     /send-visa-application.php
```

### WordPress
```
Installation:     /wp/ (or /New/wp/)
Admin:            /wp/wp-admin/
Functions:        /wordpress-functions.php
VISA Functions:   /visa-functions-addon.php
```

---

## ⚙️ CONFIGURATION

### Email Server Setup
Create `.env` file in root:
```env
EMAIL_USER=your-gmail@gmail.com
EMAIL_PASS=your-app-password
PORT=3000
```

### Node.js Dependencies
```bash
npm install express nodemailer cors dotenv body-parser
```

### Required Permissions
```bash
chmod 755 admin/
chmod 755 api/
chmod 777 data/
chmod 777 assets/images/blog/
```

---

## 🎯 COMMON TASKS

### Add a New Blog Post
1. Go to `/admin/blog-upload.html`
2. Fill in all fields
3. Upload featured image
4. Click "Publish Post"
5. Blog appears on `/pages/blogs.html`

### Update Special Offers
1. Edit `/index.html`
2. Find `.specials-grid` section
3. Update image, title, price, description
4. Save and upload

### Change Contact Email
1. Edit `/server.js`
2. Find `mailOptions` object
3. Change `to:` email address
4. Restart server

### Add New VISA Country
1. Open `/pages/visa-services.html`
2. Duplicate a `.visa-card` section
3. Update country name, image, requirements
4. Save and upload

### Update Pricing
**Kenya Safaris:**
- Edit `index.html` → `.destination-price`

**International Packages:**
- Edit individual package HTML files in `/pages/packages/`

**Special Offers:**
- Edit `index.html` → `.price-highlight`

---

## 🐛 TROUBLESHOOTING

### Email Not Sending
✅ Check `.env` file exists  
✅ Verify EMAIL_USER and EMAIL_PASS  
✅ Check Node.js server is running  
✅ Test with: `curl -X POST http://localhost:3000/send-contact-form`  

### Blog Not Displaying
✅ Check `/data/blog-posts.json` exists  
✅ Verify file permissions (777)  
✅ Check PHP errors in browser console  
✅ Test API: `/api/get-blogs.php`  

### Images Not Loading
✅ Verify file paths (case-sensitive on Linux)  
✅ Check image file extensions  
✅ Test image URLs directly  
✅ Check permissions (644 for files)  

### Hero Slideshow Not Working
✅ Check JavaScript console for errors  
✅ Verify all 5 images exist  
✅ Check `dynamic-content.js` is loaded  
✅ Test on different browsers  

### WordPress Content Not Appearing
✅ Check WordPress is installed  
✅ Verify API endpoint: `/wp/wp-json/wp/v2/`  
✅ Check `wordpress-functions.php` loaded  
✅ Test with admin refresh: Ctrl+Shift+R  

---

## 📊 STATISTICS

### Current Content
- **Blog Posts:** 1 (VISA Guide)
- **Safari Destinations:** 4
- **International Packages:** 6
- **Special Offers:** 4
- **Themed Holidays:** 3
- **VISA Countries:** 8
- **Service Pages:** 6

### Performance Targets
- **Page Load:** < 3 seconds
- **First Paint:** < 1.5 seconds
- **Mobile Score:** 85+
- **Desktop Score:** 90+

---

## 🔒 SECURITY CHECKLIST

### Before Going Live
☐ Create strong `.env` passwords  
☐ Configure `.htaccess` for admin folder  
☐ Install SSL certificate  
☐ Enable HTTPS redirect  
☐ Set proper file permissions  
☐ Change default WordPress admin  
☐ Install security plugins  
☐ Enable database backups  
☐ Configure email SPF/DKIM  
☐ Test all forms  

---

## 🚀 DEPLOYMENT CHECKLIST

### Pre-Launch
☐ Test all pages on mobile  
☐ Test all forms submit correctly  
☐ Verify all images load  
☐ Check all links work  
☐ Test email delivery  
☐ Verify contact information  
☐ Check SEO meta tags  
☐ Test site speed  
☐ Browser compatibility check  
☐ Backup current site  

### Launch
☐ Upload files via cPanel/FTP  
☐ Configure `.env` file  
☐ Set directory permissions  
☐ Install Node.js dependencies  
☐ Start Node.js server (if not using hosting)  
☐ Configure WordPress database  
☐ Test live site  
☐ Update DNS if needed  
☐ Configure SSL  
☐ Submit to search engines  

### Post-Launch
☐ Monitor error logs  
☐ Check analytics installation  
☐ Test contact forms  
☐ Verify email delivery  
☐ Check mobile performance  
☐ Monitor uptime  
☐ Get user feedback  

---

## 💡 PRO TIPS

### Performance
- Optimize images before upload (max 200KB)
- Use WebP format for modern browsers
- Enable Gzip compression
- Leverage browser caching
- Minify CSS/JS for production

### SEO
- Update meta descriptions regularly
- Add new blog posts monthly
- Build internal links
- Get backlinks from travel sites
- Submit XML sitemap

### Content Updates
- Refresh special offers seasonally
- Update prices quarterly
- Add new destinations yearly
- Keep blog content current
- Update photos annually

### Maintenance
- Backup weekly (minimum)
- Update WordPress monthly
- Check forms weekly
- Monitor uptime daily
- Review analytics monthly

---

## 📞 SUPPORT RESOURCES

### Documentation Files
- `WEBSITE_COMPLETE_DOCUMENTATION.md` - Full documentation
- `WORDPRESS_SETUP_COMPLETE.md` - WordPress guide
- `BLOG_SYSTEM_DOCUMENTATION.md` - Blog system guide
- `DEPLOYMENT_GUIDE.md` - Deployment instructions

### Helpful Commands
```bash
# Start development server
npm start

# Install dependencies
npm install

# Check Node.js version
node --version

# Check PHP version
php --version

# Test email server
curl -X POST http://localhost:3000/send-contact-form
```

### Quick Fixes
```bash
# Fix file permissions
chmod -R 755 .
chmod -R 777 data/
chmod -R 777 assets/images/blog/

# Restart Node.js server
# Press Ctrl+C to stop, then:
node server.js

# Clear browser cache
Ctrl+Shift+R (Windows/Linux)
Cmd+Shift+R (Mac)
```

---

## 📋 SERVICES OFFERED

### Tours & Safaris
1. Maasai Mara Safaris
2. Amboseli National Park
3. Lake Nakuru
4. Mt. Kenya Treks
5. Beach Getaways (Mombasa, Diani)
6. International Destinations

### VISA Processing
1. United Kingdom
2. Schengen (Europe)
3. United States
4. Canada
5. UAE (Dubai)
6. Australia
7. Turkey
8. China

### Other Services
1. Car Hire & Rentals
2. Job Placement (International)
3. Airline Bookings
4. Airport Transfers
5. Travel Insurance
6. Passport Services

---

## 🎓 LEARNING RESOURCES

### HTML/CSS/JavaScript
- MDN Web Docs: developer.mozilla.org
- W3Schools: w3schools.com
- CSS-Tricks: css-tricks.com

### Node.js
- Official Docs: nodejs.org
- Nodemailer Docs: nodemailer.com
- Express.js: expressjs.com

### WordPress
- WordPress Codex: codex.wordpress.org
- REST API: developer.wordpress.org/rest-api
- WP Tutorials: wordpress.org/support

### PHP
- PHP Manual: php.net/manual
- PHP Tutorial: w3schools.com/php
- Composer: getcomposer.org

---

## ✅ QUALITY CHECKLIST

### Before Each Update
☐ Backup current version  
☐ Test in development first  
☐ Check mobile responsiveness  
☐ Verify all links work  
☐ Test forms submit  
☐ Check browser console  
☐ Validate HTML/CSS  
☐ Test on multiple browsers  
☐ Check loading speed  
☐ Review content accuracy  

---

## 🎉 SUCCESS METRICS

### Traffic Goals
- **Monthly Visitors:** 5,000+
- **Page Views:** 15,000+
- **Bounce Rate:** <50%
- **Avg. Session:** >3 minutes

### Conversion Goals
- **Contact Forms:** 100+ monthly
- **WhatsApp Messages:** 50+ monthly
- **Phone Calls:** 30+ monthly
- **Email Inquiries:** 40+ monthly

### Content Goals
- **Blog Posts:** 2 per month
- **New Packages:** 1 per quarter
- **Updated Offers:** Monthly
- **Social Posts:** 3 per week

---

**Remember:** This is your quick reference. For detailed information, see `WEBSITE_COMPLETE_DOCUMENTATION.md`

**Need Help?** Contact the development team or refer to the full documentation.

---

*Last Updated: October 22, 2025*  
*Jetwide Travel & Safari Website*  
*Powered By Malckom*
