<?php
// edit_فنادق.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_فنادق.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-900 text-indigo-500 rounded">
        <h2 class="text-2xl font-bold mb-4">تعديل فندق</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم الفندق</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium mb-2">عنوان الفندق</label>
                <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium mb-2">هاتف الفندق</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <button type="submit" class="w-full p-2 pl-5 pr-5 bg-indigo-500 text-slate-900 text-base rounded-lg">تعديل</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        fetch(`../backend/فنادق.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('address').value = data.address;
                document.getElementById('phone').value = data.phone;
            });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/فنادق.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    address: formData.get('address'),
                    phone: formData.get('phone')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_فنادق.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>