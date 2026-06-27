<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Define module slug
$mod_slug = 'إدارة-الموظفين';

// Define page title
$page_title = 'إضافة ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Premium Tailwind UI form -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-slate-900"><?= $page_title ?></h2>
    <form id="create-form" class="mt-8 space-y-6">
        <div class="rounded-md shadow-sm space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الموظف</label>
                <input type="text" id="name" name="name" autocomplete="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" autocomplete="phone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-slate-900">ال部门</label>
                <select id="department" name="department" autocomplete="department" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">اختر قسم</option>
                    <option value="قسم المالية">قسم المالية</option>
                    <option value="قسم الموارد البشرية">قسم الموارد البشرية</option>
                    <option value="قسم التسويق">قسم التسويق</option>
                </select>
            </div>
            <div>
                <label for="job_title" class="block text-sm font-medium text-slate-900">المسمى الوظيفي</label>
                <input type="text" id="job_title" name="job_title" autocomplete="job_title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
        <div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
        </div>
    </form>
</div>

<!-- AJAX JavaScript to POST form data -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>