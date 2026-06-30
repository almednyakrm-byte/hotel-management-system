<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input is valid
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Define routes
$routes = [
    '/hotels' => [
        'GET' => function () {
            // Get all hotels
            $stmt = $pdo->prepare('SELECT * FROM إدارة_فنادق');
            $stmt->execute();
            $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($hotels);
        },
        'POST' => function () {
            // Create new hotel
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare('INSERT INTO إدارة_فنادق (name, address) VALUES (:name, :address)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(['message' => 'Hotel created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create hotel']);
            }
        }
    ],
    '/hotels/:id' => [
        'GET' => function ($id) {
            // Get hotel by ID
            $stmt = $pdo->prepare('SELECT * FROM إدارة_فنادق WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($hotel) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($hotel);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Hotel not found']);
            }
        },
        'PUT' => function ($id) {
            // Update hotel
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare('UPDATE إدارة_فنادق SET name = :name, address = :address WHERE id = :id');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Hotel updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update hotel']);
            }
        },
        'DELETE' => function ($id) {
            // Delete hotel
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM إدارة_فنادق WHERE id = :id');
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(['message' => 'Hotel deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to delete hotel']);
            }
        }
    ]
];

// Get route and method from URL
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Parse route parameters
$parts = explode('/', $route);
$parts = array_filter($parts);
$route = implode('/', $parts);

// Check if route is valid
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
    exit;
}

// Check if method is valid
if (!isset($routes[$route][$method])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Execute route
$routes[$route][$method]();