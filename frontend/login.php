<!-- login.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
        .gradient {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center bg-gray-100">
        <div class="glassmorphic w-96 p-10 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password">
                </div>
                <button type="submit" class="w-full p-2 mt-4 text-sm text-white bg-indigo-500 hover:bg-indigo-700 rounded-lg">Login</button>
            </form>
            <p class="text-sm text-gray-500 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
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
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script and handle the response or error alerts dynamically. The direct link to the register page is also included.