**list_مدفوعات.php**

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
    <title>مدفوعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #ffffff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مدفوعات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مدفوعات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/مدفوعات.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            displayRecords(data);
        }

        // Display records in table
        function displayRecords(data) {
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.المبلغ}</td>
                    <td>${record.التاريخ}</td>
                    <td>
                        <a href="edit_مدفوعات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/مدفوعات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            });
            if (response.ok) {
                fetchRecords();
            } else {
                alert('Error deleting record');
            }
        }

        // Search records
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetchRecords().then(() => {
                const records = document.getElementById('records');
                const rows = records.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let match = false;
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.includes(search)) {
                            match = true;
                            break;
                        }
                    }
                    if (!match) {
                        rows[i].style.display = 'none';
                    } else {
                        rows[i].style.display = 'table-row';
                    }
                }
            });
        }

        // Initialize fetch records
        fetchRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete records from the backend.