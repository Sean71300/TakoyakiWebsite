<?php
    include 'setup.php';

    function add_Employee($position,$employee_name,$age,$birthdate,$gender,$email,$phone_num,$address,$password)
    {
        $conn = connect();
        
        $id = generate_EmployeeID();
        $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employees
                (employee_id,position,full_name,age,birthdate,gender,email,phone_number,address,password)
                VALUES
                ($id,'$position','$employee_name',$age,'$birthdate','$gender','$email','$phone_num','$address','$hashed_pw')";

        mysqli_query($conn, $sql);
    }

    function update_Employee($position,$employee_name,$age,$birthdate,$gender,$email,$phone_num,$address,$password,$employeeID)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE employees SET position = ?, full_name = ?, age = ?, birthdate = ?, gender = ?, email = ?, phone_number = ?, address = ?, password = ? WHERE employee_id = ?");
        $stmt->bind_param("ssidsssssi", $position, $employee_name, $age, $birthdate, $gender, $email, $phone_num, $address, $hashed_pw, $employeeID);
    
        if ($stmt->execute()) 
        {
            echo "<h1>Record edited successfully!</h1><br>";
        }
        else 
        {
            echo "Could not edit data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

    function delete_Employee($employeeID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $stmt->bind_param("i", $employeeID);
    
        if ($stmt->execute()) 
        {
            echo "Record deleted successfully!";
        } 
        else 
        {
            echo "Could not delete data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }
    
    function display_Employees()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM employees";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    $employeeID = $row["employee_id"];
                    echo "<tr>";
                    echo "<td>" . $row["employee_id"] . "</td>";
                    echo "<td>" . $row["position"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["birthdate"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td><i class='fas fa-edit edit-icon' title='Edit' id='edit' onclick='deleteTransaction(" . $employeeID . ")'></i>
                              <i class='fas fa-trash delete-icon' title='Delete' onclick='deleteEmployee(" . $employeeID . ")'></i></td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $employeeID = intval($_POST["employeeID"]);
        delete_Employee($employeeID);
    }
?>