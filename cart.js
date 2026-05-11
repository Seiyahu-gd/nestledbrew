/* =========================================
   NestledBrew — cart.js
   ========================================= */

// Track state so discount doesn't reset on cart reload
let _appliedDiscount = 0;   // ₱ amount currently applied
let _appliedPoints   = 0;   // pts currently applied
let _currentTotal    = 0;   // total (with tax) before discount
let _userPoints      = 0;   // fresh from last getCartItems response

/* ===== ADD TO CART ===== */
async function addToCart(menuItemId) {
    const fd = new FormData();
    fd.append('action', 'add');
    fd.append('menu_item_id', menuItemId);

    try {
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
            showToast('✓ Item added to cart');
            updateCartCount();
        } else {
            showToast('✗ ' + result.message);
        }
    } catch (e) {
        console.error(e);
        showToast('Connection error');
    }
}

/* ===== UPDATE QUANTITY ===== */
async function updateQuantity(menuItemId, newQuantity) {
    const fd = new FormData();
    fd.append('action', 'update');
    fd.append('menu_item_id', menuItemId);
    fd.append('quantity', newQuantity);

    try {
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
            // Reset any applied discount when cart changes — total changed
            resetDiscount();
            loadCart();
        } else {
            showToast('✗ ' + result.message);
        }
    } catch (e) {
        console.error(e);
        showToast('Connection error');
    }
}

/* ===== REMOVE FROM CART ===== */
async function removeFromCart(menuItemId) {
    const fd = new FormData();
    fd.append('action', 'remove');
    fd.append('menu_item_id', menuItemId);

    try {
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
            resetDiscount();
            loadCart();
            showToast('✓ Item removed');
        } else {
            showToast('✗ ' + result.message);
        }
    } catch (e) {
        console.error(e);
        showToast('Connection error');
    }
}

/* ===== LOAD CART ===== */
async function loadCart() {
    const fd = new FormData();
    fd.append('action', 'get');

    try {
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();

        if (result.status === 'success') {
            _userPoints   = result.user_points ?? 0;
            _currentTotal = parseFloat(result.total);
            displayCart(result.items, result.subtotal, result.tax, result.total, result.user_points);
        } else {
            const c = document.getElementById('cartItems');
            if (c) c.innerHTML = '<p style="text-align:center;color:var(--text-muted);padding:40px;">Error loading cart.</p>';
        }
    } catch (e) {
        console.error(e);
    }
}

