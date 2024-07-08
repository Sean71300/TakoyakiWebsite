<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  echo '<html>';
  echo '<head>';
  echo '<title>INVALID ACCESS</title>';
  echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';
  echo '</head>';
  echo '<body>';
  echo '<div class="h-100 container d-flex flex-column justify-content-center align-items-center">';
  echo '<div class="mt-4 alert alert-danger"> Please login to continue. </div>';
  echo '</div>';
  echo '</body>';
  echo '</html>';
  header("Refresh: 2; url=login.php");
  exit;
}
?>

<html>
  <head>
    <title>Checkout</title>
    <script src="js/bootstrap.js"></script>
    <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
      a {
        text-decoration: inherit;
        color: inherit;
      }

      .logo {
            display: inline-block;
            max-width: 50px;
            width: auto;
            height: auto;
            border-radius: 50%;
            margin-left: 2%;
          } 
      .footer{
        display: inline-block;
        max-width: 50px;
        width: auto;
        height: auto;
        border-radius: 50%;
      } 
      .footer1{
        display: inline-block;
        max-width: 35px;
        width: auto;
        height: auto;
      }
      .category-icon {
        width: 75px;
        height: 56px;
      }
      .category-card {
        margin-left: 25px;
        margin-right: 25px;
        padding: 0px 30px 0px 30px;
      }
      
      .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
      }
      .cart-item .remove-button {
        margin-left: 25px;
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
                    echo    '<li><a class="dropdown-item" href="#">Rate</a></li>';
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

      <!--                                             -->
    <div class="container" style="height: 70vh;">
      <div class="container">
      <div class="row">
        <h2 class="text-center fw-bold">Your Order Summary</h2>
      </div>
    </div>
    
    <div class="container d-flex mt-5">
        <div class="container">
            <h4>Cart:</h4>
            <div class="row">
                <div class="container border border-black" style="height: 35vh; overflow-y: auto;" id="product-container">
    
                </div>
            </div>
    
            <div class="row">
                <hr>
                <div id="total-container">
                    
                </div>
            </div>
        </div>
    
        <div class="container">
            <div id="shadow p-1 mb-5 bg-body-tertiary rounded">
                <h5>Confirm Payment:</h5>
                <h6 class="mt-4">Enter GCash Number:</h6>
                <input type="text" class="w-100 mt-4 mb-4" style="height:50px"placeholder="+63">
                <button class="btn btn-warning w-100"onclick="Pay()">Pay Now</button>
            </div>
        </div>
    </div>
  </div>
      <!--                                             -->

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
      <script src="order.js"></script>
    </body>
</html>