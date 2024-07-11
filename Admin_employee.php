<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Employee</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Bg ng page pwede baguhin depende sainyo */
            margin: 0; 
        }
        .sidebar {
            background-color: white;
            height: 100vh; /* Full height sidebar */
            position: fixed;
            top: 0;
            left: -220px; /* Start off-screen sa navbar to */
            width: 220px;
            padding-top: 20px;
            transition: all 0.3s ease;
            z-index: 1030; 
        }
        .sidebar.active {
            left: 0; /* Slide in when active sa navbar */
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
            margin-right: 10px; /* Adjust margin sa icon and text */
        }
        .sidebar ul li a:hover {
            background-color: #FFC10B;
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
        .employee-list {
            margin-bottom: 20px; 
            overflow-x: auto; 
        }
        .employee-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .employee-table th,
        .employee-table td {
            padding: 12px;
            text-align: left;
            border-top: 1px solid gray; /* border lines */
        }
        .employee-table th {
            font-weight: bold;
            color: #953728;
            text-transform: uppercase;
        }

        /* Employee Image */
        .employee-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            float: left; 
            margin-right: 10px;
        }

        /* Edit and Delete Icons */
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
		.navbar-toggler {
        margin-bottom: 20px; /* Adjust hamburger for spacing */
		}
        /* Add New Employee Button */
        .add-employee {
            float: right;
            background-color: #FFC10B;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 2%;
        }
        .add-employee:hover {
            background-color: #953728; 
            color: white;
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
            width: calc(100% - 32px);
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
            margin-right: 8%;
        }

        .btn-clear {
            background-color: #953728;
        }

        .btn-primary:hover, .btn-clear:hover {
            background-color: #953728;
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
            .header {
                padding-right: 30px; 
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
            <li><a href="Admin_dashboard.html"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li><a href="Admin_transaction.html"><i class="fas fa-history fa-fw"></i> Transaction History</a></li>
            <li><a href="#"><i class="fas fa-users fa-fw"></i> Customers</a></li>
            <li><a href="#"><i class="fas fa-cogs fa-fw"></i> Services</a></li>
            <li><a href="Admin_employee.html"><i class="fas fa-user-tie fa-fw"></i> Employees</a></li>
            <li><a href="#"><i class="fas fa-chart-line fa-fw"></i> Reports</a></li>
            <li><a href="#"><i class="fas fa-comments fa-fw"></i> Feedbacks</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" id="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#">Dashboard</a>
            </div>
        </nav>

        <div class="container">
            <button class="add-employee" id="addEmployeeBtn">Add New Employee</button>
            <div class="employee-list">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>Employee Image</th>
                            <th>Employee Name</th>
                            <th>Employee Role</th>
                            <th>Employee Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="https://via.placeholder.com/50" alt="Employee Image" class="employee-img"></td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>johndoe@example.com</td>
                            <td>
                                <i class="fas fa-edit edit-icon" onclick="editEmployee(this)"></i>
                                <i class="fas fa-trash delete-icon" onclick="deleteEmployee(this)"></i>
                            </td>
                        </tr>
                        <!-- More employee rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="centered-text">Add Employee</h2>
            <form id="employeeForm">
                <label for="employeeName" class="form-label">Employee Name</label>
                <input type="text" id="employeeName" name="employeeName" class="form-control">
                <label for="employeeRole" class="form-label">Employee Role</label>
                <input type="text" id="employeeRole" name="employeeRole" class="form-control">
                <label for="employeeEmail" class="form-label">Employee Email</label>
                <input type="email" id="employeeEmail" name="employeeEmail" class="form-control">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn-primary" onclick="saveEmployee()">Save</button>
                    <button type="reset" class="btn-clear">Clear</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.content').style.marginLeft = document.getElementById('sidebar').classList.contains('active') ? '220px' : '0';
        });

        // Modal Logic
        var modal = document.getElementById("employeeModal");
        var addEmployeeBtn = document.getElementById("addEmployeeBtn");
        var closeBtn = document.getElementsByClassName("close")[0];

        addEmployeeBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function saveEmployee() {
            // Add your save logic here
            alert('Employee saved!');
            modal.style.display = "none";
        }

        function editEmployee(icon) {
            var row = icon.closest('tr');
            var name = row.cells[1].textContent;
            var role = row.cells[2].textContent;
            var email = row.cells[3].textContent;

            document.getElementById('employeeName').value = name;
            document.getElementById('employeeRole').value = role;
            document.getElementById('employeeEmail').value = email;

            document.getElementById('employeeModal').style.display = 'block';
        }

        function deleteEmployee(icon) {
            var row = icon.closest('tr');
            row.remove();
        }
    </script>
</body>
</html>