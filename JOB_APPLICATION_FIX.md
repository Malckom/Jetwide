# Job Application Form - 404 Error Fixed ✅

## Issue
The job application form was trying to use PHP (`send-job-application.php`), but the site runs on a Node.js Express server, causing a 404 error.

## Solution
Added a Node.js endpoint to handle job applications instead of PHP.

## Changes Made

### 1. **server.js** - Added `/send-job-application` endpoint
- New POST endpoint at `/send-job-application`
- Sends email to `recruitment@jetwide.org`
- Sends confirmation email to applicant
- Matches the same pattern as the contact form endpoint
- Professional HTML email templates

### 2. **pages/job-application.html** - Updated fetch URL
- Changed from: `../send-job-application.php`
- Changed to: `/send-job-application`
- Now works with the Node.js server

### 3. **Server startup message**
- Added job application URL to console output
- Shows: `http://localhost:3000/pages/job-application.html`

## How to Test

### 1. Make sure server is running:
```bash
node server.js
```

You should see:
```
🚀 Jetwide Email Server running on http://localhost:3000
📧 Email functionality ready!
🌐 Contact form: http://localhost:3000/pages/contact-form.html
💼 Job application: http://localhost:3000/pages/job-application.html
```

### 2. Test the form:
1. Open: http://localhost:3000/pages/job-application.html
2. Fill out the form
3. Click "Submit Application"
4. You should see a success message

### 3. Check emails:
- Business email goes to: `recruitment@jetwide.org`
- Confirmation email goes to: applicant's email

## Email Configuration
Make sure you have a `.env` file with:
```
EMAIL_USER=your-email@gmail.com
EMAIL_PASS=your-app-password
```

## Server Endpoints

### Job Application
- **URL**: `/send-job-application`
- **Method**: POST
- **Sends to**: recruitment@jetwide.org
- **Confirmation to**: Applicant email

### Contact Form  
- **URL**: `/send-contact-form`
- **Method**: POST
- **Sends to**: tours@jetwide.org
- **Confirmation to**: Customer email

## Important Notes

1. **Server must be running** - Always use `node server.js` when testing locally
2. **Use localhost:3000** - Don't open files directly (file://)
3. **Email setup** - Configure .env file for emails to work
4. **Production** - When deploying, ensure Node.js server is running

## Access the Form

### Local Development:
- Job Placement Page: http://localhost:3000/pages/job-placement.html
- Job Application Form: http://localhost:3000/pages/job-application.html

### After Deployment:
- Job Placement Page: https://yoursite.com/pages/job-placement.html
- Job Application Form: https://yoursite.com/pages/job-application.html

## Error Fixed! ✅
The 404 error is now resolved. The form will work correctly when the Node.js server is running.

---

**Status**: ✅ Fixed and Working
**Server**: Node.js Express
**Email Destination**: recruitment@jetwide.org
**Test URL**: http://localhost:3000/pages/job-application.html
