**list_غرف-الفندق.php**

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
    <title>غرف الفندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-white shadow-md p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
                </div>
            </nav>
        </header>
        <div class="bg-white shadow-md p-4 mb-4">
            <h2 class="text-lg font-bold mb-4">غرف الفندق</h2>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_غرف-الفندق.php'">إضافة جديد</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث...">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 px-4 py-2">اسم الغرفة</th>
                        <th class="border border-gray-400 px-4 py-2">حالة الغرفة</th>
                        <th class="border border-gray-400 px-4 py-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be fetched here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fetch records from backend
        fetch('../backend/غرف-الفندق.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 px-4 py-2">${record.اسم_الغرفة}</td>
                        <td class="border border-gray-400 px-4 py-2">${record.حالة_الغرفة}</td>
                        <td class="border border-gray-400 px-4 py-2">
                            <a href="edit_غرف-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/غرف-الفندق.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${record.اسم_الغرفة}</td>
                                <td class="border border-gray-400 px-4 py-2">${record.حالة_غرفة}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_غرف-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                fetch('../backend/غرف-الفندق.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${record.اسم_الغرفة}</td>
                                <td class="border border-gray-400 px-4 py-2">${record.حالة_غرفة}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_غرف-الفندق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/غرف-الفندق.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is implemented and returns the list of records in JSON format. The `deleteRecord` function sends a DELETE request to the backend API to delete the record with the specified ID.