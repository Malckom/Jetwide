# Blog System - Complete User Guide

## 📝 How to Upload and View Blog Posts

### Step 1: Upload a Blog Post

1. **Navigate to the admin panel:**
   - URL: `yourdomain.com/admin/blog-upload.html`
   - Or locally: `file:///C:/Users/USER/Desktop/Jetwide-web/admin/blog-upload.html`

2. **Fill in all required fields** (marked with red *):
   - **Post Title:** Your blog post headline
   - **Excerpt/Summary:** Brief description (appears on blog listing)
   - **Featured Image:** Upload image (JPG, PNG, WebP recommended: 1200x600px)
   - **Category:** Select from dropdown
   - **Publish Date:** Choose publication date
   - **Author Name:** Default is "Jetwide Travel"
   - **Blog Content:** Write your full article (can use HTML tags)
   - **Tags:** Enter comma-separated tags (e.g., "visa, kenya, travel")

3. **Optional fields:**
   - Read Time (defaults to 5 minutes)
   - SEO Title (uses post title if blank)
   - SEO Meta Description (uses excerpt if blank)

4. **Click "Publish Post"** button

5. **Success!** You'll see a green message and option to view the post

### Step 2: View Your Post on the Blog Page

**Immediately after upload, your post will appear on:**
- Blog listing page: `yourdomain.com/pages/blogs.html`
- Individual post page: `yourdomain.com/pages/blog-post.html?id=YOUR_POST_ID`

**The blog page will automatically:**
- ✅ Load all posts from `data/blog-posts.json`
- ✅ Display newest posts first
- ✅ Show 3 posts per row
- ✅ Include featured image
- ✅ Display publication date
- ✅ Show post title
- ✅ Add "Read More" link to full post

### How It Works (Behind the Scenes)

#### 1. Upload Process
```
Blog Form → upload-blog.php → Saves to:
  ├── Featured image: /assets/images/blog/blog_timestamp_unique.jpg
  └── Post data: /data/blog-posts.json
```

#### 2. Display Process
```
blogs.html (loads) → JavaScript fetches → /data/blog-posts.json
  └── Dynamically generates → Blog cards with images and links
```

#### 3. Data Flow
```
Admin Upload → JSON File → Blog Listing → Individual Post Page
```

### File Structure

```
Jetwide-web/
├── admin/
│   ├── blog-upload.html      ← Upload form
│   ├── upload-blog.php        ← Backend processor
│   └── test-upload.php        ← Diagnostics tool
├── data/
│   └── blog-posts.json        ← Blog database (JSON)
├── assets/
│   └── images/
│       └── blog/              ← Uploaded images
└── pages/
    ├── blogs.html             ← Blog listing (DYNAMIC)
    └── blog-post.html         ← Single post display
```

### What Happens After Upload

#### Immediate:
1. ✅ Image uploaded to `/assets/images/blog/`
2. ✅ Post data saved to `/data/blog-posts.json`
3. ✅ Unique post ID generated
4. ✅ Success message displayed

#### When You Visit blogs.html:
1. JavaScript loads `/data/blog-posts.json`
2. Parses all posts
3. Groups into rows of 3
4. Displays with:
   - Featured image
   - Publication date badge
   - Title
   - "Read More" link to full post

### Example Post Data Structure

```json
{
  "id": "post_1729506234_a3f9c2d1",
  "title": "Top 10 Safari Destinations in Kenya",
  "slug": "top-10-safari-destinations-kenya",
  "excerpt": "Discover the best wildlife experiences...",
  "content": "<h2>Introduction</h2><p>Kenya is home...</p>",
  "featured_image": "assets/images/blog/blog_1729506234_unique.jpg",
  "category": "Safari Adventures",
  "tags": ["safari", "kenya", "wildlife", "tourism"],
  "author": "Jetwide Travel",
  "publish_date": "2025-10-21",
  "read_time": 7,
  "seo_title": "Top 10 Safari Destinations in Kenya 2025",
  "seo_description": "Explore Kenya's best safari parks...",
  "created_at": "2025-10-21 09:45:00"
}
```

### Viewing Your Posts

#### Blog Listing Page (blogs.html)
**Shows:**
- All published posts
- Newest first
- 3 columns grid layout
- Featured image
- Date badge
- Title
- "Read More" link

