<?php
include_once "BasicDoc.php";
class ShoppingcartDoc extends BasicDoc
{

  protected function showHeader()
  {
    echo "<h1>Shopping Cart</h1>";
  }
  protected function showContent()
  {
    // Fetch cart items from the model
    $cartItems = $this->model->getCartLines();


    $totalPrice = 0;

    echo "<h2>Shopping Cart</h2>";

    if (empty($cartItems))
    {
      echo "<p>Your cart is empty.</p>";
      return;
    }

    foreach ($cartItems as $item)
    {
      echo "<div class='cart-item'>";
      echo "<a href='index.php?page=product_details&product_id=" . $item->id . "' style='cursor: pointer;'>";
      echo "<img src='Images/" . $item->file_name . "' alt='" . $item->name . "' style='width: 25%;' />";
      echo "</a>";

      echo "<h3>" . $item->name . "</h3>";
      echo "<p>Price: $" . $item->price . "</p>";
      echo "<p>Quantity: " . $item->quantity . "</p>";
      echo "<p>Subtotal: $" . $item->subtotal . "</p>";

      // Form for updating quantity with plus and minus buttons
      echo "<form onsubmit='handleSubmit(this); return false;' method='post'>";
      echo "<input type='hidden' name='page' value='shoppingcart'>";
      echo "<input type='hidden' name='action' value='update'>";
      echo "<input type='hidden' name='product_id' value='" . $item->id . "'>";

      // Minus button
      echo "<button type='button' onclick='changeQuantity(this, -1)'>&minus;</button>";

      // Quantity display
      echo "<input class='quantity' name='quantity' value='" . $item->quantity . "' min='1' readonly>";

      // Plus button
      echo "<button type='button' onclick='changeQuantity(this, 1)'>&plus;</button>";

      echo "</form>";

      echo "<br>";

      // Button for removing item
      echo "<form onsubmit='handleSubmit(this); return false;' method='post'>";
      echo "<input type='hidden' name='page' value='shoppingcart'>";
      echo "<input type='hidden' name='action' value='remove'>";
      echo "<input type='hidden' name='product_id' value='" . $item->id . "'>";
      echo "<button style=\"font-size:24px; color:red\" color><i class=\"material-icons\">delete</i></button>";
      echo "</form>";

      echo "<br>";

      echo "</div>";

      $totalPrice += $item->subtotal;
    }
    echo "<form action='index.php' method='post'>";
    echo "<input type='hidden' name='page' value='shoppingcart'>";
    echo "<input type='hidden' name='action' value='checkout'>";
    echo "<button style=\"font-size:24px\"><i class=\"fa fa-shopping-cart\"></i></button>";
    echo "</form>";

    echo "<h3>Total Price: $" . $totalPrice . "</h3>";

    // JavaScript function
    echo "<script>
   function handleSubmit(form) {
       var formData = new FormData(form);
       fetch('index.php', {
           method: 'POST',
           body: formData
       }).then(response => {
           if (response.ok) {
               window.location.href = 'index.php?page=shoppingcart';
           } else {
               alert('Error submitting form');
           }
       }).catch(error => console.error('Error:', error));
   }
   </script>";


    echo "<script>
    function changeQuantity(button, delta) {
        var form = button.parentElement;
        var quantityInput = form.querySelector('input[name=\"quantity\"]');
        var newQuantity = parseInt(quantityInput.value) + delta;
        if (newQuantity >= 1) {
            quantityInput.value = newQuantity;
            handleSubmit(form); // Submit the form
        }
    }
    </script>";

  }
}

?>