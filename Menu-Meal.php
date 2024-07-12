<?php
#Connect to database
session_start();
require_once "connect.php";

if(isset($_POST['product_id'])){
  $product_id = $_POST['product_id'];
} else {
  $product_id = NULL;
}

#Database select
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
	  
	  #scale:hover {
		transform: scale(1.09);
	  }
    </style>
  </head>

  <body class="bg-body-tertiary">
    <!-- Navbar -->
    <?php include "Navigation.php"?>      

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

        <div class="row mt-5">
          <div class="card border-0 col-12" style="width: 20rem">
            <div class="card-body">
              <img src="Images/beef.png" class="card-img-top" alt="">            
            </div>
            <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
            <p class="card-title">Beef Gyudon</p>
            <div class="d-flex justify-content-evenly">
              <p class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>85.00</p>
              <?php #set product_id for database connection ?>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <input type="hidden" name="product_id" value="2024160010">
                <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
              </form>
            </div>
          </div>

          <div class="card border-0 col-12" style="width: 20rem; margin-left: 1rem;">
            <div class="card-body">
              <img src="Images/pork.png" class="card-img-top" alt="">               
            </div>
            <img src="Images/rate.png" style="display:inline; margin-left: -5px; width: 50%" alt="">
            <p class="card-title">Pork Tonkatsu</p>
            <div class="d-flex justify-content-evenly">
              <p class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning" style="display:inline-block">₱</span>75.00</p>
              <?php #set product_id for database connection ?>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <input type="hidden" name="product_id" value="2024160011">
                <input type="submit" name="add_to_cart" value="+" class="bg-warning border-0 rounded text-white fw-bold" id="scale" style="transition: transform 0.2s ease-in-out; width: 3.5rem; height: 2.75rem; display: inline; margin-top: -18px; margin-right: 20px;" ></input>
              </form>
            </div>
          </div>			
        </div>
      </div>
		
      <div class="modal fade" id="input" tabindex="-1">
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
      </div>                                           

      <!-- Footer -->
    <?php include "Footer.php"?>      
    </body>
</html>