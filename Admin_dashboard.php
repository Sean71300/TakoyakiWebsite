<?php
require_once 'connect.php'; 
require_once 'setup.php';
include 'modal.html';

ob_start();

session_start();



// ------------ QUERIES FOR CHART -------------------------

function getChartData($conn, $query) {
    $result = $conn->query($query);

    $labels = [];
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['transaction_day'];  // Day labels
            $data[] = $row['total_value'];   // Total sales or transaction count data
        }
    }

    return [
        'labels' => $labels,
        'data' => $data
    ];
}

$conn = connect();
$type = isset($_POST['type']) ? $_POST['type'] : 'sales';

if ($type === 'transactions') {
    // Query to get total number of transactions each day
    $query = "SELECT DATE(transaction_date) AS transaction_day, COUNT(*) AS total_value 
              FROM transaction_history 
              GROUP BY DATE(transaction_date) 
              ORDER BY transaction_date";
} else {
    // Default query to get total earnings each day
    $query = "SELECT DATE(transaction_date) AS transaction_day, SUM(total_price) AS total_value 
              FROM transaction_history 
              GROUP BY DATE(transaction_date) 
              ORDER BY transaction_date";
}

$chart_data = getChartData($conn, $query);

$chart_data_json = json_encode($chart_data);


// ------------------------------ TOP AND BOTTOM PRODUCTS------------------------
function getTopOrLeastBoughtProducts($query, $conn) {
    $productRow = [];

    // Execute the query
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_img = $row['product_img'];
            $product_name = $row['product_name'];
            $price = $row['price'];
            $total_sold = $row['total_sold'];
            $rating = $row['rating'];
            $product_id = $row['product_id'];
            $category_type = $row['category_type'];

            $productRow[] = [
                "product_img" => $product_img, 
                "product_name" => $product_name,
                "price" => $price,
                "total_sold" => $total_sold,
                "rating" => $rating,
                "product_id" => $product_id,
                "category_type" => $category_type,
            ];
        }
        $result->free_result();
        return $productRow;
    } else {
        return null;
    }
}

    // functions unavailableStock($conn){
    //     $query = "SELECT * FROM "
    // }




// ------------------------------ 5 RECENT RATINGS-------------------------------
    $conn = connect();
    function getRatingsWithDetails($conn) {

        $rateRow = [];
    
        $query = "SELECT c.full_name AS customer_name, r.rating, r.rating_id, r.visibility_stat, p.product_name
                    FROM ratings r
                    INNER JOIN customers c ON r.customer_id = c.customer_id
                    INNER JOIN products p ON r.product_id = p.product_id
                    WHERE r.visibility_stat IS NULL
                    ORDER BY r.rate_timedate ASC
                    LIMIT 5;";
    
        $result = $conn->query($query);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $customer_name = $row["customer_name"];
                $rating = (int)$row["rating"];
                $product_name = $row["product_name"];
                $rating_id = $row["rating_id"];
    
                $rateRow[] = [
                    "customer_name" => $customer_name,
                    "rating" => $rating,
                    "product_name" => $product_name, 
                    "rating_id" => $rating_id
                ];
            }
            $result->free_result();
            return $rateRow;
        } else {
            return null; 
        }
    }
