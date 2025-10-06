<?php
// Simple, clean contact form handler
// No output before this point

// Turn off all error display
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
    
    // Send business notification email
    $businessSubject = "🌍 New Trip Planning Request - $destination";
    $businessMessage = generateBusinessEmailHTML($data, $currentDate);
    $businessHeaders = generateEmailHeaders($email, $name, true);
    
    $businessSent = mail('tours@jetwide.org', $businessSubject, $businessMessage, $businessHeaders);
    
    // Send customer confirmation email
    $customerSubject = "✈️ Your Dream Trip Awaits - Jetwide Tours & Safaris";
    $customerMessage = generateCustomerEmailHTML($data, $currentDate);
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
        'message' => 'System error. Please contact tours@jetwide.org directly.'
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
 * Generate professional HTML email for business notification
 */
function generateBusinessEmailHTML($data, $currentDate) {
    $services = !empty($data['services']) ? implode(', ', $data['services']) : 'None selected';
    
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
                content: '✈️🌍';
                font-size: 2.5em;
                display: block;
                margin-bottom: 10px;
                letter-spacing: 8px;
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
                background: linear-gradient(135deg, #e74c3c, #c0392b);
                color: white;
                padding: 10px 20px;
                border-radius: 25px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 25px;
                box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
            }
            .info-card { 
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
                width: 8px;
                height: 8px;
                background: #d4af37;
                border-radius: 50%;
                margin-right: 12px;
            }
            .info-row {
                display: flex;
                margin-bottom: 12px;
                padding: 10px 0;
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
                font-weight: 500;
            }
            .destination-highlight {
                background: linear-gradient(135deg, #3498db, #2980b9);
                color: white;
                padding: 3px 8px;
                border-radius: 4px;
                font-weight: 600;
            }
            .special-requests {
                background: linear-gradient(135deg, #fff5e6 0%, #ffecd1 100%);
                border: 2px solid #d4af37;
                border-radius: 10px;
                padding: 20px;
                margin: 25px 0;
            }
            .special-requests h3 {
                color: #d4af37;
                margin-bottom: 12px;
                font-size: 16px;
            }
            .special-requests p {
                color: #5d4e37;
                font-style: italic;
                line-height: 1.6;
                background: white;
                padding: 15px;
                border-radius: 6px;
                border-left: 4px solid #d4af37;
            }
            .action-required {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                color: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                margin: 25px 0;
                box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            }
            .action-required h3 {
                margin-bottom: 8px;
                font-size: 18px;
            }
            .action-required p {
                margin: 0;
                opacity: 0.95;
            }
            .footer { 
                background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); 
                color: #ecf0f1; 
                padding: 25px 30px;
                text-align: center; 
                font-size: 12px;
                line-height: 1.8;
            }
            .footer p {
                margin: 5px 0;
                opacity: 0.9;
            }
            .timestamp {
                color: #7f8c8d;
                font-size: 12px;
                text-align: center;
                margin: 20px 0;
                font-style: italic;
                background: #ecf0f1;
                padding: 10px;
                border-radius: 6px;
            }
        </style>
    </head>
    <body>
        <div class='email-wrapper'>
            <div class='header'>
                <h1>New Trip Planning Request</h1>
                <p>A new adventure awaits your expert touch!</p>
            </div>
            
            <div class='content'>
                <div class='priority-badge'>🚨 High Priority Client</div>
                
                <div class='info-card'>
                    <h3>👤 Personal Information</h3>
                    <div class='info-row'>
                        <span class='label'>Full Name:</span>
                        <span class='value'><strong>{$data['firstName']} {$data['lastName']}</strong></span>
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
                        <span class='value'><span class='destination-highlight'>{$data['destination']}</span></span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Trip Type:</span>
                        <span class='value'>{$data['tripType']}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Departure Date:</span>
                        <span class='value'><strong>{$data['startDate']}</strong></span>
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

                <div class='action-required'>
                    <h3>⚡ Action Required</h3>
                    <p>Contact this client within 24 hours with a personalized quote and detailed itinerary.</p>
                </div>

                <div class='timestamp'>
                    📅 Request submitted on $currentDate
                </div>
            </div>
            
            <div class='footer'>
                <p>🌟 <strong>This request was submitted through the Jetwide Tours & Safaris website.</strong></p>
                <p>Please respond promptly to maintain our excellent customer service standards.</p>
                <p>💼 Jetwide Tours & Safaris - Creating Unforgettable Adventures</p>
            </div>
        </div>
    </body>
    </html>";
}

/**
 * Generate elegant customer confirmation email
 */
function generateCustomerEmailHTML($data, $currentDate) {
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
                content: '🌍✈️🦒🏔️';
                font-size: 2.2em;
                display: block;
                margin-bottom: 15px;
                letter-spacing: 8px;
            }
            .header h1 { 
                color: #2c3e50; 
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 10px;
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
                font-size: 20px;
                color: #d4af37;
                font-weight: 600;
                margin-bottom: 25px;
            }
            .main-message {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-left: 5px solid #d4af37;
                border-radius: 10px;
                padding: 25px;
                margin: 25px 0;
                box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            }
            .main-message h3 {
                color: #d4af37;
                margin-bottom: 15px;
                font-size: 18px;
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
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
                border: 2px solid #28a745;
                border-radius: 12px;
                padding: 25px;
                margin: 25px 0;
            }
            .next-steps h3 {
                color: #28a745;
                font-size: 20px;
                margin-bottom: 20px;
                font-weight: 600;
                text-align: center;
            }
            .step {
                display: flex;
                align-items: flex-start;
                margin-bottom: 15px;
                padding: 12px 0;
            }
            .step-number {
                background: linear-gradient(135deg, #28a745, #20c997);
                color: white;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 14px;
                margin-right: 15px;
                flex-shrink: 0;
                box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
            }
            .step-text {
                flex: 1;
                color: #2c3e50;
                line-height: 1.6;
            }
            .contact-card {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                margin: 30px 0;
                box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            }
            .contact-card h3 {
                margin-bottom: 15px;
                font-size: 20px;
            }
            .contact-grid {
                display: flex;
                justify-content: space-around;
                margin-top: 20px;
                flex-wrap: wrap;
            }
            .contact-item {
                margin: 8px 15px;
                font-weight: 500;
                text-align: center;
            }
            .contact-item .icon {
                display: block;
                font-size: 20px;
                margin-bottom: 5px;
            }
            .testimonial {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-left: 5px solid #17a2b8;
                border-radius: 10px;
                padding: 25px;
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
                left: 20px;
                font-family: Georgia, serif;
            }
            .testimonial-text {
                margin-left: 40px;
                line-height: 1.6;
                font-size: 16px;
            }
            .testimonial-author {
                text-align: right;
                margin-top: 15px;
                font-weight: 600;
                color: #17a2b8;
                font-size: 14px;
            }
            .footer { 
                background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); 
                color: #ecf0f1; 
                padding: 35px 30px;
                text-align: center;
            }
            .footer-brand {
                font-size: 22px;
                font-weight: 700;
                margin-bottom: 10px;
                color: #d4af37;
            }
            .footer-tagline {
                font-style: italic;
                opacity: 0.9;
                margin-bottom: 15px;
                font-size: 16px;
            }
            .footer-info {
                margin: 15px 0;
                opacity: 0.8;
                line-height: 1.6;
            }
            .social-links {
                margin-top: 20px;
            }
            .social-links span {
                display: inline-block;
                margin: 0 10px;
                font-size: 24px;
            }
            .guarantee {
                background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
                color: #2d3436;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                margin: 25px 0;
                border: 2px solid #e17055;
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
                    <h3>🎉 Welcome to Your Journey!</h3>
                    <p><strong>Congratulations on taking the first step towards your dream adventure!</strong></p>
                    <p>We have successfully received your trip planning request and our expert travel consultants are already excited to craft your perfect <strong>{$data['destination']}</strong> experience. Your journey towards unforgettable memories starts now!</p>
                </div>

                <div class='trip-summary'>
                    <h3>📋 Your Trip Summary</h3>
                    <div class='trip-detail'>
                        <span class='label'>🌍 Destination:</span>
                        <span class='value'><strong>{$data['destination']}</strong></span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>🚀 Trip Type:</span>
                        <span class='value'>{$data['tripType']}</span>
                    </div>
                    <div class='trip-detail'>
                        <span class='label'>📅 Departure:</span>
                        <span class='value'><strong>{$data['startDate']}</strong></span>
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
                        <div class='step-text'><strong>Expert Review:</strong> Our experienced travel consultants will carefully analyze your requirements and preferences within 2 hours.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>2</div>
                        <div class='step-text'><strong>Custom Itinerary:</strong> We'll craft a personalized itinerary with handpicked accommodations, activities, and unique experiences.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>3</div>
                        <div class='step-text'><strong>Detailed Proposal:</strong> You'll receive a comprehensive quote with full itinerary, pricing, and booking options within 24 hours.</div>
                    </div>
                    <div class='step'>
                        <div class='step-number'>4</div>
                        <div class='step-text'><strong>Personal Consultation:</strong> Our travel expert will call you to discuss details, answer questions, and finalize your adventure.</div>
                    </div>
                </div>

                <div class='testimonial'>
                    <div class='testimonial-text'>
                        Jetwide Tours made our Kenya safari absolutely magical! From the moment we contacted them until our safe return, everything was perfectly organized. The attention to detail and personal touch made all the difference. The memories will last a lifetime!
                    </div>
                    <div class='testimonial-author'>- Sarah & Michael, UK Travelers (★★★★★)</div>
                </div>

                <div class='guarantee'>
                    ⏰ <strong>24-Hour Response Guarantee</strong><br>
                    All inquiries receive a detailed response within 24 hours, because your adventure can't wait!
                </div>

                <div class='contact-card'>
                    <h3>📞 Need to Reach Us Immediately?</h3>
                    <p>Our travel experts are standing by to assist you:</p>
                    <div class='contact-grid'>
                        <div class='contact-item'>
                            <span class='icon'>📧</span>
                            <div>tours@jetwide.org</div>
                        </div>
                        <div class='contact-item'>
                            <span class='icon'>📞</span>
                            <div>+254 748 538 311</div>
                        </div>
                        <div class='contact-item'>
                            <span class='icon'>💬</span>
                            <div>WhatsApp Available</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='footer'>
                <div class='footer-brand'>Jetwide Tours & Safaris</div>
                <div class='footer-tagline'>Creating Unforgettable Adventures Since Day One</div>
                <div class='footer-info'>
                    Licensed by Kenya's Ministry of Tourism & Wildlife<br>
                    Westlands Square, 2nd Floor, Nairobi, Kenya
                </div>
                <div class='social-links'>
                    <span>🌐</span> <span>📘</span> <span>📸</span> <span>🐦</span> <span>💼</span>
                </div>
                <p style='margin-top: 20px; font-weight: 600;'>
                    🌟 Thank you for trusting Jetwide with your adventure dreams!
                </p>
            </div>
        </div>
    </body>
    </html>";
}
?>