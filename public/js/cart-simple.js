// Simple cart functionality
console.log("Cart.js loading...");

function setCartCount(count) {
  const cartCountElement = document.querySelector(".cart-count");
  if (!cartCountElement) {
    console.log("Cart count element not found");
    return;
  }

  console.log("Setting cart count to:", count);

  if (count > 0) {
    cartCountElement.textContent = count;
    cartCountElement.style.display = "inline-block";
  } else {
    cartCountElement.style.display = "none";
  }
}

function updateCartCount() {
  const cartCountElement = document.querySelector(".cart-count");
  if (!cartCountElement) {
    console.log("Cart count element not found in updateCartCount");
    return;
  }

  // Build the cart count URL more reliably
  let countUrl;
  if (window.location.pathname.includes("index.php")) {
    // If we're using index.php in the URL
    countUrl =
      window.location.origin + window.location.pathname + "/cart/count";
  } else {
    // If we're not using index.php
    countUrl =
      window.location.origin + "/ecommerce-site/public/index.php/cart/count";
  }

  console.log("Fetching cart count from:", countUrl);

  fetch(countUrl, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => {
      console.log("Cart count response status:", response.status);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Cart count data received:", data);
      setCartCount(parseInt(data.count || 0));
    })
    .catch((error) => {
      console.error("Error fetching cart count:", error);
    });
}

// Main initialization
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM loaded, initializing cart functionality...");

  // Update cart count on page load
  updateCartCount();

  // Handle add to cart forms
  const addToCartForms = document.querySelectorAll(".add-to-cart-form");
  console.log("Found", addToCartForms.length, "add-to-cart forms");

  addToCartForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      console.log("Add to cart form submitted");

      const button = form.querySelector('button[type="submit"]');
      const originalText = button.innerHTML;

      // Disable button and show loading
      button.disabled = true;
      button.innerHTML = "Adding...";

      // Get form data
      const formData = new FormData(form);

      // Use the form's action URL
      const actionUrl = form.getAttribute("action");
      console.log("Submitting to:", actionUrl);

      fetch(actionUrl, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => {
          console.log("Add to cart response status:", response.status);
          return response.json();
        })
        .then((data) => {
          console.log("Add to cart response data:", data);

          if (data.success) {
            // Show success state
            button.innerHTML = "✓ Added";
            button.style.backgroundColor = "#28a745";

            // Update cart count after a short delay
            setTimeout(() => {
              updateCartCount();
            }, 200);

            // Reset button after 2 seconds
            setTimeout(() => {
              button.innerHTML = originalText;
              button.style.backgroundColor = "";
              button.disabled = false;
            }, 2000);
          } else {
            alert(
              "Failed to add item to cart: " + (data.message || "Unknown error")
            );
            button.innerHTML = originalText;
            button.disabled = false;
          }
        })
        .catch((error) => {
          console.error("Error adding to cart:", error);
          alert("Failed to add item to cart");
          button.innerHTML = originalText;
          button.disabled = false;
        });
    });
  });

  // Handle quantity updates on cart page
  const quantityButtons = document.querySelectorAll(".change-quantity");
  quantityButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const action = this.getAttribute("data-action");
      const itemId = this.getAttribute("data-id");
      const input = this.closest(".d-flex").querySelector("input");
      let quantity = parseInt(input.value);
      const oldQuantity = quantity;

      if (action === "increase") {
        quantity++;
      } else if (action === "decrease" && quantity > 1) {
        quantity--;
      } else {
        return;
      }

      input.value = quantity;

      // Build URL for update more reliably
      let updateUrl;
      if (window.location.pathname.includes("index.php")) {
        updateUrl =
          window.location.origin +
          window.location.pathname +
          "/updateQuantity/" +
          itemId +
          "/" +
          quantity;
      } else {
        updateUrl =
          window.location.origin +
          "/ecommerce-site/public/index.php/cart/updateQuantity/" +
          itemId +
          "/" +
          quantity;
      }

      fetch(updateUrl, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update cart count locally
            const cartCountElement = document.querySelector(".cart-count");
            if (cartCountElement) {
              const currentCount = parseInt(
                cartCountElement.textContent || "0"
              );
              const newCount = currentCount + (quantity - oldQuantity);
              setCartCount(newCount);
            }
          } else {
            input.value = oldQuantity;
            alert("Error updating quantity: " + (data.message || ""));
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          input.value = oldQuantity;
        });
    });
  });

  // Handle remove from cart
  const removeButtons = document.querySelectorAll(".remove-item");
  removeButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const itemId = this.getAttribute("data-id");

      // Build URL for remove more reliably
      let removeUrl;
      if (window.location.pathname.includes("index.php")) {
        removeUrl =
          window.location.origin +
          window.location.pathname +
          "/remove/" +
          itemId;
      } else {
        removeUrl =
          window.location.origin +
          "/ecommerce-site/public/index.php/cart/remove/" +
          itemId;
      }

      fetch(removeUrl)
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // Remove the item from display
            const cartItem = this.closest(".d-flex");
            const quantityInput = cartItem
              ? cartItem.querySelector("input[value]")
              : null;
            const quantityToRemove = quantityInput
              ? parseInt(quantityInput.value)
              : 1;

            if (cartItem) {
              cartItem.remove();
            }

            // Update cart count
            const cartCountElement = document.querySelector(".cart-count");
            if (cartCountElement) {
              const currentCount = parseInt(
                cartCountElement.textContent || "0"
              );
              const newCount = Math.max(0, currentCount - quantityToRemove);
              setCartCount(newCount);
            }

            // Check if cart is empty
            const remainingItems = document.querySelectorAll(
              ".d-flex.align-items-start.py-3"
            );
            if (remainingItems.length === 0) {
              const container = document.querySelector(".container");
              if (container) {
                container.innerHTML = `
                                    <div class="alert alert-info text-center py-5">
                                        <h4>Your cart is empty!</h4>
                                        <p><a href="/" class="btn btn-primary mt-3">Continue Shopping</a></p>
                                    </div>`;
              }
            }
          } else {
            alert("Failed to remove item: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });
  });
});

console.log("Cart.js loaded successfully");
