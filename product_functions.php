<?php
// --------------------------------------------------------- INCLUDE SETUP --------------------------------------------------------- //

    include 'setup.php';

// --------------------------------------------------------- ADD PRODUCT --------------------------------------------------------- //

    function add_Product($product_name,$categoryID,$categoryType,$status,$price)
    {
        $conn = connect();

        $checkQuery = "SELECT product_id FROM products WHERE product_id = ?";

        $id = checkDuplication(generate_ProductID(),$checkQuery);

        $sql = "INSERT INTO products
                (product_id,product_name,category_id,category_type,status,price)
                VALUES
                ($id,'$product_name',$categoryID,'$categoryType','$status',$price)";

        if (mysqli_query($conn, $sql))
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_products.php?success=add");
        }
        else 
        {
            echo "Error adding product: " . $stmt->error;
        }

        $conn->close();
    }

// --------------------------------------------------------- UPDATE PRODUCT --------------------------------------------------------- //

    function update_Product($product_name,$categoryID,$categoryType,$status,$price,$productID)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, category_id = ?, category_type = ?, status = ?, price = ? WHERE product_id = ?");
        $stmt->bind_param("sissii", $product_name, $categoryID, $categoryType, $status, $price, $productID);
    
        if ($stmt->execute()) 
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_products.php?success=update");
        }
        else 
        {
            echo "Could not edit data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

// --------------------------------------------------------- DELETE PRODUCT --------------------------------------------------------- //

    function delete_Product($productID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productID);
    
        if ($stmt->execute()) 
        {
            echo "Product deleted successfully.";
        } 
        else 
        {
            echo "Could not delete data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

// --------------------------------------------------------- DISPLAY PRODUCTS --------------------------------------------------------- //    
    
    function display_Products()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM products";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) 
                {
                    $productID = $row["product_id"];
                    $productImg = base64_encode($row["product_img"]);
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . $productImg . "' height=60></td>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>
                            <a href='#' class='btn btn-sm btn-success'><i class='fas fa-edit' onclick='updateProduct(" . $productID . ")'></i></a>
                            <a href='#' class='btn btn-sm btn-danger'><i class='fas fa-trash' onclick='deleteProduct(" . $productID . ")'></i></a>
                          </td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }

// --------------------------------------------------------- CHECK FOR DUPLICATION ID --------------------------------------------------------- //

    function checkDuplication($id, $checkQuery) 
    {
        $conn = connect();
        // Function to check for duplicate ID
        while (true) 
        {
            // Prepare the query to check for the duplicate ID
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) 
            {
                break;
            }
            $id++;

            $stmt->close();
        }
        $stmt->close();
        $conn->close();

        return $id;
    }

// --------------------------------------------------------- HANDLE FORM SUBMISSION --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice']) && empty($_POST["productID"])) 
        {
            add_Product($_POST['productName'],$_POST['categoryID'],$_POST['categoryType'],$_POST['productStatus'],$_POST['productPrice']);
        }
        else if(isset($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice'], $_POST["productID"]))
        {
            update_Product($_POST['productName'],$_POST['categoryID'],$_POST['categoryType'],$_POST['productStatus'],$_POST['productPrice'],$_POST["productID"]);
        }
        else
        {
            $productID = intval($_POST["productID"]);
    
            delete_Product($productID);
        }
    }
?>