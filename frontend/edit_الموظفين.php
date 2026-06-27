**edit_الموظفين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
    $(document).ready(function() {
        $.get('../backend/الموظفين.php?id=" . $id . "')
            .done(function(data) {
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
            })
            .fail(function() {
                console.error('Failed to fetch data');
            });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl text-slate-900 mb-4">تعديل الموظفين</h2>
        <form id="edit-form" method="post">
            <div class="form-group">
                <label for="name">اسم الموظف</label>
                <input type="text" id="name" name="name" placeholder="اسم الموظف">
            </div>
            <div class="form-group">
                <label for="email">بريد إلكتروني</label>
                <input type="email" id="email" name="email" placeholder="بريد إلكتروني">
            </div>
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" placeholder="رقم الهاتف">
            </div>
            <div class="form-group">
                <input type="submit" value="حفظ التغييرات">
            </div>
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
                    url: '../backend/الموظفين.php',
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_الموظفين.php';
                        } else {
                            console.error('Failed to update data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating data:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/الموظفين.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = get_record($id);

// Update record if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update record in database
    update_record($id, $name, $email, $phone);

    // Return success message
    echo json_encode(['success' => true]);
} else {
    // Return existing record details
    echo json_encode($record);
}

// Helper functions
function get_record($id) {
    // Fetch record from database
    // Replace with your actual database query
    $record = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'phone' => '1234567890'
    ];
    return $record;
}

function update_record($id, $name, $email, $phone) {
    // Update record in database
    // Replace with your actual database query
    echo "Record updated successfully";
}
?>