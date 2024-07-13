<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -220px; /* Hidden on larger screens */
            width: 220px;
            padding-top: 20px;
            transition: left 0.3s ease; /* Transition for sidebar movement */
            z-index: 1030;
        }

        .sidebar.active {
            left: 0; /* Show sidebar on toggle */
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

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 20px;
            margin-left: 15px;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center; /* Center icon and text vertically */
            color: black;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .sidebar ul li a .fa-fw {
            margin-right: 10px;
        }

        .sidebar ul li a:hover {
            background-color: #FFC10B;
        }

        .sidebar ul li a .nav-text {
            display: block; /* Ensure text is displayed */
            margin-left: 10px; /* Add margin between icon and text */
        }

        body.sidebar-active {
            margin-left: 220px;
        }

        /* Navbar */
        .navbar {
            background-color: #e0a800; /* Change background color of navbar */
        }

        .navbar-toggler {
            border-color: black; /* Custom border color for navbar-toggler */
        }

        /* Container Styles */
        .container-fluid-custom {
            background-color: #e0a800; /* Custom background color for container-fluid */
        }

        /* Transaction Table Styles */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for a card-like effect */
            background-color: white; /* White background for the table */
        }

        .transaction-table th,
        .transaction-table td {
            padding: 12px; /* Increased padding for better spacing */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .transaction-table th {
            background-color: #f2f2f2; /* Light gray background for headers */
        }

        .transaction-table tbody tr:hover {
            background-color: #f5f5f5; /* Light gray background on hover */
        }

        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center; /* Align items vertically */
        }

        .search-bar input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 250px;
            margin-right: 10px; /* Adjust spacing between input and button */
        }

        .search-bar button {
            padding: 10px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            cursor: pointer;
        }

        .search-bar button i {
            margin-right: 5px; /* Adjust spacing between icon and text */
            color: red; /* Set search icon color */
        }

        /* Dropdown Style */
        .dropdown {
            position: relative;
            display: inline-block;
            margin-left: 10px; /* Adjust spacing */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: gold; /* Dropdown background color */
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black; /* Dropdown item text color */
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1; /* Dropdown item hover background color */
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Smaller Screens */
        @media (max-width: 992px) {
            .sidebar {
                width: 60px; /* Adjust sidebar width for smaller screens */
                left: -60px; /* Hide sidebar off-screen initially */
            }

            .sidebar.active {
                left: 0; /* Show sidebar on toggle */
            }

            .sidebar ul li a .nav-text {
                display: none; /* Hide text on smaller screens */
            }

            .sidebar ul li a .fa-fw {
                margin-right: 0; /* Remove margin for icons on smaller screens */
            }

            .sidebar .admin-icon {
                margin-left: 0; /* Adjust icon margin for smaller screens */
            }

            body.sidebar-active {
                margin-left: 60px;
            }

            .navbar {
                background-color: #e0a800; /* Change background color of navbar */
            }

            .container-fluid-custom {
                background-color: #e0a800; /* Custom background color for container-fluid */
            }

            .navbar-toggler {
                border-color: black; /* Custom border color for navbar-toggler */
            }

            .table-responsive-sm {
                overflow-x: auto;
            }
        }

        /* Larger Screens */
        @media (min-width: 992px) {
            .sidebar {
                left: -220px; /* Initially hidden on larger screens */
                z-index: 1030; /* Ensure sidebar is above content */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0; /* Show sidebar on toggle */
            }

            .sidebar ul li a .nav-text {
                display: block; /* Text is displayed */
            }

            .sidebar .admin-icon {
                margin-left: 55px; /* Adjust icon margin */
            }

            body {
                padding-top: 0; /* Adjust top padding */
            }

            body.sidebar-active {
                margin-left: 220px; /* Adjust content margin when sidebar is active */
            }

            .navbar {
                background-color: #e0a800; /* Change background color of navbar */
            }

            .container-fluid-custom {
                background-color: #e0a800; /* Custom background color for container-fluid */
            }

            .navbar-toggler {
                border-color: black; /* Custom border color for navbar-toggler */
            }

            .table-responsive-lg {
                overflow-x: auto;
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
        </div>
        <ul>
            <li><a href="Admin_dashboard.html"><i class="fas fa-tachometer-alt fa-fw"></i> <span class="nav-text">Dashboard</span></a></li>
            <li><a href="Admin_transaction.html"><i class="fas fa-history fa-fw"></i> <span class="nav-text">Transaction History</span></a></li>
            <li><a href="#"><i class="fas fa-users fa-fw"></i> <span class="nav-text">Customers</span></a></li>
            <li><a href="#"><i class="fas fa-box-open fa-fw"></i> <span class="nav-text">Products</span></a></li>
            <li><a href="Admin_employee.html"><i class="fas fa-user-tie fa-fw"></i> <span class="nav-text">Employees</span></a></li>
            <li><a href="Admin_category.html"><i class="fas fa-th fa-fw"></i> <span class="nav-text">Categories</span></a></li>
            <li><a href="Admin_ratings.html"><i class="fas fa-star fa-fw"></i> <span class="nav-text">Ratings</span></a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt fa-fw"></i> <span class="nav-text">Sign Out</span></a></li>
            <li><a href="#"><i class="fas fa-question-circle fa-fw"></i> <span class="nav-text">Help</span></a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-custome">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                <i class="fas fa-bars text-black"></i>
            </button>
        </div>
    </nav>

    <!-- Search Bar and Dropdown -->
    <div class="container">
        <div class="search-bar">
            <input type="text" placeholder="Search transactions..." id="searchInput">
            <button type="button" class="btn btn-primary" onclick="searchTransactions()"><i class="fas fa-search" style="color: red;"></i></button>

            <!-- Dropdown for filtering dates -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dateFilterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: gold; border-color: black; color: black;">
                    Filter by Date
                </button>
                <div class="dropdown-menu" aria-labelledby="dateFilterDropdown" style="background-color: gold;">
                    <a class="dropdown-item" href="#" style="color: black;" onclick="filterByRecent()">Recent</a>
                    <a class="dropdown-item" href="#" style="color: black;" onclick="filterByOldest()">Oldest</a>
                </div>
            </div>
        </div>

        <!-- Transaction History Table -->
        <div class="transaction-history">
            <div class="table-responsive-lg">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include 'transaction_function.php';

                            display_Transaction();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.body.classList.toggle('sidebar-active');
        });
        
        // For input field to detect changes in input value
	    document.getElementById('searchInput').addEventListener('input', searchTransactions);

// ----------------------- SEARCH TRANSACTION ----------------------- //

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

// ----------------------- FILTER BY RECENT ----------------------- //

         // ayaw gumana ng dropdown
        function filterByRecent() {
            const tableRows = document.querySelectorAll('.transaction-table tbody tr');
            tableRows.forEach(row => {
                row.style.display = ''; // Reset all rows
            });
            // Implement your logic for filtering by recent dates here
        }

// ----------------------- FILTER BY OLDEST ----------------------- //

        function filterByOldest() {
            const tableRows = document.querySelectorAll('.transaction-table tbody tr');
            tableRows.forEach(row => {
                row.style.display = ''; // Reset all rows
            });
            // Implement your logic for filtering by oldest dates here
        }

        
    </script>
</body>
</html>