var setPrice;
var productName;

function Octo(){
  productName = "Octo Bits";
  setPrice = 39.00;
  addToCart(productName, setPrice);
}

function Crab(){
  productName = "Crab Bits"
  setPrice = 39.00;
  addToCart(productName, setPrice);
}

function Cheese(){
  productName = "Cheese Bits"
  setPrice = 39.00;
  addToCart(productName, setPrice);
}

function Bacon(){
  productName = "Bacon Bits"
  setPrice = 39.00
  addToCart(productName, setPrice);
}

function specialOcto(){
  productName = "10+1 Octo Bits"
  setPrice = 100.00;
  addToCart(productName, setPrice);
}

function specialCrab(){
  productName = "10+1 Crab Bits"
  setPrice = 100.00;
  addToCart(productName, setPrice);
}

function specialCheese(){
  productName = "10+1 Cheese Bits"
  setPrice = 100.00;
  addToCart(productName, setPrice);
}

function specialBacon(){
  productName = "10+1 Bacon Bits"
  setPrice = 100.00
  addToCart(productName, setPrice);
}

function barkadaAssorted(){
  productName = "Assorted Barkada Platter"
  setPrice = 320.00
  addToCart(productName, setPrice);
}

function barkadaCheesy(){
  productName = "Cheese Barkada Platter"
  setPrice = 320.00
  addToCart(productName, setPrice);
}

function gyudonPrice(){
  productName = "Gyudon Rice Meal"
  setPrice = 85.00
  addToCart(productName, setPrice);
}

function porkPrice(){
  productName = "Pork Tonkatsu Rice Meal"
  setPrice = 75.00
  addToCart(productName, setPrice);
}

var cart = [];

function addToCart(product, price) {
  var cartItem = {
    product: productName, 
    price: setPrice,
  };

  cart.push(cartItem);
  console.log(cart);
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCart();
}

function updateCart() {
  let total = 0;

  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Get the element where you want to display the cart items
  const cartContainer = document.getElementById('product-container');
  const totalContainer = document.getElementById('total-container');

  // Clear the cart container before updating
  cartContainer.innerHTML = '';

  // Loop through the cart items and create HTML elements to display them
  cart.forEach((item, index) => {
    // Create a new div element for the cart item
    const cartItemDiv = document.createElement('div');
    cartItemDiv.classList.add('cart-item');

    // Create elements for the product name, price, and remove button
    const itemDiv = document.createElement('div');
    itemDiv.textContent = `${item.product} - ₱${item.price.toFixed(2)}`;

    const removeButton = document.createElement('button');
    removeButton.className = "btn btn-danger";
    removeButton.classList.add('remove-button');
    removeButton.textContent = 'Remove';
    removeButton.addEventListener('click', () => {
      // Remove the item from the cart
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCart();
    });

    // Append the cart item div to the cart container
    cartItemDiv.appendChild(itemDiv);
    cartItemDiv.appendChild(removeButton);
    cartContainer.appendChild(cartItemDiv);

    total += item.price;
  });

  // Update the total container
  totalContainer.innerHTML = '';
  const totalDiv = document.createElement('h5');
  totalDiv.textContent = `Total: ₱${total.toFixed(2)}`;
  totalContainer.appendChild(totalDiv);
}

updateCart()



function Pay(){
  localStorage.clear();
  location.reload()
}