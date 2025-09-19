
// ===== MOBILE MENU TOGGLE =====
document.addEventListener('DOMContentLoaded', function() {
    const menuIcon = document.querySelector("#menu-icon");
    const navbar = document.querySelector(".navbar");

    // Toggle navbar visibility
    if (menuIcon && navbar) {
        menuIcon.addEventListener("click", () => {
            navbar.classList.toggle("active");
        });

        // Close menu when clicking any link
        document.querySelectorAll(".navbar a").forEach(link => {
            link.addEventListener("click", () => {
                navbar.classList.remove("active");
            });
        });

        // Close menu when clicking outside
        document.addEventListener("click", (e) => {
            if (!navbar.contains(e.target) && !menuIcon.contains(e.target)) {
                navbar.classList.remove("active");
            }
        });
    }

    // ===== SMOOTH SCROLLING =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ===== HEADER SCROLL EFFECT ===== (REMOVED)
    // Glassmorphism scroll effect has been removed per user request
});

// ===== CART FUNCTIONALITY =====
function addToCart(productId, productName) {
    // Determine the correct API path based on current page location
    const apiPath = window.location.pathname.includes('/pages/') || window.location.pathname.includes('/auth/') 
        ? '../api/add-to-cart.php' 
        : 'api/add-to-cart.php';
    
    // Send AJAX request to add item to cart
    fetch(apiPath, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            updateCartCount(data.cart_count);
            
            // Show success message
            showNotification(`${productName} added to cart!`, 'success');
        } else {
            showNotification('Error adding item to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding item to cart', 'error');
    });
}

function updateCartCount(count) {
    const cartBtn = document.querySelector('#cart-button') || document.querySelector('.cart-btn');
    if (!cartBtn) return;
    
    const existingCount = cartBtn.querySelector('.cart-count');
    
    if (count > 0) {
        if (existingCount) {
            existingCount.textContent = count;
            // Trigger bounce animation
            existingCount.style.animation = 'none';
            setTimeout(() => {
                existingCount.style.animation = 'cartBounce 0.3s ease';
            }, 10);
        } else {
            const countSpan = document.createElement('span');
            countSpan.className = 'cart-count';
            countSpan.textContent = count;
            cartBtn.appendChild(countSpan);
        }
    } else {
        if (existingCount) {
            existingCount.remove();
        }
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide and remove notification
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
