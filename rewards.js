// Function to fetch and display the user's current points
async function loadUserData() {
    try {
        const response = await fetch('rewards_action.php?action=get_points');
        const data = await response.json();

        if (data.points !== undefined) {
            // Updates the large number in your .points-card
            const pointsDisplay = document.querySelector('.points-value');
            if (pointsDisplay) pointsDisplay.textContent = data.points.toLocaleString();
            
            // Updates the Tier name (e.g., Bean Starter)
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