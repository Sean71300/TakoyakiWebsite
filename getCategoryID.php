<?php
include 'setup.php';

function get_Category($category_type) {
    $conn = connect();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $category_type = "%" . $category_type . "%"; // Add wildcard characters
    $query = $conn->prepare("SELECT category_id FROM categories WHERE category_type LIKE ?");
    $query->bind_param("s", $category_type);

    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        echo json_encode($category);
    } else {
        echo json_encode(['category_id' => null]);
    }

    $query->close();
    $conn->close();
}

if (isset($_GET['category_type'])) {
    get_Category($_GET['category_type']);
}
?>
