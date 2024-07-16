<?php
require_once 'connect.php'; 
require_once 'setup.php';
include 'modal.html';

$conn = connect();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

// Check if the user has clicked the button "UPLOAD"
if (isset($_POST['uploadfile'])) {
    $filename = $_FILES["choosefile"]["name"];
    $tempname = $_FILES["choosefile"]["tmp_name"];
    $folder = "Images/" . $filename;

    // Connect to the database
    $db = mysqli_connect("localhost", "root", "", "hentoki_db");

    // Check if connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to insert the submitted data
    $sql = "INSERT INTO images_gallery (image) VALUES (?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $image);

    // Initialize a variable to store image data
    $image = file_get_contents($tempname);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $msg = "Image uploaded successfully";
    } else {
        $msg = "Failed to upload image: " . mysqli_error($db);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close database connection
    mysqli_close($conn);

    // Add the image to the "image" folder
    if (move_uploaded_file($tempname, $folder)) {
        $msg .= "<br>Image moved to folder successfully";
    } else {
        $msg .= "<br>Failed to move image to folder";
    }
}

// Fetch all images from the database for display
$conn = mysqli_connect("localhost", "root", "", "hentoki_db");
$result = mysqli_query($conn, "SELECT * FROM images_gallery");

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
    
        /* Custom Modal Styles */
        .modal {
            display: none; 
            position: fixed; /* Stay in place */
            z-index: 1050; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); 
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
            padding: 9px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 25%; 
            margin: 4.5%;
        }

        .btn-clear {
            background-color: #953728;
        }

               /* Custom styling for file input */
               .custom-file-input {
            color: transparent;
            cursor: pointer;
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 2;
        }

        /* Style for the visible button */
        .upload-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Adjust the button on hover */
        .upload-btn:hover {
            background-color: #0056b3;
        }

        /* Styling for the file input container */
        .file-input-container {
            position: relative;
            display: inline-block;
            overflow: hidden;
            padding: 0;
            margin-right: 10px; /* Adjust spacing */
        }

        /* Style for the file name display */
        .file-name {
            display: inline-block;
            padding: 8px 15px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-right: 10px; /* Adjust spacing */
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
        <form method="POST" action="" enctype="multipart/form-data">
                <input type="file" name="choosefile" value="" />
                <div>
                    <button type="submit" name="uploadfile">UPLOAD</button>
                </div>
        </form>


        </div>
		</div>
		</nav>        
<div id="wrapper">
    <!-- Content Section -->
    <div class="container-fluid-custom">
        <div class="row">
            <?php while ($row = mysqli_fetch_array($result)) : ?>
                <div class="col-md-3">
                    <div class="card mt-5 ml-3">
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['TimeStamp']; ?>   @Hentoki </h5>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- End of Content Section -->
    <div><?php echo $msg; ?></div>
</div>
 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="modal.html"></script>
    <script>
            document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.body.classList.toggle('sidebar-active');
            });

  
        // Check if the URL has the success query parameter
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) 
        {
            // Retrieve the successType value
            var successType = urlParams.get('success');

            // Check the value of successType
            if (successType === 'add') 
            {
                alert("Added successfully.");
            } 

            // Reset successType and urlParams
            successType = null;
            urlParams = null;
        }
        
// ----------------------------------------- ADD CATEGORY MODAL ----------------------------------------- //

        // Show Add Category Modal
	    document.getElementById('addCategoryBtn').addEventListener('click', function() {
        var modal = document.getElementById('addCategoryModal');
        modal.style.display = "block";
        console.log('Add New Category button clicked');
	    });
	
	    // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[0];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('addCategoryModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
	
	    // Clear Form Button
	    document.getElementById('clearFormBtnCategory').addEventListener('click', function() {
        document.getElementById('addCategoryForm').reset();
	    });
        
// ----------------------------------------- ADD PRODUCT MODAL ----------------------------------------- //

        // Show Add Category Modal
	    document.getElementById('addProductBtn').addEventListener('click', function() {
        var modal = document.getElementById('addProductModal');
        modal.style.display = "block";
        blank();
        console.log('Add New Product button clicked');
	    });
	
	    // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[1];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('addProductModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
	
	    // Clear Form Button
	    document.getElementById('clearFormBtnProduct').addEventListener('click', function() {
        document.getElementById('addProductForm').reset();
	    });  

        categoryTypeProd = document.getElementById('CategoryTypeProd');

        // ----- GET CATEGORY INFO ----- //
        function getCategoryID() {
            // Get the value of the CategoryType input field
            var categoryTypeProd = document.getElementById('CategoryTypeProd').value;

            // Check if the categoryType is not empty
            if (categoryType) {
                // Fetch the category ID from the server
                fetch(`getCategoryID.php?category_type=${encodeURIComponent(categoryTypeProd)}`)
                    .then(response => {
                        // Ensure the response is in JSON format
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Check if the category_id exists in the returned data
                        if (data.category_id !== undefined) {
                            // Set the CategoryID input field to the fetched category ID
                            document.getElementById('CategoryIDProd').value = data.category_id;
                        } else {
                            console.error('Category ID not found in the response data');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching category data:', error);
                    });
            }
        }

        // Set up an event listener for the input event on the CategoryType field
        window.onload = function() {
            document.getElementById('CategoryTypeProd').addEventListener('input', getCategoryID);
        }

// ----------------------------------------- ADD EMPLOYEE MODAL ----------------------------------------- //

	    // Show Add Employee Modal
	    document.getElementById('addEmployeeBtn').addEventListener('click', function() {
        var modal = document.getElementById('addEmployeeModal');
        modal.style.display = "block";
        console.log('Add New Employee button clicked');
	    });
    
	    // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[2];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('addEmployeeModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
	
	    // Clear Form Button
	    document.getElementById('clearFormBtnEmployee').addEventListener('click', function() {
        document.getElementById('addEmployeeForm').reset();
	    });
         
// -------------------------------------------------- DISPLAY AGE AUTOMATICALLY -------------------------------------------------- //

        function calculateAge() 
        {
            var birthdate = document.getElementById('employeeBirthdate').value;
            if (birthdate) {
                var today = new Date();
                var birthDate = new Date(birthdate);
                var age = today.getFullYear() - birthDate.getFullYear();
                var monthDifference = today.getMonth() - birthDate.getMonth();
                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                document.getElementById('employeeAge').value = age;
            }
        }

        window.onload = function() {
            document.getElementById('employeeBirthdate').addEventListener('input', calculateAge);
        }
        
// ----------------------------------------- HELP MODAL ----------------------------------------- //

        // Show Help Modal
	    document.getElementById('HelpLink').addEventListener('click', function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "block";
        console.log('Help modal opened');
	    });
        
        // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[3];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
    </script>
</body>
</html>
