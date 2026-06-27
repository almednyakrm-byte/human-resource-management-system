**list_التقارير.php**

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
    <title>التقارير</title>
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
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">التقارير</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_التقارير.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم التعريف</th>
                    <th>اسم الملف</th>
                    <th>حالة الملف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        // Fetch records on page load
        fetch('../backend/التقارير.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.status}</td>
                        <td>${record.date}</td>
                        <td>
                            <a href="edit_التقارير.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                            <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsContainer.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search functionality
        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/التقارير.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.status}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_التقارير.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                    <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/التقارير.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.status}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_التقارير.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                    <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/التقارير.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/التقارير.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM records WHERE name LIKE '%$searchQuery%' OR status LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM records";
}

$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output data
echo json_encode($data);

// Close connection
$conn->close();
?>

Note: This is a basic example and you should adapt it to your specific needs and database schema. Also, make sure to replace the placeholders with your actual database credentials and table names.