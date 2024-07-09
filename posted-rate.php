<?php
require_once 'setup.php';
$conn = connect();


$sql = "SELECT r.product_id, r.full_name, r.rate_timedate, r.rating, r.comment, p.product_name
        FROM ratings r
        INNER JOIN products p ON r.product_id = p.product_id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$avgRatings = [];
$productCounts = [];
while ($row = $result->fetch_assoc()) {
    $product_id = $row["product_id"];
    if (!isset($avgRatings[$product_id])) {
        $avgRatings[$product_id] = 0;
        $productCounts[$product_id] = 0;
    }
    $avgRatings[$product_id] += (int)$row["rating"];
    $productCounts[$product_id]++;
}

foreach ($avgRatings as $product_id => $totalStars) {
    $avgRatings[$product_id] = $totalStars / $productCounts[$product_id];
}

$result->data_seek(0);
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
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 10px;
        }
        .rate-box {
            background-color: #fff;
            border-radius: 3px; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 15px;
            width: 500px;
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
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
            margin-left: auto;
        }
        .star {
            color: #ffcc00;
            font-size: 1em;
        }
        .comment {
            font-size: 0.9em;
            color: #555;
            line-height: 1.4;
            margin-bottom: 5px; 
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .comment p {
            margin: 0;
            text-indent: 5px;
            text-align: justify;
        }
        .rowf {
            display: flex;
            align-items: center;
            margin: 0;
        }
        .rowf p {
            color: #646060;
            margin: 0;
            font-size: 14px;
        }
        .avg_rate {
            margin-left: 5px;
        }
        .product_name p {
            color: #2a2001;
            font-weight: bolder;
            font-size: 20px;
            text-indent: 5px;
        }
    </style>
</head>
<body>
    
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row["product_id"];
            $avg_rate = number_format($avgRatings[$product_id], 1); 

            echo '<div class="rate-box">';
            echo '<div class="product_name">';
            echo '<p>' . htmlspecialchars($row["product_name"]) . '</p>';
            echo '<hr>';
            echo '</div>';
            echo '<div class="comment"><p>' . htmlspecialchars($row["comment"]) . '</p></div>';
            echo '<div class="rowf">';
            echo '<div class="name">' . htmlspecialchars($row["full_name"]) . '</div>';
            echo '<div class="DateTime">' . htmlspecialchars(date("M j, Y - H:i", strtotime($row["rate_timedate"]))) . '</div>';
            echo '</div>';
            echo '<div class="rowf">';
            echo '<div class="star">' . str_repeat('⭐', (int)$row["rating"]) . '</div>';
            echo '<div class="avg_rate"><p>(' . $avg_rate . ')</p></div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No reviews found.";
    }
    $conn->close();
    ?>
    
</body>
</html>
