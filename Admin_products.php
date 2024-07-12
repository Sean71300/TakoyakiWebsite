<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Transaction</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Page background */
            margin: 0; 
        }
        .sidebar {
            background-color: white;
            height: 100vh; /* Full height sidebar */
            position: fixed;
            top: 0;
            left: -220px; /* Start off-screen */
            width: 220px;
            padding-top: 20px;
            transition: all 0.3s ease;
            z-index: 1030; 
        }
        .sidebar.active {
            left: 0; /* Slide in when active */
        }
        .sidebar .admin-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 10px;
            overflow: hidden;
            margin-left: 55px;
        }
        .sidebar .admin-icon img {
            width: 100%;
            height: auto;
        }
        .sidebar .admin-text {
            font-size: 16px;
            margin-bottom: 20px;
            margin-left: 60px;
            color: black;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            margin-bottom: 30px;
            margin-left: 15px;
        }
        .sidebar ul li a {
            display: flex;
            color: black;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }
        .sidebar ul li a .fa-fw {
            margin-right: 10px; /* Adjust margin between icon and text */
        }
        .sidebar ul li a:hover {
            background-color: #FFC10B;
        }
        .edit-icon,
        .delete-icon {
            cursor: pointer;
            margin-left: 10px;
            color: #FFC10B; 
        }
        .edit-icon:hover,
        .delete-icon:hover {
            color: #953728; 
        }
        .content {
            padding: 20px;
            margin-left: 0; 
            transition: margin-left 0.3s ease; /* Smooth transition for content */
            z-index: 1020; 
            position: relative; 
        }
        .container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .transaction-history {
            margin-top: 20px;
            overflow-x: auto; 
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .transaction-table th,
        .transaction-table td {
            padding: 12px;
            text-align: left;
            border-top: 1px solid gray; /* Border lines */
        }
        .transaction-table th {
            font-weight: bold;
            color: #953728;
            text-transform: uppercase;
        }

        /* Search Bar */
        .search-bar {
            margin-bottom: 20px;
            text-align: right;
        }
        .search-bar input[type="text"] {
            width: 100%; 
            max-width: 400px; 
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 8px 15px;
            background-color: #FFC10B;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover {
            background-color: #e0a800;
        }
        
        /* Media Queries */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px; 
            }
            .sidebar.active {
                left: 0; /* Slide in when active */
            }
            .content {
                margin-left: 0; /* Content area adjusts */
            }
            .container {
                padding: 10px; /* Adjust padding for smaller screens */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center">
            <div class="admin-icon">
                <img src="https://via.placeholder.com/60" alt="Admin Image">
            </div>
            <div class="admin-text">Admin</div>
        </div>
        <ul>
            <li><a href="Admin_dashboard.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li><a href="Admin_transaction.php"><i class="fas fa-history fa-fw"></i> Transaction History</a></li>
            <li><a href="Admin_employee.php"><i class="fas fa-user-tie fa-fw"></i> Employees</a></li>
            <li><a href="#"><i class="fas fa-users fa-fw"></i> Customers</a></li>
            <li><a href="#"><i class="fas fa-th fa-fw"></i> Categories</a></li>
            <li><a href="Admin_products.php"><i class="fas fa-box-open fa-fw"></i> Products</a></li>
			<li><a href="#"><i class="fas fa-star fa-fw"></i> Ratings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt fa-fw"></i> Sign Out</a></li>
			<li><a href="#"><i class="fas fa-question-circle fa-fw"></i>Help</a></li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <!-- Hamburger icon -->
                <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                    <i class="fas fa-bars"></i> 
                </button>
                <div class="search-bar ms-auto">
                    <input type="text" placeholder="Search transactions..." id="searchInput">
                    <button type="button" class="btn" onclick="searchTransactions()"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </nav>
        
        <div class="container">
            <div class="transaction-history">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th> <!-- Added missing header -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data -->
                        <tr>
                            <td>10241001</td>
                            <td>OctoBits</td>
                            <td>Takoyaki</td>
                            <td>Php 100.00</td>
                            <td>Available</td>
                            <td>
                                <i class="fas fa-edit edit-icon" title="Edit"></i>
                                <i class="fas fa-trash delete-icon" title="Delete"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('main-content').classList.toggle('active');
            adjustContentMargin();
        });

        function adjustContentMargin() {
            const sidebarWidth = document.getElementById('sidebar').offsetWidth;
            const content = document.getElementById('main-content');
            
            if (document.getElementById('sidebar').classList.contains('active')) {
                content.style.marginLeft = sidebarWidth + 'px';
            } else {
                content.style.marginLeft = '0';
            }
        }
        
        function searchTransactions() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.transaction-table tbody tr');

            rows.forEach(row => {
                const rowData = row.textContent.toLowerCase();
                if (rowData.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // If input is empty, show all rows
            if (input === '') {
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        }
        
        // For input field to detect changes in input value
        document.getElementById('searchInput').addEventListener('input', searchTransactions);

        adjustContentMargin();

        window.addEventListener('resize', adjustContentMargin);
    </script>
</body>
</html>
