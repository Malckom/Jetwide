<?php
/**
 * Jetwide Contact Form Email Handler
 * WordPress-compatible PHP email sender for webmail SMTP
 */

// Clean start - no HTML output before this point
ob_start();

// Disable error display to prevent HTML interference
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set content type for JSON responses
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Clear any output buffer to ensure clean JSON
ob_clean();

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Add a simple GET test endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true, 
        'message' => 'Contact form endpoint is working!',
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => phpversion()
    ]);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if JSON decoding failed
if (json_last_error() !== JSON_ERROR_NONE) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data received: ' . json_last_error_msg()]);
    exit();
}

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'destination', 'tripType', 'startDate'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($data[$field])) {
        $errors[] = ucfirst(str_replace(['_', 'Type'], [' ', ' Type'], $field)) . ' is required';
    }
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please fill in all required fields: ' . implode(', ', $errors)
    ]);
    exit();
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit();
}

// Sanitize input data
$sanitized_data = [];
foreach ($data as $key => $value) {
    if (is_array($value)) {
        $sanitized_data[$key] = array_map('htmlspecialchars', $value);
    } else {
        $sanitized_data[$key] = htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

try {
    // Send business notification email
    $business_success = sendBusinessEmail($sanitized_data);
    
    // Send customer confirmation email  
    $customer_success = sendCustomerEmail($sanitized_data);
    
    // Clean output buffer before sending JSON
    ob_clean();
    
    if ($business_success || $customer_success) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your trip planning request has been sent successfully. We\'ll contact you within 24 hours with a personalized itinerary.',
            'debug' => [
                'business_email' => $business_success,
                'customer_email' => $customer_success
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to send emails. Please try contacting us directly at tours@jetwide.org or +254 748 538 311.'
        ]);
    }

} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error sending your request. Please try again or contact us directly at tours@jetwide.org or +254 748 538 311.',
        'error' => $e->getMessage()
    ]);
}

/**
 * Send business notification email
 */
function sendBusinessEmail($data) {
    $to = 'tours@jetwide.org';
    $subject = '🌍 New Trip Planning Request - ' . $data['destination'];
    $message = generateBusinessEmailHTML($data);
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: Jetwide Website <noreply@jetwide.org>',
        'Reply-To: ' . $data['firstName'] . ' ' . $data['lastName'] . ' <' . $data['email'] . '>',
        'X-Priority: 1',
        'X-MSMail-Priority: High'
    ];

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Send customer confirmation email
 */
function sendCustomerEmail($data) {
    $to = $data['email'];
    $subject = '✈️ Your Dream Trip Awaits - Jetwide Tours & Safaris';
    $message = generateCustomerEmailHTML($data);
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: Jetwide Tours & Safaris <tours@jetwide.org>',
        'Reply-To: tours@jetwide.org',
        'X-Priority: 3'
    ];

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Generate HTML email for business notification
 */
