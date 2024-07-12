<?php
    include_once 'setup.php';

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
    function editdata()
    {
        
        $customer_name = $_POST["custoname"];
        $birthdate = $_POST["BirthD"];
        $age = calculateAge($_POST["BirthD"]);    
        $gender = $_POST["gender"];
        $email = $_POST["email"];
        $phone_num = $_POST["phone"];    
        $address = $_POST["address"];
        $customerID = htmlspecialchars($_SESSION["id"]);
        
        update_Customer($customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$customerID);      
        $_SESSION["full_name"] = $customer_name;
        
        
    }
    function update_Customer($customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$customerID)
    {
        $conn = connect();
        
        $stmt = $conn->prepare("UPDATE customers SET full_name = ?, age = ?, birthdate = ?, gender = ?, email = ?, phone_number = ?, address = ? WHERE customer_id = ?");
        $stmt->bind_param("sisssssi", $customer_name, $age, $birthdate, $gender, $email, $phone_num, $address,$customerID);
    
        if ($stmt->execute()) 
        {
            echo "";            
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
            echo "<h5>Record deleted successfully!</h5><br>";
        } 
        else 
        {
            echo "Could not delete data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

    function calculateAge($birthdate) 
    {
        $birthDate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        return $age;
    }
    function retainInput($fieldName, $type = 'text', $radioValue = '') {
        if (isset($_POST[$fieldName])) {
            $value = htmlspecialchars($_POST[$fieldName]);
            if ($type === 'radio') {
                if ($_POST[$fieldName] === $radioValue) {
                    echo 'checked';
                }
            } else {
                echo $value;
            }
        }
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
    function display_Value($value,$id)
    {
        $conn = connect();
        
        $sql = "SELECT $value FROM customers WHERE customer_id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                return $row["$value"];
            }
        } else {
            echo "No results found";
        }
        $conn->close();
    }
    function gender_check($value,$gender)
    {
        if ($value==$gender){
            echo 'Checked="Checked"';
        }
        else{
            echo "";
        }
    }

?>