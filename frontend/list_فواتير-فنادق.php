**list_فواتير-فنادق.php**

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
    <title>فواتير فنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">فواتير فنادق</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_فواتير-فنادق.php'">إضافة جديد</button>
        <div class="flex items-center mb-4">
            <input type="search" id="search" class="w-full py-2 pl-10 text-lg border border-gray-300 rounded-l-lg" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">رقم الفاتورة</th>
                    <th class="border border-gray-300 px-4 py-2">تاريخ الفاتورة</th>
                    <th class="border border-gray-300 px-4 py-2">مبلغ الفاتورة</th>
                    <th class="border border-gray-300 px-4 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/فواتير-فنادق.php', { method: 'GET' });
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-gray-300 px-4 py-2">${record.id}</td>
                    <td class="border border-gray-300 px-4 py-2">${record.date}</td>
                    <td class="border border-gray-300 px-4 py-2">${record.amount}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="edit_فواتير-فنادق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/فواتير-فنادق.php', { method: 'GET', params: { search: searchQuery } })
                    .then((response) => response.json())
                    .then((data) => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach((record) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-300 px-4 py-2">${record.id}</td>
                                <td class="border border-gray-300 px-4 py-2">${record.date}</td>
                                <td class="border border-gray-300 px-4 py-2">${record.amount}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="edit_فواتير-فنادق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف الفاتورة؟')) {
                fetch('../backend/فواتير-فنادق.php', { method: 'DELETE', params: { id } })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            loadRecords();
                        } else {
                            alert('حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/فواتير-فنادق.php`) that handles GET and DELETE requests for fetching and deleting records, respectively. You will need to implement this script separately.