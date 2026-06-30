<?php
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
    $roomId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure
    if ($roomId) {
        $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        $room = $stmt->fetch();
        if ($room) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($room);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Room not found']);
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM rooms');
        $stmt->execute();
        $rooms = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rooms);
    }
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
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $capacity = filter_var($data['capacity'] ?? null, FILTER_VALIDATE_INT);

    if (!$name || !$capacity) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO rooms (name, capacity) VALUES (:name, :capacity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Room created successfully']);
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
    $roomId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $capacity = filter_var($data['capacity'] ?? null, FILTER_VALIDATE_INT);

    if (!$roomId || !$name || !$capacity) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->execute();
    $room = $stmt->fetch();

    if (!$room) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Room not found']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':id', $roomId);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Room updated successfully']);
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
    $roomId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$roomId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->execute();
    $room = $stmt->fetch();

    if (!$room) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Room not found']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM rooms WHERE id = :id');
    $stmt->bindParam(':id', $roomId);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Room deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}