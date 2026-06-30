<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($inputData)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Validate required fields
$requiredFields = ['id', 'name', 'description'];
foreach ($requiredFields as $field) {
    if (!isset($inputData[$field])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field: ' . $field]);
        exit;
    }
}

// Sanitize input data
$inputData['name'] = htmlspecialchars($inputData['name']);
$inputData['description'] = htmlspecialchars($inputData['description']);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin to allow GET all
    if ($isAdmin) {
        $stmt = $pdo->prepare('SELECT * FROM غرف');
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($rooms);
    } else {
        // Get room by ID
        $roomId = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM غرف WHERE id = :id');
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room) {
            http_response_code(200);
            echo json_encode($room);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Room not found']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert new room
    $stmt = $pdo->prepare('INSERT INTO غرف (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(['message' => 'Room created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Update existing room
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $roomId = $inputData['id'];
    $stmt = $pdo->prepare('UPDATE غرف SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(['message' => 'Room updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Delete room
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $roomId = $inputData['id'];
    $stmt = $pdo->prepare('DELETE FROM غرف WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(['message' => 'Room deleted successfully']);
}

// Set content type to JSON
header('Content-Type: application/json');