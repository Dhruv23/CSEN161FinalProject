// register.js
document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const errorDiv = document.getElementById('error-message');
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');

    if (password !== confirmPassword) {
        errorDiv.textContent = 'Passwords do not match.';
        errorDiv.className = 'error-visible';
        return;
    }

    try {
        const response = await fetch('register.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        let result;

        try {
            result = JSON.parse(text);
        } catch (parseError) {
            errorDiv.textContent = 'Server error. Run the site with PHP (e.g. php -S localhost:8000) and visit register.html through that server.';
            errorDiv.className = 'error-visible';
            return;
        }

        if (result.success) {
            window.location.href = 'login.html';
        } else {
            errorDiv.textContent = result.message;
            errorDiv.className = 'error-visible';
        }
    } catch (error) {
        errorDiv.textContent = 'Could not reach the server. Open this page through a PHP server, not as a local file.';
        errorDiv.className = 'error-visible';
    }
});
