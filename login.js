// login.js
const loginForm = document.getElementById('login-form');
const errorDiv = document.getElementById('error-message');
const forgotPassLink = document.getElementById('forgot-pass-link');
const resetModal = document.getElementById('reset-modal');
const resetForm = document.getElementById('reset-form');
const resetCancel = document.getElementById('reset-cancel');
const resetError = document.getElementById('reset-error');
const resetSuccess = document.getElementById('reset-success');

loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = 'explore.html';
        } else {
            errorDiv.textContent = result.message;
            errorDiv.className = 'error-visible';
        }
    } catch (error) {
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.className = 'error-visible';
    }
});

forgotPassLink.addEventListener('click', function (e) {
    e.preventDefault();
    resetForm.reset();
    resetError.className = 'error-hidden';
    resetError.textContent = '';
    resetSuccess.className = 'success-hidden';
    resetSuccess.textContent = '';
    resetModal.classList.remove('hidden');
});

resetCancel.addEventListener('click', function () {
    resetModal.classList.add('hidden');
});

resetModal.addEventListener('click', function (e) {
    if (e.target === resetModal) {
        resetModal.classList.add('hidden');
    }
});

resetForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');

    resetError.className = 'error-hidden';
    resetSuccess.className = 'success-hidden';

    if (password !== confirmPassword) {
        resetError.textContent = 'Passwords do not match.';
        resetError.className = 'error-visible';
        return;
    }

    try {
        const response = await fetch('reset_password.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            resetSuccess.textContent = result.message;
            resetSuccess.className = 'success-visible';
            resetForm.reset();

            setTimeout(() => {
                resetModal.classList.add('hidden');
            }, 2000);
        } else {
            resetError.textContent = result.message;
            resetError.className = 'error-visible';
        }
    } catch (error) {
        resetError.textContent = 'An error occurred. Please try again.';
        resetError.className = 'error-visible';
    }
});
