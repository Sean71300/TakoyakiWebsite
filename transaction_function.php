<?php
    include 'setup.php';
    
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
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["total_price"] . "</td>";
                    echo "<td>" . $row["transaction_date"] . "</td>";
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