**edit_الميزانيات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/الميزانيات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set page title and mod slug
$page_title = 'تعديل الميزانية';
$mod_slug = 'الميزانيات';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold text-sm mb-2">اسم الميزانية</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="amount" class="text-slate-900 font-bold text-sm mb-2">مبلغ الميزانية</label>
                <input type="number" id="amount" name="amount" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" value="<?= $data['amount'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التغييرات</button>
        </form>
    </div>
</div>

<!-- JavaScript code -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/الميزانيات.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('amount').value = data.amount;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/الميزانيات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/الميزانيات.php**

<?php
// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$query = "SELECT * FROM الميزانيات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $query = "UPDATE الميزانيات SET name = '$name', amount = '$amount' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
} else {
    // Return existing record details as JSON
    echo json_encode($data);
}
?>