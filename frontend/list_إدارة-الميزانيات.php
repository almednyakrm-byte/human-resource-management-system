**list_إدارة-الميزانيات.php**

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
    <title>إدارة الميزانيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
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
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500"> | </span>
        <span class="text-slate-900">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <span class="text-indigo-500"> | </span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">إدارة الميزانيات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-الميزانيات.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنصر</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/إدارة-الميزانيات.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record العنصر}</td>
                            <td>${record الوصف}</td>
                            <td>
                                <a href="edit_إدارة-الميزانيات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/إدارة-الميزانيات.php')
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record العنصر}</td>
                            <td>${record الوصف}</td>
                            <td>
                                <a href="edit_إدارة-الميزانيات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف العنصر؟')) {
                fetch('../backend/إدارة-الميزانيات.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/إدارة-الميزانيات.php**

<?php
// Assuming you have a database connection established
// and a table named 'إدارة_الميزانيات' with columns 'id', 'العنصر', 'الوصف'

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $records = array_filter($db->query("SELECT * FROM إدارة_الميزانيات WHERE العنصر LIKE '%$searchQuery%' OR الوصف LIKE '%$searchQuery%'")->fetchAll());
} else {
    $records = $db->query("SELECT * FROM إدارة_الميزانيات")->fetchAll();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db->query("DELETE FROM إدارة_الميزانيات WHERE id = $id");
    echo json_encode(['success' => true]);
} else {
    echo json_encode($records);
}
?>

Note: This is a basic implementation and you should adjust it according to your specific requirements and database schema. Also, make sure to validate user input and handle errors properly.