<?php
    include 'setup.php';

    function add_Category($categoryType)
    {
        $conn = connect();
        
        $categoryID = generate_CategoryID();
    
        $sql = "INSERT INTO categories
                (category_id,category_type)
                VALUES
                ($categoryID,'Takoyaki')";

        mysqli_query($conn, $sql);
    }

    function update_Category($categoryID,$categoryType)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE categories SET category_type = ? WHERE category_id = ?");
        $stmt->bind_param("si", $categoryType, $categoryID);
    
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

    function delete_Category($categoryID)
    {
        $conn = connect();

        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->bind_param("i", $categoryID);
    
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
    
    function display_Categories()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM categories";
        $retval = $conn->query($sql);
    
        if (!$retval) {
            echo "Error: " . $conn->error;
        } else {
            if ($retval->num_rows > 0) {
                while ($row = $retval->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }
?>