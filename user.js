// user.js
document.addEventListener('DOMContentLoaded', async function () {
    const welcomeEl = document.getElementById('welcome-user');
    if (!welcomeEl) return;

    try {
        const response = await fetch('user.php');
        const result = await response.json();

        if (result.logged_in && result.full_name) {
            welcomeEl.textContent = 'welcome ' + result.full_name;
            welcomeEl.classList.remove('hidden');
        }
    } catch (error) {
        // Server unavailable or user not logged in
    }
});
