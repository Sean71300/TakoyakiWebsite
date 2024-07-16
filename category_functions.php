<?php
// ------------------------------ INCLUDE SETUP ------------------------------ //

    include 'setup.php';

// ------------------------------ ADD CATEGORIES ------------------------------ //

    function add_Category($categoryType)
    {
        $conn = connect();
        
        $checkQuery = "SELECT category_id FROM categories WHERE category_id = ?";

        $id = checkDuplication(generate_CategoryID(),$checkQuery);
    
        $stmt = $conn->prepare("INSERT INTO categories (category_id, category_type) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $categoryType);

        if ($stmt->execute()) 
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_category.php?success=add");
        } 
        else 
        {
            echo "Error adding category: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }

// ------------------------------ UPDATE CATEGORIES ------------------------------ //

    function update_Category($categoryID,$categoryType)
    {
        $conn = connect();
    
        $stmt = $conn->prepare("UPDATE categories SET category_type = ? WHERE category_id = ?");
        $stmt->bind_param("si", $categoryType, $categoryID);
    
        if ($stmt->execute()) 
        {
            // Redirect to Admin_category.php with a success query parameter
            header("Location: Admin_category.php?success=update");
        }
        else 
        {
            echo "Could not edit data: " . $stmt->error;
        }
    
        $stmt->close();
        $conn->close();
    }

// ------------------------------ DELETE CATEGORIES ------------------------------ //

    function delete_Category($categoryID)
    {
        $conn = connect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $categoryID = intval($_POST["categoryID"]);
        
            // Prepare a delete statement
            $sql = "DELETE FROM categories WHERE category_id = ?";
            if ($stmt = $conn->prepare($sql)) 
            {
                $stmt->bind_param("i", $categoryID);
        
                // Attempt to execute the prepared statement
                if ($stmt->execute()) 
                {
                    echo "Category deleted successfully.";
                } 
                else 
                {
                    echo "Error: Could not execute the delete statement.";
                }
            } 
            else 
            {
                echo "Error: Could not prepare the delete statement.";
            }
        } 
        else 
        {
            echo "Invalid request.";
        }
        
        $conn->close();
    }
    
// ------------------------------ DISPLAY CATEGORIES ------------------------------ //

    function display_Categories()
    {
        $conn = connect();
        
        $sql = "SELECT * FROM categories";
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
                    $categoryID = $row["category_id"];
                    echo "<tr>";
                    echo "<td>" . $row["category_id"] . "</td>";
                    echo "<td>" . $row["category_type"] . "</td>";
                    echo "<td class='category-actions'>
                            <a href='#' class='btn btn-sm btn-success'><i class='fas fa-edit' onclick='updateCategory(" . $categoryID . ")'></i></a>
                            <a href='#' class='btn btn-sm btn-danger'><i class='fas fa-trash' onclick='deleteCategory(" . $categoryID . ")'></i></a>
                        </td>";
                    echo "</tr>";
                }
            }
        }
        $conn->close();
    }

// ------------------------------ HANDLE FORM SUBMISSION ------------------------------ //

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['categoryType']) && empty($_POST["categoryID"])) 
        {
            add_Category($_POST['categoryType']);
        }
        else if(isset($_POST['categoryType'], $_POST["categoryID"]))
        {
            update_Category($_POST['categoryID'],$_POST['categoryType']);
        }
        else
        {
            $categoryID = intval($_POST["categoryID"]);
        
            delete_Category($categoryID);
        }
    }
?>

