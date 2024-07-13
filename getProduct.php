<?php
    include 'setup.php';

    function get_Product($product_id) 
    {
        $conn = connect();

        $query = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $query->bind_param("i", $product_id);
        $query->execute();
        $result = $query->get_result();
        $product = $result->fetch_assoc();

        echo json_encode($product);

        $query->close();
        $conn->close();
    }

    if (isset($_GET['product_id'])) 
    {
        get_Product($_GET['product_id']);
    }
?>