/* ===== DISPLAY CART ===== */
function displayCart(items, subtotal, tax, total, userPoints) {
    const container        = document.getElementById('cartItems');
    const summaryContainer = document.getElementById('cartSummary');

    if (!container || !summaryContainer) return;

    // ---- Empty state ----
    if (!items || items.length === 0) {
        container.innerHTML = `
            <div style="text-align:center;padding:80px 20px;display:flex;flex-direction:column;align-items:center;gap:16px;">
                <div style="font-size:3rem;">🛒</div>
                <p style="font-family:var(--font-display);font-size:1.3rem;font-style:italic;color:var(--text);">Your cart is empty</p>
                <p style="color:var(--text-muted);font-size:0.9rem;">Add something from the menu to get started.</p>
                <a href="menu.php" class="btn btn-primary" style="margin-top:8px;">Browse Menu</a>
            </div>`;
        summaryContainer.innerHTML = '';
        return;
    }

    // ---- Items ----
    let cartHTML = '';
    items.forEach(item => {
        cartHTML += `
            <div class="cart-item reveal" data-id="${item.menu_item_id}">
                <div class="cart-item-image">
                    <img src="${item.image_path || 'assets/placeholder.png'}" alt="${escHtml(item.name)}" onerror="this.style.display='none'">
                </div>
                <div class="cart-item-content">
                    <h3>${escHtml(item.name)}</h3>
                    <p class="price">₱${parseFloat(item.price).toFixed(2)}</p>
                </div>
                <div class="cart-item-controls">
                    <button class="qty-btn" onclick="updateQuantity(${item.menu_item_id}, ${item.quantity - 1})">−</button>
                    <input type="number" class="qty-input" value="${item.quantity}" min="1"
                        onchange="updateQuantity(${item.menu_item_id}, parseInt(this.value)||1)">
                    <button class="qty-btn" onclick="updateQuantity(${item.menu_item_id}, ${item.quantity + 1})">+</button>
                </div>
                <div class="cart-item-subtotal">
                    <p class="subtotal">₱${parseFloat(item.subtotal).toFixed(2)}</p>
                </div>
                <button class="remove-btn" onclick="removeFromCart(${item.menu_item_id})" title="Remove item">✕</button>
            </div>`;
    });
    container.innerHTML = cartHTML;

    // Trigger scroll-reveal on freshly rendered items
    container.querySelectorAll('.reveal').forEach(el => {
        el.classList.add('revealed');
    });

    // ---- Points hint copy ----
    const maxPossibleDiscount = parseFloat(total);                        // can't discount more than total
    const maxUsablePoints     = Math.min(userPoints, Math.ceil(maxPossibleDiscount / 10 * 100));
    const pointsHint          = userPoints > 0
        ? `You have <strong>${Number(userPoints).toLocaleString()} pts</strong> available &nbsp;·&nbsp; Rate: 100 pts = ₱10 off &nbsp;·&nbsp; Max usable: <strong>${maxUsablePoints} pts</strong>`
        : `You have <strong>0 pts</strong>. <a href="rewards.php" style="color:var(--accent);">Learn how to earn</a>`;

    // ---- Summary ----
    summaryContainer.innerHTML = `
        <h2 style="font-family:var(--font-display);font-size:1.2rem;font-weight:600;font-style:italic;color:var(--text);margin-bottom:20px;">Order Summary</h2>

        <div class="summary-row">
            <span>Subtotal</span>
            <span>₱${parseFloat(subtotal).toFixed(2)}</span>
        </div>
        <div class="summary-row">
            <span>Tax (10%)</span>
            <span>₱${parseFloat(tax).toFixed(2)}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total-row">
            <span>Total</span>
            <span id="totalPrice">₱${parseFloat(total).toFixed(2)}</span>
        </div>

        <!-- BrewPoints Redemption -->
        <div class="points-redeem-box" style="margin-top:24px;">
            <div class="points-redeem-header">
                <span style="font-size:1rem;">✦</span>
                <span style="font-family:var(--font-body);font-size:0.82rem;font-weight:600;color:var(--text);">
                    Redeem BrewPoints
                </span>
            </div>
            <p class="points-redeem-hint" style="font-size:0.78rem;color:var(--text-muted);margin:8px 0 12px;line-height:1.5;">
                ${pointsHint}
            </p>
            <div style="display:flex;gap:8px;align-items:center;">
                <input
                    type="number"
                    id="pointsInput"
                    class="form-input"
                    placeholder="Points to use"
                    min="0"
                    max="${maxUsablePoints}"
                    step="100"
                    ${userPoints === 0 ? 'disabled' : ''}
                    style="flex:1;"
                    oninput="previewDiscount(this.value)"
                >
                <button class="btn btn-primary" onclick="applyPointsDiscount()" ${userPoints === 0 ? 'disabled' : ''}>Apply</button>
            </div>
            <p id="pointsMessage" style="margin-top:8px;font-size:0.82rem;min-height:18px;"></p>
        </div>

        <!-- Discount row (hidden until applied) -->
        <div id="discountSummary" style="display:none;margin-top:8px;">
            <div class="summary-divider"></div>
            <div class="summary-row" style="color:var(--accent);">
                <span>BrewPoints Discount (<span id="discountPtsLabel">0</span> pts)</span>
                <span id="discountAmount">−₱0.00</span>
            </div>
            <div class="summary-row total-row" style="margin-top:8px;">
                <span>Final Total</span>
                <span id="finalTotal">₱${parseFloat(total).toFixed(2)}</span>
            </div>
            <button class="btn" style="width:100%;margin-top:8px;font-size:0.75rem;padding:7px;" onclick="clearDiscount()">
                ✕ Remove discount
            </button>
        </div>

        <button class="btn btn-primary btn-lg" style="width:100%;margin-top:24px;" onclick="proceedToCheckout()">
            Proceed to Checkout
        </button>
        <a href="menu.php" class="btn" style="width:100%;margin-top:12px;text-align:center;display:block;">
            Continue Shopping
        </a>
    `;

    // Re-apply any discount that was active before a reload
    if (_appliedDiscount > 0) {
        _showDiscountRow(_appliedPoints, _appliedDiscount, parseFloat(total));
    }
}

