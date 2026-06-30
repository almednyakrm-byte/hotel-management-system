**create_فنادق.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-20 xl:px-20">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة فندق جديد</h2>
        <form id="create-hotel-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم الفندق</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="address" class="text-slate-900 font-bold">عنوان الفندق</label>
                    <input type="text" id="address" name="address" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="text-slate-900 font-bold">المدينة</label>
                    <input type="text" id="city" name="city" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="country" class="text-slate-900 font-bold">الدولة</label>
                    <input type="text" id="country" name="country" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label for="description" class="text-slate-900 font-bold">وصف الفندق</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-hotel-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فنادق.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_فنادق.php';
                    } else {
                        alert('Error adding hotel');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**Note:** This code assumes you have jQuery and Bootstrap installed. You may need to adjust the CSS classes and JavaScript code to match your specific Tailwind UI setup. Additionally, you will need to create a backend PHP file (`فنادق.php`) to handle the form submission and database insertion.