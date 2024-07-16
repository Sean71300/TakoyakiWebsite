<?php
    function add_Employee($position, $employee_name, $age, $birthdate, $gender, $email, $phone_num, $address) 
    {
        $conn = connect();
        
        $checkQuery = "SELECT employee_id FROM employees WHERE employee_id = ?";

        $id = checkDuplication(generate_EmployeeID(),$checkQuery);
        $password = 'Hentoki_employee2024';
        $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO employees (employee_id, position, full_name, age, birthdate, gender, email, phone_number, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississssss", $id, $position, $employee_name, $age, $birthdate, $gender, $email, $phone_num, $address, $hashed_pw);

        if ($stmt->execute()) 
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_employee.php?success=add");
        } 
        else 
        {
            echo "Error adding employee: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }

// --------------------------------------------------------- HANDLE FORM SUBMISSION --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['employeePosition'], $_POST['employeeName'], $_POST['EmployeeAge'], $_POST['EmployeeBday'], $_POST['employeeGender'], $_POST['employeeEmail'], $_POST['employeePhone'], $_POST['employeeAddress']) && empty($_POST["employeeID"])) 
        {
            $position = $_POST['employeePosition'];
            $employee_name = $_POST['employeeName'];
            // Retrieve other form fields similarly

            add_Employee($position, $employee_name, $_POST['EmployeeAge'], $_POST['EmployeeBday'], $_POST['employeeGender'], $_POST['employeeEmail'], $_POST['employeePhone'], $_POST['employeeAddress']);
        }
    }
?>