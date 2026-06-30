**list_إدارة-فنادق.php**

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
    <title>إدارة فنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <img src="avatar.jpg" alt="avatar" class="w-10 h-10 rounded-full">
                <span class="ml-2"><?= $_SESSION['username'] ?></span>
                <button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة فنادق</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-فنادق.php'">إضافة جديد</button>
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="بحث...">
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">الاسم</th>
                    <th class="border border-gray-300 px-4 py-2">العنوان</th>
                    <th class="border border-gray-300 px-4 py-2">الإجراءات</th>
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

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach((record, index) => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/إدارة-فنادق.php');
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-300 px-4 py-2">${record.اسم}</td>
                        <td class="border border-gray-300 px-4 py-2">${record العنوان}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_إدارة-فنادق.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/إدارة-فنادق.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id }),
                    });
                    if (response.ok) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    </script>
</body>
</html>

**backend/إدارة-فنادق.php**

<?php
// Assuming you have a database connection established

// Retrieve all records from the database
$records = array();
$query = "SELECT * FROM إدارة_فنادق";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Output the records in JSON format
header('Content-Type: application/json');
echo json_encode($records);
?>