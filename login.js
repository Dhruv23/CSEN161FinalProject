// login.js
document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const errorDiv = document.getElementById('error-message');

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = 'explore.html'; // Redirect on success
        } else {
            errorDiv.textContent = result.message;
            errorDiv.className = 'error-visible';
        }
    } catch (error) {
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.className = 'error-visible';
    }
});