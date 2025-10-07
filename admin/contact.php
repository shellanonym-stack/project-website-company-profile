<?php
// contact.php - Updated untuk database
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST['subject'] ?? '', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Valid email is required';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Save to database
            $stmt = $db->prepare("
                INSERT INTO contacts (name, email, phone, subject, message, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name, $email, $phone, $subject, $message, $_SERVER['REMOTE_ADDR']
            ]);
            
            // Send email notification (optional)
            $to = "contact@komodoindustrialindonesia.com";
            $email_subject = "New Contact Form: " . $subject;
            $email_body = "
            New contact form submission:
            
            Name: $name
            Email: $email
            Phone: $phone
            Subject: $subject
            
            Message:
            $message
            
            IP: {$_SERVER['REMOTE_ADDR']}
            Time: " . date('Y-m-d H:i:s') . "
            ";
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            
            // Uncomment to actually send email
            // mail($to, $email_subject, $email_body, $headers);
            
            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully.'
            ]);
            
        } catch (Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.'
            ]);
        }
    } else {
        // Return error response
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>