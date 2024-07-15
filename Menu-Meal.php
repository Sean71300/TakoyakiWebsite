<?php
#Connect to database
session_start();
require_once "connect.php";
require_once "setup.php";
?>

<?php
function getProductRatings($product_id) {
  $conn = connect();

  $sql = "SELECT rating FROM ratings WHERE product_id = $product_id";
  $result = mysqli_query($conn, $sql);

  $totalRatings = 0;
  $ratingsCount = 0;

  if($result){
    while($row = mysqli_fetch_assoc($result)) {
      $totalRatings += $row['rating'];
      $ratingsCount++;
    }
    mysqli_free_result($result);
  } else {
    echo "Error retrieving ratings: " . mysqli_error($conn);
  }
  $conn->close();
  if($ratingsCount > 0){
    $averageRating = $totalRatings / $ratingsCount;
  } else {
    $averageRating = 0;
  }
  return array('average' => $averageRating, 'count' => $ratingsCount);
}
?>

<?php
if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  #Select database and initialize product
$sql = "SELECT product_id, product_name, category_type, price FROM products WHERE product_id = ?";

#Get product details from db
if ($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $product_id);

  if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_store_result($stmt);
      if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $product_id, $product_name, $category_type, $price);
          if (mysqli_stmt_fetch($stmt)) {
              $_SESISON['product_id'] = $product_id;
              $_SESSION["product_name"] = $product_name;
              $_SESSION["category_type"] = $category_type;
              $_SESSION["price"] = $price;
          }
      }
  }
  mysqli_stmt_close($stmt);
}
} else {
  $product_id = null;
}

#Initialize Cart
if (isset($_POST['add_to_cart'])) {
  $quantity = $_POST['quantity'];
  if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
  }

#Put items in cart
  $_SESSION['cart'][] = array(
      'product_id' => $product_id,
      'product_name' => $_SESSION["product_name"],
      'product_category' => $_SESSION["category_type"],
      'price' => $_SESSION["price"],
      'quantity' => $quantity,
  );
}
?>

