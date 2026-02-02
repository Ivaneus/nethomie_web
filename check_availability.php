<?php
// Set headers to handle AJAX requests
header('Content-Type: application/json');

// Initialize response array
$response = [
    'status' => '',
    'message' => ''
];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
    
    // Validate form data
    $errors = [];
    
    // Validate street address
    if (empty($address)) {
        $errors[] = 'Street address is required.';
    } elseif (strlen($address) < 5 || strlen($address) > 100) {
        $errors[] = 'Street address must be between 5 and 100 characters.';
    }
    
    // Validate city
    if (empty($city)) {
        $errors[] = 'City is required.';
    } elseif (strlen($city) < 2 || strlen($city) > 50) {
        $errors[] = 'City name must be between 2 and 50 characters.';
    } elseif (!preg_match('/^[a-zA-Z\s\-\.]+$/', $city)) {
        $errors[] = 'City name contains invalid characters.';
    }
    
    // Validate ZIP code - US format (5 digits or 5+4)
    if (empty($zip)) {
        $errors[] = 'ZIP code is required.';
    } elseif (!preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
        $errors[] = 'Please enter a valid US ZIP code (e.g., 12345 or 12345-6789).';
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['status'] = 'error';
        $response['message'] = implode(' ', $errors);
    } else {
        // No validation errors - always return the "service not available" message
        $response['status'] = 'unavailable';
        $response['message'] = "We're sorry, but NetHomie NOC services are not currently available in your area. We're expanding our network and hope to serve your location in the future. Please check back later or contact our office for more information.";
    }
} else {
    // Not a POST request
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Return JSON response
echo json_encode($response);
?> 