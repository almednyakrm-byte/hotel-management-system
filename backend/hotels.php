<?php
// Import database connection
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
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Process GET request
if ($method == 'GET') {
    // Validate and sanitize input
    $hotel_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if hotel ID is provided
    if ($hotel_id) {
        // SQL query to select hotel by ID
        $stmt = $pdo->prepare('SELECT * FROM hotels WHERE id = :id');
        $stmt->bindParam(':id', $hotel_id);
        $stmt->execute();
        $hotel = $stmt->fetch();

        // Check if hotel exists
        if ($hotel) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($hotel);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Hotel not found']);
        }
    } else {
        // SQL query to select all hotels
        $stmt = $pdo->prepare('SELECT * FROM hotels');
        $stmt->execute();
        $hotels = $stmt->fetchAll();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($hotels);
    }
}

// Process POST request
if ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'] ?? null, FILTER_SANITIZE_STRING);
    $city = filter_var($input['city'] ?? null, FILTER_SANITIZE_STRING);
    $country = filter_var($input['country'] ?? null, FILTER_SANITIZE_STRING);

    // Check if input is valid
    if (!$name || !$address || !$city || !$country) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query to insert hotel
    $stmt = $pdo->prepare('INSERT INTO hotels (name, address, city, country) VALUES (:name, :address, :city, :country)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':country', $country);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Hotel created successfully']);
}

// Process PUT request
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $hotel_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'] ?? null, FILTER_SANITIZE_STRING);
    $city = filter_var($input['city'] ?? null, FILTER_SANITIZE_STRING);
    $country = filter_var($input['country'] ?? null, FILTER_SANITIZE_STRING);

    // Check if input is valid
    if (!$hotel_id || !$name || !$address || !$city || !$country) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query to update hotel
    $stmt = $pdo->prepare('UPDATE hotels SET name = :name, address = :address, city = :city, country = :country WHERE id = :id');
    $stmt->bindParam(':id', $hotel_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':country', $country);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Hotel updated successfully']);
}

// Process DELETE request
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $hotel_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if input is valid
    if (!$hotel_id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query to delete hotel
    $stmt = $pdo->prepare('DELETE FROM hotels WHERE id = :id');
    $stmt->bindParam(':id', $hotel_id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Hotel deleted successfully']);
}