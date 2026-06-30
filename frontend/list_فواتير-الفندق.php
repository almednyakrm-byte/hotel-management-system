<!-- list_فواتير-الفندق.php -->

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
    <title>فواتير الفندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-500 hover:text-red-700">تسجيل خروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">فواتير الفندق</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_فواتير-الفندق.php'">إضافة جديد</button>
        <div class="mt-4">
            <input type="search" id="search" class="w-full p-2 mb-4 border border-gray-400 rounded" placeholder="بحث...">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">رقم الفاتورة</th>
                    <th class="border border-gray-400 p-2">تاريخ الفاتورة</th>
                    <th class="border border-gray-400 p-2">المبلغ</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', async () => {
            const searchQuery = searchInput.value.trim();
            const response = await fetch('../backend/فواتير-الفندق.php', {
                method: 'GET',
                params: { search: searchQuery }
            });
            const data = await response.json();
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-gray-400 p-2">${record.id}</td>
                    <td class="border border-gray-400 p-2">${record.date}</td>
                    <td class="border border-gray-400 p-2">${record.amount}</td>
                    <td class="border border-gray-400 p-2">
                        <a href="edit_فواتير-الفندق.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        });

        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الفاتورة؟')) {
                const response = await fetch('../backend/فواتير-الفندق.php', {
                    method: 'DELETE',
                    params: { id }
                });
                if (response.ok) {
                    alert('تم حذف الفاتورة بنجاح');
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف الفاتورة');
                }
            }
        }

        searchInput.focus();
        searchInput.addEventListener('input', () => {
            searchInput.value.trim() === '' ? window.location.reload() : null;
        });
    </script>
</body>
</html>



<!-- backend/فواتير-الفندق.php -->

<?php
// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $searchQuery = $_GET['search'] ?? '';
    $records = [];
    // Query database to fetch records
    // ...
    echo json_encode($records);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    // Query database to delete record
    // ...
    http_response_code(200);
    exit;
}