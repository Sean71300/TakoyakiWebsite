<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Category</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="customCodes/custom.css">
    <!-- Custom CSS -->
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
        left: -220px; /*hidden on larger screens */
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


    /*Smaller Screens */
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
    }

    /*Larger Screens */
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
            display: block; /*text is displayed */
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
		}
		    .category-container {
        margin-top: 20px;
        padding: 20px;
    }

    .search-box {
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        background-color: #fff;
    }

    .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        cursor: pointer;
    }

    .add-category-btn {
        float: right;
        background-color: #000;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-category-btn:hover {
        background-color: #333;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .category-table {
        width: 100%;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .category-table th, .category-table td {
        border: none;
        padding: 12px 15px;
        text-align: left;
    }

    .category-table th {
        background-color: #ffd700; /* Gold */
        color: black;
        font-weight: bold;
    }

    .category-table tbody tr:nth-child(odd) {
        background-color: #dc3545; /* Red */
        color: white;
    }

    .category-table tbody tr:nth-child(even) {
        background-color: #ffffff; /* White */
        color: black;
    }

    .category-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .category-actions a {
        margin-left: 5px;
        color: inherit;
        text-decoration: none;
    }

    .category-actions a:hover {
        color: #666;
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

        .btn-primary {
            background-color: #FFC10B;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #953728;
        }

        /* Centered Text in Modal */
        .centered-text {
            text-align: center;
            margin-bottom: 20px;
            font-size: 30px;
            font-weight: bold;
            color: #953728;
        }
		

        .btn-primary, .btn-clear {
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

        .btn-primary:hover, .btn-clear:hover {
            background-color: #953728;
        }
        
    @media (max-width: 576px) {
        .add-category-btn {
            float: none;
            margin-bottom: 10px;
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
            <li><a href="Admin_dashboard.php"><i class="fas fa-tachometer-alt fa-fw"></i> <span class="nav-text">Dashboard</span></a></li>
            <li><a href="Admin_transaction.php"><i class="fas fa-history fa-fw"></i> <span class="nav-text">Transaction History</span></a></li>
            <li><a href="Admin_employee.php"><i class="fas fa-user-tie fa-fw"></i> <span class="nav-text">Employees</span></a></li>
            <li><a href="Admin_customers.php"><i class="fas fa-users fa-fw"></i> <span class="nav-text">Customers</span></a></li>
            <li><a href="Admin_category.php"><i class="fas fa-th fa-fw"></i> <span class="nav-text">Categories</span></a></li>
            <li><a href="Admin_products.php"><i class="fas fa-box-open fa-fw"></i> <span class="nav-text">Products</span></a></li>
            <li><a href="Admin_ratings.php"><i class="fas fa-star fa-fw"></i> <span class="nav-text">Ratings</span></a></li>
            <li><a href="Admin_gallery.php"><i class="fas fa-image fa-fw"></i> <span class="nav-text">Gallery</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> <span class="nav-text">Sign Out</span></a></li>
            <li><a href="#" id="HelpLink"><i class="fas fa-question-circle fa-fw"></i> <span class="nav-text">Help</span></a></li>
		</ul>
    </div>

    <!-- Navbar -->  <!-- Hamburger icon -->
    <nav class="navbar navbar-dark bg-custome">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                <i class="fas fa-bars text-black"></i>
            </button>
            <?php include 'nav.php'; ?>
        </div>
    </nav>

    <!-- Content Here -->
    <div class="container category-container">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="position-relative">
                    <input type="text" class="form-control search-box" placeholder="Search..." id="searchInput">
                    <span class="fas fa-search form-control-feedback search-icon"></span>
                </div>
            </div>
            <div class="col-md-6">
                <button class="btn btn-success add-category-btn" id="addCategoryBtn">Add Category <i class="fas fa-plus"></i></button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table table-striped table-hover category-table">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include 'category_functions.php';

                        display_Categories();
                    ?>
                </tbody>
            </table>
        </div>

        <!------- ADD CATEGORY MODAL ------->
        <div id="addCategoryModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="centered-text" id="title">Add New Category</h2>
                <form id="addCategoryForm" method="post" action="category_functions.php">
                    <div class="mb-3">
                        <label for="categoryID" class="form-label"><b style="color: red;">*</b>Category ID</label>
                        <input type="text" class="form-control" id="CategoryID" name="categoryID" placeholder="202403----" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="categoryType" class="form-label"><b style="color: red;">*</b>Category Type</label>
                        <input type="text" class="form-control" id="CategoryType" name="categoryType" required>
                    </div>
                    <div  class="form-group d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary" id="updateBtn">Add Category</button>
                        <button type="button" class="btn btn-clear" id="clearFormBtn">Clear</button>
                    </div>
                </form>
            </div>
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

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <!-- Script -->
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.body.classList.toggle('sidebar-active');
        });

        // Toggle search input focus on search icon click
        document.querySelector('.search-icon').addEventListener('click', function() {
            document.querySelector('.search-box').focus();
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
                alert("Category added successfully.");
            } 
            else if (successType === 'update') 
            {
                alert("Category updated successfully.");
            }

            // Reset successType and urlParams
            successType = null;
            urlParams = null;
        }

// ------------------------- ADD CATEGORY MODAL JS ------------------------- //

        // Show Add Category Modal
	    document.getElementById('addCategoryBtn').addEventListener('click', function() {
        var modal = document.getElementById('addCategoryModal');
        modal.style.display = "block";
        console.log('Add New Employee button clicked');
	    });
	
	    // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[0];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('addCategoryModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
	
	    // Clear Form Button
	    document.getElementById('clearFormBtn').addEventListener('click', function() {
        document.getElementById('addCategoryForm').reset();
	    });

// ------------------------- UPDATE CATEGORY MODAL JS ------------------------- //

        // Function to fetch category data and fill the fields
        function getCategory(categoryId) 
        {
            fetch(`getCategory.php?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('CategoryID').value = data.category_id;
                document.getElementById('CategoryType').value = data.category_type;
            })
            .catch(error => {
                console.error('Error fetching employee data:', error);
            });
        }

        function changeFormTitleAndButton(title, buttonText) 
        {
            // Change the modal title
            document.getElementById('title').innerText = title;

            // Change the button value
            document.getElementById('updateBtn').innerText = buttonText;
        }

        // Function to open the modal and populate the fields
        function updateCategory(categoryId) 
        {
            // Open the modal
            const modal = document.getElementById('addCategoryModal');
            modal.style.display = 'block';

            // Fetch the employee details
            getCategory(categoryId);

            // Change the modal title and button text for updating employee
            changeFormTitleAndButton('Update Category', 'Update');
        }

// ------------------------- DELETE CATEGORY MODAL JS ------------------------- //

        function deleteCategory(categoryId) 
        {
            if (confirm("Are you sure you want to delete Category ID: " + categoryId + "?")) 
            {
                // Create an XMLHttpRequest object
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "category_functions.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Define what happens on successful data submission
                xhr.onload = function () {
                    if (xhr.status == 200) 
                    {
                        // Do something with the response
                        alert(xhr.responseText);
                        // Optionally, you can remove the row from the table or refresh the page
                        location.reload();
                    } 
                    else 
                    {
                        alert("Error deleting category.");
                    }
                };

                // Send the request with the transaction ID
                xhr.send("categoryID=" + categoryId);
            }
        }

// ------------------------- ADD CATEGORY MODAL JS ------------------------- //

        function addEmployee() 
        {
            // Get form data
            var categoryType = document.getElementById('CategoryType').value;

            // Validate form data (you can add more validation as needed)
            if (!categoryType) 
            {
                alert("Please fill in all fields.");
                return;
            }

            // Create an XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "category_functions.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens on successful data submission
            xhr.onload = function () {
                if (xhr.status == 200) 
                {
                    // Do something with the response
                    alert(xhr.responseText);
                    // Optionally, you can close the modal and refresh the page
                    closeModal();
                    location.reload();
                } 
                else 
                {
                    alert("Error adding category.");
                }
            };

            // Send the request with the form data
            var data = "category_type=" + encodeURIComponent(categoryType);

            xhr.send(data);
        }

// ----------------------------------------- SEARCH ----------------------------------------- //

        // For input field to detect changes in input value
	    document.getElementById('searchInput').addEventListener('input', searchEmployee);

        function searchEmployee() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.table-striped tbody tr');

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
        
// ----------------------------------------- HELP MODAL ----------------------------------------- //

        // Show Help Modal
	    document.getElementById('HelpLink').addEventListener('click', function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "block";
        console.log('Help modal opened');
	    });
        
        // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[1];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('helpModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };         
    </script>
      <!-- Footer -->
      <?php include "Footer.php"?>      
</body>
</html>