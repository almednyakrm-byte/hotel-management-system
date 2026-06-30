<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Define validation rules
$validationRules = [
    'hotelID' => 'required|integer',
    'guestName' => 'required|string',
    'checkInDate' => 'required|date',
    'checkOutDate' => 'required|date',
];

// Validate input data
foreach ($validationRules as $field => $rules) {
    $inputData[$field] = trim($inputData[$field]);
    if (!preg_match('/^' . implode('|', $rules) . '$/', $inputData[$field])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
}

// Sanitize input data
$inputData['hotelID'] = (int) $inputData['hotelID'];
$inputData['checkInDate'] = date('Y-m-d', strtotime($inputData['checkInDate']));
$inputData['checkOutDate'] = date('Y-m-d', strtotime($inputData['checkOutDate']));

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get all bookings
    $stmt = $pdo->prepare('SELECT * FROM حجوزات_الفندق');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return bookings
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($bookings);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Insert new booking
    $stmt = $pdo->prepare('INSERT INTO حجوزات_الفندق (hotelID, guestName, checkInDate, checkOutDate) VALUES (:hotelID, :guestName, :checkInDate, :checkOutDate)');
    $stmt->execute($inputData);

    // Return new booking ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['bookingID' => $pdo->lastInsertId()]);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Update existing booking
    $stmt = $pdo->prepare('UPDATE حجوزات_الفندق SET hotelID = :hotelID, guestName = :guestName, checkInDate = :checkInDate, checkOutDate = :checkOutDate WHERE bookingID = :bookingID');
    $stmt->execute(array_merge($inputData, ['bookingID' => (int) $_GET['bookingID']]));

    // Return updated booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($inputData);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Delete existing booking
    $stmt = $pdo->prepare('DELETE FROM حجوزات_الفندق WHERE bookingID = :bookingID');
    $stmt->execute(['bookingID' => (int) $_GET['bookingID']]);

    // Return deleted booking ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['bookingID' => (int) $_GET['bookingID']]);
    exit;
}