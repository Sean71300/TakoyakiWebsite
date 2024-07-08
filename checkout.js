function updateCart() {
  var getCart = JSON.parse(localStorage.getItem('cart')) || [];
  const productsList = document.getElementById('product-list');

  if (productsList) {
    if (getCart.length > 0) {
      getCart.forEach(product => {
        const listItem = document.createElement('li');
        listItem.textContent = `Product: ${product.productName}, Price: ${product.price}`;
        productsList.appendChild(listItem);
      });
    } else {
      // Display a message if the array is empty or not found
      const listItem = document.createElement('li');
      listItem.textContent = 'No products found in localStorage.';
      productsList.appendChild(listItem);
    }
  } else {
    console.error('Element with ID "product-list" not found in the DOM.');
  }
}

function Pay(){
  localStorage.clear();
}