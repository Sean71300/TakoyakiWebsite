<?php
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

// --------------------------------------------------------- HANDLE FORM SUBMISSION --------------------------------------------------------- //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['productName'], $_POST['categoryID'], $_POST['categoryType'], $_POST['productStatus'], $_POST['productPrice']) && empty($_POST["productID"])) 
        {
            add_Product($_POST['productName'],$_POST['categoryID'],$_POST['categoryType'],$_POST['productStatus'],$_POST['productPrice']);
        }
    }
?>