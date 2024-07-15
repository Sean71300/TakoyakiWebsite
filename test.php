<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
  <title>Remove Item from Cart</title>
</head>
<body>
  <?php
    session_start();

    if (isset($_POST['remove_item'])) {
      $index = $_POST['remove_item'];
      unset($_SESSION['cart'][$index]);
      $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
      echo "<h2>Your Cart</h2>";
      echo "<table>";
      echo "<tr><th>Product</th><th>Category</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>";

      foreach ($_SESSION['cart'] as $key => $item) {
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td>" . $item['product_category'] . "</td>";
        echo "<td>$" . $item['price'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>
        <form method='post'>
            <input type='hidden' name='remove_item' value='$key'>
            <button type='submit'>Remove</button>
        </form>
        </td>";
        echo "</tr>";
      }

      echo "</table>";
    } else {
      echo "<p>Your cart is currently empty.</p>";
    }
  ?>
</body>
=======
<html>
    <head>
        <title>Quantity Adjustment</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            button {
                font-size: 24px;
                padding: 8px 12px;
                margin: 0 8px;
            }
            #number {
                font-size: 24px;
                min-width: 40px;
                text-align: center;
            }
        </style>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const quantity = document.getElementById('number');
                const addNumber = document.getElementById('add');
                const minusNumber = document.getElementById('minus');

                let number = 1;

                addNumber.addEventListener('click', () => {
                    number++;
                    quantity.textContent = number;
                });

                minusNumber.addEventListener('click', () => {
                    if (number > 1) {
                        number--;
                        quantity.textContent = number;
                    }
                });
            });
        </script>
    </head>

    <body>
        <button id="minus">-</button>
        <span id="number">1</span>
        <button id="add">+</button>
        <span>Lorem</span>
    </body>
>>>>>>> dd204e5c20d28066fa42651b1f3210d8809595a8
</html>