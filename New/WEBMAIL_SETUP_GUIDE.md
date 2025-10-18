# WordPress Email Configuration Guide

## Getting Your Webmail SMTP Settings

To configure automatic email sending for your contact form on WordPress hosting, you need to get your webmail SMTP settings from your hosting provider.

### Step 1: Find Your Webmail SMTP Settings

Contact your hosting provider or check your hosting control panel for these details:

**Typical Settings for `tours@jetwide.org`:**
- **SMTP Server:** `mail.jetwide.org` (or `smtp.jetwide.org`)
- **Port:** `587` (TLS) or `465` (SSL)
- **Encryption:** `TLS` or `SSL`
- **Username:** `tours@jetwide.org` (your full email address)
- **Password:** Your email account password

### Step 2: Update the PHP Configuration

Open `New/send-contact-form.php` and update the SMTP configuration section:

```php
$smtp_config = [
    'host' => 'mail.jetwide.org', // Replace with your actual SMTP server
    'port' => 587, // Use 587 for TLS or 465 for SSL
    'encryption' => 'tls', // Use 'tls' or 'ssl'
    'username' => 'tours@jetwide.org',
    'password' => 'your-actual-email-password', // Replace with real password
    'from_email' => 'tours@jetwide.org',
    'from_name' => 'Jetwide Tours & Safaris'
];
```

### Step 3: WordPress Installation Options

#### Option A: Direct File Upload (Simplest)
1. Upload the entire `New` folder to your WordPress site
2. The contact form will work as a standalone page
3. Access it at: `https://yoursite.com/New/pages/contact-form.html`

#### Option B: WordPress Integration (Recommended)
1. Install the "WP Mail SMTP" plugin from WordPress admin
2. Configure it with your webmail settings
3. The PHP script will automatically use WordPress's email system

### Step 4: Common Webmail SMTP Settings by Provider

**cPanel Hosting:**
- SMTP: `mail.yourdomain.com`
- Port: 587 (TLS) or 465 (SSL)

**Hostinger:**
- SMTP: `smtp.hostinger.com`
- Port: 587

**SiteGround:**
- SMTP: `smtp.siteground.com`
- Port: 465 (SSL)

**Bluehost:**
- SMTP: `box.yourdomain.com`
- Port: 465

### Step 5: Testing

1. Upload files to your WordPress hosting
2. Visit the contact form page
3. Fill out and submit a test form
4. Check both your `tours@jetwide.org` inbox and the customer's email

### Troubleshooting

**If emails don't send:**
1. Check your hosting provider's email logs
2. Verify SMTP settings are correct
3. Ensure your hosting allows outbound SMTP
4. Try using WordPress's wp_mail() function instead

**Security Notes:**
- Never commit email passwords to public repositories
- Consider using WordPress environment variables for credentials
- Enable two-factor authentication on your email account

### WordPress Plugin Alternative

For easier setup, consider installing these WordPress plugins:
- **WP Mail SMTP** - Easy SMTP configuration
- **Contact Form 7** - Popular WordPress contact forms
- **Fluent Forms** - Advanced form builder with email integration

The current setup provides automatic email sending without requiring users to open their email clients, exactly as requested.