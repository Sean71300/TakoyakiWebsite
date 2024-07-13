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
            font-size: 24px;
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
            <li><a href="Admin_dashboard.php"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li><a href="Admin_transaction.php"><i class="fas fa-history fa-fw"></i> Transaction History</a></li>
            <li><a href="Admin_employee.php"><i class="fas fa-user-tie fa-fw"></i> Employees</a></li>
            <li><a href="Admin_customers.php"><i class="fas fa-users fa-fw"></i> Customers</a></li>
            <li><a href="Admin_category.php"><i class="fas fa-th fa-fw"></i> Categories</a></li>
            <li><a href="Admin_products.php"><i class="fas fa-box-open fa-fw"></i> Products</a></li>
			<li><a href="Admin_ratings.php"><i class="fas fa-star fa-fw"></i> Ratings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt fa-fw"></i> Sign Out</a></li>
			<li><a href="#"><i class="fas fa-question-circle fa-fw"></i>Help</a></li>
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
                        <label for="employeeID" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="EmployeeID" name="employeeID" placeholder="202405----" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="EmployeeName" name="employeeName" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeePosition" class="form-label">Position</label>
                        <input type="text" class="form-control" id="EmployeePosition" name="employeePosition" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="EmployeeEmail" name="employeeEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeePhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="EmployeePhoneNum" name="employeePhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="EmployeeAddress" name="employeeAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeBirthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="employeeBirthdate" name="EmployeeBday" required>
                    </div>
                    <div class="mb-3">
                        <label for="employeeAge" class="form-label">Age</label>
                        <input type="number" class="form-control" id="employeeAge" name="EmployeeAge" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="employeeGender" class="form-label">Gender</label>
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

// -------------------------------------------------- ADD EMPLOYEE MODAL JS -------------------------------------------------- //

	    // Show Add Employee Modal
	    document.getElementById('addEmployeeBtn').addEventListener('click', function() {
        var modal = document.getElementById('addEmployeeModal');
        modal.style.display = "block";
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

// -------------------------------------------------- ADD EMPLOYEE JS -------------------------------------------------- //

        function addEmployee() {
        // Get form data
        var fullName = document.getElementById('full_name').value;
        var position = document.getElementById('position').value;
        var age = document.getElementById('age').value;
        var birthdate = document.getElementById('birthdate').value;
        var gender = document.getElementById('gender').value;
        var email = document.getElementById('email').value;
        var phoneNumber = document.getElementById('phone_number').value;
        var address = document.getElementById('address').value;

        // Validate form data (you can add more validation as needed)
        if (!fullName || !position || !age || !birthdate || !gender || !email || !phoneNumber || !address) {
            alert("Please fill in all fields.");
            return;
        }

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
                // Optionally, you can close the modal and refresh the page
                closeModal();
                location.reload();
            } 
            else 
            {
                alert("Error adding employee.");
            }
        };

        // Send the request with the form data
        var data = "full_name=" + encodeURIComponent(fullName) +
                   "&position=" + encodeURIComponent(position) +
                   "&age=" + encodeURIComponent(age) +
                   "&birthdate=" + encodeURIComponent(birthdate) +
                   "&gender=" + encodeURIComponent(gender) +
                   "&email=" + encodeURIComponent(email) +
                   "&phone_number=" + encodeURIComponent(phoneNumber) +
                   "&address=" + encodeURIComponent(address);

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
    </script>
</body>
</html>