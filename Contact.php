<?php
  session_start();
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
  {
    header("Refresh: 2; url=login.php");
    exit;
  }

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
    .banner{
        background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.50), rgb(0, 0, 0,0.45)), url('Images/cont-bg.jpg');;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
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
    .banner-image { 
      background-size: cover; /* Cover the entire container */ 
      background-position: center; /* Center the image */ 
      background-repeat: no-repeat;
      height: 300px; /* Set a fixed height or adjust as needed  */
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
      <!-- Navbar -->
    <?php include "Navigation.php"?>      
      <div class="forbanner">
        <div class="banner col-12 bg-black ">
          <div class="py-lg-5 py-3"></div>
          <div class="col-12 text-center text-light fs-1 fw-bolder" style="animation: fade 1s ease 0s 1 normal forwards;">
            CONTACTS & LOCATION
            <span class="fw-light fs-4" id="caption">
              <br> Easy Access And Fast Interaction
            </span>
          </div>
          <div class="py-lg-5 py-3 "></div>
        </div>
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
                <!--Name-->
                <?php 
                if(isset($_SESSION["full_name"]))
                  {
                                        
                  }                 
                
                ?>





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
      <!-- Footer -->
    <?php include "Footer.php"?>      
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