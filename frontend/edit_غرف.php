**edit_غرف.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/غرف.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found.';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit غرف';
$modSlug = 'غرف';

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/غرف.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Create AJAX PUT request
        fetch('../backend/غرف.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_<?= $modSlug ?>.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/غرف.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace this with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'Existing Record Name',
    'description' => 'Existing Record Description'
);

// Return existing record details as JSON
echo json_encode($existingRecord);
?>