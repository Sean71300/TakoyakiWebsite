<?php
#Connect to database
#Insert transaction to db
#Automatic insert customer info to textfields   

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
        <?php include "Navigation.php"?>
        <!-- [NAVIGATION BAR] -->

        <!-- [BODY] -->       
        <div class="container">
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
                    <hr>
                    <div class="row">
                        <div class="container border border-black" style="height: 43.5rem; max-height: 100%; overflow-y: auto;" id="product-container">
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
                        <div class="d-flex">
                            <div class="total-container w-50">
                                <h5>Total: ₱<?php echo number_format($total, 2);?></h5>
                            </div>
                            <div stlye="overflow: auto;" class="container w-50">
                                <div style="float:right">    
                                    <h5>Clear Cart</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            

                <div class="container">
                    <div class="total-container">
                        <h4>Customer Info</h4>
                        <hr>
                        <div class="form-group">
                            <p>Name:</p>
                            <p name="name" class="form-control">Lorem</p>
                            <p>Phone Number:</p>
                            <p name="number" class="form-control">Lorem</p>
                            <p>Address:</p>
                            <p class="form-control" name="address">Lorem</p>
                        </div>
                    </div>
                    <div class="total-container mt-5">
                        <h4>Shipping Address</h4>
                        <hr>
                        <div class="form-group">
                            <p>Address:</p>
                            <p class="form-control" name="shipping-address">Lorem</p>
                        </div>
                    </div>
                    <div class="total-container mt-5">
                        <h4>Confirm Payment:</h4>
                        <hr>
                        <h6 class="mt-2">Enter GCash Number:</h6>
                        <form method="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" action="">
                            <input type="text" class="w-100 mt-2 mb-4" style="height:50px"placeholder=" 09XXXXXXXXX">
                            <input type="submit" name="pay" value="Pay"class="btn btn-warning w-100"></input>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- [FOOTER ] -->
        <?php include "footer.php"?>
    </body>
</html>