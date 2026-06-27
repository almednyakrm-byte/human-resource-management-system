// edit_إدارة-الميزانيات.php

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/إدارة-الميزانيات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الميزانيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل إدارة الميزانيات</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">وصف الإدارة</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-الميزانيات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_إدارة-الميزانيات.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/إدارة-الميزانيات.php

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Error: ID is required';
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = array(
    'id' => $id,
    'name' => 'إدارة الميزانيات',
    'description' => 'وصف لإدارة الميزانيات'
);

echo json_encode($record);