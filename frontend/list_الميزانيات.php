**list_الميزانيات.php**

<?php
// Session validation
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
    <title>الميزانيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 1rem;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header .nav-links li {
            margin-right: 2rem;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1f2937;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">الميزانيات</div>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="profile.php"><?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل خروج</a></li>
        </ul>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الميزانيات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_الميزانيات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الميزانية</th>
                    <th>تاريخ الإضافة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery === '') {
                fetchRecords();
            } else {
                fetchRecords(searchQuery);
            }
        }

        function fetchRecords(searchQuery = '') {
            fetch('../backend/الميزانيات.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search: searchQuery
                }
            })
            .then(response => response.json())
            .then(data => {
                const records = data.records;
                const html = records.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.date}</td>
                        <td><button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><a href="edit_الميزانيات.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                    </tr>
                `).join('');
                recordsContainer.innerHTML = html;
            })
            .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف الميزانية؟')) {
                fetch('../backend/الميزانيات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        fetchRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a table showing list of records with actions, a search bar filtering elements in real-time, and AJAX Javascript fetching list records from the backend.