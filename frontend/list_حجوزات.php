**list_حجوزات.php**

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
    <title>حجوزات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header .nav-link {
            color: #fff;
        }
        .header .nav-link:hover {
            color: #fff;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 50%;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="header py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="nav-link text-lg font-bold">الرئيسية</a>
                <div class="flex items-center">
                    <img src="profile-picture.jpg" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="nav-link text-lg font-bold ml-4">تسجيل خروج</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 mt-4">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold">حجوزات</h2>
            <a href="create_حجوزات.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="mt-4">
            <input type="search" id="search-bar" class="search-bar" placeholder="بحث...">
        </div>
        <div class="table mt-4">
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
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-bar');
        const tableBody = document.getElementById('table-body');

        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = tableBody.children;
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const cells = row.children;
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    const text = cell.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        fetch('../backend/حجوزات.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.اسم_الحجز}</td>
                    <td>${item.تاريخ_الحجز}</td>
                    <td>${item.حالة_الحجز}</td>
                    <td>
                        <a href="edit_حجوزات.php?id=${item.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error(error));

        function deleteItem(id) {
            fetch('../backend/حجوزات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف الحجز بنجاح');
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف الحجز');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**backend/حجوزات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all records
$query = "SELECT * FROM حجوزات";
$result = $conn->query($query);

// Fetch records
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close connection
$conn->close();

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($data);

Note: This is a basic example and you should adjust the code to fit your specific needs. Also, make sure to replace the database connection details with your actual database credentials.