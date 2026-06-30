<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة فنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-slate-900 h-screen">
    <div class="flex flex-col h-screen">
        <header class="bg-slate-900 py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <h1 class="text-3xl text-indigo-500 font-bold">نظام إدارة فنادق</h1>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </header>
        <main class="flex-1 p-4">
            <div class="glassmorphism-card mb-4">
                <h2 class="text-2xl text-indigo-500 font-bold mb-2">مرحباً</h2>
                <p class="text-gray-300">إدارة فنادق</p>
            </div>
            <div class="glassmorphism-card mb-4">
                <h2 class="text-2xl text-indigo-500 font-bold mb-2">إحصائيات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-slate-900 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-bold mb-2">عدد الغرف</h3>
                        <p id="rooms-count" class="text-gray-300"></p>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-bold mb-2">عدد الحجوزات</h3>
                        <p id="bookings-count" class="text-gray-300"></p>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-4 text-white">
                        <h3 class="text-lg font-bold mb-2">عدد الخدمات</h3>
                        <p id="services-count" class="text-gray-300"></p>
                    </div>
                </div>
            </div>
            <div class="glassmorphism-card mb-4">
                <h2 class="text-2xl text-indigo-500 font-bold mb-2">روابط سريعة</h2>
                <ul class="list-none mb-0">
                    <li class="mb-2">
                        <a href="#" class="text-gray-300 hover:text-white">غرف</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-gray-300 hover:text-white">حجوزات</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-gray-300 hover:text-white">خدمات</a>
                    </li>
                </ul>
            </div>
        </main>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('rooms-count').textContent = data.rooms_count;
                document.getElementById('bookings-count').textContent = data.bookings_count;
                document.getElementById('services-count').textContent = data.services_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes that you have a backend API endpoint at `/api/stats` that returns a JSON response with the stats data. You will need to replace this with your actual API endpoint and data structure.

Also, this code uses the `fetch` API to make a GET request to the API endpoint. If you are using an older browser that does not support the `fetch` API, you may need to use a library like Axios or jQuery to make the request.

Finally, this code uses the `session_start` function to start a session, and checks if the `username` session variable is set. If it is not set, the user is redirected to the login page. You will need to replace this with your actual session management code.