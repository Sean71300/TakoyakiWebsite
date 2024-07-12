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
function connect()
{
    // Configuration
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'registration_db';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
$total = 0.00;
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $errors = 0;
      $phone_number = $_POST["phone_number"];

        if(checkPhone($phone_number) == false){
          $error_display = "Invalid phone number, please check or try again.";
          $errors++;
      }

      /*
      if ($errors == 0) {
        if ($res["result"] == 0) {
          $unique = $res['array'];
          // Insert data into database
          $conn = connect();
          $sql = "INSERT INTO registered 
                  (id, type, full_name, birthdate, age, gender, email, phone_number, address, password) 
                  VALUES 
                  ($gen_id, '$type', '$unique[0]', '$birthdate', $age, '$gender', '$unique[1]', '$unique[2]', '$unique[3]', '$hashed_password')";
          if ($conn->query($sql) === TRUE) {
              $message = "Payment success!";
          } else {
              echo "Error occured in connecting to the database, please try again.";
          } 
          $conn->close();
      } else {
          $errors++;
      }
          
    }*/
  }
  ?>
      <!--                                             -->
      <!-- Footer -->
    <?php include "Footer.php"?>      
    </body>
</html>