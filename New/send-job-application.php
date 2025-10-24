<?php
// Job Application Form Handler for Serbia Jobs
// Sends to recruitment@jetwide.org

// Turn off all error display for production
ini_set('display_errors', 0);
error_reporting(0);

// Set JSON header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

// Test endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['success' => true, 'message' => 'Job application PHP endpoint working!']);
    exit();
}

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    // Get and validate input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit();
    }
    
    // Validate required fields
    $required = ['name', 'passportNo', 'email', 'phone', 'position'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
            exit();
        }
    }
    
    // Prepare data for emails
    $name = $data['name'];
    $email = $data['email'];
    $position = $data['position'];
    $currentDate = date('F j, Y \a\t g:i A');
    
    // Send business notification email to recruitment@jetwide.org
    $businessSubject = "New Job Application - $position - Serbia";
    $businessMessage = generateRecruitmentEmail($data, $currentDate);
    $businessHeaders = generateEmailHeaders($email, $name, true);
    
    $businessSent = mail('recruitment@jetwide.org', $businessSubject, $businessMessage, $businessHeaders);
    
    // Send customer confirmation email
    $customerSubject = "Application Received - $position | Jetwide Recruitment";
    $customerMessage = generateApplicantConfirmationEmail($data, $currentDate);
    $customerHeaders = generateEmailHeaders('recruitment@jetwide.org', 'Jetwide Recruitment', true);
    
    $customerSent = mail($email, $customerSubject, $customerMessage, $customerHeaders);
    
    $sent = $businessSent || $customerSent;
    
    if ($sent) {
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your application has been submitted successfully. We will review it and contact you within 24 hours.'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to send email. Please contact us directly at recruitment@jetwide.org',
            'debug' => ['business' => $businessSent, 'customer' => $customerSent]
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'System error: ' . $e->getMessage()
    ]);
}

/**
 * Generate email headers
 */
function generateEmailHeaders($fromEmail, $fromName, $isHTML = true) {
    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    if ($isHTML) {
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    } else {
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    }
    $headers .= "X-Priority: 1\r\n";
    $headers .= "X-MSMail-Priority: High\r\n";
    return $headers;
}

/**
 * Generate recruitment email HTML for business
 */