// ------------------------------ UNAVAILABLE STOCKS-------------------------------

    function unavailableStock($conn) {
        // Define the query
        $query = "SELECT COUNT(*) AS unavailable_count
                  FROM products
                  WHERE status = 'Unavailable'";
    
        // Execute the query
        $result = mysqli_query($conn, $query);
    
        // Check if the query was successful
        if ($result) {
            // Fetch the result row
            $row = mysqli_fetch_assoc($result);
            // Return the count of unavailable products
            return $row['unavailable_count'];
        } else {
            // Handle query error
            return "Error: " . mysqli_error($conn);
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
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

            nav.navbar {
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

            nav.navbar {
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
        /* Card Styles */
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
		.card-header {
		background-color: #E6D877;
		border-bottom: 1px solid #ddd;
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 10px 20px;
		}
		.see-more-link {
		font-size: 16px;
		color: red;
		text-decoration: none;
		transition: color 0.3s ease;
		margin-left: auto; 
		}
        .card-title {
            margin-bottom: 0;
        }

        .card-body ul {
            padding-left: 0;
            list-style-type: none;
        }

        .card-body ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .card-body ul li:last-child {
            border-bottom: none;
        }

        .badge {
            font-size: 14px;
        }

        /* Chart Container */
        #chart-container {
            position: relative;
            width: 100%;
            height: 300px; /* Initial height */
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensure chart stays within container */
        }

        #salesChart {
            width: 100%;
            height: 100%; /* Ensure chart fills its container */
        }
		.container-fluid-custom {
		margin-top: 20px; /* Adjust the margin-top value as needed */
		background-color: white; 
		padding: 20px; 
		}
        nav.navbar {
            background-color: #e0a800; 
        }

        .navbar-toggler {
            border-color: black;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-text {
            color: white; /* Adjust navbar text color */
        }

        .welcome-admin {
            color: white;
            margin-right: 20px;
            font-weight: bold;
        }
		
		.top-products .card-body,
		.ratings-section .card-body {
		height: 100%; 
		display: flex;
		flex-direction: column;
		justify-content: space-between; /* Distribute space evenly */
		}
		.top-products .product {
		height: 100%; 
		display: flex;
		flex-direction: column;
		justify-content: space-between; /* Distribute space evenly */
		align-items: center; /* Center content horizontally */
		text-align: center; /* Center text */
		}

    .top-products .product img {
        width: 150px; /* Adjust image width */
        height: 150px; /* Adjust image height */
        border-radius: 50%;
        margin-bottom: 10px; /* Spacing between image and content */
    }

    .top-products .product h4 {
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 16px; /* Increase font size */
    }

    .top-products .product p {
        margin-bottom: 5px;
        font-size: 14px; /* Increase font size */
        color: #666;
    }

    .top-products .product .ratings {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .top-products .product .ratings .fa-star {
        color: #FFC10B;
        margin-right: 2px;
    }

    .top-products .product .ratings .rating-value {
        margin-left: 5px;
        font-size: 14px; /* Increase font size */
    }

    .ratings-section .list-group-item {
        height: 100%; /* Ensure each list item takes full height of its container */
        display: flex;
        justify-content: space-between; /* Adjust alignment */
        align-items: center; /* Center items vertically */
    }

    .ratings-section .ratings {
        display: flex;
        align-items: center;
    }

    .ratings-section .ratings .fa-star {
        color: #FFC10B;
        margin-right: 2px;
    }

    .ratings-section .badge {
        font-size: 14px; /* Increase font size */
    }
		/* Sales Overview Section */
		.container-fluid-custom .card:nth-child(1) {
		background-color: #f9f6f2;
		}
		/* Ratings Section */
		.container-fluid-custom .card:nth-child(2) {
		background-color: #fee3e2;
		}
        .see-more-link:hover {
            color: black;
        }
		       /* Recent Transactions Section */
        .recent-transactions {
            margin-top: 20px;
        }

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 5px;
        }

        .transaction-table thead {
            background-color: #f8f9fa;
        }

        .transaction-table th, .transaction-table td {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        .transaction-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .transaction-table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .transaction-table th, .transaction-table td {
                font-size: 12px;
                padding: 8px;
            }
        }

        

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 5% from the top and centered */
            padding: 30px;
            border: none;
            width: 80%; 
            max-width: 500px; /* Limit maximum width */
            border-radius: 10px;
            position: relative;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); 
        }

        .modal-content-help {
            background-color: #fefefe;
            margin: 5% auto; /* 5% from the top and centered */
            padding: 30px;
            border: none;
            width: 80%; 
            max-width: 650px; /* Limit maximum width */
            border-radius: 10px;
            position: relative;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); 
        }
        
        .close {
            color: #aaaaaa;
            font-size: 30px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* Form Input Styling */
        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        /* Centered Text in Modal */
        .centered-text {
            text-align: center;
            margin-bottom: 20px;
            font-size: 30px;
            font-weight: bold;
            color: #953728;
        }
		

        .btn-clear {
            background-color: #FFC10B;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 40%; 
            margin: 4.5%;
        }

        .btn-clear {
            background-color: #953728;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
	<div class="sidebar" id="sidebar">
        <div class="text-center">
        <div class="admin-icon">
            <a href="Admin_edit profile.html">
                <img src="https://via.placeholder.com/60" alt="Admin Image">
            </a>
        </div>
		</div>
        <ul>
            <li><a href="Admin_dashboard.php"><i class="fas fa-tachometer-alt fa-fw"></i> <span class="nav-text">Dashboard</span></a></li>
            <li><a href="Admin_transaction.php"><i class="fas fa-history fa-fw"></i> <span class="nav-text">Transaction History</span></a></li>
            <li><a href="Admin_employee.php"><i class="fas fa-user-tie fa-fw"></i> <span class="nav-text">Employees</span></a></li>
            <li><a href="Admin_customers.php"><i class="fas fa-users fa-fw"></i> <span class="nav-text">Customers</span></a></li>
            <li><a href="Admin_category.php"><i class="fas fa-th fa-fw"></i> <span class="nav-text">Categories</span></a></li>
            <li><a href="Admin_products.php"><i class="fas fa-box-open fa-fw"></i> <span class="nav-text">Products</span></a></li>
            <li><a href="Admin_ratings.php"><i class="fas fa-star fa-fw"></i> <span class="nav-text">Ratings</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> <span class="nav-text">Sign Out</span></a></li>
            <li><a href="#" id="HelpLink"><i class="fas fa-question-circle fa-fw"></i> <span class="nav-text">Help</span></a></li>
        </ul>
        </div>

        <!-- Page Content -->
	    <nav class="navbar navbar-dark bg-custome">
        <div class="container-fluid">
        <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
            <i class="fas fa-bars text-black"></i>
        </button>
        <div class="welcome-admin">
            <?php include 'nav.php'; ?>
        </div>
        <div class="ml-auto">
            <button class="btn btn-success mx-2">Add Category</button>
            <button class="btn btn-primary mx-2">Add Product</button>
            <button class="btn btn-info mx-2">Add Employee</button>
        </div>
		</div>
		</nav>
		
	<div class="container-fluid-custom">
		<div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header justify-content-between mx-1">
                    <h3 class="card-title ">Overview</h3>
                    <form method="POST" id="queryForm">
                        <button type="submit" name="type" value="<?php echo $type === 'transactions' ? 'sales' : 'transactions'; ?>">
                            <?php echo $type === 'transactions' ? 'Show Sales' : 'Show Transactions'; ?>
                        </button>
                    </form>

                </div>
                <div class="card-body">
                    <div id="chart-container">
                    <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php

        function getTodaysEarnings($conn) {
        // SQL query to get and total the earnings of the current day
        $sql = "SELECT SUM(total_price) AS total_earnings 
                FROM transaction_history 
                WHERE DATE(transaction_date) = CURDATE()";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalEarnings = $row['total_earnings'] ? $row['total_earnings'] : 0; 
            return number_format($totalEarnings, 2);
        } else {
            return "0.00";
        }
        }

        function getMonthlyEarnings($conn) {
        // SQL query to get and total the earnings of the Month
        $sql = "SELECT SUM(total_price * quantity) AS total_earnings 
                FROM transaction_history 
                WHERE MONTH(transaction_date) = MONTH(CURDATE()) 
                AND YEAR(transaction_date) = YEAR(CURDATE());
                ";

        $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalEarnings = $row['total_earnings'] ? $row['total_earnings'] : 0; // Handle null result
                return number_format($totalEarnings, 2);
            } else {
                return "0.00";
            }
            }
            ?>

	        <div class="col-lg-4">
		    <div class="card card-right">
            <?php
                $conn = connect();
                $earnings = getMonthlyEarnings($conn);
                $month = date('F');
            echo ' <div class="card-header justify-content-center p-1">
                    <h3 class="card-title" style="text-align: center;">Total Sales  This <b> '. $month .' </b> </h3>
                    </div>
                    <div class="card-body">
                    <p class="card-text " style= "font-size: 30px; text-align: center;">' . 'PHP '. $earnings .'</p>
                </div>';
           ?>

            </div>

            <!-- Unavailable Product Section -->
            <div class="card card-right ">
            <div class="card-header justify-content-center p-1">
                <h3 class="card-title">Unavailable Product</h3>
            </div>
            <?php
                $conn = connect();
                $unavailableCount = unavailableStock($conn);
                echo ' <div class="card-body p-1">
                            <p class="card-text" style= "font-size: 30px; text-align: center; ">'. $unavailableCount .'</p>
                        </div>
                        </div>
            ';


            ?>
           
            <!-- Total Earn Section -->
            <div class="card card-right">
            <?php
            $conn = connect();
            $today = date('F j, Y'); 
            $totalToday = getTodaysEarnings($conn);
            echo    '<div class="card-header justify-content-center p-1">
                        <h3 class="card-title">Total for: <b> '. $today .' </b></h3>
                    </div>
                    <div class="card-body">
                    <p class="card-text " style= "font-size: 30px; text-align: center;">' . 'PHP '. $totalToday .'</p>
                    </div>';

             $conn->close();
            ?>
            </div>

            </div>
	            <!-- Popular Products Section -->
                <div class="col-lg-6">
        <div class="card top-products">
            <div class="card-header">
                <h4 class="card-title"> 
                <?php
                    if (isset($_POST['order'])) {
                        echo $_POST['order'] === 'top' ? 'TOP ' : 'LEAST ';
                    } else {
                        echo 'TOP';
                    }
                    ?>  
                        PRODUCTS  
                </h4>
                <form method="post">
                    <button type="submit" name="order" value="<?php echo (isset($_POST['order']) && $_POST['order'] === 'top') ? 'least' : 'top'; ?>">
                        <?php
                        if (isset($_POST['order'])) {
                            echo $_POST['order'] === 'top' ? '▼' : '▲';
                        } else {
                            echo '▼';
                        }
                        ?>
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php 
                        $conn = connect();
                        $order = isset($_POST['order']) ? $_POST['order'] : 'top';

                        $query = "
                            SELECT p.product_img, p.product_name, p.price, p.product_id, p.category_type, SUM(th.quantity) AS total_sold, r.rating
                            FROM transaction_history th
                            INNER JOIN products p ON th.product_id = p.product_id
                            LEFT JOIN ratings r ON p.product_id = r.product_id
                            GROUP BY p.product_id
                            ORDER BY total_sold " . ($order === 'top' ? 'DESC' : 'ASC') . "
                            LIMIT 3;";

                        $products = getTopOrLeastBoughtProducts($query, $conn);
                        if ($products) {
                            foreach ($products as $product) {
                                $averageRating = getAverageRating($conn, $product['product_id']);
                                echo '<div class="col-md-4 product">
                                    <p class="badge badge-info" style="color: #FFFFFF;">'.$product['category_type'] .'</p>
                                    <img src="data:image/jpeg;base64,' . base64_encode($product['product_img']) . '" alt="' . $product['product_name'] . '">
                                    <p>'. generateStars($averageRating)  .'</p>
                                    <h4>' . $product['product_name'] . '</h4>
                                    <p>Php ' . number_format($product['price'], 2) . '</p>
                                    <p>
                                </div>';
                            }
                        } else {
                            echo '<p>No products found</p>';
                        }

                     
                     ?>
                </div>
            </div>
        </div>
    </div>
                <!-- Ratings Section -->
                <div class="col-lg-6">
                    <div class="card ratings-section">
                        <div class="card-header">
                            <h4 class="card-title">Customer Ratings</h4>
                            <a href="Admin_ratings.php" class="see-more-link">See more</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                            <?php
                            $conn = connect();
                            $ratings = getRatingsWithDetails($conn);
                            if ($ratings !== null) {
                                foreach ($ratings as $rateRow) {
                                    echo '<li class="list-group-item d-flex justify-content-around  p-1">
                                            <span m-1>' . htmlspecialchars($rateRow["customer_name"]) . '</span>
                                            <span>' . generateStars($rateRow["rating"]) . ' <p class="badge badge-warning badge-pill">' . htmlspecialchars($rateRow["rating"]) . '</p></span>
                                            <span class="justify-content-center m-0 p-0">
                                            <form  action="Admin_dashboard.php" method="post" style="display:inline;">
                                                <input type="hidden" name="rating_id" value="'. $rateRow["rating_id"] .'">
                                                <input type="hidden" name="new_visibility_stat" value="approved">
                                                <button type="submit" class="btn btn-success mx-1" name="approveBTN">✔</button>
                                            </form>
                                            <form action="Admin_dashboard.php" method="post" style="display:inline;">
                                                    <input type="hidden" name="rating_id" value="'. $rateRow["rating_id"] .'">
                                                    <input type="hidden" name="new_visibility_stat" value="archived"  >
                                                    <button type="submit" class="btn btn-danger mx-1" name="deleteBTN" onclick="openConfirmModal()">⊘</button>
                                            </form>
                                            </span>
                                            </li>';
                                }
                            }
                            $conn->close();
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
            </div>
                    </div>
		</div>
    <?php
        function getAverageRating($conn, $product_id) {
            $sql = "SELECT AVG(rating) AS average_rating 
                    FROM ratings 
                    WHERE product_id = ? 
                    AND visibility_stat IS NOT NULL";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['average_rating'];
            } else {
                return 0;
            }
        }
        

        function generateStars($averageRating) {
            // Round the average rating to the nearest half-star
            $roundedRating = round($averageRating * 2) / 2;
            $fullStars = floor($roundedRating);
            $halfStars = ceil($roundedRating - $fullStars);
            $emptyStars = 5 - $fullStars - $halfStars;
    
            // Generate star icons
            $stars = str_repeat('<i class="fas fa-star" style="color: #FFD700;"></i>', $fullStars);
            $stars .= str_repeat('<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>', $halfStars);
            $stars .= str_repeat('<i class="far fa-star" style="color: #FFD700; "></i>', $emptyStars);
    
            return $stars;
        }

        function approveComment($new_visi_stat, $id) {
            $conn = connect();
            $rateID = $id;
            $sql = "UPDATE ratings 
                    SET visibility_stat = ?
                    WHERE rating_id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
        
            $stmt->bind_param('si', $new_visi_stat, $rateID);
            
            if ($stmt->execute()) {
                $message = "Rating has been successfully approved!";
                $title = "Hentoki Customer Ratings";
                echo "<script type='text/javascript'>
                    showModal('$message', '$title');
                    setTimeout(function() {
                        window.location.href = 'Admin_dashboard.php';
                    }, 2000); 
                </script>";

            } else {
                $message = "Error:'. $stmt->error .' ";
                echo "<script type='text/javascript'>alert('$message');</script>";

            }
            $stmt->close();
            $conn->close();
        
            return $rateID;
        }

        function deleteComment($id) {
            $conn = connect();
            
            $sql = "DELETE FROM ratings WHERE rating_id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param('i', $id);
            
            if ($stmt->execute()) {
                $message = "Rating has been successfully deleted!";
                $title = "Hentoki Customer Ratings";
                echo "<script type='text/javascript'>
                    showModal('$message', '$title');
                    setTimeout(function() {
                        window.location.href = 'Admin_dashboard.php';
                    }, 2000); 
                </script>";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }
            
            $stmt->close();
            $conn->close();
            
            return $id;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['approveBTN'])) {
                $new_visi_stat = $_POST['new_visibility_stat'];
                $rateID = intval($_POST['rating_id']);
                approveComment($new_visi_stat, $rateID);
            } elseif (isset($_POST['deleteBTN'])) {
                $id = intval($_POST['rating_id']);
                deleteComment($id);
            } elseif (isset($_POST['replyBTN'])){
                $rateID = intval($_POST['rating_id']);
                if(isset($_POST['adminReplyBTN']))
                echo "hello";

                $adminReply = $_POST['adminReply'];
                replyComment($adminReply, $rateID);
            }
            
        }

        ?>
	        <!-- Recent Transactions -->
            <!-- <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Transactions</h3>
                                <div class="search-bar">
            <input type="text" placeholder="Search transactions..." id="searchInput">
            <button type="button" class="btn btn-primary" onclick="searchTransactions()"><i class="fas fa-search" style="color: red;"></i></button>

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
                    </div>
                    <div class="card-body">
                        <table class="transaction-table">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Product Ordered</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Product A</td>
                                    <td>2</td>
                                    <td>July 12, 2024</td>
                                    <td>$250.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
    </div>
    <!-- Bootstrap JavaScript -->
    <!-- Bootstrap JS and other scripts -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Your custom script -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="modal.html"></script>
    <script>
            document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.body.classList.toggle('sidebar-active');
            });
            var chartData = <?php echo $chart_data_json; ?>;

    // Access labels and data from PHP variable
    var chartData = <?php echo $chart_data_json; ?>;

// Access labels and data from PHP variable
var labels = chartData.labels.map(function(dateString) {
    var date = new Date(dateString); // Convert string to Date object
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
});
var data = chartData.data;

// Chart.js configuration
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,  // Formatted date labels
        datasets: [{
            label: '<?php echo $type === 'transactions' ? 'Daily Transactions' : 'Daily Sales'; ?>',
            data: data,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Set to false to fill the whole container
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
    </script>
</body>
</html>
