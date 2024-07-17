<?php
// --------------------------------------------------------- INCLUDE SETUP --------------------------------------------------------- //

    include 'setup.php';

// --------------------------------------------------------- ADD PRODUCT --------------------------------------------------------- //
    
    function add_Product($product_name, $categoryID, $categoryType, $status, $price)
    {
        $conn = connect();
        if (!$conn) 
        {
            die("Connection failed: " . mysqli_connect_error());
        }

        $checkQuery = "SELECT product_id FROM products WHERE product_id = ?";
        $id = checkDuplication(generate_ProductID(), $checkQuery);

        $checkQuery = "SELECT product_name FROM products WHERE product_name LIKE ?";
        $name = checkDuplicationName($product_name, $checkQuery);

        $sql = "INSERT INTO products (product_id, product_name, category_id, category_type, status, price) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) 
        {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("isissd", $id, $name, $categoryID, $categoryType, $status, $price);

        if ($stmt->execute()) 
        {
            // Redirect to Admin_products.php with a success query parameter
            header("Location: Admin_products.php?success=add");
        }
        else 
        {
            echo "Error adding product: " . $stmt->error;
        }

        $stmt->close();
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
    
        if (!$retval) 
        {
            echo "Error: " . $conn->error;
        } 
        else 
        {
            if ($retval->num_rows > 0) 
            {
                while ($row = $retval->fetch_assoc()) 
                {
                    $productID = $row["product_id"];
                    $productImg = base64_encode($row["product_img"]);
                    $F_price = number_format($row["price"], 2);
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . $productImg . "' height=60></td>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $F_price . "</td>";
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

// --------------------------------------------------------- HANDLE FORM SUBMISSION --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Check for required POST parameters and handle actions accordingly
        if (isset($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice']) && empty($_POST["productID"])) 
        {
            // Add new product
                add_Product($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice']);
        } 
        elseif (isset($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice'], $_POST["productID"])) 
        {
            // Update existing product
            $productID = intval($_POST["productID"]);
            update_Product($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice'], $productID);
        } 
        elseif (!empty($_POST["productID"])) 
        {
            // Delete product
            $productID = intval($_POST["productID"]);
            delete_Product($productID);
        } 
        else 
        {
            // Handle unexpected cases or errors
            echo "Invalid request parameters.";
        }
    }

// -------------------------------------- CHECK FOR PRODUCT DUPLICATION -------------------------------------- //


    function checkDuplicationName($name, $checkQuery) {
        $conn = connect();
        // Function to check for duplicate ID
        while (true) {
            // Prepare the query to check for the duplicate ID
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows == 0) {
                break;
            }
            echo '<script>alert("Product already exist.");</script>';
            header("Location: Admin_products.php?");
    
            $stmt->close();
        }
        $stmt->close();
        $conn->close();
        return $name;
    }
?>