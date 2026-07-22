**list_إمتيازات.php**

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
    <title>إمتيازات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23 !important;
        }
        .text-indigo-500 {
            color: #6b7280 !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <p class="text-indigo-500 mr-2">مرحباً <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-500 hover:text-white">تسجيل الخروج</a>
            </div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-md">
            <h2 class="text-lg font-bold mb-2">إمتيازات</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إمتيازات.php'">إضافة جديد</button>
            <div class="flex justify-between items-center mt-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">إسم</th>
                        <th class="px-4 py-2">وصف</th>
                        <th class="px-4 py-2">حذف</th>
                        <th class="px-4 py-2">تعديل</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <?php
                    // Fetch records from backend
                    $records = fetchRecords();
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $record['name'] ?></td>
                            <td class="px-4 py-2"><?= $record['description'] ?></td>
                            <td class="px-4 py-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="edit_إمتيازات.php?id=<?= $record['id'] ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/إمتيازات.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record.name}</td>
                            <td class="px-4 py-2">${record.description}</td>
                            <td class="px-4 py-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="edit_إمتيازات.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/إمتيازات.php', {
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
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/إمتيازات.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/إمتيازات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'name' => 'إسم السجل 1', 'description' => 'وصف السجل 1');
$records[] = array('id' => 2, 'name' => 'إسم السجل 2', 'description' => 'وصف السجل 2');
$records[] = array('id' => 3, 'name' => 'إسم السجل 3', 'description' => 'وصف السجل 3');

// Search records
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false || strpos($record['description'], $searchValue) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));
?>

Note: This code assumes that you have a backend script (`backend/إمتيازات.php`) that fetches records from a database and handles search and delete operations. You should replace the placeholder code in the backend script with your actual database logic.