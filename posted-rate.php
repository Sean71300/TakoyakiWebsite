<?php
ob_start();
require 'welcome.php';

$content = ob_get_clean();
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
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="customCodes/custom.css">
    <style>
        body {
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 10px; 
            margin: 0;
        }
        .floating-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
        }
        .floating-icon img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        .star-icon {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
        }
        .star-icon input {
            display: none;
        }
        .star-icon label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
        }
        .star-icon input:checked ~ label,
        .star-icon label:hover,
        .star-icon label:hover ~ label {
            color: #ffca08;
        }
        .modal-body {
            padding: 2rem;
        }
        /* .char-count {
            font-size: 13px;
            text-align: left;
            margin-left: 40px;
        } */
        .DateTime {
            font-size: 0.8em;
            color: #888;
        }
        .floating-icon {
            position: fixed;
            bottom: 40px;
            right: 60px; 
            width: 80px; 
            height: 80px; 
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
        }

        .floating-icon img {
            width: 100%; 
            height: 100%;
            border-radius: 50%; 
        }

        /* .star {
            color: #ffcc00;
            font-size: 1em;
        } */
        .modal-header .close {
            padding: 0; 
            margin: 0; 
            background: none; 
            border: none;
            outline: none; 
            font-size: 30px; 
            color: #000;
        }

        .modal-header .close:hover {
            color: #ff0000; 
            text-decoration: none;
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
        /* .rowh {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .rowh .card-title {
            margin: 0; 
        } */

        .banner{
            background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.80), rgb(0, 0, 0,0.75)), url('Images/banner_a.png');;
            background-repeat: no-repeat;
            background-position: 100% 25%;
            background-attachment:fixed;
            background-size: cover;
        }
        #caption {
            color: hsl(0, 4%, 11%, 0.1);
            background-repeat:no-repeat;
            background-size: 0% 100%;
            background-clip:text;
            background-image: linear-gradient(90deg, #ffffff, #ffffff);
            animation-timeline: view();
            animation: scroll-anim 1.5s ease 0s 1 normal forwards;
        }
        @keyframes scroll-anim{
            0% {
                background-size: 0% 100%;
            }
            60% {
                background-size: 0% 100%;
            }
            100% {
                background-size: 100% 100%;
            }
        }
        @keyframes fade {
            0% {
                opacity: 0;
            }
            50% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        @keyframes fade-bottom {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            70% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            background-color:white;
            justify-content: center;
            align-items: center;
            opacity:1;
            transition: opacity 1s ease-in-out;
            z-index: 999;
        }
        .loading-screen img {
            animation: spin 1s linear forwards;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(1000deg); }
        }

        

    </style>
</head>
<body class="bg-body-tertiary">
    <script>
            window.onload = function() {
            const loadingScreen = document.querySelector('.loading-screen');
            loadingScreen.style.opacity = 0;
            setTimeout(() => {
                loadingScreen.remove();
                document.body.style.display = 'block';
            }, 1000);
            }
        </script>

        <div class="forNavigationbar sticky-top">
        <nav class="navbar navbar-expand-lg bg-body-tertiary ">
            <div class="container-fluid ">
            <a href="index.php"><img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 "></a>
            <a class="navbar-brand " href="index.php"><b>Hentoki</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ps-5 mb-2 mb-lg-0 col d-flex justify-content-between">                  
                    <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="About.php">About</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link"  href="Menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link"  href="Pages.php">Personnel</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="Contact.php">Contact</a>
                    </li> 
                
                    <?php  
                    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link " href="Login.php">Login</a>';
                    echo '</li>';
                    }
                    else{          
                    echo  '<div class="dropdown">';
                    echo  '<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">';
                    echo    "Welcome ".htmlspecialchars($_SESSION["full_name"]).'';   
                    echo  '</button>';
                    echo  '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    echo    '<li><a class="dropdown-item" href="rating.php">Rate</a></li>';
                    echo    '<li><a class="dropdown-item" href="reset.php">Change Password</a></li>';
                    echo    '<li><a class="dropdown-item" href="logout.php">Sign Out</a></li>';
                    echo  '</ul>';
                    echo  '</div>';
                    
                    }
                    ?>                               
                    </ul>   
                                    
                </div>                    
            </div>                   
            </nav>
        </div>
        <div class="forbanner">
            <div class="banner col-12 bg-black ">
            <div class="py-lg-5 py-3"></div>
            <div class="col-12 text-center text-light fs-1 fw-bolder" style="animation: fade 1s ease 0s 1 normal forwards;">
            HEAR FROM OUR CUSTOMERS
                <span class="fw-light fs-4 text-warning" id="caption">
                <br> Your Feedback matters!
                </span>
            </div>
            <div class="py-lg-5 py-3 "></div>
            </div>
        </div>

