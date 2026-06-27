**list_الموظفين.php**

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
    <title>الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500 hover:text-red-700">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">الموظفين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_الموظفين.php'">إضافة موظف جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search-input" placeholder="بحث...">
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
            if (searchQuery === '') {
                loadRecords();
            } else {
                fetch('../backend/الموظفين.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const html = records.map(record => `
                        <tr>
                            <td>${record.اسم_الموظف}</td>
                            <td>${record.وظيفة}</td>
                            <td>${record.تاريخ_الميلاد}</td>
                            <td>
                                <a href="edit_الموظفين.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = html;
                });
            }
        }

        function loadRecords() {
            fetch('../backend/الموظفين.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                const records = data.records;
                const html = records.map(record => `
                    <tr>
                        <td>${record.اسم_الموظف}</td>
                        <td>${record.وظيفة}</td>
                        <td>${record.تاريخ_الميلاد}</td>
                        <td>
                            <a href="edit_الموظفين.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    </tr>
                `).join('');
                recordsTable.innerHTML = html;
            });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا الموظف؟')) {
                fetch('../backend/الموظفين.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/الموظفين.php**

<?php
// Assuming you have a database connection established
$db = new PDO('dsn', 'username', 'password');

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $db->prepare('SELECT * FROM الموظفين WHERE اسم_الموظف LIKE :search');
    $stmt->bindParam(':search', $searchQuery);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->prepare('SELECT * FROM الموظفين');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode(['records' => $records]);

Note: This is a basic implementation and you should adjust it according to your specific requirements and database schema.