<?php
    include 'setup.php';

    function add_Customer($customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$password)
    {
        $conn = connect();
        
        $id = generate_CustomerID();
        $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customers
                (customer_id,full_name,age,birthdate,gender,email,phone_number,address,password)
                VALUES
                ($id,'$customer_name',$age,'$birthdate','$gender','$email','$phone_num','$address','$hashed_pw')";

        mysqli_query($conn, $sql);
    }

    function update_Customer($customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$password,$cusomerID)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE customers SET full_name = ?, age = ?, birthdate = ?, gender = ?, email = ?, phone_number = ?, address = ?, password = ? WHERE customer_id = ?");
        $stmt->bind_param("sidsssssi", $customer_name, $age, $birthdate, $gender, $email, $phone_num, $address, $hashed_pw, $cusomerID);
    
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

    function delete_Customer($customerID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = ?");
        $stmt->bind_param("i", $cusomerID);
    
        if ($stmt->execute()) 
        {
            echo "<h1>Record deleted successfully!</h1><br>";
        } 
        else 
        {
            echo "Could not delete data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }
    
    function display_Customers()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM customers";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["birthdate"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }
?>