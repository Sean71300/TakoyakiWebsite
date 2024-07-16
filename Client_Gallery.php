<?php
session_start();
require_once 'connect.php'; 
require_once 'setup.php';
include 'modal.html';


// Fetch all images from the database for display
$db = mysqli_connect("localhost", "root", "", "hentoki_db");
$result = mysqli_query($db, "SELECT * FROM images_gallery");
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
            Mementos with Hentoki
                <span class="fw-light fs-4 text-warning" id="caption">
                <br> "Explore our Takoyaki gallery: each picture tells a delicious story of flavor and tradition."
                </span>
            </div>
            <div class="py-lg-5 py-3 "></div>
            </div>
        </div>
        <div id="wrapper">
    <!-- Content Section -->
    <div class="container-fluid-custom">
        <div class="row">
            <?php while ($row = mysqli_fetch_array($result)) : ?>
                <div class="col-md-3">
                    <div class="card mt-5 ml-3">
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['TimeStamp']; ?>   @Hentoki </h5>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
 







        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="modal.html"></script>
    <script>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
ob_end_flush(); // Flush the output buffer


?>

