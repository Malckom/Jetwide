# Blog Upload ↔ Blog Post Display - Field Mapping

## ✅ Complete Field Synchronization

This document shows how each field in the blog upload form maps to the blog post display.

---

## 📝 Form Fields → Display Location

| Upload Form Field | Where It Appears | Notes |
|-------------------|------------------|-------|
| **Post Title** | • Hero section overlay<br>• Browser tab title<br>• SEO title (if custom not set) | Max 150 characters |
| **Excerpt/Summary** | • Blog listing cards<br>• SEO meta description (if custom not set) | Max 300 characters, shows on blogs.html |
| **Featured Image** | • Blog listing card<br>• Hero section background<br>• Main content top | Recommended 1200x600px |
| **Category** | • Post meta bar (badge)<br>• Sidebar category list<br>• Can filter blog listing | 8 available categories |
| **Publish Date** | • Post meta bar<br>• Blog listing date badge | Format: YYYY-MM-DD |
| **Author Name** | • Post meta bar | Shows as "By [Author Name]" |
| **Read Time** | • Post meta bar (optional) | Not currently displayed but stored |
| **Post Tags** | • Sidebar tags cloud<br>• Each tag is clickable | Comma-separated, 3-8 recommended |
| **Blog Content** | • Main content area | Full HTML rendering |
| **SEO Title** | • Browser tab<br>• Search engine results | Max 60 chars, defaults to title |
| **SEO Meta Description** | • Search engine results<br>• Social sharing preview | Max 160 chars, defaults to excerpt |

---

## 🎯 Visual Mapping

### Blog Listing Page (blogs.html)
```
┌─────────────────────────────────┐
│  [Featured Image]               │  ← Featured Image
│  ┌──────────┐                   │
│  │ 15 JAN   │                   │  ← Publish Date
│  └──────────┘                   │
│                                 │
│  Post Title Here                │  ← Post Title
│  [Read More →]                  │  ← Links to blog-post.html?id=POST_ID
└─────────────────────────────────┘
```

### Individual Blog Post Page (blog-post.html)

#### Hero Section
```
┌────────────────────────────────────────────┐
│  [Featured Image Background]               │  ← Featured Image
│                                            │
│  Post Title Here                           │  ← Post Title
└────────────────────────────────────────────┘
```

#### Main Content Area (70%)
```
┌────────────────────────────────────────────┐
│  [Travel Tips] • Jan 15, 2024 • By Author  │  ← Category, Date, Author
├────────────────────────────────────────────┤
│  [Full HTML Content]                       │  ← Blog Content
│  <h2>Section Heading</h2>                  │
│  <p>Paragraph text...</p>                  │
│  <ul><li>List items</li></ul>              │
├────────────────────────────────────────────┤
│  Share: [FB] [TW] [LI] [WA]               │  ← Social Sharing
└────────────────────────────────────────────┘
```

#### Sidebar (30%)
```
┌────────────────────────────┐
│  SEARCH                    │
│  [Search box]              │
├────────────────────────────┤
│  CATEGORIES                │
│  • Travel Tips (12)        │  ← Category (with count)
│  • Safari Adventures (8)   │
├────────────────────────────┤
│  RECENT POSTS              │
│  [Thumb] Post Title        │
│         Jan 15, 2024       │
├────────────────────────────┤
│  TAGS                      │
│  [visa] [kenya] [travel]   │  ← Post Tags
├────────────────────────────┤
│  NEED HELP PLANNING?       │
│  [Contact Us]              │
└────────────────────────────┘
```

---

## 🔄 Data Flow

### Creating a New Post

```
1. Admin fills form (blog-upload.html)
   ↓
2. Form submits to upload-blog.php
   ↓
3. PHP validates and processes:
   - Uploads image → assets/images/blog/
   - Converts tags: "visa, kenya" → ["visa", "kenya"]
   - Generates ID and slug
   - Sets SEO fields (uses defaults if empty)
   ↓
4. Saves to data/blog-posts.json
   ↓
5. Returns post URL: blog-post.html?id=POST_ID
```

### Viewing a Post

```
1. User clicks "Read More" on blogs.html
   ↓
2. Loads blog-post.html?id=POST_ID
   ↓
3. JavaScript extracts POST_ID from URL
   ↓
4. Fetches data/blog-posts.json
   ↓
5. Finds matching post by ID
   ↓
6. Dynamically populates page:
   - Hero title
   - Featured image
   - Meta bar (category, date, author)
   - Full content
   - Tags in sidebar
   - SEO meta tags
```

---

## ✨ New Fields Added for Sidebar Layout

The following fields were **added** to sync with the new sidebar design:

