<?php
// edit_نزلاء.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_نزلاء.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل نزلاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-slate-900 text-indigo-500 rounded">
        <h1 class="text-3xl font-bold mb-4">تعديل نزلاء</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم النزيل</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 rounded hover:bg-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/نزلاء.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/نزلاء.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_نزلاء.php';
                    }
                });
            });
        });
    </script>
</body>
</html>