**create_التقارير.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Insert data into database
    $query = "INSERT INTO التقارير (name, description, status) VALUES ('$name', '$description', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to list page
        header('Location: list_التقارير.php');
        exit;
    } else {
        // Display error message
        $error = 'Error adding record';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create report form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create Report</h2>
    <form id="create-report-form" method="POST" class="space-y-4">
        <div class="flex flex-col">
            <label for="name" class="text-sm font-bold text-slate-900 mb-2">Name:</label>
            <input type="text" id="name" name="name" class="px-4 py-2 text-sm text-slate-900 rounded-lg border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="flex flex-col">
            <label for="description" class="text-sm font-bold text-slate-900 mb-2">Description:</label>
            <textarea id="description" name="description" class="px-4 py-2 text-sm text-slate-900 rounded-lg border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="flex flex-col">
            <label for="status" class="text-sm font-bold text-slate-900 mb-2">Status:</label>
            <select id="status" name="status" class="px-4 py-2 text-sm text-slate-900 rounded-lg border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="px-4 py-2 text-sm text-indigo-500 rounded-lg bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">Create Report</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-report-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/التقارير.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_التقارير.php';
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


**backend/التقارير.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Insert data into database
    $query = "INSERT INTO التقارير (name, description, status) VALUES ('$name', '$description', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Return success message
        echo json_encode(array('success' => true));
    } else {
        // Return error message
        echo json_encode(array('success' => false, 'error' => 'Error adding record'));
    }
}