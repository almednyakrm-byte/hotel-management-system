**edit_حجز-غرف.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجز-غرف.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and content
$page_title = 'Edit حجز_غرف';
$page_content = '
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">' . $page_title . '</h1>
        <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" value="' . $data['name'] . '">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description">' . $data['description'] . '</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status">
                    <option value="active" ' . ($data['status'] == 'active' ? 'selected' : '') . '>Active</option>
                    <option value="inactive" ' . ($data['status'] == 'inactive' ? 'selected' : '') . '>Inactive</option>
                </select>
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Update</button>
        </form>
    </div>
';

// Include footer
include 'footer.php';
?>

<script>
    // Fetch existing record details via GET
    fetch("../backend/حجز-غرف.php?id=<?php echo $id; ?>")
        .then(response => response.json())
        .then(data => {
            document.getElementById("name").value = data.name;
            document.getElementById("description").value = data.description;
            document.getElementById("status").value = data.status;
        })
        .catch(error => console.error(error));

    // Update form on submit
    document.getElementById("edit-form").addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch("../backend/حجز-غرف.php", {
            method: "PUT",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "list_حجز-غرف.php";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/حجز-غرف.php**

<?php
// Check if ID exists in URL
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Error: ID not found.'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if ID exists in database
// Replace with your database connection and query
$database = new PDO('sqlite:database.db');
$stmt = $database->prepare('SELECT * FROM حجز_غرف WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$data = $stmt->fetch();

// Check if data exists
if (empty($data)) {
    echo json_encode(array('success' => false, 'message' => 'Error: Record not found.'));
    exit;
}

// Update record via PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $database = new PDO('sqlite:database.db');
    $stmt = $database->prepare('UPDATE حجز_غرف SET name = :name, description = :description, status = :status WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->execute();

    echo json_encode(array('success' => true, 'message' => 'Record updated successfully.'));
    exit;
}

// Fetch existing record details via GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode($data);
    exit;
}