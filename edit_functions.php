<?php
    
    include_once 'setup.php';
    $Confirmation = "";
    $passcheck = "";
    $result = "";

// ---------------------------------------------------------- START EDIT DATA -------------------------------------------------------------//



// ---------------------------------------------------------- GATHER USERS DATA -------------------------------------------------------------//
function editdata()
{        
    $customerID = htmlspecialchars($_SESSION["id"]);
    $image = search_Value("customer_img",$customerID);
    $filename = $_FILES["Dpict"]["name"];
    $tempname = $_FILES["Dpict"]["tmp_name"];
    $folder = "Images/" . $filename;    
    if ($filename != null && $tempname != null)
    {
        $image = file_get_contents($tempname);
        move_uploaded_file($tempname, $folder);
    }   
    $customer_name = $_POST["custoname"];
    $birthdate = $_POST["BirthD"];
    $age = calculateAge($_POST["BirthD"]);    
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $phone_num = $_POST["phone"];    
    $address = $_POST["address"];
    
    
    if (pass_Check()===true){
        if (update_Customer($image,$customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$customerID)==true)
        {
            $_SESSION["full_name"] = $customer_name; 
            return true;   
        }   
        else
        {
            return false;
        }   
          
    }   
    else
    {        
        return false;
    }
         
    
}

// ---------------------------------------------------------- UPDATE USERS DATA -------------------------------------------------------------//

function update_Customer($image,$customer_name,$age,$birthdate,$gender,$email,$phone_num,$address,$customerID)
    {
        $conn = connect();
        
        $stmt = $conn->prepare("UPDATE customers SET customer_img = ?,full_name = ?, age = ?, birthdate = ?, gender = ?, email = ?, phone_number = ?, address = ? WHERE customer_id = ?");
        $stmt->bind_param("ssisssssi",$image, $customer_name, $age, $birthdate, $gender, $email, $phone_num, $address,$customerID);

        if ($stmt->execute()) 
        {
            return true;         
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

// ---------------------------------------------------------- SAVE CHANGES -------------------------------------------------------------//
function pass_Check()
{
    $password = $_POST["pass"];
    $hashed_password = search_Value("password",htmlspecialchars($_SESSION["id"]));
    
    if(password_verify($password, $hashed_password)){        
        return true;
    }
    else
    {        
        return false;
    }
    
}
// ---------------------------------------------------------- GET IMAGE -------------------------------------------------------------//
function getUploadedImage() {
    
        
}