# Jetwide Blog System Documentation

## Overview
Complete blog management system with frontend display, individual post pages, and backend admin panel for uploading/managing blog posts.

## 📁 File Structure

```
Jetwide-web/
├── pages/
│   ├── blogs.html           # Main blog listing page
│   └── blog-post.html       # Individual blog post template
├── admin/
│   ├── blog-upload.html     # Admin form to upload blog posts
│   └── upload-blog.php      # Backend handler for blog uploads
├── api/
│   └── get-blogs.php        # API to retrieve blog posts
├── data/
│   └── blog-posts.json      # Blog posts database (created automatically)
└── assets/
    └── images/
        └── blog/            # Blog images directory (created automatically)
```

## 🚀 Features

### Blog Listing Page (blogs.html)
- Displays all published blog posts in a grid layout
- Shows featured image, title, excerpt, and publish date
- Responsive design for mobile/tablet/desktop
- Links to individual blog post pages

### Individual Blog Post Page (blog-post.html)
- Full blog post content display
- Hero section with breadcrumbs
- Featured image
- Author, date, and read time metadata
- Social sharing buttons (Facebook, Twitter, LinkedIn, WhatsApp)
- Related posts section
- Responsive and SEO-friendly

### Admin Upload Form (admin/blog-upload.html)
- User-friendly interface for creating blog posts
- Rich text editor with formatting toolbar
- Image upload with preview
- Category selection
- SEO metadata fields
- Character counters for title and excerpt
- Draft save functionality
- Form validation

### Backend API
- **upload-blog.php**: Handles blog post uploads, image storage, and data management
- **get-blogs.php**: RESTful API to retrieve blog posts with filtering options

## 🛠️ Setup Instructions

### 1. Server Requirements
- PHP 7.0 or higher
- Apache/Nginx web server
- Write permissions for data and assets directories

### 2. Installation Steps

1. **Upload files to your server**
   ```
   Upload all files maintaining the directory structure
   ```

2. **Set directory permissions**
   ```bash
   chmod 755 admin/
   chmod 755 api/
   chmod 777 data/
   chmod 777 assets/images/blog/
   ```

3. **Verify PHP configuration**
   - Ensure `file_uploads = On` in php.ini
   - Check `upload_max_filesize` (recommended: 10MB minimum)
   - Check `post_max_size` (recommended: 12MB minimum)

### 3. Access URLs

- **Blog Listing**: `https://yourdomain.com/pages/blogs.html`
- **Admin Panel**: `https://yourdomain.com/admin/blog-upload.html`
- **Individual Post**: `https://yourdomain.com/pages/blog-post.html?id=POST_ID`

## 📝 How to Use

### Creating a New Blog Post

1. Navigate to `/admin/blog-upload.html`
2. Fill in all required fields:
   - **Post Title**: Engaging headline (max 150 characters)
   - **Excerpt**: Brief summary shown on blog listing (max 300 characters)
   - **Featured Image**: Upload high-quality image (1200 x 600px recommended)
   - **Category**: Select appropriate category
   - **Publish Date**: Set publication date
   - **Author Name**: Author attribution
   - **Read Time**: Estimated minutes to read
   - **Content**: Full blog post with HTML formatting
   - **SEO Keywords**: Comma-separated keywords

3. Use the formatting toolbar to add:
   - **Bold** and *italic* text
   - Headings (H2, H3)
   - Bullet lists
   - Blockquotes
   - Links

4. Click "Publish Post" or "Save as Draft"

### Managing Blog Posts

#### Retrieving Posts via API

```javascript
// Get all published posts
fetch('/api/get-blogs.php')
  .then(response => response.json())
  .then(data => console.log(data.posts));

// Get specific post by ID
fetch('/api/get-blogs.php?id=POST_ID')
  .then(response => response.json())
  .then(data => console.log(data.post));

// Get posts by category
fetch('/api/get-blogs.php?category=safari-adventures')
  .then(response => response.json())
  .then(data => console.log(data.posts));

// Limit number of posts
fetch('/api/get-blogs.php?limit=5')
  .then(response => response.json())
  .then(data => console.log(data.posts));
```

#### Data Structure

Blog posts are stored in JSON format in `/data/blog-posts.json`:

```json
{
  "id": "post_1234567890_abc123",
  "title": "Amazing Safari Adventure in Kenya",
  "slug": "amazing-safari-adventure-in-kenya",
  "excerpt": "Discover the thrill of wildlife...",
  "content": "<p>Full HTML content...</p>",
  "category": "safari-adventures",
  "author": "Jetwide Travel",
  "publish_date": "2025-10-15",
  "read_time": 5,
  "featured_image": "assets/images/blog/blog_1234567890_abc123.jpg",
  "meta_tags": "safari, kenya, wildlife",
  "status": "published",
  "views": 0,
  "created_at": "2025-10-15 14:30:00",
  "updated_at": "2025-10-15 14:30:00"
}
```

## 🎨 Customization

### Styling
All blog styles are in `/assets/styles.css` under the "BLOG POST PAGE STYLES" section.

Key classes:
- `.blog-post-hero`: Hero section styling
- `.blog-post-content`: Main content area
- `.blog-post-share`: Social sharing section
- `.related-posts`: Related posts grid

### Categories
Edit categories in `/admin/blog-upload.html` line ~180:
```html
<select id="postCategory" name="category" class="form-control" required>
  <option value="your-category">Your Category</option>
</select>
```

## 🔒 Security Considerations

1. **Protect Admin Panel**: Add authentication to `/admin/` directory
   ```apache
   # .htaccess in /admin/
   AuthType Basic
   AuthName "Admin Area"
   AuthUserFile /path/to/.htpasswd
   Require valid-user
   ```

2. **File Upload Validation**: Configured to accept only JPG, PNG, WebP images

3. **XSS Protection**: HTML content is sanitized on upload

4. **Input Validation**: All required fields are validated client and server-side

## 🔧 Troubleshooting

### Blog posts not saving
- Check `data/` directory permissions (should be 777)
- Verify PHP error logs
- Ensure `blog-posts.json` is writable

### Images not uploading
- Check `assets/images/blog/` permissions (should be 777)
- Verify `upload_max_filesize` in php.ini
- Check image file type (only JPG, PNG, WebP allowed)

### Blog posts not displaying
- Ensure `get-blogs.php` is accessible
- Check browser console for JavaScript errors
- Verify JSON file structure is valid

## 📱 Mobile Responsiveness

All pages are fully responsive:
- **Desktop**: Full grid layout with 3 columns
- **Tablet**: 2-column grid
- **Mobile**: Single column with optimized spacing

## 🌐 SEO Features

- Meta title and description tags
- Semantic HTML structure
- Schema.org Article markup ready
- Social media Open Graph tags
- URL-friendly slugs
- Keyword tagging

## 🚀 Future Enhancements

Potential additions:
- Comments system
- Search functionality
- Tags/filtering
- Author profiles
- Post editing interface
- Image gallery support
- Analytics integration
- RSS feed generation

## 📞 Support

For questions or issues, contact the development team.

---

**Version**: 1.0  
**Last Updated**: October 16, 2025  
**Powered By**: Malckom
