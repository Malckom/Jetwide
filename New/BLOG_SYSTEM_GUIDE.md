# Blog System - Complete Guide

## Overview
The Jetwide Travel & Safari blog system is a complete content management solution with admin panel, API, and dynamic frontend. The design matches professional travel blogs with a sidebar layout for enhanced navigation.

---

## 📁 File Structure

```
Jetwide-web/
├── admin/
│   ├── blog-upload.html    # Admin interface for creating/editing posts
│   └── upload-blog.php     # Backend handler for post uploads
├── api/
│   └── get-blogs.php       # RESTful API for retrieving posts
├── data/
│   └── blog-posts.json     # JSON database for blog posts
├── pages/
│   ├── blogs.html          # Blog listing page
│   └── blog-post.html      # Individual blog post template
└── assets/
    ├── styles.css          # Contains blog post and sidebar styles
    └── images/
        └── blog/           # Uploaded blog images stored here
```

---

## 🎨 Frontend Features

### Blog Listing Page (`pages/blogs.html`)
- Grid layout displaying blog cards
- Each card shows:
  - Featured image
  - Date badge
  - Post title
  - "Read More" link
- Responsive design (3 columns → 2 columns → 1 column)

### Blog Post Page (`pages/blog-post.html`)
- **Hero Section**: Full-width featured image with title overlay
- **Two-Column Layout**:
  - **Main Content Area (70%)**:
    - Post meta bar (category badge, date, author)
    - Full HTML content
    - Social sharing buttons (Facebook, Twitter, LinkedIn, WhatsApp)
  - **Sidebar (30%)**:
    - Search widget
    - Categories list
    - Recent posts with thumbnails
    - Tags cloud
    - Contact CTA widget
- **Related Posts Section**: Below main content
- **Dynamic Loading**: JavaScript fetches post data from JSON using URL parameter

---

## 🔧 Backend System

### Admin Panel (`admin/blog-upload.html`)

**Features**:
- Rich text editor with formatting buttons (Bold, Italic, Headings, Lists, Quotes, Links)
- Real-time character counters
- Image preview before upload
- Draft save functionality (localStorage)
- Form validation

**Form Fields**:
- **Post Title** (required, max 150 chars)
- **Excerpt/Summary** (required, max 300 chars) - Shows on blog listing
- **Featured Image** (required, JPG/PNG/WebP)
- **Category** (required) - Dropdown with 8 categories
- **Publish Date** (required)
- **Author Name** (required, defaults to "Jetwide Travel")
- **Read Time** (optional, minutes)
- **Post Tags** (required, comma-separated) - For sidebar tag cloud
- **Blog Content** (required, supports HTML)
- **SEO Title** (optional, max 60 chars, defaults to post title)
- **SEO Meta Description** (optional, max 160 chars, defaults to excerpt)

**Actions**:
- **Publish Post**: Saves to JSON and uploads image
- **Save as Draft**: Saves to localStorage and optionally to backend
- **Clear Form**: Resets all fields

### Upload Handler (`admin/upload-blog.php`)

**Functionality**:
- Validates all required fields
- Handles image upload to `assets/images/blog/`
- Generates unique post ID and slug
- Converts comma-separated tags to array
- Sanitizes HTML and user input
- Saves to `data/blog-posts.json`
- Returns success response with post URL

**Response Format**:
```json
{
  "success": true,
  "message": "Blog post published successfully",
  "post_id": "post_1697456789_abc123",
  "post_url": "blog-post.html?id=post_1697456789_abc123",
  "data": { /* post object */ }
}
```

### API Endpoint (`api/get-blogs.php`)

**Query Parameters**:
- `id` - Get specific post by ID or slug
- `limit` - Number of posts to return (default: 10)
- `category` - Filter by category
- `status` - Filter by status (default: "published")

**Examples**:
```
GET /api/get-blogs.php                          # Get all posts
GET /api/get-blogs.php?limit=5                  # Get 5 posts
GET /api/get-blogs.php?category=Travel%20Tips   # Get posts by category
GET /api/get-blogs.php?id=visa-guide-kenya-2024 # Get specific post
```

**Response Format**:
```json
{
  "success": true,
  "count": 5,
  "posts": [
    {
      "id": "visa-guide-kenya-2024",
      "title": "Essential Visa Guide for Kenya Travel",
      "slug": "visa-guide-kenya-2024",
      "excerpt": "Planning to visit Kenya?...",
      "content": "<h2>Understanding Kenya Visa...</h2>",
      "featured_image": "assets/images/blog/blog_1697456789.jpg",
      "category": "Travel Tips",
      "tags": ["visa", "kenya", "travel documents"],
      "author": "Jetwide Team",
      "publish_date": "2024-01-15",
      "read_time": 5,
      "status": "published",
      "views": 127,
      "seo_title": "Kenya Visa Guide 2024",
      "seo_description": "Complete guide to Kenya visa...",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ]
}
```

---

## 🎯 Data Structure

### Blog Post Object

```json
{
  "id": "unique-post-id",
  "title": "Post Title",
  "slug": "post-title-url-friendly",
  "excerpt": "Brief summary for listing page",
  "content": "Full HTML content",
  "featured_image": "assets/images/blog/filename.jpg",
  "category": "Travel Tips",
  "tags": ["tag1", "tag2", "tag3"],
  "author": "Author Name",
  "publish_date": "2024-01-15",
  "read_time": 5,
  "status": "published",
  "views": 0,
  "seo_title": "SEO-optimized title",
  "seo_description": "SEO meta description",
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-15 10:30:00"
}
```

