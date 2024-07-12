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
            echo "Record deleted successfully!";
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
                    
                    //Variable initialization for DELETE Functionality
                    $product_id = $row["product_id"];

                    //Variable initialization for EDIT Functionality
                    $product_name = $row["product_name"];
                    $category_Type = $row["category_type"];
                    $status = $row["status"];
                    $price = $row["status"];

                    echo "<tr>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>
                        <i class='fas fa-edit edit-icon' title='Edit' id='editBtn' onclick=' update_Product( " . $product_id . ")'  ></i>
                        <i class='fas fa-trash delete-icon' title='Delete' id='deleteBtn' onclick='  deleteProduct(" . $product_id . ") ' ></i>";
                    echo "<td>";
                    echo "<tr>";
                }
            }
        }
        $conn->close();
    }

// --------------------------------------------------------- GET SPECIFIC PRODUCT INFO --------------------------------------------------------- //

function get_Employee($employee_id) 
{
    $conn = connect();

    $query = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
    $query->bind_param("i", $product_id);
    $query->execute();
    $result = $query->get_result();
    $product = $result->fetch_assoc();

    echo json_encode($product);

    $query->close();
    $conn->close();
}


// --------------------------------------------------------- HANDLE AJAX REQUEST --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Check if productID is set and is a valid integer
        if (isset($_POST["productID"]) && filter_var($_POST["productID"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) 
        {
            $productID = intval($_POST["productID"]);
        
            delete_Product($productID);
        }
    }

?>