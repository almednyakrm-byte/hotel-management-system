**list_حجوزات-الفندق.php**

<?php
// Session validation
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
    <title>حجوزات الفندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .table-container {
            max-width: 1200px;
            margin: 40px auto;
        }
        .table-container table {
            border-collapse: collapse;
            width: 100%;
        }
        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table-container th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </div>
    </header>
    <main class="container mx-auto py-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">حجوزات الفندق</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_حجوزات-الفندق.php'">إضافة جديد</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>اسم الحجز</th>
                        <th>تاريخ الحجز</th>
                        <th>حالة الحجز</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Table rows will be populated here -->
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        // Search function
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/حجوزات-الفندق.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.اسم_الحجز}</td>
                                <td>${record.تاريخ_الحجز}</td>
                                <td>${record.حالة_الحجز}</td>
                                <td>
                                    <a href="edit_حجوزات-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/حجوزات-الفندق.php')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.اسم_الحجز}</td>
                                <td>${record.تاريخ_الحجز}</td>
                                <td>${record.حالة_الحجز}</td>
                                <td>
                                    <a href="edit_حجوزات-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }
        }

        // Delete record function
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/حجوزات-الفندق.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Fetch records on page load
        fetch('../backend/حجوزات-الفندق.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_الحجز}</td>
                        <td>${record.تاريخ_الحجز}</td>
                        <td>${record.حالة_الحجز}</td>
                        <td>
                            <a href="edit_حجوزات-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/حجوزات-الفندق.php`) that handles the GET and DELETE requests. The backend script should return a JSON response with the list of records or a success/failure message.