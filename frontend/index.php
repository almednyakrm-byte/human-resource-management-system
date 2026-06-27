<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الموارد البشرية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">نظام إدارة الموارد البشرية</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">مرحباً</h2>
            <p class="text-gray-600">إدارة الموظفين، الميزانيات، التقارير</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900">عدد الموظفين</h3>
                    <p id="employee-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900">الميزانيات</h3>
                    <p id="budget-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-slate-900">التقارير</h3>
                    <p id="report-count" class="text-gray-600"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-slate-900">روابط سريعة</h2>
            <ul class="list-none p-0 m-0">
                <li class="mb-2">
                    <a href="employees.php" class="text-gray-600 hover:text-gray-900">الموظفين</a>
                </li>
                <li class="mb-2">
                    <a href="budgets.php" class="text-gray-600 hover:text-gray-900">الميزانيات</a>
                </li>
                <li class="mb-2">
                    <a href="reports.php" class="text-gray-600 hover:text-gray-900">التقارير</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('employee-count').textContent = data.employeeCount;
                document.getElementById('budget-count').textContent = data.budgetCount;
                document.getElementById('report-count').textContent = data.reportCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a backend API that returns the stats data in JSON format. You'll need to replace `/api/stats` with the actual URL of your API endpoint.

You'll also need to create a `logout.php` file that handles the logout logic.

Note that this code uses Tailwind CSS for styling, and it's assumed that you have it installed and configured properly. If you don't have Tailwind CSS installed, you can add it by running `npm install tailwindcss` and then creating a `tailwind.config.js` file with the necessary configuration.

Also, make sure to update the API endpoint URL and the logout logic to match your specific requirements.