<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-200 flex justify-center items-center">
    <div class="bg-white p-10 rounded shadow-md w-1/2">
        <h2 class="text-3xl text-blue-500 mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-blue-500 mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-blue-500 mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500" id="password-error"></div>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Register</button>
        </form>
        <div class="text-red-500" id="register-error"></div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validation
            let valid = true;
            if (username.length < 3) {
                document.getElementById('username-error').innerText = 'Username must be at least 3 characters long';
                valid = false;
            } else {
                document.getElementById('username-error').innerText = '';
            }
            if (!email.includes('@')) {
                document.getElementById('email-error').innerText = 'Invalid email';
                valid = false;
            } else {
                document.getElementById('email-error').innerText = '';
            }
            if (password.length < 8) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters long';
                valid = false;
            } else {
                document.getElementById('password-error').innerText = '';
            }

            if (valid) {
                const formData = new FormData();
                formData.append('username', username);
                formData.append('email', email);
                formData.append('password', password);

                fetch('../backend/auth.php?action=register', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    } else {
                        document.getElementById('register-error').innerText = data.message;
                    }
                })
                .catch(error => {
                    console.error(error);
                });
            }
        });
    </script>
</body>
</html>