---

## 🎨 CSS Classes

### Blog Post Layout
- `.blog-post-layout` - Grid container (main + sidebar)
- `.blog-post-main` - Main content area (70% width)
- `.blog-post-sidebar` - Sidebar (350px width, sticky)

### Sidebar Widgets
- `.sidebar-widget` - Widget container
- `.widget-title` - Widget heading
- `.search-box` - Search input container
- `.category-list` - Category links list
- `.recent-posts-list` - Recent posts container
- `.recent-post-item` - Individual recent post
- `.tags-cloud` - Tag cloud container
- `.tag` - Individual tag button
- `.sidebar-cta` - Call-to-action widget

### Post Meta
- `.post-meta-bar` - Meta information bar
- `.post-category` - Category badge
- `.post-date` - Publish date
- `.post-author` - Author name

---

## 🚀 Usage Guide

### Creating a New Blog Post

1. **Access Admin Panel**: Open `admin/blog-upload.html`
2. **Fill in Post Details**:
   - Enter compelling title
   - Write excerpt (shows on blog listing)
   - Upload featured image (recommended 1200x600px)
   - Select category
   - Set publish date
   - Add 3-8 relevant tags (comma-separated)
3. **Write Content**:
   - Use formatting buttons for HTML tags
   - Add headings (H2, H3) for structure
   - Include bullet lists for readability
   - Add blockquotes for highlights
4. **Optimize for SEO** (optional):
   - Custom SEO title (60 chars max)
   - Meta description (160 chars max)
5. **Publish or Save Draft**:
   - Click "Publish Post" to make live
   - Click "Save as Draft" to continue later

### Linking to Blog Posts

**From Blog Listing**:
```html
<a href="blog-post.html?id=POST_ID">Read More →</a>
```

**Direct URL**:
```
https://yoursite.com/pages/blog-post.html?id=visa-guide-kenya-2024
```

### Updating the Blog Listing

To add new posts to `blogs.html`:

```html
<article class="blog-card">
  <div class="blog-image-wrapper">
    <img src="../assets/images/blog/your-image.jpg" alt="Post Title" />
    <div class="blog-date-badge">
      <span class="date-day">15</span>
      <span class="date-month">JAN</span>
    </div>
  </div>
  <div class="blog-content">
    <h3>Your Post Title</h3>
    <a href="blog-post.html?id=your-post-id" class="blog-read-more">Read More →</a>
  </div>
</article>
```

---

## 🔄 Dynamic Loading (JavaScript)

The blog post page uses JavaScript to dynamically load content:

```javascript
// On page load
const urlParams = new URLSearchParams(window.location.search);
const postId = urlParams.get('id');

if (postId) {
  loadBlogPost(postId);
}

// loadBlogPost function fetches from blog-posts.json
async function loadBlogPost(postId) {
  const response = await fetch('../data/blog-posts.json');
  const posts = await response.json();
  const post = posts.find(p => p.id === postId);
  
  // Update page content
  document.getElementById('post-hero-title').textContent = post.title;
  document.getElementById('post-content').innerHTML = post.content;
  // ... etc
}
```

---

## 📱 Responsive Design

**Desktop (>1024px)**:
- Two-column layout (main + sidebar)
- Sidebar sticky positioned

**Tablet (768px - 1024px)**:
- Two-column layout with narrower sidebar (300px)
- Reduced gaps

**Mobile (<768px)**:
- Single column layout
- Sidebar stacks below content
- Sidebar no longer sticky

---

## 🔐 Security Considerations

1. **Input Sanitization**: All user inputs are sanitized in PHP
2. **File Upload Validation**: Only JPG, PNG, WebP allowed
3. **HTML Content**: Post content allows HTML but is stored securely
4. **Directory Permissions**: Upload directories use 0755 permissions
5. **CORS Headers**: Configured for local/production domains

---

## 🎯 Categories

Current available categories:
- Travel Tips
- Destinations
- Safari Adventures
- Cultural Experiences
- Visa & Travel Guide
- Beach Holidays
- Adventure Sports
- Budget Travel

---

## 📈 Future Enhancements

Potential improvements:
- Comment system
- Post editing interface
- Image gallery support
- Related posts algorithm
- Search functionality (connect search widget)
- Category filtering on blog listing
- Pagination for large blog lists
- Email subscription integration
- Social share counters
- Author profiles
- Post scheduling

---

## 🐛 Troubleshooting

**Images not uploading**:
- Check `assets/images/blog/` directory exists
- Verify write permissions (755)
- Check file size limits in php.ini

**Posts not loading**:
- Verify `data/blog-posts.json` exists
- Check JSON syntax is valid
- Ensure proper file permissions

**Sidebar not showing**:
- Check CSS file includes sidebar styles
- Verify `assets/styles.css` is loaded
- Check browser console for errors

**Categories not matching**:
- Ensure category values in upload form match sidebar exactly
- Update both `blog-upload.html` and `blog-post.html` if adding new categories

---

## 📞 Support

For issues or questions:
- Email: safaris@jetwide.org
- WhatsApp: +254 748 538 311

---

**Last Updated**: October 2025  
**Version**: 1.0
