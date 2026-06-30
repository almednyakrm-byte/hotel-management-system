<?php
// edit_فواتير-الفندق.php

// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/فواتير-الفندق.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فاتورة الفندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+Dz00i+YfaG/7LpZHHJ4fQf6Ipwsb" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNwNwOgKlmWHRsye" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mx-auto p-4 mt-12">
        <h2 class="text-2xl font-bold mb-4">تعديل فاتورة الفندق</h2>
        <form id="edit-form" class="bg-white p-8 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الفاتورة:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $existingRecord['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الفاتورة:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $existingRecord['description']; ?></textarea>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">مبلغ الفاتورة:</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $existingRecord['amount']; ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/فواتير-الفندق.php',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'تم التعديل بنجاح!',
                            text: 'تم تعديل الفاتورة بنجاح.',
                            icon: 'success',
                            confirmButtonText: 'موافق'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_فواتير-الفندق.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/فواتير-الفندق.php

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$existingRecord = // fetch record from database using $id

// Output JSON response
header('Content-Type: application/json');
echo json_encode($existingRecord);