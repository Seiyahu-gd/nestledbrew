// Function to fetch and display the user's current points
async function loadUserData() {
    try {
        const fd = new FormData();
        fd.append('action', 'get_points');

        const response = await fetch('rewards_action.php', { method: 'POST', body: fd });
        const data = await response.json();

        if (data && data.status === 'success' && data.points !== undefined) {
            // Rewards page markup uses .rewards-card-pts-label for the number
            const pointsDisplay = document.querySelector('.rewards-card-pts-label');
            if (pointsDisplay) pointsDisplay.textContent = data.points.toLocaleString();

            // If you add a tier badge later, this selector can be updated
            const tierDisplay = document.querySelector('.tier-badge');
            if (tierDisplay) tierDisplay.textContent = data.tier;
        }
    } catch (error) {
        console.error('Error loading rewards:', error);
    }
}


// Update your claimReward function to deduct points in the DB
async function claimReward(drinkName, cost) {
    const formData = new FormData();
    formData.append('action', 'claim');
    formData.append('cost', cost);

    try {
        const response = await fetch('rewards_action.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            showToast(`Redeemed! Enjoy your free ${drinkName}.`);
            // Update the UI with new point total
            document.querySelector('.points-value').textContent = result.new_points.toLocaleString();
        } else {
            showToast(result.message);
        }
    } catch (error) {
        showToast('Could not process redemption.');
    }
}

// Run this automatically when the script loads
document.addEventListener('DOMContentLoaded', loadUserData);