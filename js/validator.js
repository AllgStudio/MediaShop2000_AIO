// Function to validate form inputs
function validateForm(event) {
    event.preventDefault(); // Prevent the form from submitting

    // Fetch form inputs
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    // Reset error messages
    resetErrors();

    // Validate each input
    let isValid = true;

    if (username === '') {
        isValid = false;
        setError('usernameError', 'Username is required');
    }

    if (email === '') {
        isValid = false;
        setError('emailError', 'Email is required');
    } else if (!isValidEmail(email)) {
        isValid = false;
        setError('emailError', 'Invalid email format');
    }

    if (password === '') {
        isValid = false;
        setError('passwordError', 'Password is required');
    }

    if (confirmPassword === '') {
        isValid = false;
        setError('confirmPasswordError', 'Please confirm your password');
    } else if (password !== confirmPassword) {
        isValid = false;
        setError('confirmPasswordError', 'Passwords do not match');
    }

    // If form is valid, submit it
    if (isValid) {
        alert('Form submitted successfully!');
        document.getElementById('registrationForm').reset();
    }
}

// Function to display error message
function setError(id, errorMessage) {
    const errorElement = document.getElementById(id);
    errorElement.textContent = errorMessage;
}

// Function to reset all error messages
function resetErrors() {
    document.getElementById('usernameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('confirmPasswordError').textContent = '';
}

// Function to validate email format
function isValidEmail(email) {
    // Simple email format validation using regular expression
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
