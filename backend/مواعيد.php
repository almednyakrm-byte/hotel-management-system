<?php
require_once 'db.php';

// Get user role and logged-in status
$userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : null;
$loggedIn = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : false;

// Check if user is logged in
if (!$loggedIn) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get') {
    // Validate and sanitize input
    $id = isset($inputData['id']) ? intval($inputData['id']) : null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'list') {
    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد');
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByUser') {
    // Validate and sanitize input
    $userId = isset($inputData['userId']) ? intval($inputData['userId']) : null;
    if (!$userId) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user ID'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE user_id = :userId');
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByDate') {
    // Validate and sanitize input
    $date = isset($inputData['date']) ? $inputData['date'] : null;
    if (!$date) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid date'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE date = :date');
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByTime') {
    // Validate and sanitize input
    $time = isset($inputData['time']) ? $inputData['time'] : null;
    if (!$time) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid time'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE time = :time');
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByUserAndDate') {
    // Validate and sanitize input
    $userId = isset($inputData['userId']) ? intval($inputData['userId']) : null;
    $date = isset($inputData['date']) ? $inputData['date'] : null;
    if (!$userId || !$date) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user ID or date'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE user_id = :userId AND date = :date');
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByUserAndTime') {
    // Validate and sanitize input
    $userId = isset($inputData['userId']) ? intval($inputData['userId']) : null;
    $time = isset($inputData['time']) ? $inputData['time'] : null;
    if (!$userId || !$time) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user ID or time'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE user_id = :userId AND time = :time');
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByDateAndTime') {
    // Validate and sanitize input
    $date = isset($inputData['date']) ? $inputData['date'] : null;
    $time = isset($inputData['time']) ? $inputData['time'] : null;
    if (!$date || !$time) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid date or time'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE date = :date AND time = :time');
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByUserDateAndTime') {
    // Validate and sanitize input
    $userId = isset($inputData['userId']) ? intval($inputData['userId']) : null;
    $date = isset($inputData['date']) ? $inputData['date'] : null;
    $time = isset($inputData['time']) ? $inputData['time'] : null;
    if (!$userId || !$date || !$time) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user ID, date, or time'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE user_id = :userId AND date = :date AND time = :time');
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Check if results exist
    if ($results) {
        http_response_code(200);
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'listByUserDate') {
    // Validate and sanitize input
    $userId = isset($inputData['userId']) ? intval($inputData['