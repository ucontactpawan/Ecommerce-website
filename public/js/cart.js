function setCartCount(count) {
  const cartCountElement = document.querySelector(".cart-count");
  if (!cartCountElement) return;

  console.log("Setting cart count to:", count); // Debug log

  if (count > 0) {
    cartCountElement.textContent = count;
    cartCountElement.style.display = "inline-block";
  } else {
    cartCountElement.style.display = "none";
  }
}

function updateCartCount(forceValue = null) {
  const cartCountElement = document.querySelector(".cart-count");
  if (!cartCountElement) {
    console.log("Cart count element not found");
    return;
  }

  if (forceValue !== null) {
    setCartCount(forceValue);
    return;
  }

  console.log("Fetching cart count from:", `${window.siteUrl}/cart/count`);
  fetch(`${window.siteUrl}/cart/count`)
    .then((response) => {
      console.log("Cart count response status:", response.status);
      if (!response.ok)
        throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then((data) => {
      console.log("Cart count response:", data); // Debug log
      setCartCount(parseInt(data.count || 0));
    })
    .catch((error) => {
      console.error("Error updating cart count:", error);
      cartCountElement.style.display = "none";
    });
}

// Test function to manually trigger cart count update
window.testCartCount = function () {
  console.log("Testing cart count update...");
  updateCartCount();
};

// Make updateCartCount globally available for testing
window.updateCartCount = updateCartCount;

document.addEventListener("DOMContentLoaded", () => {
  console.log(
    "Cart.js loaded. siteUrl:",
    typeof siteUrl !== "undefined" ? siteUrl : "undefined"
  ); // Debug log

  // Ensure siteUrl is defined
  if (typeof siteUrl === "undefined") {
    console.error("siteUrl is not defined!");
    window.siteUrl =
      window.location.origin + "/ecommerce-site/public/index.php";
    console.log("Using fallback siteUrl:", window.siteUrl);
  } else {
    window.siteUrl = siteUrl;
  }

  updateCartCount(); // Initialize count on page load

  // Add to Cart
  document.querySelectorAll(".add-to-cart-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const button = form.querySelector('button[type="submit"]');
      const productId = form.querySelector('input[name="product_id"]').value;

      console.log("Form submission intercepted for product:", productId); // Debug log

      button.disabled = true;
      button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...`;

      // Create FormData to include CSRF token
      const formData = new FormData(form);

      fetch(`${window.siteUrl}/cart/add`, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
      })
        .then((response) => {
          console.log("Add to cart response status:", response.status);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          console.log("Add to cart response:", data); // Debug log
          if (data.success) {
            button.classList.remove("btn-primary");
            button.classList.add("btn-success");
            button.innerHTML = '<i class="bi bi-check2"></i> Added';

            // Update cart count by fetching the real count from server
            console.log("Updating cart count..."); // Debug log
            // Add a small delay to ensure database operation is complete
            setTimeout(() => {
              updateCartCount();
            }, 100);

            setTimeout(() => {
              button.classList.remove("btn-success");
              button.classList.add("btn-primary");
              button.innerHTML = "Add to Cart";
              button.disabled = false;
            }, 2000);
          } else {
            button.innerHTML = "Add to Cart";
            button.disabled = false;
            alert("Failed to add item: " + (data.message || "Unknown error"));
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          button.innerHTML = "Add to Cart";
          button.disabled = false;
          alert("Error adding to cart: " + error.message);
        });
    });
  });

  // Update Quantity
  document.querySelectorAll(".change-quantity").forEach((btn) => {
    btn.addEventListener("click", function () {
      const action = this.getAttribute("data-action");
      const itemId = this.getAttribute("data-id");
      const input = this.closest(".d-flex").querySelector("input");
      let quantity = parseInt(input.value);
      const oldQuantity = quantity;

      if (action === "increase") quantity++;
      else if (action === "decrease" && quantity > 1) quantity--;
      else return;

      input.value = quantity;

      fetch(
        `${
          window.siteUrl || siteUrl
        }/cart/updateQuantity/${itemId}/${quantity}`,
        {
          method: "POST",
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const cartCountElement = document.querySelector(".cart-count");
            if (cartCountElement) {
              const currentCount = parseInt(
                cartCountElement.textContent || "0"
              );
              const newCount = currentCount + (quantity - oldQuantity);
              setCartCount(newCount);
            }

            // Update subtotal
            const priceElement = input
              .closest(".d-flex")
              .parentElement.querySelector(".text-danger");
            if (priceElement) {
              const unitPrice = parseFloat(
                priceElement.textContent.replace("₹", "")
              );
              const totalPrice = (unitPrice * quantity).toFixed(2);
              const totalElement = priceElement.nextElementSibling;
              if (totalElement && totalElement.textContent.includes("Total")) {
                totalElement.textContent = `Total: ₹${totalPrice}`;
              }
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

  // Remove from Cart
  document.querySelectorAll(".remove-item").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const itemId = this.getAttribute("data-id");
      const cartItem = btn.closest(".d-flex");
      const quantityInput = cartItem
        ? cartItem.querySelector("input[value]")
        : null;
      const quantityToRemove = quantityInput
        ? parseInt(quantityInput.value)
        : 1;

      fetch(`${window.siteUrl || siteUrl}/cart/remove/${itemId}`)
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            const cartCountElement = document.querySelector(".cart-count");
            if (cartCountElement) {
              const currentCount = parseInt(
                cartCountElement.textContent || "0"
              );
              const newCount = Math.max(0, currentCount - quantityToRemove);
              setCartCount(newCount);
            }

            if (cartItem) {
              cartItem.remove();
              const cartItems = document.querySelectorAll(
                ".d-flex.align-items-start.py-3"
              );
              if (cartItems.length === 0) {
                const container = document.querySelector(".container");
                if (container) {
                  container.innerHTML = `
                                        <div class="alert alert-info text-center py-5">
                                            <h4>Your cart is empty!</h4>
                                            <p><a href="${
                                              window.siteUrl || siteUrl
                                            }" class="btn btn-primary mt-3">Continue Shopping</a></p>
                                        </div>`;
                }
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
