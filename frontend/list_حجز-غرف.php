**list_حجز-غرف.php**

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
    <title>حجز_غرف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">حجز_غرف</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_حجز-غرف.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" placeholder="بحث" class="p-2 pl-10 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">اسم</th>
                    <th class="border border-gray-300 px-4 py-2">تاريخ الحجز</th>
                    <th class="border border-gray-300 px-4 py-2">حالة الحجز</th>
                    <th class="border border-gray-300 px-4 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.4/dist/fetch.min.js"></script>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/حجز-غرف.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-300 px-4 py-2">${record.اسم}</td>
                            <td class="border border-gray-300 px-4 py-2">${record.تاريخ_الحجز}</td>
                            <td class="border border-gray-300 px-4 py-2">${record.حالة_الحجز}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="edit_حجز-غرف.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/حجز-غرف.php', {
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
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/حجز-غرف.php**

<?php
// Assuming you have a database connection established
// and a function to fetch records from the database

function fetchRecords($search = null) {
    // Your database query to fetch records
    // If $search is not null, add a WHERE clause to filter records
    // ...
    return $records;
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = fetchRecords($search);
} else {
    $records = fetchRecords();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Your database query to delete a record
    // ...
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode($records);