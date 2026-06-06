// user.js
document.addEventListener('DOMContentLoaded', async function () {
    const welcomeEl = document.getElementById('welcome-user');
    const authLinksEl = document.querySelector('.auth-links'); // Select the login/signup container

    try {
        const response = await fetch('user.php');
        const result = await response.json();

        if (result.logged_in && result.full_name) {
            // Show welcome message
            if (welcomeEl) {
                welcomeEl.textContent = 'welcome ' + result.full_name;
                welcomeEl.classList.remove('hidden');
            }

            // Replace "Log in" and "Sign up" with a "Log out" button
            if (authLinksEl) {
                authLinksEl.innerHTML = '<a href="logout.php" class="btn-nav outline">Log out</a>';
            }
        }
    } catch (error) {
        // Server unavailable or user not logged in
        console.error("Authentication check failed:", error);
    }
});