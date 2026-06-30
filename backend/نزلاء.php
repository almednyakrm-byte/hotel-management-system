<?php

// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
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
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Define table name
$tableName = 'نزلاء';

// Define columns
$columns = array('id', 'name', 'email', 'phone');

// Define validation rules
$validationRules = array(
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'required|numeric'
);

// Validate input data
foreach ($validationRules as $column => $rule) {
    if (!isset($inputData[$column]) || !preg_match('/^' . $rule . '$/', $inputData[$column])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Sanitize input data
$sanitizedData = array();
foreach ($columns as $column) {
    $sanitizedData[$column] = filter_var($inputData[$column], FILTER_SANITIZE_STRING);
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin or trying to get their own data
    if ($isAdmin || $inputData['id'] == $_SESSION['user_id']) {
        // Prepare SQL query
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sanitizedData['id']);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare SQL query
    $sql = 'INSERT INTO ' . $tableName . ' (name, email, phone) VALUES (:name, :email, :phone)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $sanitizedData['name']);
    $stmt->bindParam(':email', $sanitizedData['email']);
    $stmt->bindParam(':phone', $sanitizedData['phone']);
    try {
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin or trying to edit their own data
    if ($isAdmin || $inputData['id'] == $_SESSION['user_id']) {
        // Prepare SQL query
        $sql = 'UPDATE ' . $tableName . ' SET name = :name, email = :email, phone = :phone WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sanitizedData['id']);
        $stmt->bindParam(':name', $sanitizedData['name']);
        $stmt->bindParam(':email', $sanitizedData['email']);
        $stmt->bindParam(':phone', $sanitizedData['phone']);
        try {
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('message' => 'Updated successfully'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin or trying to delete their own data
    if ($isAdmin || $inputData['id'] == $_SESSION['user_id']) {
        // Prepare SQL query
        $sql = 'DELETE FROM ' . $tableName . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sanitizedData['id']);
        try {
            $stmt->execute();
            http_response_code(204);
            echo json_encode(array('message' => 'Deleted successfully'));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

// Set headers
header('Content-Type: application/json');