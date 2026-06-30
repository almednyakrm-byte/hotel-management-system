<?php

require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Check if user is logged in
if (!isset($_SESSION['loggedIn'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Validate input data
if (!isset($inputData['id']) && !isset($inputData['name']) && !isset($inputData['description']) && !isset($inputData['price'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request data'));
    exit;
}

// Sanitize input data
$inputData['name'] = trim($inputData['name']);
$inputData['description'] = trim($inputData['description']);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all rooms
    $stmt = $pdo->prepare('SELECT * FROM غرف_فنادق');
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rooms);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Insert new room
    $stmt = $pdo->prepare('INSERT INTO غرف_فنادق (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->bindParam(':price', $inputData['price']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Room created successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create room'));
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing room
    $stmt = $pdo->prepare('UPDATE غرف_فنادق SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->bindParam(':price', $inputData['price']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Room updated successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update room'));
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete existing room
    $stmt = $pdo->prepare('DELETE FROM غرف_فنادق WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Room deleted successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete room'));
        exit;
    }
}

http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;