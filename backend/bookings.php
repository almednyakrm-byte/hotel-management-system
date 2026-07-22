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
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $booking_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if booking ID is provided
    if ($booking_id) {
        // SQL query to retrieve a single booking
        $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = :id');
        $stmt->bindParam(':id', $booking_id);
        $stmt->execute();
        
        // Fetch booking data
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if booking exists
        if ($booking) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($booking);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Booking not found']);
        }
    } else {
        // SQL query to retrieve all bookings
        $stmt = $pdo->prepare('SELECT * FROM bookings');
        $stmt->execute();
        
        // Fetch all bookings
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($bookings);
    }
}

// Handle POST requests
if ($method == 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $user_id = filter_var($input['user_id'] ?? null, FILTER_VALIDATE_INT);
    $room_id = filter_var($input['room_id'] ?? null, FILTER_VALIDATE_INT);
    $start_date = filter_var($input['start_date'] ?? null, FILTER_VALIDATE_DATE);
    $end_date = filter_var($input['end_date'] ?? null, FILTER_VALIDATE_DATE);
    
    // Check if input is valid
    if ($user_id && $room_id && $start_date && $end_date) {
        // Check if user is admin or making a booking for themselves
        if ($_SESSION['user_id'] == $user_id || $_SESSION['role'] == 'admin') {
            // SQL query to insert a new booking
            $stmt = $pdo->prepare('INSERT INTO bookings (user_id, room_id, start_date, end_date) VALUES (:user_id, :room_id, :start_date, :end_date)');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':room_id', $room_id);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Booking created successfully']);
        } else {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
        }
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
}

// Handle PUT requests
if ($method == 'PUT') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $booking_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $user_id = filter_var($input['user_id'] ?? null, FILTER_VALIDATE_INT);
    $room_id = filter_var($input['room_id'] ?? null, FILTER_VALIDATE_INT);
    $start_date = filter_var($input['start_date'] ?? null, FILTER_VALIDATE_DATE);
    $end_date = filter_var($input['end_date'] ?? null, FILTER_VALIDATE_DATE);
    
    // Check if input is valid
    if ($booking_id && $user_id && $room_id && $start_date && $end_date) {
        // Check if user is admin
        if ($_SESSION['role'] == 'admin') {
            // SQL query to update a booking
            $stmt = $pdo->prepare('UPDATE bookings SET user_id = :user_id, room_id = :room_id, start_date = :start_date, end_date = :end_date WHERE id = :id');
            $stmt->bindParam(':id', $booking_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':room_id', $room_id);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Booking updated successfully']);
        } else {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
        }
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $booking_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if input is valid
    if ($booking_id) {
        // Check if user is admin
        if ($_SESSION['role'] == 'admin') {
            // SQL query to delete a booking
            $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id');
            $stmt->bindParam(':id', $booking_id);
            $stmt->execute();
            
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Booking deleted successfully']);
        } else {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
        }
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
}