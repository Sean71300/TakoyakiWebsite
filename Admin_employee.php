<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

            .input-group {
                width: 100%; /* Adjust search input width */
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

        /* Employee Table */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: #fff; /* White background for table */
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border: none; /* Remove borders */
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05); /* Light gray background */
        }

        /* For Search and Add Button */
        .search-and-add {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem; /* Adjust margin as necessary */
        }

        .input-group {
            width: 60%; /* Adjust search input width */
            margin-right: 10px; 
        }

        .btn-add-employee {
            flex-shrink: 0; /* Prevent button from shrinking */
            background-color: red; /* Red background for Add Employee button */
            color: white; /* White text color */
            border-color: red; /* Red border color */
        }

        .btn-add-employee:hover {
            background-color: #ed7b7a; /* Darker red on hover */
            border-color: #ed7b7a; /* Darker red border on hover */
        }

        /* Employee Table Actions */
        .btn-edit,
        .btn-delete {
            color: gold; /* Initial color for icons */
            text-decoration: none; /* Remove underline on icons */
            margin-right: 5px; /* Adjust spacing between icons if needed */
        }

        .btn-edit:hover,
        .btn-delete:hover {
            color: red; /* Red color on hover */
        }

        .btn-edit:hover .fa-edit,
        .btn-delete:hover .fa-trash {
            color: red; /* Adjust icon color on hover */
        }

        .btn-edit .fa-edit,
        .btn-delete .fa-trash {
            transition: color 0.3s ease; /* Smooth transition for icon color change */
        }

        .btn-edit:hover .fa-edit,
        .btn-delete:hover .fa-trash {
            color: red; /* Red color for icons on hover */
        }

        .btn-search {
            color: black; /* Black text color */
            border-color: black; /* Black border color */
        }

        .btn-search:hover {
            color: #333; /* Darker black on hover */
            border-color: #333; /* Darker black border on hover */
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

        .notification {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border: none;
            cursor: pointer;
            width: 100%;
            display: inline-block;
        }
        .hidden {
            display: none;
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
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-custome">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                <i class="fas fa-bars text-black"></i>
            </button>
            <?php include 'nav.php'; ?>
        </div>
    </nav>

    <!-- Content Here For Employee -->
    <div class="container-fluid mt-4">
        <!-- Search and Add Button Section -->
        <div class="search-and-add d-flex justify-content-between align-items-center">
            <form class="form-inline input-group">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                <div class="input-group-append">
                    <button class="btn btn-outline-light btn-search" type="button" onclick="searchEmployee()"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <button class="btn btn-add-employee" id="addEmployeeBtn">Add Employee</button>
        </div>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>IMG</th>
                        <th>Employee ID</th>
                        <th>Position</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th> </th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include_once 'employee_functions.php';

                        display_Employees();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- ADD EMPLOYEE MODAL -->
        <div id="addEmployeeModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="centered-text" id="title">Add New Employee</h2>
                <form id="addEmployeeForm" method="post" action="employee_functions.php">
                    <div class="mb-3">
                        <label for="employeeID" class="form-label"><b style="color: red;">*</b>Employee ID <i style="color: lightgrey;">(automated)</i></label>
                        <input type="text" class="form-control" id="EmployeeID" name="employeeID" placeholder="202405----" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="employeeName" class="form-label"><b style="color: red;">*</b>Name</label>
                        <input type="text" class="form-control" id="EmployeeName" name="employeeName" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeePosition" class="form-label"><b style="color: red;">*</b>Position</label>
                        <input type="text" class="form-control" id="EmployeePosition" name="employeePosition" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeEmail" class="form-label"><b style="color: red;">*</b>Email</label>
                        <input type="email" class="form-control" id="EmployeeEmail" name="employeeEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeePhone" class="form-label"><b style="color: red;">*</b>Phone</label>
                        <input type="tel" class="form-control" id="EmployeePhoneNum" name="employeePhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeAddress" class="form-label"><b style="color: red;">*</b>Address</label>
                        <input type="text" class="form-control" id="EmployeeAddress" name="employeeAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeBirthdate" class="form-label"><b style="color: red;">*</b>Birthdate</label>
                        <input type="date" class="form-control" id="employeeBirthdate" name="EmployeeBday"
                            value="<?php echo isset($_POST['EmployeeBday']) ? htmlspecialchars($_POST['EmployeeBdat']) : ''; ?>"
                            min="<?php echo date('Y-m-d', strtotime('-50 years')); ?>"
                            max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeAge" class="form-label"><b style="color: red;">*</b>Age <i style="color: lightgrey;">(automated)</i></label>
                        <input type="number" class="form-control" id="employeeAge" name="EmployeeAge" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="employeeGender" class="form-label"><b style="color: red;">*</b>Gender</label>
                        <select class="form-select" id="EmployeeGender" name="employeeGender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary" id="updateBtn">Add Employee</button>
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
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.body.classList.toggle('sidebar-active');
        });

        // Check if the URL has the success query parameter
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') || urlParams.has('fail')) 
        {
            // Retrieve the successType value
            var successType = urlParams.get('success');
            var failType = urlParams.get('fail');

            // Check the value of successType
            if (successType === 'add') 
            {
                alert("Employee added successfully.");
            } 
            else if (successType === 'update') 
            {
                alert("Employee updated successfully.");
            }
            else if (failType === 'add') 
            {
                alert("Failed adding employee.");
            }
            

            // Reset successType and urlParams
            successType = null;
            urlParams = null;
        }

