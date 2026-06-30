**edit_غرف-فنادق.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/غرف-فنادق.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل غرفة فندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="container mx-auto p-4 mt-6">
    <h1 class="text-3xl font-bold mb-4">تعديل غرفة فندق</h1>

    <form id="edit-form" class="w-full max-w-md">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الغرفة</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الغرفة</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $existingRecord['description'] ?></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">سعر الغرفة</label>
            <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['price'] ?>">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit-form').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'PUT',
                url: '../backend/غرف-فنادق.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>

</body>
</html>


**backend/غرف-فنادق.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$existingRecord = getRecordFromDatabase($id);

// Return JSON response
echo json_encode($existingRecord);

function getRecordFromDatabase($id) {
    // Replace with your actual database query
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $db->prepare('SELECT * FROM غرف_فنادق WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    return $existingRecord;
}


Note: Replace `your_database`, `your_username`, and `your_password` with your actual database credentials. Also, replace `getRecordFromDatabase` function with your actual database query.