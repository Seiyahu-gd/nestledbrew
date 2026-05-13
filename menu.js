function addToCart(itemId) {
    const fd = new FormData();
    fd.append('action', 'add');
    fd.append('menu_item_id', itemId);

    fetch('cart-handler.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(result => {
            if (result.status === 'success') {
                showToast('✓ Item added to cart');
            } else if (result.status === 'error' && result.message === 'Not authenticated') {
                showToast('Please sign in to add items to your cart.');
                setTimeout(() => { window.location.href = 'login.php'; }, 1500);
            } else {
                showToast('✗ ' + result.message);
            }
        })
        .catch(() => showToast('Connection error'));
}

function showQtyControl(itemId) {
    const wrapper = document.getElementById('qty-' + itemId);
    wrapper.innerHTML = `
        <button class="qty-btn" onclick="changeQty(${itemId}, -1)">−</button>
        <span class="qty-count" id="count-${itemId}">1</span>
        <button class="qty-btn" onclick="changeQty(${itemId}, 1)">+</button>
        <button class="qty-confirm" onclick="confirmAdd(${itemId})">Add to Cart</button>
    `;
}

function changeQty(itemId, delta) {
    const counter = document.getElementById('count-' + itemId);
    let val = parseInt(counter.textContent) + delta;
    if (val < 1) {
        // Reset back to original button
        const wrapper = document.getElementById('qty-' + itemId);
        wrapper.innerHTML = `<button class="add-btn" onclick="showQtyControl(${itemId})">+ Add</button>`;
        return;
    }
    counter.textContent = val;
}

function confirmAdd(itemId) {
    const qty = parseInt(document.getElementById('count-' + itemId).textContent);
    const fd = new FormData();
    fd.append('action', 'add');
    fd.append('menu_item_id', itemId);
    fd.append('quantity', qty);

    fetch('cart-handler.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(result => {
            if (result.status === 'success') {
                showToast('✓ Item added to cart');
                // Reset back to original button
                const wrapper = document.getElementById('qty-' + itemId);
                wrapper.innerHTML = `<button class="add-btn" onclick="showQtyControl(${itemId})">+ Add</button>`;
            } else if (result.message === 'Not authenticated') {
                showToast('Please sign in to add items to your cart.');
                setTimeout(() => { window.location.href = 'login.php'; }, 1500);
            } else {
                showToast('✗ ' + result.message);
            }
        })
        .catch(() => showToast('Connection error'));
}