// -------------------------------------------------- ADD EMPLOYEE MODAL JS -------------------------------------------------- //

        function changeFormTitleAndButton(title, buttonText) 
        {
            // Change the modal title
            document.getElementById('title').innerText = title;

            // Change the button value
            document.getElementById('updateBtn').innerText = buttonText;
        }

        function blank()
        {
                document.getElementById('EmployeeID').value = "";
                document.getElementById('EmployeeName').value = "";
                document.getElementById('EmployeePosition').value = "";
                document.getElementById('EmployeeEmail').value = "";
                document.getElementById('EmployeePhoneNum').value = "";
                document.getElementById('EmployeeAddress').value = "";
                document.getElementById('employeeBirthdate').value = "";
                document.getElementById('employeeAge').value = "";
                document.getElementById('EmployeeGender').value = "";
        }

	    // Show Add Employee Modal
	    document.getElementById('addEmployeeBtn').addEventListener('click', function() {
        var modal = document.getElementById('addEmployeeModal');
        modal.style.display = "block";
        changeFormTitleAndButton('Add Employee', 'Add Employee');
        blank();
        console.log('Add New Employee button clicked');
	    });
    
	    // Close Modal
	    var closeBtn = document.getElementsByClassName("close")[0];
	    closeBtn.onclick = function() {
        var modal = document.getElementById('addEmployeeModal');
        modal.style.display = "none";
        console.log('Modal closed');
	    };
	
	    // Clear Form Button
	    document.getElementById('clearFormBtn').addEventListener('click', function() {
        document.getElementById('addEmployeeForm').reset();
	    });

// -------------------------------------------------- UPDATE EMPLOYEE MODAL JS -------------------------------------------------- //

        // Function to fetch employee data and fill the fields
        function getEmployee(employeeId) 
        {
            fetch(`getEmployee.php?employee_id=${employeeId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('EmployeeID').value = data.employee_id;
                document.getElementById('EmployeeName').value = data.full_name;
                document.getElementById('EmployeePosition').value = data.position;
                document.getElementById('EmployeeEmail').value = data.email;
                document.getElementById('EmployeePhoneNum').value = data.phone_number;
                document.getElementById('EmployeeAddress').value = data.address;
                document.getElementById('employeeBirthdate').value = data.birthdate;
                document.getElementById('employeeAge').value = data.age;
                document.getElementById('EmployeeGender').value = data.gender;
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
        function updateEmployee(employeeId) 
        {   
            // Open the modal
            const modal = document.getElementById('addEmployeeModal');
            modal.style.display = 'block';

            // Fetch the employee details
            getEmployee(employeeId);

            // Change the modal title and button text for updating employee
            changeFormTitleAndButton('Update Employee', 'Update');
        }
         
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

// -------------------------------------------------- DELETE EMPLOYEE JS -------------------------------------------------- //

        function deleteEmployee(employeeId) 
        {
            if (confirm("Are you sure you want to delete Employee ID: " + employeeId + "?")) 
            {
                // Create an XMLHttpRequest object
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "employee_functions.php", true);
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
                        alert("Error deleting employee.");
                    }
                };

                // Send the request with the transaction ID
                xhr.send("employeeID=" + employeeId);
            }
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
</body>
</html>