# Package Page Template

This document provides the standard format for all package pages on the Jetwide website.

## Hero Section Format (Package Meta)

Use this exact structure for the package details section below the title:

```html
<!-- Package Detail Hero -->
<section class="package-detail-hero">
    <div class="container">
        <div class="package-detail-header">
            <div class="package-breadcrumb">
                <a href="../../index.html">Home</a> > 
                <a href="../[CATEGORY].html">[Category Name]</a> > 
                <span>[Package Name]</span>
            </div>
            <h1 class="package-detail-title">[XX-Days Package Title]</h1>
            <div class="package-meta">
                <div class="meta-item">
                    <strong>FROM</strong>
                    <span class="package-price">[Price]</span>
                </div>
                <div class="meta-item">
                    <strong>HOTEL RATING</strong>
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                </div>
                <div class="meta-item">
                    <strong>TOUR TYPE</strong>
                    <span class="tour-type">[Themed/Beach/Safari/etc]</span>
                </div>
                <div class="meta-item">
                    <strong>DESTINATION</strong>
                    <span>[Country/Location]</span>
                </div>
            </div>
        </div>
    </div>
</section>
```

## Field Guidelines

### FROM (Price)
- Format: `KSh XXX,XXX` or `USD XXX`
- Class: `package-price` (styled in gold/yellow)
- Example: `KSh 210,000` or `USD 2,500`

### HOTEL RATING
- Use star emojis: ⭐
- Number of stars: 3-5 stars based on actual hotel rating
- Class: `rating`
- Examples:
  - Budget: ⭐⭐⭐
  - Standard: ⭐⭐⭐⭐
  - Luxury: ⭐⭐⭐⭐⭐

### TOUR TYPE
- Common values: `Themed`, `Beach`, `Safari`, `Adventure`, `Cultural`
- Class: `tour-type` (styled with orange badge background)
- Use single word or short phrase

### DESTINATION
- Format: Country or City, Country
- Examples:
  - `Malaysia`
  - `Dubai, UAE`
  - `South Africa`
  - `Diani Beach, Kenya`

## Visual Design

The package meta displays as a **4-column grid** on desktop:
- Each column has a label (in uppercase gray text)
- Each value is styled distinctly:
  - Price: Gold/yellow color
  - Stars: Gold star emojis
  - Tour Type: Orange pill/badge
  - Destination: White text

## Examples from Current Packages

### Dubai Honeymoon
```html
<div class="package-meta">
    <div class="meta-item">
        <strong>FROM</strong>
        <span class="package-price">KSh 285,000</span>
    </div>
    <div class="meta-item">
        <strong>HOTEL RATING</strong>
        <div class="rating">⭐⭐⭐⭐⭐</div>
    </div>
    <div class="meta-item">
        <strong>TOUR TYPE</strong>
        <span class="tour-type">Themed</span>
    </div>
    <div class="meta-item">
        <strong>DESTINATION</strong>
        <span>Dubai, UAE</span>
    </div>
</div>
```

### Diani Budget Getaway
```html
<div class="package-meta">
    <div class="meta-item">
        <strong>FROM</strong>
        <span class="package-price">USD 426</span>
    </div>
    <div class="meta-item">
        <strong>HOTEL RATING</strong>
        <div class="rating">⭐⭐⭐</div>
    </div>
    <div class="meta-item">
        <strong>TOUR TYPE</strong>
        <span class="tour-type">Beach</span>
    </div>
    <div class="meta-item">
        <strong>DESTINATION</strong>
        <span>Diani Beach, Kenya</span>
    </div>
</div>
```

## CSS Classes Used

- `.package-detail-hero` - Hero section container
- `.package-detail-header` - Header wrapper
- `.package-breadcrumb` - Breadcrumb navigation
- `.package-detail-title` - Main H1 title
- `.package-meta` - Grid container for 4 meta items
- `.meta-item` - Individual column container
- `.package-price` - Price styling (gold color)
- `.rating` - Star rating container
- `.tour-type` - Tour type badge (orange pill)

## Notes for Future Packages

1. **Always use uppercase** for the labels (FROM, HOTEL RATING, TOUR TYPE, DESTINATION)
2. **Match the breadcrumb** to the actual category page (beach-getaways.html, themed-packages.html, etc.)
3. **Use consistent pricing format** - include currency prefix
4. **Star ratings** should reflect actual hotel quality, not arbitrary ratings
5. **Tour Type badge** will automatically get the orange background styling via CSS
6. **Keep destination names** concise and recognizable

## Responsive Behavior

On mobile devices (< 768px):
- The 4-column grid stacks vertically
- Each meta-item takes full width
- Maintains the same visual hierarchy
- Labels remain uppercase and bold

---

**Updated:** October 13, 2025  
**Status:** All existing packages updated to this format