### 1. Post Tags (Required)
- **Form Field**: Text input, comma-separated
- **Example**: `visa, kenya travel, safari tips, tourism`
- **Storage**: Converted to array `["visa", "kenya travel", "safari tips", "tourism"]`
- **Display**: Sidebar tags cloud, each tag is clickable
- **Purpose**: Tag-based navigation and categorization

### 2. SEO Title (Optional)
- **Form Field**: Text input, max 60 characters
- **Default**: Uses post title if empty
- **Display**: Browser tab, search results
- **Purpose**: Optimized for search engines

### 3. SEO Meta Description (Optional)
- **Form Field**: Textarea, max 160 characters
- **Default**: Uses excerpt if empty
- **Display**: Search results snippet, social sharing
- **Purpose**: Improved SEO and click-through rates

### 4. Category Format Updated
- **Before**: `travel-tips` (lowercase with dashes)
- **Now**: `Travel Tips` (proper case with spaces)
- **Purpose**: Direct display in meta bar and sidebar without transformation

---

## 🎨 Styling Sync

All sidebar widgets use consistent styling from `assets/styles.css`:

```css
/* Sidebar Layout */
.blog-post-layout { /* Grid: main + sidebar */ }
.blog-post-sidebar { /* Sticky positioning */ }

/* Widgets */
.sidebar-widget { /* Card styling */ }
.widget-title { /* Consistent headers */ }

/* Tags (from upload form) */
.tags-cloud { /* Flex wrap container */ }
.tag { /* Pill-style buttons */ }

/* Categories */
.category-list { /* Vertical list */ }
.category-list a { /* Links with hover */ }
```

---

## 📋 Checklist: Adding a New Blog Post

- [ ] Open `admin/blog-upload.html`
- [ ] Enter **post title** (engaging, 150 chars max)
- [ ] Write **excerpt** (compelling summary, 300 chars max)
- [ ] Upload **featured image** (1200x600px recommended)
- [ ] Select **category** from dropdown
- [ ] Set **publish date**
- [ ] Add **3-8 tags** (comma-separated, for sidebar)
- [ ] Write **blog content** (use formatting buttons)
- [ ] Optionally add **SEO title** (60 chars max)
- [ ] Optionally add **SEO description** (160 chars max)
- [ ] Click **Publish Post**
- [ ] Verify post appears in `data/blog-posts.json`
- [ ] Update `pages/blogs.html` to add card with link
- [ ] Test post loads correctly with all sidebar widgets

---

## 🔍 Testing the Sync

To verify everything is working:

1. **Create test post** via upload form
2. **Check JSON file**:
   ```bash
   cat data/blog-posts.json
   ```
3. **Verify fields**:
   - ✅ `tags` is an array
   - ✅ `category` has proper case
   - ✅ `seo_title` and `seo_description` exist
   - ✅ `featured_image` path is correct
4. **Test display**:
   - Visit `blog-post.html?id=POST_ID`
   - Verify hero title shows
   - Check meta bar displays correctly
   - Confirm tags appear in sidebar
   - Test social sharing buttons

---

## 🚨 Common Issues

### Tags not showing in sidebar
- **Problem**: Tags stored as string instead of array
- **Solution**: Ensure `upload-blog.php` converts comma-separated string to array

### Category doesn't match sidebar
- **Problem**: Upload form uses different format (e.g., `travel-tips` vs `Travel Tips`)
- **Solution**: Updated upload form to use proper case with spaces

### SEO title empty
- **Problem**: Optional field left blank
- **Solution**: PHP backend defaults to post title if empty

### Image not displaying
- **Problem**: Wrong path in JSON
- **Solution**: Verify path is relative: `assets/images/blog/filename.jpg`

---

## 📦 Files Updated for Sync

1. ✅ `admin/blog-upload.html`
   - Added tags field (required)
   - Added SEO title field (optional)
   - Added SEO description field (optional)
   - Updated category format
   - Added character counters for SEO fields

2. ✅ `admin/upload-blog.php`
   - Added tags to required fields
   - Convert comma-separated tags to array
   - Added SEO fields with defaults
   - Reordered fields in JSON output

3. ✅ `pages/blog-post.html`
   - JavaScript populates tags in sidebar
   - Loads SEO fields for meta tags
   - Displays category in meta bar

4. ✅ `assets/styles.css`
   - Added sidebar layout styles
   - Added tag cloud styles
   - Added responsive breakpoints

5. ✅ `data/blog-posts.json`
   - Sample post with all new fields

---

**Status**: ✅ Complete Synchronization Achieved

All form fields now properly map to blog post display with sidebar layout.
