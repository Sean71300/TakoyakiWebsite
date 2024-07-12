<?php
    include 'setup.php';

    function add_Product($product_name,$categoryID,$status,$price)
    {
        $conn = connect();
        
        $sql = "SELECT category_type FROM categories WHERE category_id = $categoryID";
        $category = $conn->query($sql);
        $row = $category->fetch_assoc();

        $categoryType = $row["category_type"];
        $id = generate_ProductID();

        $sql = "INSERT INTO products
                (product_id,product_name,category_id,category_type,status,price)
                VALUES
                ($id,'$product_name',$categoryID,'$categoryType','$status',$price)";

        mysqli_query($conn, $sql);
    }

    function update_Product($product_name,$categoryType,$status,$price,$productID)
    {
        $conn = connect();
        
        $sql = "SELECT category_id FROM categories WHERE category_type = $categoryType";
        $category = $conn->query($sql);
        $row = $category->fetch_assoc();

        $categoryID = $row["category_id"];
    
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, category_id = ?, category_type = ?, status = ?, price = ? WHERE product_id = ?");
        $stmt->bind_param("sissfi", $product_name, $categoryID, $categoryType, $status, $price, $productID);
    
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

    function delete_Product($productID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productID);
    
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
    
    function display_Products()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM products";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>
                        <i class=\"fas fa-edit edit-icon\" title=\"Edit\"></i>
                        <i class=\"fas fa-trash delete-icon\" title=\"Delete\"></i>";
                    echo "<td>";
                    echo "<tr>";
                }
            }
        }
        $conn->close();
    }
?>