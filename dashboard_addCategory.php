<?php
    function add_Category($categoryType)
    {
        $conn = connect();

        $checkQuery = "SELECT category_id FROM categories WHERE category_id = ?";

        $id = checkDuplication(generate_CategoryID(),$checkQuery);

        $stmt = $conn->prepare("INSERT INTO categories (category_id, category_type) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $categoryType);

        if ($stmt->execute()) 
        {
            header("Location: Admin_dashboard.php?success=add");
        } 
        else 
        {
            echo "Error adding category: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }

// ------------------------------ HANDLE FORM SUBMISSION ------------------------------ //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['categoryType']) && empty($_POST["categoryID"])) 
        {
            add_Category($_POST['categoryType']);
        }
    }
?>