<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/get' => 'getReservations',
    '/create' => 'createReservation',
    '/update/:id' => 'updateReservation',
    '/delete/:id' => 'deleteReservation',
];

// Route request
$match = false;
foreach ($routes as $route => $method) {
    if (strpos($route, '/') !== false) {
        $parts = explode('/', $route);
        if (count($parts) == 2 && $parts[0] == 'update' && $parts[1] == ':id') {
            if (isset($input['id']) && is_numeric($input['id'])) {
                $id = $input['id'];
                $match = true;
                break;
            }
        } elseif (count($parts) == 1 && $parts[0] == 'delete' && $parts[1] == ':id') {
            if (isset($input['id']) && is_numeric($input['id'])) {
                $id = $input['id'];
                $match = true;
                break;
            }
        } elseif (count($parts) == 1 && $parts[0] == 'get') {
            $match = true;
            break;
        } elseif (count($parts) == 1 && $parts[0] == 'create') {
            $match = true;
            break;
        }
    }
}

// Call the correct method
if ($match) {
    $method = $routes[$route];
    $response = call_user_func($method, $input);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}

// Output response
header('Content-Type: application/json');
echo json_encode($response);

// Methods
function getReservations($input) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM reservations');
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $reservations;
}

function createReservation($input) {
    global $db;
    // Validate and sanitize input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Insert data
    $stmt = $db->prepare('INSERT INTO reservations (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    $id = $db->lastInsertId();
    return ['message' => 'Reservation created successfully', 'id' => $id];
}

function updateReservation($input) {
    global $db;
    // Validate and sanitize input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update data
    $stmt = $db->prepare('UPDATE reservations SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    return ['message' => 'Reservation updated successfully'];
}

function deleteReservation($input) {
    global $db;
    // Validate and sanitize input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field']);
        exit;
    }
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete data
    $stmt = $db->prepare('DELETE FROM reservations WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return ['message' => 'Reservation deleted successfully'];
}