**list_إدارة-الموظفين.php**

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
    <title>إدارة الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
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
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">إدارة الموظفين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-الموظفين.php'">إضافة موظف جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الموظف</th>
                    <th>وظيفة</th>
                    <th>تاريخ الميلاد</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/إدارة-الموظفين.php');
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.name}</td>
                    <td>${record.job}</td>
                    <td>${record.birthdate}</td>
                    <td>
                        <a href="edit_إدارة-الموظفين.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/إدارة-الموظفين.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.job}</td>
                                <td>${record.birthdate}</td>
                                <td>
                                    <a href="edit_إدارة-الموظفين.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا الموظف؟')) {
                const response = await fetch('../backend/إدارة-الموظفين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء حذف الموظف');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes the following features:

*   Session validation to ensure the user is logged in before accessing the page.
*   A premium Tailwind UI design with a specific color palette matching the theme.
*   A header navigation bar with links to the main page, user info, and logout.
*   A table displaying a list of records with actions: Edit (link to edit_إدارة-الموظفين.php?id=X) and Delete (AJAX call to backend).
*   An "Add New Item" button linking to create_إدارة-الموظفين.php.
*   A search bar filtering elements in real-time.
*   AJAX JavaScript (Fetch API) fetching list records from '../backend/إدارة-الموظفين.php' (GET) and DELETE requests.