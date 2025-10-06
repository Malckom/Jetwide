<?php
// Simple, clean contact form handler
// No output before this point

// Turn off all error display for production, enable for debugging
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
    echo json_encode(['success' => true, 'message' => 'PHP endpoint working!']);
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
    $required = ['firstName', 'lastName', 'email', 'destination'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
            exit();
        }
    }
    
    // Prepare data for emails
    $name = $data['firstName'] . ' ' . $data['lastName'];
    $email = $data['email'];
    $destination = $data['destination'];
    $currentDate = date('F j, Y \a\t g:i A');
    
    // Send business notification email (simple version)
    $businessSubject = "New Trip Planning Request - $destination";
    $businessMessage = generateSimpleBusinessEmail($data, $currentDate);
    $businessHeaders = generateEmailHeaders($email, $name, true);
    
    $businessSent = mail('tours@jetwide.org', $businessSubject, $businessMessage, $businessHeaders);
    
    // Send customer confirmation email (simple version)  
    $customerSubject = "Your Dream Trip Awaits - Jetwide Tours & Safaris";
    $customerMessage = generateSimpleCustomerEmail($data, $currentDate);
    $customerHeaders = generateEmailHeaders('tours@jetwide.org', 'Jetwide Tours & Safaris', true);
    
    $customerSent = mail($email, $customerSubject, $customerMessage, $customerHeaders);
    
    $sent = $businessSent || $customerSent;
    
    if ($sent) {
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your trip request has been sent successfully. We\'ll contact you within 24 hours.'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to send email. Please contact us directly at tours@jetwide.org',
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
 * Generate simple business email HTML
 */
function generateSimpleBusinessEmail($data, $currentDate) {
    $services = !empty($data['services']) ? implode(', ', $data['services']) : 'None selected';
    $firstName = htmlspecialchars($data['firstName']);
    $lastName = htmlspecialchars($data['lastName']);
    $email = htmlspecialchars($data['email']);
    $destination = htmlspecialchars($data['destination']);
    $tripType = htmlspecialchars($data['tripType'] ?? 'Not specified');
    $startDate = htmlspecialchars($data['startDate'] ?? 'Not specified');
    $duration = htmlspecialchars($data['duration'] ?? 'Not specified');
    $groupSize = htmlspecialchars($data['groupSize'] ?? 'Not specified');
    $phone = htmlspecialchars($data['phone'] ?? 'Not provided');
    $country = htmlspecialchars($data['country'] ?? 'Not specified');
    $budget = htmlspecialchars($data['budget'] ?? 'Flexible / To be discussed');
    $accommodation = htmlspecialchars($data['accommodation'] ?? 'No specific preference');
    $specialRequests = htmlspecialchars($data['specialRequests'] ?? '');
    
    $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Trip Planning Request</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d4af37; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; text-align: center; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #d4af37; background: #f9f9f9; text-align: left; }
        .field { margin-bottom: 10px; text-align: left; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>New Trip Planning Request</h1>
        </div>
        <div class='content'>
            <div class='section'>
                <h3>Personal Information</h3>
                <div class='field'><span class='label'>Name:</span> $firstName $lastName</div>
                <div class='field'><span class='label'>Email:</span> $email</div>
                <div class='field'><span class='label'>Phone:</span> $phone</div>
                <div class='field'><span class='label'>Country:</span> $country</div>
            </div>
            
            <div class='section'>
                <h3>Trip Details</h3>
                <div class='field'><span class='label'>Destination:</span> $destination</div>
                <div class='field'><span class='label'>Trip Type:</span> $tripType</div>
                <div class='field'><span class='label'>Start Date:</span> $startDate</div>
                <div class='field'><span class='label'>Duration:</span> $duration</div>
                <div class='field'><span class='label'>Group Size:</span> $groupSize</div>
                <div class='field'><span class='label'>Budget:</span> $budget</div>
                <div class='field'><span class='label'>Accommodation:</span> $accommodation</div>
                <div class='field'><span class='label'>Services:</span> $services</div>
            </div>";
    
    if (!empty($specialRequests)) {
        $html .= "<div class='section'>
                <h3>Special Requests</h3>
                <p>$specialRequests</p>
            </div>";
    }
    
    return $html . "<div class='section'>
                <h3>Action Required</h3>
                <p>Contact this client within 24 hours with a personalized quote.</p>
                <p><strong>Submitted:</strong> $currentDate</p>
            </div>
        </div>
    </div>
</body>
</html>";
}

/**
 * Generate simple customer confirmation email
 */
function generateSimpleCustomerEmail($data, $currentDate) {
    $firstName = htmlspecialchars($data['firstName']);
    $destination = htmlspecialchars($data['destination']);
    $tripType = htmlspecialchars($data['tripType'] ?? 'Not specified');
    $startDate = htmlspecialchars($data['startDate'] ?? 'Not specified');
    $duration = htmlspecialchars($data['duration'] ?? 'Not specified');
    $groupSize = htmlspecialchars($data['groupSize'] ?? 'Not specified');
    
    return "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Your Adventure Awaits</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d4af37; color: white; padding: 30px; text-align: center; }
        .content { padding: 20px; text-align: center; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #d4af37; background: #f9f9f9; text-align: left; }
        .highlight { background: #fff8e1; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: left; }
        .contact { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Your Adventure Awaits!</h1>
            <p>Thank you for choosing Jetwide Tours & Safaris</p>
        </div>
        <div class='content'>
            <h2>Dear $firstName,</h2>
            
            <div class='section'>
                <h3>Welcome to Your Journey!</h3>
                <p>We have successfully received your trip planning request and our expert travel consultants are excited to craft your perfect <strong>$destination</strong> experience!</p>
            </div>

            <div class='highlight'>
                <h3>Your Trip Summary</h3>
                <p><strong>Destination:</strong> $destination</p>
                <p><strong>Trip Type:</strong> $tripType</p>
                <p><strong>Departure:</strong> $startDate</p>
                <p><strong>Duration:</strong> $duration</p>
                <p><strong>Group Size:</strong> $groupSize</p>
            </div>

            <div class='section'>
                <h3>What Happens Next?</h3>
                <ol>
                    <li><strong>Expert Review:</strong> Our travel consultants will analyze your requirements within 2 hours.</li>
                    <li><strong>Custom Itinerary:</strong> We'll craft a personalized itinerary with handpicked experiences.</li>
                    <li><strong>Detailed Proposal:</strong> You'll receive a comprehensive quote within 24 hours.</li>
                    <li><strong>Personal Consultation:</strong> Our expert will call you to finalize your adventure.</li>
                </ol>
            </div>

            <div class='contact'>
                <h3>Need to Reach Us?</h3>
                <p><strong>Email:</strong> tours@jetwide.org</p>
                <p><strong>Phone:</strong> +254 748 538 311</p>
                <p><strong>WhatsApp Available</strong></p>
            </div>

            <p style='text-align: center; margin-top: 30px;'>
                <strong>24-Hour Response Guarantee</strong><br>
                All inquiries receive a detailed response within 24 hours!
            </p>
            
            <p style='text-align: center; font-size: 12px; color: #666; margin-top: 20px;'>
                Jetwide Tours & Safaris - Creating Unforgettable Adventures<br>
                Westlands Square, 2nd Floor, Nairobi, Kenya<br>
                Request submitted on $currentDate
            </p>
        </div>
    </div>
</body>
</html>";
}
?>