<?php
// Car Hire Enquiry Form Handler
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
    $required = ['firstName', 'lastName', 'email', 'vehicleRequired', 'branch'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
            exit();
        }
    }
    
    // Prepare data for emails
    $name = $data['firstName'] . ' ' . $data['lastName'];
    $email = $data['email'];
    $vehicleRequired = $data['vehicleRequired'];
    $branch = $data['branch'];
    $currentDate = date('F j, Y \a\t g:i A');
    
    // Send business notification email
    $businessSubject = "New Car Hire Enquiry - $vehicleRequired from $branch";
    $businessMessage = generateBusinessEmail($data, $currentDate);
    $businessHeaders = generateEmailHeaders($email, $name, true);
    
    $businessSent = mail('tours@jetwide.org', $businessSubject, $businessMessage, $businessHeaders);
    
    // Send customer confirmation email
    $customerSubject = "Your Car Hire Enquiry - Jetwide Car Hire Services";
    $customerMessage = generateCustomerEmail($data, $currentDate);
    $customerHeaders = generateEmailHeaders('tours@jetwide.org', 'Jetwide Car Hire Services', true);
    
    $customerSent = mail($email, $customerSubject, $customerMessage, $customerHeaders);
    
    $sent = $businessSent || $customerSent;
    
    if ($sent) {
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your car hire enquiry has been sent successfully. We\'ll contact you within 24 hours with a quote.'
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
 * Generate business email HTML
 */
function generateBusinessEmail($data, $currentDate) {
    $firstName = htmlspecialchars($data['firstName']);
    $lastName = htmlspecialchars($data['lastName']);
    $email = htmlspecialchars($data['email']);
    $phone = htmlspecialchars($data['phone'] ?? 'Not provided');
    $companyName = htmlspecialchars($data['companyName'] ?? 'Individual');
    $branch = htmlspecialchars($data['branch']);
    $vehicleRequired = htmlspecialchars($data['vehicleRequired']);
    $startDate = htmlspecialchars($data['startDate'] ?? 'Not specified');
    $duration = htmlspecialchars($data['duration'] ?? 'Not specified');
    $serviceType = htmlspecialchars($data['serviceType'] ?? 'Not specified');
    $hireType = htmlspecialchars($data['hireType'] ?? 'Not specified');
    $additionalInfo = htmlspecialchars($data['additionalInfo'] ?? 'None');
    
    $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>New Car Hire Enquiry</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #14132A; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #FE9900; background: #f9f9f9; }
        .field { margin-bottom: 10px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>New Car Hire Enquiry</h1>
        </div>
        <div class='content'>
            <div class='section'>
                <h3>Personal Information</h3>
                <div class='field'><span class='label'>Name:</span> $firstName $lastName</div>
                <div class='field'><span class='label'>Email:</span> $email</div>
                <div class='field'><span class='label'>Phone:</span> $phone</div>
                <div class='field'><span class='label'>Company:</span> $companyName</div>
            </div>
            
            <div class='section'>
                <h3>Car Hire Details</h3>
                <div class='field'><span class='label'>Branch:</span> $branch</div>
                <div class='field'><span class='label'>Vehicle Required:</span> $vehicleRequired</div>
                <div class='field'><span class='label'>Hire Type:</span> $hireType</div>
                <div class='field'><span class='label'>Service Type:</span> $serviceType</div>
                <div class='field'><span class='label'>Start Date:</span> $startDate</div>
                <div class='field'><span class='label'>Duration:</span> $duration</div>
            </div>";
    
    if (!empty($additionalInfo) && $additionalInfo !== 'None') {
        $html .= "<div class='section'>
                <h3>Additional Information</h3>
                <p>$additionalInfo</p>
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
 * Generate customer confirmation email
 */
function generateCustomerEmail($data, $currentDate) {
    $firstName = htmlspecialchars($data['firstName']);
    $vehicleRequired = htmlspecialchars($data['vehicleRequired']);
    $branch = htmlspecialchars($data['branch']);
    $hireType = htmlspecialchars($data['hireType'] ?? 'Not specified');
    $startDate = htmlspecialchars($data['startDate'] ?? 'Not specified');
    $duration = htmlspecialchars($data['duration'] ?? 'Not specified');
    
    return "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Your Car Hire Enquiry</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FE9900; color: white; padding: 30px; text-align: center; }
        .content { padding: 20px; }
        .section { margin-bottom: 20px; padding: 15px; border-left: 3px solid #FE9900; background: #f9f9f9; }
        .highlight { background: #fff8e1; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .contact { background: #14132A; color: white; padding: 20px; text-align: center; border-radius: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Your Car Awaits!</h1>
            <p>Thank you for choosing Jetwide Car Hire Services</p>
        </div>
        <div class='content'>
            <h2>Dear $firstName,</h2>
            
            <div class='section'>
                <h3>Welcome to Reliable Car Hire!</h3>
                <p>We have successfully received your car hire enquiry and our team is excited to provide you with the perfect vehicle for your journey!</p>
            </div>

            <div class='highlight'>
                <h3>Your Enquiry Summary</h3>
                <p><strong>Vehicle Required:</strong> $vehicleRequired</p>
                <p><strong>Branch:</strong> $branch</p>
                <p><strong>Hire Type:</strong> $hireType</p>
                <p><strong>Start Date:</strong> $startDate</p>
                <p><strong>Duration:</strong> $duration</p>
            </div>

            <div class='section'>
                <h3>What Happens Next?</h3>
                <ol>
                    <li><strong>Quick Review:</strong> Our team will review your requirements within 2 hours.</li>
                    <li><strong>Vehicle Availability:</strong> We'll confirm vehicle availability and pricing.</li>
                    <li><strong>Quote Delivery:</strong> You'll receive a detailed quote within 24 hours.</li>
                    <li><strong>Personal Consultation:</strong> Our expert will call you to finalize the booking.</li>
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
                All enquiries receive a detailed response within 24 hours!
            </p>
            
            <p style='text-align: center; font-size: 12px; color: #666; margin-top: 20px;'>
                Jetwide Car Hire Services - Drive Kenya Your Way<br>
                Westlands Square, 2nd Floor, Nairobi, Kenya<br>
                Enquiry submitted on $currentDate
            </p>
        </div>
    </div>
</body>
</html>";
}
?>
