// Real-time form validation
document.addEventListener('DOMContentLoaded', function() {
    // Email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                this.classList.add('invalid');
                if (this.nextElementSibling) {
                    this.nextElementSibling.textContent = 'Please enter a valid email';
                }
            } else {
                this.classList.remove('invalid');
                if (this.nextElementSibling) {
                    this.nextElementSibling.textContent = '';
                }
            }
        });
    });

    // Password validation
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const errorElement = this.nextElementSibling;
            if (!errorElement) return;
            
            if (this.value.length < 8) {
                errorElement.textContent = 'Password must be at least 8 characters';
                this.classList.add('invalid');
            } else if (!/[A-Z]/.test(this.value)) {
                errorElement.textContent = 'Password must contain at least one uppercase letter';
                this.classList.add('invalid');
            } else if (!/[a-z]/.test(this.value)) {
                errorElement.textContent = 'Password must contain at least one lowercase letter';
                this.classList.add('invalid');
            } else if (!/[0-9]/.test(this.value)) {
                errorElement.textContent = 'Password must contain at least one number';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });
    });

    // Confirm password validation
    const confirmPasswordInputs = document.querySelectorAll('input[name="confirm_password"]');
    confirmPasswordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const password = document.querySelector('input[name="password"]');
            const errorElement = this.nextElementSibling;
            
            if (this.value !== password.value) {
                errorElement.textContent = 'Passwords do not match';
                this.classList.add('invalid');
            } else {
                errorElement.textContent = '';
                this.classList.remove('invalid');
            }
        });
    });
});