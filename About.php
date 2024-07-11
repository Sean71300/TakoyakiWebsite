<?php
  session_start();
  //ellaine hi
?>
<html>
  
    <head>
        <title>
            About Page for ellaine
        </title>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="customCodes/custom.css">
		<style>
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
	  <div class="loading-screen">
		<img src="Images/loading.png" alt="Loading...">
	  </div>
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
            HENTOKI HISTORY
            <span class="fw-light fs-4" id="caption">
              <br> The Authenthic Takoyaki Made from Excellent Taste
            </span>
              
          </div>
          <div class="py-lg-5 py-3 "></div>
        </div>
      </div>
      <div class="foropener p-5 my-3" style="animation: fade-bottom 1.4s ease 0s 1 normal forwards;">
        <div class="container-fluid">
          <div class="row">
            <div class="forcarousel col-12 col-lg-4  ">
              <div id="carouselExample" class=" carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner border border-1 border-black rounded-4">
                  <div class="carousel-item active" data-bs-interval="5000">
                    <img src="Images/A1.jpg" class="d-block w-100 img-fluid" alt="...">
                  </div>
                  <div class="carousel-item" data-bs-interval="5000">
                    <img src="Images/A2.jpg" class="d-block w-100 img-fluid" alt="...">
                  </div>
                  <div class="carousel-item" data-bs-interval="5000">
                    <img src="Images/A3.jpg" class="d-block w-100 img-fluid" alt="...">
                  </div>
                  <div class="carousel-item" data-bs-interval="5000">
                    <img src="Images/A4.jpg" class="d-block w-100 img-fluid" alt="...">
                  </div>
                  <div class="carousel-item" data-bs-interval="5000">
                    <img src="Images/A5.jpg" class="d-block w-100 img-fluid" alt="...">
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
            <div class="forstory col-12 col-lg-7">
              <div class="mx-lg-5 my-lg-1 m-3 fs-3">
			    <br>
                <span class="fw-bolder">OUR STORY</span>
				<br>
			  </div>
			  <div class="mx-lg-5 my-lg-2 m-3 fs-5">
                Hentoki started during our college days amidst the pandemic. We saw an opportunity to deliver delicious homemade takoyaki directly to people's homes, starting with our friends. The name "Hentoki combines "Hen," meaning spectacular, and "Toki," meaning up-to-date, reflecting our commitment to quality and innovation.
                <br>
                <br>
                Our mission is to deliver the authentic taste of takoyaki to people everywhere, bringing the flavors of Japan to your doorstep. We strive to make every bite a delightfull experience.
                <br>
                <br>
                Our vision is to expand Hentoki by opening more branches, spreading the joy of our authentic takoyakino different communities and making it accessible to as many people as possible. Join us on this exciting journey and savor the spectacular taste of Hentoki               
                <br>
				<br>
				
				 <a href="Contact.php"> 
                  <button type="button" class="btn Bcol rounded-5 text-light mt-4"><b>Contact Us</b></button>
                </a>
			  </div>

            </div>
          </div>

          </div>
          
      </div>
      <div class="foricons">
        <div class="container-fluid ">
          <div class="row justify-content-center" >
            <div class="col-lg-3 col-5 bg-white text-center p-3 m-3 rounded-5 shadow-sm" style="animation: fade-bottom 1.5s ease 0s 1 normal forwards;">
              <img src="Images/authentic.png" class="img-fluid icons m-5">
              <br>
              <span class="fs-4 fw-bolder">Authentic taste of takoyaki</span>
              <br>
              <br>
              To people everywhere bringing the flavors of Japan to your doorstep. We strive to make every bite a delight experience.
            </div>
            <div class="col-lg-3 col-5 bg-white text-center p-3 m-3 rounded-5 shadow-sm" style="animation: fade-bottom 1.7s ease 0s 1 normal forwards;">
              <img src="Images/Branching.png" class="img-fluid icons m-5">
              <br>
              <span class="fs-4 fw-bolder">Opening more branches</span>
              <br>
              <br>
              Spreading the joy of our authentic takoyaki to different communities and making it accessible to as many people as possible.
            </div>
            <div class="col-lg-3 col-5 bg-white text-center p-3 m-3 rounded-5 shadow-sm" style="animation: fade-bottom 1.9s ease 0s 1 normal forwards;">
              <img src="Images/Concern.png" class="img-fluid icons m-5">
              <br>
              <span class="fs-4 fw-bolder">Malasakit</span>  
              <br>
              <br>          
              Concern for the customer, employees.co- workers, business partners, community and company
            </div>
          </div>
          
        </div>
      </div>
      </div>
      <div class="forfooter ">
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
      
    </body>
</html>