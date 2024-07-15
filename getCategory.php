<?php
    include 'setup.php';

    function get_Category($category_id) 
    {
        $conn = connect();

        $query = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
        $query->bind_param("i", $category_id);
        $query->execute();
        $result = $query->get_result();
        $category = $result->fetch_assoc();

        echo json_encode($category);

        $query->close();
        $conn->close();
    }

    if (isset($_GET['category_id'])) 
    {
        get_Category($_GET['category_id']);
    }
?>