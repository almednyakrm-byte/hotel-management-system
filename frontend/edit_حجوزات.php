**edit_حجوزات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجوزات.php?id=' . $id;
$data = json_decode(file_get_contents($url), true);

// Check if data is retrieved successfully
if ($data) {
    // Set form fields
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
} else {
    echo 'Error retrieving data';
    exit;
}

// Include header and navigation
include 'header.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit حجوزات</h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" value="<?= $name ?>" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $description ?></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" value="<?= $price ?>" class="block w-full p-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    const url = '../backend/حجوزات.php?id=<?= $id ?>';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Set form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('price').value = data.price;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/حجوزات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_حجوزات.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/حجوزات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID is numeric
if (!is_numeric($id)) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Fetch existing record details
$stmt = $conn->prepare("SELECT * FROM حجوزات WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if data is retrieved successfully
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No data found']);
}

// Close connection
$conn->close();
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit حجوزات</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-slate-900 py-4">
        <div class="container mx-auto flex justify-between">
            <a href="#" class="text-white font-bold text-lg">Logo</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-white hover:text-indigo-500">Home</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">About</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">Contact</a></li>
            </ul>
        </div>
    </nav>
    <!-- Main content -->
    <main>
        <!-- Content will be rendered here -->
    </main>
</body>
</html>


**footer.php**

<!-- Footer -->
<footer class="bg-slate-900 py-4">
    <div class="container mx-auto text-center text-white">
        &copy; 2023 Company Name
    </div>
</footer>


Note: You'll need to replace the placeholders in the code with your actual database credentials and table names. Additionally, you'll need to create a `list_حجوزات.php` page to handle the redirect after successful form submission.