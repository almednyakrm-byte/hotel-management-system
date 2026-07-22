<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة إدارة أعمال الفنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <h1 class="text-3xl text-blue-500 font-bold mt-4">مرحباً بك في منصة إدارة أعمال الفنادق</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إجمالي الحجوزات</h2>
                <p id="total-bookings" class="text-2xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إجمالي الغرف</h2>
                <p id="total-rooms" class="text-2xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إجمالي الخدمات</h2>
                <p id="total-services" class="text-2xl font-bold"></p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إجمالي الإيرادات</h2>
                <p id="total-revenue" class="text-2xl font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إدارة الحجوزات</h2>
                <a href="bookings.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة الحجوزات</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إدارة الغرف</h2>
                <a href="rooms.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة الغرف</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold">إدارة الخدمات</h2>
                <a href="services.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إدارة الخدمات</a>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/bookings.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-bookings').innerText = data.total;
            });

        fetch('api/rooms.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-rooms').innerText = data.total;
            });

        fetch('api/services.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-services').innerText = data.total;
            });

        fetch('api/revenue.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-revenue').innerText = data.total;
            });

        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 0 10px rgba(0, 0, 0, 0.1), 0 0 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
        }
    </style>
</body>
</html>