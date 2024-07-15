<?php
#Connect to Database
session_start();
require_once "connect.php";

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
} else {
    $product_id = null;
}

#Select database and initialize product
$sql = "SELECT product_name, category_type, price FROM products WHERE product_id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $product_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $product_name, $category_type, $price);
            if (mysqli_stmt_fetch($stmt)) {
                $_SESSION["product_name"] = $product_name;
                $_SESSION["category_type"] = $category_type;
                $_SESSION["price"] = $price;
            }
        }
    }
    mysqli_stmt_close($stmt);
}

#Initialize Cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $_SESSION['cart'][] = array(
        'product_name' => $_SESSION["product_name"],
        'product_category' => $_SESSION["category_type"],
        'price' => $_SESSION["price"],
    );

  echo '<div class="modal fade" id="input" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="quantityInput">Order Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p class="text-center p-3">Order Added!</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
              </div>
            </div>
          </div>
        </div>';
}
?>
<html>
  <head>
    <title>Menu Page</title>
    <script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="customCodes/custom.css">
        <script src="customCodes/custom.js"></script>
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
      .modal-footer .btn {
        width: 100%;
      }
	  
	  #scale:hover {
		transform: scale(1.09);
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

      <!--                                             -->
        <div class="container">
          <div class="row">
            <h3 class="text-center fw-bold">Category</h3>
            <div class="container d-flex flex-row justify-content-center mt-4">
              <a href="Menu.php">
                <div class="card category-card border-black" id="scale" style="transition: transform 0.2s ease-in-out;">
                  <div class="card-body">
                    <img src="Images/category-1.png" alt="" class="category-icon">
                  </div>
                  <h5 class="text-center text-secondary mt-2">Takoyaki</h5>
                </div>
              </a>
              <a href="Menu-Meal.php">
                <div class="card category-card border-black" id="scale" style="transition: transform 0.2s ease-in-out;"> 
                  <div class="card-body"> 
                    <img src="Images/category-2.png" alt="" class="category-icon">
                  </div>
                  <h5 class="text-center text-secondary mt-2">Meal</h5>
                </div>
              </a>
            </div>
          </div>
        </div>

        <div class="container" style="margin-top: 70px;">
          <div class="row">
            <div class="col d-flex align-items-center">
              <h4 class="fw-bold">Best Sellers</h4>
            </div>
            <div class="col d-flex align-items-center justify-content-end">
              <a href="Checkout.php" > 
                <button type="button" class="btn Bcol rounded-5 text-light" style="background-color:#eb5757; padding:15px; transition: transform 0.2s ease-in-out;" id="scale"><img src="Images/checkout.png" height="35px;"><b>Check Out</b></button>
              </a>
            </div>
          </div>
          
          <div class="row d-flex flex-row justify-content-evenly mt-5">
            <!-- Octo Bits -->
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/octobits.png" class="card-img-top" alt="">            
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Octo Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>39.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160000">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>

            <!-- Crab Bits -->
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/crab bits.png" class="card-img-top" alt="">               
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Crab Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>39.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160001">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>

            <!-- Cheese Bits -->
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/cheese bits.png" class="card-img-top" alt="">               
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Cheese Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>39.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160002">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>

            <!-- Bacon Bits -->
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/bacon bits.png" class="card-img-top" alt="" style="margin-top: 30px;">
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Bacon Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>39.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160003">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>

          </div>

          <div class="row" style="margin-top: 150px;">
            <h4 class="fw-bold">Special Offers</h4>
          </div>
          <div class="row d-flex flex-row justify-content-evenly mt-5">
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon-promo.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/octobits.png" class="card-img-top" alt="">            
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Octo Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>100.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160004">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon-promo.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/crab bits.png" class="card-img-top" alt="">               
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Crab Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>100.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160005">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon-promo.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/cheese bits.png" class="card-img-top" alt="">               
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Cheese Bits</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>100.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160006">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>
            <div class="card border-0 col" style="width: 15rem">
              <div class="card-body">
                <img src="Images/ribbon-promo.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/bacon bits.png" class="card-img-top" alt="" style="margin-top: 30px;">
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Bacon Bits</p>
              <div class="d-flex justify-content-evenly">
                <p class="card-title fw-bold w-100" style="display:inline-block" id="price"><span class="text-warning" style="display:inline-block">₱</span>100.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160007">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>
          </div>

          <div class="row" style="margin-top: 150px;">
            <h4 class="fw-bold">Barkada Platters</h4>
          </div>
          <div class="row mt-5" style="margin-bottom: 150px;">
            <div class="card border-0 col-12" style="width: 20rem">
              <div class="card-body">
                <img src="Images/ribbon-special.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/assorted-barkada.png" class="card-img-top" alt="">            
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Assorted Barkada</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>400.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160008">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
              </div>
            </div>
            <div class="card border-0 col-12" style="width: 20rem; margin-left: 1rem;">
              <div class="card-body">
                <img src="Images/ribbon-special.png" alt="" style="display:inline; margin-left: -28px; margin-top: -17px;">
                <img src="Images/cheesy-barkada.png" class="card-img-top" alt="">               
              </div>
              <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
              <p class="card-title getProduct">Cheesy Barkada</p>
              <div class="d-flex justify-content-evenly">
                <p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>320.00</p>
                <?php #set product_id for database connection ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                  <input type="hidden" name="product_id" value="2024160009">
                  <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
                </form>
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
    </body>
</html>