**Example URL:** `yourdomain.com/pages/blogs.html`

#### Individual Post Page (blog-post.html)
**Shows:**
- Full content
- Featured image
- Author, date, read time
- Tags
- Related posts
- Sidebar widgets

**Example URL:** `yourdomain.com/pages/blog-post.html?id=post_1729506234_a3f9c2d1`

### Quick Testing Guide

1. **Upload a test post:**
   - Go to: `/admin/blog-upload.html`
   - Fill form with test data
   - Upload any image
   - Click "Publish Post"

2. **Check it saved:**
   - Open: `/data/blog-posts.json`
   - Look for your new post at the top

3. **View on blog page:**
   - Go to: `/pages/blogs.html`
   - Your post should appear as the first card
   - Click "Read More" to see full post

### Troubleshooting

#### "Blog posts not showing on blogs.html"

**Check:**
1. Open browser console (F12) → Check for errors
2. Verify `/data/blog-posts.json` exists and has data
3. Check file path: Should be `../data/blog-posts.json` from pages folder
4. Ensure JavaScript is enabled

**Fix:**
```javascript
// Open browser console and type:
fetch('../data/blog-posts.json')
  .then(r => r.json())
  .then(d => console.log(d))
// Should show your posts
```

#### "Upload successful but post not showing"

**Check:**
1. Refresh `blogs.html` page (Ctrl+F5)
2. Check `/data/blog-posts.json` was updated
3. Verify image uploaded to `/assets/images/blog/`
4. Check browser console for JavaScript errors

#### "Featured image not displaying"

**Check:**
1. Image path in JSON is correct: `"assets/images/blog/filename.jpg"`
2. Image actually exists in that folder
3. Check image file permissions
4. Try opening image URL directly in browser

### URLs You Need to Know

#### For Testing Locally:
- **Upload Form:** `file:///C:/Users/USER/Desktop/Jetwide-web/admin/blog-upload.html`
- **Blog Page:** `file:///C:/Users/USER/Desktop/Jetwide-web/pages/blogs.html`
- **Diagnostics:** `file:///C:/Users/USER/Desktop/Jetwide-web/admin/test-upload.php`
- **JSON Data:** `file:///C:/Users/USER/Desktop/Jetwide-web/data/blog-posts.json`

#### After Deployment:
- **Upload Form:** `yourdomain.com/admin/blog-upload.html`
- **Blog Page:** `yourdomain.com/pages/blogs.html`
- **Single Post:** `yourdomain.com/pages/blog-post.html?id=POST_ID`
- **API Data:** `yourdomain.com/data/blog-posts.json`

### Features

✅ **Dynamic Loading** - Posts load automatically from JSON
✅ **Newest First** - Latest posts appear at the top
✅ **Responsive Grid** - 3 columns on desktop, 1 on mobile
✅ **Date Display** - Formatted date badge on each post
✅ **Direct Links** - Click "Read More" to view full post
✅ **No Manual Updates** - blogs.html updates automatically
✅ **Unlimited Posts** - Add as many posts as you want
✅ **Easy Management** - Edit JSON file to modify posts

### Best Practices

1. **Image Size:** Use 1200x600px for best display
2. **File Format:** JPG or WebP for smaller file sizes
3. **Title Length:** Keep under 60 characters
4. **Excerpt:** 150-300 characters works best
5. **Tags:** Use 3-8 relevant tags per post
6. **Content:** Use HTML formatting for better readability
7. **SEO:** Always fill SEO title and description

### Quick Reference

| Action | URL |
|--------|-----|
| Upload new post | `/admin/blog-upload.html` |
| View all posts | `/pages/blogs.html` |
| View single post | `/pages/blog-post.html?id=POST_ID` |
| Check diagnostics | `/admin/test-upload.php` |
| Edit posts | `/data/blog-posts.json` |
| Uploaded images | `/assets/images/blog/` |

### Summary

**To upload and view a blog post:**
1. Go to `/admin/blog-upload.html`
2. Fill form and upload
3. Click "Publish Post"
4. Visit `/pages/blogs.html` to see it live
5. Click "Read More" to view full post

**That's it!** The system handles everything automatically. No manual HTML editing needed. 🎉

---
*Last Updated: October 21, 2025*
