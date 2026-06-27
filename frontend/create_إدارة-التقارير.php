**create_إدارة-التقارير.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة إدارة التقارير</h2>
        <form id="create-report-management-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="name">اسم الإدارة</label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" placeholder="اسم الإدارة">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="description">وصف الإدارة</label>
                    <textarea class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" rows="4" placeholder="وصف الإدارة"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="status">حالة الإدارة</label>
                    <select class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="status">
                        <option value="">اختر حالة</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="created_at">تاريخ الإنشاء</label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="created_at" type="date" placeholder="تاريخ الإنشاء">
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-report-management-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-التقارير.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_إدارة-التقارير.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**backend/إدارة-التقارير.php**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status']) && isset($_POST['created_at'])) {
    // Prepare SQL query
    $sql = "INSERT INTO إدارة_التقارير (name, description, status, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['description'], $_POST['status'], $_POST['created_at']);
    // Execute query
    if ($stmt->execute()) {
        // Return success response
        echo json_encode(array('success' => true, 'message' => 'إدارة التقارير تمت إضافتها بنجاح'));
    } else {
        // Return error response
        echo json_encode(array('success' => false, 'message' => 'خطأ في إضافة الإدارة'));
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Return error response
    echo json_encode(array('success' => false, 'message' => 'بيانات الإضافة غير صالحة'));
}
?>