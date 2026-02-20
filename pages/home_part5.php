<script>
    // Sticky Header logic (throttled)
    (function() {
        const header = document.querySelector('.header-area');
        if (!header) return;
        let lastY = 0;
        let ticking = false;
        function updateHeader(y) {
            const shouldStick = y > 100;
            const hasClass = header.classList.contains('header-sticky');
            if (shouldStick && !hasClass) header.classList.add('header-sticky');
            else if (!shouldStick && hasClass) header.classList.remove('header-sticky');
        }
        window.addEventListener('scroll', function() {
            lastY = window.scrollY || window.pageYOffset;
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateHeader(lastY);
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
        updateHeader(window.scrollY || window.pageYOffset);
    })();

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const productName = this.getAttribute('data-product-name');
            const originalText = this.innerHTML;
            if (form) {
                e.preventDefault();
                const fd = new FormData(form);
                fd.set('add_to_cart', '1');
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                this.disabled = true;
                fetch('index.php?page=cart_add', {
                    method: 'POST',
                    body: fd,
                    headers: { 'Accept': 'application/json' }
                }).then(r => r.json()).then(data => {
                    if (data && data.ok) {
                        const cartBadge = document.querySelector('.badge.bg-primary') || createCartBadge();
                        cartBadge.textContent = parseInt(data.cart_count ?? '0');
                        showToast(`${productName} added to cart!`);
                    } else {
                        showToast(data?.error || 'Unable to add to cart.');
                    }
                }).catch(() => {
                    showToast('Network error. Please try again.');
                }).finally(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            }
        });
    });

    function createCartBadge() {
        const badge = document.createElement('span');
        badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary';
        document.querySelector('a[href*="cart"]').appendChild(badge);
        return badge;
    }

    function showToast(message) {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        const wrapper = document.createElement('div');
        wrapper.innerHTML = toastHtml;
        const toastEl = wrapper.firstElementChild;
        toastContainer.appendChild(toastEl);
        
        const bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        bsToast.show();
        
        toastEl.addEventListener('hidden.bs.toast', function () {
            toastEl.remove();
        });
    }

    // Newsletter handling
    document.getElementById('newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = this.querySelector('input[type="email"]');
        const button = this.querySelector('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        setTimeout(() => {
            showToast('Thank you for subscribing!');
            emailInput.value = '';
            button.innerHTML = originalText;
            button.disabled = false;
        }, 1000);
    });
</script>
