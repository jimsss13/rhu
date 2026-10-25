// Function to check if user already exists
function checkUserExists(username, email) {
    const users = JSON.parse(localStorage.getItem('registeredUsers')) || [];
    return users.some(user => user.username === username || user.email === email);
}

// Function to show error message
function showError(message) {
    const errorMessage = document.getElementById('error-message');
    const form = document.getElementById('registration-form');
    
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    form.classList.add('error-shake');
    
    // Remove shake animation after it completes
    setTimeout(() => {
        form.classList.remove('error-shake');
    }, 500);
}

// Function to redirect to login page
function redirectToLogin() {
    setTimeout(() => {
        window.location.href = 'login1.html';
    }, 2000);
}

// Function to save user data
function saveUser(userData) {
    const users = JSON.parse(localStorage.getItem('registeredUsers')) || [];
    users.push(userData);
    localStorage.setItem('registeredUsers', JSON.stringify(users));
}

document.getElementById('registration-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    // Check if passwords match
    if (password !== confirmPassword) {
        showError('Passwords do not match!');
        return;
    }

    // Check if user already exists
    if (checkUserExists(username, email)) {
        showError('You already have an account. Redirecting to login page...');
        redirectToLogin();
        return;
    }

    // If all checks pass, save the user data
    const userData = {
        firstName: document.getElementById('first-name').value,
        middleInitial: document.getElementById('middle-initial').value,
        lastName: document.getElementById('last-name').value,
        dateOfBirth: document.getElementById('dob').value,
        gender: document.querySelector('input[name="gender"]:checked').value,
        username: username,
        email: email,
        password: password
    };

    saveUser(userData);

    // Show success message and hide form
    var registrationForm = document.querySelector('.form-container');
    var successMessage = document.getElementById('success-message');
    
    registrationForm.style.opacity = '0';
    setTimeout(function() {
        registrationForm.style.display = 'none';
        successMessage.classList.add('visible');
    }, 300);
});

// Clear error message when user starts typing
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', () => {
        document.getElementById('error-message').style.display = 'none';
    });
});