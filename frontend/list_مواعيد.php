**list_مواعيد.php**

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
    <title>مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span>مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مواعيد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>وقت</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['time']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مواعيد.php?id=<?php echo $record['id']; ?>'">تعديل</button>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/مواعيد.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.time}</td>
                            <td>${record.status}</td>
                            <td>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مواعيد.php?id=${record.id}'">تعديل</button>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مواعيد.php', {
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
                        alert('حدث خطأ');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/مواعيد.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/مواعيد.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'اسم السجل 1',
    'date' => '2022-01-01',
    'time' => '10:00',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 2,
    'name' => 'اسم السجل 2',
    'date' => '2022-01-02',
    'time' => '11:00',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 3,
    'name' => 'اسم السجل 3',
    'date' => '2022-01-03',
    'time' => '12:00',
    'status' => 'مفعل'
);

// Search records
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));


Note: This code assumes that you have a database setup and a backend script (`backend/مواعيد.php`) that fetches records from the database. You should replace the hardcoded records in the backend script with actual database queries.