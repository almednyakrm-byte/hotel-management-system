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

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all bookings
    $stmt = $pdo->prepare('SELECT * FROM حجز_غرف');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return bookings
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($bookings);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($input['room_id']) || !isset($input['guest_id']) || !isset($input['check_in']) || !isset($input['check_out'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $room_id = (int) $input['room_id'];
    $guest_id = (int) $input['guest_id'];
    $check_in = date('Y-m-d', strtotime($input['check_in']));
    $check_out = date('Y-m-d', strtotime($input['check_out']));

    // Insert new booking
    $stmt = $pdo->prepare('INSERT INTO حجز_غرف (room_id, guest_id, check_in, check_out) VALUES (:room_id, :guest_id, :check_in, :check_out)');
    $stmt->bindParam(':room_id', $room_id);
    $stmt->bindParam(':guest_id', $guest_id);
    $stmt->bindParam(':check_in', $check_in);
    $stmt->bindParam(':check_out', $check_out);
    $stmt->execute();

    // Return new booking
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['room_id']) || !isset($input['guest_id']) || !isset($input['check_in']) || !isset($input['check_out'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];
    $room_id = (int) $input['room_id'];
    $guest_id = (int) $input['guest_id'];
    $check_in = date('Y-m-d', strtotime($input['check_in']));
    $check_out = date('Y-m-d', strtotime($input['check_out']));

    // Update booking
    $stmt = $pdo->prepare('UPDATE حجز_غرف SET room_id = :room_id, guest_id = :guest_id, check_in = :check_in, check_out = :check_out WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->bindParam(':guest_id', $guest_id);
    $stmt->bindParam(':check_in', $check_in);
    $stmt->bindParam(':check_out', $check_out);
    $stmt->execute();

    // Return updated booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];

    // Delete booking
    $stmt = $pdo->prepare('DELETE FROM حجز_غرف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking deleted successfully'));
    exit;
}

// Return error response
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;