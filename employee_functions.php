<?php
// --------------------------------------------------------- INCLUDE SETUP --------------------------------------------------------- //

    include 'setup.php';

// --------------------------------------------------------- ADD EMPLOYEE --------------------------------------------------------- //

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

// --------------------------------------------------------- UPDATE EMPLOYEE --------------------------------------------------------- //

    function update_Employee($employeeID,$position,$employee_name,$age,$birthdate,$gender,$email,$phone_num,$address)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE employees SET position = ?, full_name = ?, age = ?, birthdate = ?, gender = ?, email = ?, phone_number = ?, address = ?, password = ? WHERE employee_id = ?");
        $stmt->bind_param("ssissssssi", $position, $employee_name, $age, $birthdate, $gender, $email, $phone_num, $address, $hashed_pw, $employeeID);
    
        if ($stmt->execute()) 
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_employee.php?success=update");
        }
        else 
        {
            echo "Could not edit data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

// --------------------------------------------------------- DELETE EMPLOYEE --------------------------------------------------------- //

    function delete_Employee($employeeID)
    {
        $conn = connect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $employeeID = intval($_POST["employeeID"]);
        
            // Prepare a delete statement
            $sql = "DELETE FROM employees WHERE employee_id = ?";
            if ($stmt = $conn->prepare($sql)) 
            {
                $stmt->bind_param("i", $employeeID);
        
                // Attempt to execute the prepared statement
                if ($stmt->execute()) 
                {
                    echo "Employee deleted successfully.";
                } 
                else 
                {
                    echo "Error: Could not execute the delete statement.";
                }
            } 
            else 
            {
                echo "Error: Could not prepare the delete statement.";
            }
        } 
        else 
        {
            echo "Invalid request.";
        }
        
        $conn->close();
    }

// --------------------------------------------------------- DISPLAY EMPLOYEES --------------------------------------------------------- //
    
    function display_Employees()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM employees";
        $retval = $conn->query($sql);
    
        if (!$retval) 
        {
            echo "Error: " . $conn->error;
        } 
        else 
        {
            if ($retval->num_rows > 0) 
            {
                while ($row = $retval->fetch_assoc()) 
                {
                    $employeeID = $row["employee_id"];
                    $employeeImg = base64_encode($row["employee_img"]);
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . $employeeImg . "' height=60></td>";
                    echo "<td>" . $row["employee_id"] . "</td>";
                    echo "<td>" . $row["position"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["birthdate"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td><a href='#' class='btn-edit'><i class='fas fa-edit' onclick='updateEmployee(" . $employeeID . ")'></i></a>
                            <a href='#' class='btn-delete'><i class='fas fa-trash' onclick='deleteEmployee(" . $employeeID . ")'></i></a></td>";
                    echo "</tr>";
                }
            }
        }
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
        else if(isset($_POST['employeePosition'], $_POST['employeeName'], $_POST['EmployeeAge'], $_POST['EmployeeBday'], $_POST['employeeGender'], $_POST['employeeEmail'], $_POST['employeePhone'], $_POST['employeeAddress'], $_POST["employeeID"]))
        {
            update_Employee($_POST['employeeID'],$_POST['employeePosition'], $_POST['employeeName'], $_POST['EmployeeAge'], $_POST['EmployeeBday'], $_POST['employeeGender'], $_POST['employeeEmail'], $_POST['employeePhone'], $_POST['employeeAddress']);
        }
        else
        {
            $employeeID = intval($_POST["employeeID"]);
        
            delete_Employee($employeeID);
        }
    }
?>