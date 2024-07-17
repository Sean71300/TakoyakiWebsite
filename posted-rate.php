<?php
ob_start();
require 'welcome.php';


$content = ob_get_clean();
require_once 'setup.php';
$conn = connect();


ob_start();
    //query for fetching the columns from table rating
$sql = "SELECT r.product_id, r.full_name, r.rate_timedate, r.rating, r.comment, p.product_name
        FROM ratings r
        INNER JOIN products p ON r.product_id = p.product_id
        WHERE r.visibility_stat IS NOT NULL";
$result = $conn->query($sql);




if (!$result) {
    die("Query failed: " . $conn->error);
}


$avgRatings = [];
$productCounts = []; //init array
while ($row = $result->fetch_assoc()) {  //the data from result is turned into assoc array
    $product_id = $row["product_id"];    //get the product_id
    if (!isset($avgRatings[$product_id])) {  //check if the product_id exist in $avgRatings array
        $avgRatings[$product_id] = 0;   //if not init both to 0
        $productCounts[$product_id] = 0; //
    }
    $avgRatings[$product_id] += (int)$row["rating"]; //add the 'selected' row's rating to the existing total sum for that specific product_id
    $productCounts[$product_id]++; //count how many product ID in table ratings
}


foreach ($avgRatings as $product_id => $totalStars) { //iterate the assoc array then divide it to get the AVG
    $avgRatings[$product_id] = $totalStars / $productCounts[$product_id];
}


$result->data_seek(0); //pointer to index 0
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


    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="customCodes/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>



    <style>
        body {
            justify-content: center;
            align-items: center;
            flex-direction: column;
            /* gap: 10px;  */
            margin: 0;
            padding: 0;
            /* max-width: 100%;
            overflow-x: hidden;  */


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


        .DateTime {
            font-size: 0.8em;
            color: #888;
        }
   
        .font-title-rate{
            font-size: 35px;
            font-weight: bolder;
            font-family: Helvetica;
            margin: 7px;
            text-align: center;
            color: #FFC107;
        }


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


        .star_AVG p{
            font-size: 50px;
            margin: 0px;
            padding: 0px;
            text-align: center;
        }
        .confirm-modal{
            background-color: rgba(16, 16, 16, 0.7); 
        }
        .confirm-modal .modal-header{
            display: flex;
            justify-content: space-between;
        }
        .total_AVG p{
            font-size: 65px;
            color: #2A2117;
            font-family: Helvetica;
            margin: 0px;
            padding: 0px;
            text-align: center;
            font-weight: 200;
        }
        .total-num-ratings{
            font-size: 75px;
            margin: 0px;
            text-align: center;
            font-family: Helvetica;
        }
        .title-rate{
            font-size: 30px;
            margin: 0px;
            text-align: center;
            font-family: Helvetica;
        }


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
                    echo    '<li><a class="dropdown-item" href="posted-rate.php">Rate</a></li>';
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
            <div class="row px-2">
                <div class=" col-12 p-2">
                    <p class="font-title-rate"> RATINGS & REVIEWS</p>
                </div>
            </div>
            <div class="row m-0 p-2">
                    <div class="col-4 justify-content-center">
                    <?php
                        $averageRating = getAverageRating($conn); //get total AVG of all products
                        $formattedAverageRating = number_format($averageRating, 2); // Format to 2 decimal places
                        $stars = generateStars($averageRating); // functions for generating star
                        //post as HTML
                        echo '<div class="total_AVG m-0 p-0"> <p>' . $formattedAverageRating . '</p> </div>';
                        echo '<div class="star_AVG m-0 p-0"> <p >' . $stars . '</p> </div>';
                        ?>
                    </div>
                    <div class="col-4">
                        <!-- chart for Ratings -->
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-4">
                    <?php
                        $totalRate= countRatings($conn);
                        echo '<div class="total-num-ratings">' . $totalRate . '</div>' ;
                        echo '<div class="title-rate">'. 'Total Ratings Received' . '</div>' ;
                    ?>
                    </div>
            </div>
        </div>


<?php
    //fetch all ratings as count
    function fetchRating() {
        $conn = connect();


        $sql = "SELECT rating, COUNT(*) AS count 
                FROM ratings 
                WHERE visibility_stat IS NOT NULL 
                GROUP BY rating 
                ORDER BY rating DESC";
        $result = $conn->query($sql);
        $data = [];


            //check for num of returned row from DB
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'rating' => $row['rating'],
                    'count' => $row['count']
                ];
            }
        }
        $conn->close();
        return $data;


    }




    function getAverageRating($conn) {
        $sql = "SELECT AVG(rating) AS average_rating 
                FROM ratings 
                WHERE visibility_stat IS NOT NULL";
                $result = $conn->query($sql);
   
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['average_rating'];
        } else {
            return 0;
        }
    }
   
    function generateStars($averageRating) {
        // Round the average rating to the nearest half-star
        $roundedRating = round($averageRating * 2) / 2;
        $fullStars = floor($roundedRating);
        $halfStars = ceil($roundedRating - $fullStars);
        $emptyStars = 5 - $fullStars - $halfStars;
   
        // Generate star icons
        $stars = str_repeat('<i class="fas fa-star" style="color: #FFD700;"></i>', $fullStars);
        $stars .= str_repeat('<i class="fas fa-star-half-alt" style="color: #FFD700;"></i>', $halfStars);
        $stars .= str_repeat('<i class="far fa-star" style="color: #FFD700; "></i>', $emptyStars);
   
        return $stars;
    }
   
    function countRatings($conn) {
        $sql = "SELECT AVG(rating) AS average_rating, COUNT(*) AS total_ratings 
                FROM ratings 
                WHERE visibility_stat IS NOT NULL";
                $result = $conn->query($sql);
   
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_ratings'];
        } else {
            return 0;
        }
    }


