<?php
    include 'setup.php';

    function add_Ratings($customerID,$rating,$comment)
    {
        $conn = connect();

        $sql = "SELECT full_name FROM customers WHERE customer_id = $customerID";
        $category = $conn->query($sql);
        $row = $category->fetch_assoc();

        $customer_name = $row["full_name"];
        $id = generate_RatingID();

        $sql = "INSERT INTO ratings
                    (rating_id,customer_id,full_name,product_id,rating,comment)
                    VALUES
                    ($id,$customerID,'$customer_name',$productID,$rating,'$comment')";

            mysqli_query($conn, $sql);
    }

    function delete_Rating($ratingID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM ratings WHERE rating_id = ?");
        $stmt->bind_param("i", $ratingID);
    
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
    
    function display_Ratings()
    {
        $conn = connect();

        $sql = "SELECT * FROM ratings";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["rating_id"] . "</td>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["rating"] . "</td>";
                    echo "<td>" . $row["comment"] . "</td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }
?>