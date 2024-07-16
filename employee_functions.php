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
            $age = $_POST['EmployeeAge'];
            $bday = $_POST['EmployeeBday'];
            $gender = $_POST['employeeGender'];
            $email = $_POST['employeeEmail'];
            $phone = $_POST['employeePhone'];
            $address = $_POST['employeeAddress'];
            $email_pattern = "/^[a-zA-Z0-0._%+-]+@[a-zA-Z0-9.-]+\\.com$/";

            $errors = 0;
            $error_display = "";

            if (!preg_match($email_pattern, $email) ) 
            {
                $error_display = "Please enter a valid email address with a .com extension.";
                $errors++;
                // Redirect to Admin_category.php with a success query parameter
                header("Location: Admin_employee.php?fail=email");
            }
            if (checkPhone($phone) == false)
            {
                $error_display = "Invalid phone number, please check or try again.";
                $errors++;
                // Redirect to Admin_category.php with a success query parameter
                header("Location: Admin_employee.php?fail=phone");
            }

            if ($errors == 0)
            {
                add_Employee($position, $employee_name, $age, $bday, $gender, $email, $phone, $address);
            }
        }
        else if(isset($_POST['employeePosition'], $_POST['employeeName'], $_POST['EmployeeAge'], $_POST['EmployeeBday'], $_POST['employeeGender'], $_POST['employeeEmail'], $_POST['employeePhone'], $_POST['employeeAddress'], $_POST["employeeID"]))
        {
            $position = $_POST['employeePosition'];
            $employee_name = $_POST['employeeName'];
            $age = $_POST['EmployeeAge'];
            $bday = $_POST['EmployeeBday'];
            $gender = $_POST['employeeGender'];
            $email = $_POST['employeeEmail'];
            $phone = $_POST['employeePhone'];
            $address = $_POST['employeeAddress'];
            $email_pattern = "/^[a-zA-Z0-0._%+-]+@[a-zA-Z0-9.-]+\\.com$/";

            $errors = 0;
            $error_display = "";

            if (!preg_match($email_pattern, $email) ) 
            {
                $error_display = "Please enter a valid email address with a .com extension.";
                $errors++;
            }
            if (checkPhone($phone) == false)
            {
                $error_display = "Invalid phone number, please check or try again.";
                $errors++;
            }

            if ($errors == 0)
            {
                update_Employee($_POST['employeeID'],$position, $employee_name, $age, $bday, $gender, $email, $phone, $address);
            }
            else
            {
                echo "$error_display";
            }
        }
        else
        {
            $employeeID = intval($_POST["employeeID"]);
        
            delete_Employee($employeeID);
        }
    }

// --------------------------------------------------------- PHONE VALIDATION --------------------------------------------------------- //

    function checkPhone($phone_number) 
    {
        $phone_number = preg_replace('/\D/', '', $phone_number);

        if (strlen($phone_number) != 10 && strlen($phone_number) != 11) 
        {
            return false;
        }

        $valid_area_codes = array('02', '032', '033', '034', '035', '036', '037', '038', '039', '041', '042', '043', '044', '045', '046', '047', '048', '049', '052', '053', '054', '055', '056', '057', '058', '059', '063', '064', '065', '066', '067', '068', '077', '078', '082', '083', '084', '085', '086', '087', '088', '089');

        if (substr($phone_number, 0, 1) != '0' && substr($phone_number, 0, 2) != '9' && !in_array(substr($phone_number, 0, 2), $valid_area_codes)) 
        {
            return false;
        }

        return true;
    }
?>