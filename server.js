// Jetwide Travel Email Server
const express = require('express');
const nodemailer = require('nodemailer');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Disable caching for development
app.use((req, res, next) => {
  res.set({
    'Cache-Control': 'no-cache, no-store, must-revalidate, max-age=0',
    'Pragma': 'no-cache',
    'Expires': '0',
    'Last-Modified': new Date().toUTCString(),
    'ETag': false
  });
  next();
});

// Serve static files
app.use(express.static('.', {
  etag: false,
  lastModified: false,
  setHeaders: (res, path) => {
    res.set({
      'Cache-Control': 'no-cache, no-store, must-revalidate, max-age=0',
      'Pragma': 'no-cache',
      'Expires': '0'
    });
  }
}));
app.use('/New', express.static('New'));

// Email transporter configuration
const createTransporter = () => {
  return nodemailer.createTransporter({
    service: 'gmail',
    auth: {
      user: process.env.EMAIL_USER || 'your-email@gmail.com',
      pass: process.env.EMAIL_PASS || 'your-app-password'
    }
  });
};

// Job Application endpoint
app.post('/send-job-application', async (req, res) => {
  try {
    console.log('Received job application:', req.body);
    
    const {
      name, passportNo, issueDate, expiryDate, placeOfBirth, dob,
      phone, idNo, address, email, education, period, occupation,
      refusedEntry, countryRefused, reason, position, additionalInfo
    } = req.body;

    if (!name || !passportNo || !email || !phone || !position) {
      return res.status(400).json({
        success: false,
        message: 'Missing required fields. Please fill in all required information.'
      });
    }

    const transporter = createTransporter();

    // Email to recruitment team
    const recruitmentEmailHTML = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #FE9900 0%, #e88800 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .section { margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-left: 4px solid #FE9900; }
        .section h3 { color: #FE9900; margin-top: 0; }
        .field { margin-bottom: 10px; }
        .alert { background: #fff8e1; padding: 15px; border-left: 4px solid #d4af37; margin: 10px 0; }
        .footer { background: #14132A; color: white; padding: 15px; text-align: center; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>🌍 New Job Application - Serbia</h1>
        <p><strong>Position: ${position}</strong></p>
      </div>
      <div class="content">
        <div class="alert">
          <strong>⏰ Action Required:</strong> Review and contact applicant within 24 hours
        </div>
        <div class="section">
          <h3>📋 Personal Information</h3>
          <div class="field"><strong>Name:</strong> ${name}</div>
          <div class="field"><strong>Email:</strong> ${email}</div>
          <div class="field"><strong>Phone:</strong> ${phone}</div>
          <div class="field"><strong>ID No:</strong> ${idNo || 'Not provided'}</div>
          <div class="field"><strong>Address:</strong> ${address || 'Not provided'}</div>
          <div class="field"><strong>Date of Birth:</strong> ${dob || 'Not provided'}</div>
          <div class="field"><strong>Place of Birth:</strong> ${placeOfBirth || 'Not provided'}</div>
        </div>
        <div class="section">
          <h3>🛂 Passport Information</h3>
          <div class="field"><strong>Passport No:</strong> ${passportNo}</div>
          <div class="field"><strong>Issue Date:</strong> ${issueDate || 'Not provided'}</div>
          <div class="field"><strong>Expiry Date:</strong> ${expiryDate || 'Not provided'}</div>
        </div>
        <div class="section">
          <h3>🎓 Education & Experience</h3>
          <div class="field"><strong>Education:</strong> ${education || 'Not provided'}</div>
          <div class="field"><strong>Period:</strong> ${period || 'Not provided'}</div>
          <div class="field"><strong>Current Occupation:</strong> ${occupation || 'Not provided'}</div>
        </div>
        <div class="section">
          <h3>✈️ Travel History</h3>
          <div class="field"><strong>Ever Refused Entry:</strong> ${refusedEntry || 'Not specified'}</div>
          ${refusedEntry === 'Yes' ? `
          <div class="field"><strong>Country:</strong> ${countryRefused || 'N/A'}</div>
          <div class="field"><strong>Reason:</strong> ${reason || 'N/A'}</div>
          ` : ''}
        </div>
        <div class="section">
          <h3>💼 Position Details</h3>
          <div class="field"><strong>Position Applied For:</strong> ${position}</div>
          ${additionalInfo ? `<div class="field"><strong>Additional Information:</strong><br>${additionalInfo}</div>` : ''}
        </div>
      </div>
      <div class="footer">
        <p>Application submitted on ${new Date().toLocaleString()}</p>
        <p>📧 Reply to: ${email} | 📞 ${phone}</p>
      </div>
    </body>
    </html>`;

    const recruitmentMailOptions = {
      from: { name: 'Jetwide Website', address: process.env.EMAIL_USER || 'your-email@gmail.com' },
      to: 'recruitment@jetwide.org',
      cc: process.env.EMAIL_USER || 'your-email@gmail.com',
      subject: `🌍 New Job Application - ${position} - Serbia (${name})`,
      html: recruitmentEmailHTML,
      replyTo: email
    };

    await transporter.sendMail(recruitmentMailOptions);

    // Confirmation email to applicant
    const confirmationHTML = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #FE9900 0%, #e88800 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .section { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-left: 4px solid #FE9900; }
        .highlight { background: #fff8e1; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .contact { background: #14132A; color: white; padding: 20px; text-align: center; border-radius: 5px; }
        .checkmark { color: #28a745; font-size: 20px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>✅ Application Received!</h1>
        <p>Your Journey to Serbia Begins Here</p>
      </div>
      <div class="content">
        <p>Dear ${name},</p>
        <div class="section">
          <h3>🎉 Thank You for Applying!</h3>
          <p>We have successfully received your application for the <strong>${position}</strong> position in Serbia through Jetwide Travel & Safari.</p>
        </div>
        <div class="highlight">
          <h3>📋 Application Summary</h3>
          <p><strong>Position:</strong> ${position}</p>
          <p><strong>Location:</strong> Serbia</p>
          <p><strong>Status:</strong> <span style="color: #FE9900; font-weight: bold;">Under Review</span></p>
        </div>
        <div class="section">
          <h3>🔄 What Happens Next?</h3>
          <p><span class="checkmark">✓</span> <strong>Application Review:</strong> Our recruitment team will carefully review your application within 24 hours.</p>
          <p><span class="checkmark">✓</span> <strong>Initial Contact:</strong> If your profile matches our requirements, we'll contact you for a preliminary discussion.</p>
          <p><span class="checkmark">✓</span> <strong>Interview & Assessment:</strong> Qualified candidates will be invited for interviews.</p>
          <p><span class="checkmark">✓</span> <strong>Documentation:</strong> We'll guide you through all necessary paperwork.</p>
          <p><span class="checkmark">✓</span> <strong>Deployment:</strong> Final contract signing and travel arrangements to Serbia.</p>
        </div>
        <div class="contact">
          <h3>📞 Need Assistance?</h3>
          <p><strong>Email:</strong> recruitment@jetwide.org</p>
          <p><strong>Phone:</strong> +254 748 538 311</p>
          <p><strong>Office:</strong> Westlands Square, 2nd Floor, Nairobi</p>
          <p><strong>Hours:</strong> Monday - Friday, 9 AM - 5 PM</p>
        </div>
        <p style="text-align: center; margin-top: 30px;">
          <strong>🌟 Jetwide Travel & Safari</strong><br>
          Licensed Recruitment Agency | 100% Legal Compliance
        </p>
      </div>
    </body>
    </html>`;

    const confirmationOptions = {
      from: { name: 'Jetwide Recruitment', address: process.env.EMAIL_USER || 'your-email@gmail.com' },
      to: email,
      subject: `✅ Application Received - ${position} | Jetwide Recruitment`,
      html: confirmationHTML
    };

    await transporter.sendMail(confirmationOptions);

    res.json({
      success: true,
      message: 'Thank you! Your application has been submitted successfully. We will review it and contact you within 24 hours.'
    });

  } catch (error) {
    console.error('Job application error:', error);
    res.status(500).json({
      success: false,
      message: 'Sorry, there was an error submitting your application. Please try again or contact us directly at recruitment@jetwide.org'
    });
  }
});

// Email sending endpoint
app.post('/send-contact-form', async (req, res) => {
  try {
    console.log('Received contact form submission:', req.body);
    
    const {
      firstName, lastName, email, phone, country, destination,
      tripType, startDate, duration, groupSize, budget,
      accommodation, services, specialRequests
    } = req.body;

    if (!firstName || !lastName || !email || !destination || !tripType) {
      return res.status(400).json({
        success: false,
        message: 'Missing required fields. Please fill in all required information.'
      });
    }

    const transporter = createTransporter();
    const servicesList = Array.isArray(services) ? services.join(', ') : (services || 'None selected');

    const emailHTML = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .section { margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-left: 4px solid #d4af37; }
        .section h3 { color: #d4af37; margin-top: 0; }
        .field { margin-bottom: 10px; }
        .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>🌍 New Trip Planning Request</h1>
        <p>Jetwide Travel & Safari</p>
      </div>
      <div class="content">
        <div class="section">
          <h3>👤 Personal Information</h3>
          <div class="field"><strong>Name:</strong> ${firstName} ${lastName}</div>
          <div class="field"><strong>Email:</strong> ${email}</div>
          <div class="field"><strong>Phone:</strong> ${phone || 'Not provided'}</div>
          <div class="field"><strong>Country:</strong> ${country || 'Not specified'}</div>
        </div>
        <div class="section">
          <h3>🗺️ Trip Details</h3>
          <div class="field"><strong>Destination:</strong> ${destination}</div>
          <div class="field"><strong>Trip Type:</strong> ${tripType}</div>
          <div class="field"><strong>Start Date:</strong> ${startDate}</div>
          <div class="field"><strong>Duration:</strong> ${duration}</div>
          <div class="field"><strong>Group Size:</strong> ${groupSize}</div>
          <div class="field"><strong>Budget:</strong> ${budget || 'Not specified'}</div>
        </div>
        <div class="section">
          <h3>🏨 Preferences</h3>
          <div class="field"><strong>Accommodation:</strong> ${accommodation || 'Not specified'}</div>
          <div class="field"><strong>Additional Services:</strong> ${servicesList}</div>
        </div>
        ${specialRequests ? `<div class="section"><h3>📝 Special Requests</h3><div class="field">${specialRequests}</div></div>` : ''}
      </div>
      <div class="footer">
        <p>This request was sent from the Jetwide Travel website contact form.</p>
        <p>📧 Respond to: ${email} | 📞 ${phone || 'No phone provided'}</p>
      </div>
    </body>
    </html>`;

    const mailOptions = {
      from: { name: 'Jetwide Website', address: process.env.EMAIL_USER || 'your-email@gmail.com' },
      to: 'tours@jetwide.org',
      cc: process.env.EMAIL_USER || 'your-email@gmail.com',
      subject: `🌍 Trip Planning Request - ${destination} (${firstName} ${lastName})`,
      html: emailHTML,
      replyTo: email
    };

    await transporter.sendMail(mailOptions);

    // Send confirmation to customer
    const confirmationOptions = {
      from: { name: 'Jetwide Travel & Safari', address: process.env.EMAIL_USER || 'your-email@gmail.com' },
      to: email,
      subject: '✅ Your Trip Planning Request - Jetwide Travel',
      html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%); color: white; padding: 30px; text-align: center;">
          <h1>🙏 Thank You for Your Interest!</h1>
          <p>Your trip planning request has been received</p>
        </div>
        <div style="padding: 30px;">
          <p>Dear ${firstName},</p>
          <p>Thank you for choosing Jetwide Travel & Safari for your <strong>${destination}</strong> adventure!</p>
          <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>📋 What happens next?</h3>
            <ul>
              <li>Our travel specialists will review your request within <strong>24 hours</strong></li>
              <li>We'll prepare a customized itinerary based on your preferences</li>
              <li>You'll receive a detailed proposal with pricing and options</li>
            </ul>
          </div>
          <div style="background: #e8f5e8; padding: 20px; border-radius: 8px;">
            <h3>📞 Contact us:</h3>
            <p><strong>Phone:</strong> +254 748 538 311<br>
            <strong>Email:</strong> tours@jetwide.org</p>
          </div>
          <p>Best regards,<br><strong>The Jetwide Travel Team</strong></p>
        </div>
      </div>`
    };

    await transporter.sendMail(confirmationOptions);

    res.json({
      success: true,
      message: 'Your trip request has been sent successfully! Check your email for confirmation.'
    });

  } catch (error) {
    console.error('Email sending error:', error);
    res.status(500).json({
      success: false,
      message: 'Sorry, there was an error sending your request. Please try again or contact us directly.'
    });
  }
});

// Root redirect
app.get('/', (req, res) => {
  res.redirect('/index.html');
});

app.listen(PORT, () => {
  console.log(`🚀 Jetwide Email Server running on http://localhost:${PORT}`);
  console.log(`📧 Email functionality ready!`);
  console.log(`🌐 Contact form: http://localhost:${PORT}/pages/contact-form.html`);
  console.log(`💼 Job application: http://localhost:${PORT}/pages/job-application.html`);
  
  if (!process.env.EMAIL_USER || !process.env.EMAIL_PASS) {
    console.warn('⚠️  EMAIL CONFIGURATION NEEDED:');
    console.warn('   Create a .env file with:');
    console.warn('   EMAIL_USER=your-email@gmail.com');
    console.warn('   EMAIL_PASS=your-app-password');
  }
});