<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'];
$authenticated = $_SESSION['authenticated'];

// Check if user is authenticated
if (!$authenticated) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get report ID from URL query string
    $reportId = $_GET['id'] ?? null;

    // Check if report ID is provided
    if ($reportId) {
        // Prepare SELECT statement
        $stmt = $pdo->prepare('SELECT * FROM تقارير WHERE id = :id');
        $stmt->bindParam(':id', $reportId);
        $stmt->execute();

        // Fetch report data
        $reportData = $stmt->fetch();

        // Check if report exists
        if ($reportData) {
            // Return report data
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($reportData);
        } else {
            // Return 404 Not Found
            http_response_code(404);
            echo json_encode(array('error' => 'Report not found'));
        }
    } else {
        // Prepare SELECT statement
        $stmt = $pdo->prepare('SELECT * FROM تقارير');
        $stmt->execute();

        // Fetch all reports
        $reports = $stmt->fetchAll();

        // Return reports data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($reports);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get report data from request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate report data
    if (!isset($requestData['title']) || !isset($requestData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid report data'));
        exit;
    }

    // Sanitize report data
    $requestData['title'] = htmlspecialchars($requestData['title']);
    $requestData['description'] = htmlspecialchars($requestData['description']);

    // Prepare INSERT statement
    $stmt = $pdo->prepare('INSERT INTO تقارير (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $requestData['title']);
    $stmt->bindParam(':description', $requestData['description']);
    $stmt->execute();

    // Get last inserted report ID
    $reportId = $pdo->lastInsertId();

    // Return report ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $reportId));
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get report ID from URL query string
    $reportId = $_GET['id'] ?? null;

    // Check if report ID is provided
    if ($reportId) {
        // Get report data from request body
        $requestData = json_decode(file_get_contents('php://input'), true);

        // Validate report data
        if (!isset($requestData['title']) || !isset($requestData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid report data'));
            exit;
        }

        // Sanitize report data
        $requestData['title'] = htmlspecialchars($requestData['title']);
        $requestData['description'] = htmlspecialchars($requestData['description']);

        // Check if user is admin
        if ($userRole !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Prepare UPDATE statement
        $stmt = $pdo->prepare('UPDATE تقارير SET title = :title, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $reportId);
        $stmt->bindParam(':title', $requestData['title']);
        $stmt->bindParam(':description', $requestData['description']);
        $stmt->execute();

        // Check if report updated
        if ($stmt->rowCount() === 1) {
            // Return report ID
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('id' => $reportId));
        } else {
            // Return 404 Not Found
            http_response_code(404);
            echo json_encode(array('error' => 'Report not found'));
        }
    } else {
        // Return 400 Bad Request
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid report ID'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get report ID from URL query string
    $reportId = $_GET['id'] ?? null;

    // Check if report ID is provided
    if ($reportId) {
        // Check if user is admin
        if ($userRole !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Prepare DELETE statement
        $stmt = $pdo->prepare('DELETE FROM تقارير WHERE id = :id');
        $stmt->bindParam(':id', $reportId);
        $stmt->execute();

        // Check if report deleted
        if ($stmt->rowCount() === 1) {
            // Return 204 No Content
            http_response_code(204);
        } else {
            // Return 404 Not Found
            http_response_code(404);
            echo json_encode(array('error' => 'Report not found'));
        }
    } else {
        // Return 400 Bad Request
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid report ID'));
    }
}