<?php
# Function retrieve products from a specific category
function retrieveProductsByCategory($categoryId) {
  $conn = connect();

  # SQL query to select products from the specified category
  $sql = "SELECT * FROM products WHERE category_id = $categoryId";
  $result = mysqli_query($conn, $sql);

  # Check if there are products in the result set
  if (mysqli_num_rows($result) > 0) {
    # Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
      $productId = $row['product_id'];
      $productName = $row['product_name'];
      $productPrice = $row['price'];
      $productImg = base64_encode($row['product_img']);
      $ratings = getProductRatings($productId);

      $ratingData = getProductRatings($productId);
      $averageRating = number_format($ratingData['average'], 1);
      $ratingsCount = $ratingData['count'];

      $fullStars = floor($averageRating);
      $halfStar = $averageRating - $fullStars >= 0.5 ? 1 : 0;
      $emptyStars = 5 - $fullStars - $halfStar;

      $stars = str_repeat('⭐', $fullStars) . ($halfStar ? '⭐️' : '') . str_repeat('☆', $emptyStars);

      # Output HTML structure for each product
      echo '<div class="card border-100" style="width: 18rem; height: 20rem; margin-left: 1%; margin-right: 1%;">';
        echo '<div class="card-body">';
          echo '<img src="data:image/png;base64,' . $productImg . '" class="card-img-top" alt="' . $productName . '" style = "margin-left: auto; margin-right: auto; width: 100%; height: 130px;">';
          #Set Ribbons
          switch ($categoryId) {
            case 2024030000:
              echo '<img src="Images/ribbon.png" alt="" style="display:inline; margin-left: -28px;">';
              break;
            case 2024030001:
              echo '<img src="Images/ribbon-promo.png" alt="" style="display:inline; margin-left: -28px;">';
              break;
            case 2024030002:
              echo '<img src="Images/ribbon-special.png" alt="" style="display:inline; margin-left: -28px;">';
              break;
          }  
        echo '</div>';
        
        echo "<p>$stars ($averageRating)</p>";      
        echo '<p class="card-title getProduct">' . $productName . '</p>';
        echo '<div class="d-flex justify-content-evenly align-items-center">';
          echo '<p id="price" class="card-title fw-bold w-100" style="display:inline-block"><span class="text-warning">₱</span>' . number_format($productPrice, 2) . '</p>';
          #quantity
            echo '<button class="bg-warning rounded text-white fw-bold border-0 w-25" onclick="decreaseCount(this)" data-product-id="' . $productId . '">-</button>';
            echo '<span style="font-size: 16px; font-weight: bold; margin-left: 5px; margin-right: 5px;" id="quantity_' . $productId . '">1</span>';
            echo '<button class="bg-warning rounded text-white fw-bold border-0 w-25" onclick="increaseCount(this)" data-product-id="' . $productId . '">+</button>';
          #form
          echo '
            <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post" id="cart-form-'.htmlspecialchars($productId).'">
              <input type="hidden" name="product_id" value="'.htmlspecialchars($productId).'">
              <input type="hidden" name="quantity" id="quantity-'.htmlspecialchars($productId).'" value="">
              <input type="submit" name="add_to_cart" value="Add to Cart" class="bg-warning border-0 rounded text-white fw-bold" style="font-size: 16px; margin-left:20px; height: 30px;" onclick="updateQuantity('.htmlspecialchars($productId).')">
            </form>';
        echo '</div>';
      echo '</div>';
    }
  } else {
    echo "0 results";
  }
  mysqli_close($conn);
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

    /*LOADING*/
    .loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        background-color: white;
        justify-content: center;
        align-items: center;
        opacity: 1;
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
<<<<<<< HEAD
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
    <!--- Navbar --->
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
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                  echo '<li class="nav-item">';
                  echo '<a class="nav-link " href="Login.php">Login</a>';
                  echo '</li>';
                }
                else {          
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
=======
    <!-- Navbar -->
    <?php include "Navigation.php"?>      
>>>>>>> dd204e5c20d28066fa42651b1f3210d8809595a8

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
    </div>

    <div class="container">
      <div class="row d-flex flex-row w-100 mt-5">
        <?php
        $conn = connect();
        $categoryID = 2024030003;
        retrieveProductsByCategory($categoryID);
        ?>
      </div>
<<<<<<< HEAD
    </div>
		                                         
    <!--- FOOTER --->
    <div class="forfooter">
      <div class="container-fluid text-light bg-black mt-5">          
        <div class="row ">
          <div class="col-12 text-center">
            <a href="index.html"><img src="Images/Logo.jpg" class="footer image-fluid my-4"></a>
          </div>           
          <div class="col-12">
            <div class="row">
              <div class="col-lg-3 col-md-1 col-sm-0"></div>
              <div class="col-lg-6 col-md-10 col-sm-12 d-flex justify-content-around pe-4">
                <a href="index.html" class="text-decoration-none text-reset">Home</a>
                <a href="About.html" class="text-decoration-none text-reset">About us</a>
                <a href="Menu.php" class="text-decoration-none text-reset">Menu</a>
                <a href="Contact.html" class="text-decoration-none text-reset">Contact</a>
                <a href="Pages.html" class="text-decoration-none text-reset">Personnel</a>
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

    <script>
      let qty = 1;

      function decreaseCount(button) {
      const productId = button.dataset.productId;
      const quantityElement = document.getElementById('quantity_' + productId);
      let quantity = parseInt(quantityElement.textContent);
      if (quantity > 1) {
          quantity--;
          quantityElement.textContent = quantity;
        }
      }

      function increaseCount(button) {
        const productId = button.dataset.productId;
        const quantityElement = document.getElementById('quantity_' + productId);
        let quantity = parseInt(quantityElement.textContent);
        quantity++;
        quantityElement.textContent = quantity;
      }
    </script>
    <script>
      function updateQuantity(productId) {
        var quantitySpan = document.getElementById('quantity_' + productId);
        var quantityInput = document.getElementById('quantity-' + productId);
        quantityInput.value = quantitySpan.textContent;
      }
    </script>
=======
		
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
>>>>>>> dd204e5c20d28066fa42651b1f3210d8809595a8
    </body>
</html>