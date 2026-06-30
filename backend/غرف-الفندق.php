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

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($userRole == 'admin') {
        // Get all rooms
        $stmt = $pdo->prepare('SELECT * FROM غرف_الفندق');
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rooms);
    } else {
        // Get user's rooms
        $stmt = $pdo->prepare('SELECT * FROM غرف_الفندق WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $userID);
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rooms);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is admin
    if ($userRole == 'admin') {
        // Validate input data
        if (!isset($inputData['room_number']) || !isset($inputData['room_type']) || !isset($inputData['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }

        // Sanitize input data
        $roomNumber = filter_var($inputData['room_number'], FILTER_SANITIZE_NUMBER_INT);
        $roomType = filter_var($inputData['room_type'], FILTER_SANITIZE_STRING);
        $price = filter_var($inputData['price'], FILTER_SANITIZE_NUMBER_FLOAT);

        // Insert new room
        $stmt = $pdo->prepare('INSERT INTO غرف_الفندق (room_number, room_type, price) VALUES (:room_number, :room_type, :price)');
        $stmt->bindParam(':room_number', $roomNumber);
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':price', $price);
        $stmt->execute();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Room created successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($userRole == 'admin') {
        // Validate input data
        if (!isset($inputData['room_id']) || !isset($inputData['room_number']) || !isset($inputData['room_type']) || !isset($inputData['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }

        // Sanitize input data
        $roomId = filter_var($inputData['room_id'], FILTER_SANITIZE_NUMBER_INT);
        $roomNumber = filter_var($inputData['room_number'], FILTER_SANITIZE_NUMBER_INT);
        $roomType = filter_var($inputData['room_type'], FILTER_SANITIZE_STRING);
        $price = filter_var($inputData['price'], FILTER_SANITIZE_NUMBER_FLOAT);

        // Update room
        $stmt = $pdo->prepare('UPDATE غرف_الفندق SET room_number = :room_number, room_type = :room_type, price = :price WHERE id = :id');
        $stmt->bindParam(':id', $roomId);
        $stmt->bindParam(':room_number', $roomNumber);
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':price', $price);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Room updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($userRole == 'admin') {
        // Validate input data
        if (!isset($inputData['room_id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }

        // Sanitize input data
        $roomId = filter_var($inputData['room_id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete room
        $stmt = $pdo->prepare('DELETE FROM غرف_الفندق WHERE id = :id');
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Room deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}