<div class="container-fluid"> 
    <div class="row">

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row["product_id"];
                $avg_rate = number_format($avgRatings[$product_id], 1);
                    echo '<div class="col-12 col-lg-3 col-md-3 col-sm-6">';
                        echo '<div class="card p-3 m-2">';
                            echo '<div class="row">';
                                echo '<div class="col-12 d-flex justify-content-between align-items-end">';
                                    echo '<p class="fw-bold m-0 ">' . htmlspecialchars($row["full_name"]) . '</p>';
                                    echo '<p class="bg-warning m-0 ml-2 text-uppercase text-dark px-2 py-1 rounded"><em>' .htmlspecialchars($row["product_name"]) . '</em></p>';
                                echo '</div>';
                                echo '<div class="col-12  d-flex align-items-center">';
                                    echo '<div>' .  str_repeat('⭐', (int)$row["rating"]). '</div>';
                                    echo '<p class="mb-0 ms-2">'. $avg_rate .'</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="row">';
                                echo '<div class="col-12 comment">';
                                    echo '<p>' . htmlspecialchars($row["comment"]) . '</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="row">';
                                echo '<div class="col-12 d-flex justify-content-between align-items-end">';
                                    echo '<p class="mb-0 bg-secondary text-light px-2 py-1 rounded">Taste</p>';
                                    echo '<div class="DateTime mb-0">' . htmlspecialchars(date("M j Y", strtotime($row["rate_timedate"]))) .  '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    // echo '</div>';
                }
            } else {
                echo "No reviews found.";
            }
            $conn->close();
            ?>
    </div>
</div>


<?php

function getFullName() {
 
    if(isset($_SESSION['full_name']))
    $fullname =  $_SESSION['full_name'];

    return $fullname;
}

function getUserID() {
    if(isset($_SESSION['id']))
    $uID =  $_SESSION['id'];
    return $uID;
}

function getProductID() {

    return 2024160001;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $star = $_POST['rating'];
    $comment = $_POST['comments'];
    $rating_id = generate_RatingID();
    $userID = getUserID();
    $fullName = getFullName();
    $productID = getProductID();

    $comment = substr($comment, 0, 500);


    $connection = connect();
    $comment = mysqli_real_escape_string($connection, $comment);

    $query = "INSERT INTO ratings (rating_id,customer_id, full_name, product_id, rating, comment) 
              VALUES ($rating_id, $userID, '$fullName', $productID, $star, '$comment')";

    if (mysqli_query($connection, $query)) {
        echo "<br>Rating submitted successfully.";
    } else {
        echo "<br>Error submitting rating: " . mysqli_error($connection);
    }

}
?>

<a href="#addReviewModal" class="floating-icon" data-toggle="modal" data-target="#addReviewModal">
        <img src="Images/addComment.png" alt="Add Review">
    </a>  
            
         <!-- Modal Structure -->
    <div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="rating-css">
                            <div class="star-icon">
                                <input type="radio" name="rating" id="rating5" value="5">
                                <label for="rating5" class="fa fa-star"></label>
                                
                                <input type="radio" name="rating" id="rating4" value="4">
                                <label for="rating4" class="fa fa-star"></label>
                                
                                <input type="radio" name="rating" id="rating3" value="3">
                                <label for="rating3" class="fa fa-star"></label>
                                
                                <input type="radio" name="rating" id="rating2" value="2">
                                <label for="rating2" class="fa fa-star"></label>
                                
                                <input type="radio" name="rating" id="rating1" value="1" checked>
                                <label for="rating1" class="fa fa-star"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comments">Comment/Suggestion:</label>
                            <textarea name="comments" id="comments" class="form-control" placeholder="500 characters limit" maxlength="500" rows="3" oninput="updateCharCount()"></textarea>
                        </div>
                        <p class="char-count">
                            Characters left: <span id="charCount">500</span>
                        </p>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>


<script>
        function openPopup() {
            document.getElementById("popup").style.display = "flex";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
        function updateCharCount() {
            var textarea = document.getElementById('comments');
            var charCount = document.getElementById('charCount');
            var remaining = 500 - textarea.value.length;
            charCount.textContent = remaining;
        }
    </script>    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
