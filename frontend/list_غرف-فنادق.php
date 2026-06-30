<!-- list_غرف-فنادق.php -->

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
    <title>غرف فندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2">مرحباً, <?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">غرف فندق</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_غرف-فنادق.php'">إضافة جديد</button>
        <div class="flex justify-between mt-4">
            <input type="search" id="search" class="w-full p-2 mb-4" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>رقم الغرفة</th>
                    <th>اسم الغرفة</th>
                    <th>حالة الغرفة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/غرف-فنادق.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.status}</td>
                        <td>
                            <a href="edit_غرف-فنادق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value;
            fetchRecords(searchValue);
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/غرف-فنادق.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                    if (response.ok) {
                        fetchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Load records on page load
        fetchRecords();
    </script>
</body>
</html>