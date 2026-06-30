<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Handle GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM فواتير_الفندق WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->query('SELECT * FROM فواتير_الفندق');
    $results = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Handle POST request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO فواتير_الفندق (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $id = $_GET['id'];
    $stmt = $pdo->prepare('UPDATE فواتير_الفندق SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM فواتير_الفندق WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(204);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}