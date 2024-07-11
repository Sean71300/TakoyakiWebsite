<?php
    include 'setup.php';

    function add_Transaction($customerID,$productID,$total_price)
    {
        $conn = connect();

        $sql = "SELECT full_name,phone_number FROM customers WHERE customer_id = $customerID";
        $category = $conn->query($sql);
        $row = $category->fetch_assoc();

        $customer_name = $row["full_name"];
        $phone_num = $row["phone_number"];
        $transactID = generate_TransactionID();

        // foreach()
        // {
        //     $sql = "INSERT INTO transaction_history
        //             (transaction_id,customer_id,full_name,phone_number,product_id,total_price)
        //             VALUES
        //             ($transactID,$customerID,'$customer_name','$phone_num',$productID,$total_price)";

        //     mysqli_query($conn, $sql);
        // }
    }

    function delete_Transaction($transactID)
    {
        $conn = connect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $transactID = intval($_POST["transactID"]);
        
            // Prepare a delete statement
            $sql = "DELETE FROM transaction_history WHERE transaction_id = ?";
            if ($stmt = $conn->prepare($sql)) 
            {
                $stmt->bind_param("i", $transactID);
        
                // Attempt to execute the prepared statement
                if ($stmt->execute()) 
                {
                    echo "Transaction deleted successfully.";
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
    
    function display_Transaction()
    {
        $conn = connect();

        $sql = "SELECT * FROM transaction_history";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    $transactID = $row["transaction_id"];
                    echo "<tr>";
                    echo "<td>" . $row["transaction_id"] . "</td>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["total_price"] . "</td>";
                    echo "<td>" . $row["transaction_date"] . "</td>";
                    echo "<td><i class='fas fa-trash delete-icon' title='Delete' onclick='deleteTransaction(" . $transactID . ")'></i></td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $transactID = intval($_POST["transactID"]);
        delete_Transaction($transactID);
    }
?>