**list_خدمات.php**

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
    <title>خدمات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B5CFF;
        }
    </style>
</head>
<body class="bg-slate-900">
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-indigo-500"><?= $_SESSION['username']; ?></span>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-indigo-500 text-3xl mb-4">خدمات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_خدمات.php'">إضافة خدمة جديدة</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="bg-gray-800 text-gray-300 rounded w-full py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="search()">بحث</button>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-indigo-500 text-left py-2">اسم الخدمة</th>
                    <th class="text-indigo-500 text-left py-2">حذف</th>
                    <th class="text-indigo-500 text-left py-2">تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="py-2"><?= $record['name']; ?></td>
                        <td class="py-2">
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                        </td>
                        <td class="py-2">
                            <a href="edit_خدمات.php?id=<?= $record['id']; ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/خدمات.php', { method: 'GET' });
            const data = await response.json();
            return data;
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الخدمة؟')) {
                const response = await fetch('../backend/خدمات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id }) });
                if (response.ok) {
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف الخدمة');
                }
            }
        }

        // Search records
        function search() {
            const searchValue = document.getElementById('search').value;
            const records = document.getElementById('records');
            records.innerHTML = '';
            fetchRecords().then(data => {
                data.forEach(record => {
                    if (record.name.includes(searchValue)) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="py-2">${record.name}</td>
                            <td class="py-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="py-2">
                                <a href="edit_خدمات.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }
    </script>
</body>
</html>

<?php
// Function to fetch records from backend
function fetchRecords() {
    $records = array();
    // Fetch records from backend
    // Replace with actual backend code
    return $records;
}
?>

Note: Replace the `fetchRecords()` function with actual backend code to fetch records from the database.