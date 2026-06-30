<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get all hotels
    $stmt = $pdo->prepare('SELECT * FROM hotels');
    $stmt->execute();
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return hotels in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($hotels);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['address']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($inputData['name']);
    $address = htmlspecialchars($inputData['address']);
    $phone = htmlspecialchars($inputData['phone']);

    // Insert new hotel
    $stmt = $pdo->prepare('INSERT INTO hotels (name, address, phone) VALUES (:name, :address, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return new hotel in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Hotel created successfully'));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['address']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);
    $name = htmlspecialchars($inputData['name']);
    $address = htmlspecialchars($inputData['address']);
    $phone = htmlspecialchars($inputData['phone']);

    // Update hotel
    $stmt = $pdo->prepare('UPDATE hotels SET name = :name, address = :address, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return updated hotel in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Hotel updated successfully'));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);

    // Delete hotel
    $stmt = $pdo->prepare('DELETE FROM hotels WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted hotel in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Hotel deleted successfully'));
}

// Return error response for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}