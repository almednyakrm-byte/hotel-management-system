<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $roomId = isset($_GET['id']) ? (int) $_GET['id'] : null;

    // SQL query structure: Select all rooms or a specific room by id
    if ($roomId) {
        $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->bindParam(':id', $roomId);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM rooms');
    }

    // Execute query and process output
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rooms);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = isset($data['name']) ? trim($data['name']) : null;
    $capacity = isset($data['capacity']) ? (int) $data['capacity'] : null;

    if (!$name || !$capacity) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Insert new room
    $stmt = $pdo->prepare('INSERT INTO rooms (name, capacity) VALUES (:name, :capacity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);

    // Execute query and process output
    $stmt->execute();
    $roomId = $pdo->lastInsertId();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $roomId, 'name' => $name, 'capacity' => $capacity]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $roomId = isset($data['id']) ? (int) $data['id'] : null;
    $name = isset($data['name']) ? trim($data['name']) : null;
    $capacity = isset($data['capacity']) ? (int) $data['capacity'] : null;

    if (!$roomId || !$name || !$capacity) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Update existing room
    $stmt = $pdo->prepare('UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);

    // Execute query and process output
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $roomId, 'name' => $name, 'capacity' => $capacity]);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $roomId = isset($data['id']) ? (int) $data['id'] : null;

    if (!$roomId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Delete existing room
    $stmt = $pdo->prepare('DELETE FROM rooms WHERE id = :id');
    $stmt->bindParam(':id', $roomId);

    // Execute query and process output
    $stmt->execute();

    http_response_code(204);
    header('Content-Type: application/json');
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}