function generateBusinessEmailHTML($data) {
    $services = !empty($data['services']) ? implode(', ', $data['services']) : 'None selected';
    $currentDate = date('F j, Y \a\t g:i A');
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>New Trip Planning Request</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                line-height: 1.6; 
                color: #2c3e50; 
                background-color: #f8f9fa;
            }
            .email-wrapper { 
                max-width: 650px; 
                margin: 20px auto; 
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header { 
                background: linear-gradient(135deg, #d4af37 0%, #f4e4a1 100%); 
                padding: 30px 25px;
                text-align: center;
                position: relative;
            }
            .header::before {
                content: '✈️';
                font-size: 2.5em;
                display: block;
                margin-bottom: 10px;
            }
            .header h1 { 
                color: #2c3e50; 
                font-size: 24px;
                font-weight: 600;
                margin: 0;
            }
            .header p {
                color: #5d4e37;
                font-size: 14px;
                margin: 8px 0 0 0;
                opacity: 0.9;
            }
            .content { 
                padding: 35px 30px;
                background: #ffffff;
            }
            .priority-badge {
                display: inline-block;
                background: #e74c3c;
                color: white;
                padding: 8px 16px;
                border-radius: 25px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 25px;
            }
            .info-card { 
                background: #f8f9fa;
                border-left: 5px solid #d4af37;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 25px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            .info-card h3 { 
                color: #d4af37; 
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
            }
            .info-card h3::before {
                content: '';
                width: 6px;
                height: 6px;
                background: #d4af37;
                border-radius: 50%;
                margin-right: 10px;
            }
            .info-row {
                display: flex;
                margin-bottom: 12px;
                padding: 8px 0;
                border-bottom: 1px solid #ecf0f1;
            }
            .info-row:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
            .label { 
                font-weight: 600; 
                color: #34495e; 
                min-width: 140px;
                font-size: 14px;
            }
            .value {
                color: #2c3e50;
                font-size: 14px;
                flex: 1;
            }
            .special-requests {
                background: linear-gradient(135deg, #fff5e6 0%, #ffecd1 100%);
                border: 1px solid #d4af37;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
            }
            .special-requests h3 {
                color: #d4af37;
                margin-bottom: 12px;
            }
            .special-requests p {
                color: #5d4e37;
                font-style: italic;
                line-height: 1.6;
            }
            .contact-cta {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
                padding: 20px;
                border-radius: 8px;
                text-align: center;
                margin: 25px 0;
            }
            .contact-cta h3 {
                margin-bottom: 8px;
                font-size: 16px;
            }
            .contact-cta p {
                margin: 0;
                opacity: 0.9;
            }
            .footer { 
                background: #2c3e50; 
                color: #ecf0f1; 
                padding: 20px 30px;
                text-align: center; 
                font-size: 12px;
                line-height: 1.5;
            }
            .footer p {
                margin: 0;
                opacity: 0.8;
            }
            .timestamp {
                color: #7f8c8d;
                font-size: 12px;
                text-align: center;
                margin: 15px 0;
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <div class='email-wrapper'>
            <div class='header'>
                <h1>New Trip Planning Request</h1>
                <p>A new adventure awaits planning!</p>
            </div>
            
            <div class='content'>
                <div class='priority-badge'>🚨 High Priority</div>
                
                <div class='info-card'>
                    <h3>👤 Personal Information</h3>
                    <div class='info-row'>
                        <span class='label'>Full Name:</span>
                        <span class='value'>{$data['firstName']} {$data['lastName']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Email Address:</span>
                        <span class='value'>{$data['email']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Phone Number:</span>
                        <span class='value'>" . ($data['phone'] ?: 'Not provided') . "</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Country:</span>
                        <span class='value'>" . ($data['country'] ?: 'Not specified') . "</span>
                    </div>
                </div>

                <div class='info-card'>
                    <h3>🌍 Trip Details</h3>
                    <div class='info-row'>
                        <span class='label'>Destination:</span>
                        <span class='value'><strong>{$data['destination']}</strong></span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Trip Type:</span>
                        <span class='value'>{$data['tripType']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Departure Date:</span>
                        <span class='value'>{$data['startDate']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Duration:</span>
                        <span class='value'>{$data['duration']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Group Size:</span>
                        <span class='value'>{$data['groupSize']} travelers</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Budget Range:</span>
                        <span class='value'>" . ($data['budget'] ?: 'Flexible / To be discussed') . "</span>
                    </div>
                </div>

                <div class='info-card'>
                    <h3>🏨 Preferences & Services</h3>
                    <div class='info-row'>
                        <span class='label'>Accommodation:</span>
                        <span class='value'>" . ($data['accommodation'] ?: 'No specific preference') . "</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Additional Services:</span>
                        <span class='value'>$services</span>
                    </div>
                </div>

                " . (!empty($data['specialRequests']) ? "
                <div class='special-requests'>
                    <h3>💬 Special Requests & Notes</h3>
                    <p>\"{$data['specialRequests']}\"</p>
                </div>
                " : "") . "

                <div class='contact-cta'>
                    <h3>📞 Follow Up Required</h3>
                    <p>Contact this client within 24 hours to provide a personalized quote and itinerary.</p>
                </div>

                <div class='timestamp'>
                    Request submitted on $currentDate
                </div>
            </div>
            
            <div class='footer'>
                <p>🌟 This request was submitted through the Jetwide Tours & Safaris website contact form.</p>
                <p>Please respond promptly to maintain our excellent customer service standards.</p>
            </div>
        </div>
    </body>
    </html>";
}

/**
 * Generate HTML email for customer confirmation
 */
function generateCustomerEmailHTML($data) {
    $currentDate = date('F j, Y');
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Your Adventure Awaits - Jetwide Tours</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                line-height: 1.7; 
                color: #2c3e50; 
                background-color: #f0f2f5;
            }
            .email-wrapper { 
                max-width: 650px; 
                margin: 20px auto; 
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .header { 
                background: linear-gradient(135deg, #d4af37 0%, #f4e4a1 50%, #fff8e1 100%); 
                padding: 40px 30px;
                text-align: center;
                position: relative;
            }
            .header::before {
                content: '🌍✈️🦒';
                font-size: 2.2em;
                display: block;
                margin-bottom: 15px;
                letter-spacing: 10px;
            }
            .header h1 { 
                color: #2c3e50; 
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 8px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .header p {
                color: #5d4e37;
                font-size: 16px;
                font-weight: 500;
                opacity: 0.9;
            }
            .content { 
                padding: 40px 35px;
                background: #ffffff;
            }
            .greeting {
                font-size: 18px;
                color: #d4af37;
                font-weight: 600;
                margin-bottom: 20px;
            }
            .main-message {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-left: 5px solid #d4af37;
                border-radius: 10px;
                padding: 25px;
                margin: 25px 0;
                box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            }
            .trip-summary {
                background: linear-gradient(135deg, #fff5e6 0%, #ffecd1 100%);
                border: 2px solid #d4af37;
                border-radius: 12px;
                padding: 25px;
                margin: 25px 0;
                text-align: center;
            }
            .trip-summary h3 {
                color: #d4af37;
                font-size: 20px;
                margin-bottom: 15px;
                font-weight: 600;
            }
            .trip-detail {
                background: #ffffff;
                padding: 12px 20px;
                margin: 8px 0;
                border-radius: 6px;
                border-left: 3px solid #d4af37;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .trip-detail .label {
                font-weight: 600;
                color: #5d4e37;
            }
            .trip-detail .value {
                color: #2c3e50;
                font-weight: 500;
            }
            .next-steps {
                background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
                border: 1px solid #28a745;
                border-radius: 10px;
                padding: 25px;
                margin: 25px 0;
            }
            .next-steps h3 {
                color: #28a745;
                font-size: 18px;
                margin-bottom: 15px;
                font-weight: 600;
            }
            .step {
                display: flex;
                align-items: flex-start;
                margin-bottom: 12px;
                padding: 8px 0;
            }
            .step-number {
                background: #28a745;
                color: white;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 12px;
                margin-right: 12px;
                flex-shrink: 0;
            }
            .step-text {
                flex: 1;
                color: #2c3e50;
                line-height: 1.5;
            }
            .contact-card {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
                padding: 25px;
                border-radius: 12px;
                text-align: center;
                margin: 25px 0;
                box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
            }
            .contact-card h3 {
                margin-bottom: 15px;
                font-size: 18px;
            }
            .contact-item {
                display: inline-block;
                margin: 8px 15px;
                font-weight: 500;
            }
            .contact-item::before {
                margin-right: 8px;
            }
            .email-contact::before { content: '📧'; }
            .phone-contact::before { content: '📞'; }
            .whatsapp-contact::before { content: '💬'; }
            .testimonial {
                background: #f8f9fa;
                border-left: 5px solid #17a2b8;
                border-radius: 8px;
                padding: 20px;
                margin: 25px 0;
                font-style: italic;
                color: #495057;
                position: relative;
            }
            .testimonial::before {
                content: '"';
                font-size: 60px;
                color: #17a2b8;
                position: absolute;
                top: -10px;
                left: 15px;
                font-family: Georgia, serif;
            }
            .testimonial-text {
                margin-left: 40px;
                line-height: 1.6;
            }
            .testimonial-author {
                text-align: right;
                margin-top: 10px;
                font-weight: 600;
                color: #17a2b8;
            }
            .footer { 
                background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); 
                color: #ecf0f1; 
                padding: 30px;
                text-align: center;
            }
            .footer-brand {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 10px;
                color: #d4af37;
            }
            .footer-tagline {
                font-style: italic;
                opacity: 0.9;
                margin-bottom: 15px;
            }
            .social-links {
                margin-top: 15px;
            }
            .social-links span {
                display: inline-block;
                margin: 0 8px;
                font-size: 18px;
            }
            .signature {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ecf0f1;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class='email-wrapper'>
            <div class='header'>
                <h1>Your Adventure Awaits!</h1>
                <p>Thank you for choosing Jetwide Tours & Safaris</p>
            </div>
            
            <div class='content'>
                <div class='greeting'>
                    Dear {$data['firstName']},
                </div>
                
                <div class='main-message'>
                    <p><strong>🎉 Congratulations on taking the first step towards your dream adventure!</strong></p>
                    <p>We have successfully received your trip planning request and our expert travel consultants are already excited to craft your perfect <strong>{$data['destination']}</strong> experience. Your journey towards unforgettable memories starts now!</p>
                </div>

                <div class='trip-summary'>
                    <h3>📋 Your Trip Summary</h3>
                    <div class='trip-detail'>
                        <span class='label'>🌍 Destination:</span>
                        <span class='value'>{$data['destination']}</span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>🚀 Trip Type:</span>
                        <span class='value'>{$data['tripType']}</span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>📅 Departure:</span>
                        <span class='value'>{$data['startDate']}</span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>⏱️ Duration:</span>
                        <span class='value'>{$data['duration']}</span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>👥 Group Size:</span>
                        <span class='value'>{$data['groupSize']} travelers</span>
                    </div>
                </div>

                <div class='next-steps'>
                    <h3>🚀 What Happens Next?</h3>
                    <div class='step'>
                        <div class='step-number'>1</div>
                        <div class='step-text'><strong>Expert Review:</strong> Our experienced travel consultants will carefully analyze your requirements and preferences.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>2</div>
                        <div class='step-text'><strong>Custom Itinerary:</strong> We'll craft a personalized itinerary with handpicked accommodations, activities, and experiences.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>3</div>
                        <div class='step-text'><strong>Detailed Proposal:</strong> You'll receive a comprehensive quote with full itinerary within 24 hours via email.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>4</div>
                        <div class='step-text'><strong>Personal Consultation:</strong> Our travel expert will call you to discuss details and answer any questions.</div>
                    </div>
                </div>

                <div class='testimonial'>
                    <div class='testimonial-text'>
                        Jetwide Tours made our Kenya safari absolutely magical! From the moment we contacted them until our safe return, everything was perfectly organized. The memories will last a lifetime!
                    </div>
                    <div class='testimonial-author'>- Sarah & Michael, UK Travelers</div>
                </div>

                <div class='contact-card'>
                    <h3>📞 Need to Reach Us?</h3>
                    <p>Our travel experts are standing by to assist you:</p>
                    <div style='margin-top: 15px;'>
                        <div class='contact-item email-contact'>tours@jetwide.org</div>
                        <div class='contact-item phone-contact'>+254 748 538 311</div>
                        <div class='contact-item whatsapp-contact'>WhatsApp Available</div>
                    </div>
                </div>

                <p style='margin-top: 25px; color: #7f8c8d; font-style: italic; text-align: center;'>
                    <strong>Response Guarantee:</strong> All inquiries receive a detailed response within 24 hours, because your adventure can't wait! ⏰
                </p>
            </div>
            
            <div class='footer'>
                <div class='footer-brand'>Jetwide Tours & Safaris</div>
                <div class='footer-tagline'>Creating Unforgettable Adventures Since Day One</div>
                <p>Licensed by Kenya's Ministry of Tourism & Wildlife</p>
                <div class='social-links'>
                    <span>🌐</span> <span>📘</span> <span>📸</span> <span>🐦</span> <span>💼</span>
                </div>
                <div class='signature'>
                    <p>🌟 Thank you for trusting Jetwide with your adventure dreams!</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
}

/**
 * Generate plain text email for fallback
 */
function generateBusinessEmailText($data) {
    $services = !empty($data['services']) ? implode(', ', $data['services']) : 'None selected';
    
    $message = "NEW TRIP PLANNING REQUEST\n\n";
    $message .= "=== PERSONAL INFORMATION ===\n";
    $message .= "Name: {$data['firstName']} {$data['lastName']}\n";
    $message .= "Email: {$data['email']}\n";
    $message .= "Phone: " . ($data['phone'] ?: 'Not provided') . "\n";
    $message .= "Country: " . ($data['country'] ?: 'Not specified') . "\n\n";
    
    $message .= "=== TRIP DETAILS ===\n";
    $message .= "Destination: {$data['destination']}\n";
    $message .= "Trip Type: {$data['tripType']}\n";
    $message .= "Start Date: {$data['startDate']}\n";
    $message .= "Duration: {$data['duration']}\n";
    $message .= "Group Size: {$data['groupSize']}\n";
    $message .= "Budget: " . ($data['budget'] ?: 'Not specified') . "\n\n";
    
    $message .= "=== PREFERENCES ===\n";
    $message .= "Accommodation: " . ($data['accommodation'] ?: 'Not specified') . "\n";
    $message .= "Additional Services: $services\n\n";
    
    if (!empty($data['specialRequests'])) {
        $message .= "=== SPECIAL REQUESTS ===\n";
        $message .= $data['specialRequests'] . "\n\n";
    }
    
    $message .= "---\nThis request was sent from the Jetwide website contact form.";
    
    return $message;
}

// End output buffering and flush
ob_end_flush();
?>