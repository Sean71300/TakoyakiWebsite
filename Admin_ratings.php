<?php
    require_once 'setup.php';
    include 'modal.html';
    
    $conn = connect();

    ob_start();
        //query for fetching the columns from table rating
        $sql = "SELECT r.rating_id, r.product_id, r.full_name, r.customer_id, r.rate_timedate, r.rating, r.comment, p.product_name
        FROM ratings r
        INNER JOIN products p ON r.product_id = p.product_id
        WHERE r.visibility_stat IS NULL";
        $result = $conn->query($sql);

        if (!$result) {
        die("Query failed: " . $conn->error);
        }

        $avgRatings = [];
        $productCounts = []; //init array
        while ($row = $result->fetch_assoc()) {  //the data from result is turned into assoc array
        $product_id = $row["product_id"];    //get the product_id
        if (!isset($avgRatings[$product_id])) {  //check if the product_id exist in $avgRatings array
        $avgRatings[$product_id] = 0;   //if not init both to 0
        $productCounts[$product_id] = 0; //
        }
        $avgRatings[$product_id] += (int)$row["rating"]; //add the 'selected' row's rating to the existing total sum for that specific product_id
        $productCounts[$product_id]++; //count how many product ID in table ratings
        }


        foreach ($avgRatings as $product_id => $totalStars) { //iterate the assoc array then divide it to get the AVG
        $avgRatings[$product_id] = $totalStars / $productCounts[$product_id];
        }


        $result->data_seek(0); //pointer to index 0
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="customCodes/custom.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>



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

        /* Table Styles */
        .table {
            margin-top: 5%;
            width: 100%;
            margin-bottom: 1rem;
            background-color: #ffffff;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: none;
            border-color: gold; /* Gold color for table borders */
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: none;
            background-color: #DC3545; /* Red */
            color: white;
            font-weight: bold;
        }

        .table tbody {
            background-color: #ffffff; /* White */
        }

        .table tbody + tbody {
            border-top: none;
        }

        .table .table {
            background-color: #ffffff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: none;
        }

        .table-bordered th,
        .table-bordered td {
            border: none;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: none;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #FFD700; /* Gold */
        }

        .table-hover tbody tr:hover {
            background-color: #ffffff; /* White */
        }

        .table-hover tbody tr:hover td {
            color: #000000; /* Black */
        }

        .table-primary,
        .table-primary > th,
        .table-primary > td {
            background-color: #DC3545;
        }

        .table-primary th,
        .table-primary td,
        .table-primary thead th,
        .table-primary tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-primary:hover {
            background-color: #DC3545;
        }

        .table-hover .table-primary:hover > td,
        .table-hover .table-primary:hover > th {
            background-color: #DC3545;
        }

        .table-secondary,
        .table-secondary > th,
        .table-secondary > td {
            background-color: #DC3545;
        }

        .table-secondary th,
        .table-secondary td,
        .table-secondary thead th,
        .table-secondary tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-secondary:hover {
            background-color: #DC3545;
        }

        .table-hover .table-secondary:hover > td,
        .table-hover .table-secondary:hover > th {
            background-color: #DC3545;
        }

        .table-success,
        .table-success > th,
        .table-success > td {
            background-color: #DC3545;
        }

        .table-success th,
        .table-success td,
        .table-success thead th,
        .table-success tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-success:hover {
            background-color: #DC3545;
        }

        .table-hover .table-success:hover > td,
        .table-hover .table-success:hover > th {
            background-color: #DC3545;
        }

        .table-info,
        .table-info > th,
        .table-info > td {
            background-color: #DC3545;
        }

        .table-info th,
        .table-info td,
        .table-info thead th,
        .table-info tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-info:hover {
            background-color: #DC3545;
        }

        .table-hover .table-info:hover > td,
        .table-hover .table-info:hover > th {
            background-color: #DC3545;
        }

        .table-warning,
        .table-warning > th,
        .table-warning > td {
            background-color: #DC3545;
        }

        .table-warning th,
        .table-warning td,
        .table-warning thead th,
        .table-warning tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-warning:hover {
            background-color: #DC3545;
        }

        .table-hover .table-warning:hover > td,
        .table-hover .table-warning:hover > th {
            background-color: #DC3545;
        }

        .table-danger,
        .table-danger > th,
        .table-danger > td {
            background-color: #DC3545;
        }

        .table-danger th,
        .table-danger td,
        .table-danger thead th,
        .table-danger tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-danger:hover {
            background-color: #DC3545;
        }

        .table-hover .table-danger:hover > td,
        .table-hover .table-danger:hover > th {
            background-color: #DC3545;
        }

        .table-light,
        .table-light > th,
        .table-light > td {
            background-color: #ffffff;
        }

        .table-light th,
        .table-light td,
        .table-light thead th,
        .table-light tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-light:hover {
            background-color: #ffffff;
        }

        .table-hover .table-light:hover > td,
        .table-hover .table-light:hover > th {
            background-color: #ffffff;
        }

        .table-dark,
        .table-dark > th,
        .table-dark > td {
            background-color: #DC3545;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th,
        .table-dark tbody + tbody {
            border-color: #DC3545;
        }

        .table-hover .table-dark:hover {
            background-color: #DC3545;
        }

        .table-hover .table-dark:hover > td,
        .table-hover .table-dark:hover > th {
            background-color: #DC3545;
        }

        .table-active,
        .table-active > th,
        .table-active > td {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover > td,
        .table-hover .table-active:hover > th {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table .thead-dark th {
            color: #ffffff;
            background-color: #DC3545;
            border-color: #DC3545;
        }

        .table .thead-light th {
            color: #ffffff;
            background-color: #ffffff;
            border-color: #DC3545;
        }

        .table-dark {
            color: #ffffff;
            background-color: #DC3545;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th {
            border-color: #DC3545;
        }

        .table-dark.table-bordered {
            border: 0;
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .table-dark.table-hover tbody tr:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.075);
        }

        .table-responsive-sm {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive-lg {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
            .short-comment {
            display: inline;
        }
        }
        /* addition */
        .short-comment {
            display: inline;
        }

        .full-comment {
            display: none;
        }

        .toggle-comment {
            display: inline;
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .comment-column {
            max-width: 200px; 
            max-height: 150px; 
            overflow: hidden; /
            text-overflow: ellipsis; 
            white-space: nowrap; 
            position: relative;
        }

        .comment-column:hover {
            overflow: auto; 
            white-space: normal;
}
.message-container {
            background-color: #32B877; /* Success background color */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .message {
            font-size: 24px;
            color: #FFFFFF; /* Success text color */
            margin-left: 10px;
        }
        .success-icon {
            font-size: 36px;
            color: #FFFFFF; /* Icon color */
        }

        /* Modal Content */
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
                <img src="https://via.placeholder.com/60" alt="Admin Image">
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

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-custome">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                <i class="fas fa-bars text-black"></i>
            </button>
            <?php include_once 'nav.php'; ?>
        </div>
    </nav>
    

    <!-- Content Here For Ratings -->
    <div class="container mt-4">
        <div class="table-responsive-lg">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">
                            <input class="form-control" id="searchInput" type="text" placeholder="Search">
                        </th>
                        <th scope="col"></th>
                        <th scope="col">
                            <select class="form-select" id="sortSelect">
                                <option selected disabled>Sort by</option>
                                <option value="ratingAsc">Rating (Low to High)</option>
                                <option value="ratingDesc">Rating (High to Low)</option>
                                <option value="dateAsc">Date (Old to New)</option>
                                <option value="dateDesc">Date (New to Old)</option>
                            </select>
                        </th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Star Rating</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Date of Rating</th>
                        <th scope="col">Comment</th>
                        <th scope="col"> Actions </th>
                    </tr>
                </thead>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $comment = htmlspecialchars($row["comment"]);
                        $short_comment = strlen($comment) > 100 ? substr($comment, 0, 100) . '...' : $comment;
                    echo '<tbody>
                    <tr>
                        <td>'. htmlspecialchars($row["full_name"]) .'</td>
                        <td>'.htmlspecialchars($row["customer_id"])  .'</td>
                        <td>'. generateStars($row["rating"]) .'</td>
                        <td>'. htmlspecialchars($row["product_name"]) .'</td>
                        <td>'.htmlspecialchars($row["rate_timedate"]).'</td>
                        <td class="comment-column">';
                        if (strlen($comment) > 100) {
                            echo '  <span class="short-comment">'. $short_comment .'</span>
                                    <span class="full-comment" style="display:none;">'. $comment .'</span>
                                    <button class="toggle-comment btn btn-link">👁</button>';
                        } else {
                            echo '<span class="short-comment">'. $comment .'</span>';
                        }
                        echo '</td>
                        <td>
                        <form  action="Admin_ratings.php" method="post" style="display:inline;">
                            <input type="hidden" name="rating_id" value="'. $row["rating_id"] .'">
                            <input type="hidden" name="new_visibility_stat" value="approved">
                            <button type="submit" class="btn btn-success mx-1" name="approveBTN">✔</button>
                        </form>
                        <form action="Admin_ratings.php" method="post" style="display:inline;">
                                <input type="hidden" name="rating_id" value="'. $row["rating_id"] .'">
                                <input type="hidden" name="new_visibility_stat" value="archived"  >
                                <button type="submit" class="btn btn-danger mx-1" name="deleteBTN" onclick="openConfirmModal()">⊘</button>
                        </form>
                        
                    </tr>
                </tbody>';
                    }
                }
                ?>
            </table>
        </div>

        <!------- HELP MODAL ------->
        <div id="helpModal" class="modal">
            <div class="modal-content-help">
                <span class="close">&times;</span>
                <h1 class="centered-text" id="title">Help</h1>
                <table>
                    <tr>
                        <td>
                            <h5><b>Dashboard</b></h5>
                            <ul>
                                <li>Shows the Sales of the business.</li>
                                <li>Shows the Ratings of the products.</li>
                                <li>Shows the Employees summarized information.</li>
                            </ul>
                        </td>
                        <td>
                            <h5><b>Categories</b></h5>
                            <ul>
                                <li>Shows all the Categories.</li>
                                <li>Can add new Category.</li>
                                <li>Can edit existing Category.</li>
                                <li>Can delete a Category.</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><b>Transaction History</b></h5>
                            <ul>
                                <li>Shows the overall Transaction of the business.</li>
                            </ul>
                        </td>
                        <td>
                            <h5><b>Products</b></h5>
                            <ul>
                                <li>Shows all the Products.</li>
                                <li>Can add new Product.</li>
                                <li>Can edit existing Product.</li>
                                <li>Can delete a Product.</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><b>Employees</b></h5>
                            <ul>
                                <li>Shows all the Employees.</li>
                                <li>Can add new Employees.</li>
                                <li>Can edit existing Employees' information.</li>
                                <li>Can delete an Employee.</li>
                            </ul>
                        </td>
                        <td>
                            <h5><b>Ratings</b></h5>
                            <ul>
                                <li>Shows all the Ratings.</li>
                                <li>Can approve customers' Ratings.</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><b>Customers</b></h5>
                            <ul>
                                <li>Shows all the Customers.</li>
                                <li>Can delete a Customer.</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- Button to trigger the modal -->


<!-- Confirmation Modal -->
<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>



<?php

        function getAverageRating($conn) {
            $sql = "SELECT AVG(rating) AS average_rating 
                    FROM ratings 
                    WHERE visibility_stat IS NOT NULL";
                    $result = $conn->query($sql);

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
                        window.location.href = 'Admin_ratings.php';
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
                        window.location.href = 'Admin_ratings.php';
                    }, 2000); 
                </script>";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }
            
            $stmt->close();
            $conn->close();
            
            return $id;
        }
        
        function replyComment($adminreply, $rateID) {
            $conn = connect(); 
        
            $adminreply = mysqli_real_escape_string($conn, $adminreply);
            $rateID = mysqli_real_escape_string($conn, $rateID);
            

            $query = "INSERT INTO replytocustomer (rating_id, reply)
                    VALUES ('$rateID', '$adminreply')";
        
            if (mysqli_query($conn, $query)) {
                echo "Reply submitted successfully.";
                header('Location: Admin_ratings.php');
                exit;
            } else {
                echo "Error submitting reply: " . mysqli_error($conn);
            }
        }

        function updateReplyVisibility(){
            $conn = connect(); 

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

    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="modal.html"></script>

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.body.classList.toggle('sidebar-active');
        });

        function openConfirmModal() {
                $('#confirmModal').modal('show'); // Show the modal
            }

            // Function to handle the action after confirmation
            $('#confirmBtn').click(function () {
                // Perform your action here
                // For example, you can redirect, submit a form, or perform an AJAX request
                // Replace this with your action logic
                console.log('Action confirmed');
                // After action, close modal
                $('#confirmModal').modal('hide');
            });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    const toggleButtons = document.querySelectorAll(".toggle-comment");

    toggleButtons.forEach(button => {
        button.addEventListener("click", function() {
            const shortComment = this.previousElementSibling.previousElementSibling;
            const fullComment = this.previousElementSibling;
            
            if (fullComment.style.display === "none") {
                fullComment.style.display = "inline";
                shortComment.style.display = "none";
                this.textContent = "Read less";
            } else {
                fullComment.style.display = "none";
                shortComment.style.display = "inline";
                this.textContent = "Read more";
            }
        });
    });
});  
        
// ----------------------------------------- HELP MODAL ----------------------------------------- //

        // Show Help Modal
	    document.getElementById('HelpLink').addEventListener('click', function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "block";
        console.log('Help modal opened');
	    });
        
        // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[0];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
    </script>
</body>
</html>
<?php
    ob_end_flush(); // Flush the output buffer
?>