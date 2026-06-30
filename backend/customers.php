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
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Handle GET requests
if ($method === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if admin or owner
    if ($id && $_SESSION['user_role'] !== 'admin' && $_SESSION['user_id'] !== $id) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Select all customers or a specific customer by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $customer = $stmt->fetch();
        if (!$customer) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Customer not found']);
            exit;
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($customer);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM customers');
        $stmt->execute();
        $customers = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($customers);
    }
}

// Handle POST requests
elseif ($method === 'POST') {
    // Check if admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);
    $phone = filter_var($data['phone'] ?? null, FILTER_SANITIZE_STRING);

    // Check if input is valid
    if (!$name || !$email || !$phone) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Insert new customer
    $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->execute([':name' => $name, ':email' => $email, ':phone' => $phone]);
    $customerId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $customerId]);
}

// Handle PUT requests
elseif ($method === 'PUT') {
    // Check if admin or owner
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id && $_SESSION['user_role'] !== 'admin' && $_SESSION['user_id'] !== $id) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);
    $phone = filter_var($data['phone'] ?? null, FILTER_SANITIZE_STRING);

    // Check if input is valid
    if (!$name || !$email || !$phone) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Update existing customer
    $stmt = $pdo->prepare('UPDATE customers SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->execute([':name' => $name, ':email' => $email, ':phone' => $phone, ':id' => $id]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Customer updated']);
}

// Handle DELETE requests
elseif ($method === 'DELETE') {
    // Check if admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if input is valid
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Delete customer
    $stmt = $pdo->prepare('DELETE FROM customers WHERE id = :id');
    $stmt->execute([':id' => $id]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Customer deleted']);
}

// Handle other requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}