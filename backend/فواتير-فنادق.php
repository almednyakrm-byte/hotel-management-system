<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate input (no validation needed for GET)
    $stmt = $pdo->prepare('SELECT * FROM فواتير_فنادق');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!isset($input['hotel_id']) || !isset($input['invoice_date']) || !isset($input['invoice_total'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $input['hotel_id'] = (int)$input['hotel_id'];
    $input['invoice_date'] = date('Y-m-d', strtotime($input['invoice_date']));
    $input['invoice_total'] = (float)$input['invoice_total'];

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Insert data
    $stmt = $pdo->prepare('INSERT INTO فواتير_فنادق (hotel_id, invoice_date, invoice_total) VALUES (:hotel_id, :invoice_date, :invoice_total)');
    $stmt->bindParam(':hotel_id', $input['hotel_id']);
    $stmt->bindParam(':invoice_date', $input['invoice_date']);
    $stmt->bindParam(':invoice_total', $input['invoice_total']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Invoice created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input
    if (!isset($input['id']) || !isset($input['hotel_id']) || !isset($input['invoice_date']) || !isset($input['invoice_total'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $input['id'] = (int)$input['id'];
    $input['hotel_id'] = (int)$input['hotel_id'];
    $input['invoice_date'] = date('Y-m-d', strtotime($input['invoice_date']));
    $input['invoice_total'] = (float)$input['invoice_total'];

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update data
    $stmt = $pdo->prepare('UPDATE فواتير_فنادق SET hotel_id = :hotel_id, invoice_date = :invoice_date, invoice_total = :invoice_total WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':hotel_id', $input['hotel_id']);
    $stmt->bindParam(':invoice_date', $input['invoice_date']);
    $stmt->bindParam(':invoice_total', $input['invoice_total']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Invoice updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $input['id'] = (int)$input['id'];

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete data
    $stmt = $pdo->prepare('DELETE FROM فواتير_فنادق WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Invoice deleted successfully'));
    exit;
}