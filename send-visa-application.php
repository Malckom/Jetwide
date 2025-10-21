<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to validate date format
function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Collect and sanitize form data
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $dateOfBirth = sanitizeInput($_POST['dateOfBirth'] ?? '');
    $nationality = sanitizeInput($_POST['nationality'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    
    $passportNumber = sanitizeInput($_POST['passportNumber'] ?? '');
    $passportCountry = sanitizeInput($_POST['passportCountry'] ?? '');
    $passportExpiry = sanitizeInput($_POST['passportExpiry'] ?? '');
    
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    
    $purposeOfTravel = sanitizeInput($_POST['purposeOfTravel'] ?? '');
    $arrivalDate = sanitizeInput($_POST['arrivalDate'] ?? '');
    $durationOfStay = sanitizeInput($_POST['durationOfStay'] ?? '');
    
    $declaration = isset($_POST['declaration']) ? 'Yes' : 'No';
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($dateOfBirth) || 
        empty($nationality) || empty($gender) || empty($passportNumber) || 
        empty($passportCountry) || empty($passportExpiry) || empty($email) || 
        empty($phone) || empty($address) || empty($purposeOfTravel) || 
        empty($arrivalDate) || empty($durationOfStay)) {
        throw new Exception('All required fields must be filled out');
    }
    
    // Validate email
    if (!isValidEmail($email)) {
        throw new Exception('Invalid email address');
    }
    
    // Validate dates
    if (!isValidDate($dateOfBirth)) {
        throw new Exception('Invalid date of birth format');
    }
    if (!isValidDate($passportExpiry)) {
        throw new Exception('Invalid passport expiry date format');
    }
    if (!isValidDate($arrivalDate)) {
        throw new Exception('Invalid arrival date format');
    }
    
    // Validate declaration
    if ($declaration !== 'Yes') {
        throw new Exception('You must accept the declaration to proceed');
    }
    
    // Handle file upload
    $uploadedFile = '';
    $uploadDir = __DIR__ . '/assets/documents/visa-applications/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (isset($_FILES['passportCopy']) && $_FILES['passportCopy']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['passportCopy']['tmp_name'];
        $fileName = $_FILES['passportCopy']['name'];
        $fileSize = $_FILES['passportCopy']['size'];
        $fileType = $_FILES['passportCopy']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Validate file extension
        $allowedExts = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($fileExtension, $allowedExts)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and PDF files are allowed');
        }
        
        // Validate file size (2MB max)
        if ($fileSize > 2 * 1024 * 1024) {
            throw new Exception('File size exceeds 2MB limit');
        }
        
        // Generate unique filename
        $newFileName = 'passport_' . $firstName . '_' . $lastName . '_' . time() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $uploadedFile = $newFileName;
        } else {
            throw new Exception('Error uploading file. Please try again');
        }
    } else {
        throw new Exception('Passport bio-page copy is required');
    }
    
    // Prepare email content for business
    $businessEmail = generateBusinessEmail(
        $firstName, $lastName, $dateOfBirth, $nationality, $gender,
        $passportNumber, $passportCountry, $passportExpiry,
        $email, $phone, $address,
        $purposeOfTravel, $arrivalDate, $durationOfStay,
        $uploadedFile
    );
    
    // Prepare email content for customer
    $customerEmail = generateCustomerEmail($firstName, $lastName);
    
    // Email headers
    $businessHeaders = "MIME-Version: 1.0\r\n";
    $businessHeaders .= "Content-type: text/html; charset=UTF-8\r\n";
    $businessHeaders .= "From: Jetwide Visa Services <noreply@jetwide.org>\r\n";
    $businessHeaders .= "Reply-To: " . $email . "\r\n";
    
    $customerHeaders = "MIME-Version: 1.0\r\n";
    $customerHeaders .= "Content-type: text/html; charset=UTF-8\r\n";
    $customerHeaders .= "From: Jetwide Visa Services <noreply@jetwide.org>\r\n";
    $customerHeaders .= "Reply-To: visas@jetwide.org\r\n";
    
    // Send emails
    $businessEmailSent = mail(
        'visas@jetwide.org',
        'New Visa Application - ' . $firstName . ' ' . $lastName,
        $businessEmail,
        $businessHeaders
    );
    
    $customerEmailSent = mail(
        $email,
        'Visa Application Received - Jetwide Travel & Safari',
        $customerEmail,
        $customerHeaders
    );
    
    if ($businessEmailSent && $customerEmailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your visa application has been submitted successfully. We will contact you shortly to schedule your document verification appointment.'
        ]);
    } else {
        throw new Exception('Error sending confirmation emails. Please contact us directly');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Function to generate business notification email
function generateBusinessEmail($firstName, $lastName, $dateOfBirth, $nationality, $gender,
                               $passportNumber, $passportCountry, $passportExpiry,
                               $email, $phone, $address,
                               $purposeOfTravel, $arrivalDate, $durationOfStay,
                               $uploadedFile) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #14132A 0%, #1a1a3e 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; }
            .section { margin-bottom: 25px; }
            .section-title { font-size: 18px; font-weight: bold; color: #14132A; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #FE9900; }
            .info-row { display: flex; margin-bottom: 10px; }
            .info-label { font-weight: bold; width: 200px; color: #555; }
            .info-value { flex: 1; color: #333; }
            .file-info { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-top: 10px; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🛂 New Visa Application Received</h1>
                <p style="margin: 10px 0 0 0; font-size: 14px;">Application submitted on ' . date('F j, Y \a\t g:i A') . '</p>
            </div>
            
            <div class="content">
                <div class="section">
                    <div class="section-title">📋 Personal Details</div>
                    <div class="info-row">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">' . htmlspecialchars($firstName . ' ' . $lastName) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value">' . htmlspecialchars($dateOfBirth) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nationality:</div>
                        <div class="info-value">' . htmlspecialchars($nationality) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Gender:</div>
                        <div class="info-value">' . htmlspecialchars($gender) . '</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">🛂 Passport Information</div>
                    <div class="info-row">
                        <div class="info-label">Passport Number:</div>
                        <div class="info-value">' . htmlspecialchars($passportNumber) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Passport Country:</div>
                        <div class="info-value">' . htmlspecialchars($passportCountry) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Passport Expiry:</div>
                        <div class="info-value">' . htmlspecialchars($passportExpiry) . '</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">📞 Contact Information</div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value"><a href="tel:' . htmlspecialchars($phone) . '">' . htmlspecialchars($phone) . '</a></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value">' . nl2br(htmlspecialchars($address)) . '</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">✈️ Travel Information</div>
                    <div class="info-row">
                        <div class="info-label">Purpose of Travel:</div>
                        <div class="info-value">' . htmlspecialchars($purposeOfTravel) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Intended Arrival:</div>
                        <div class="info-value">' . htmlspecialchars($arrivalDate) . '</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Duration of Stay:</div>
                        <div class="info-value">' . htmlspecialchars($durationOfStay) . '</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">📎 Uploaded Document</div>
                    <div class="file-info">
                        <strong>Passport Bio-Page:</strong> ' . htmlspecialchars($uploadedFile) . '<br>
                        <small>Location: /assets/documents/visa-applications/</small>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>Next Steps:</strong> Contact the applicant to schedule a document verification appointment.</p>
                <p style="margin: 5px 0;">Jetwide Travel & Safari | Westlands Square, 2nd Floor | Nairobi, Kenya</p>
                <p style="margin: 5px 0;">Phone: 0748 538 311 | Email: visas@jetwide.org</p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

// Function to generate customer confirmation email
function generateCustomerEmail($firstName, $lastName) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #14132A 0%, #1a1a3e 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; }
            .highlight { background: #FFF8E1; padding: 15px; border-left: 4px solid #FE9900; margin: 20px 0; border-radius: 4px; }
            .next-steps { background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .next-steps h3 { margin-top: 0; color: #14132A; }
            .next-steps ul { margin: 10px 0; padding-left: 20px; }
            .next-steps li { margin: 8px 0; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; color: #666; font-size: 14px; }
            .contact-btn { display: inline-block; background: #FE9900; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 5px; font-weight: bold; }
            .social-links { margin: 15px 0; }
            .social-links a { display: inline-block; margin: 0 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>✅ Visa Application Received</h1>
                <p style="margin: 10px 0 0 0;">Dear ' . htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) . '</p>
            </div>
            
            <div class="content">
                <p>Thank you for submitting your visa application through Jetwide Travel & Safari!</p>
                
                <div class="highlight">
                    <strong>⚠️ Important:</strong> This is only a pre-application. You must bring all original documents to our office for verification to complete your application process.
                </div>
                
                <div class="next-steps">
                    <h3>📋 Next Steps:</h3>
                    <ul>
                        <li><strong>Step 1:</strong> Our visa team will review your submission within 24-48 hours.</li>
                        <li><strong>Step 2:</strong> We will contact you via phone or email to schedule an office appointment.</li>
                        <li><strong>Step 3:</strong> Bring all original documents for verification (passport, financial statements, etc.).</li>
                        <li><strong>Step 4:</strong> We will guide you through the complete visa application process.</li>
                    </ul>
                </div>
                
                <p><strong>What to prepare for your office visit:</strong></p>
                <ul>
                    <li>Original passport with at least 6 months validity</li>
                    <li>Recent passport-sized photographs</li>
                    <li>Bank statements (last 3-6 months)</li>
                    <li>Proof of accommodation/itinerary</li>
                    <li>Travel insurance (if required)</li>
                    <li>Employment/student verification letters</li>
                    <li>Any supporting documents based on your travel purpose</li>
                </ul>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="tel:+254748538311" class="contact-btn">📞 Call: 0748 538 311</a>
                    <a href="https://wa.me/254748538311" class="contact-btn" style="background: #25D366;">💬 WhatsApp Us</a>
                </div>
                
                <p style="margin-top: 25px;"><strong>Our Office Location:</strong><br>
                Westlands Square, 2nd Floor<br>
                Nairobi, Kenya</p>
                
                <p><strong>Working Hours:</strong><br>
                Monday - Friday: 9:00 AM - 5:00 PM<br>
                Saturday: 10:00 AM - 2:00 PM</p>
            </div>
            
            <div class="footer">
                <p><strong>Need Help?</strong></p>
                <p>Email: visas@jetwide.org | Phone: 0748 538 311 | 0700 368 676</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/jetwideconsortium/" target="_blank">Facebook</a> |
                    <a href="https://www.instagram.com/jetwidesafaris/" target="_blank">Instagram</a> |
                    <a href="https://www.linkedin.com/company/jetwideconsortium" target="_blank">LinkedIn</a>
                </div>
                <p style="margin-top: 15px; font-size: 12px; color: #999;">
                    This is an automated message from Jetwide Travel & Safari. Please do not reply directly to this email.
                </p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}
?>