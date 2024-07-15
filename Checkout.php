<?php
/*
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
*/
?>

<?php
session_start();

require_once "connect.php";
require_once "setup.php";
?>

<html>
  <head>
    <title>Checkout</title>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
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
    <!-- Navbar -->
    <?php include "Navigation.php"?>      

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
                <?php
                $total = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $product_total = $item['price'];
                        $total += $product_total;
                        echo $item['product_name'] . " - " . $item['product_category'];
                        echo " - ₱" . $item['price'];
                        echo "<br>";
                    }
                }
                ?>
                </div>
            </div>
    
            <div class="row">
                <hr>
                <div id="total-container">
                  Total: ₱<?php echo $total; ?>
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

  <?php
    #Check for valid phone number:
    function checkPhone($phone_number) {
      $phone_number = preg_replace('/\D/', '', $phone_number);
  
      if (strlen($phone_number) != 10 && strlen($phone_number) != 11) {
          return false;
      }
      $valid_area_codes = array('02', '032', '033', '034', '035', '036', '037', '038', '039', '041', '042', '043', '044', '045', '046', '047', '048', '049', '052', '053', '054', '055', '056', '057', '058', '059', '063', '064', '065', '066', '067', '068', '077', '078', '082', '083', '084', '085', '086', '087', '088', '089');
      if (substr($phone_number, 0, 1) != '0' && substr($phone_number, 0, 2) != '9' && !in_array(substr($phone_number, 0, 2), $valid_area_codes)) {
          return false;
      }
  
      return true;
    }

    if (isset($_POST['pay'])) {
      $errors = 0;
      $phone_number = $_POST["phone_number"];

        if(checkPhone($phone_number) == false){
          $error_display = "Invalid phone number, please check or try again.";
          $errors++;
      }
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
        <!-- [LOADING] -->
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
        <!-- [LOADING] -->

        <!-- [NAVIGATION BAR] -->
        <div class="forNavigationbar sticky-top">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a href="index.php">
                        <img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 ">
                    </a>
                    <a class="navbar-brand " href="index.php">
                        <b>Hentoki</b>
                    </a>
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
        <!-- [NAVIGATION BAR] -->

        <!-- [BODY] -->       
        <div class="container" style="height: 70vh;">
            <div class="container mt-5">
                <div class="row">
                    <h2 class="text-center fw-bold">
                        Your Order Summary
                    </h2>
                </div>
            </div>
        

            <div class="container d-flex mt-5">
                <div class="container">
                    <h4>Cart:</h4>
                    <div class="row">
                        <div class="container border border-black" style="height: 50vh; overflow-y: auto;" id="product-container">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th> </th>
                                    </tr>
                                </thead>    
                                <tbody>
                                        <?php
                                        if (isset($_POST['remove_from_cart'])) {
                                            $index = $_POST['remove_from_cart'];
                                            unset($_SESSION['cart'][$index]);
                                            $_SESSION['cart'] = array_values($_SESSION['cart']);
                                        }
                                        
                                        if (isset($_POST['update_cart'])) {
                                            $product_id = $_POST['product_id'];
                                            $new_quantity = $_POST['quantity'];
                                            foreach ($_SESSION['cart'] as &$item) {
                                                if ($item['product_id'] == $product_id) {
                                                    $item['quantity'] = $new_quantity;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        $total = 0;
                                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                            foreach ($_SESSION['cart'] as $key => $item) {
                                                $product_total = $item['price'] * $item['quantity'];
                                                $total += $product_total;
                                                echo '<tr>';
                                                echo '<td>' . $item['product_name'] . '</td>';
                                                echo '<td>' . $item['product_category'] . '</td>';
                                                echo '<td>';
                                                echo '<form action="" method="post">';
                                                echo '<input type="hidden" name="product_id" value="' . $item['product_id'] . '">';
                                                echo '<div class="input-group w-50">';
                                                echo '<input type="number" class="form-control" name="quantity" value="' . $item['quantity'] . '" min="1">';
                                                echo '<div class="input-group-append w-25">';
                                                echo '<button type="submit" name="update_cart" class="btn btn-primary">Update</button>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '<td>₱' . $product_total . '.00</td>';
                                                echo '<td>';
                                                echo '<form method="post" action="">';
                                                echo '<input type="hidden" name="remove_from_cart" value="' . $key . '">';
                                                echo '<button type="submit" class="btn btn-danger">Remove</button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <div class="row">
                        <hr style="margin-top: 25px;">
                        <div class="total-container">
                            <h5>Total: ₱<?php echo number_format($total, 2);?></h5>
                        </div>
                    </div>
                </div>
            

                <div class="container">
                    <div class="total-container">
                        <h5>Confirm Payment:</h5>
                        <h6 class="mt-4">Enter GCash Number:</h6>
                        <form method="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" action="">
                            <input type="text" class="w-100 mt-4 mb-4" style="height:50px"placeholder=" +63">
                            <input type="submit" name="pay" value="Pay"class="btn btn-warning w-100"></input>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- [FOOTER ] -->
        <div class="forfooter">
            <div class="container-fluid text-light bg-black mt-5">          
                <div class="row ">
                    <div class="col-12 text-center">
                        <a href="index.php">
                            <img src="Images/Logo.jpg" class="footer image-fluid my-4">
                        </a>
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