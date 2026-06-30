**create_حجز-غرف.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Form data
$data = array(
    'name' => '',
    'phone' => '',
    'email' => '',
    'room_number' => '',
    'check_in' => '',
    'check_out' => '',
    'status' => ''
);

// Form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['name'] = $_POST['name'];
    $data['phone'] = $_POST['phone'];
    $data['email'] = $_POST['email'];
    $data['room_number'] = $_POST['room_number'];
    $data['check_in'] = $_POST['check_in'];
    $data['check_out'] = $_POST['check_out'];
    $data['status'] = $_POST['status'];

    // AJAX request
    $ajax_url = '../backend/حجز-غرف.php';
    $ajax_data = array(
        'name' => $data['name'],
        'phone' => $data['phone'],
        'email' => $data['email'],
        'room_number' => $data['room_number'],
        'check_in' => $data['check_in'],
        'check_out' => $data['check_out'],
        'status' => $data['status']
    );

    $ajax_response = json_decode(send_ajax_request($ajax_url, $ajax_data), true);

    if ($ajax_response['success']) {
        header('Location: list_حجز-غرف.php');
        exit;
    } else {
        $errors = $ajax_response['errors'];
    }
}

// Send AJAX request
function send_ajax_request($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Form fields
?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-4">حجز غرف</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">اسم المستخدم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['phone'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="room_number" class="block text-sm font-bold mb-2">رقم الغرفة</label>
                <input type="text" id="room_number" name="room_number" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['room_number'] ?>">
            </div>
            <div class="mb-4">
                <label for="check_in" class="block text-sm font-bold mb-2">تاريخ الدخول</label>
                <input type="date" id="check_in" name="check_in" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['check_in'] ?>">
            </div>
            <div class="mb-4">
                <label for="check_out" class="block text-sm font-bold mb-2">تاريخ الخروج</label>
                <input type="date" id="check_out" name="check_out" class="block w-full p-2 border border-gray-300 rounded-lg" value="<?= $data['check_out'] ?>">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-bold mb-2">الحالة</label>
                <select id="status" name="status" class="block w-full p-2 border border-gray-300 rounded-lg">
                    <option value="">اختر الحالة</option>
                    <option value="active" <?= ($data['status'] == 'active') ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= ($data['status'] == 'inactive') ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">حجز</button>
        </form>
        <?php if (isset($errors)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>


**create_حجز-غرف.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/حجز-غرف.php',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = 'list_حجز-غرف.php';
                } else {
                    var errors = response.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).parent().addClass('has-error');
                        $('#' + key).parent().append('<span class="help-block">' + value + '</span>');
                    });
                }
            }
        });
    });
});


**حجز-غرف.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $room_number = $_POST['room_number'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $status = $_POST['status'];

    // Validate form data
    $errors = array();
    if (empty($name)) {
        $errors[] = 'اسم المستخدم مطلوب';
    }
    if (empty($phone)) {
        $errors[] = 'رقم الهاتف مطلوب';
    }
    if (empty($email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    }
    if (empty($room_number)) {
        $errors[] = 'رقم الغرفة مطلوب';
    }
    if (empty($check_in)) {
        $errors[] = 'تاريخ الدخول مطلوب';
    }
    if (empty($check_out)) {
        $errors[] = 'تاريخ الخروج مطلوب';
    }
    if (empty($status)) {
        $errors[] = 'الحالة مطلوبة';
    }

    // Insert data into database
    if (empty($errors)) {
        $query = "INSERT INTO حجز_غرف (name, phone, email, room_number, check_in, check_out, status) VALUES ('$name', '$phone', '$email', '$room_number', '$check_in', '$check_out', '$status')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'errors' => array('حدث خطأ أثناء الحجز'));
        }
    } else {
        $response = array('success' => false, 'errors' => $errors);
    }

    // Output response
    echo json_encode($response);
}
?>