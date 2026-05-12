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