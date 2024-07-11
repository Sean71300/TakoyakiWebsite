<?php
  session_start();
  
?>
<html>
    <head>
        <title>
            Contacts Page
        </title>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="customCodes/custom.css">
        <script src="customCodes/custom.js"></script>
        <style>
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
	  <div class="loading-screen">
		<img src="Images/loading.png" alt="Loading...">
	  </div>
      <div class="forNavigationbar sticky-top">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container-fluid">
            <a href="index.php"><img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 "></a>
            <a class="navbar-brand " href="index.php"><b>Hentoki</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav  ps-5 mb-2 mb-lg-0 col d-flex justify-content-between">
                  <li class="nav-item">
                    <a class="nav-link"  href="index.php">Home</a>
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
          <div class="py-lg-4 py-3"></div>
          <div class="py-lg-5 py-4"></div>
          <div class="py-lg-4 py-3"></div>
        </div>        
      </div>
      <div class="forcontent">        
        <div class="container-fluid">
          <div class="row d-flex justify-content-center py-5 mt-5">
            <div class="col-lg-5 col-12 fw-bolder">
              <span class="fs-3">Contact Us</span> 
              <br>
              <br>
              <span class="fs-6">Fill out this form to contact Hentoki about your concerns and comments</span>
              <form action="email.php" id="Contacts" method="post" class="w-100">
                
                <!--User Input-->
                <!--Subject-->
                <input name="subject" class="my-2 rounded-2 w-100" type="text" placeholder="Subject*" required><br>
                <!--Message/Comments-->
                <textarea name="message" class = "my-2 rounded-2 w-100" id="comments" placeholder="Comments/Message/Rating*" rows="15" required></textarea>
                 
                         
                <br>
                <button action="" type="submit" name="send" id="con" class="btn btn-danger ml-3 mr-3 w-100" data-bs-toggle="modal" data-bs-target="#exampleModal" >Submit</button>              
                

                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-3" id="exampleModalLabel">Thanks for Contacting us</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p id="modalName"></p>
                        <p id="modalEmail"></p>
                        <p id="modalNumber"></p>
                        <p id="modalMeans"></p>
                        <hr class="border rounded-5 border-danger border-2 opacity-25">
                        <p id="end" class="text-center fs-4">We will be back with you shortly!</p>
                      </div>
                      <div class="modal-footer">                        
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Return</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!--Footer-->
                <div class="col-12">
                  <div class="row mt-4  rounded-3 justify-content-center">                      
                    <div class="col-1 col-lg-2 my-2 ">
                      <img src="Images/call.png" class="img-fluid iconssm">
                    </div>                             
                    <div class="col-4 col-lg-9 m-3 align-self-center"> 
                        Phone <br>  <span class="text-danger"> 0945884598 </span> 
                    </div> 
                    <div class="col-1 col-lg-2 my-2">
                      <img src="Images/security.png" class="img-fluid iconssm">
                    </div>             
                    <div class="col-4 col-lg-9 m-3 align-self-center">
                      Opening Hour <br> <span class="text-danger"> 7:00 PM - 2:00 AM </span> 
                    </div>      
                    <div class="col-12 col-lg-0"></div>  
                    <div class="col-1 col-lg-2 my-2">
                      <img src="Images/email.png" class="img-fluid iconssm">
                    </div>          
                      
                    <div class="col-4 col-lg-9 m-3 align-self-center">
                      Email <br> <span class="text-danger"> hentokitakoyaki@gmail.com </span>
                    </div>
                  </div>  
                </div>                         
              </form>
              
            </div>
            <div class="col-lg-5 col-11 text-center ms-0 ms-lg-5 text-center map-responsive">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d965.0104223833935!2d120.9692609670136!3d14.653574979154179!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b5f3015260a5%3A0x555c5623d562a4fe!2sHentoki!5e0!3m2!1sen!2sph!4v1716303284036!5m2!1sen!2sph" width="700" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>
        </div>
        
      </div>
      <div class="forfooter">
        <div class="container-fluid text-light bg-black mt-5">          
          <div class="row ">
            <div class="col-12 text-center">
              <a href="index.php"><img src="Images/Logo.jpg" class="footer image-fluid my-4"></a>
            </div>           
            <div class="col-12">
              <div class="row">
                <div class="col-lg-3 col-md-1 col-sm-0"></div>
                <div class="col-lg-6 col-md-10 col-sm-12 d-flex justify-content-around pe-4">
                  <a href="index.php" class="text-decoration-none text-reset">Home</a>
                  <a href="About.php" class="text-decoration-none text-reset">About us</a>
                  <a href="Menu.php" class="text-decoration-none text-reset">Menu</a>
                  <a href="Contact.php" class="text-decoration-none text-reset">Contact</a>
                  <a href="Pages.php" class="text-decoration-none text-reset">Personnel</a>
                </div>
                <div class="col-lg-3 col-md-1 col-sm-0"></div>
              </div>              
            </div> 
            <div class="col-12 text-center my-3">
              <a href="https://www.facebook.com/hentokitakoyaki"><img src="Images/facebook.png" class="img-fluid footer1 m-2"></a>
              <a href="https://www.instagram.com/hentokitakoyaki/"><img src="Images/instagram.png" class="img-fluid footer1 m-2"></a> <br>  
              <span class="text-white-50">@copyright 2023 - hentoki</span>
            </div> 
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

            $query = "INSERT INTO rating (id, full_name, product_id, star, comment) 
                      VALUES ($userID, '$fullName', $productID, $star, '$comment')";

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