?>






<div class="container-fluid px-5 ">
    <div class="row justify-content-center mt-3">


        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row["product_id"];
                $avg_rate = number_format($avgRatings[$product_id], 1);
                    echo '<div class="col-5 col-lg-5 col-md-5 col-sm-10">';
                        echo '<div class="card p-3 m-2">';
                            echo '<div class="row">';
                                echo '<div class="col-12 d-flex justify-content-between align-items-end">';
                                    echo '<p class="fw-bold m-0 ">' . htmlspecialchars($row["full_name"]) . '</p>';
                                    echo '<p class="bg-warning m-0 ml-2 text-uppercase text-dark px-2 py-1 rounded"><em>' .htmlspecialchars($row["product_name"]) . '</em></p>';
                                echo '</div>';
                                echo '<div class="col-12  d-flex align-items-center">';
                                    echo '<div>' .  generateStars($row["rating"]). '</div>';
                                    // echo '<p class="mb-0 ms-2">'. $avg_rate .'</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="row">';
                                echo '<div class="col-12 comment">';
                                    echo '<p>' . htmlspecialchars($row["comment"]) . '</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="row">';
                                echo '<div class="col-12 d-flex justify-content-between align-items-end">';
                                    echo '<p class="mb-0 bg-secondary text-light px-2 py-1 rounded">Review</p>';
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


    return 2024160009;
}

?>




<a href="#addReviewModal" class="floating-icon" data-toggle="modal" data-target="#addReviewModal">
        <img src="Images/addComment.png" alt="Add Review">
    </a>  
           
         <!-- Modal Structure -->

<form id="ratingForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmSubmitModal">Submit Review</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade confirm-modal" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSubmitModalLabel">Confirm Submission</h5>
                    <button type="button" class="close confirm-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your rating?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $star = $_POST['rating'];
    $comment = $_POST['comments'];
    $rating_id = generate_RatingID();
    $userID = getUserID();
    $fullName = getFullName();
    $productID = getProductID();

    $comment = substr($comment, 0, 500);

    $connection = connect();
    $comment = mysqli_real_escape_string($connection, $comment);

    $query = "INSERT INTO ratings (
                rating_id, 
                customer_id, 
                full_name, 
                product_id, 
                rating, 
                comment, 
                visibility_stat) 
                VALUES ($rating_id, $userID, '$fullName', $productID, $star, '$comment', NULL)";

    if (mysqli_query($connection, $query)) {
        echo "Rating submitted successfully.";
        header('Location: posted-rate.php');
        // $message = "Rating has been successfully deleted!";
        // $title = "Hentoki Customer Ratings";
        // echo "<script type='text/javascript'>
        //     showModal('$message', '$title');
        //     setTimeout(function() {
        //         window.location.href = 'posted-rate.php';
        //     }, 2000); 
        // </script>";

        exit;
    } else {
        echo "Error submitting rating: " . mysqli_error($connection);
    }
}
?>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="modal.html"></script>
    <script>

        function updateCharCount() {
            var textarea = document.getElementById('comments');
            var charCount = document.getElementById('charCount');
            var remaining = 500 - textarea.value.length;
            charCount.textContent = remaining;
        }

    </script>


    <script>


        const ratingsData = <?php echo json_encode(fetchRating()); ?>;


        // const labels = ratingsData.map(item => item.rating);
        const labels = ratingsData.map(item => `${item.rating} ☆`);
        const dataValues = ratingsData.map(item => item.count);


        // Chart.js configuration
        const config = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
            axis: 'y',
            label: 'Rating Counts',
            data: dataValues,
            fill: false,
            backgroundColor: [
                'rgb(14,157,88)',
                'rgb(191,208,71)',
                'rgb(255,193,5)',
                'rgb(239,126,20)',
                'rgb(211,98,89)'
            ],
            borderColor: [
                'rgb(14,157,88)',
                'rgb(191,208,71)',
                'rgb(255,193,5)',
                'rgb(239,126,20)',
                'rgb(211,98,89)'
            ],
            borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y'
        }
        };
    if (ratingsData.length === 0) {
        config.data.labels = [];
        config.data.datasets[0].data = [];
    }
        const myChart = new Chart(
        document.getElementById('myChart'),
        config
        );
    </script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
ob_end_flush(); // Flush the output buffer


?>

