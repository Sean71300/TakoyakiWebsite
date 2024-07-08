<?php
include 'welcome.php';
include 'setup.php';

$conn = connect();

// Fetch reviews from the rating table
$sql = "SELECT full_name, rate_date, star, comment FROM rating";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Posts</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 10px;
    }
    .rate-box {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 15px;
        width: 400px; 
        max-width: 100%;
        margin: 10px;
        word-wrap: break-word;
    }
    .name {
        font-size: 1em;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .DateTime {
        font-size: 0.8em;
        color: #888;
        margin-bottom: 10px;
    }
    .star {
        color: #ffcc00;
        font-size: 1em;
        margin-bottom: 10px;
    }
    .comment {
        font-size: 0.9em;
        color: #555;
        line-height: 1.4;
    }
    .comment p {
        margin: 0;
    }

    </style>
</head>
<body>
<?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="rate-box">';
            echo '<div class="name">' . htmlspecialchars($row["full_name"]) . '</div>';
            echo '<div class="DateTime">' . htmlspecialchars(date("M j, Y - H:i", strtotime($row["rate_date"]))) . '</div>';
            echo '<div class="star">' . str_repeat('⭐', (int)$row["star"]) . '</div>';
            echo '<div class="comment"><p>' . htmlspecialchars($row["comment"]) . '</p></div>';
            echo '</div>';
        }
    } else {
        echo "No reviews found.";
    }
    $conn->close();
    ?>
</body>
</html>
