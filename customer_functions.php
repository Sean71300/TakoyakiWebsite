<?php
// --------------------------------------------------------- INCLUDE SETUP --------------------------------------------------------- //

    include 'setup.php';

// --------------------------------------------------------- DELETE CUSTOMER --------------------------------------------------------- //

    function delete_Customer($customerID)
    {
        $conn = connect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $customerID = intval($_POST["customerID"]);
        
            // Prepare a delete statement
            $sql = "DELETE FROM customers WHERE customer_id = ?";
            if ($stmt = $conn->prepare($sql)) 
            {
                $stmt->bind_param("i", $customerID);
        
                // Attempt to execute the prepared statement
                if ($stmt->execute()) 
                {
                    echo "Customer deleted successfully.";
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
    
// --------------------------------------------------------- DISPLAY CUSTOMER --------------------------------------------------------- //

    function display_Customers()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM customers";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) 
                {    
                    $customerID = $row["customer_id"];
                    $customerImg = base64_encode($row["customer_img"]);
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . $customerImg . "' height=60></td>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["birthdate"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td><a href='#' class='btn-delete'><i class='fas fa-trash' onclick='deleteCustomer(" . $customerID . ")'></i></a></td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }

// --------------------------------------------------------- HANDLE FORM SUBMISSION --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $customerID = intval($_POST["customerID"]);
    
        delete_Customer($customerID);
    }
?>

