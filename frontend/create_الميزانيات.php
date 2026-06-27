**create_الميزانيات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة ميزانية جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم الميزانية</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="اسم الميزانية">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="amount" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">مبلغ الميزانية</label>
                    <input type="number" id="amount" name="amount" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="مبلغ الميزانية">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف الميزانية</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="وصف الميزانية"></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/الميزانيات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_الميزانيات.php';
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


**Note:** This code assumes that you have jQuery and Bootstrap installed in your project. Also, make sure to replace `../backend/الميزانيات.php` with the actual URL of your backend script that handles the form submission.