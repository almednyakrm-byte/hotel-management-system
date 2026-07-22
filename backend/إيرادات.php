<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate input parameters
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM إيرادات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input parameters
    if (!isset($inputData['name']) || !isset($inputData['amount'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input parameters
    $name = trim($inputData['name']);
    $amount = floatval($inputData['amount']);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO إيرادات (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Get inserted ID
    $id = $pdo->lastInsertId();

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM إيرادات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input parameters
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['amount'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);
    $name = trim($inputData['name']);
    $amount = floatval($inputData['amount']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE إيرادات SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Check if result exists
    $stmt = $pdo->prepare('SELECT * FROM إيرادات WHERE id = :id');
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input parameters
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM إيرادات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if result exists
    $stmt = $pdo->prepare('SELECT * FROM إيرادات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    if (!$result) {
        http_response_code(204);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}