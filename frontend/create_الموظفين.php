**create_الموظفين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة موظف جديد</h2>
        <form id="create-employee-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold text-sm mb-2">اسم الموظف</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="text-slate-900 font-bold text-sm mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="text-slate-900 font-bold text-sm mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="position" class="text-slate-900 font-bold text-sm mb-2">الوظيفة</label>
                <input type="text" id="position" name="position" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-employee-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/الموظفين.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_الموظفين.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/الموظفين.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['position'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    // Database connection
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');

    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // SQL query
    $sql = "INSERT INTO employees (name, email, phone, position) VALUES ('$name', '$email', '$phone', '$position')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo 'Error: No data submitted';
}
?>