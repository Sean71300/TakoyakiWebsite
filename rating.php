<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Rating Popup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: #fff;
            border: 4px solid #838383;
            padding: 20px;
            text-align: center;
            width: 450px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .close-btn {
            align-self: flex-end;
            font-size: 24px;
            color: #000;
            border: none;
            background: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 10px;
        }

        .close-btn:hover {
            color: #cc0000;
        }

        .rating-css {
            margin-bottom: 20px;
        }

        .rating-css div, .rating-css input {
            color: #ffe400;
            font-size: 30px;
            font-family: sans-serif;
            font-weight: 800;
            text-transform: uppercase;
            padding: 20px 0;
        }

        .rating-css .star-icon {
            display: inline-block;
        }

        .rating-css input {
            display: none;
        }

        .rating-css label {
            font-size: 60px;
            text-shadow: 1px 1px 0 #ffe400;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .rating-css input:checked ~ label {
            color: #838383;
        }

        .rating-css label:active {
            transform: scale(0.8);
        }

        .rating-css textarea {
            width: 80%;
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            resize: vertical;
        }

        .rating-css label[for="comments"] {
            font-size: 18px;
            margin-top: 10px;
            display: block;
        }

        .rating-css button {
            padding: 10px 20px;
            color: #333;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .rating-css button:hover {
            background-color: #ccb400;
        }
    </style>
</head>
<body onload="openPopup()">
    <div class="popup" id="popup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup()">X</button>
            <div class="rating-css">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="star-icon">
                        <label for="rating1" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="rating1" value="1" checked>

                        <label for="rating2" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="rating2" value="2">
                        
                        <label for="rating3" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="rating3" value="3">
                        
                        <label for="rating4" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="rating4" value="4">
                        
                        <label for="rating5" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="rating5" value="5">
                    </div>
                    <label for="comments">Comment/Suggestion:</label>
                    <textarea name="comments" id="comments" placeholder="120 characters limit" rows="3"></textarea>
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <?php

    include 'setup.php';
    

    function getFullName() {     
        return "Maam Kathleen Dimaano";
    }

    function getUserID() {
        return 2024101000;
    }

    function getProductID() {
        return 123;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $star = $_POST['rating'];
        $comment = $_POST['comments'];
        $userID = getUserID();
        $fullName = getFullName();
        $productID = getProductID();

        $comment = substr($comment, 0, 120);

        echo "Star: $star, Comment: $comment, UserID: $userID, FullName: $fullName, ProductID: $productID";

        $connection = connect();
        $comment = mysqli_real_escape_string($connection, $comment);
        $rating_id = generate_RatingID();

        $query = "INSERT INTO ratings (rating_id, customer_id , product_id, rating, comment) 
                  VALUES ('$rating_id', '$userID', $productID, $star, '$comment')";

        if (mysqli_query($connection, $query)) {
            echo "<br>Rating submitted successfully.";
        } else {
            echo "<br>Error submitting rating: " . mysqli_error($connection);
        }

    }
    ?>

    <script>
        function openPopup() {
            document.getElementById("popup").style.display = "flex";
            
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
               
        }
        
        
    </script>
</body>
</html>
