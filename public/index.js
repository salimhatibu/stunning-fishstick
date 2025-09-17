// Form validation and enhancement
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (username.length === 0) {
        e.preventDefault();
        showError('Please enter your full name.');
        return;
    }
    
    if (username.length > 100) {
        e.preventDefault();
        showError('Name must be less than 100 characters.');
        return;
    }
    
    if (email.length === 0) {
        e.preventDefault();
        showError('Please enter your email address.');
        return;
    }
    
    if (!isValidEmail(email)) {
        e.preventDefault();
        showError('Please enter a valid email address.');
        return;
    }
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(message) {
    // Remove existing error messages
    const existingError = document.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Insert before the form
    const form = document.getElementById('signupForm');
    form.parentNode.insertBefore(errorDiv, form);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Add loading state to button
document.getElementById('signupForm').addEventListener('submit', function() {
    const btn = this.querySelector('.btn');
    btn.textContent = 'Creating Account...';
    btn.disabled = true;
});