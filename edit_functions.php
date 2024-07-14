<?php

    include_once 'setup.php';

// ---------------------------------------------------------- GATHER USERS DATA -------------------------------------------------------------//
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

// ---------------------------------------------------------- UPDATE USERS DATA -------------------------------------------------------------//

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
// ---------------------------------------------------------- SEARCHES SPECIFIC DATA -------------------------------------------------------------//
function search_Value($value,$id)
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
// ---------------------------------------------------------- GENDER CHECK -------------------------------------------------------------//

function gender_check($value,$gender)
    {
        if ($value==$gender){
            echo 'Checked="Checked"';
        }
        else{
            echo "";
        }
    }
// ---------------------------------------------------------- CALCUlATE AGE -------------------------------------------------------------//

function calculateAge($birthdate) 
    {
        $birthDate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        return $age;
    }
    

?>