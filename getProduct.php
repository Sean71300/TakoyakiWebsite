<?php
    
    include 'setup.php';

    function get_Product($product_id) 
    {
        $conn = connect(); // Assuming connect() function is defined in setup.php to establish database connection

        // Prepare SQL query
        $query = $conn->prepare("SELECT product_id, product_name, category_id, category_type, status, price FROM products WHERE product_id = ?");
        $query->bind_param("i", $product_id); // Bind product_id parameter as integer

        // Execute query
        $query->execute();

        // Get result set
        $result = $query->get_result();

        // Fetch product data as associative array
        $product = $result->fetch_assoc();
    
        // Output JSON response
        header('Content-Type: application/json'); // Set JSON content type header
        echo json_encode($product); // Encode the fetched data as JSON and output it

        // Clean up resources
        $query->close();
        $conn->close();
    }

    // Check if product_id is provided via GET request
    if (isset($_GET['product_id'])) 
    {
        get_Product($_GET['product_id']); // Call the function to fetch product data
    } 
    else 
    {
        echo json_encode(array('error' => 'Product ID not provided')); // Handle case where product_id is not provided
    }
?>