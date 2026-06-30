<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the request is for registration or login
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize and validate input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and email are already taken
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // If the username or email is already taken, return an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Username or email already taken'
                );
                echo json_encode($response);
                exit;
            }

            // Hash the password using password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            // Return a success response
            $response = array(
                'status' => 'success',
                'message' => 'User registered successfully'
            );
            echo json_encode($response);
            exit;
        } else {
            // If any required field is missing, return an error response
            $response = array(
                'status' => 'error',
                'message' => 'Missing required fields'
            );
            echo json_encode($response);
            exit;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize and validate input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and password are valid
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // If the password is correct, log the user in
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $response = array(
                        'status' => 'logged_in',
                        'user_id' => $user['id'],
                        'username' => $user['username']
                    );
                    echo json_encode($response);
                    exit;
                } else {
                    // If the password is incorrect, return an error response
                    $response = array(
                        'status' => 'error',
                        'message' => 'Invalid password'
                    );
                    echo json_encode($response);
                    exit;
                }
            } else {
                // If the username is not found, return an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid username'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            // If any required field is missing, return an error response
            $response = array(
                'status' => 'error',
                'message' => 'Missing required fields'
            );
            echo json_encode($response);
            exit;
        }
    }
}

// Check if the user is trying to logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    echo json_encode($response);
    exit;
}

// If the user is not logged in, return a JSON response with their status
$response = array(
    'status' => 'logged_out'
);
echo json_encode($response);
exit;