/* ===== LIVE PREVIEW while typing ===== */
function previewDiscount(val) {
    const pts = parseInt(val) || 0;
    const msg = document.getElementById('pointsMessage');
    if (!msg) return;
    if (pts <= 0) { msg.textContent = ''; msg.style.color = ''; return; }
    if (pts > _userPoints) {
        msg.textContent = `You only have ${_userPoints.toLocaleString()} pts.`;
        msg.style.color = '#D9534F';
        return;
    }
    const preview = Math.min((_userPoints > 0 ? pts / 100 * 10 : 0), _currentTotal);
    msg.textContent = `Preview: ${pts} pts = ₱${preview.toFixed(2)} off`;
    msg.style.color = 'var(--accent)';
}

/* ===== APPLY DISCOUNT ===== */
async function applyPointsDiscount() {
    const input = document.getElementById('pointsInput');
    const points = parseInt(input?.value) || 0;

    if (points <= 0) { showToast('Enter the number of points to use.'); return; }

    const fd = new FormData();
    fd.append('action',     'validate_points');
    fd.append('points',     points);
    fd.append('cart_total', _currentTotal);   // let server cap the discount

    try {
        const res    = await fetch('cart-handler.php', { method: 'POST', body: fd });
        const result = await res.json();
        const msg    = document.getElementById('pointsMessage');

        if (result.status === 'success') {
            _appliedDiscount = result.discount_amount;
            _appliedPoints   = result.points_used;

            if (msg) { msg.textContent = result.message; msg.style.color = 'var(--accent)'; }
            _showDiscountRow(result.points_used, result.discount_amount, _currentTotal);
            showToast(`✓ ${result.points_used} BrewPoints applied — ₱${result.discount_amount.toFixed(2)} off!`);
        } else {
            if (msg) { msg.textContent = result.message; msg.style.color = '#D9534F'; }
            showToast('✗ ' + result.message);
        }
    } catch (e) {
        console.error(e);
        showToast('Connection error');
    }
}

/* ===== SHOW DISCOUNT ROW ===== */
function _showDiscountRow(pts, discount, baseTotal) {
    const discountEl = document.getElementById('discountSummary');
    const amountEl   = document.getElementById('discountAmount');
    const finalEl    = document.getElementById('finalTotal');
    const ptsLabel   = document.getElementById('discountPtsLabel');
    if (!discountEl || !amountEl || !finalEl) return;

    const finalTotal = Math.max(baseTotal - discount, 0);
    ptsLabel.textContent  = pts.toLocaleString();
    amountEl.textContent  = `−₱${discount.toFixed(2)}`;
    finalEl.textContent   = `₱${finalTotal.toFixed(2)}`;
    discountEl.style.display = 'block';
}

/* ===== CLEAR / REMOVE DISCOUNT ===== */
function clearDiscount() {
    resetDiscount();
    const input = document.getElementById('pointsInput');
    const msg   = document.getElementById('pointsMessage');
    if (input) input.value = '';
    if (msg)   { msg.textContent = ''; msg.style.color = ''; }
    showToast('Discount removed.');
}

function resetDiscount() {
    _appliedDiscount = 0;
    _appliedPoints   = 0;
    const discountEl = document.getElementById('discountSummary');
    if (discountEl) discountEl.style.display = 'none';
}

/* ===== CHECKOUT ===== */
function proceedToCheckout() {
    if (_appliedPoints > 0) {
        // Pass applied points to checkout page via sessionStorage
        sessionStorage.setItem('checkout_points',   _appliedPoints);
        sessionStorage.setItem('checkout_discount',  _appliedDiscount.toFixed(2));
    } else {
        sessionStorage.removeItem('checkout_points');
        sessionStorage.removeItem('checkout_discount');
    }
    window.location.href = 'checkout.php';
}

/* ===== CART BADGE COUNT ===== */
function updateCartCount() {
    // If you have a cart count badge in the nav, update it here
    // e.g. document.getElementById('cartBadge').textContent = count;
}

/* ===== HELPER ===== */
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// Load on page ready
document.addEventListener('DOMContentLoaded', loadCart);