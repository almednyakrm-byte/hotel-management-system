<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-gray-200 flex justify-center items-center">
    <div class="glassmorphic-card bg-white/20 backdrop-blur-md rounded-2xl shadow-2xl p-10 w-80">
        <h1 class="text-3xl text-blue-500 font-bold mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-blue-500 mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="bg-gray-100/50 border border-blue-500 rounded-lg p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-blue-500 mb-2">Password</label>
                <input type="password" id="password" name="password" required class="bg-gray-100/50 border border-blue-500 rounded-lg p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-full">Login</button>
        </form>
        <p class="text-blue-500 mt-4">Don't have an account? <a href="register.php" class="underline">Register here</a></p>
        <div id="error-alert" class="hidden mt-4 p-4 bg-red-500 text-white rounded-lg"></div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const errorAlert = document.getElementById('error-alert');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    errorAlert.classList.remove('hidden');
                    errorAlert.innerText = data.message;
                }
            } catch (error) {
                errorAlert.classList.remove('hidden');
                errorAlert.innerText = 'An error occurred. Please try again later.';
            }
        });
    </script>
</body>
</html>