**list_غرف.php**

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
    <title>غرف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">غرف</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_غرف.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الغرفة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/غرف.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.اسم_الغرفة}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                                <td><a href="edit_غرف.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/غرف.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.اسم_الغرفة}</td>
                                <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                                <td><a href="edit_غرف.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/غرف.php', {
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

**backend/غرف.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM غرف WHERE اسم_الغرفة LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM غرف";
}

// Fetch records
$result = $conn->query($sql);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output records
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM غرف WHERE id = '$id'";
    $conn->query($sql);
    echo json_encode(array('success' => true));
}

$conn->close();
?>