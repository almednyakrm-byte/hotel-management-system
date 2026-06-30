<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/services' => array('GET' => 'getServices', 'POST' => 'createService'),
    '/services/:id' => array('GET' => 'getService', 'PUT' => 'updateService', 'DELETE' => 'deleteService')
);

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
$route = '/' . implode('/', array_slice($route, 1));
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get allowed methods for route
$allowedMethods = $routes[$route];

// Check if method is allowed
if (!in_array($method, $allowedMethods)) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Get allowed method
$allowedMethod = $allowedMethods[$method];

// Call allowed method
$allowedMethod();

// Helper function to get services
function getServices() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
}

// Helper function to get service by id
function getService() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$service) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($service);
}

// Helper function to create service
function createService() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Insert service
    $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service created successfully'));
}

// Helper function to update service
function updateService() {
    global $pdo;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update service
    $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service updated successfully'));
}

// Helper function to delete service
function deleteService() {
    global $pdo;
    $id = $_GET['id'];
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete service
    $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service deleted successfully'));
}

?>