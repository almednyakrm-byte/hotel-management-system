**list_customers.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-blue-500 {
            background-color: #1a73ff;
        }
        .text-gray-200 {
            color: #f7f7f7;
        }
    </style>
</head>
<body class="bg-gray-200">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
            <div class="flex items-center">
                <p class="text-gray-700 mr-2">Welcome, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
            </div>
        </div>
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl text-gray-700">Customers</h1>
            <a href="create_customers.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 mb-4 text-gray-700" placeholder="Search...">
            <button id="search-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Email</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="customer-list">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/customers.php');
                $customers = json_decode($response, true);
                foreach ($customers as $customer) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?= $customer['name'] ?></td>
                        <td class="border border-gray-400 p-2"><?= $customer['email'] ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_customers.php?id=<?= $customer['id'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteCustomer(<?= $customer['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const customerList = document.getElementById('customer-list');

        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value.toLowerCase();
            const customers = JSON.parse(localStorage.getItem('customers'));
            const filteredCustomers = customers.filter(customer => customer.name.toLowerCase().includes(searchTerm) || customer.email.toLowerCase().includes(searchTerm));
            renderCustomerList(filteredCustomers);
        });

        function deleteCustomer(id) {
            fetch(`../backend/delete_customer.php?id=${id}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const customers = JSON.parse(localStorage.getItem('customers'));
                        const filteredCustomers = customers.filter(customer => customer.id !== id);
                        localStorage.setItem('customers', JSON.stringify(filteredCustomers));
                        renderCustomerList(filteredCustomers);
                    } else {
                        alert('Error deleting customer');
                    }
                })
                .catch(error => console.error(error));
        }

        function renderCustomerList(customers) {
            const customerListHtml = customers.map(customer => `
                <tr>
                    <td class="border border-gray-400 p-2">${customer.name}</td>
                    <td class="border border-gray-400 p-2">${customer.email}</td>
                    <td class="border border-gray-400 p-2">
                        <a href="edit_customers.php?id=${customer.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteCustomer(${customer.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
            customerList.innerHTML = customerListHtml;
        }

        // Fetch data from backend on page load
        fetch('../backend/customers.php')
            .then(response => response.json())
            .then(data => {
                localStorage.setItem('customers', JSON.stringify(data));
                renderCustomerList(data);
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>

**Note:** This code assumes that you have a `customers.php` file in the `../backend` directory that returns a JSON array of customers. You will need to modify the `fetch` URLs and the `delete_customer.php` file to match your backend API.