function generateRecruitmentEmail($data, $currentDate) {
    $name = htmlspecialchars($data['name']);
    $passportNo = htmlspecialchars($data['passportNo']);
    $issueDate = htmlspecialchars($data['issueDate'] ?? 'Not provided');
    $expiryDate = htmlspecialchars($data['expiryDate'] ?? 'Not provided');
    $placeOfBirth = htmlspecialchars($data['placeOfBirth'] ?? 'Not provided');
    $dob = htmlspecialchars($data['dob'] ?? 'Not provided');
    $phone = htmlspecialchars($data['phone']);
    $idNo = htmlspecialchars($data['idNo'] ?? 'Not provided');
    $address = htmlspecialchars($data['address'] ?? 'Not provided');
    $email = htmlspecialchars($data['email']);
    $education = htmlspecialchars($data['education'] ?? 'Not provided');
    $period = htmlspecialchars($data['period'] ?? 'Not provided');
    $occupation = htmlspecialchars($data['occupation'] ?? 'Not provided');
    $refusedEntry = htmlspecialchars($data['refusedEntry'] ?? 'Not specified');
    $countryRefused = htmlspecialchars($data['countryRefused'] ?? 'N/A');
    $reason = htmlspecialchars($data['reason'] ?? 'N/A');
    $position = htmlspecialchars($data['position']);
    $additionalInfo = htmlspecialchars($data['additionalInfo'] ?? 'None provided');
    
    $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Job Application - Serbia</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; text-align: center; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; }
        .header { background: #FE9900; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; text-align: center; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #FE9900; background: #f9f9f9; text-align: left; }
        .field { margin-bottom: 10px; text-align: left; }
        .label { font-weight: bold; color: #14132A; }
        .alert { background: #fff8e1; padding: 15px; border-left: 3px solid #d4af37; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🌍 New Job Application - Serbia</h1>
            <p>Position: $position</p>
        </div>
        <div class='content'>
            <div class='alert'>
                <strong>⏰ Action Required:</strong> Review and contact applicant within 24 hours
            </div>

            <div class='section'>
                <h3>📋 Personal Information</h3>
                <div class='field'><span class='label'>Name:</span> $name</div>
                <div class='field'><span class='label'>Email:</span> $email</div>
                <div class='field'><span class='label'>Phone:</span> $phone</div>
                <div class='field'><span class='label'>ID No:</span> $idNo</div>
                <div class='field'><span class='label'>Address:</span> $address</div>
                <div class='field'><span class='label'>Date of Birth:</span> $dob</div>
                <div class='field'><span class='label'>Place of Birth:</span> $placeOfBirth</div>
            </div>
            
            <div class='section'>
                <h3>🛂 Passport Information</h3>
                <div class='field'><span class='label'>Passport No:</span> $passportNo</div>
                <div class='field'><span class='label'>Issue Date:</span> $issueDate</div>
                <div class='field'><span class='label'>Expiry Date:</span> $expiryDate</div>
            </div>

            <div class='section'>
                <h3>🎓 Education & Experience</h3>
                <div class='field'><span class='label'>Education:</span> $education</div>
                <div class='field'><span class='label'>Period:</span> $period</div>
                <div class='field'><span class='label'>Current Occupation:</span> $occupation</div>
            </div>

            <div class='section'>
                <h3>✈️ Travel History</h3>
                <div class='field'><span class='label'>Ever Refused Entry:</span> $refusedEntry</div>
                <div class='field'><span class='label'>Country (if yes):</span> $countryRefused</div>
                <div class='field'><span class='label'>Reason (if yes):</span> $reason</div>
            </div>

            <div class='section'>
                <h3>💼 Position Details</h3>
                <div class='field'><span class='label'>Position Applied For:</span> $position</div>
                <div class='field'><span class='label'>Additional Information:</span><br>$additionalInfo</div>
            </div>

            <div class='section'>
                <h3>📅 Application Details</h3>
                <p><strong>Submitted:</strong> $currentDate</p>
                <p><strong>Status:</strong> <span style='color: #FE9900; font-weight: bold;'>Pending Review</span></p>
            </div>
        </div>
    </div>
</body>
</html>";
    
    return $html;
}

/**
 * Generate applicant confirmation email
 */
function generateApplicantConfirmationEmail($data, $currentDate) {
    $name = htmlspecialchars($data['name']);
    $position = htmlspecialchars($data['position']);
    
    return "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Application Received - Jetwide Recruitment</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FE9900; color: white; padding: 30px; text-align: center; }
        .content { padding: 20px; text-align: center; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #FE9900; background: #f9f9f9; text-align: left; }
        .highlight { background: #fff8e1; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: left; }
        .contact { background: #14132A; color: white; padding: 20px; text-align: center; border-radius: 5px; }
        .steps { text-align: left; padding-left: 20px; }
        .checkmark { color: #28a745; font-size: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>✅ Application Received!</h1>
            <p>Your Journey to Serbia Begins Here</p>
        </div>
        <div class='content'>
            <h2>Dear $name,</h2>
            
            <div class='section'>
                <h3>🎉 Thank You for Applying!</h3>
                <p>We have successfully received your application for the <strong>$position</strong> position in Serbia through Jetwide Travel & Safari.</p>
            </div>

            <div class='highlight'>
                <h3>📋 Application Summary</h3>
                <p><strong>Position:</strong> $position</p>
                <p><strong>Location:</strong> Serbia</p>
                <p><strong>Submitted:</strong> $currentDate</p>
                <p><strong>Status:</strong> <span style='color: #FE9900; font-weight: bold;'>Under Review</span></p>
            </div>

            <div class='section'>
                <h3>🔄 What Happens Next?</h3>
                <div class='steps'>
                    <p><span class='checkmark'>✓</span> <strong>Application Review:</strong> Our recruitment team will carefully review your application within 24 hours.</p>
                    <p><span class='checkmark'>✓</span> <strong>Initial Contact:</strong> If your profile matches our requirements, we'll contact you for a preliminary discussion.</p>
                    <p><span class='checkmark'>✓</span> <strong>Interview & Assessment:</strong> Qualified candidates will be invited for interviews and group sessions.</p>
                    <p><span class='checkmark'>✓</span> <strong>Documentation:</strong> We'll guide you through all necessary paperwork and visa processes.</p>
                    <p><span class='checkmark'>✓</span> <strong>Deployment:</strong> Final contract signing and travel arrangements to Serbia.</p>
                </div>
            </div>

            <div class='section'>
                <h3>📌 Important Notes</h3>
                <ul>
                    <li>Keep your phone and email accessible - we may reach out soon!</li>
                    <li>Start gathering your documents (passport, certificates, police clearance)</li>
                    <li>All legitimate job opportunities through Jetwide are properly documented</li>
                    <li>We will never ask for payment before a formal offer</li>
                </ul>
            </div>

            <div class='contact'>
                <h3>📞 Need Assistance?</h3>
                <p><strong>Email:</strong> recruitment@jetwide.org</p>
                <p><strong>Phone:</strong> +254 748 538 311</p>
                <p><strong>Office:</strong> Westlands Square, 2nd Floor, Nairobi</p>
                <p><strong>Hours:</strong> Monday - Friday, 9 AM - 5 PM</p>
                <p style='margin-top: 15px;'><strong>💬 WhatsApp Available</strong></p>
            </div>

            <p style='text-align: center; margin-top: 30px;'>
                <strong>🌟 Why Choose Jetwide?</strong><br>
                Licensed recruitment agency | 100% Legal Compliance | Proven Track Record
            </p>
            
            <p style='text-align: center; font-size: 12px; color: #666; margin-top: 20px;'>
                Jetwide Travel & Safari - Your Trusted Partner for Overseas Employment<br>
                Licensed under Kenya's Labour Institutions Act (2007)<br>
                Application received on $currentDate
            </p>
        </div>
    </div>
</body>
</html>";
}
?>
