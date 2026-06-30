<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Function to validate input data
function validate_input($data) {
    // Add validation rules here
    return true;
}

// Function to sanitize input data
function sanitize_input($data) {
    // Add sanitization rules here
    return $data;
}

// Function to handle GET request
function get_payments() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM payments');
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($payments);
}

// Function to handle POST request
function create_payment() {
    global $pdo;
    if (!validate_input($_POST)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    $data = sanitize_input($_POST);
    $stmt = $pdo->prepare('INSERT INTO payments (name, amount, date) VALUES (:name, :amount, :date)');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':amount', $data['amount']);
    $stmt->bindParam(':date', $data['date']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Payment created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create payment'));
    }
}

// Function to handle PUT request
function update_payment() {
    global $pdo;
    if (!validate_input($_POST)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    $data = sanitize_input($_POST);
    $stmt = $pdo->prepare('UPDATE payments SET name = :name, amount = :amount, date = :date WHERE id = :id');
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':amount', $data['amount']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':id', $data['id']);
    if ($is_admin && $stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Payment updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

// Function to handle DELETE request
function delete_payment() {
    global $pdo;
    $id = $_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM payments WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($is_admin && $stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Payment deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    get_payments();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($input_data['action']) && $input_data['action'] == 'create') {
        create_payment();
    } elseif (isset($input_data['action']) && $input_data['action'] == 'update') {
        update_payment();
    } elseif (isset($input_data['action']) && $input_data['action'] == 'delete') {
        delete_payment();
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}