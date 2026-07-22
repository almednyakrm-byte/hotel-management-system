<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if user is admin
$is_admin = $user['role'] == 'admin';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if id is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM فصول WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if (!$result) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // Return result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST request
elseif ($method == 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;

    // Check if required fields are provided
    if (!$name || !$description) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO فصول (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get inserted id
    $id = $pdo->lastInsertId();

    // Return inserted id
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle PUT request
elseif ($method == 'PUT') {
    // Validate and sanitize input
    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;

    // Check if id is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if required fields are provided
    if (!$name || !$description) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE فصول SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE request
elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $id = intval($_GET['id']);

    // Check if id is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM فصول WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}