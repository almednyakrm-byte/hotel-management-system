**edit_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مواعيد.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'Edit ' . $existingRecord['title'];

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $pageTitle ?></h1>
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-slate-900">Time</label>
                <input type="time" id="time" name="time" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['time'] ?>">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-slate-900">Location</label>
                <input type="text" id="location" name="location" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['location'] ?>">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</main>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/مواعيد.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('date').value = data.date;
            document.getElementById('time').value = data.time;
            document.getElementById('location').value = data.location;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/مواعيد.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مواعيد.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/مواعيد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

if (empty($record)) {
    http_response_code(404);
    echo 'Record not found';
    exit;
}

// Update record
update_record($id, $_POST);

// Return success message
http_response_code(200);
echo json_encode(['success' => true]);

function get_record($id) {
    // Implement database query to get record by id
    // Return record data
}

function update_record($id, $data) {
    // Implement database query to update record
    // Return success message
}
?>